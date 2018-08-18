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
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $cartItemCount = 0;
        if ($session->has("cartItems")) {
            $cartItems = $session->get("cartItems");
            $cartItemCount = count($cartItems["items"]);
        }
        $books = $this->getDoctrine()->getRepository(Book::class)
            ->getAllBooks();
        return $this->render('application/book/list.html.twig', array(
            'books'=> $books,
            "cartItemCount"=> $cartItemCount
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
            $entityManager->merge($book);
            $entityManager->flush();
            return $this->redirectToRoute('books_all');
        }

        return $this->render('application/book/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/update/{id}")
     */
    public function updateAction($id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)
            ->find($id);
        $form = $this->createForm(BookFormType::class, $book);
        return $this->render('application/book/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/show/{id}")
     */
    public function showAction($id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)
            ->find($id);
        return $this->render('application/book/show.html.twig', array(
            'book'=> $book
        ));
    }

    /**
     * @Route("/delete/{id}")
     */
    public function deleteAction($id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)
            ->find($id);
        if($book)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }
        return $this->redirectToRoute('books_all');
    }


}
