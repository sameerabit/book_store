<?php
/**
 * Created by PhpStorm.
 * User: sameera
 * Date: 8/18/18
 * Time: 10:53 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Book;
use AppBundle\Util;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart")
 */
class CartController extends Controller
{

    const FICTION = "Fiction";
    const CHILDREN = "Children";

    private $util;

    public function getUtil(){
        if(is_null($this->util)){
           $this->util = new Util();
        }
        return $this->util;
    }

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
            $content["category"] = $book->getCategory()->getName();
            $content["description"] = $book->getDescription();
            $content["unitPrice"] = $book->getUnitPrice();
            $cartItems["items"][$id] = $content;
        }
        $cartItems = $this->getUtil()->calculateTotal($cartItems);
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
                $cartItems = $this->getUtil()->calculateTotal($cartItems);
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
                $cartItems = $this->getUtil()->calculateTotal($cartItems);
                $session->set("cartItems",$cartItems);
            }
        }
        return $this->json($cartItems);
    }



    /**
     * @Route("/checkout",name="checkout_cart")
     */
    public function checkOut(Request $request){
        $isCouponCodeEnabled = false;
        $session = $request->getSession();
        $cartItems = $session->get("cartItems");
        $invoice = array();
        $fictionTotal = 0;
        $childrenTotal = 0;
        foreach ($cartItems["items"] as $cartItem){
            $invoice[Util::FICTION]["subTotal"] = 0;
            $invoice[Util::FICTION]["items"]=[];
            $invoice[Util::FICTION]["discount"] = 0;
            if($cartItem["category"]==Util::FICTION){
                $fictionTotal += $cartItem['quantity']*$cartItem["unitPrice"];
                $invoice[Util::FICTION]["items"][] = $cartItem;
                $invoice[Util::FICTION]["subTotal"] = $fictionTotal;
                $invoice[Util::FICTION]["discount"] = 0;

            }
            if($cartItem["category"]==Util::CHILDREN){
                $childrenTotal += $cartItem['quantity']*$cartItem["unitPrice"];
                $invoice[Util::CHILDREN]["items"][] = $cartItem;
                $invoice[Util::CHILDREN]["subTotal"] = $childrenTotal;
                $invoice[Util::CHILDREN]["discount"] = 0;

            }
        }
        $invoice["total"] = $invoice[Util::CHILDREN]["subTotal"] + $invoice[Util::FICTION]["subTotal"];
        $request->request->has("coupon_code");
        if(strlen($request->request->get("coupon_code"))>0){
            $isCouponCodeEnabled = true;
        }
        $invoice = $this->getUtil()->calculateDiscount($invoice,$isCouponCodeEnabled);
        return $this->render('application/cart/invoice.html.twig', array(
            "invoice" => $invoice,
        ));
    }
}
