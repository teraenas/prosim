<?php

	if($_GET['page']=='register') {
		
		//redirect if not admin
		if(!is_admin($_SESSION['SESS_NAME'])) {
			header('location: index.php?page=access-denied');
			exit();
		}
		
		//---------------------->continutul paginii register---------------------->

		if(!isset($_SESSION)) {
		session_start();
		}

		if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0 ) {
			echo '<ul class="err">';
			foreach($_SESSION['ERRMSG_ARR'] as $msg) {
				echo '<li>',$msg,'</li>'; 
			}
			echo '</ul>';
			unset($_SESSION['ERRMSG_ARR']);
		}
	
?>

<div class="form_container">
	<form id="loginForm" name="loginForm" method="post" action="includes/user_handling/register_exec.php">
  		<table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
    		<tr>
      			<th width="124">Username</th>
      		<td width="168"><input name="name" type="text" class="textfield" id="name" /></td>
    		</tr>
    		<tr>
      			<th>Parola</th>
      			<td><input name="password" type="password" class="textfield" id="password" /></td>
    		</tr>
    		<tr>
      			<th>Confirmare Parola </th>
      			<td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
    		</tr>
  		</table>
        <input style="margin-top:12px" type="submit" name="Submit" value="Inregistrare" />
	</form>
</div><!-- .form_container -->

<?php

		//<----------------------continutul paginii register<----------------------
		
	}

?>