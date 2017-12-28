<?php
	if(session_id() == '') {
		session_start();
	}
	require_once "functions.php";
	
	if(isset($_POST['action']) && $_POST['action'] == "collect"){
		$action = $_POST['action'];
		if($action == 'collect'){
			if(isset($_POST['user'])){
				$sites = $_SESSION['sites'];
				$expsites = explode(";", $sites);
				$siteoptions = "";
				if($_SESSION['office'] == null || $_SESSION['office'] == ""){
					$siteoptions = $siteoptions . "<option value='' disabled selected>Selecione o escritório</option>";
				}
				foreach($expsites as $site){
					if($site != ""){
						if($_SESSION['office'] == $site){
							$siteoptions = $siteoptions . "<option value='" . $site . "' selected>" . $site . "</option>";
						} else {
							$siteoptions = $siteoptions . "<option value='" . $site . "'>" . $site . "</option>";
						}
						
					}
				}
				
				$html = "<div class='row'>".
							"<div class='col s12'>".
								"<div class='card'>".
									"<form method='post' name='mydata' id='mydata' action='updatedata.php'>".
										"<div class='card-content lighten-2'>".
											"<div class='row'>".
												"<div class='col s12 truncate'>".
													"<h3 class='orange-text truncate'>" . $_SESSION['tumbphoto'] . " <span style='position:relative;bottom:20px;'>" . $_SESSION['name'] . "</span></h3>".
												"</div>".
												"<div class='col s12 divider'></div>".
											"</div>".
											"<div class='row'>".
												"<div class='col s12 m6'>".
													"<span class='card-title orange-text'>Meus Dados</span>".
													"<span class='justified'>Edite seus dados da forma que desejar, confirme as alterações clicando em \"Aplicar\" para salvar as informações do seu contato.</span>".
													"<br />".
													"<br />".
												"</div>".
												"<br />".
												"<div class='col s12 m6 verticalDivider orange-text text-darken-3'>".
													"<input type='hidden' id='update_name' name='update_name' value='" . $_SESSION['name'] . "' />".
													"<div class='input-field col s12'>".
														"<i class='material-icons prefix'>chrome_reader_mode</i>".
														"<input type='text' name='description' id='description' value='" . $_SESSION['description'] . "' />".
														"<label class='active'>Descrição</label>".
													"</div>".
													"<div class='input-field col s12'>".
														"<div><i class='lg material-icons'>domain</i>&nbsp;&nbsp;&nbsp; <span class='xs grey-text text-ligthen-2'>Escritório</span></div>".
														"<div align='right'>".
															"<select class='browser-default' id='office' name='office' style='width:90%; border-bottom: 1px solid rgba(80,80,80,0.6); margin-bottom:10px;'>".
																$siteoptions.
															"</select>".
														"</div>".
													"</div>".
													"<div class='input-field col s12'>".
														"<i class='material-icons prefix'>mail</i>".
														"<input type='text' name='mail' id='mail' value='" . $_SESSION['mail'] . "' readonly />".
														"<label class='active'>E-mail</label>".
													"</div>".
													"<div class='input-field col s12'>".
														"<i class='material-icons prefix'>phone</i>".
														"<input type='text' name='phone' id='phone' value='" . $_SESSION['phone'] . "' />".
														"<label class='active'>Telefone/Ramal</label>".
													"</div>".
													"<div class='input-field col s12'>".
														"<i class='material-icons prefix'>phone_iphone</i>".
														"<input type='text' name='mobile' id='mobile' value='" . $_SESSION['mobile'] . "' />".
														"<label class='active'>Celular</label>".
													"</div>".
												"</div>".
											"</div>".
										"</div>".
										"<div class='card-action grey lighten-2' align='right'>".
											"<a href='#!' onclick='updatedata()' class='orange-text text-darken-3 waves-effect waves-orange btn-flat' id='aplicar'><i class='fa fa-check'></i> APLICAR<a/>".
										"</div>".
									"</form>".
								"</div>".
							"</div>".
						"</div>";
				$html = utf8_encode($html);
				echo $html;
				
			} elseif(isset($_POST['contact'])) {
				$user = getcontact($_POST['contact']);
				//echo $user;
				if($user == null){
					$result = "<h3 class='red-text text-darken-3 center'><i class='fa fa-times'></i> Contato não encontrado!</h3>";
					echo utf8_encode($result);
				} else {
					$userdata = explode("|", $user);
					$nome = utf8_decode($userdata[0]);
					$email = utf8_decode($userdata[1]);
					$descricao = utf8_decode($userdata[2]);
					$escritorio = utf8_decode($userdata[3]);
					$telefone = utf8_decode($userdata[4]);
					$celular = utf8_decode($userdata[5]);
					$conta = utf8_decode($userdata[6]);
					if($_SESSION['matricula'] == "s00161"){
						$favorito = "<p>Para adicionar este usuário à sua lista de contatos preferenciais você deve primeiramente acessar o sistema clicando no campo <span class='orange-text text-darken-3'>Acessar <i class='fa fa-sign-in'></i></span>.</p>".
									"<br />";
					} else {
						$favoritoButtom = "<span id='addRmvFav'><a class='btn waves-effect waves-light orange white-text' id='favorito' onclick='listaFavorito(\"" . $conta . "\")' href='#'><i id='favorito_icon' class='fa fa-star-o'></i> Listar Favorito</a></span>";
						if($_SESSION['bookmarks'] != ""){
							$favBook = base64_decode($_SESSION['bookmarks']);
							$favBook = explode(";", $favBook);
							for($i = 1; $i < count($favBook); $i++){
								if($favBook[$i] == $conta){
									$favoritoButtom = "<span id='addRmvFav'><a class='btn waves-effect waves-light red white-text' id='favorito' onclick='removeFavorito(\"" . $conta . "\")' href='#'><i id='favorito_icon' class='fa fa-times'></i> Remover Favorito</a></span>";
								}
							}
						}
						$favorito = $favoritoButtom.
									"<br />".
									"<div class='justified'>".
										"<br />".
										"<p>Para adicionar este usuário à sua lista de contatos preferenciais clique no campo <span class='orange-text text-darken-3'><i class='fa fa-star-o'></i> Listar Favorito</span>.</p>".
									"</div>".
									"<br />";
					}
					
					
					$html = "<div class='row'>".
								"<div class='col s12'>".
									"<div class='card'>".
										"<form method='post' name='mydata' id='mydata' action='updatedata.php'>".
											"<div class='card-content lighten-2'>".
												"<div class='row'>".
													"<div class='col s12 truncate'>".
														"<h3 class='orange-text truncate'><i class='fa fa-address-book'></i> Contato<span class='hide-on-med-and-down'> de:</span></h3>".
													"</div>".
												"</div>".
												"<div class='row grey lighten-2 flagStyle'>".
													"<div class='col s12 m6'>".
														"<span class='card-title orange-text'><i class='fa fa-user'></i> <u>" . $nome . "</u></span>".
														"<br />".
														$favorito.
													"</div>".
													"<br />".
													"<div class='col s12 m6 verticalDivider orange-text text-darken-3'>".
														"<div class='input-field'>".
															"<i class='material-icons prefix'>chrome_reader_mode</i>".
															"<input type='text' name='description' id='description' value='" . $descricao . "' readonly />".
															"<label class='active'>Descrição</label>".
														"</div>".
														"<div class='input-field'>".
															"<i class='material-icons prefix'>domain</i>".
															"<input type='text' name='office' id='office' value='" . $escritorio . "' readonly />".
															"<label class='active'>Escritório</label>".
														"</div>".
														"<div class='input-field'>".
															"<i class='material-icons prefix'>mail</i>".
															"<input type='text' name='mail' id='mail' value='" . $email . "' readonly />".
															"<label class='active'>E-mail</label>".
														"</div>".
														"<div class='input-field'>".
															"<i class='material-icons prefix'>phone</i>".
															"<input type='text' name='phone' id='phone' value='" . $telefone . "' readonly />".
															"<label class='active'>Telefone/Ramal</label>".
														"</div>".
														"<div class='input-field'>".
															"<i class='material-icons prefix'>phone_iphone</i>".
															"<input type='text' name='mobile' id='mobile' value='" . $celular . "' readonly />".
															"<label class='active'>Celular</label>".
														"</div>".
													"</div>".
												"</div>".
											"</div>".
										"</form>".
									"</div>".
								"</div>".
							"</div>";
					$html = utf8_encode($html);
					echo $html;
				}
				
			} elseif(isset($_POST['autocomplete'])) {
				$collected = explode("#", $_SESSION['addresslist']);
				$result = array();
				$value = $_POST['value'];
				$len = strlen($_POST['value']);
				for($i = 1; $i < (count($collected) - 1); $i++){
					$expColl = explode("|", $collected[$i]);
					$expColl[0] = str_replace("\0", "", $expColl[0]);
					for($j = 0; $j < $len; $j++){
						if($expColl[0][$j] == $value[$j]){
							$result[$expColl[0]] = "img/user.png";
						}
					}
					
				}
				unset($collected);
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($result, JSON_PRETTY_PRINT);
				
			} elseif(isset($_POST['bookmarks'])){
				$meusContatos = array();
				$collected = explode("#", $_SESSION['addresslist']);
				echo 	"<div class='col s12'>".
							"<div class='card'>".
								"<div class='card-content'>".
									"<div class='orange-text'>".
										"<h3><i class='fa fa-star'></i> Favoritos</h3>".
									"</div>";
				if($_SESSION['bookmarks'] == ""){
					echo "<div align='center' style='padding-top:80px; padding-bottom:90px;'><h4 class='red-text'><i class='fa fa-user-circle'></i> Lista vazia.</h4><h5 class='orange-text'>Nenhum contato listado.</h5></div>";
				} else {
					$favoritos = $_SESSION['bookmarks'];
					$favoritos = base64_decode($favoritos);
					$favoritos = explode(";", $favoritos);
					for($i = 1; $i < count($favoritos); $i++){
						for($j = 1; $j < (count($collected) - 1); $j++){
							$expColl = explode("|", $collected[$j]);
							if($expColl[6] == $favoritos[$i]){
								array_push($meusContatos, $collected[$j]);
							}
						}
					}
					sort($meusContatos);
					foreach($meusContatos as $contatos){
						//echo $contatos . "<br />";
						$dados = explode("|", $contatos);
						$nome = $dados[0];
						$email = $dados[1];
						$telefone = $dados[4];
						$celular = $dados[5];
						$conta = $dados[6];
						echo 	"<div class='card grey lighten-4'>".
									"<div class='card-content'>".
										"<div class='row'>".
											"<div class='col s12 m6 md orange-text text-darken-3'>".
												"<i class='fa fa-user-circle fa-lg'></i> " . $nome.
											"</div>".
											"<div class='col s12 m6 orange-text text-darken-4'>".
												"<a class='truncate btn-flat right waves-effect waves-orange orange-text text-darken-3' id='favorito' onclick='removeFavoritoList(\"" . $conta . "\")' href='#'><i id='favorito_icon' class='fa fa-times'></i> Remover</a>".
											"</div>".
											"<div class='col s12 divider'></div>".
										"</div>".
										"<div class='row orange-text'>".
											"<div class='col s12 m5 chip orange white-text' title='e-mail'>".
												"<i class='fa fa-envelope fa-lg'></i> <i class='fa fa-skype fa-lg'></i> " . $email.
											"</div>".
											"<div class='col s12 m3 chip orange white-text' title='Telefone/Ramal'>".
												"<i class='fa fa-phone fa-lg'></i> " . $telefone.
											"</div>".
											"<div class='col s12 m3 chip orange white-text' title='Celular'>".
												"<i class='fa fa-mobile fa-lg'></i> " . $celular.
											"</div>".
										"</div>".
									"</div>".
								"</div>";
					}
				}
				echo 				"</div>".
								"</div>".
							"</div>".
						"</div>";
				
			} elseif(isset($_POST['list'])){
				$contactList = explode("#", $_SESSION['addresslist']);
				$index = null;
				$html = "";
				$navMenu = "";
				for($i=1; $i < (count($contactList) - 1); $i++){
					$line = utf8_encode($contactList[$i]);
					$line = utf8_decode($line);
					$expColl = explode("|", $line);
					$nome = strtolower($expColl[0]);
					$nome = ucwords($nome);
					$firstIndex = $nome[0];
					if($firstIndex != $index){
						$index = $firstIndex;
						if($i != 1){
							$html = $html . "</div>";
						}
						$html = $html . "<div class='col s12 m6 indexcontact'>";
						$html = $html . "<div id='index-" . $index . "' class='xl orange white-text indexpoint'>" . $index . ".</div><br />";
						
						$navMenu = $navMenu . "<li class='waves-effect'><a href='#index-" . $index . "' class='navMenu'>" . $index . "</a></li>";
					}
					$html = $html . "<div class='contactList'><a class='md modal-trigger' style='color:inherit;' href='#contactModal' onclick='callUserDataModal(\"" . $expColl[0] . "\")'><i class='fa fa-address-card'></i> " . $nome . "</a></div>";
				}
				$navMenu = $navMenu . "<li class='waves-effect'><a href='#index-top' class='navMenu'>Topo</a></li>";
				$navMenu = "<div id='navigator' class='grey darken-3'><ul class='pagination'>" . $navMenu . "</ul></div>";
				echo $navMenu;
				echo $html;
				
			} elseif(isset($_POST['refresh'])){
				$result = refreshCatalog();
				echo $result;
				
			} else {
				echo "<span class='lg red-text center'>No Data informed!</span>";
			}
		}
		
	} elseif(isset($_POST['action']) && $_POST['action'] == "insert"){
		$nome = $_POST['name'];
		$descricao = $_POST['description'];
		$escritorio = $_POST['office'];
		$mail = $_POST['mail'];
		$telefone = $_POST['phone'];
		$celular = $_POST['mobile'];
		$data = "#" . $nome . "|" . $mail . "|" . $descricao . "|" . $escritorio . "|" . $telefone . "|" . $celular;
		$result = updateData($_SESSION['matricula'], $data);
		echo $result;
		
	} elseif(isset($_POST['addfavorito']) && isset($_POST['account'])){
		$escreve = writeBookmark($_POST['account']);
		if($escreve){
			echo "success";
		} else {
			echo "fail";
		}
		
	} elseif(($_POST['rmvfavorito']) && isset($_POST['account'])){
		$apaga = eraseBookmark($_POST['account']);
		if($apaga){
			echo "success";
		} else {
			echo "fail";
		}
		
	} else {		
		echo "<span class='lg red-text center'>No Action informed!</span>";
	}
	
	
	
?>