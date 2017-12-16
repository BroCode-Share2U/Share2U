<?php

namespace Controller;

use Model\Comment;
use Model\Item;
use Model\Loan;
use Model\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function homepageAction(Request $request, Application $app)
    {
        return $app['twig']->render('homepage.html.twig',[
            'user'=> $this->getUserAuthArray($app)
        ]);
    }

    public function aboutAction(Request $request, Application $app)
    {

        return $app['twig']->render('about.html.twig',[]);
    }

    public function supportAction(Request $request, Application $app)
    {

        return $app['twig']->render('support.html.twig',[]);
    }

    public function dashboardAction(Request $request, Application $app)
    {
        // Get serivces and repository
        $entityManager = $this->getEntityManager($app);
        $userRepo = $entityManager->getRepository(User::class);
        $itemRepo = $entityManager->getRepository(Item::class);
        $loanRepo = $entityManager->getRepository(Loan::class);
        $commentRepo = $entityManager->getRepository(Comment::class);

        // Get the user
        $user = $this->getUserAuth($app);
        // Get rating
        $userRate = $commentRepo->getRatingUser($user);
        // Get user's items
        $items = [];
        foreach ($itemRepo->findByOwner($user) as $item){
            $items[] = $item->toArray();
        }

        // Get Request In
        $requestsIn = $loanRepo->getLoanIn($user, Loan::STATUS_REQUESTED);
        // Get Request Out
        $requestsOut = $loanRepo->getLoanOut($user, Loan::STATUS_REQUESTED);

        // Get Loan In
        $loansIn = $loanRepo->getLoanIn($user, Loan::STATUS_IN_PROGRESS);
        // Get Loan Out
        $loansOut = $loanRepo->getLoanOut($user, Loan::STATUS_IN_PROGRESS);

        $closedLoansIn = $loanRepo->getLoanIn($user, Loan::STATUS_CLOSED);
        $closedLoansOut = $loanRepo->getLoanOut($user, Loan::STATUS_CLOSED);


        return $app['twig']->render('dashboard.html.twig',
            [
                'user'=> $this->getUserAuthArray($app),
                'avgRating' => $userRate,
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