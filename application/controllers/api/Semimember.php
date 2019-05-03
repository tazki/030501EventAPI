<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Semimember extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Base_model');
    }

    public function index_get()
    {
        $search_string = $this->get('q');
        $search_query = '`membership_status` = "2"';
        if($search_string !== NULL)
        {
            $search_query .= ' AND (`company_name` LIKE "%'.$search_string.'%"
                OR `semi_membership_id` LIKE "%'.$search_string.'%"
            )';
        }
        $rows = $this->Base_model->list_all('tz_semicon_member_company', '', '', 'company_name', 'asc', $search_query, 'semi_membership_id,company_name,company_country');
        // Check if the users data store contains users (in case the database result returns NULL)
        if(is_array($rows) && sizeof($rows) > 0)
        {            
            // Set the response and exit
            $this->response($rows, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No events were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
}