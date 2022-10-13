<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BookController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookRepository $bookRepository,
        private readonly Environment $twig,
    ) {
    }

    /**
     * return all name of books in json format.
     */
    #[Route('/books/list', name: 'list-of-my-books', methods: ['POST'], format: 'json')]
    public function book(): JsonResponse|string
    {
        $book = $this->bookRepository->find(1);

        try {
            $template = $this->twig->load('book/index.html.twig');
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $template->render([
            'return' => json_encode([
                'data' => json_encode($book[0]['name']),
            ]),
        ]);
    }

    /**
     * parcour all books and add sufix on name.
     */
    #[Route('/books/add-sufix', name: 'add-sufix-on-my-books', methods: ['GET'], format: 'json')]
    public function addSufix(): JsonResponse|string
    {
        $books = $this->bookRepository->findAll();

        foreach ($books as $book) {
            $book->setTitle(sprintf('%s - Sufix', $book->getTitle()));
            $this->entityManager->persist($book);
            $this->entityManager->flush();
        }

        try {
            $template = $this->twig->load('book/index.html.twig');
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $template->render([
            'return' => json_encode([
                'data' => json_encode('ok'),
                'books' => json_encode($books),
            ]),
        ]);
    }
}
