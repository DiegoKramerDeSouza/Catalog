<?php 
	require_once("structure.php");
	//Escreve cabeçalho
	echo $headerHtml;
	
	$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if($actual_link == "catalogo.call.br/catalog/login.php"){
		header("Location:access.php?user=s00161");
	}
?>
<body>
	<main>
		<div class="row" style="display:none;">
			<div class="col s12 blue-text text-lighten-1 truncate" id="title">
				Documentos <span class="fa fa-files-o white-text text-darken-4"> </span>
			</div>
		</div>
		
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
		
		<div id="imgBG">
			<img src="img/layer.jpg" id="bgLayer" />
			<img src="img/bg6.jpg" id="bg" />
		</div>
		<div class="row">
			<div class="centro col s12 m8 l6 offset-m2 offset-l3" id="loginPanel">
				<div class="card">
					<form method="post" name="login" id="login" action="access.php">
						<div class="card-content lighten-2">
							<div class="row">
								<div class="col s12 truncate title">
									<b><span class="grey-text text-darken-3">Catalog</span></b> <span style='position:absolute; margin-top:10px;'><i class="medium material-icons orange-text">layers</i></span>
								</div>
								<hr />
							</div>
							<div class="row">
								<div class="col s6" >
									<span class="card-title orange-text" style="font-size:36px;">Login</span>
								</div>
								<div class="col s6" style="border-left: 1px solid rgba(80,80,80,0.2);">
									<div class="input-field" style="max-height:45px;">
										<input type="text" autocomplete="off" class="validad" name="user" id="user" required autofocus />
										<label class="active" for="user"><span class="fa fa-user fa-lg"></span> Usuário</label>
									</div>
									<div class="input-field" style="max-height:45px;">
										<input type="password" autocomplete="off" class="validade" name="password" id="password" required />
										<label class="active" for="password"><span class="fa fa-lock fa-lg"></span> senha</label>
									</div>
								</div>
							</div>
						</div>
						<div class="card-action grey lighten-4" align="right" style="max-height:60px;">
							<input type="submit" class="orange-text text-darken-3 waves-effect waves-orange btn-flat" style="opacity:1.0; position:relative; right:-20px;" id="Acessar" value="Acessar" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>
	<?php
		echo $scriptsHtml;
	?>

</body>
</html>