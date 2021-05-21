<?php

namespace App\Controllers;

// use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use App\Models\Student as StudentModel;

class Student extends BaseController
{
	use ResponseTrait;

	//@Get request
	//Fetch all students from the database
	public function index()
	{
		// return view('welcome_message');
		$student = new StudentModel;
		$result = $student->findAll();
		return $this->respond($result);
	}

	// @GET request
	// Fetch a student from the database
	public function show($id)
	{
		$student = new StudentModel;
		$result = $student->getWhere(['id' => $id])->getResult();
		if ($result) {
			return $this->respond(($result));
		} else {
			$this->failNotFound('No Student with id = ' . $id . ' found in database');
		}
	}

	//@POST request
	//Create a Student handle
	public function create()
	{
		$data = $this->request->getPost();
		$student = new StudentModel();
		$result = $student->insert($data);
		if ($result) {
			$response = [
				'status' => 201,
				'error' => null,
				'messages' => [
					'success' => 'Student saved'
				]
			];
			return $this->respondCreated($response);
		} else {
			if ($student->errors()) {
				return $this->fail($student->errors());
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
		$student = new StudentModel();
		$result = $student->update($id, $data);
		if ($student) {
			$students = $student->getwhere(['id' => $id])->getResult();
			return $this->respondUpdated($students);
		} else {
			if ($student->errors()) {
				return $this->fail($student->errors());
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
		$student = new StudentModel();
		$data = $student->find($id);
		if ($data) {
			$deleted = $student->delete($id);
			return $this->respondDeleted($deleted);
		} else {
			$response = [
				'status' => 201,
				'error' => null,
				'messages' => [
					'success' => 'Student with '.$id. 'does not exist'
				]
			];
			return $this->respondCreated($response);
			// return $this->respond('Lecturer does not exist');
		}
	}

	//@route Post /Login
	//@desc Login route
	//@access Public
	public function login(){
		

	}
	//@route Post /Lecturer/Register/id
	//@desc Register Courses
	//@access Private
	public function register_coursers(){

	}
}
