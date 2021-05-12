<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
  if(isset($_POST['pxu']) and isset($_POST['pxp'])){
    $db = new DbOperations();
    
    if($db->userLogin($_POST['pxu'], $_POST['pxp'])){
      $user = $db->getUserByUsername($_POST['pxu']);
      $response['error'] = false;
      $response['pxu'] = $user['pxu'];
      $response['userAuth'] = $user['userAuth'];
      $response['email'] = $user['email'];
      $response['FirstName'] = $user['FirstName'];
      $response['MiddleName'] = $user['MiddleName'];
      $response['LastName'] = $user['LastName'];
      $response['Age'] = $user['Age'];
      $response['Address'] = $user['Address'];
      $response['BirthDate'] = $user['BirthDate'];
      $response['Gender'] = $user['Gender'];
      
    }
    else{
      $response['error'] = true;
      $response['message']="Invalid username or Password";
    }

  }

  else{
    $response['error'] = true;
    $response['message']="Required fields are missing";
  }
}
echo json_encode($response);