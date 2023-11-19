<?php

namespace App\Controller;

use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class LocksController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {}

    #[Route('/locks', name: 'app_locks')]
    public function index(): Response
    {
        return $this->render('locks/index.html.twig');
    }

    #[Route('/locks/combination/{lock}/{lockColumn}', name: 'app_locks_getcombination', options: ['expose' => true])]
    public function getCombination(int $lock, string $lockColumn)
    {
        $position = $this->entityManager->getRepository(Position::class)->findOneByLockAndLockColumn($lock, $lockColumn);

        if (null === $position) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($position->jsonSerialize());
    }
}
