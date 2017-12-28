<?php 
	session_start();
	require_once("structure.php");
	require_once("get-date.php");
	$currentUser = $_SESSION['name'];
	
	echo $headerHtml;
?>
</body>
<?php
	if(isset($_POST['targetfolder'])){
		
		if ($_FILES['fileToUpload']['error'] > 0) {
			echo "Error: " . $_FILES['fileToUpload']['error'] . "<br />";
			print_r($_FILES['fileToUpload']);
			header("Location:index.php?error=FalhaAoIniciar");
		} else {
			$target = $_POST['targetfolder'];
			$user = $_POST['owner'];
			$validExtensions = array('.jpg', '.jpeg', '.gif', '.png', '.doc', '.docx', '.xls', '.xlsx', '.txt', '.pdf', '.zip', '.rar', '.ppt', '.pptx', '.ppsx');
			$fileExtension = strrchr($_FILES['fileToUpload']['name'], ".");
			$filename = basename($_FILES['fileToUpload']['name'], $fileExtension);
			$filename = utf8_decode($filename);
			if (in_array($fileExtension, $validExtensions)) {
				$destination = $target . utf8_decode($_FILES['fileToUpload']['name']);
				if(move_uploaded_file(utf8_decode($_FILES['fileToUpload']['tmp_name']), $destination)){
					echo "Copiado com sucesso!";
					
					//$filename = mb_convert_encoding($filename, 'HTML-ENTITIES', 'UTF-8');
					$writeFile = "share/Files/Metadata_" . $filename . ".mddocs";
					$writeFileAuth = "share/History/_" . $filename . ".mddocs";
					$file = fopen($writeFile, "w");
					$fileAuth = fopen($writeFileAuth, "w");
					$current = file_get_contents($writeFile);
					$currentAuth = file_get_contents($writeFileAuth);
					$txt = "#" . $user . "#" . $timeNow . " por " . $user;
					$Audth = "#" . $dateNow . " por " . $user . " às " . $hourNow . "#" . $timeNow;
					$current .= base64_encode($txt);
					$currentAuth .= base64_encode($Audth);
					file_put_contents($writeFile, $current);
					file_put_contents($writeFileAuth, $currentAuth);
					header("Location:index.php?status=success");
				} else {
					echo "Falha ao mover!";
					header("Location:index.php?error=FalhaAoMover");
				}
			} else {
				echo 'Arquivo inválido!';
				header("Location:index.php?error=ArquivoInvalido");
			}
		}
	} else {
		echo "Destino não encontrado!";
		header("Location:index.php?error=DestinoNaoEncontrado");
	}
	
	
	
?>
</body>
</html>