<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Service\FormValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AuthorRepository $authorRepository,
        private readonly FormValidator $formValidator,
    ) {
    }

    /**
     * Create an author with data posted.
     */
    #[Route(path: '/authors', name: 'post_author', requirements: [], methods: ['POST'])]
    public function createAuthor(Request $request): JsonResponse
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->submit($request->request->all(), false);

        if (empty($errors = $this->formValidator->getErrors($form))) {
            $this->entityManager->persist($author);
            $this->entityManager->flush();

            // serializer could be used here
            return $this->json([
                'id' => $author->getId(),
                'firstname' => $author->getFirstname(),
                'lastname' => $author->getLastname(),
            ], Response::HTTP_CREATED);
        }

        return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Return authors whose match to criteria.
     */
    #[Route(path: '/authors', name: 'get_authors', requirements: [], methods: ['GET'])]
    public function getAuthors(Request $request): JsonResponse
    {
        return $this->json(
            $this->authorRepository->findByAuthor(
                $request->query->get('firstname'),
                $request->query->get('lastname')
            ),
            Response::HTTP_OK
        );
    }
}
