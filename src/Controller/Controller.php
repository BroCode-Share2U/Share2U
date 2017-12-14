<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Silex\Application;
use Symfony\Component\Form\FormFactory;

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
}