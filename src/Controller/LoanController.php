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

class LoanController
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

        // Set the item in the loan
        $loan->setItem($item);
        // Set the borrower
        // TODO get the user signin
        $loan->setBorrower($entityManager->getRepository(User::class)->find('663b739a-e0d2-11e7-b6f9-00163e763728'));

        // Persist the loan and send the eamil to the owner
        if ($loanForm->isSubmitted() && $loanForm->isValid()) {
            //TODO persist the loan

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
                    ->setBody($loan->getRequestMessage()); //TODO Create a beautifull message

            $app['mailer']->send($message);
    }

    /**
     * @param Application $app
     * @return EntityManager
     */
    public function getEntityManager(Application $app)
    {
        return $app['orm.em'];
    }

    /**
     * @param Application $app
     * @return FormFactory
     */
    public function getFormFactory(Application $app)
    {
        return $app['form.factory'];
    }


}