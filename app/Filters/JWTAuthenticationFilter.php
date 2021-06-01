<?php

namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;

//This file authenticates whether a user is authorized 
// and then gives the user access for a time limit if authorized
class JWTAuthenticationFilter implements FilterInterface{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null){
        $authenticationHeader = $request->getServer('HTTP_AUTHORIZATION');

        try{
            helper('jwt');
            $encodedToken = getJWTFromRequest($authenticationHeader);
            validateJWTFromRequest($encodedToken);
            return $request;       
        } catch (Exception $e){
                return Services::response()->setJSON(
                    [
                        'error' => $e->getMessage()
                    ]
                )->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
    } 

    public function after(RequestInterface $request,
                          ResponseInterface $response,
                          $arguments = null)
    {
    }
}