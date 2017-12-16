<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Model\User;
use Silex\Application;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class Controller
{
    /**
     * @param Application $app
     * @return EntityManager
     */
    public function getEntityManager(Application $app)
    {
        return $app['orm.em'];
    }

    /**
     * @param Application $app
     * @return FormFactory
     */
    public function getFormFactory(Application $app)
    {
        return $app['form.factory'];
    }

    /**
     * @param Application $app
     * @return User
     */
    public function getUserAuth($app)
    {
        // Get current authentication token
        $token = $app['security.token_storage']->getToken();
        if ($token === null) {
            throw new AccessDeniedHttpException('User not found');
        }
        // Get user from token
        return $token->getUser();
    }

}