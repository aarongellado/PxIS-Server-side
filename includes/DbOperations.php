<?php

class DbOperations{
  private $con;

  function __construct(){
    require_once dirname(__FILE__).'/DBConnect.php';
    $db = new DbConnect();

    $this->con = $db->connect();
  }

  /* CRUD -> C -> CRUD*/
  function createUser($pxu, $pxp, $email, $FirstName, $MiddleName, $LastName, $Age, $Address, $Birthdate, $Gender){
    if($this->ifUserExist($pxu,$email)){
      return 0;
    }
    else
      {
        $pxp=md5($pxp);
      $stmt = $this->con->prepare("INSERT INTO `px` (`pxu`, `pxp`, `email`, `FirstName`, `MiddleName`, `LastName`, `Age`, `Address`, `BirthDate`, `Gender`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
      $stmt->bind_param("ssssssisss",$pxu, $pxp, $email, $FirstName, $MiddleName, $LastName, $Age, $Address, $Birthdate, $Gender);

      if($stmt->execute()){
        return 1;
      }
      else{
        return 2;
      }
    }
  }

  public function userLogin($pxu, $pxp){
    $pxp = md5($pxp);
    $stmt = $this->con->prepare("SELECT * FROM px WHERE pxu = ? AND pxp = ?");
    $stmt->bind_param("ss", $pxu, $pxp);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
  }

  public function getUserByUsername($pxu){
    $stmt = $this->con->prepare("SELECT * FROM px WHERE pxu = ?");
    $stmt->bind_param("s",$pxu);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
  }

  private function ifUserExist($pxu, $email){
    $stmt = $this->con->prepare("SELECT * FROM px WHERE pxu = ? OR email = ?");
    $stmt->bind_param("ss",$pxu, $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;

  }

  function createAppointment ($pxu, $pxName, $aptDate, $aptPurpose){
    if($this->ifAptExist($pxu,$aptDate)){
      return 0;
    }
    else{
      $stmt = $this->con->prepare("INSERT INTO `pxappointments` (`pxu`, `pxName`, `aptDate`, `aptPurpose`) VALUES (?, ?, ?, ?);");
      $stmt->bind_param("ssss",$pxu, $pxName, $aptDate, $aptPurpose);
      if($stmt->execute()){
        return 1;
      }
      else{
        return 2;
      }
    }
  }

  private function ifAptExist($pxu, $aptDate){
    $stmt = $this->con->prepare("SELECT * FROM pxappointments WHERE pxu = ? AND aptDate = ?");
    $stmt->bind_param("ss",$pxu, $aptDate);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;

  }

  public function getAptList($pxu){
    $stmt = $this->con->prepare("SELECT aptDate, aptPurpose, pxName FROM pxappointments WHERE pxu = ? ORDER BY aptDate DESC");
    $stmt->bind_param("s",$pxu);
    $stmt->execute();
    $stmt->bind_result($aptListDate, $aptListPurpose,$aptListName);
    $appointments = array();
    while($stmt->fetch()){
      $temp = array();
      $temp['aptDate']=$aptListDate;
      $temp['aptPurpose']=$aptListPurpose;
      $temp['pxnName']=$aptListName;
      array_push($appointments,$temp);
    }
    return $appointments;
  }

  public function getMedsList($pxu){
    $stmt = $this->con->prepare("SELECT dateCreated, doctorName, dateValid, medications FROM px_medications WHERE pxu = ? ORDER BY dateCreated DESC");
    $stmt->bind_param("s",$pxu);
    $stmt->execute();
    $stmt->bind_result($dateCreated, $doctorName, $dateValid, $medications);
    $medsList = array();
    while($stmt->fetch()){
      $temp = array();
      $temp['dateCreated']=$dateCreated;
      $temp['doctorName']=$doctorName;
      $temp['dateValid']=$dateValid;
      $temp['medications']=$medications;
      array_push($medsList,$temp);
    }
    return $medsList;
  }

  public function getDiagsList($pxu){
    $stmt = $this->con->prepare("SELECT dateCreated, doctorName, diagnosis FROM px_findings WHERE pxu = ? ORDER BY dateCreated DESC");
    $stmt->bind_param("s",$pxu);
    $stmt->execute();
    $stmt->bind_result($dateCreated, $doctorName, $diagnosis);
    $diagsList = array();
    while($stmt->fetch()){
      $temp = array();
      $temp['dateCreated']=$dateCreated;
      $temp['doctorName']=$doctorName;
      $temp['diagnosis']=$diagnosis;
      array_push($diagsList,$temp);
    }
    return $diagsList;
  }


  //drAppSide

  public function getDrAptList(){
    $stmt = $this->con->prepare("SELECT aptDate, aptPurpose, pxName FROM pxappointments");
    $stmt->execute();
    $stmt->bind_result($aptListDate, $aptListPurpose,$aptListName);
    $appointments = array();
    while($stmt->fetch()){
      $temp = array();
      $temp['aptDate']=$aptListDate;
      $temp['aptPurpose']=$aptListPurpose;
      $temp['pxName']=$aptListName;
      array_push($appointments,$temp);
    }
    return $appointments;
  }
  
  
  public function writePrescription ($pxu, $drName, $medsDate, $pxMeds){
    
    $stmt = $this->con->prepare("INSERT INTO `px_medications` (`pxu`, `doctorName`, `dateValid`, `medications`) VALUES (?, ?, ?, ?);");
    $stmt->bind_param("ssss",$pxu, $drName, $medsDate, $pxMeds);
    if($stmt->execute()){
      return 1;
    }
    else{
      return 2;
    }
  }
  public function writeDiagnosis ($pxu, $drName, $drDiag){
    
    $stmt = $this->con->prepare("INSERT INTO `px_findings` (`pxu`, `doctorName`, `diagnosis`) VALUES (?, ?, ?);");
    $stmt->bind_param("sss",$pxu, $drName, $drDiag);
    if($stmt->execute()){
      return 1;
    }
    else{
      return 2;
    }
  }

}