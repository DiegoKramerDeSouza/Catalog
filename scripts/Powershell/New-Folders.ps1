#Cria novo diretório no caminho especificado
param (
	[Parameter(Mandatory=$true)][string]$folder,
	[Parameter(Mandatory=$true)][string]$newfolder
)
$OutputEncoding = New-Object -typename System.Text.UTF8Encoding
[Console]::OutputEncoding = New-Object -typename System.Text.UTF8Encoding

$folder = "{0}\{1}" -f $folder, $newfolder
mkdir $folder
