<?php
namespace App\UserBundle\Security\Provider;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManager;
use App\UserBundle\Entity\LoginHistory as LoginHistory;

class CustomProvider implements UserProviderInterface
{
    private $userManager;
    protected $entityManager;

    public function __construct(UserManagerInterface $userManager, EntityManager $entityManager)
    {
        $this->userManager = $userManager;
        $this->entityManager = $entityManager;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->userManager->findUserByUsernameOrEmail($username);
        $em = $this->entityManager;

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('No user with name "%s" was found.', $username));
        }else{
            $em->getRepository('App\UserBundle\Entity\LoginHistory')->add($user,LoginHistory::STATUS_LOGIN , $username);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\UserInterface, but got "%s".', get_class($user)));
        }

        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', $this->userManager->getClass(), get_class($user)));
        }

        if (null === $reloadedUser = $this->userManager->findUserBy(array('id' => $user->getId()))) {
            throw new UsernameNotFoundException(sprintf('User with ID "%s" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }
}
