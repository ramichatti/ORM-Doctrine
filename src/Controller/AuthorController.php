<?php
namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;
use App\Form\SearchByDateType; // Respectez bien la casse
 // Importez votre formulaire de recherche par date

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/getall', name: 'app_author_getall')]
    public function getAllAuthors(Request $request, AuthorRepository $repo): Response
    {
        $form = $this->createForm(SearchBydateType::class);
        $form->handleRequest($request);

        $books = null; // Initialisation de $books

        if ($form->isSubmitted() && $form->isValid()) {
            $date1 = $form->get('startDate')->getData();
            $date2 = $form->get('endDate')->getData();
            $books = $repo->getBooksByDate($date1, $date2);
        }

        return $this->render('author/index.html.twig', [
            'authors' => $repo->findAll(),
            'authors_tri' => $repo->getAuthorsOrdedByName(),
            'a' => $repo->getAuthorsName('rami'),
            'bookdate' => $books,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/author/add', name: 'app_author_add')]
    public function addAuthor(Request $request, ManagerRegistry $manager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $manager->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('app_author_getall');
        }

        return $this->render('author/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor($id, ManagerRegistry $manager, AuthorRepository $repo): Response
    {
        $author = $repo->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Aucun auteur trouvé avec cet ID');
        }

        $em = $manager->getManager();
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('app_author_getall');
    }

    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    public function editAuthor(Request $request, ManagerRegistry $manager, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $manager->getManager();
            $em->flush();

            return $this->redirectToRoute('app_author_getall');
        }

        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }

    public function __toString()
    {
        return $this->getUsername(); // Correction pour correspondre à vos attributs
    }
}
