<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class Application extends Silicone\Application
{
    protected function configure()
    {
        $app = $this;
        require_once $app->getRootDir() . '/config/dev.php';
    }

    /**
     * Main run method with HTTP Cache.
     *
     * @param Request $request
     */
    public function run(Request $request = null)
    {
        if ($this['debug']) {
            parent::run($request);
        } else {
            $this['http_cache']->run($request);
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function entityManager()
    {
        return $this['em'];
    }

    /**
     * Get session object.
     *
     * @return Session
     */
    public function session()
    {
        return $this['session'];
    }

    /**
     * Gets a user from the Security Context.
     *
     * @return mixed
     *
     * @see TokenInterface::getUser()
     */
    public function user()
    {
        if (null === $token = $this['security']->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function isGranted($role)
    {
        return $this['security']->isGranted($role);
    }

    /**
     * Encodes the raw password.
     *
     * @param UserInterface $user     A UserInterface instance
     * @param string $password The password to encode
     *
     * @return string The encoded password
     *
     * @throws \RuntimeException when no password encoder could be found for the user
     */
    public function encodePassword(UserInterface $user, $password)
    {
        return $this['security.encoder_factory']->getEncoder($user)->encodePassword($password, $user->getSalt());
    }
}