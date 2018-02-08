<?php

	if(!isset($_SESSION)) {
	session_start();
	}

	//Unset the variables stored in session
	unset($_SESSION['SESS_ID']);
	unset($_SESSION['SESS_NAME']);
	session_destroy();
	
?>

<h1>Ati fost deconectat. </h1>
<p align="center"><a href="index.php?page=login">Click aici pentru autentificare.</a></p>