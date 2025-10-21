<?php

namespace App\Controllers;

use Framework\Session;
use Framework\Validation;
use Framework\Database;
use Framework\Authorization;

class ListingController
{
    protected $db;
    public function __construct()
    {

        // Initialize Database connection
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show All Listings
     * 
     * @return void
     */
    public function index()
    {

        $listings = $this->db->query("SELECT * FROM listings ORDER BY created_at DESC")->fetchAll();

        loadView('listings/index', ['listings' => $listings]);
    }

    /**
     * Show Create Listing Form
     * 
     * @return void
     */
    public function create()
    {
        loadView('listings/create');
    }

    /**
     * Show Single Listing
     * 
     * @param array $params
     * @return void
     */
    public function show($params)
    {
        // :id is a named placeholder.
        // prevents SQL injection, PDO sends the SQL and the data separately to the database engine.
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

    /**
     * Store New Listing
     * 
     * @return void
     */
    public function store()
    {
        $allowedFields = [
            'title',
            'description',
            'salary',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
            'requirements',
            'benefits'
        ];

        // Filter $_POST to only include allowed fields
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        // Store the user id from the session
        $newListingData['user_id'] = Session::get('user')['id']; 

        // Sanitize input data. will apply the sanitize function to each element of the array
        $newListingData = array_map('sanitize', $newListingData);

        // These fields are required
        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . " is required and must be a valid string.";
            }
        }

        if (!empty(($errors))) {
            // Handle errors (e.g., reload the form with error messages)
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }
            $fields = implode(', ', $fields);

            $values = [];
            foreach ($newListingData as $field => $value) {
                // Convert empty strings to NULL
                if ($value === '') {
                    $newListingData[$field] = null;
                }

                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);

            Session::setFlashMessage('success_message', 'Listing created successfully');

            // Redirect to listings page after successful insertion
            redirect('/listings');
        }
    }

    /**
     * Delete a Listing
     * 
     * @param array $params
     * @return void
     */
    public function destroy($params)
    {
        $id = $params['id'];
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        //authorization
        if (!Authorization::isOwner($listing->user_id)) {
            
            // set an error flash message
            Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');
            return redirect('/listings/' . $id);
        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        // set a success flash message
        Session::setFlashMessage('success_message', 'Listing deleted successfully');

        // Redirect to listings page after deletion
        redirect('/listings');
    }

    /**
     * Show the Listing edit form
     * 
     * @param array $params
     * @return void
     */
    public function edit($params)
    {
        // :id is a named placeholder.
        // prevents SQL injection, PDO sends the SQL and the data separately to the database engine.
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        //authorization
        if (!Authorization::isOwner($listing->user_id)) {

            // set an error flash message
            Session::setFlashMessage('error_message', 'You are not authorized to update this listing');
            return redirect('/listings/' . $listing->id);
        }

        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }

    /**
     * Update a Listing
     * 
     * @param array $params
     * @return void
     */
    public function update($params)
    {
        $id = $params['id'];
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        //authorization
        if (!Authorization::isOwner($listing->user_id)) {

            // set an error flash message
            Session::setFlashMessage('error_message', 'You are not authorized to update this listing');
            return redirect('/listings/' . $id);
        }

        $allowedFields = [
            'title',
            'description',
            'salary',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
            'requirements',
            'benefits'
        ];

        $updateValues = [];

        // Filter $_POST to only include allowed fields
        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));
        
        // Sanitize input data. will apply the sanitize function to each element of the array
        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields =  ['title', 'description', 'salary', 'email', 'city', 'state'];
        $errors = [];

        foreach ($requiredFields as $field){
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])){
                $errors[$field] = ucfirst($field) . " is required and must be a valid string.";
            }
        }

        if (!empty(($errors))) {
            loadView('listings/edit', [
                'listing' => $listing,
                'errors' => $errors
            ]);
            exit;
        } else{ 
            $updateFields = [];

            foreach (array_keys($updateValues) as $field){
                $updateFields[] = "{$field} = :{$field}";
            }
        }

        $updateFields = implode(', ', $updateFields);

        $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";
        $updateValues['id'] = $id;
        
        $this->db->query($updateQuery, $updateValues);

        // set an success flash message
        Session::setFlashMessage('success_message', 'Listing Updated');

        redirect("/listings/{$id}");       
    }

    /**
     * Search Listings by keywords and location
     * 
     * @return void
     */
    public function search(){
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords) AND (city LIKE :location OR state LIKE :location) ORDER BY created_at DESC";

        $params = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%"
        ];

        $listings = $this->db->query($query, $params)->fetchAll();

        loadView('/listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }
}