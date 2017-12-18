<?php

namespace Controller;

use Form\ItemForm;
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
        // Get serivces
        $entityManager = self::getEntityManager($app);
        $formFactory = self::getFormFactory($app);

        $item = new Item();

        $itemForm = $formFactory->create(ItemForm::class, $item, ['standalone' => true]);

        $itemForm->handleRequest($request);

        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $now = new \DateTime();
            $item->setOwner(self::getAuthorizedUser($app));
            $item->setInsertedAt($now);
            $item->setUpdatedAt($now);
            $entityManager->persist($item);
            $entityManager->flush();

            // Redidect to the dashboard
            return $app->redirect($app['url_generator']->generate('dashboard'));
        }

        return $app['twig']->render('addItem.html.twig',
            [
                'itemForm' => $itemForm->createView()
            ]
        );
    }

    public function editAction(Request $request, Application $app, $itemId)
    {

        return $app['twig']->render('editItem.html.twig',[]);
    }

    public function searchAction(Request $request, Application $app)
    {

        //ADDED FOR TEST/////////////////////////////////////////////////////////////////////
        $entityManager = $this->getEntityManager($app);
        $userRepo = $entityManager->getRepository(User::class);
        $itemRepo = $entityManager->getRepository(Item::class);

        // Get the user
        $user = $this->getAuthorizedUser($app);

        $items = [];
        foreach ($itemRepo->findByOwner($user) as $item) {
            $items[] = $item->toArray();
        }

        return $app['twig']->render('search.html.twig',[
            'items' => $items
        ]);

        //////////////////////////////////////////////////////////////////////////////////////////
    }

    public function deleteAction(Request $request, Application $app, $itemId)
    {

    }
}