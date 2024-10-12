<?php

namespace App\Controller;

use App\Entity\Author; // Assurez-vous que le chemin correspond à votre structure
use App\Repository\AuthorRepository; // Importez AuthorRepository
use Doctrine\Persistence\ManagerRegistry; // Importez ManagerRegistry
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;



class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    // Méthode qui permet d’afficher la liste des auteurs
    #[Route('/author/getall', name: 'app_author_getall')] // Correction de '/preson/add' à '/person/add'
    public function getAllAuthors(AuthorRepository $repo): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $repo->findAll(),
        ]);
    }

    // la méthode qui permet d’ajouter
    // #[Route('/author/add', name: 'app_author_add')] 
    // public function addAuthor(ManagerRegistry $manager): Response 
    // {
    //     $author = new author();
    //     $author->setUsername('rami_chatti');
    //     $author->setEmail("ramichatti14@gmail.com"); // Exemple de date
     
    //     $em = $manager->getManager();
    //     $em->persist($author);
    //     $em->flush();

    //     return $this->redirectToRoute('app_author_getall');
    // }

    // la méthode qui permet d’ajouter un auteur via le formulaire
    #[Route('/author/add', name: 'app_author_add')]
    public function addAuthor(Request $request, ManagerRegistry $manager): Response
    {
        $author = new Author(); // Crée une nouvelle instance d'auteur
        $form = $this->createForm(AuthorType::class, $author); // Crée le formulaire
        $form->handleRequest($request); // Gère la requête et lie les données du formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, on enregistre l'auteur
            $em = $manager->getManager();
            $em->persist($author);
            $em->flush();

            // Redirection vers la liste des auteurs
            return $this->redirectToRoute('app_author_getall');
        }

        // Affiche le formulaire si aucune soumission ou en cas d'erreur
        return $this->render('author/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // la méthode qui permet supprimer un auteur via le formulaire
    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor($id, ManagerRegistry $manager, AuthorRepository $repo): Response
    {
        $author = $repo->find($id);

        if (!$author) {
            // Vous pouvez rediriger avec un message d'erreur ou gérer la situation selon vos besoins
            throw $this->createNotFoundException('Aucun auteur trouvé avec cet ID');
        }

        $em = $manager->getManager();
        $em->remove($author);
        $em->flush();

        // Redirection vers la liste des auteurs
        return $this->redirectToRoute('app_author_getall');
        
    }

    // Edit
    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    public function editAuthor(Request $request, ManagerRegistry $manager, Author $author): Response
    {
        // Create the form using the existing Author entity
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request); // Handle the form submission

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $manager->getManager();
            $em->flush(); // Save changes to the database

            return $this->redirectToRoute('app_author_getall'); // Redirect to the list of authors
        }

        // Render the edit form if not submitted or if invalid
        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }
}
