<?php
	//usable flags: type, search, cindex
	//page id check
	if($_GET['page'] == 'clienti') {
	
require_once('functions.php');
db_connect();

		//-----------------add_client
		if($_GET['action'] == 'add-client') {
			?>
            
            <div class="clienti_header">

				<?php
				
					if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0 ) {
						echo '<ul class="err">';
						foreach($_SESSION['ERRMSG_ARR'] as $msg) {
							echo '<li>',$msg,'</li>'; 
						}
						echo '</ul>';
						unset($_SESSION['ERRMSG_ARR']);
					}
						
				?>
                
            </div><!-- .clienti_header -->
            
            <div class="clienti_content">
            
            	<?php add_client(); ?>
                
            </div><!-- .clienti_content -->
				
            <?php
			unset($_GET['action']);
		//-----------------add_client
		} else {
			//-----------------get cu parametri
			if($_GET['action'] == 'get') {
				
				if(!isset($_GET['type'])) {
					$_GET['type'] = 'all';
				}
				if(!isset($_GET['search'])) {
					$_GET['search'] = '';
				}
				if(!isset($_GET['cindex'])) {
					$_GET['cindex'] = 1;
				} else {
					$_GET['cindex'] = (int) $_GET['cindex'];
				}
				?>
                
                <div class="clienti_header">
                </div><!-- .clienti_header -->
                
                <div class="clienti_content">
                
					<?php list_clients($_GET['type'], $_GET['search'], $_GET['cindex']); ?>
				 
                </div><!-- .clienti_content -->
                
                <?php
				unset($_GET['action']);
			//-----------------get cu parametri
			} else {
				//-----------------delete
				if($_GET['action'] == 'delete') {
					delete_client($_GET['client-id']);
					unset($_GET['action']);
				//-----------------delete
				} else {
					session_write_close();
					header('location: index.php?page=access-denied');
					exit();
				}
			}
		}
		
	}
	
