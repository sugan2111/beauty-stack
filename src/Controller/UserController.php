<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private $entityManager;
    private $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $ur)
    {
        $this->entityManager = $em;
        $this->userRepository = $ur;
    }

    /**
     * @Route("/user", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setName($request->get('name'));
        $user->setType($request->get('type'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'type' => $user->getType()]);

    }

    /**
     * @Route("/user", methods={"GET"})
     */
    public function listAction()
    {
        $users = $this->userRepository->findAll();
        return new JsonResponse(array_map(function($user) {
            return [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'type' => $user->getType(),
            ];
        }, $users));

    }

    /**
     * @Route("/user/{id}", methods={"PATCH"})
     */
    public function updateAction(Request $request, int $id)
    {
        $user = $this->userRepository->find($id);
        $user->setType($request->get('type'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'type' => $user->getType()]);

    }


}
