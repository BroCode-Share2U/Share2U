<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DefaultController
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

        return $app['twig']->render('dashboard.html.twig',[]);
    }
}