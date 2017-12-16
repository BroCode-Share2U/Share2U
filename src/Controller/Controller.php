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
        // Delete after resolve the auth problem
        return $this->getEntityManager($app)->getRepository(User::class)->find('909f6844-e0c5-11e7-b6f9-00163e743728');

        // Get current authentication token
        $token = $app['security.token_storage']->getToken();
        if ($token === null) {
            throw new AccessDeniedHttpException('User not found');
        }
        // Get user from token
        return $token->getUser();
    }

}