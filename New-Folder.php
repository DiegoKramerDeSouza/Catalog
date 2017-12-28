<?php
	session_start();
	require_once("structure.php");
	require_once("get-date.php");
	$currentUser = $_SESSION['name'];
	
	if(isset($_POST["NewFolder"])){
		$newFolder = $_POST["NewFolder"];
		$targetHome = $_POST["folder"];
		$cmdNewFolder = 'powershell.exe -ExecutionPolicy ByPass -file C:\\xampp\\htdocs\\docshare\\scripts\\Powershell\\New-Folders.ps1 -folder "' . $targetHome . '" -newfolder "' . $newFolder . '"';
		shell_exec($cmdNewFolder);
		
		$writeFile = "share/Files/Metadata_Folder_" . $newFolder . ".mddocs";
		$writeFileAudth = "share/History/_Folder_" . $newFolder . ".mddocs";
		$file = fopen($writeFile, "w");
		$fileAudth = fopen($writeFileAudth, "w");
		$current = file_get_contents($writeFile);
		$currentAudth = file_get_contents($writeFileAudth);
		$txt = "#" . $currentUser . "#" . $timeNow . " por " . $currentUser;
		$Audth = "#" . $dateNow . " por " . $currentUser . " às " . $hourNow . "#" . $timeNow;
		$current .= base64_encode($txt);
		$currentAudth .= base64_encode($Audth);
		file_put_contents($writeFile, $current);
		file_put_contents($writeFileAudth, $currentAudth);
	}
	header("Location:index.php");

?>