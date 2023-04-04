<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use App\Service\AnnonceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * Create Annonce
     * @Route("/api/annonce", name="create_annonce", methods={"POST"})
     */
    public function createAnnonce(Request $request, AnnonceService $annonceService): Response
    {
        // Get the data from the request
        $data = json_decode($request->getContent(), true);
        //Get Current User
        $user = $this->getUser();
        //Create Annonce
        $annonce = $annonceService->createOrUpdateAnnonce($user, $data);
        // Return a JSON response with a success message
        return $this->json(['idAnnonce' => $annonce->getId()]);
    }

    /**
     * Update the "Annonce"
     * @Route("/api/annonce/{id_annonce}", name="update_annonce", methods={"PUT"})
     */
    public function updateAnnonce($id_annonce, Request $request, AnnonceService $userService, AnnonceRepository $annonceRepository): Response
    {
        $annonce = $annonceRepository->find($id_annonce);
        if(!$annonce instanceof Annonce){
            return new JsonResponse(['code'=>Response::HTTP_NOT_FOUND, 'message'=>'Annonce not found'], Response::HTTP_NOT_FOUND, []);
        }
        // Get the data from the request
        $data = json_decode($request->getContent(), true);
        //Get Current User
        $user = $this->getUser();
        //Update Annonce
        $annonce = $userService->createOrUpdateAnnonce($user, $data, $annonce);
        // Return a JSON response with a success message
        return $this->json(['idAnnonce' => $annonce->getId()]);
    }

    /**
     * Respond the "Annonce"
     * @Route("/api/annonce/{id_annonce}/respond", name="respond_annonce", methods={"POST"})
     */
    public function respondAnnonce($id_annonce, EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository): Response
    {
        $user = $this->getUser();
        $annonce = $annonceRepository->find($id_annonce);
        if(!$annonce instanceof Annonce){
            return new JsonResponse(['code'=>Response::HTTP_NOT_FOUND, 'message'=>'Annonce not found'], Response::HTTP_NOT_FOUND, []);
        }
        //Add the Current user in the list of "prestataire" who respond the "annonce"
        $annonce->addResponseUser($user);
        $entityManager->flush();

        return $this->json(['message' => "Success"]);
    }

    /**
     * Get the list of "Prestataire" who respond the "annonce"
     * @Route("/api/annonce/{id_annonce}/responses", name="responses_annonce", methods={"GET"})
     */
    public function listResponsesAnnonce($id_annonce, AnnonceRepository $annonceRepository): Response
    {
        $annonce = $annonceRepository->find($id_annonce);
        if(!$annonce instanceof Annonce){
            return new JsonResponse(['code'=>Response::HTTP_NOT_FOUND, 'message'=>'Annonce not found'], Response::HTTP_NOT_FOUND, []);
        }
        //Get the list of "prestataire" who respond the "annonce"
        $listUsers = $annonce->getResponseUsers();
        $prestataires = [];
        foreach ($listUsers as $presta){
            $data=[];
            $data['id']=$presta->getId();
            $data['name']=$presta->getName();
            $data['email']=$presta->getEmail();
            $prestataires[]=$data;
        }
        return $this->json(['prestataires' => $prestataires]);
    }


}
