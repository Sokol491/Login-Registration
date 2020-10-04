<?php
function header_lol($url)
	{
		echo "<script>window.location.href='".$url."'</script>";
		exit;
	}
function JSON ($url)
	{
		$data = file_get_contents($url);
		return json_decode($data);
	}
function gen_password($length = 6)
{
	$password = '';
	$arr = array(
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
		'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
		'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
	);
 
	for ($i = 0; $i < $length; $i++) {
		$password .= $arr[random_int(0, count($arr) - 1)];
	}
	return $password;
}
class db_users extends SQLite3
{
    function __construct()
    {
        $this->open(explode('public_html', $_SERVER['DOCUMENT_ROOT'])[0] . 'db_users.sqlite');
    }
}