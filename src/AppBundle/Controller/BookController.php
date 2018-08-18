<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Forms\BookFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends Controller
{

    /**
     * @Route("/index",name="books_all")
     */
    public function indexAction()
    {
        $books = $this->getDoctrine()->getRepository(Book::class)
            ->getAllBooks();
        return $this->render('application/book/list.html.twig', array(
            'books'=> $books
        ));
    }

    /**
     * @Route("/create",name="book_create")
     */
    public function createAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $book = $form->getData();
            $book->setStatus(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('books_all');
        }

        return $this->render('application/book/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/update")
     */
    public function updateAction()
    {
        return $this->render('AppBundle:Book:update.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/show")
     */
    public function showAction()
    {
        return $this->render('AppBundle:Book:show.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/delete")
     */
    public function deleteAction()
    {
        return $this->render('AppBundle:Book:delete.html.twig', array(
            // ...
        ));
    }

}
