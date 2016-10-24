<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 02-11-2015
 * Time: 16:19
 */
//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Methods: GET, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

class AmazonData extends CI_Controller
{
    /**
     *
     */

    public function __construct($config = 'rest')
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        parent::__construct();
    }

    public function test()
    {
       $_POST=json_decode(file_get_contents('php://input'),true);
        //echo "Category in PHP".$_POST['category'];
       return( $this->getProductsBasedOnCategory($_POST['category'],"NewReleases"));
       // return( $this->getProductsBasedOnCategory("DVD","NewReleases"));
    }

    public function test1()
    {
        $_POST=json_decode(file_get_contents('php://input'),true);
        return( $this->productDetail($_POST['ASIN']));

    }

    public function getProductsBasedOnCategory($category,$responseGroup){
        $this->load->model('amazonapi');
        $response=$this->amazonapi->getProducts($category,$responseGroup);
        print_r(json_encode($response));
        return json_encode($response);
    }

    public function productDetail($ASIN){
        $this->load->model('amazonapi');
        $response=$this->amazonapi->getProductDetails($ASIN);
        print_r(json_encode($response)) ;
        return json_encode($response);
    }
}