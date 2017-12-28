#Coleta todos os diretórios dentro do caminho especificado
param (
	[Parameter(Mandatory=$true)][string]$username,
	[Parameter(Mandatory=$true)][string]$password,
	[Parameter(Mandatory=$true)][string]$folder
)
$setpassword = ConvertTo-SecureString $password -AsPlainText -Force
$fullusername = "call\$username"
$credential = New-Object System.Management.Automation.PSCredential($fullusername, $setpassword)
#$OutputEncoding = New-Object -typename System.Text.UTF8Encoding
#[Console]::OutputEncoding = New-Object -typename System.Text.UTF8Encoding

try {
	#$response = Start-Process powershell.exe -argumentlist '-executionpolicy', 'bypass', '-file', 'C:\xampp\htdocs\FileManager\scripts\Powershell\Get-Folders.ps1', '-folder', $folder -Credential $credential
	$ssn = New-PSSession -Credential $credential
	try {
		$folderList = ""
		$folders = Get-ChildItem $folder | Where-Object {$_.PSIsContainer} | Foreach-Object {$_.Name}
		foreach ($container in $folders) {
			$folderList = "$folderList$container||"
		}
		$response = $folderList
	} catch {
		$response = "ERROR-02||002"
	}
	Remove-PSSession $ssn
} catch {
	$response = "ERROR||001"
}

write-host "$folderList"

#[Console]::WriteLine("$response")


# cd C:\xampp\htdocs\FileManager\scripts\Powershell
#.\Set-Credentials.ps1 -username s00120 -password sxhkm-1289 -folder "\\fileserver\administrativo\tecnologia"
