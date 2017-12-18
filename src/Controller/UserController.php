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

    public function forgotPasswordAction(Request $request, Application $app)
    {
/*        $request = $app['request'];

        $message = \Swift_Message::newInstance()
            ->setSubject('Reset password')
            ->setFrom(array($request->get('email')))
            ->setTo(array(''))
            ->setBody($request->get(''));

        $app['mailer']->send($message);

        return $app['twig']->render('pages/contact.twig', array('sent' => true));

    });*/
        $error = null;
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $this->User->findOneBy(array('email' => $email));
            if ($user) {
                // Initialize and send the password reset request.
                $user->setTimePasswordResetRequested(time());
                if (!$user->getConfirmationToken()) {
                    $user->setConfirmationToken($app['user.tokenGenerator']->generateToken());
                }
                $this->userManager->update($user);
                $app['user.mailer']->sendResetMessage($user);
                $app['session']->getFlashBag()->set('alert', 'Instructions for resetting your password have been emailed to you.');
                $app['session']->set('_security.last_username', $email);
                return $app->redirect($app['url_generator']->generate('user.login'));
            }
            $error = 'No user account was found with that email address.';

        } else {

            $email = $request->request->get('email') ?: ($request->query->get('email') ?: $app['session']->get('_security.last_username'));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $email = '';
        }

        return $app['twig']->render($this->getTemplate('forgot-password'), array(
            'layout_template' => $this->getTemplate('layout'),
            'email' => $email,
            'fromAddress' => $app['user.mailer']->getFromAddress(),
            'error' => $error,
        ));



        return $app['twig']->render('reset_password.html.twig',[]);
    }

    public function resetPasswordAction(Application $app, Request $request, $token)
    {
        $tokenExpired = false;
        $user = $this->userManager->findOneBy(array('confirmationToken' => $token));
        if (!$user) {
            $tokenExpired = true;
        } else if ($user->isPasswordResetRequestExpired($app['user.options']['passwordReset']['tokenTTL'])) {
            $tokenExpired = true;
        }
        if ($tokenExpired) {
            $app['session']->getFlashBag()->set('alert', 'Sorry, your password reset link has expired.');
            return $app->redirect($app['url_generator']->generate('user.signin'));
        }
        $error = '';
        if ($request->isMethod('POST')) {
            // Validate the password
            $password = $request->request->get('password');
            if ($password != $request->request->get('confirm_password')) {
                $error = 'Passwords don\'t match.';
            } else if ($error = $this->userManager->validatePasswordStrength($user, $password)) {
                ;
            } else {
                // Set the password and log in.
                $this->userManager->setUserPassword($user, $password);
                $user->setConfirmationToken(null);
                $user->setEnabled(true);
                $this->userManager->update($user);
                $this->userManager->loginAsUser($user);
                $app['session']->getFlashBag()->set('alert', 'Your password has been reset and you are now signed in.');
                return $app->redirect($app['url_generator']->generate('user.homepage', array('id' => $user->getId())));
            }
        }
        return $app['twig']->render($this->getTemplate('reset-password'), array(
            'layout_template' => $this->getTemplate('layout'),
            'user' => $user,
            'token' => $token,
            'error' => $error,
        ));
    }

    public function deleteAction(Request $request, Application $app, $userId)
    {

    }

    public function adminPanelAction(Request $request, Application $app)
    {

        return $app['twig']->render('adminPanel.html.twig',[]);
    }
}