<?php
	
	if($_GET['page'] == 'register-success') {
		
		//redirect if not admin
		if(!is_admin($_SESSION['SESS_NAME'])) {
			header('location: index.php?page=access-denied');
			exit();
		}
		
		//---------------------->continutul paginii register-success---------------------->
		
		echo('<h1>Inregistrarea noului utilizator a fost efectuata cu succes.</h1>');
		
		//<----------------------continutul paginii register-success<----------------------
		
	}

?>