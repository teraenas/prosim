<?php

	require_once('functions.php');
db_connect();
	$query = "DELETE FROM clienti WHERE id='".$_POST['client_id']."'";
	$result = mysqli_query($mysqlLink, $query);
	mysqli_free_result($result);

	header('location: ../index.php?page=clienti&action=get&filter=all&cindex=1');

?>
