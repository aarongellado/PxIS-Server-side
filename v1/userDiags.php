<?php

require_once '../includes/DbOperations.php';

$response = array();
if($_SERVER['REQUEST_METHOD']=='POST'){

  if( 
    isset($_POST['pxu']) and
    isset($_POST['drName']) and
    isset($_POST['drDiag'])
    )
    {
      
      $db = new DbOperations();

      $result = $db->writeDiagnosis($_POST['pxu'],$_POST['drName'],$_POST['drDiag']);

      if($result==1){
        $response['error']=false;
        $response['message']="New Diagnosis Created";
      }
      elseif($result==2){
        $response['error']=true;
        $response['message']="Some error occured, please try again";
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