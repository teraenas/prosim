<?php

	//includes
	require_once('includes/functions.php');

	//start session
	if(!isset($_SESSION)) {
		session_start();
	}

	//initialize page variable
	validateURL();
	if ( !isset($_GET['page']) && !is_logged_in() ) {
		$_GET['page'] = "login";
	} else {
		if( !isset($_GET['page']) && is_logged_in() ) {
			$_GET['page'] = "home";
		}
	}
	if (!isset($valid_pages)) {
		
		$valid_pages = array('home', 'logout', 'register', 'register-success', 'user-options', 'clienti', 'access-denied', 'lucrari', 'facturare');
		$valid_admin_pages = array('register', 'register-success');
		
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Situatie Lucrari Prosim Impex</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />

</head>
<body>
<div class="container">

		<?php
		
			if(!is_logged_in()) {
				if ($_GET['page'] == 'login-failed') {
					include('includes/user_handling/login_failed.php');
				} else {
					if($_GET['page'] == 'login') {
						include('includes/user_handling/login_form.php');
					} else {
						$_GET['page'] = 'access-denied';
						include('includes/access_denied.php');
					}
				}	
			} else {
				
				//-------------------logged in content
				if ($_GET['page'] == 'logout') {
					include('includes/user_handling/logout.php');
				} else {
					
					?>
                    
					<?php include('includes/header.php'); ?>
                    
					<div class="content">
        
						<?php
						
							if(in_array($_GET['page'], $valid_pages) || in_array($_GET['page'], $valid_admin_pages)) {
								
								//include paginile pentru admin
									include(		'includes/user_handling/register_form.php'			);
									include(		'includes/user_handling/register_success.php'		);
								//sfarsit includere pagini admin
								
								include(		'includes/home.php'				);
								include(		'includes/user_options.php'		);
								include(		'includes/clienti.php'			);
								include(		'includes/lucrari.php'			);
								include(		'includes/facturare.php'			);
								include(		'includes/access_denied.php'		);
							} else {
								include(		'includes/404.php'				);
							}
							
						?>
		
		  			</div><!-- .content -->
        
        	<?php
						
				}
			}
				
			?>

</div><!-- .container -->

</body>
</html>
