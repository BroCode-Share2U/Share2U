<?php

namespace Controller;

use Form\CommentForm;
use Model\Comment;
use Model\Loan;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends Controller
{
    public function commentAction(Request $request, Application $app, $loanId)
    {
        // Get serivces
        $entityManager = self::getEntityManager($app);
        $formFactory = self::getFormFactory($app);
        $loanRepo = $entityManager->getRepository(Loan::class);

        // Get user
        $user = self::getAuthorizedUser($app);

        // Create the form comment
        $comment = new Comment();
        $commentForm = $formFactory->create(CommentForm::class, $comment, ['standalone' => true]);
        $commentForm->handleRequest($request);

        // find the loan
        $loan = $loanRepo->find($loanId);

        // get the loaner & borrower
        $borrower = $loan->getborrower();
        $owner = $loan->getItem()->getOwner();

        // if loan null redirect not found
        if ($loan === null || $loan->getStatus() !== Loan::STATUS_CLOSED)
        {
            throw new NotFoundHttpException('loan not found');
        }

        // defined if is the borrower or the loaner
        $isBorrower = $borrower->getId() === $user->getId() ;
        $isLoaner = $owner->getId() === $user->getId() ;

        // if isn't loaner or borrower not found
        if (!$isBorrower && !$isLoaner)
        {
            throw new NotFoundHttpException('user invalid');
        }

        // Form submit and valid
        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            // Set the user comment
            if ($isLoaner){
                $comment->setUser($borrower);
            }
            if ($isBorrower){
                $comment->setUser($owner);
            }
            // Set the author
            $comment->setAuthor($user);
            $now = new \DateTime();
            $comment->setInsertedAt($now);
            $comment->setUpdatedAt($now);
            $comment->setLoan($loan);

            // Push on db
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redidect to the dashboard
            return $app->redirect($app['url_generator']->generate('dashboard'));
        }

        return $app['twig']->render('comment.html.twig',
            [
                'isBorrower' => $isBorrower,
                'isLoaner' => $isLoaner,
                'loan' => $loan->toArray(),
                'commentForm' => $commentForm->createView()
            ]
        );
    }
}