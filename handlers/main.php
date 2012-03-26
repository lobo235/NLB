<?php

echo 'This is the site index<br />';
echo 'Welcome, '.$user->getFirstName();

$u = new User();
$u->setEmail('7@netlobo.com');
$u->setFirstName('Justin');
$u->setLastName('Barlow');
$u->setPassword(md5(PASSWORD_HASH_SALT.'7uju7*Nn'));
$u->setUsername('lobo235');
$u->save();