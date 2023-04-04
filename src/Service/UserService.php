<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $entityManager;
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager= $entityManager;
        $this->encoder= $encoder;
    }

    public function createUser($data){
        // Create a new user object and set its properties
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->encoder->encodePassword($user, $data['password']));
        $user->setType($data['type']);
        $user->setRoles(['ROLE_USER']);

        // Persist the user object to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}