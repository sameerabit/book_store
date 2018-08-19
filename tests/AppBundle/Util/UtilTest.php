<?php
namespace Tests\AppBundle\Util;

use AppBundle\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testCalculateDiscount(){
        $util = new Util();
        $invoice = array();
        $invoice["Children"]["items"] = array(
            0 => array("id"=>1,"name"=>"ABC","quantity"=>5,"unitPrice"=>15),
            1 => array("id"=>2,"name"=>"ABC1","quantity"=>5,"unitPrice"=>15),
            2 => array("id"=>3,"name"=>"ABC2","quantity"=>5,"unitPrice"=>15),
            4 => array("id"=>4,"name"=>"ABC3","quantity"=>5,"unitPrice"=>15),
            5 => array("id"=>5,"name"=>"ABC4","quantity"=>5,"unitPrice"=>15),
            6 => array("id"=>6,"name"=>"ABC5","quantity"=>5,"unitPrice"=>15),
            7 => array("id"=>7,"name"=>"ABC6","quantity"=>5,"unitPrice"=>15),
            8 => array("id"=>8,"name"=>"ABC7","quantity"=>5,"unitPrice"=>15),
        );
        $invoice[Util::FICTION]["items"] = array();
        $invoice[Util::CHILDREN]["subTotal"] = 500;
        $invoice[Util::FICTION]["subTotal"] = 0;
        $res = $util->calculateDiscount($invoice,false);
        $this->assertEquals(25,$res["Children"]["discount"]);
    }

    public function testCalculateDiscountWithCoupon(){
        $util = new Util();
        $invoice = array();
        $invoice["Children"]["items"] = array(
            0 => array("id"=>1,"name"=>"ABC","quantity"=>5,"unitPrice"=>15),
            1 => array("id"=>2,"name"=>"ABC1","quantity"=>5,"unitPrice"=>15),
            2 => array("id"=>3,"name"=>"ABC2","quantity"=>5,"unitPrice"=>15),
            6 => array("id"=>6,"name"=>"ABC5","quantity"=>5,"unitPrice"=>15),
            7 => array("id"=>7,"name"=>"ABC6","quantity"=>5,"unitPrice"=>15),
            8 => array("id"=>8,"name"=>"ABC7","quantity"=>5,"unitPrice"=>15),
        );
        $invoice[Util::FICTION]["items"] = array();
        $invoice[Util::CHILDREN]["subTotal"] = 750;
        $invoice[Util::FICTION]["subTotal"] = 630;
        $invoice["total"] = 1380;
        $res = $util->calculateDiscount($invoice,true);
        $this->assertEquals(138,$res["discount"]);
    }

    public function testCalculateDiscountWithEachTenBuy(){
        $util = new Util();
        $invoice = array();
        $invoice["Children"]["items"] = array(
            0 => array("id"=>1,"name"=>"ABC","quantity"=>5,"unitPrice"=>15),
            1 => array("id"=>2,"name"=>"ABC1","quantity"=>5,"unitPrice"=>15),
            2 => array("id"=>3,"name"=>"ABC2","quantity"=>5,"unitPrice"=>15),
            3 => array("id"=>4,"name"=>"ABC2","quantity"=>5,"unitPrice"=>15),
            4 => array("id"=>5,"name"=>"ABC2","quantity"=>5,"unitPrice"=>15),
            5 => array("id"=>11,"name"=>"ABC2","quantity"=>5,"unitPrice"=>15),
            6 => array("id"=>6,"name"=>"ABC5","quantity"=>5,"unitPrice"=>15),
            7 => array("id"=>7,"name"=>"ABC6","quantity"=>5,"unitPrice"=>15),
            8 => array("id"=>8,"name"=>"ABC7","quantity"=>5,"unitPrice"=>15),
            9 => array("id"=>9,"name"=>"ABC7","quantity"=>5,"unitPrice"=>15),
            10 => array("id"=>10,"name"=>"ABC7","quantity"=>5,"unitPrice"=>15),
        );
        $invoice["Fiction"]["items"] = array(
            0 => array("id"=>1,"name"=>"ABASC","quantity"=>5,"unitPrice"=>15),
            1 => array("id"=>2,"name"=>"ABSDC1","quantity"=>5,"unitPrice"=>15),
            2 => array("id"=>3,"name"=>"ABSDCC2","quantity"=>5,"unitPrice"=>15),
            3 => array("id"=>4,"name"=>"ABSDCC2","quantity"=>5,"unitPrice"=>15),
            4 => array("id"=>5,"name"=>"ABSDCC2","quantity"=>5,"unitPrice"=>15),
            6 => array("id"=>6,"name"=>"ABCSA5","quantity"=>5,"unitPrice"=>15),
            7 => array("id"=>7,"name"=>"ABC6323","quantity"=>5,"unitPrice"=>15),
            8 => array("id"=>8,"name"=>"ABC798809","quantity"=>5,"unitPrice"=>15),
            9 => array("id"=>9,"name"=>"ABC744343","quantity"=>5,"unitPrice"=>15),
            10 => array("id"=>10,"name"=>"43334ABC7","quantity"=>5,"unitPrice"=>15),
        );
        $invoice[Util::CHILDREN]["subTotal"] = 750;
        $invoice[Util::FICTION]["subTotal"] = 630;
        $invoice["total"] = 1380;
        $res = $util->calculateDiscount($invoice,false);
        $this->assertEquals(67.125,$res["discount"]);
    }

    public function testCalculateTotal(){
        $util = new Util();
        $cart["items"] = array(
            0 => array("id"=>1,"name"=>"ABASC","quantity"=>5,"unitPrice"=>15),
            1 => array("id"=>2,"name"=>"ABSDC1","quantity"=>5,"unitPrice"=>15),
            2 => array("id"=>3,"name"=>"ABSDCC2","quantity"=>5,"unitPrice"=>15),
            3 => array("id"=>4,"name"=>"ABSDCC2","quantity"=>5,"unitPrice"=>15),
            4 => array("id"=>5,"name"=>"ABSDCC2","quantity"=>5,"unitPrice"=>15),
            6 => array("id"=>6,"name"=>"ABCSA5","quantity"=>5,"unitPrice"=>15),
            7 => array("id"=>7,"name"=>"ABC6323","quantity"=>5,"unitPrice"=>15),
            8 => array("id"=>8,"name"=>"ABC798809","quantity"=>5,"unitPrice"=>15),
            9 => array("id"=>9,"name"=>"ABC744343","quantity"=>5,"unitPrice"=>15),
            10 => array("id"=>10,"name"=>"43334ABC7","quantity"=>5,"unitPrice"=>15),
        );
        $res = $util->calculateTotal($cart);
        $this->assertEquals(750,$res["cartTotal"]);
    }
}
