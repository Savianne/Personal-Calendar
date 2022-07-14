<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

$client = new Google_Client();

$client->setClientId("731603140362-uf4235vqt77ut1vqmff4pk7e6rup7r9o.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost:82/GoogleOauth2Client/authenticate.php");
$client->addScope('email');
$client->addScope('profile');