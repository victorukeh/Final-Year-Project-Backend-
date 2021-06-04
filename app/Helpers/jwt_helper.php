<?php
use App\Models\User as UserModel;
use Config\Services;
// include __DIR__ . "vendor/autoload.php";
// require_once('vendor/autoload.php');
use \Firebase\JWT\JWT;

//Fetching the authentication header
function getJWTFromRequest($authenticationHeader) : string {
    if(is_null($authenticationHeader)){
        throw new Exception('Misssing or Invalid JWT in request');
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return explode(' ', $authenticationHeader)[1];
} 
//Checks if user with the token has username present in the database
function validateJWTFromRequest(string $encodedToken){
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $user = new UserModel();
    $user ->findUserByUsername($decodedToken->username);
}

//Creates a JWT token for the user
function getSignedJWTForUser(string $username){
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenxpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'username' => $username,
        'iat' => $issuedAtTime,
        'exp' => $tokenxpiration,

    ]; 
    $jwt = JWT::encode($payload, Services::getSecretKey());
    return $jwt;
}