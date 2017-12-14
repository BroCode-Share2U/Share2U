<?php

namespace Controller;

use Form\LoanForm;
use Model\Item;
use Silex\Application;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Model\Loan;

class LoanController
{
    public function requestAction(Request $request, Application $app, $itemId)
    {
        
        $entityManager = $this->getEntityManager($app);
        $formFactory = $this->getFormFactory($app);

        $loan = new Loan();

        $loanForm = $formFactory->create(LoanForm::class, $loan, ['standalone' => true]);
        $loanForm->handleRequest($request);

        $item = $entityManager->getRepository(Item::class)->find($itemId);

        $loan->setItem($item);
        $loan->setBorrower();

        if ($loanForm->isSubmitted() && $loanForm->isValid()) {
            $this->sendRequestMessage($app, $loan);
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
        $message = new \Swift_Message();
            $message->setSubject('[Share2U] Request loan')
                    ->setFrom(array('support@share2u.com', $loan->getBorrower()->getEmail()))
                    ->setTo(array('giuliani.cyril@gmail.com'))
                    ->setBody($loan->getRequestMessage());

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