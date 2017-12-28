#Coleta os colaboradores relacionados ao grupo especificado
param (
	[Parameter(Mandatory=$true)][string]$group
)
Import-Module activedirectory
$OutputEncoding = New-Object -typename System.Text.UTF8Encoding
[Console]::OutputEncoding = New-Object -typename System.Text.UTF8Encoding
$users = Get-ADGroupMember -Identity $group | Sort-Object Name | Foreach-Object {$_.Name}
$usersList = ""
foreach ($user in $users) {
	$usersList = "$usersList$user||"
}
[Console]::WriteLine("$usersList") 