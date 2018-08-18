<?php
/**
 * Created by PhpStorm.
 * User: sameera
 * Date: 8/18/18
 * Time: 10:53 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/item/{id}",name="cart_item")
     */
    public function addToCart($id){
        $book = $this->getDoctrine()->getRepository(Book::class)
            ->find($id);
    }

}