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
	$cpassword = clean($_POST['cpassword']);
	
	//Input Validations
	if($name == '') {
		$errmsg_arr[] = 'Va rugam sa specificati un username';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Va rugam sa specificati o parola';
		$errflag = true;
	}
	if($cpassword == '') {
		$errmsg_arr[] = 'Campul de confirmare a parolei este gol';
		$errflag = true;
	}
	if( strcmp($password, $cpassword) != 0 ) {
		$errmsg_arr[] = 'Parolele nu se potrivesc';
		$errflag = true;
	}
	
	//Check for duplicate login ID
	if($name != '') {
		$query = "SELECT * FROM users WHERE name='$name'";
		$result = mysqli_query($mysqlLink, $query);
		if($result) {
			if(mysqli_num_rows($result) > 0) {
				$errmsg_arr[] = 'Username-ul exista deja';
				$errflag = true;
			}
			mysqli_free_result($result);
		} else {
			die("Query failed");
		}
	}
	
	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: ../../index.php?page=register");
		exit();
	}

	//Create INSERT query
	$query = "INSERT INTO users(name, password) VALUES('$name','".md5($_POST['password'])."')";
	$result = mysqli_query($mysqlLink, $query);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../../index.php?page=register-success");
		exit();
	} else {
		die("Query failed");
	}
?>
