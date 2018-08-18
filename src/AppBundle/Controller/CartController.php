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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/book/{id}",name="cart_item")
     */
    public function addToCart(Request $request,$id){
        $session = $request->getSession();
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository(Book::class)
            ->find($id);
        if (!$session->has("cartItems")) {
            $newCart = array();
            $session->set("cartItems",$newCart);
        }
        $cartItems = $session->get("cartItems");
        if(!array_key_exists($id,$cartItems)){
            $content = array();
            $content["id"] = $book->getId();
            $content["name"] = $book->getName();
            $content["quantity"] = 1;
            $content["description"] = $book->getDescription();
            $content["unitPrice"] = $book->getUnitPrice();
            $cartItems["items"][$id] = $content;
        }
        $cartItems = $this->calculateTotal($cartItems);
        $session->set("cartItems",$cartItems);

        return $this->redirectToRoute('cart_view');

    }

    /**
     * @Route("/view",name="cart_view")
     */
    public function viewCart(Request $request){
        $session = $request->getSession();
        $cartItemCount = 0;
        if (!$session->has("cartItems")) {
            $newCart = array();
            $session->set("cartItems",$newCart);
        }else{
            $cartItems = $session->get("cartItems");
            $cartItemCount = count($cartItems["items"]);
        }

        return $this->render('application/cart/checkout.html.twig', array(
            "cart" => $session->get("cartItems"),
            "cartItemCount"=>$cartItemCount
        ));
    }

    /**
     * @Route("/delete/{id}",name="remove_cart_item")
     */
    public function removeFromCart(Request $request,$id){
        $session = $request->getSession();
        if ($session->has("cartItems")) {
            $cartItems = $session->get("cartItems");
            if(array_key_exists($id,$cartItems)){
                unset($cartItems["items"][$id]);
                $cartItems = $this->calculateTotal($cartItems);
                $session->set("cartItems",$cartItems);
            }
        }
        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/edit/{id}",name="edit_cart_item")
     */
    public function editCart(Request $request,$id){
        $session = $request->getSession();
        $data = $request->request->all();
        $cartItems = array();
        if ($session->has("cartItems")) {
            $cartItems = $session->get("cartItems");
            if(array_key_exists($id,$cartItems["items"])){
                $cartItems["items"][$id]["quantity"]=$data["qty"];
                $cartItems = $this->calculateTotal($cartItems);
                $session->set("cartItems",$cartItems);
            }
        }
        return $this->json($cartItems);
    }

    public function calculateTotal($cartItems){
        $cartTotal = 0;
        foreach ($cartItems["items"] as $cartItem){
            $cartTotal += $cartItem['quantity']*$cartItem['unitPrice'];
        }
        $cartItems["cartTotal"] = $cartTotal;
        return $cartItems;
    }
}
