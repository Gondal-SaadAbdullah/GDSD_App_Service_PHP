<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require APP . 'model/rentalListingModel.php';

 /**
  * @author saad, osama
  */
class ios_searchresults extends Controller {
    
    function __construct() {
        parent::__construct();
        $this->rental_ids = array();
        $this->rental_id_to_title = array();
        $this->rental_id_to_price = array();
        $this->rental_id_to_date_posted = array();
        $this->rental_id_to_images = array();
        $this->rental_listing_model = new RentalListingModel($this->db);
    }
    
    public function index() {
        
    }
    
    public function getPropertyListingsWithCount()
    {
//        $requestBody = file_get_contents("php://input");
//        $jsonValues = json_decode($requestBody, true);
//        
//        $count = $jsonValues["count"];
        
        $count = $_GET["count"];
        
        //echo $count;
        //die();
                
        $returnValue = array();
        
        $returnValue = $this->rental_listing_model->getPostedPropertiesWithCount($count);
        
        //header('Content-Type: application/json'); 
        //$returnValue["Status"] = "Success";
               
        echo ($returnValue);
        return;
    }
    
    public function searchPropertyListingsByParameters()
    {
        $title = $_GET["title"];
        $address = $_GET["address"];
        $zipcode = $_GET["zipcode"];
        $rentFrom = $_GET["rentFrom"];
        $rentTo = $_GET["rentTo"];
        $roomatesFrom = $_GET["roomatesFrom"];
        $roomatesTo = $_GET["roomatesTo"];
        $animals = $_GET["animals"];
        
        $returnValue = array();
        
        //echo $title;
        header('Content-Type: application/json');
        $returnValue = $this->rental_listing_model->getListingsWithParameter($title, $address, $zipcode, $rentFrom, $rentTo, $roomatesFrom, $roomatesTo, $animals);
        
        echo $returnValue;
        return;
    }
}
