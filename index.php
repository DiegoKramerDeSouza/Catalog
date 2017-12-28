<?php 
	if(session_id() == '') {
		session_start();
	}
	require_once("structure.php");
	
	if(!(isset($_SESSION['active']) && ($_SESSION['active'] == 1 || $_SESSION['active'] == 2))){
		header("Location:login.php");
	}
	$currentUser = $_SESSION['name'];
	$login = base64_encode($_SESSION['matricula']);
	$senha = base64_encode($_SESSION['senha']);
	$photo = $_SESSION['photo'];
	
	//Escreve cabeçalho
	echo $headerHtml;
	//onkeypress='runScript(event)'
?>
<body class="grey lighten-4">
	<main>
		<header id='index-top'>
			<nav class="nav-extended grey darken-4">
				<div class="nav-wrapper">
					<?php 
						if($_SESSION['matricula'] != "s00161"){
							echo "<a href='#!' class='button-collapse orange-text right' onclick='logoff()' title='Sair'><i class='fa fa-sign-out fa-2x'></i></a>";
						} else {
							echo "<a href='#loginModal' class='navtitle md orange-text modal-trigger right'><b>Acessar <i class='fa fa-sign-in'>&nbsp;&nbsp;&nbsp;</i></b></a>";
						}
					?>
					<a href="index.php" class="brand-logo"><span class="orange-text text-darken-1 truncate navtitle"><b>Catalog</b><i class="material-icons orange-text text-darken-1 hide-on-med-and-down">layers</i></span></a>
					<ul id="nav-mobile" class="right hide-on-med-and-down">
						<li>
							<?php 
								if($_SESSION['matricula'] != "s00161"){
									echo "<a class='dropdown-button' id='userDropDown' href='#!' data-activates='myProfile'>";
									echo "	<div id='userChip' class='chip orange white-text'>";
									echo 		$photo . utf8_encode($currentUser);
									echo "	</div>";
									echo "</a>";
								}
							?>
						</li>
					</ul>
					
				</div>
				<ul id="myProfile" class="dropdown-content" style="margin-top:60px;">
					<?php
						if($_SESSION['matricula'] != "s00161"){
							echo "<li class='grey darken-3'><a href='#' class='orange-text' onclick='callMyData(\"" . $_SESSION['matricula'] . "\")'><i class='fa fa-user fa-lg'></i> Meus Dados</a></li>";
							echo "<li class='divider'></li>";
							echo "<li class='grey darken-3'><a href='#' class='orange-text' onclick='callMyFav()'><i class='fa fa-star-o fa-lg'></i> Favoritos</a></li>";
							echo "<li class='divider'></li>";
						}
						echo "<li class='grey darken-3'><a href='#' class='orange-text' onclick='callList()'><i class='fa fa-list-ul fa-lg'></i> Lista Geral</a></li>";
						echo "<li class='divider'></li>";
						echo "<li class='grey darken-3'><a href='#!' onclick='logoff()' class='orange-text'><i class='fa fa-sign-out fa-lg'></i> Sair</a></li>";
					?>
				</ul>
			</nav>
		</header>
		
		<div id="loading">
			<div id="dialogLoading" align="center">
				<div>
					Aguarde...</span><br/>
				</div>
				<div id="loadGif">
					<img src="./img/load.png" class="image_spinner_md" ></span>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col s12 m4 l3">
				&nbsp;
				<div class="collection cardStyle grey darken-4" style="border: 0px;">
					<div class="selectImage" style="margin-top:5px;">
						<span class="selectImageContent">
							<img src="img/logo2.png" alt='Call Tecnologia' class='logoimg' />
						</span>
					</div>
					<br />
					<?php
						echo "<a href='index.php' id='home' class='cardStyle md grey darken-2 orange-text collection-item waves-effect waves-orange mouseHover-li'><span class='fa fa-home'></span> Início</a>";
						if($_SESSION['matricula'] != "s00161"){
							echo "<a href='#' id='myid' class='cardStyle md grey darken-3 orange-text collection-item waves-effect waves-orange mouseHover-li' onclick='callMyData(\"" . $_SESSION['matricula'] ."\")'><span class='fa fa-user-circle'></span> Meus Dados</a>";
							echo "<a href='#' id='star' class='cardStyle md grey darken-3 orange-text collection-item waves-effect waves-orange mouseHover-li' onclick='callMyFav()'><span class='fa fa-star'></span> Favoritos</a>";
						}
						echo "<a href='#' id='list' class='cardStyle md grey darken-3 orange-text collection-item waves-effect waves-orange mouseHover-li' onclick='callList()'><span class='fa fa-stack-overflow'></span> Lista Geral</a>";
						echo "<a href='#' id='refresh' class='truncate cardStyle md grey darken-3 orange-text collection-item waves-effect waves-orange mouseHover-li' onclick='reloadCatalog()'><span class='fa fa-refresh'></span> Atualiza Catálogo</a>";
						//echo $_SESSION['myInfo'];
					?>
				</div>
			</div>
			
			<div class="col s12 m8 l9" id='information'>
				<!--JSON Data here!-->
				<div class='row'>
					<div class='input-field col s10'>
						<input id="search_bar" type="text" class='autocomplete' autocomplete="off" placeholder='Pesquisar Contatos' autofocus />
					</div>
					<div class='input-field col s2'>
						<a class='waves-effect waves-light btn-floating orange' onclick='callUserData()'><i class="white-text material-icons text-darken-1">search</i></a>
					</div>
				</div>
				<?php
					if($_SESSION['matricula'] == "s00161"){
						echo "<div align='center' id='loginMessage'><i><b>Para utilizar todos os recursos do sistema você deve <span class='orange-text'>Acessar</span> informando seu usuário e senha.</b></i></div>";
					}
				?>
				<!--<div id='returnKey'></div>-->
				<!--JSON Data here!-->
				<div id='presentation'>
					<div class="section">
						<div class="row">
							<div class="col s12 m4">
								<div class="icon-block">
									<h2 class="center orange-text"><i class="large material-icons">perm_contact_calendar</i></h2>
									<h5 class="center">Gerencie seus dados de contato</h5>
									<p class="light justified">Mantenha seus dados a sua maneira. Disponibilize suas informações mais atuais para consulta a qualquer hora.</p>
								</div>
							</div>
							<div class="col s12 m4">
								<div class="icon-block">
									<h2 class="center orange-text"><i class="large material-icons">grade</i></h2>
									<h5 class="center">Crie uma lista de principais contatos</h5>
									<p class="light justified">Forme sua lista de contatos prioritários adicionando qualquer pessoa na sua lista de favoritos pessoal.</p>
								</div>
							</div>
							<div class="col s12 m4">
								<div class="icon-block">
									<h2 class="center orange-text"><i class="large material-icons">list</i></h2>
									<h5 class="center">Veja todos os contatos listados</h5>
									<p class="light justified">Localize qualquer contato acessando a listagem geral com todas as contas do domínio e suas respectivas configurações.</p>
								</div>
							</div>
						</div>
					</div>
					<br />
					<br />
				</div>
			</div>
		</div>
		<div id='contactModal' class='modal modal-fixed-footer' style='width:90%; height:90%;'>
			<div id='contact-content' class='modal-content'>
			</div>
			<div class='modal-footer'>
				<a href='#!' class='modal-action modal-close waves-effect waves-orange btn-flat orange-text text-darken-3'><i class='fa fa-times'></i> Fechar</a>
			</div>
		</div>
		
		<div id='loginModal' class='modal modal-fixed-footer grey darken-4' style='max-height:400px;'>
			<div class='modal-content'>
				<div class="row">
					<div class="col s12 truncate title">
						<b><span class="orange-text">Catalog</span></b> <span style='position:absolute; margin-top:10px;'><i class="medium material-icons orange-text">layers</i></span>
					</div>
					<hr />
				</div>
				<div class="row">
					<div class="col s12 m8 offset-m2">
						<div class="input-field" style="max-height:45px;">
							<input type="text" autocomplete="off" class="validad orange-text" name="user" id="user" required autofocus />
							<label class="active" for="user"><span class="fa fa-user fa-lg"></span> Usuário</label>
						</div>
						<div class="input-field" style="max-height:45px;">
							<input type="password" autocomplete="off" class="validade orange-text" name="password" id="password" required />
							<label class="active" for="password"><span class="fa fa-lock fa-lg"></span> senha</label>
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer grey darken-3'>
				<a href='#!' class='modal-action modal-close waves-effect waves-orange btn-flat orange-text text-darken-3'><i class='fa fa-times'></i> Cancelar</a>
				<a href='#!' onclick='login()' class='modal-action modal-close waves-effect waves-orange btn-flat orange-text text-darken-3'><i class='fa fa-sign-in'></i> Acessar</a>
			</div>
		</div>
		
		<div id='linhaModal' class='modal modal-fixed-footer'>
			<div class='modal-content'>
				<h4><i class='fa fa-comments-o'></i> Linhas Diretas</h4>
				<div class='container orange-text text-darken-3'>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Recepção 1º Andar(SDH)
						</div>
						<div class='col s6 black-text'>
							71 3340-2150
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Recepção 3º Andar(Fivetech, Multicanal, HSR)
						</div>
						<div class='col s6 black-text'>
							71 3340-2151
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Administração
						</div>
						<div class='col s6 black-text'>
							71 3340-2163
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Educação Continuada
						</div>
						<div class='col s6 black-text'>
							71 3340-2162
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Sérgio Ramos
						</div>
						<div class='col s6 black-text'>
							71 3340-2165
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							SAEM
						</div>
						<div class='col s6 black-text'>
							61 3035-5087 / 71 3340-2164
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Coordenação SDH
						</div>
						<div class='col s6 black-text'>
							61 3035-5198 / 71 3340-2161
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<a href='#!' class='modal-action modal-close waves-effect waves-orange btn-flat orange-text text-darken-3'><i class='fa fa-times'></i> Fechar</a>
			</div>
		</div>
		<div id='gestaoModal' class='modal modal-fixed-footer'>
			<div class='modal-content'>
				<h4><i class='fa fa-mobile'></i> Celulares Gestão</h4>
				<div class='container orange-text text-darken-3'>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Rudá Falsirolli
						</div>
						<div class='col s6 black-text'>
							(71) 99703-2712
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Caio Regis
						</div>
						<div class='col s6 black-text'>
							(71) 98775-7240
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Sérgio Ramos
						</div>
						<div class='col s6 black-text'>
							(61) 99854-7704
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<a href='#!' class='modal-action modal-close waves-effect waves-orange btn-flat orange-text text-darken-3'><i class='fa fa-times'></i> Fechar</a>
			</div>
		</div>
		<div id='ramaisModal' class='modal modal-fixed-footer'>
			<div class='modal-content'>
				<h4><i class='fa fa-phone'></i> Ramais</h4>
				<div class='container orange-text text-darken-3'>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Alisson (DAP)
						</div>
						<div class='col s6 black-text'>
							5731
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Davi (SESMT)
						</div>
						<div class='col s6 black-text'>
							5722
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Diego (ADM)
						</div>
						<div class='col s6 black-text'>
							633928
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Luan (DAP)
						</div>
						<div class='col s6 black-text'>
							5716
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Rodrigo (TI)
						</div>
						<div class='col s6 black-text'>
							5710
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Yuri (TI)
						</div>
						<div class='col s6 black-text'>
							5730
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Wiliane (DAP)
						</div>
						<div class='col s6 black-text'>
							5715
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Sergio Ramos (Gestão)
						</div>
						<div class='col s6 black-text'>
							5712
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Rudá Falsirolli (ADM)
						</div>
						<div class='col s6 black-text'>
							649888
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							DAP (Atendimento)
						</div>
						<div class='col s6 black-text'>
							5703
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Planejamento
						</div>
						<div class='col s6 black-text'>
							5709
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Apoio Psicológico
						</div>
						<div class='col s6 black-text'>
							5704
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Coordenação SDH
						</div>
						<div class='col s6 black-text'>
							5198
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							SAEM
						</div>
						<div class='col s6 black-text'>
							5087
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Educação Continuada
						</div>
						<div class='col s6 black-text'>
							5706
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Monitoria
						</div>
						<div class='col s6 black-text'>
							5707
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Sala de Reunião (SDH)
						</div>
						<div class='col s6 black-text'>
							5705
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Supervisão (SDH)
						</div>
						<div class='col s6 black-text'>
							5708
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Recepção 1º Andar (SDH)
						</div>
						<div class='col s6 black-text'>
							5700
						</div>
					</div>
					<div class='row grey lighten-2'>
						<div class='col s6'>
							Recepção 3º Andar (Fivetech, HSR, Multicanal)
						</div>
						<div class='col s6 black-text'>
							5701
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<a href='#!' class='modal-action modal-close waves-effect waves-orange btn-flat orange-text text-darken-3'><i class='fa fa-times'></i> Fechar</a>
			</div>
		</div>
	</main>
	
	<footer class="page-footer grey darken-4">
		<div class="">
			<div class="row">
				<div class="col l6 s12">
					<h5 class="white-text">Encontre o que você precisa.</h5>
					<p class="grey-text text-lighten-4 ">Os principais contatos, ramais e telefones úteis podem ser encontrados em um só lugar de maneira simplificada.</p>
				</div>
				<div class="col l6 s12">
					<h5 class="orange-text">Números Úteis</h5>
					<ul>
						<li><a class="md white-text modal-trigger" href="#linhaModal"><span class='uteis'><i class='fa fa-comments-o'></i> Linas Diretas</span></a></li>
						<li><a class="md white-text modal-trigger" href="#gestaoModal"><span class='uteis'><i class='fa fa-mobile fa-lg'></i> Celulares Gestão</span></a></li>
						<li><a class="md white-text modal-trigger" href="#ramaisModal"><span class='uteis'><i class='fa fa-phone'></i> Ramais</span></a></li>
					</ul>
				</div>
				
			</div>
		</div>
		<div class="footer-copyright">
			<div style='margin-left:10px;'>
				2017 <span class="orange-text"><b>Equipe de Colaboração de <span class='blue-text'>Serviços</span></b></span>
			</div>
		</div>
	</footer>
		
	
	<?php
		echo $scriptsHtml;
	?>
</body>

</html>