<div class="header">
		<ul class="left">
			<li id="header_menu_item" <?php echo(is_active_link('home')); ?>><a href="index.php?page=home">Acasa</a></li>
        	<li id="header_menu_item" <?php echo(is_active_link('user-options')); ?>><a href="index.php?page=user-options">Optiuni</a></li>
			<li id="header_menu_item" <?php echo(is_active_link('clienti')); ?>><a href="index.php?page=clienti&action=get&cindex=1">Clienti</a></li>
            <li id="header_menu_item" <?php echo(is_active_link('lucrari')); ?>><a href="index.php?page=lucrari">Lucrari</a></li>
            <li id="header_menu_item" <?php echo(is_active_link('facturare')); ?>><a href="index.php?page=facturare">Facturare</a></li>
        </ul>
		<ul class="right">
        	<li>Sunteti autentificat ca <b><?php echo($_SESSION['SESS_NAME']) ?></b> - <a href="index.php?page=logout">Deconectare</a><?php if (is_admin($_SESSION['SESS_NAME'])) { echo(' | <a href="index.php?page=register">Inregistrare User</a>');	} ?></li>
		</ul>
</div><!-- .header -->
<div class="navbar">
	<div class="navbar_buttons">
    	<ul>
    	<?php
			if($_GET['page'] == 'clienti') {
				echo('<li><a id="navbar_item" class="add_client_btn'.is_active_link('add-client').'" href="index.php?page=clienti&action=add-client">Adauga</a></li>' );
				echo('<li><a id="navbar_item" class="get_clients_btn'.is_active_link('get').'" href="index.php?page=clienti&action=get&cindex=1">Listeaza</a></li>' );
			}
		?>
		</ul>
    </div><!-- .navbar_buttons -->
</div><!-- .navbar -->