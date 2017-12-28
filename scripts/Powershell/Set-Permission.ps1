param (
	[Parameter(Mandatory=$true)][string]$folder,
	[Parameter(Mandatory=$true)][string]$group,
	[Parameter(Mandatory=$true)][string]$permition,
	[Parameter(Mandatory=$true)][string]$action,
	[switch]$inherit = $false,
	[switch]$remove = $false
)
$OutputEncoding = New-Object -typename System.Text.UTF8Encoding
[Console]::OutputEncoding = New-Object -typename System.Text.UTF8Encoding
$group = "CALL\$group"
$error = ""
try{
	if ($inherit) {
		$Ar = New-Object System.Security.AccessControl.FileSystemAccessRule("$group","$permition","ContainerInherit,ObjectInherit","None","$action")
	} else {
		$Ar = New-Object System.Security.AccessControl.FileSystemAccessRule("$group","$permition","$action")
	}
	if ($remove) {
		$Acl.RemoveAccessRule($Ar)
	} else {
		$Acl.SetAccessRule($Ar)
	}
	Set-Acl $folder $Acl
	$Acl = Get-Acl $folder
	$error = "Permiss�o executada com sucesso!"
}catch{
	$error = "Falha ao executar a a��o!"
}

[Console]::WriteLine("Exit error: $error")

#==================================================================================================================
#-ContainerInherit,ObjectInherit: For�a todos os demais folders e arquivos filhos a herdar as permiss�es definidas;
#-None: N�o intefere com as configura��es de heran�a dos folders filhos;
