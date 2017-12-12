<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function showAction(Request $request, Application $app, $username)
    {

        return $app['twig']->render('profile.html.twig',[]);
    }

    public function editAction(Request $request, Application $app)
    {

        return $app['twig']->render('profileEdit.html.twig',[]);
    }

    public function signinAction(Request $request, Application $app)
    {

        return $app['twig']->render('signin.html.twig',[]);
    }

    public function signupAction(Request $request, Application $app)
    {

        return $app['twig']->render('signup.html.twig',[]);
    }

    public function resetAction(Request $request, Application $app)
    {

        return $app['twig']->render('reset.html.twig',[]);
    }

    public function deleteAction(Request $request, Application $app, $userId)
    {

    }

    public function adminPanelAction(Request $request, Application $app)
    {

        return $app['twig']->render('adminPanel.html.twig',[]);
    }
}