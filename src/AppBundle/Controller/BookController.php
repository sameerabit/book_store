<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/book")
 */
class BookController extends Controller
{

    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        return $this->render('application/book/list.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/create")
     */
    public function createAction()
    {
        return $this->render('AppBundle:Book:create.html.twig', array(
            // ...
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
