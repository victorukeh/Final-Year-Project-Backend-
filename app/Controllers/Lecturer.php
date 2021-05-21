<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Lecturer as LecturerModel;
use \CodeIgniter\API\ResponseTrait;

class Lecturer extends BaseController
{
    use ResponseTrait;

    //@route Get /Lecturer
    //@desc Get all documents
    //@accesss Private  
    public function index()
    {
        $lecturer = new LecturerModel;
        $result = $lecturer->findAll();
        if ($lecturer->errors()) {
            return $this->fail($lecturer->errors());
        } else {
            return $this->respond($result);
        }
    }

    // GET request
    // Get details for a single Lecturer
    public function show($id)
    {

        $lecturer = new LecturerModel;
        $result = $lecturer->getwhere(['id ' => $id])->getResult();
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
        $lecturer = new LecturerModel;
        $result = $lecturer->insert($data);
        if ($result) {
            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'Lecturer has been saved'
                ]
            ];
            return $this->respondCreated($response);
        } else {
            if ($lecturer->errors()) {
                return $this->fail($lecturer->errors());
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
        $lecturer = new LecturerModel;
        $result = $lecturer->update($id, $data);
        if ($result) {
            $lecturers = $lecturer->getwhere(['id' => $id])->getResult();
            return $this->respondUpdated($lecturers);
        } else {
            if ($lecturer->errors()) {
                return $this->fail($lecturer->errors());
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
        $lecturer = new LecturerModel;
        $data = $lecturer->find($id);
        if($data){
            $deleted = $lecturer->delete($id);
            return $this->respondDeleted($deleted);
        }
        else{
            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'Lecturer does not exist'
                ]
            ];
            return $this->respondCreated($response);
            // return $this->respond('Lecturer does not exist');
        }
       
    }
}
