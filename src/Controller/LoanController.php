<?php

namespace Controller;

use Form\LoanForm;
use Model\Item;
use Model\User;
use Silex\Application;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Model\Loan;

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
        // TODO check if the object exist
        $item = $entityManager->getRepository(Item::class)->find($itemId);
        if ($item === null){
            return $app->redirect($app['url_generator']->generate('dashboard'));
        }

        // Set the item in the loan
        $loan->setItem($item);
        // Set the status
        $loan->setStatus(Loan::STATUS_REQUESTED);
        // Set the borrower
        // TODO get the user signin
        $loan->setBorrower($entityManager->getRepository(User::class)->find('663b739a-e0d2-11e7-b6f9-00163e763728'));

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

    }

    public function rejectAction(Request $request, Application $app, $loanId)
    {

    }

    public function closeAction(Request $request, Application $app, $loanId)
    {

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