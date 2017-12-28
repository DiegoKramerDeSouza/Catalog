import-module activedirectory

$result = ""
$sites = ([System.DirectoryServices.ActiveDirectory.Forest]::GetCurrentForest().Sites).name
foreach($site in $sites){
	$result = "{0};{1}" -f $result, $site
}
[Console]::WriteLine("$result") 