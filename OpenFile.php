<?php
	$ppup = $_GET['PPUP'];
	$ppup = explode("|||", $ppup);
	$up = $ppup[0];
	$pp = $ppup[1];
	$pp = base64_decode($pp);

	echo "<div align='left'>
				<p><b>Arquivo " . $_GET["file"] . " em " . $_GET["IP"] . ".</b></p>
				<p><b>Caminho " . $_GET["folder"] . ".</b></p>
			</div>";
			
	$file = utf8_decode($_GET["file"]);
	//PSExec com credenciais
	//$callCmd = 'cmd.exe /c psexec \\\\' . $_GET["IP"] . ' -u call\\' . $up . ' -p ' . $pp . ' cmd /c "\\\\10.61.200.111\\Default\\Home\\' . $file . '"';
	
	//PSExec sem credenciais
	$callCmd = 'cmd.exe /c psexec \\\\' . $_GET["IP"] . ' cmd /c "\\\\10.61.200.111\\Default\\Home\\' . $file . '"';
	$retorno = exec($callCmd);
		
	echo '<script type="text/javascript">
			window.close();
		</script>';
?>