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
        $options = [];

        if($request->getMethod() == 'POST') {
            $name = $request->get('firstname') .' '. $request->get('lastname');

            $messagebody = new \Swift_Message();
            $messagebody->setSubject('Hello , I need some help please?')
                ->setFrom(array($request->get('email') =>$name ))
                ->setTo('share2u.contact@gmail.com')
                ->setBody($request->get('message')."\n".$request->get('email'));
            $app['mailer']->send($messagebody);

            $options = [
                'sent' => true
            ];
        }

            return $app['twig']->render('support.html.twig', $options);
    }

    public function dashboardAction(Request $request, Application $app)
    {
        // Get services and repository
        $entityManager = $this->getEntityManager($app);
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

        // Get requests in&out
        $requestsIn = $loanRepo->getLoansIn($user, Loan::STATUS_REQUESTED);
        $requestsOut = $loanRepo->getLoansOut($user, Loan::STATUS_REQUESTED);

        // Get loans in&out
        $loansIn = $loanRepo->getLoansIn($user, Loan::STATUS_IN_PROGRESS);
        $loansOut = $loanRepo->getLoansOut($user, Loan::STATUS_IN_PROGRESS);

        // Get declined request in&out
        $declineIn = $loanRepo->getLoansIn($user, Loan::STATUS_DECLINED);
        $declineOut = $loanRepo->getLoansOut($user, Loan::STATUS_DECLINED);

        // Get Cancel loans in&out
        $cancelIn = $loanRepo->getLoansIn($user, Loan::STATUS_CANCELLED);
        $cancelOut = $loanRepo->getLoansOut($user, Loan::STATUS_CANCELLED);

        // Get Clodsed loans in&out
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
                'declineIn' => $declineIn,
                'declineOut' => $declineOut,
                'cancelIn' => $cancelIn,
                'cancelOut' => $cancelOut,
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