<?php
	if(session_id() == '') {
		session_start();
	}
	global $ldapConnection;
	
	function getcontact($name){
		$found = false;
		$result = null;
		$searchname = base64_decode($name);
		$searchname = utf8_encode($searchname);
		$collection = explode("#", $_SESSION['addresslist']);
		for($i = 1; $i < (count($collection) - 1); $i++){
			if(!$found){
				$expColl = explode("|", $collection[$i]);
				//echo $i . "# " . $expColl[0] . " - " . $searchname . "<br />";
				if($expColl[0] == $searchname){
					$found = true;
					$result = $collection[$i];
					break;
				}
			}
		}
		unset($collection);
		return $result;
	}
	function refreshCatalog(){
		try{
			$addresslist = file_get_contents('./list/list.mddb');
			$_SESSION['addresslist'] = utf8_encode($addresslist);
			$_SESSION['addresslist'] = str_replace("\0", "", $_SESSION['addresslist']);
			$addresslist = null;
			return "success";
		} catch(Exception $e){
			return "fail";
		}
	}
	function listSites(){
		$cmd = "cmd.exe /c powershell.exe -ExecutionPolicy ByPass -file \"C:\\xampp\\htdocs\\docshare\\scripts\\Powershell\\Get-Sites.ps1\"";
		$sites = exec($cmd);
		return $sites;
	}
	function connect(){
		global $ldapConnection;
		require_once 'conf.php';
		$initial = STRINIT;
		$ps = base64_decode($initial);
		$base = explode("|", $ps);
		$ac = base64_decode($base[0]);
		$ps = base64_decode($base[1]);
		$login = base64_decode($ac);
		$ldapU = "call\\" . $login;
		$ldapPw = base64_decode($ps);
		//Host de conexão
		$ldapH = "LDAP://SVDF07W000010.call.br";
		//Porta de conexão
		$ldapP = "389";
		//=====================================================================================================
			
		//Estabelece conexão com LDAP
		$ldapConnection = ldap_connect($ldapH, $ldapP);
		ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
		
		if($ldapConnection){
			$ldapBind = ldap_bind($ldapConnection, $ldapU, $ldapPw);
		}
		return $ldapBind;
	}
	function updateData($user, $data){
		Global $ldapConnection;
		$expData = explode("|", $data);
		$nome = $expData[0];
		$email = $expData[1];
		$descricao = $expData[2];
		$escritorio = $expData[3];
		$telefone = $expData[4];
		$celular = $expData[5];

		$base = "DC=call,DC=br";
		$bind = connect();
		if($bind){
			$filter = '(&(objectClass=User)(sAMAccountname=' . $user . '))';

			$search = ldap_search($ldapConnection, $base, $filter);
			$sort = ldap_sort($ldapConnection, $search, 'name');
			$info = ldap_get_entries($ldapConnection, $search);
			
			$dn = $info[0]["distinguishedname"][0];
			if($descricao != null && $descricao != ""){
				$dados["description"] = $descricao;
			}
			if($escritorio != null && $escritorio != ""){
				$dados["physicaldeliveryofficename"] = $escritorio;
			}
			if($telefone != null && $telefone != ""){
				$dados["telephonenumber"] = $telefone;
			} else {
				if($_SESSION['phone'] != null && $_SESSION['phone'] != ""){
					$dadosDel["telephonenumber"] = $_SESSION['phone'];
				}
			}
			if($celular != null && $celular != ""){
				$dados["mobile"] = $celular;
				//$dados["othermobile"] = $celular;
			} else {
				if($_SESSION['mobile'] != null && $_SESSION['mobile'] != ""){
					$dadosDel["mobile"] = $_SESSION['mobile'];
				}
			}
			if(isset($dados)){
				$ldapC = ldap_mod_replace($ldapConnection, $dn, $dados);
			}
			if(isset($dadosDel)){
				$ldapD = ldap_mod_del($ldapConnection, $dn, $dadosDel);
			}
		}
		ldap_close($ldapConnection);
		if(isset($ldapC) && $ldapC){
			$_SESSION['description'] = $descricao;
			$_SESSION['office'] = $escritorio;
			$_SESSION['phone'] = $telefone;
			$_SESSION['mobile'] = $celular;
			$oldInfo = $_SESSION['myInfo'];
			$_SESSION['myInfo'] = "#" . $nome . "|" . $email . "|" . $descricao . "|" . $escritorio . "|" . $telefone . "|" . $celular;
			$_SESSION['addresslist'] = str_replace($oldInfo, $_SESSION['myInfo'], $_SESSION['addresslist']);
			
			return "success";
		} else {
			return "fail";
		}
	}
	
	function singleSignOn($user){
		Global $ldapConnection;
		$base = "DC=call,DC=br";
		$bind = connect();
		if($bind){
			$filter = '(&(objectClass=User)(sAMAccountname=' . $user . '))';

			$search = ldap_search($ldapConnection, $base, $filter);
			$sort = ldap_sort($ldapConnection, $search, 'name');
			$info = ldap_get_entries($ldapConnection, $search);
			for ($i = 0; $i < $info["count"]; $i++){
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
					$photo = "<img class='img-circle' src='data:image/jpeg;base64," . base64_encode($photo) . "' />";
				} else {
					$photo = "<img class='img-circle' src='img/user_icon.png'>";
				}
				
				$_SESSION['matricula'] = $user;
				$_SESSION['senha'] = "";
				$_SESSION['name'] = utf8_decode($name);
				$_SESSION['photo'] = $photo;
				$_SESSION['description'] = utf8_decode($description);
				$_SESSION['office'] = utf8_decode($office);
				$_SESSION['mail'] = $mail;
				$_SESSION['phone'] = $phone;
				$_SESSION['mobile'] = $mobile;
				$_SESSION['active'] = 2;
				$addresslist = file_get_contents('./list/list.mddb');
				$_SESSION['addresslist'] = utf8_encode($addresslist);
				$_SESSION['addresslist'] = str_replace("\0", "", $_SESSION['addresslist']);
				$addresslist = null;
				$_SESSION['sites'] = listSites();
				$_SESSION['myInfo'] = "#" . $name . "|" . $mail . "|" . $description . "|" . $office . "|" . $phone . "|" . $mobile;
				$_SESSION['bookmarks'] = "";
				
				ldap_close($ldapConnection);				
				header("Location:index.php");
			}
		} else {
			$_SESSION['active'] = 0;
			header("Location:logout.php");
			exit();
		}
	}
	
	function writeBookmark($line){
		$file = "./list/Bookmarks/" . $_SESSION['matricula'] . ".mddb";
		$fileExists = file_exists($file);
		if($fileExists){
			$current = file_get_contents($file);
			$current = base64_decode($current);
			$current .= ";" . $line;
			$current = base64_encode($current);
		} else {
			$current = ";" . $line;
			$current = base64_encode($current);
		}
		$write = file_put_contents($file, $current);
		$_SESSION['bookmarks'] = $current;
		return $write;
	}
	
	function eraseBookmark($line){
		$file = "./list/Bookmarks/" . $_SESSION['matricula'] . ".mddb";
		$current = file_get_contents($file);
		$current = base64_decode($current);
		$current = str_replace(";" . $line, "", $current);
		$current = base64_encode($current);
		$erase = file_put_contents($file, $current);
		$_SESSION['bookmarks'] = $current;
		return $erase;
	}

?>