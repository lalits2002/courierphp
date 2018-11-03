<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends BD_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
//        $this->auth();
        $this->load->library('validation');
        $this->load->model('M_main');
    }

    public function test_post() {

        $theCredential = $this->user_data;
        $this->response($theCredential, 200); // OK (200) being the HTTP response code
    }

    public function users_post() {
        $params = $this->input->post();
        $this->validation->set_post($params);

        $this->validation->required(array('FIRSTNAME', 'PHONENO', 'EMAIL', 'LOGINPWD'), 'Fields are required')
                ->email('EMAIL', 'Entered email is not valid')
                ->minlen('PHONENO', 10, 'Invalid length of phone')
                ->callback('_checkDuplicateMail', 'Phone or Email already exists.', array('EMAIL', 'PHONENO'))
//		->callback(array($your_model, 'method_name'), 'Error message', array('parameter1', 'parameter2', 'parameter3'))
//		->callback('_other_func', 'Your error.')
        ;

        if ($this->validation->is_valid()) {
            $result = $this->M_main->createUser($params);
        } else {
            $result = $this->validation->get_error_message();
            exit;
        }
        return $result;
    }

    public function _checkDuplicateMail($email, $phone) {
        $email = $this->validation->get_data($email);
        $phone = $this->validation->get_data($phone);
        $result = $this->M_main->getUser($email, $phone);

        if (count($result) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function users_get($email = '', $phone = '') {
        // Users from a data store e.g. database
        try {
            $result = array();
            $result = $this->M_main->getUser($email, $phone);

            if (is_array($result) && count($result) > 0) {
                return $this->response(['status' => true, 'data' => $result, 'message' => 'success'], REST_Controller::HTTP_OK);
            } else {
                return $this->response([
                            'status' => FALSE,
                            'message' => 'No users were found'
                                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        } catch (Exception $e) {
            echo '<pre>';
            print_r($e);
            exit;
        }
    }

    public function users_delete() {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0) {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
