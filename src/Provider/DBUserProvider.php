<?php

namespace Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Model\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class DBUserProvider implements UserProviderInterface
{

    /**
     * Repository
     *
     * The repository injected in constructor
     *
     * @var ObjectRepository
     */
    private $repository;

    /**
     * Constructor
     *
     * The default constructor of the DBUserProvider. It receive as dependency
     * injection an instance of repository to be able to load the user instances
     *
     * @param ObjectRepository $repository The repository used to load the users
     *
     * @return void
     */
    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Load user by username
     *
     * This method will load a user instance from the database and validate if
     * the user exist. If it does not exist, this method will throw a
     * UsernameNotFoundException
     *
     * @param string $username The username of the user to load
     *
     * @return UserInterface
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->repository->findOneByUsername($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist', $username));
        }

        return $user;
    }

    /**
     * Refresh user
     *
     * This method refresh a user given by the application. This grant the user
     * already exist and refresh it roles in case of change
     *
     * @param UserInterface $user THe user to refresh
     *
     * @return UserInterface
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported', get_class($user)));
        }

        $user = $this->loadUserByUsername($user->getUsername());
        return $user;
    }

    /**
     * Support class
     *
     * This method validate that the given class is usable by the current
     * provider.
     *
     * @param string $class The class to validate
     *
     * @return boolean
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}