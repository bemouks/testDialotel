<?php


namespace App\Service;


use App\Entity\Annonce;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AnnonceService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager= $entityManager;
    }

    public function createOrUpdateAnnonce(User $user, $data, Annonce $annonce=null){
        if(!$annonce){
            $annonce = new Annonce();
        }
        $annonce->setDescription($data['description']);
        $annonce->setPrice($data['price']);
        $annonce->setUser($user);
        $this->entityManager->persist($annonce);
        $this->entityManager->flush();
        return $annonce;
    }
}