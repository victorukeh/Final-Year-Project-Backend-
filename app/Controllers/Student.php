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
		if($result){
			return $this->respond(($result));
		}
		else{
			$this->failNotFound('No Student with id = '.$id.' found in database');
		}
	}

	//@POST request
	//Create a Student handle
	public function create(){
		$data = $this->request->getPost();
		$student = new StudentModel();
		$result = $student->insert($data);
		if($student->error()){
			return $this->fail($student->errors());
			// return $this->failServerError();
		}
	}
 }
