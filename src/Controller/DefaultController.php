<?php

namespace Controller;

use GuzzleHttp\Client;
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
        return $app['twig']->render('homepage.html.twig',[]);
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
        // Get services and repository
        $entityManager = $this->getEntityManager($app);
        $userRepo = $entityManager->getRepository(User::class);
        $itemRepo = $entityManager->getRepository(Item::class);
        $loanRepo = $entityManager->getRepository(Loan::class);
        $commentRepo = $entityManager->getRepository(Comment::class);

        // Get the user
        $user = $this->getAuthorizedUser($app);
        // Get rating
        $averageRating = $commentRepo->getAverageRating($user);
        // Get user's items
        $items = [];
        foreach ($itemRepo->findByOwner($user) as $item) {
            $items[] = $item->toArray();
        }

        // Get requests in
        $requestsIn = $loanRepo->getLoansIn($user, Loan::STATUS_REQUESTED);
        // Get requests out
        $requestsOut = $loanRepo->getLoansOut($user, Loan::STATUS_REQUESTED);

        // Get loans in
        $loansIn = $loanRepo->getLoansIn($user, Loan::STATUS_IN_PROGRESS);
        // Get loans out
        $loansOut = $loanRepo->getLoansOut($user, Loan::STATUS_IN_PROGRESS);

        $closedLoansIn = $loanRepo->getLoansIn($user, Loan::STATUS_CLOSED);
        $closedLoansOut = $loanRepo->getLoansOut($user, Loan::STATUS_CLOSED);


        return $app['twig']->render('dashboard.html.twig',
            [
                'averageRating' => $averageRating,
                'items' => $items,
                'requestsIn' => $requestsIn,
                'requestsOut' => $requestsOut,
                'loansIn' => $loansIn,
                'loansOut' => $loansOut,
                'closedLoansIn' => $closedLoansIn,
                'closedLoansOut' => $closedLoansOut,
            ]);
    }

    public function searchIgdb(Request $request, Application $app)
    {
        $client = new Client();

        $search = $request->query->get('inputSearch');
        $headers = ['user-key' => ' 2d156ee0f911a8d4d7d0984c5ceff1ca ', 'Accept' => 'application/json'];

        $request = new \GuzzleHttp\Psr7\Request('GET', 'https://api-2445582011268.apicast.io/games/?search='. $search . '&fields=name,summary,cover', $headers);

        $response = $client->send($request, ['timeout' => 2]);

        return $response->getBody()->getContents();
    }

}