function list_clients($type, $search, $cindex) {
    global $mysqlLink;

	echo('Clienti: </br>');
	$escaped_search = mysqli_real_escape_string($mysqlLink, $search);
	$query = "SELECT COUNT(*) FROM clienti";
	//filtrare dupa tip client
	if($type != 'all') {
		if($type == 'agentie') {
			$query .= " WHERE (agentie = '1')";
		} else {
			if($type == 'client-intern') {
				$query .= " WHERE (agentie = '0')";
			} else {
				session_write_close();
				header('location: index.php?page=access-denied'); //totodata si verificare daca variabila type este valida
				exit();	
			}
		}
	}
	//cautare dupa nume
	if($search != '') {
		if($type != 'all') {
			$query .= " AND (nume = '{$escaped_search}')";
		} else {
			$query .= " WHERE (nume = '{$escaped_search}')";
		}
	}
	$result = mysqli_query($mysqlLink, $query);
	if($result) {
		$r = mysqli_fetch_row($result);
		if($r[0] > 0) {
			
			//-----------afisare clienti daca exista cel putin unul
			
			$numrows = $r[0]; //nr total de clienti
			echo('Total: '.$numrows.' clienti</br>');
			
			$rowsperpage = 5; //clienti pe pagina
			
			$totalpages = ceil($numrows / $rowsperpage);
			if(isset($_GET['cindex']) && is_numeric($_GET['cindex'])) {
				$cindex = (int) $_GET['cindex'];
				} else {
					$cindex = 1;
				}
			if($cindex > $totalpages) {
				$cindex = $totalpages;
				}
			if($cindex < 1) {
   				$cindex = 1;
			}
			$offset = ($cindex - 1) * $rowsperpage;
				
			$query = "SELECT * FROM clienti";
			if($type != 'all') {
				if($type == 'agentie') {
					$query .= " WHERE (agentie = '1')";
				} else {
					if($type == 'client-intern') {
						$query .= " WHERE (agentie = '0')";
					}
				}
			}
			if($search != '') {
				if($type != 'all') {
					$query .= " AND (nume = '{$escaped_search}')";
				} else {
					$query .= " WHERE (nume = '{$escaped_search}')";
				}
			} else {
				$query .= " ORDER BY nume ASC";
			}
			$query .= " LIMIT $offset, $rowsperpage";
			$result = mysqli_query($mysqlLink, $query) or trigger_error("SQL", E_USER_ERROR);
			?>
            
			<table class="clienti_table" cellpadding="0" cellspacing="0">
				<tr class="table_header">
					<td class="nr">#</td>
					<td class="nume">Nume</td>
					<td class="persoanacontact">Persoana contact</td>
					<td class="telefon">Telefon</td>
					<td class="numefirma">Nume firma</td>
					<td class="cui">C.U.I.</td>
					<td class="j">J</td>
					<td class="banca">Banca</td>
					<td class="adresa">Adresa</td>
					<td class="discount">D%</td>
					<td class="agentie">Ag/CI</td>
					<td class="actiuni">Actiuni</td>
				</tr><!-- table_header -->
			
            <?php
            $pos = 1;
			$number = (($cindex - 1) * $rowsperpage)+1;
			while($client = mysqli_fetch_assoc($result)) {
				
				if($pos%2 == 1) {
					echo( '<tr class="odd">' );
				} else {
					echo( '<tr class="even">' );
				}
				
					echo( '<td>'			.$number.						'</td>' );
					echo( '<td>'			.$client['nume'].				'</td>' );
					echo( '<td>'			.$client['persoana_contact']. 	'</td>' );
					echo( '<td>'			.$client['telefon'].			'</td>' );
					echo( '<td>'			.$client['nume_firma'].			'</td>' );
					echo( '<td>'			.$client['cui'].				'</td>' );
					echo( '<td>'			.$client['j'].					'</td>' );
					echo( '<td>'			.$client['banca'].				'</td>' );
					echo( '<td>'			.$client['adresa'].				'</td>' );
					echo( '<td>'			.$client['discount'].			'</td>' );
					echo( '<td>' ); //td agentie/client_intern
					if($client['agentie'] == '1') {
						echo('Agentie');
					} else {
						if($client['agentie'] == '0') {
							echo('Client intern');
						}
					}
					echo( '</td>' ); //td agentie/client_intern
					echo( '<td><a class="client_delete" href="index.php?page=clienti&action=delete&type='.$_GET['type'].'&search='.$_GET['search'].'&cindex='.$_GET['cindex'].'&client-id='.$client['id'].'">Sterge</a></td>' );
				
				echo( '</tr><!-- .clienti_row -->' );
				$pos++; //pentru pare sau impare
				$number++; //pentru coloana de enumerare
			
			} //endwhile
			?>
            
			</table><!-- .clienti_table -->
			
            <?php
			//-----------sfarsit afisare clienti
						
			echo('<div class="pagination">');	//--------------------pagination start
			
				$range = 2; //raza de afisare a paginilor
				
				//paginile numerotate dinaintea paginii curente
				echo('<div class="pagination_prev">');
					for($x = ($cindex - 1); $x > (($cindex - $range)-1); $x--) {
						if($x > 0) {
							echo ( '<input type="button" class="prev_pages" onclick="location.href='."'".'index.php?page=clienti&action=get&type='.$type.'&search='.$search.'&cindex='.$x."'".'" value="'.$x.'" />');
						}
					}
					// arata butoanele de "prima pagina" si "pagina precedenta"
					if ($cindex > 1) {
						$prevpage = $cindex - 1;
						echo ( '<input type="button" class="prev_pages" onclick="location.href='."'".'index.php?page=clienti&action=get&type='.$type.'&search='.$search.'&cindex='.$prevpage."'".'" value="<" />' );
						echo ( '<input type="button" class="prev_pages" onclick="location.href='."'".'index.php?page=clienti&action=get&type='.$type.'&search='.$search.'&cindex=1'."'".'" value="<<"/>' );
					}
				echo('</div>');
				//pagina curenta
				echo('<div class="pagination_current">'.$cindex.'</div>');
				//paginile numerotate de dupa pagina curenta
				echo('<div class="pagination_next">');
					if ($cindex != $totalpages) {
						for($x = ($cindex + 1); $x < (($cindex + $range) + 1); $x++) {
							if($x <= $totalpages) {
								echo ( '<input type="button" class="next_pages" onclick="location.href='."'".'index.php?page=clienti&action=get&type='.$type.'&search='.$search.'&cindex='.$x."'".'" value="'.$x.'" />');
							}
						}
						//butoanele de "pagina urmatoare" si "ultima pagina"
						$nextpage = $cindex + 1;
						echo ( '<input type="button" class="next_pages" onclick="location.href='."'".'index.php?page=clienti&action=get&type='.$type.'&search='.$search.'&cindex='.$nextpage."'".'" value=">" />' );
						echo ( '<input type="button" class="next_pages" onclick="location.href='."'".'index.php?page=clienti&action=get&type='.$type.'&search='.$search.'&cindex='.$totalpages."'".'" value=">>" />' );
					}
				echo('</div>');
			echo('</div>');	//--------------------pagination end
				
		} else { //else de la verificare numar clienti in query
			echo('Nu s-a gasit nici un client de tip '.$type.' si nume '.$search.'!');
		}
		@mysqli_free_result($result);
	} else {
		die("Query failed");
	}
	
}

