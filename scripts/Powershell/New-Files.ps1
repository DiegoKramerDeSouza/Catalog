#Cria novo diretório no caminho especificado
param (
	[Parameter(Mandatory=$true)][string]$folder,
	[Parameter(Mandatory=$true)][string]$file
)
$OutputEncoding = New-Object -typename System.Text.UTF8Encoding
[Console]::OutputEncoding = New-Object -typename System.Text.UTF8Encoding

if(-not test-path($file)){
	copy $file $folder /y
} else {
	[Console]::WriteLine("EXISTS")
}

