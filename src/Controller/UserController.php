<?php

namespace Controller;

use Form\AddressForm;
use Form\EditProfileForm;
use Form\ForgetForm;
use Form\UserForm;
use Form\ResetForm;
use Model\Repository\UserRepository;
use Model\Address;
use Model\User;
use Model\Item;
use Model\Comment;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


class UserController extends Controller
{
    public function showUserAction(Request $request, Application $app, $username) {
        $userRepo = $this->getEntityManager($app)->getRepository(User::class);
        $viewedUser = $userRepo->findOneByUsername($username);

        if ($viewedUser !== null) {
            return $this->renderProfileOrUser($app, $viewedUser);
        }
        else  {
            echo "Viewed user does not exist"; die;
        }
    }

    public function showProfileAction(Request $request, Application $app) {
        $thisUser = $this->getAuthorizedUser($app);

        return $this->renderProfileOrUser($app, $thisUser);
    }

    private function renderProfileOrUser ($app, $user) {
        $em = self::getEntityManager($app);
        $itemRepo = $em ->getRepository(Item::class);
        $commentRepo = $em ->getRepository(Comment::class);

        // Get items back ordered by name
        $items = $itemRepo->findBy(['owner' => $user, 'active' => true], ['name' => 'ASC']);
        $comments = $commentRepo->findByUser($user);
        $rating = $commentRepo->getAverageRating($user);

        return $app['twig']->render('profile.html.twig', [
            'viewedUser' => $user,
            'items' => $items,
            'comments' => $comments,
            'rating' => $rating
        ]);
    }

    public function editProfileAction(Request $request, Application $app)
    {
        $user = self::getAuthorizedUser($app);

        // if we're logged in
        if ($user) {
            $formFactory = self::getFormFactory($app);

            // Create forms
            $editForm = $formFactory->create(EditProfileForm::class, $user, [
                'standalone' => true,
            ]);
            $address = $user->getAddress();
            $addressForm = $formFactory->create(AddressForm::class, $address);

            $editForm->handleRequest($request);
            $addressForm->handleRequest($request);

            // If the form was just submitted
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                // Set parameters that aren't set by form->handleRequest()
                $now = new \DateTime();
                $user->setUpdatedAt($now);
                $address->setUpdatedAt($now);
                $user->setAddress($address);
                // Encrypt password
                $encoder = $app['security.encoder_factory']->getEncoder(UserInterface::class);
                $password = $encoder->encodePassword($user->getPassword(), null);
                $user->setPassword($password);

                // Persist the update to user and address
                $entityManager = self::getEntityManager($app);
                $entityManager->flush();

                return $this->renderProfileOrUser($app, $user);
            }
            else {
                return $app['twig']->render('profileEdit.html.twig', [
                    'editProfileForm' => $editForm->createView(),
                    'addressForm' => $addressForm->createView()
                ]);
            }
        }
        // if we're not logged in redirect to the signin page
        else return $app['twig']->render('signin.html.twig', []);
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
            $user->setRoles(User::ROLE_USER);
            $now = new \DateTime();
            $user->setInsertedAt($now);
            $user->setUpdatedAt($now);
            $address->setInsertedAt($now);
            $address->setUpdatedAt($now);
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
        $forgetForm = $formFactory->create(ForgetForm::class, $user, [
            'standalone' => true,
            'user_repository' => $entityManager->getRepository(User::class)
        ]);
        $forgetForm->handleRequest($request);

        if ($forgetForm->isSubmitted() && $forgetForm->isValid()) {
            $dbUser = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            $length = 32;
            $token = base64_encode(random_bytes($length));
            $token = str_replace('+', '', $token);

            $dbUser->setToken($token);
            $entityManager->flush();

            $messageBody = new \Swift_Message();
            $messageBody->setSubject('Reset password')
                ->setFrom('share2u.contact@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($app['twig']->render('mail/mail.html.twig', [
                    'message' => $messageBody,
                    'token'=> $token
                ]),
                    'text/html'
                );
            $app['mailer']->send($messageBody);
            $app['session']->set('alert',  'Instructions for resetting your password have been emailed to you');

            return $app->redirect($app['url_generator']->generate('homepage'));
        } else {
            return $app['twig']->render('forgot_password.html.twig', [
                'form' => $forgetForm->createView()
            ]);
        }
    }

    public function resetPasswordAction(Application $app, Request $request)
    {
        $entityManager =self::getEntityManager($app);
        $formFactory = self::getFormFactory($app);
        $token = $request->query->get('token_');
        $user = $entityManager->getRepository(User::class)->findOneByToken($token);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('this token is not valid');
        }

        $resetForm = $formFactory->create(ResetForm::class, $user, [
            'standalone' => true
        ]);

        $resetForm->handleRequest($request);

        if ($resetForm->isSubmitted() && $resetForm->isValid())
        {
            $encoder = $app['security.encoder_factory']->getEncoder(UserInterface::class);
            $password = $encoder->encodePassword($user->getPassword(), null);
            $user->setPassword($password);
            $user->setToken(null);
            $entityManager->persist($user);
            $entityManager->flush();
            return $app->redirect($app['url_generator']->generate('homepage'));
        }
        return $app['twig']->render('reset_password.html.twig', [
            'form' => $resetForm->createView()
        ]);
    }

    public function deleteAction(Request $request, Application $app, $userId)
    {
        $manager = $app['orm.em'];
        $repository = $manager->getRepository(User::class);
        $user = $repository->find($userId);
        if (!$user) {
            $message = sprintf('User %d not found', $userId);
            return $app->json(['status' => 'error', 'message' => $message], 404);
        }

        $manager->remove($user);
        $manager->flush();

         return $app->json(['status' => 'done']);
    }

    public function adminPanelAction(Request $request, Application $app)
    {
         if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
            return $app->redirect($app['url_generator']->generate('homepage'));
         }

        $repository = $app['orm.em']->getRepository(User::class);
        $result = [];
        foreach($repository->findAll() as $user) {
            $result[] = $user->toArray();
        }

        return $app['twig']->render('adminPanel.html.twig', [
           'users' => $result
        ]);
    }
}