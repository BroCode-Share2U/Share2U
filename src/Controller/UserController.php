<?php

namespace Controller;

use Form\AddressForm;
use Form\ForgetForm;
use Form\UserForm;
use Model\Address;
use Form\ResetForm;
use Model\Repository\UserRepository;
use Model\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


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

    public function forgotPasswordAction(Request $request, Application $app)
    {
        $user = new User();
        $entityManager = $app['orm.em'];
        $formFactory = $app['form.factory'];
        $ForgetForm = $formFactory->create(ForgetForm::class, $user, [
            'standalone' => true,
            'user_repository' => $entityManager->getRepository(User::class)
        ]);
        $ForgetForm->handleRequest($request);

        if ($ForgetForm->isSubmitted() && $ForgetForm->isValid()) {
            $dbUser = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            $length = 32;
            $token = base64_encode(random_bytes($length));

            $dbUser->setToken($token);
            $entityManager->flush();

            $messagebody = new \Swift_Message();
            $messagebody->setSubject('Reset password')
                ->setFrom('share2u.contact@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($app['twig']->render('mail/mail.html.twig', [
                    'message' => $messagebody,
                    'token'=> $token
                ]),
                    'text/html'
                );
            $app['mailer']->send($messagebody);
            $app['session']->set('alert',  'Instructions for resetting your password have been emailed to you');

            return $app->redirect($app['url_generator']->generate('homepage'));
        } else {
            return $app['twig']->render('forgot_password.html.twig', [
                'form' => $ForgetForm->createView()
            ]);
        }
    }

    public function resetPasswordAction(Application $app, Request $request)
    {
        $user = new User();
        $entityManager =self::getEntityManager($app);
        $formFactory = self::getFormFactory($app);
        $token = $request->query->get('token');
        $user = $entityManager->getRepository(User::class)->findOneByToken($token);
        if (!$user) {
            throw new CustomUserMessageAuthenticationException(
                'this token is not valid ' );
        }
        $ResetForm = $formFactory->create(ResetForm::class, $user, [
            'standalone' => true,
            'user_repository' => $entityManager->getRepository(User::class)
        ]);
        $ResetForm->handleRequest($request);
        if ($ResetForm->isSubmitted() && $ResetForm->isValid()) {

                $encoder = $app['security.encoder_factory']->getEncoder(UserInterface::class);
                $password = $encoder->encodePassword($user->getPassword(), null);
                $user->setPassword($password);
                 var_dump($password);
                $user->setToken(null);
                 $entityManager->persist($user);
                 $entityManager->flush();
                var_dump($token);
            return $app->redirect($app['url_generator']->generate('homepage'));
        }
        return $app['twig']->render('reset_password.html.twig', [
            'form' => $ResetForm->createView()
        ]);
    }

    public function deleteAction(Request $request, Application $app, $userId)
    {

    }

    public function adminPanelAction(Request $request, Application $app)
    {

        return $app['twig']->render('adminPanel.html.twig',[]);
    }
}