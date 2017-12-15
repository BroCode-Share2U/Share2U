<?php

namespace Controller;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;
use Model\Item;
use Model\Loan;
use Model\Repository\LoanRepository;
use Model\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function homepageAction(Request $request, Application $app)
    {

        return $app['twig']->render('homepage.html.twig',[]);
    }

    public function supportAction(Request $request, Application $app)
    {

        return $app['twig']->render('support.html.twig',[]);
    }

    public function dashboardAction(Request $request, Application $app)
    {
        // Get serivces
        $entityManager = $this->getEntityManager($app);
        $userRepo = $entityManager->getRepository(User::class);
        $itemRepo = $entityManager->getRepository(Item::class);
        $loanRepo = $entityManager->getRepository(Loan::class);

        // Get the user
        // TODO Get the user auth
        $user = $userRepo->find('909f6844-e0c5-11e7-b6f9-00163e743728');

        // Get user's items
        foreach ($itemRepo->findByOwner($user) as $item){
            $items[] = $item->toArray();
        }

        // Get Request In
        $requestsIn = $loanRepo->getRequestIn($user, Loan::STATUS_REQUESTED);

        print_r($requestsIn);
        die;

        /////////////////////

        /////////////////////////////

        /*$criteria = Criteria::create()
            ->where(Criteria::expr()->eq("status", "0"))
            ->orderBy(array("insertAt" => Criteria::ASC))
        ;
        $test = $loanRepo->findAll()->matching($criteria);

        var_dump($test[0]->toArray());*/
        // Get Request Out
        /*foreach ($loanRepo->findByBorrower($user) as $loan){
            $requestsOut[] = $loan->toArray();
        }*/

        // Get Loan In
        $loansIn = null;

        // Get Loan Out
        $loansOut = null;

        $closedLoansIn = null;
        $closedLoansOut = null;


        return $app['twig']->render('dashboard.html.twig',
            [
                'user'=> $user->toArray(),
                'items' => $items,
                'requestsIn' => $requestsIn,
                'requestsOut' => $requestsOut,
                'loansIn' => $loansIn,
                'loansOut' => $loansOut,
                'closedLoansIn' => $closedLoansIn,
                'closedLoansOut' => $closedLoansOut,
            ]);
    }
}