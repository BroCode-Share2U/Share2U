<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

//ADDED FOR TEST//////////
use Model\User;
use Model\Item;
////////////////////////////////


class ItemController extends Controller
{
    public function showAction(Request $request, Application $app, $itemId)
    {

        return $app['twig']->render('item.html.twig',[]);
    }

    public function addAction(Request $request, Application $app)
    {

        return $app['twig']->render('addItem.html.twig',[]);
    }

    public function editAction(Request $request, Application $app, $itemId)
    {

        return $app['twig']->render('editItem.html.twig',[]);
    }

    public function searchAction(Request $request, Application $app)
    {
        $entityManager = $this->getEntityManager($app);
        $itemRepo = $entityManager->getRepository(Item::class);

        // Get the user
        $user = $this->getAuthorizedUser($app);
        $searchString = $request->query->get('searchString');

        return $app['twig']->render('search.html.twig', [
            'items' => $itemRepo->searchOthersItems($searchString, $user),
            'searchString' => $searchString
        ]);
    }

    public function deleteAction(Request $request, Application $app, $itemId)
    {

    }
}