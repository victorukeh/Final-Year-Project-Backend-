<?php

namespace App\Models;

use CodeIgniter\Model;

class Student extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'students';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['firstname', 'lastname', 'username', 'email', 'password', 'program'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'firstname' => 'required|min_length[2]',
        'lastname' => 'required|min_length[2]',
        'username' => 'required|min_length[10]',
        'email' => [
            'rules' => 'required|valid_email|is_unique[students.email]'
        ],
        'password' => 'required|min_length[6]', 
        'program' => 'required|min_length[8]'
    ];
    protected $validationMessages = [
        'firstname' => [
            'required' => 'Your firstname is required',
            'min_length' => 'Your firstname is shorter than 2 characters'
        ],
        'lastname' => [
            'required' => 'Your lastname is required',
            'min_length' => 'Your lastname is shorter than 2 characters'
        ],
        'username' => [
            'required' => 'Your username is required',
            'min_length' => 'Your username is shorter than 10 characters'
        ],
        'email' => [
            'required' => 'Your Email is required',
            'valid_email' => 'Not a valid email address',
            'is_unique' => 'Email already exists'
        ],
        'password' => [
            'required' => 'Your password is required',
            'min_length' => 'Your username is shorter than 6 characters'
        ], 
        'program' => [
            'required' => 'Your program is required',
            'min_length' => 'Your username is shorter than 8 characters'
        ]
    ];
    protected $skipValidation = false;
    // 'email' => 'required|valid_email|is_unique[students.email]'
}