function delete_client($id) {
	
	$query = "DELETE FROM clienti WHERE id='".$id."'";
	$result = mysqli_query($mysqlLink, $query);
	mysqli_free_result($result);
	header('location: index.php?page=clienti&action=get&type='.$_GET['type'].'&search='.$_GET['search'].'&cindex='.$_GET['cindex']);
	
}

function add_client() {
	
	?>
    
    <div class="add_clientForm_container">
	<form name="add_clientForm" method="post" action="includes/add_client.php">
		<table class="add_client_table" cellpadding="2" cellspacing="0">
        	<tr>
            	<td class="labels">Nume</td>
                <td><input name="add_client_nume_input" class="add_client_textfield" type="text" tabindex="1" maxlength="40"></td>
            </tr>
            <tr>
                <td>Agentie</td>
                <td><input name="add_client_agentie_chk[]" class="add_client_chk" type="checkbox" tabindex="2"></td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td><input name="add_client_telefon_input" class="add_client_textfield" type="text" tabindex="3" maxlength="10"></td>
            </tr>
            <tr>
                <td>Discount %</td>
                <td><input name="add_client_discount_input" class="add_client_textfield" type="text" tabindex="4" maxlength="3"></td>
            </tr>
            <tr>
                <td>Persoana contact</td>
                <td><input name="add_client_persoanacontact_input" class="add_client_textfield" type="text" tabindex="5" maxlength="40"></td>
            </tr>
            <tr>
                <td>Nume firma</td>
                <td><input name="add_client_numefirma_input" class="add_client_textfield" type="text" tabindex="6" maxlength="40"></td>
            </tr>
            <tr>
                <td>C.U.I.</td>
                <td><input name="add_client_cui_input" class="add_client_textfield" type="text" tabindex="7" maxlength="8"></td>
            </tr>
            <tr>
                <td>J</td>
                <td><input name="add_client_j_input" class="add_client_textfield" type="text" tabindex="8" maxlength="12"></td>
            </tr>
            <tr>
                <td>Banca</td>
                <td><input name="add_client_banca_input" class="add_client_textfield" type="text" tabindex="9" maxlength="40"></td>
            </tr>
            <tr>
                <td>Adresa</td>
                <td><input name="add_client_adresa_input" class="add_client_textfield" type="text" tabindex="10" maxlength="50"></td>
            </tr>
        </table>
        <input class="add_client_submit_btn" type="submit" name="Submit" tabindex="11" value="Adauga" /></td>
    </form>
    </div><!-- .add_clientForm_container -->
    
	<?php
}

?>
