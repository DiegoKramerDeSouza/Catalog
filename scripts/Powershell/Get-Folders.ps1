#Coleta todos os diretórios dentro do caminho especificado
param (
	[Parameter(Mandatory=$true)][string]$folder
)
$OutputEncoding = New-Object -typename System.Text.UTF8Encoding
[Console]::OutputEncoding = New-Object -typename System.Text.UTF8Encoding
$folderList = ""
$folders = Get-ChildItem $folder | Foreach-Object {$_.Name}
if($folders -ne $null){
	foreach ($container in $folders) {
		$folderList = "$folderList$container||"
	}
} else {
	$folderList = ""
}
#write-host $folderList
[Console]::WriteLine("$folderList")