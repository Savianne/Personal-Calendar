<?php

function getUserInfoFromGoogleClientService($client, $token) {
    
    $client->setAccessToken($token);

    $google_service = new Google_Service_Oauth2($client);

    $user_info = $google_service->userinfo->get();

    return $user_info;
}