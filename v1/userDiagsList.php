<?php

require_once '../includes/DbOperations.php';
if(isset($_POST['pxu'])){
  $db = new DbOperations();
  echo json_encode($db->getDiagsList($_POST['pxu']));
}

else{
  $response['error'] = true;
  $response['message']="Required fields are missing";
}