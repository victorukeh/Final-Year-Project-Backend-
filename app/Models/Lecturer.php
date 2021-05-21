<?php

namespace App\Models;

use CodeIgniter\Model;

class Lecturer extends Model
{
   protected $DBGroup = 'default';
   protected $table = 'lecturers';
   protected $primarykey = 'id';

   protected $returnType = 'array';
   protected $useSoftDeletes = true;

   protected $allowedFields = ['firstname', 'lastname', 'username', 'email', 'password'];

   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';

   protected $validationRules = [
      'firstname' => 'required|min_length[2]',
      'lastname' => 'required|min_length[2]',
      'username' => 'required|min_length[2]',
      'email' => 'required|valid_email|is_unique[lecturers.email,id,{id}]',
      'password' => 'required|min_length[6]'
   ];
   protected $validationMessages = [
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
   protected $skipValidation = false;
}
