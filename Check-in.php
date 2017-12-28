<?php
	session_start();
	require_once("get-date.php");
	$currentUser = $_SESSION['name'];
	
	if(isset($_POST["targetfolder"])){
		$targetFolder = $_POST["targetfolder"];
		$targetFolder = str_replace("/", "\\", $targetFolder);
		$targetobject = $_POST["object"];
		$targetobject = utf8_decode($targetobject);
		$extension = $_POST["extension"];
		
		$writeFileAudth = "C:\\xampp\\htdocs\\docshare\\share\\Check-out\\check-out_" . $targetobject . ".mddocs";
		$cmd = "cmd.exe /c del /f \"" . $writeFileAudth . "\"";
		$step1 = exec($cmd);
		
		$writeFileAudth = "C:\\xampp\\htdocs\\docshare\\share\\Users\\" . $currentUser . "\\Check-out\\" . $targetobject . ".mddocs";
		$cmd = "cmd.exe /c del /f \"" . $writeFileAudth . "\"";
		$step2 = exec($cmd);
		
		$writeFileAudth = "C:/xampp/htdocs/docshare/share/History/_" . $targetobject . ".mddocs";
		$fileAudth = fopen($writeFileAudth, "w");
		$currentAudth = file_get_contents($writeFileAudth);
		$Audth = "#" . $dateNow . " por " . $currentUser . " às " . $hourNow . "#" . $timeNow;
		$currentAudth .= base64_encode($Audth);
		file_put_contents($writeFileAudth, $currentAudth);
		
	}
	header("Location:index.php");



?>