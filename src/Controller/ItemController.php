<?php

namespace Controller;

use Form\ItemForm;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

//ADDED FOR TEST//////////
use Model\User;
use Model\Item;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

////////////////////////////////


class ItemController extends Controller
{
    public function showAction(Request $request, Application $app, $itemId)
    {
        $itemRepo = self::getEntityManager($app)->getRepository(Item::class);

        $item = $itemRepo->find($itemId);
        if ($item === null){
            throw new NotFoundHttpException('item not found');
        }

        return $app['twig']->render('item.html.twig',
            [
                'item' => $item->toArray()
            ]
        );
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
            $item->setActive(true);
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
        // Get serivces
        $entityManager = self::getEntityManager($app);
        $formFactory = self::getFormFactory($app);

        $item = $entityManager->getRepository(Item::class)->find($itemId);

        $itemForm = $formFactory->create(ItemForm::class, $item, ['standalone' => true]);

        $itemForm->handleRequest($request);

        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $now = new \DateTime();
            $item->setUpdatedAt($now);
            $entityManager->flush();

            // Redidect to the dashboard
            return $app->redirect($app['url_generator']->generate('dashboard'));
        }

        return $app['twig']->render('editItem.html.twig',
            [
                'itemForm' => $itemForm->createView(),
                'item' => $item->toArray()
            ]
        );
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
        $entityManager = self::getEntityManager($app);
        $itemRepo = $entityManager->getRepository(Item::class);
        $item = $itemRepo->find($itemId);
        $user = self::getAuthorizedUser($app);
        if ($item !== null){
            $ownerOk = $item->getOwner() === $user;
            if ( $ownerOk ){
                $item->setActive(false);
                $entityManager->flush();
                return $app->json(
                    [
                        'code' => 1,
                        'message' => 'item delete'
                    ]
                );
            }
            return $app->json(
                [
                    'code' => 0,
                    'message' => 'bad owner'
                ]
            );
        }
        return $app->json(
            [
                'code' => 0,
                'message' => 'item not found'
            ]
        );
    }
}