<?php

namespace Extension\Shop\CommerceML;

use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CommerceMLAuthenticate
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    private $sessionStorage;
    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    private $userProvider;
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    function __construct(SessionStorageInterface $sessionStorage, UserProviderInterface $userProvider, EncoderFactory $encoderFactory)
    {
        $this->sessionStorage = $sessionStorage;
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
    }

    public function authenticate($username, $password)
    {
        $user = $this->userProvider->loadUserByUsername($username);
        if(null !== $user) {
            return $this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $password, $user->getSalt());
        }
        return false;
    }

} 