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
        $entityManager = self::getEntityManager($app);
        $formFactory = self::getFormFactory($app);

        // Find the item with the param in url
        $item = $entityManager->getRepository(Item::class)->find($itemId);
        if ($item === null){
            throw new NotFoundHttpException('item not found');
        }

        // Item in loan ?
        $itemIsLoaned = $entityManager->getRepository(Loan::class)->itemIsLoaned($item);
        $itemisRequested = $entityManager->getRepository(Loan::class)->itemIsRequested($item);

        $loanForm = null;
        if (!$itemIsLoaned && !$itemisRequested){

            // Create empty user
            $loan = new Loan();

            // Create the loan form
            $loanForm = $formFactory->create(LoanForm::class, $loan, ['standalone' => true]);

            //
            $loanForm->handleRequest($request);

            // Set the item in the loan
            $loan->setItem($item);
            // Set the status
            $loan->setStatus(Loan::STATUS_REQUESTED);

            // Set the borrower
            $borrower = self::getAuthorizedUser($app);
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
            $loanForm = $loanForm->createView();
        }

        return $app['twig']->render('request.html.twig',
            [
                'item' => $item,
                'requestForm' => $loanForm,
                'itemIsLoaned' => $itemIsLoaned,
                'itemIsRequested' => $itemisRequested
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

    public function declineAction(Request $request, Application $app, $loanId)
    {
        $entityManager = self::getEntityManager($app);
        $user = self::getAuthorizedUser($app);
        $loanRepo = $entityManager->getRepository(Loan::class);
        $loan = $loanRepo->find($loanId);

        if ($loan !== null){
            $loanStatusOk = $loan->getStatus() ===  Loan::STATUS_REQUESTED;
            $ownerOk = $loan->getItem()->getOwner() === $user;

            if ($loanStatusOk && $ownerOk ){
                $loanRepo->patchLoanStatus($loan, Loan::STATUS_DECLINED);
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
        $message
            ->setSubject('[Share2U] Request loan')
            ->setFrom([$borrowerEmail])
            ->setTo([$ownerEmail])
            ->setBody($app['twig']
                ->render('mail/requestMail.html.twig', [
                    'message' => $loan->getRequestMessage(),
                    'borrower' => $loan->getBorrower(),
//                    'borrower' => $loan->getBorrower()->getLastname(),
                    'item' => $loan->getItem()
                ]),
            'text/html'
        );
        $app['mailer']->send($message);
    }
}
