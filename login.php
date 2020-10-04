<?php
include('function.php');
$db_users = new db_users();

echo "
<form method='post'>
Login:<br><input id='email' name='email'  type='email' size='40' maxlength='40' required><br>
Password:<br><input id='password' name='password'  type='password' size='40' maxlength='40' required><br>
<input id='login' name='login'  type='submit' style='text-decoration: none; color: black' value='Вход'>
</from>
";

if (isset($_POST['login']))
	{
		$password = $_POST['password'];

		$crypt_method = 'AES-256-CBC';
		$crypt_options = 0;

		$secret_key = explode("-", $password)[0];
		$secret_iv = explode("-", $password)[1];


		$crypt_key = hash('sha256', $secret_key);
		$crypt_iv = substr(hash('sha256', $secret_iv), 0, 16);

		$login = $_POST['email'];
		$db = $db_users->query('SELECT * FROM "table" WHERE login="'.$login.'"')->fetchArray(1);
		
		if ($db == false) {echo '<br>Введен неверный логин!';}
		
		$cript_mnemonic = $db['cript1'];

		$decript_text = openssl_decrypt($cript_mnemonic, $crypt_method, $crypt_key, $crypt_options, $crypt_iv);
		$decript = json_decode($decript_text,true);
		
		if ($decript == NULL) {echo '<br>Введен неверный пароль!';}else{echo '<br>Вы вошли!';}
	}