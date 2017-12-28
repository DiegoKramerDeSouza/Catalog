import-module activedirectory
$data = "CONTROL"
$users = get-aduser -server "SVDF07W000010.call.br" -filter * -properties * | sort-object name
foreach($user in $users){
	$name = $user.name
	$mail = $user.mail
	$description = $user.description
	$office = $user.office
	$phone = $user.telephonenumber
	$mobile = $user.mobile
	$account = $user.samaccountname
	$data = "{0}#{1}|{2}|{3}|{4}|{5}|{6}|{7}" -f $data, $name, $mail, $description, $office, $phone, $mobile, $account
	
}
$data >> C:\xampp\htdocs\Catalog\list\TEMP\list.mddb
Move-Item C:\xampp\htdocs\Catalog\list\TEMP\list.mddb C:\xampp\htdocs\Catalog\list -force





