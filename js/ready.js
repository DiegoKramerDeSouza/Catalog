/*
	Arquivo de inicialização de funções personalizadas
	Document ready functions
*/

$("#bg").fadeIn(3000);
$("#bgLayer").fadeIn(3500);
$(".title").fadeIn(1000);
$("#loginPanel").show(0);

$( document ).ready(function(){
	Materialize.updateTextFields();
	$('select').material_select();
	$(".button-collapse").sideNav();
	$('.modal').modal({
		dismissible: true,
		opacity: .8,
		inDuration: 500,
		outDuration: 500,
		startingTop: '4%',
		endingTop: '10%',
    });
	
	$('input#input_text, textarea#textarea1').characterCounter();
	
	$(".dropdown-button").dropdown();
	$('.chips').material_chip();
	$('.userBlock').slideDown(1000);
	
	//Aplica para os botões a exibição da tela de load----------
	$("#Acessar").click(function(){
		if (document.forms["login"]["user"].value != "" && document.forms["login"]["password"].value != ""){
			$("#loading").fadeIn();
		}
	});
	
	callContacts();
});

function login(){
	$("#loading").fadeIn(300);
	setTimeout(function(){
		$ac = document.getElementById('user').value;
		$ps = document.getElementById('password').value;
		if(($ac != null && $ac != "") && ($ps != null && $ps != "")){
			$.post( "access.php", { user: $ac, password: $ps });
		} else {
			$toastContent = $('<span class="white-text"><i class="fa fa-user-times fa-lg"></i> usuário ou senha incorretos!</span>');
			Materialize.toast($toastContent, 1000, 'red');
		}
		window.location.replace("index.php");
	}, 1000);
}

function callMyData(target){
	togglemenuselection('myid');
	$.post( "getdata.php", { user: target, action: "collect" },
		function(data,status){
			obj = data;
			if(status == "success"){
				document.getElementById("information").innerHTML = data;
			} else {
				console.log("ERROR");
			}
		}
	);
}

function callMyFav(){
	togglemenuselection('star');
	$.post( "getdata.php", { bookmarks: "1", action: "collect" },
		function(data,status){
			obj = data;
			if(status == "success"){
				document.getElementById("information").innerHTML = data;
			} else {
				console.log("ERROR");
			}
		}
	);
}

function callUserData(){
	$("#loading").show();
	var search = document.getElementById('search_bar').value;
	search = btoa(search);
	if(search != null && search != ""){
		setTimeout(function(){
			$.ajaxSetup({async: false});
			$.post( "getdata.php", { contact: search, action: "collect" },
				function(data,status){
					obj = data;
					if(status == "success"){
						document.getElementById("presentation").innerHTML = data;
					} else {
						console.log("ERROR");
					}
				}
			);
			document.getElementById('search_bar').value = "";
			$("#loading").hide();
		}, 1000);
	} else {
		$("#loading").hide();
		$toastContent = $('<span class="white-text"><i class="fa fa-times fa-lg"></i> Informe um contato!</span>');
		Materialize.toast($toastContent, 1000, 'red');
	}
}

function callUserDataModal(user){
	if(user != null && user != ""){
		user = btoa(user);
		//console.log(search);
		$.post( "getdata.php", { contact: user, action: "collect" },
			function(data,status){
				obj = data;
				if(status == "success"){
					document.getElementById("contact-content").innerHTML = data;
				} else {
					console.log("ERROR");
				}
			}
		);
	} else {
		$toastContent = $('<span class="white-text"><i class="fa fa-times"></i> Informe um contato!</span>');
		Materialize.toast($toastContent, 1000, 'red');
	}
}

function callList(){
	$("#loading").fadeIn(300);
	togglemenuselection('list');
	$.ajaxSetup({async: true});
	$.post( "getdata.php", { list: "1", action: "collect" },
		function(data,status){
			obj = data;
			if(status == "success"){
				$("#loading").fadeOut(300);
				document.getElementById("presentation").innerHTML = data;
			} else {
				$("#loading").fadeOut(300);
				console.log("ERROR");
			}
		}
	);
}

function callContacts(){
	$.ajaxSetup({async: false});
	$('#search_bar').autocomplete();
	$('#search_bar').on("keyup", function(event){
		if(event.keyCode >= 48 && event.keyCode <= 90 || (event.keyCode == 229)){
			var val = document.getElementById('search_bar').value;
			$.ajax({
				url: 'getdata.php',
				type: 'post',
				dataType: 'json',
				data: {autocomplete: "1", action: "collect", value: val},
				success: function(data){
					$('#search_bar').autocomplete({
						data: data, 
						limit: 10,
						minLength: 3
					});
				}
			});
			//document.getElementById('returnKey').innerHTML = event.keyCode;
		} else if(event.keyCode == 13) {
			callUserData();
		}
	});
}

