#Cria novo diretório no caminho especificado
param (
	[Parameter(Mandatory=$true)][string]$foldername
	#[Parameter(Mandatory=$false)][string]$targetfolder
	#[Parameter(Mandatory=$true)][string]$object
)
$OutputEncoding = New-Object -typename System.Text.UTF8Encoding
[Console]::OutputEncoding = New-Object -typename System.Text.UTF8Encoding
$metadata = "C:\xampp\htdocs\docshare\share\Files\Metadata_{0}.mddocs" -f $target 
$newmetadata = "Metadata_{0}" -f $object
$oldName = $foldername.split(".")
$deleteHistory = "C:\xampp\htdocs\docshare\share\History\_{0}.mddocs" -f $oldName[0] 
$targetfolder

remove-item -path $deleteHistory -force

#ren $foldername $target
#ren $metadata $newmetadata

