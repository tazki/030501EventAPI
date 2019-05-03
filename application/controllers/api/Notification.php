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
class Notification extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Base_model');
    }

    public function index_get()
    {
        $user_id = $this->get('user_id');
        $search_string = $this->get('q');
        $search_query = '`notification_status` = "2" AND `trashed_at` IS NULL';
        // if($search_string !== NULL)
        // {
        //     $search_query .= ' AND (`company_name` LIKE "%'.$search_string.'%"
        //         OR `semi_membership_id` LIKE "%'.$search_string.'%"
        //     )';
        // }
        $notification_status = array();
        $user_to_notification = $this->Base_model->list_all('tz_user_to_notification', '', '', '', '', 'user_id="'.$user_id.'"');
        if(is_array($user_to_notification))
        {
            foreach($user_to_notification as $key => $val)
            {
                if(!empty($val['read_at']))
                {
                    $notification_status[$val['notification_id']]['read'] = 1;
                }

                if(!empty($val['delete_at']))
                {
                    $notification_status[$val['notification_id']]['delete'] = 1;
                }
            }
            
        }
        $rows = $this->Base_model->list_all('tz_notification', '', '', 'created_at', 'desc', $search_query);
        // Check if the users data store contains users (in case the database result returns NULL)
        if(is_array($rows) && sizeof($rows) > 0)
        {
            foreach($rows as $key => $val)
            {
                if(isset($notification_status[$val['notification_id']]['read'])
                    && !isset($notification_status[$val['notification_id']]['delete_at']))
                {
                    $rows[$key]['read'] = 1;    
                }
            }
            // Set the response and exit
            $this->response($rows, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No notification were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function hasunread_get()
    {
        $user_id = $this->get('user_id');
        $notification_status = array();
        $user_to_notification = $this->Base_model->list_all('tz_user_to_notification', '', '', '', '', 'user_id="'.$user_id.'"');
        if(is_array($user_to_notification))
        {
            foreach($user_to_notification as $key => $val)
            {
                if(!empty($val['read_at']))
                {
                    $notification_status[$val['notification_id']]['read'] = 1;
                }

                if(!empty($val['delete_at']))
                {
                    $notification_status[$val['notification_id']]['delete'] = 1;
                }
            }    
        }
        $search_query = '`notification_status` = "2" AND `trashed_at` IS NULL';
        $rows = $this->Base_model->list_all('tz_notification', '', '', 'created_at', 'desc', $search_query);
        // Check if the users data store contains users (in case the database result returns NULL)
        $has_unread = array();
        if(is_array($rows) && sizeof($rows) > 0)
        {
            foreach($rows as $key => $val)
            {
                if(isset($notification_status[$val['notification_id']]['read'])
                    && !isset($notification_status[$val['notification_id']]['delete_at']))
                {
                    //has nothiing to do here
                }
                else
                {
                    $has_unread[$key] = $val['notification_id'];
                }
            }

            $user_has_unread = false;
            if(sizeof($has_unread) > 0)
            {
                $user_has_unread = true;
            }

            // Set the response and exit
            $this->response($user_has_unread, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No notification were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function read_get()
    {
        $user_id = $this->get('user_id');
        $notification_id = $this->get('n_id');
        if(!empty($user_id) && !empty($notification_id))
        {
            $user_to_notification_row = $this->Base_model->search_one('notification_id="'.$notification_id.'" AND user_id="'.$user_id.'"', 'tz_user_to_notification');
            if(!is_array($user_to_notification_row))
            {
                $tz_user_to_notification['user_id'] = $user_id;
                $tz_user_to_notification['notification_id'] = $notification_id;
                $tz_user_to_notification['read_at'] = datenow();
                $this->Base_model->insert($tz_user_to_notification, 'tz_user_to_notification');
            }

            $row = $this->Base_model->search_one('notification_id="'.$notification_id.'"', 'tz_notification');
            $this->response($row, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
}