<?php


namespace App\Controller;


use App\Entity\Carte;
use App\Repository\CarteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CarteController extends AbstractController
{
    /**
     * Create Carte
     * @Route("/api/card", name="create_carte", methods={"POST"})
     */
    public function createCarte(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the data from the request
        $data = json_decode($request->getContent(), true);
        //Get Current User
        $user = $this->getUser();
        //Create Carte
        $carte = new Carte();
        $carte->setNumber($data['number']);
        $carte->setUser($this->getUser());
        $entityManager->persist($carte);
        $entityManager->flush();
        // Return a JSON response with a success message
        return $this->json(['idCarte' => $carte->getId()]);
    }

    /**
     * Get the list of "Cards"
     * @Route("/api/cards", name="list_carts", methods={"GET"})
     */
    public function listCards(): Response
    {
        $currentUser = $this->getUser();
        $cards = $currentUser->getCartes();
        $result=[];
        foreach ($cards as $card){
            $data=[];
            $data['id']=$card->getId();
            $data['number']=$card->getNumber();
        }
        return $this->json(['cards' => $result]);
    }

    /**
     * Delete Carte
     * @Route("/api/card/{id_card}", name="delete_carte", methods={"DELETE"})
     */
    public function deleteCarte($id_card, Request $request, EntityManagerInterface $entityManager, CarteRepository $carteRepository): Response
    {
        $card = $carteRepository->find($id_card);
        if(!$card instanceof Carte){
            return new JsonResponse(['code'=>Response::HTTP_NOT_FOUND, 'message'=>'Card not found'], Response::HTTP_NOT_FOUND, []);
        }
        $entityManager->remove($card);
        $entityManager->flush();
        return $this->json(['message' => "Success"]);
    }
}