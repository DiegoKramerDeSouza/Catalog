<?php
	require_once("structure.php");
	require_once("get-date.php");
	
	if(isset($_POST["SelectClass"])){
		$class = $_POST["SelectClass"];
		$targetFolder = $_POST["targetfolder"];
		$targetFolder = str_replace("/", "\\", $targetFolder);
		$targetobject = $_POST["object"];
		$targetobject = utf8_decode($targetobject);
		$extension = $_POST["extension"];
		if($class != "0"){
			$writeFileAudth = "C:/xampp/htdocs/docshare/share/Files/class/_Metadata/class_" . $targetobject . ".mddocs";
			$fileAudth = fopen($writeFileAudth, "w");
			$currentAudth = file_get_contents($writeFileAudth);
			$Audth = $class . "#" . $targetFolder . "#" . $extension;
			$currentAudth .= $Audth;			
			file_put_contents($writeFileAudth, $currentAudth);
		} else {
			$writeFileAudth = "C:/xampp/htdocs/docshare/share/Files/class/_Metadata/class_" . $targetobject . ".mddocs";
			$writeFileAudth = str_replace("/", "\\", $writeFileAudth);
			$cmd = "cmd.exe /c del /f \"" . $writeFileAudth . "\"";
			$step1 = exec($cmd);
		}
	}
	header("Location:index.php");



?>