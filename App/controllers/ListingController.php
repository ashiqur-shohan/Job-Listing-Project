<?php

namespace App\Controllers;

use Framework\Validation;
use Framework\Database;

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

        $newListingData['user_id'] = 1; // Temporary user_id until auth is implemented

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
            // Insert the new listing into the database


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

        if(!$listing){
            ErrorController::notFound("Listing not found");
            return;
        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);
        // Redirect to listings page after deletion
        redirect('/listings');
    }
}