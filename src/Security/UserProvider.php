<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @param $username
     *
     * @return UserInterface
     *
     */
    public function loadUserByUsername($username)
    {
        // Load a User object from your data source or throw UsernameNotFoundException.
        // The $username argument may not actually be a username:
        // it is whatever value is being returned by the getUsername()
        // method in your User class.
        return new User($username, "", "");
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if ( !$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }
        return $user;

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
    }

    /**
     * Tells Symfony to use this provider for this User class.
     *
     * @param $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }

    /**
     * Upgrades the encoded password of a user, typically for using a better hash algorithm.
     *
     * @param UserInterface $user
     * @param string        $newEncodedPassword
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword) : void
    {
        // TODO: when encoded passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newEncodedPassword);
    }
}
