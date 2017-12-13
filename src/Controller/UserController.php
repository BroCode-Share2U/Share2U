<?php

namespace Controller;

use Form\AddressForm;
use Form\UserForm;
use Model\Address;
use Model\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;


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
        $user = new User();
        $address = new Address();
        $user->setAddress($address);

        $formFactory = $app['form.factory'];
        $userForm = $formFactory->create(UserForm::class, $user, ['standalone' => true]);
        $addressForm = $formFactory->create(AddressForm::class, $user->getAddress());

        $userForm->handleRequest($request);
        $addressForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager = $app['orm.em'];

            $user->setRole(User::ROLE_USER);

            // Encrypt password
//            $encoder = $app['security.encoder_factory']->getEncoder(UserInterface::class);
//            $password = $encoder->encodePassword($user->getPassword(), null);
//            $user->setPassword($password);

            var_dump($user); die;
//            $entityManager->persist($user);
//            $entityManager->flush();

            return $app->redirect($app['url_generator']->generate('signin'));
        }
        else {
            return $app['twig']->render('signup.html.twig', [
                'userForm' => $userForm->createView(),
                'addressForm' => $addressForm->createView()
            ]);
        }
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