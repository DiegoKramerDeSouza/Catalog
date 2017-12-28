<?php
	if(session_id() == '') {
		session_start();
	}
	require_once "functions.php";
	
	if (isset($_POST['user']) && isset($_POST['password'])){
		$_SESSION['matricula'] = strtolower($_POST['user']);
		$_SESSION['senha'] = base64_encode($_POST['password']);
		$login = $_SESSION['matricula'];
		//Dados de Conexão LDAP================================================================================
		//Usuário de conexão
		$ldapU = "call\\" . $login;
		//Senha de conexão
		$ldapPw = base64_decode($_SESSION['senha']);
		//Caminho - OU
		$base = "DC=call,DC=br";
		//Host de conexão
		$ldapH = "LDAP://SVDF07W000010.call.br";
		//Porta de conexão
		$ldapP = "389";
		//=====================================================================================================
			
		//Estabelece conexão com LDAP
			$ldapConnection = ldap_connect($ldapH, $ldapP) or die (header("Location:login.php?erro=1"));
			ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
			
			if($ldapConnection){
				//Executa Binding de conta LDAP
				$ldapBind = ldap_bind($ldapConnection, $ldapU, $ldapPw) or die (header("Location:login.php?erro=2"));
			} else {
				$_SESSION['active'] = 0;
				header("location:login.php?erro=0");
			}
			if($ldapBind){
				$filter = '(&(objectClass=User)(sAMAccountname=' . $login . '))';
				//Search
				$search = ldap_search($ldapConnection, "DC=call,DC=br", $filter);
				//Recolhe entradas
				$info = ldap_get_entries($ldapConnection, $search);
				
				if (isset($info[0]["displayname"][0])){
					$name = $info[0]["displayname"][0];
				}
				if (isset($info[0]["description"][0])){
					$description = $info[0]["description"][0];
				} else {
					$description = null;
				}
				if (isset($info[0]["physicaldeliveryofficename"][0])){
					$office = $info[0]["physicaldeliveryofficename"][0];
				} else {
					$office = null;
				}
				if (isset($info[0]["mail"][0])){
					$mail = $info[0]["mail"][0];
				} else {
					$mail = null;
				}
				if (isset($info[0]["telephonenumber"][0])){
					$phone = $info[0]["telephonenumber"][0];
				} else {
					$phone = null;
				}
				if (isset($info[0]["mobile"][0])){
					$mobile = $info[0]["mobile"][0];
				} else {
					$mobile = null;
				}
				//Get photo--------------------
				if (isset($info[0]["thumbnailphoto"])){
					$photo = $info[0]["thumbnailphoto"][0];
					$tumbphoto = "<img src='data:image/jpeg;base64," . base64_encode($photo) . "' style='width:80px; height:80px; border-radius:40px;' />";
					$photo = "<img src='data:image/jpeg;base64," . base64_encode($photo) . "' />";
				} else {
					$photo = "<img src='img/user_icon.png'>";
					$tumbphoto = "<img src='img/user_icon.png' style='width:80px; height:80px; border-radius:40px;' />";
				}
				
				$_SESSION['name'] = utf8_decode($name);
				$_SESSION['photo'] = $photo;
				$_SESSION['tumbphoto'] = $tumbphoto;
				$_SESSION['description'] = utf8_decode($description);
				$_SESSION['office'] = utf8_decode($office);
				$_SESSION['mail'] = $mail;
				$_SESSION['phone'] = $phone;
				$_SESSION['mobile'] = $mobile;
				$_SESSION['active'] = 1;
				$addresslist = file_get_contents('./list/list.mddb');
				$_SESSION['addresslist'] = utf8_encode($addresslist);
				$_SESSION['addresslist'] = str_replace("\0", "", $_SESSION['addresslist']);
				$addresslist = null;
				$_SESSION['sites'] = listSites();
				$_SESSION['myInfo'] = "#" . $name . "|" . $mail . "|" . $description . "|" . $office . "|" . $phone . "|" . $mobile;
				$file = "./list/Bookmarks/" . $_SESSION['matricula'] . ".mddb";
				$fileExists = file_exists($file);
				if($fileExists){
					$_SESSION['bookmarks'] = file_get_contents($file);
				} else {
					$_SESSION['bookmarks'] = "";
				}
				
				
				ldap_close($ldapConnection);				
				header("Location:index.php");

			} else {
				$_SESSION['active'] = 0;
				header("Location:logout.php");
				exit();
			}
		//===========================================================================================================
		
		
	} elseif(isset($_GET['user'])){
		singleSignOn($_GET['user']);
	} else {
		$_SESSION['active'] = 0;
		header("location:login.php?erro=0");
	}
	
?>