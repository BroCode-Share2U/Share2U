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
$app->match('/support', 'Controller\DefaultController::supportAction')
    ->bind('support');
/* about */
$app->get('/about', 'Controller\DefaultController::aboutAction')
    ->bind('about');
/* dashboard */
$app->get('/dashboard', 'Controller\DefaultController::dashboardAction')
    ->bind('dashboard');
/* Search IGDB - User Action, proxy for search in IGDB */
$app->get('/igdb', 'Controller\DefaultController::searchIgdb')
    ->bind('searchIgdb');

/* *********************
* Comment Controller
********************* */
/* comment */
$app->match('/comment/{loanId}', 'Controller\CommentController::commentAction')
    ->bind('comment');

/* *********************
* User Controller
********************* */
/* show a fellow user */
$app->get('/user/{username}', 'Controller\UserController::showUserAction')
    ->bind('user');
/* send message user */
$app->match('/message/{username}', 'Controller\UserController::sendMessageAction')
    ->bind('sendMessage');
/* profile */
$app->match('/profile', 'Controller\UserController::showProfileAction')
    ->bind('profile');
/* edit profile */
$app->match('/profile/edit', 'Controller\UserController::editProfileAction')
    ->bind('editProfile');
/* signin */
$app->get('/signin', 'Controller\UserController::signinAction')
    ->bind('signin');
/* signup */
$app->match('/signup', 'Controller\UserController::signupAction')
    ->bind('signup');
/* forgot */
$app->match('/forgotPassword', 'Controller\UserController::forgotPasswordAction')
    ->bind('forgotPassword');
/* reset */
$app->match('/resetPassword', 'Controller\UserController::resetPasswordAction')
    ->bind('resetPassword');
/* admin panel */
$app->get('/admin/user', 'Controller\UserController::adminPanelAction')
    ->bind('adminPanel');
/* delete */
$app->delete('/admin/user/{userId}', 'Controller\UserController::deleteAction')
    ->bind('admin/user');

/* *********************
* Item Controller
********************* */
/* add item */
$app->match('/item/add', 'Controller\ItemController::addAction')
    ->bind('addItem');
/* item */
$app->get('/item/{itemId}', 'Controller\ItemController::showAction')
    ->bind('item');
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
/* request loan - borrower action, creates loan object */
$app->match('/loan/request/{itemId}', 'Controller\LoanController::requestAction')
    ->bind('requestItem');
/* accept loan - owner action */
$app->patch('/loan/accept/{loanId}', 'Controller\LoanController::acceptAction')
    ->bind('acceptRequest');
/* reject loan  - owner action */
$app->patch('/loan/decline/{loanId}', 'Controller\LoanController::declineAction')
    ->bind('declineRequest');
/* close loan  - owner action */
$app->patch('/loan/close/{loanId}', 'Controller\LoanController::closeAction')
    ->bind('closeLoan');
/* close loan  - borrower action, borrower cancels their request, should not possible after loan is accepted */
$app->patch('/loan/cancel/{loanId}', 'Controller\LoanController::cancelAction')
    ->bind('cancelRequest');


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
