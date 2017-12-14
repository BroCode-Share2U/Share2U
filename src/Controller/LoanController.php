<?php

namespace Controller;

use Model\Item;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class LoanController
{
    public function requestAction(Request $request, Application $app, $itemId)
    {
        $entityManager = $this->getEntityManager($app);
        $item = $entityManager->getRepository(Item::class)->find($itemId);

        $message = new \Swift_Message();
        $message->setSubject('[Share2U] Request loan')
                ->setFrom(array('support@share2u.com'))
                ->setTo(array('giuliani.cyril@gmail.com'))
                ->setBody('test');
        //->setBody($request->get('message'));

        $app['mailer']->send($message);


        return $app['twig']->render('request.html.twig',
            [
                'item' => $item
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

    /**
     * @param Application $app
     * @return EntityManager
     */
    public function getEntityManager(Application $app): EntityManager
    {
        return $app['orm.em'];
    }
}