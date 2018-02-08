<?php

	//Start session
	if(!isset($_SESSION))
	{
	session_start();
	}
	
require_once('functions.php');
	
	//Vector cu erori de validare
	$errmsg_arr = array();
	
	//Flag pentru eroare de validare
	$errflag = false;
	
db_connect();

	//Sanitize the POST values
	$nume = clean($_POST['add_client_nume_input']);
	$persoana_contact = clean($_POST['add_client_persoanacontact_input']);
	$telefon = clean($_POST['add_client_telefon_input']);
	$nume_firma = clean($_POST['add_client_numefirma_input']);
	$cui = clean($_POST['add_client_cui_input']);
	$j = clean($_POST['add_client_j_input']);
	$banca = clean($_POST['add_client_banca_input']);
	$adresa = clean($_POST['add_client_adresa_input']);
	$discount = clean($_POST['add_client_discount_input']);
	if(!empty($_POST['add_client_agentie_chk'])) {
		$agentie = '1';
	} else {
		$agentie = '0';	
	}
	
	//Validare
	if($nume == '') {
		$errmsg_arr[] = 'Va rugam sa specificati numele clientului';
		$errflag = true;
	}
	
	//Verifica daca exista deja un client cu numele "nume"
	if($nume != '') {
		$query = "SELECT * FROM clienti WHERE nume='$nume'";
		$result = mysqli_query($mysqlLink, $query);
		if($result) {
			if(mysqli_num_rows($result) > 0) {
				$errmsg_arr[] = 'Clientul cu numele <b>'.$nume.'</b> exista deja';
				$errflag = true;
			}
			mysqli_free_result($result);
		} else {
			die("Query failed");
		}
	}
	
	//Daca sunt erori, redirectioneaza la pagina de adaugare client
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: ../index.php?page=clienti&action=add-client");
		exit();
	}
	
	//Create INSERT query
	$query = "INSERT INTO clienti(nume, persoana_contact, telefon, nume_firma, cui, j, banca, adresa, discount, agentie) VALUES('$nume','$persoana_contact','$telefon','$nume_firma','$cui','$j','$banca','$adresa','$discount','$agentie')";
	$result = mysqli_query($mysqlLink, $query);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../index.php?page=clienti&action=get");
		exit();
	} else {
		die("Query failed");
	}

?>
