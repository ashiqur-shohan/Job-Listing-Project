<?php

namespace App\controllers;
use Framework\Database;
use Framework\Validation;
use Framework\Session;

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

        // Get new user id
        $userId = $this->db->conn->lastInsertId();

        //set user session
        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ]);

        // Redirect to login page
        header('Location: /auth/login');
    }

    /**
     * Logout a User and Kill session
     * 
     * @return void
     */
    public function logout(){
        Session::clearAll();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect ('/');
    }

    /**
     * Authenicate a user with email and password
     * 
     * @return void
     */
    public function authenticate() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        //validation
        if(!Validation::email($email)){
            $errors['email'] = 'Please eneter a valid email';
        }

        if(!Validation::string($password, 6)){
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if(!empty($errors)){
            loadView('users/login',[
                'errors' => $errors
            ]);
            exit;
        }

        // Check If email exist
        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email',$params)->fetch();
        
        if(!$user) {
            $errors['email'] = 'Incorrect Credentials';
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Check if password if correct
        if(!password_verify($password, $user->password)){
            $errors['email'] = 'Incorrect Credentials';
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Set user session
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state
        ]);
        
        redirect('/');
    }
}