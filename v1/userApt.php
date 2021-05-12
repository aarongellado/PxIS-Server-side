<?php

require_once '../includes/DbOperations.php';

$response = array();
if($_SERVER['REQUEST_METHOD']=='POST'){

  if( 
    isset($_POST['pxu']) and
    isset($_POST['pxName']) and
    isset($_POST['aptDate']) and
    isset($_POST['aptPurpose'])
    )
    {
      
      $db = new DbOperations();

      $result = $db->createAppointment($_POST['pxu'],$_POST['pxName'],$_POST['aptDate'],$_POST['aptPurpose']);

      if($result==1){
        $response['error']=false;
        $response['message']="Appointment request submitted";
      }
      elseif($result==2){
        $response['error']=true;
        $response['message']="Some error occured, please try again";
      }
      elseif($result==0){
        $response['error']=true;
        $response['message']="Appointment already exists";
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