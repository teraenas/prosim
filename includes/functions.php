<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

$mysqlLink = null;

function db_connect() {
    global $mysqlLink;
	
	require_once('mysql_config.php');
	
	//Connect to mysql server
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysqli_select_db($link, DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

	$mysqlLink = $link;
}

function is_admin($name) {
    global $mysqlLink;
	
	db_connect();
	//Create query
	$query="SELECT * FROM users WHERE name='$name' AND level='0'";
	$result=mysqli_query($mysqlLink, $query);
	if ($result) {
		if(mysqli_num_rows($result) >= 1) {
			return true;
		} else {
			return false;
		}
	} else {
		die("Query failed");
	}
	
}

function is_logged_in() {

	if(!isset($_SESSION)) {
		session_start();
	}
	
	if(!isset($_SESSION['SESS_NAME']) || (trim($_SESSION['SESS_NAME']) == '')) {
				return false;
	} else {
		return true;
	}
	
}

function is_active_link($item) {
	
	if ($item == $_GET['page']) {
		
		return ('class = "selected"');
		
	}
	if (isset($_GET['action'])) {
		if ($item == $_GET['action']) {
			return ('-selected');	
		}
	}
	
}

function validateURL() {
	
	$pattern = 'prosim/index.php?page=';
	$curr_url = $_SERVER['REQUEST_URI'];
	$result = strpos($curr_url, $pattern);
	if ( $result === false  && !is_logged_in() ) {
		header('location: index.php?page=login');
		exit();
	} else {
		if ( $result === false  && is_logged_in() ) {
			session_write_close();
			header('location: index.php?page=home');
			exit();
		}
	}
	
}

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    global $mysqlLink;
	$str = @trim($str);
	if(get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return mysqli_real_escape_string($mysqlLink, $str);
}
