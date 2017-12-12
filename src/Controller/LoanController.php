<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LoanController
{
    public function requestAction(Request $request, Application $app, $itemId)
    {

        return $app['twig']->render('request.html.twig',[]);
    }

    public function acceptAction(Request $request, Application $app, $loanId)
    {

    }

    public function rejectAction(Request $request, Application $app, $loanId)
    {

    }

    public function closeAction(Request $request, Application $app, $loanId)
    {

    }
}