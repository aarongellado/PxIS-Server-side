<?php

require_once '../includes/DbOperations.php';

$response = array();
if($_SERVER['REQUEST_METHOD']=='POST'){

  if( 
    isset($_POST['pxu']) and
    isset($_POST['pxp']) and
    isset($_POST['email']) and
    isset($_POST['FirstName']) and
    isset($_POST['MiddleName']) and
    isset($_POST['LastName']) and
    isset($_POST['Age']) and
    isset($_POST['Address']) and
    isset($_POST['BirthDate']) and
    isset($_POST['Gender'])
    )
    {
      
      $db = new DbOperations();

      $result = $db->createUser($_POST['pxu'],$_POST['pxp'],$_POST['email'],$_POST['FirstName'],$_POST['MiddleName'],$_POST['LastName'],$_POST['Age'],$_POST['Address'],$_POST['BirthDate'], $_POST['Gender']);

      if($result==1){
        $response['error']=false;
        $response['message']="User registered successfully";
      }
      elseif($result==2){
        $response['error']=true;
        $response['message']="Some error occured, please try again";
      }
      elseif($result==0){
        $response['error']=true;
        $response['message']="Username or Email already exists";
      }


    }
    else{
      $response['error']=true;
      $response['message']="required fields are missing";
    }


}
else{
$response['error'] = true;
$response['message']="Invalid Request";
}

echo json_encode($response);