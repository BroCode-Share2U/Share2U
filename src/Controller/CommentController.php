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

        $user = self::getAuthorizedUser($app);

        $comment = new Comment();
        $commentForm = $formFactory->create(CommentForm::class, $comment, ['standalone' => true]);
        $commentForm->handleRequest($request);

        $loan = $loanRepo->find($loanId);
        $borrower = $loan->getborrower();
        $owner = $loan->getItem()->getOwner();

        if ($loan === null || $loan->getStatus() !== Loan::STATUS_CLOSED){
            throw new NotFoundHttpException('loan not found');
        }

        $isBorrower = $borrower->getId() === $user->getId() ;
        $isLoaner = $owner->getId() === $user->getId() ;

        var_dump($isBorrower);
        var_dump($isLoaner);

        if (!$isBorrower && !$isLoaner){
            throw new NotFoundHttpException('user invalid');
        }

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            if ($isLoaner){
                $comment->setUser($borrower);
            }
            if ($isBorrower){
                $comment->setUser($owner);
            }
            $comment->setAuthor($user);
            $now = new \DateTime();
            $comment->setInsertedAt($now);
            $comment->setUpdatedAt($now);
            $comment->setLoan($loan);

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