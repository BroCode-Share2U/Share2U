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
     * @return null|User
     */
    public function getAuthorizedUser($app)
    {
        // Get current authentication token
        $token = $app['security.token_storage']->getToken();

        if ($token !== null) {
            $user = $token->getUser(); // Get user from token
            if ($user !== 'anon.') {
                return $user;
            }
        }
        return null;
    }

    /**
     * @param Application $app
     * @return null|array
     */
    public function getAuthorizedUserAsArray($app)
    {
        // Get current authentication token
        $user = $this->getAuthorizedUser($app);

        return $user ? $user->toArray() : null;
    }

}