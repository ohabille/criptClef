<?php

	function autoCharg($classe) {
		require 'class_'.$classe.'.php';
	}
	spl_autoload_register('autoCharg');

	foreach(array('login', 'password') as $val) {
		${$val} = (isset($_POST[$val]) && !empty($_POST[$val])) ? htmlentities($_POST[$val]): $val;
	}

	$clef = new criptClef($login, $password);

	echo <<<EOF
<form method="post" action="index.php">
	<input type="text" name="login" value="$login"/>
	<input type="text" name="password" value="$password" />
	<input type="submit" value="TEST" />
</form>
<pre>
sha1 : {$clef->getSha1()}
md5 : {$clef->getMd5()}
cript (salt null : alleatoire) : {$clef->getCrypt()}
cript (salt non null) : {$clef->getCrypt("salt")}
</pre>
EOF;
?>