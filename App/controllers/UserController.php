<?php

namespace App\controllers;
use Framework\Database;
use Framework\Validation;


class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the login page
     * 
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Show the registration page
     * 
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * Store user in database
     * 
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        $errors = [];

        //Validattion
        if (!Validation::string($name, 2, 100)) {
            $errors['name'] = "Name must be between 2 and 50 characters";
        }

        if(!Validation::email($email)){
            $errors['email'] = "Email is not valid";
        }
        
        if(!Validation::string($password, 6, 50)){
            $errors['password'] = "Password must be atleast 6 characters";
        }

        if(!Validation::match($password, $passwordConfirmation)){
            $errors['password_confirmation'] = "Passwords do not match";
        }

        if(!empty($errors)){
            loadView('users/create',[
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
                ]);
                exit;
        }
        
        // Check if email already exists
        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user){
            $errors['email'] = "Email already exists";
            loadView('users/create',[
                'errors' => $errors
            ]);
            exit;
        }

        //create user account
        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)  
        ];

        $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $params);

        // Redirect to login page
        header('Location: /auth/login');
    }
}