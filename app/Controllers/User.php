<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;
use CodeIgniter\Controller;
use \CodeIgniter\API\ResponseTrait;
use App\Models\User as UserModel;

class User extends BaseController
{
    use ResponseTrait;

    //@route Get /User
    //@desc Get all documents
    //@accesss Private  
    public function index(string $role)
    {
        $user = new UserModel();
        return $this->getResponse(
            [
                'message' => 'Users retreived successfuly',
                'users' => $user->findAll()
            ]
        );
    }

    //@route Get /User
    //@desc Get a user
    //@accesss Private  
    public function show($id)
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByID($id);
            return $this->getResponse(
                [
                    'message' => 'User retreived successfully',
                    'user' => $user
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find client with that id'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
        // $user = new UserModel;
        // $result = $user->getwhere(['id ' => $id])->getResult();
        // if ($result) {

        //     return $this->respond($result);
        // } else {
        //     return $this->respond('No document with that id exists');
        // }
    }

    //@route POST /Lecturer
    //@desc Create a Lecturer
    //@access Private
    public function create()
    {

        // if( )
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
                'is_unique' => 'The username already exists in the database'
            ],
            'email' => [
                'required' => 'Your Email is required',
                'valid_email' => 'The Email is not valid',
                'is_unique' => 'The email already exists in the database'
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
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        $user = new userModel;
        $user->save($input);
        return $this->getJWTForUser($input['username'], ResponseInterface::HTTP_CREATED);

        // else {
        //     return $this->respond('You do not have the required privelleges');
        // }

        // $data = $this->request->getPost();
        // $user = new UserModel;
        // $result = $user->insert($data);
        // if ($result) {
        //     $response = [
        //         'status' => 201,
        //         'error' => null,
        //         'messages' => [
        //             'success' => 'User has been saved'
        //         ]
        //     ];
        //     return $this->respondCreated($response);
        // } else {
        //     if ($user->errors()) {
        //         return $this->fail($user->errors());
        //     } else {
        //         return $this->failServerError();
        //     }
        // }
    }



    //@route PUT /Lecturer/id
    //@desc Edit a Lecturer
    //@access Private
    public function update($id)
    {

        $data = $this->request->getRawInput($id);
        $user = new UserModel;
        $result = $user->update($id, $data);
        if ($result) {
            $users = $user->getwhere(['id' => $id])->getResult();
            return $this->respondUpdated($users);
        } else {
            if ($user->errors()) {
                return $this->fail($user->errors());
            }
            if ($result == false) {
                return $this->failServerError();
            }
        }
    }

    //@route DELETE /Lecturer/id
    //@desc Delete a Lecturer
    //@access Private
    public function delete($id)
    {
        $user = new UserModel;
        $data = $user->find($id);
        if ($data) {
            $deleted = $user->delete($id);
            return $this->respondDeleted($deleted);
        } else {
            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'User does not exist'
                ]
            ];
            return $this->respondCreated($response);
            // return $this->respond('Lecturer does not exist');
        }
    }

    private function getJWTForUser(
        string $UserName,
        int $responseCode = ResponseInterface::HTTP_OK
    ) {
        try {
            $model = new userModel;
            $user = $model->findUserByUsername($UserName);
            unset($user['password']);
            helper('jwt');

            return $this->getResponse([
                'message' => 'User authenticated successfully',
                'user' => $user,
                'access_token' => getSignedJWTForUser($UserName)
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
