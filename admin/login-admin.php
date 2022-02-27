<?php
session_start();


include('database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$username=$_POST['username'];
$password=$_POST['password'];
$data[]='';
$sql="SELECT*FROM tbl_sample where USERNAME='$username' and PASSWORD='$password' LIMIT 1";
$stmt = $connect->prepare($sql);
if($stmt->execute())
{

	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		 $data= $row;
	}
}
$i=0;
foreach($data as &$arr){
    $i++;
   if($i==1){
        $_SESSION['user']=$username;
		header("Location:index.php");

   }
}

?>