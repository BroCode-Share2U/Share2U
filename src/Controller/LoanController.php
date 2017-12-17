<?php

namespace Controller;

use Model\Item;
use Model\User;
use Model\Loan;
use Silex\Application;
use Form\LoanForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LoanController extends Controller
{
    public function requestAction(Request $request, Application $app, $itemId)
    {
        // Get serivces
        $entityManager = $this->getEntityManager($app);
        $formFactory = $this->getFormFactory($app);

        // Create empty user
        $loan = new Loan();

        // Create the loan form
        $loanForm = $formFactory->create(LoanForm::class, $loan, ['standalone' => true]);

        //
        $loanForm->handleRequest($request);

        // Find the item with the param in url
        $item = $entityManager->getRepository(Item::class)->find($itemId);
        if ($item === null){
            throw new NotFoundHttpException('item not found');
        }
        // Set the item in the loan
        $loan->setItem($item);
        // Set the status
        $loan->setStatus(Loan::STATUS_REQUESTED);

        // Set the borrower
        $borrower = $this->getAuthorizedUser($app);
        $loan->setBorrower($borrower);

        // Persist the loan and send the eamil to the owner
        if ($loanForm->isSubmitted() && $loanForm->isValid()) {
            $entityManager->persist($loan);
            $entityManager->flush();
            // Send email request
            $this->sendRequestMessage($app, $loan);
            // Redidect to the dashboard
            return $app->redirect($app['url_generator']->generate('dashboard'));
        }

        return $app['twig']->render('request.html.twig',
            [
                'item' => $item,
                'requestForm' => $loanForm->createView()
            ]);
    }

    public function acceptAction(Request $request, Application $app, $loanId)
    {
        $entityManager = self::getEntityManager($app);
        $user = self::getAuthorizedUser($app);
        $loanRepo = $entityManager->getRepository(Loan::class);
        $loan = $loanRepo->find($loanId);

        if ($loan !== null){
            $loanStatusOk = $loan->getStatus() ===  Loan::STATUS_REQUESTED;
            $ownerOk = $loan->getItem()->getOwner() === $user;

            if ($loanStatusOk && $ownerOk ){
                $loanRepo->patchLoanStatus($loan, Loan::STATUS_IN_PROGRESS);
                return $app->json(
                    [
                        'code' => 1,
                        'message' => 'status changed'
                    ]
                );
            }
        }
        return $app->json(
            [
                'code' => 0,
                'message' => 'Loan or User invalid'
            ]
        );
    }

    public function rejectAction(Request $request, Application $app, $loanId)
    {
        $entityManager = self::getEntityManager($app);
        $user = self::getAuthorizedUser($app);
        $loanRepo = $entityManager->getRepository(Loan::class);
        $loan = $loanRepo->find($loanId);

        if ($loan !== null){
            $loanStatusOk = $loan->getStatus() ===  Loan::STATUS_REQUESTED;
            $ownerOk = $loan->getItem()->getOwner() === $user;

            if ($loanStatusOk && $ownerOk ){
                $loanRepo->patchLoanStatus($loan, Loan::STATUS_DENIED);
                return $app->json(
                    [
                        'code' => 1,
                        'message' => 'status changed'
                    ]
                );
            }
        }
        return $app->json(
            [
                'code' => 0,
                'message' => 'Loan or User invalid'
            ]
        );
    }

    public function closeAction(Request $request, Application $app, $loanId)
    {
        $entityManager = self::getEntityManager($app);
        $user = self::getAuthorizedUser($app);
        $loanRepo = $entityManager->getRepository(Loan::class);
        $loan = $loanRepo->find($loanId);

        if ($loan !== null){
            $loanStatusOk = $loan->getStatus() ===  Loan::STATUS_IN_PROGRESS;
            $ownerOk = $loan->getItem()->getOwner() === $user;

            if ($loanStatusOk && $ownerOk ){
                $loanRepo->patchLoanStatus($loan, Loan::STATUS_CLOSED);
                return $app->json(
                    [
                        'code' => 1,
                        'message' => 'status changed'
                    ]
                );
            }
        }
        return $app->json(
            [
                'code' => 0,
                'message' => 'Loan or User invalid'
            ]
        );
    }

    public function cancelAction(Request $request, Application $app, $loanId)
    {
        $entityManager = self::getEntityManager($app);
        $user = self::getAuthorizedUser($app);
        $loanRepo = $entityManager->getRepository(Loan::class);
        $loan = $loanRepo->find($loanId);

        if ($loan !== null){
            $loanStatusOk = $loan->getStatus() ===  Loan::STATUS_REQUESTED;
            $ownerOk = $loan->getBorrower() === $user;

            if ($loanStatusOk && $ownerOk ){
                $loanRepo->patchLoanStatus($loan, Loan::STATUS_CANCELLED);
                return $app->json(
                    [
                        'code' => 1,
                        'message' => 'status changed'
                    ]
                );
            }
        }
        return $app->json(
            [
                'code' => 0,
                'message' => 'Loan or User invalid'
            ]
        );
    }

    public function sendRequestMessage(Application $app, Loan $loan)
    {
        $ownerEmail = $loan->getItem()->getOwner()->getEmail();
        $borrowerEmail = $loan->getBorrower()->getEmail();

        $message = new \Swift_Message();
            $message->setSubject('[Share2U] Request loan')
                ->setFrom([$borrowerEmail])
                ->setTo([$ownerEmail])
                ->setBody($app['twig']->render('mail/requestMail.html.twig',
                    [
                        'message' => $loan->getRequestMessage(),
                        'borrower' => $loan->getBorrower()->getLastname(),
                        'borrowerEmail' => $borrowerEmail
                    ]),
                    'text/html'
                );
            $app['mailer']->send($message);
    }

}