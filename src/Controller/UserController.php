<?php

namespace Controller;

use Form\AddressForm;
use Form\UserForm;
use Model\Address;
use Model\Repository\UserRepository;
use Model\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class UserController extends Controller
{
    public function showAction(Request $request, Application $app, $username)
    {

        return $app['twig']->render('user.html.twig',[]);
    }

    public function editAction(Request $request, Application $app)
    {
        return $app['twig']->render('profile.html.twig',[]);
    }

    public function signinAction(Request $request, Application $app)
    {

        return $app['twig']->render('signin.html.twig',
            [
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ]
        );
    }

    public function signupAction(Request $request, Application $app)
    {
        $user = new User();
        $address = new Address();
        $entityManager = $app['orm.em'];

        $formFactory = $app['form.factory'];
        $userForm = $formFactory->create(UserForm::class, $user, [
            'standalone' => true,
            'user_repository' => $entityManager->getRepository(User::class)
        ]);
        $addressForm = $formFactory->create(AddressForm::class, $address);

        $userForm->handleRequest($request);
        $addressForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // Set parameters that aren't set by form->handleRequest()
            $user->setRole(User::ROLE_USER);
            $now = new \DateTime();
            $user->setInsertedAt($now);
            $user->setUpdatedAt($now);
            $user->setAddress($address);
            // Encrypt password
            $encoder = $app['security.encoder_factory']->getEncoder(UserInterface::class);
            $password = $encoder->encodePassword($user->getPassword(), null);
            $user->setPassword($password);

            // Persist the user and address
            $entityManager->persist($address);
            $entityManager->persist($user);
            $entityManager->flush();

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