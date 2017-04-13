<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require APP . 'model/imageUploadsModel.php';
require APP . 'model/rentalListingModel.php';

class ios_postListings extends Controller {

    private $image_model;
    private $rental_listing_model;

    function __construct() {
        parent::__construct();

        $this->image_model = new ImageUploadsModel($this->db);
        $this->rental_listing_model = new RentalListingModel($this->db);
    }
    
    public function index() {
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert"><strong>Error!</strong>Upload Image size should be less than 2 MB.</div>';
        }
        require APP . 'view/_templates/header.php';
        require APP . "view/protoView/index.php";
        require APP . 'view/_templates/footer.php';
    }
    
    public function submitPost() {        
        
      //  echo "reached here";
        
        $requestBody = file_get_contents("php://input");
        $jsonValues = json_decode($requestBody, true);
        
        
        
        $title = $jsonValues["title"];
        $description = $jsonValues["description"];
        $address = $jsonValues["address"];
        $zipCode = $jsonValues["zipCode"];
        $rent = $jsonValues["rent"];
        $occupants = $jsonValues["occupants"];
        $animals = $jsonValues["animals"];
        
        $rentalListingType = "APARTMENT";
        
        //echo($zipCode);
        //die();
        
        
        
        $this->rental_listing_model->
        insertRentalListing($title, $description, $address, $zipCode, $rent, $this->rental_listing_model->getRentalType($rentalListingType), $occupants, $animals);
        
        $rental_listing_id = $this->rental_listing_model->getLatestId();
        
        $returnValues = array();
        $returnValues["status"] = "Success";
        $returnValues["message"] = "Property Posted Successfully";
        
        echo json_encode($returnValues);
        return;
        //echo($rental_listing_id);
//        die();
    }
}