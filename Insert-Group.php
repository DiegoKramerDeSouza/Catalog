<?php
	require_once("structure.php");
	require_once("get-date.php");
	
	if(isset($_POST["SelectGroup"])){
		$group = $_POST["SelectGroup"];
		$targetFolder = $_POST["targetfolder"];
		$targetFolder = str_replace("/", "\\", $targetFolder);
		$targetobject = $_POST["object"];
		$targetobject = utf8_decode($targetobject);
		$extension = $_POST["extension"];
		if($group != "0"){
			$writeFileAudth = "C:/xampp/htdocs/docshare/share/Files/group/_Metadata/group_" . $targetobject . ".mddocs";
			$fileAudth = fopen($writeFileAudth, "w");
			$currentAudth = file_get_contents($writeFileAudth);
			$Audth = $group . "#" . $targetFolder . "#" . $extension;
			$currentAudth .= $Audth;			
			file_put_contents($writeFileAudth, $currentAudth);
		} else {
			$writeFileAudth = "C:/xampp/htdocs/docshare/share/Files/group/_Metadata/group_" . $targetobject . ".mddocs";
			$writeFileAudth = str_replace("/", "\\", $writeFileAudth);
			$cmd = "cmd.exe /c del /f \"" . $writeFileAudth . "\"";
			$step1 = exec($cmd);
		}
	}
	header("Location:index.php");



?>