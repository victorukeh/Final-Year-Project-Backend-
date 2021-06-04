<?php

namespace App\Controllers;

use App\Models\User as UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
// use \CodeIgniter\API\ResponseTrait;
use Exception;
use \CodeIgniter\API\ResponseTrait;
use ReflectionException;

class Auth extends BaseController
{
    use ResponseTrait;
    // use ResponseTrait;

    // public function __construct(){
    //     parent::__construct();
    //     $this->validator = Authentication::validation();
    //     $this->auth->restrict('admin');
    // }

    // public function restrict($group = null, $single = null){
    //     if($group === null){
    //         if($this->login() == true){
    //             return true;
    //         }
    //         else{
    //             $this->respond('Insufficient privilleges');
    //             // show_error($this->CI->lang->line('insufficient_privs'));
    //         }
    //     }
    //     elseif($this->login() == true){
    //         $level = $this->config['auth_groups'][$group];
    //         $userLevel = $this->CI->session->userdata('groupId');

    //         if($userLevel > $level OR $single == true && $userLevel !== $level){
    //             $this->respond('Insufficient privilleges');
    //             // show_error($this->CI->lang->line('insufficient_privs'));
    //         }
    //         return true;

    //     }
    // }


    //@route POST /User
    //@desc Register a user
    //@accesss Private 
    public function register()
    {
        
        // $this->auth->restrict('admin');
        // $role = $this->session->userdata('role');
        // if ($this->login() == true) {
        // $roles = $this->db->get_where("role", array("deleted_at" => null));
        // $user = $this->asArray()->where(['role ' => $role]);    
        // $roles= $user->getwhere([]);
        // if ($this->$roles = 'admin') {
        $rules = [
            'firstname' => 'required|min_length[2]',
            'lastname' => 'required|min_length[2]',
            'username' => 'required|min_length[2]|is_unique[users.username]',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            'role' => 'required|min_length[2]',
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
                'min_length' => 'Must be a minimum of 2 characters',
                'is_unique' => 'The username already exists'
            ],
            'email' => [
                'required' => 'Your Email is required',
                'valid_email' => 'The Email is not valid',
                'is_unique' => 'The email already exists'
            ],
            'role' => [
                'required' => 'Your role is required',
                'min_length' => 'Must be a minimum of 2 characters'
            ],
            'password' => [
                'required' => 'Password is required',
                'min_length' => 'The minimum length is 8 characters'
            ]
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules, $messages)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
        $userModel = new UserModel();
        $userModel->save($input);
        return $this
            ->getJWTForUser(
                $input['username'],
                ResponseInterface::HTTP_CREATED
            );
       
    }

    //@route POST /User
    //@desc Login to the application
    //@accesss Public
    public function login()
    {
        $rules = [
            'username' => 'required|min_length[2]',
            'password' => 'required|min_length[6]|max_length[255]'
        ];
        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];
        $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules, $errors)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
        return $this->getJWTForUser($input['username']);
    }



    private function getJWTForUser(
        string $UserName,
        int $responseCode = ResponseInterface::HTTP_OK
    ) {
        try {
            $model = new UserModel();
            $user = $model->findUserByUsername($UserName);
            unset($user['password']);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($UserName)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}
