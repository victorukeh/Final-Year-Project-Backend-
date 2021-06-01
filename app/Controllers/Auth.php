<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User as userModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use \CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Auth extends BaseController
{

    use ResponseTrait;
    //@route POST /User
    //@desc Register a user
    //@accesss Private 
    public function register()
    {
        $rules = [
            'firstname' => 'required|min_length[2]',
            'lastname' => 'required|min_length[2]',
            'username' => 'required|min_length[2]',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            //role\
            'password' => 'required|min_length[6]|max_length[255]'
        ];
        $messages = [
            'firstname' => [
                'required' => 'Your firstname is required',
                'min_length' => 'Must be a minimum of 2 characters'
             ],
             'lastname' => [
                'required' => 'Your lastname is required',
                'min_length' => 'Must be a minimum of 2 characters'
             ],
             'username' => [
                'required' => 'Your username is required',
                'min_length' => 'Must be a minimum of 2 characters'
             ],
             'email' => [
                'required' => 'Your Email is required',
                'valid_email' => 'The Email is not valid',
                'is_unique' => 'The email already exists in the database'
             ],
             'password' => [
                'required' => 'Password is required',
                'min_length' => 'The minimum length is 8 characters'
             ]
        ];
        $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules, $messages)) {
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        $user = new userModel;
        $user->save($input);
        return $this->getJWTForUser($input['email'], ResponseInterface::HTTP_CREATED);
    }


    private function getJWTForUser(
        string $emailAddress,
        int $responseCode = ResponseInterface::HTTP_OK
    ) {
        try {
            $user = new userModel;
            $users = $user->findUserByEmailAddress([$emailAddress]);
            unset($users['password']);
            helper('jwt');

            return $this->getResponse([
                'message' => 'User authenticated successfully',
                'users' => $users,
                'access_token' => getSignedJWTForUser($emailAddress)
            ]);
        } catch (Exception $exception) {
            return $this->getResponse(
                [
                    'error' => $exception->getMessage()
                ],
                $responseCode
            );
        }
    }
}
