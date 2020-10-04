<?php
declare(strict_types=1);
require_once('config/minterapi/vendor/autoload.php');
use Minter\MinterAPI;
use Minter\SDK\MinterWallet;

include('function.php');
$db_users = new db_users();

echo "
<form method='post'>
Login:<br><input id='email' name='email' type='email' size='40' maxlength='40' required><br>
<input id='reg' name='reg' type='submit' style='text-decoration: none; color: black' value='Регистрация'>
</from>
";

if (isset($_POST['reg'])) {
	$login = $_POST['email'];
	$db = $db_users->query('SELECT * FROM "table" WHERE login="'.$login.'"')->fetchArray(1);
	if ($db != false) {echo '<br>Данный аккаунт уже существует!';}
	else
		{
		
			$wallet = MinterWallet::create();
			$mnemonic = $wallet['mnemonic'];
			$address = $wallet['address'];
			$privateKey = $wallet['private_key'];

			$arr = array(
					'mnemonic' => $mnemonic,
					'address' => $address,
					'private_key' => $privateKey
					);

			$json = json_encode($arr, JSON_UNESCAPED_UNICODE);

			$secret_key = gen_password(8);
			$secret_iv = gen_password(8);

			$crypt_method = 'AES-256-CBC';
			$crypt_options = 0;
			$crypt_key = hash('sha256', $secret_key);
			$crypt_iv = substr(hash('sha256', $secret_iv), 0, 16);

			$cript_mnemonic = openssl_encrypt($json,$crypt_method,$crypt_key,$crypt_options,$crypt_iv);
			
			sleep(1);	
			
			$to = $_POST['email'];

			$subject = 'Password';
			$message = "$secret_key-$secret_iv";
			$headers = 'From: admin@wsgamestudio.fun ' . "\r\n" .
						'Reply-To: admin@wsgamestudio.fun ' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers);
			echo 'Сообщение отправлено на почту' . $to . '!<br>Если сообщение не пришло, проверьте папку "спам".';
			
			sleep(1);

			$db_users->exec('CREATE TABLE IF NOT EXISTS "table" (
							"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
							"login" VARCHAR,
							"cript1" VARCHAR,
							"cript2" VARCHAR
						)');
			$db_users->exec('INSERT INTO "table" ("login", "cript1", "cript2")
							VALUES ("'.$to.'", "'.$cript_mnemonic.'", "")');

			sleep(1);

			header_lol('index.php');
		}
	}