<?php

	function autoCharg($classe) {
		require 'class_'.$classe.'.php';
	}
	spl_autoload_register('autoCharg');

	foreach(CryptKey::getParamsKeys() as $val)
		${$val} = (isset($_POST[$val]) ? htmlentities($_POST[$val]): '');

	$key = new CryptKey($login, $password);
?>

<form method="post" action="index.php">
	<input type="text" name="login" placeholder="login"/>
	<input type="text" name="password" placeholder="password" />
	<input type="submit" value="TEST" />
</form>
<p>
login : <?= (empty($login) ? 'login': $login) ?>
</p>
<p>
password : <?= (empty($password) ? 'password': $password) ?>
</p>
<p>
chaîne générée : <?= $key->getKey() ?>
</p>
