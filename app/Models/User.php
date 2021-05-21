<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class User extends Model
{
   protected $DBGroup = 'default';
   protected $table = 'users';
   protected $primarykey = 'id';

   protected $returnType = 'array';
   // protected $useSoftDeletes = true;

   protected $allowedFields = [
      'firstname',
      'lastname',
      'username',
      'email',
      'role',
      'password'
   ];

   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';
   protected $beforeInsert = ['beforeInsert'];
   protected $beforeUpdate = ['beforeUpdate'];

   protected $validationRules = [
      'firstname' => 'required|min_length[2]',
      'lastname' => 'required|min_length[2]',
      'username' => 'required|min_length[2]',
      'email' => 'required|valid_email|is_unique[users.email]',
      //role
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

   protected function beforeInsert($data)
   {
      return $this->getUpdatedDataWithHashedPasswords($data);
   }

   protected function beforeUpdate($data)
   {
      return $this->getUpdatedDataWithHashedPasswords($data);
   }

   private function getUpdatedDataWithHashedPasswords($data)
   {
      if (isset($data['data']['password'])) {
         $plaintextPassword = $data['data']['password'];
         $data['data']['password'] = $this->hashPassword($plaintextPassword);
      }
      return $data;
      // if($data){
      //    $this->find('data', 'password')
      // }
   }
   private function hashPassword(string $plaintextPassword)
   {
      return password_hash($plaintextPassword, PASSWORD_BCRYPT);
   }

   public function findUserByEmailAddress($email)
   {
      $user = $this->asArray()->where(['email' => $email])->first();
      if (!$user) {
         throw new Exception('User does not exist for specified email Address');
         return $user;
      }
   }
}
