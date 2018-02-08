<?php
	//Start session
	if(!isset($_SESSION))
	{
	session_start();
	}
	
require_once('../functions.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
db_connect();
	
	//Sanitize the POST values
	$name = clean($_POST['name']);
	$password = clean($_POST['password']);
	
	//Input Validations
	if($name == '') {
		$errmsg_arr[] = 'Username-ul lipseste';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Parola lipseste';
		$errflag = true;
	}

	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: ../../index.php?page=login-failed");
		exit();
	}
	
	//Create query
	$query="SELECT * FROM users WHERE name='$name' AND password='".md5($_POST['password'])."'";
	$result=mysqli_query($mysqlLink, $query);
	
	//Check whether the query was successful or not
	if($result) {
		if(mysqli_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$user = mysqli_fetch_assoc($result);
			$_SESSION['SESS_ID'] = $user['id'];
			$_SESSION['SESS_NAME'] = $user['name'];
			session_write_close();
			header("location: ../../index.php?page=home");
			exit();
		} else {
			//Login failed
			header("location: ../../index.php?page=login-failed");
			exit();
		}
	} else {
		die("Query failed");
	}
?>
