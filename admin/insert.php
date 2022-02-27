<?php

//insert.php

include('database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));

$error = '';
$message = '';
$validation_error = '';
$hoten = '';
$diachi = '';
$username='';
$password='';

if($form_data->action == 'fetch_single_data')
{
	$query = "SELECT * FROM tbl_sample WHERE id='".$form_data->id."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['hoten'] = $row['hoten'];
		$output['diachi'] = $row['diachi'];
		$output['username'] = $row['USERNAME'];
		$output['password'] = $row['PASSWORD'];
	}
}
elseif($form_data->action == "Delete")
{
	$query = "
	DELETE FROM tbl_sample WHERE id='".$form_data->id."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = 'Xóa thành công';
	}
}
else
{
	if(empty($form_data->hoten))
	{
		$error[] = 'Họ tên bắt buộc';
	}
	else
	{
		$hoten = $form_data->hoten;
	}

	if(empty($form_data->diachi))
	{
		$error[] = 'địa chỉ bắt buộc';
	}
	else
	{
		$diachi = $form_data->diachi;
	}
	if(empty($form_data->username))
	{
		$error[] = 'username bắt buộc';
	}
	else
	{
		$username = $form_data->username;
	}if(empty($form_data->password))
	{
		$error[] = 'password bắt buộc';
	}
	else
	{
		$password = $form_data->password;
	}



	if(empty($error))
	{
		if($form_data->action == 'Insert')
		{
			$data = array(
				':hoten'		=>	$hoten,
				':diachi'		=>	$diachi,
				':username'		=>	$username,
				':password'		=>	$password
			);
			$query = "
			INSERT INTO tbl_sample
				(hoten, diachi,USERNAME,PASSWORD) VALUES
				(:hoten, :diachi,:username,:password)
			";
			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Thêm thành công';
			}
		}
		if($form_data->action == 'Edit')
		{
			$data = array(
				':hoten'	=>	$hoten,
				':diachi'	=>	$diachi,
				':USERNAME'	=>	$username,
				':PASSWORD'	=>	$password,
				':id'		=>	$form_data->id
			);
			$query = "
			UPDATE tbl_sample
			SET hoten = :hoten, diachi = :diachi,username=:username,password=:password
			WHERE id = :id
			";

			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Sửa thành công';
			}
		}
	}
	else
	{
		$validation_error = implode(", ", $error);
	}

	$output = array(
		'error'		=>	$validation_error,
		'message'	=>	$message
	);

}



echo json_encode($output);

?>