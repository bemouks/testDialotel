<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/api/user/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserService $userService): Response
    {
        // Get the data from the request
        $data = json_decode($request->getContent(), true);

        //Create User by UserService
        $user = $userService->createUser($data);

        // Return a JSON response with a success message
        return $this->json(['idUser' => $user->getId()]);

    }
}
