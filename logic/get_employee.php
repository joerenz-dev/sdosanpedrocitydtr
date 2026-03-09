<?php
include '../db.php';

if(isset($_POST['employee_number'])){

$emp = $_POST['employee_number'];

$stmt = $conn->prepare("SELECT employee_name, functional_division
                        FROM employees 
                        WHERE employee_number = ?");
$stmt->bind_param("s",$emp);
$stmt->execute();

$result = $stmt->get_result();

if($row = $result->fetch_assoc()){
    echo json_encode($row);
}else{
    echo json_encode(null);
}

}
?>