function updatedata(){
	$nome = document.getElementById('update_name').value;
	$descricao = document.getElementById('description').value;
	$escritorio = document.getElementById('office').value;
	$email = document.getElementById('mail').value;
	$telefone = document.getElementById('phone').value;
	$celular = document.getElementById('mobile').value;
	$.post( "getdata.php", { update: "1", action: "insert", name: $nome, description: $descricao, office: $escritorio, mail: $email, phone: $telefone, mobile: $celular },
		function(data,status){
			obj = data;
			console.log(obj);
			if(status == "success" && obj == "success"){
				$toastContent = $('<span class="white-text"><i class="fa fa-user-plus fa-lg"></i> Dados atualizado com sucesso!</span>');
				Materialize.toast($toastContent, 1000, 'green');
			} else {
				$toastContent = $('<span class="white-text"><i class="fa fa-user-times fa-lg"></i> Falha ao atualizar dados!</span>');
				Materialize.toast($toastContent, 1000, 'red');
				console.log(obj)
			}
		}
	);
}

function listaFavorito(account){
	//console.log(account);
	$.post( "getdata.php", { addfavorito: "1", account: account },
		function(data,status){
			obj = data;
			if(status == "success" && obj == "success"){
				$toastContent = $('<span class="white-text"><i class="fa fa-user-plus fa-lg"></i> Contato adicionado à sua lista de favoritos!</span>');
				Materialize.toast($toastContent, 1000, 'green');
			} else {
				$toastContent = $('<span class="white-text"><i class="fa fa-user-times fa-lg"></i> Falha ao adicionar contato aos favoritos!</span>');
				Materialize.toast($toastContent, 1000, 'red');
				console.log(obj)
			}
		}
	);
	document.getElementById('addRmvFav').innerHTML = "<a class='btn waves-effect waves-light red white-text' id='favorito' onclick='removeFavorito(\"" + account + "\")' href='#'><i id='favorito_icon' class='fa fa-times'></i> Remover Favorito</a>";
}

function removeFavorito(account){
	//console.log(account);
	//$('#favorito').fadeOut(500);
	$.post( "getdata.php", { rmvfavorito: "1", account: account },
		function(data,status){
			obj = data;
			if(status == "success"){
				$toastContent = $('<span class="white-text"><i class="fa fa-user-times fa-lg"></i> Contato removido da lista de favoritos!</span>');
				Materialize.toast($toastContent, 1000, 'green');
			} else {
				$toastContent = $('<span class="white-text"><i class="fa fa-times fa-lg"></i> Falha ao remover contato aos favoritos!</span>');
				Materialize.toast($toastContent, 1000, 'red');
				console.log(obj)
			}
		}
	);
	document.getElementById('addRmvFav').innerHTML = "<a class='btn waves-effect waves-light orange white-text' id='favorito' onclick='listaFavorito(\"" + account +"\")' href='#'><i id='favorito_icon' class='fa fa-star-o'></i> Listar Favorito</a>";
}

function removeFavoritoList(account){
	//console.log(account);
	//$('#favorito').fadeOut(500);
	$.post( "getdata.php", { rmvfavorito: "1", account: account },
		function(data,status){
			obj = data;
			if(status == "success"){
				$toastContent = $('<span class="white-text"><i class="fa fa-user-times fa-lg"></i> Contato removido da lista de favoritos!</span>');
				Materialize.toast($toastContent, 1000, 'green');
			} else {
				$toastContent = $('<span class="white-text"><i class="fa fa-times fa-lg"></i> Falha ao remover contato aos favoritos!</span>');
				Materialize.toast($toastContent, 1000, 'red');
				console.log(obj)
			}
		}
	);
	callMyFav();
}

function reloadCatalog(){
	$.post( "getdata.php", { refresh: "1", action: "collect" },
		function(data,status){
			obj = data;
			if(status == "success" && obj == "success"){
				$toastContent = $('<span class="white-text"><i class="fa fa-check fa-lg"></i> Catálogo de contatos atualizado com sucesso!</span>');
				Materialize.toast($toastContent, 1000, 'green');
			} else {
				$toastContent = $('<span class="white-text"><i class="fa fa-times fa-lg"></i> Falha ao atualizar catálogo!</span>');
				Materialize.toast($toastContent, 1000, 'red');
				console.log(obj)
			}
		}
	);
}

function toggleOptions(itemId, cls){
	var elem = document.getElementById(itemId);
	if(hasClass(elem, cls)){
		elem.classList.remove(cls);
		$("#"+itemId).slideDown(500);
	} else {
		elem.classList.add(cls);
		$("#"+itemId).slideUp(500);
	}
}

function togglemenuselection(targetmenu){
	var elem;
	var $elems = document.getElementsByClassName('mouseHover-li');
	for(var i=0; i < $elems.length; i++){
		elem = document.getElementById($elems[i].id);
		elem.classList.remove('darken-2');
		elem.classList.add('darken-3');
	}
	elem = document.getElementById(targetmenu);
	elem.classList.remove('darken-3');
	elem.classList.add('darken-2');
}

function logoff(){
	$("#loading").fadeIn(300);
	window.location.replace("logout.php");
}

function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

function runScript(event) {
    if (event.keyCode == 13) {
        //callUserData();
    }
}