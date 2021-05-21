<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use \CodeIgniter\API\ResponseTrait;
use App\Models\User as UserModel;

class User extends BaseController
{
    use ResponseTrait;

    //@route Get /Lecturer
    //@desc Get all documents
    //@accesss Private  
    public function index()
    {
        $user = new UserModel;
        $result = $user->findAll();
        if ($user->errors()) {
            return $this->fail($user->errors());
        } else {
            return $this->respond($result);
        }
    }

    // GET request
    // Get details for a single Lecturer
    public function show($id)
    {

        $user = new UserModel;
        $result = $user->getwhere(['id ' => $id])->getResult();
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respond('No document with that id exists');
        }
    }

    //@route POST /Lecturer
    //@desc Create a Lecturer
    //@access Private
    public function create()
    {
        $data = $this->request->getPost();
        $user = new UserModel;
        $result = $user->insert($data);
        if ($result) {
            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'User has been saved'
                ]
            ];
            return $this->respondCreated($response);
        } else {
            if ($user->errors()) {
                return $this->fail($user->errors());
            } else {
                return $this->failServerError();
            }
        }
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
        if($data){
            $deleted = $user->delete($id);
            return $this->respondDeleted($deleted);
        }
        else{
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
}
