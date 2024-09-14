<?php
require_once "membership.class.php";  // Assuming you have a Product class with the delete method

if(isset($_GET['id'])){
    $productObj = new Product();
    $recordId = $_GET['id'];

    if($productObj->delete($recordId)){
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
