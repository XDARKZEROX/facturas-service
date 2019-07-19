<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Facturas extends REST_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index_post() {

        //Here make an auth token validation 

        
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];

       //testing the auth Token:
       $this->response($this->input->get_request_header('Authorization'));
	}
}
