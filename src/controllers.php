<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

/* *********************
* Default Controller
********************* */
/* homepage */
$app->get('/', 'Controller\DefaultController::homepageAction')
    ->bind('homepage');
/* support */
$app->get('/support', 'Controller\DefaultController::supportAction')
    ->bind('support');
/* dashboard */
$app->get('/dashboard', 'Controller\DefaultController::dashboardAction')
    ->bind('dashboard');

/* *********************
* Comment Controller
********************* */
/* comment */
$app->match('/comment/{loanId}', 'Controller\CommentController::commentAction')
    ->bind('comment');

/* *********************
* User Controller
********************* */
/* profile */
$app->get('/profile/{username}', 'Controller\UserController::showAction')
    ->bind('profile');
/* profile edit */
$app->match('/profile/edit', 'Controller\UserController::editAction')
    ->bind('profileEdit');
/* signin */
$app->get('/signin', 'Controller\UserController::signinAction')
    ->bind('signin');
/* signup */
$app->match('/signup', 'Controller\UserController::signupAction')
    ->bind('signup');
/* reset */
$app->match('/reset', 'Controller\UserController::resetAction')
    ->bind('reset');
/* delete */
$app->delete('/admin/user/{userId}', 'Controller\UserController::deleteAction')
    ->bind('deleteUser');
/* admin panel */
$app->get('/admin/user', 'Controller\UserController::adminPanelAction')
    ->bind('adminPanel');

/* *********************
* Item Controller
********************* */
/* item */
$app->get('/item/{itemId}', 'Controller\ItemController::showAction')
    ->bind('item');
/* add item */
$app->match('/item/add', 'Controller\ItemController::addAction')
    ->bind('addItem');
/* edit item */
$app->match('/item/edit/{itemId}', 'Controller\ItemController::editAction')
    ->bind('editItem');
/* delete item */
$app->delete('/item/{itemId}', 'Controller\ItemController::deleteAction')
    ->bind('deleteItem');
/* search item */
$app->get('/search', 'Controller\ItemController::searchAction')
    ->bind('searchItem');

/* *********************
* Loan Controller
********************* */
/* request loan */
$app->match('/request/{itemId}', 'Controller\LoanController::requestAction')
    ->bind('requestItem');
/* accept loan */
$app->patch('/loan/accept/{loanId}', 'Controller\LoanController::acceptAction')
    ->bind('acceptRequest');
/* reject loan */
$app->patch('/loan/reject/{loanId}', 'Controller\LoanController::rejectAction')
    ->bind('rejectRequest');
/* close loan */
$app->patch('/loan/close/{loanId}', 'Controller\LoanController::closeAction')
    ->bind('closeRequest');

/* *********************
* Error route
********************* */
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
