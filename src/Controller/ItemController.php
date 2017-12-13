<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ItemController
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

        return $app['twig']->render('search.html.twig',[]);
    }

    public function deleteAction(Request $request, Application $app, $itemId)
    {

    }
}