<?php

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request');
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return explode(' ', $authenticationHeader)[1];
}

function validateJWTFromRequest(string $encodedToken)
{
    $key = Services::getSecretKey();
    // $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    $userModel = new UserModel();
    // $userModel->findUserByEmailAddress($decodedToken->email);
    $userModel->getUserByGuid($decodedToken->guid);
}

function validateJWTFromRequestOutputUser(string $encodedToken)
{
    $key = Services::getSecretKey();
    // $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    $userModel = new UserModel();
    // $userModel->findUserByEmailAddress($decodedToken->email);
    $user = $userModel->getUserByGuid($decodedToken->guid);
    return $user;
}

// function getSignedJWTForUser(string $email)
function getSignedJWTForUser(string $guid)
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        // 'email' => $email,
        'guid' => $guid,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');
    return $jwt;
}

function imageExperience()
{
    $images = array(
        'http://fuegonetworxservices.com/assets/img/experience/energy.jpg',
         'http://fuegonetworxservices.com/assets/img/experience/creative.jpg',
         'http://fuegonetworxservices.com/assets/img/experience/focus.jpg',
         'http://fuegonetworxservices.com/assets/img/experience/bliss.jpg',
         'http://fuegonetworxservices.com/assets/img/experience/calm.jpg',
         'http://fuegonetworxservices.com/assets/img/experience/Sleep3.png',
         'http://fuegonetworxservices.com/assets/img/experience/arouse.jpg',
         'http://fuegonetworxservices.com/assets/img/experience/Comfort-2.png'
    );
    return $images;
}