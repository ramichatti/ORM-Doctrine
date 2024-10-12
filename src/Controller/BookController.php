<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // <-- Ajoute cette ligne
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;

class BookController extends AbstractController
{
    #[Route('/book/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        
        // Initialisation de published à true
        $book->setEnabled(true);

        // Création du formulaire
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'auteur associé au livre
            $author = $book->getAuthor();

            if ($author) {
                // Incrémenter le nombre de livres de l'auteur
                $author->setNbBooks($author->getNbBooks() + 1);
            }

            // Sauvegarder le livre et l'auteur
            $entityManager->persist($book);
            $entityManager->persist($author);
            $entityManager->flush();

            // Redirection ou message de succès
            return new Response('book_success');
        }

        return $this->render('book/add_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/books/published', name: 'books_published')]
    public function listPublishedBooks(BookRepository $bookRepository): Response
    {
        // Récupérer les livres publiés (enabled = true)
        $publishedBooks = $bookRepository->findBy(['enabled' => true]);

        // Compter le nombre total de livres
        $totalBooks = $bookRepository->count([]);
        // Compter le nombre de livres publiés
        $publishedCount = count($publishedBooks);
        // Calculer le nombre de livres non publiés
        $unpublishedCount = $totalBooks - $publishedCount;

        return $this->render('book/published_books.html.twig', [
            'books' => $publishedBooks,
            'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount,
            'totalBooks' => $totalBooks,
        ]);
    }

}
