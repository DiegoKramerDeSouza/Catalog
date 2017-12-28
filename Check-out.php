<?php
	session_start();
	require_once("get-date.php");
	$currentUser = $_SESSION['name'];
	
	if(isset($_POST["targetfolder"])){
		$targetFolder = $_POST["targetfolder"];
		$targetFolder = str_replace("/", "\\", $targetFolder);
		$targetFolder = utf8_decode($targetFolder);
		$targetobject = $_POST["object"];
		$targetobject = utf8_decode($targetobject);
		$extension = $_POST["extension"];
		$count = 1;
		$cmd = "cmd.exe /c copy /y \"" . $targetFolder . "\" \"C:\\xampp\\htdocs\\docshare\\share\\Check-out\\_Versions\"";
		$step1 = exec($cmd);
		
		$versions = "C:/xampp/htdocs/docshare/share/Check-out/_Versions/" . $targetobject . "(" . $count . ")." . $extension;												
		$fileExists = file_exists($versions);
		while($fileExists){
			$count++;
			$versions = "C:/xampp/htdocs/docshare/share/Check-out/_Versions/" . $targetobject . "(" . $count . ")." . $extension;												
			$fileExists = file_exists($versions);
		}
		$cmd = "cmd.exe /c ren \"C:\\xampp\\htdocs\\docshare\\share\\Check-out\\_Versions\\" . $targetobject . "." . $extension . "\" \"" . $targetobject . "(" . $count . ")." . $extension . "\"";
		$step2 = exec($cmd);
		
		$writeFileAudth = "C:/xampp/htdocs/docshare/share/Check-out/check-out_" . $targetobject . ".mddocs";
		$fileAudth = fopen($writeFileAudth, "w");
		$currentAudth = file_get_contents($writeFileAudth);
		$Audth = $dateNow . " por " . $currentUser . " às " . $hourNow . "#" . $currentUser;
		$currentAudth .= base64_encode($Audth);
		file_put_contents($writeFileAudth, $currentAudth);
		
		$writeFileAudth = "C:/xampp/htdocs/docshare/share/Users/" . $currentUser . "/Check-out/" . $targetobject . ".mddocs";
		$fileAudth = fopen($writeFileAudth, "w");
		$currentAudth = file_get_contents($writeFileAudth);
		$Audth = $targetFolder . "#" . $targetobject . "." . $extension . "#" . $dateNow . " às " . $hourNow;
		$currentAudth .= base64_encode($Audth);
		file_put_contents($writeFileAudth, $currentAudth);
	}
	header("Location:index.php");



?>