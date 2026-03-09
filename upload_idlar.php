<?php
include 'db.php';
session_start();

date_default_timezone_set('Asia/Manila');

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $day = date('N'); 
    $time = date('H:i:s');

    $allowed = false;

    if($day == 5 && $time >= "17:00:00"){
        $allowed = true;
    }
    elseif($day == 6){
        $allowed = true;
    }
    elseif($day == 7){
        $allowed = true;
    }

    if(!$allowed){
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "IDLAR upload allowed only Friday 5PM to Sunday 12 Midnight.";
        header("Location: DTR.php");
        exit();
    }

    $emp_num = $_POST['employee_number'];
    $emp_name = $_POST['emp_name'];
    $division = $_POST['func_div_office'];

    $file = $_FILES['idlar_file']['name'];
    $tmp = $_FILES['idlar_file']['tmp_name'];

    $folder = "uploads/idlar/";

    if(!is_dir($folder)){
        mkdir($folder,0777,true);
    }

    if(move_uploaded_file($tmp,$folder.$file)){

        $stmt = $conn->prepare("INSERT INTO idlar_uploaded 
        (employee_number, emp_name, func_div_office, idlar_file) 
        VALUES (?,?,?,?)");

        $stmt->bind_param("ssss",$emp_num,$emp_name,$division,$file);

        if($stmt->execute()){
            $_SESSION['status'] = "success";
            $_SESSION['message'] = "IDLAR Successfully Uploaded.";
        }else{
            $_SESSION['status'] = "error";
            $_SESSION['message'] = "IDLAR Upload Failed.";
        }

    }else{
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "File Upload Failed.";
    }

    header("Location: DTR.php");
}
?>