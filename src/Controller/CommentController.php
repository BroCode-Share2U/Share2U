<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CommentController
{
    public function commentAction(Request $request, Application $app, $loanId)
    {

        return $app['twig']->render('comment.html.twig',[]);
    }
}