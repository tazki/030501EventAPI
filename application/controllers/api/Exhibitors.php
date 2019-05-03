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
class Exhibitors extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Base_model');
    }

    public function index_get()
    {
        $event_id = $this->get('e');
        $event_row = $this->Base_model->search_one('event_id='.$event_id, 'tz_event');
        
        $event_start_hour = dateformat($event_row['event_start_date'],'H');
        $event_end_hour = dateformat($event_row['event_end_date'],'H');
        if($event_end_hour == '00')
        {
            $event_end_hour = 17;
        }
        $event_hours = array();
        for($i=$event_start_hour; $i<=$event_end_hour; $i++)
        {
            $event_hours[$i] = $i.':00';
        }
        $data['event_hours'] = $event_hours;

        if(!stristr($event_row['event_start_date'], '0000-00-00') && !stristr($event_row['event_end_date'], '0000-00-00'))
        {
            $arr_event_date_range = createDateRange($event_row['event_start_date'], $event_row['event_end_date']);
            if(is_array($arr_event_date_range))
            {
                foreach($arr_event_date_range as $key => $val)
                {
                    $event_date_range[dateformat($val, 'Y-m-d')] = dateformat($val, 'M d');
                }
            }
        }
        elseif(!stristr($event_row['event_start_date'], '0000-00-00') && stristr($event_row['event_end_date'], '0000-00-00'))
        {
            $event_date_range[dateformat($event_row['event_start_date'], 'Y-m-d')] = dateformat($event_row['event_start_date'], 'M d');
        }
        $data['event_date'] = $event_date_range;

        $user_id = $this->get('u');
        $search_query = '`user_id` = "'.$user_id.'"';
        $rows = $this->Base_model->list_all('tz_exhibitors_appointment', '', '', '', '', $search_query, 'exhibitors_id');
        if(is_array($rows))
        {
            $arr_exhibitors_id = array();
            foreach($rows as $key => $val)
            {
                $arr_exhibitors_id[$key] = $val['exhibitors_id'];
            }
            $exhibitors_id_list = implode(',', $arr_exhibitors_id);
        }

        $search_string = $this->get('q');
        $search_query = '`exhibitors_status` = "2"
            AND `company_name` != ""';
        if($search_string !== NULL)
        {
            $search_query .= ' AND (`company_name` LIKE "%'.$search_string.'%"
                OR `exhibitors_id` LIKE "%'.$search_string.'%"
            )';
        }

        if(isset($exhibitors_id_list) && !empty($exhibitors_id_list))
        {
            $search_query .= ' AND `exhibitors_id` NOT IN('.$exhibitors_id_list.')';
        }

        $rows = $this->Base_model->list_all('tz_exhibitors', '', '', 'company_name', 'asc', $search_query, 'exhibitors_id,first_name,last_name,designation,company_name,country,booth_number');
        // Check if the users data store contains users (in case the database result returns NULL)
        if(is_array($rows) && sizeof($rows) > 0)
        {
            $data['exhibitor_list'] = $rows;
            // Set the response and exit
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

    public function requestdetail_get()
    {
        $id = $this->get('id');
        $search_query = '`tz_exhibitors_appointment_id` = "'.$id.'"';
        $rows = $this->Base_model->list_all('tz_exhibitors_appointment', '', '', '', '', $search_query, 'tz_exhibitors_appointment_id,exhibitors_id,user_id,appointment_date,appointment_status,appointment_message');
        if(is_array($rows))
        {
            $arr_exhibitors_id = array();
            foreach($rows as $key => $val)
            {
                $tz_user_to_appointment_row = $this->Base_model->search_one('tz_exhibitors_appointment_id="'.$id.'" AND user_id="'.$val['user_id'].'"', 'tz_user_to_appointment');
                if(!is_array($tz_user_to_appointment_row))
                {
                    $tz_user_to_appointment['user_id'] = $val['user_id'];
                    $tz_user_to_appointment['tz_exhibitors_appointment_id'] = $id;
                    $tz_user_to_appointment['read_at'] = datenow();
                    $this->Base_model->insert($tz_user_to_appointment, 'tz_user_to_appointment');
                }

                $arr_exhibitors_id[$key] = $val['exhibitors_id'];
                $exhibitors_appointment_rows[$val['exhibitors_id']] = $val;

                if($key == 'appointment_date')
                {
                   $exhibitors_appointment_rows[$val['exhibitors_id']]['appointment_date'] = dateformat($val['appointment_date'], 'Y-m-d');
                   $exhibitors_appointment_rows[$val['exhibitors_id']]['appointment_time'] = dateformat($val['appointment_date'], 'H:i');
                }
                
            }
            $exhibitors_id_list = implode(',', $arr_exhibitors_id);
        }

        $search_string = $this->get('q');
        $search_query = '`exhibitors_status` = "2"
            AND `company_name` != ""';
        if($search_string !== NULL)
        {
            $search_query .= ' AND (`company_name` LIKE "%'.$search_string.'%"
                OR `exhibitors_id` LIKE "%'.$search_string.'%"
            )';
        }

        if(isset($exhibitors_id_list) && !empty($exhibitors_id_list))
        {
            $search_query .= ' AND `exhibitors_id` IN('.$exhibitors_id_list.')';
        }

        $rows = $this->Base_model->list_all('tz_exhibitors', '', '', 'company_name', 'asc', $search_query, 'exhibitors_id,first_name,last_name,designation,company_name,country,booth_number');
        // Check if the users data store contains users (in case the database result returns NULL)
        if(is_array($rows) && sizeof($rows) > 0)
        {
            foreach($rows as $key => $val)
            {                
                if(isset($exhibitors_appointment_rows[$val['exhibitors_id']]))
                {
                    $data = array_merge($val,$exhibitors_appointment_rows[$val['exhibitors_id']]);
                }
            }
            // prearr($rows);
            // prearr($data);
            // Set the response and exit
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

    public function requestlist_get()
    {
        $user_id = $this->get('u');
        // $user_id = 100001;

        $user_to_appointment_status = array();
        $user_to_appointment = $this->Base_model->list_all('tz_user_to_appointment', '', '', '', '', 'user_id="'.$user_id.'"');
        if(is_array($user_to_appointment))
        {
            foreach($user_to_appointment as $key => $val)
            {
                if(!empty($val['read_at']))
                {
                    $user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['read'] = 1;
                }

                if(!empty($val['delete_at']))
                {
                    $user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['delete'] = 1;
                }
            }
        }

        $order_by = 'ORDER BY `created_at` ASC;';
        $search_query = '`ea`.`user_id` = "'.$user_id.'"';
        $search_query .= ' AND `appointment_status` != "declined"';
        $query = $this->db->query(
            'SELECT `ea`.*,
                `e`.`first_name` as `exhibitors_first_name`,
                `e`.`last_name` as `exhibitors_last_name`,
                `e`.`designation` as `exhibitors_designation`,
                `e`.`company_name` as `exhibitors_company_name`,
                `e`.`country` as `exhibitors_country`
            FROM `tz_exhibitors_appointment` `ea`
            LEFT JOIN `tz_exhibitors` `e` ON `e`.`exhibitors_id`=`ea`.`exhibitors_id`
            WHERE '.$search_query.$order_by
        );
        $rows = $query->result_array();

        if ($query->num_rows() > 0) {
            $li_item = '';
            foreach ($rows as $key => $val) {
                $unread = 'unread';
                if(isset($user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['read'])
                    && !isset($user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['delete_at']))
                {
                    $unread = '';
                }

                $li_item .= '<li class="swipeout">
                    <div class="swipeout-content">
                    <a href="appointment_detail.html?id='.$val['tz_exhibitors_appointment_id'].'" class="item-link item-content '.$unread.'">
                      <div class="item-media">
                        <i class="icon material-icons">event</i>
                        <span class="badge color-red noti-badge">&nbsp;</span>
                      </div>
                      <div class="item-inner">
                        <div class="item-title-row">
                          <div class="item-title">'.$val['exhibitors_company_name'].'</div>
                        </div>
                        <div class="item-subtitle '.$val['appointment_status'].'">'.strtoupper($val['appointment_status']).'</div>
                      </div>
                    </a>
                    </div>
                </li>';
            }

            $message['status'] = TRUE;
            $message['request_list'] = $li_item;
            // Set the response and exit
            $this->response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => '<li class="swipeout">
                    <div class="swipeout-content">
                      <div class="item-inner">
                        <div class="item-title-row">
                          <div class="item-title" style="margin:0 auto;">No Request Found</div>
                        </div>
                      </div>
                    </div>
                </li>'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function hasunreadrequestlist_get()
    {
        $user_id = $this->get('u');
        // $user_id = 100001;

        $user_to_appointment_status = array();
        $user_to_appointment = $this->Base_model->list_all('tz_user_to_appointment', '', '', '', '', 'user_id="'.$user_id.'"');
        if(is_array($user_to_appointment))
        {
            foreach($user_to_appointment as $key => $val)
            {
                if(!empty($val['read_at']))
                {
                    $user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['read'] = 1;
                }

                if(!empty($val['delete_at']))
                {
                    $user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['delete'] = 1;
                }
            }
        }

        $search_query = '`user_id` = "'.$user_id.'"';
        $query = $this->db->query(
            'SELECT `tz_exhibitors_appointment_id`
            FROM `tz_exhibitors_appointment`
            WHERE '.$search_query
        );
        $rows = $query->result_array();

        if ($query->num_rows() > 0) {
            $has_unread = array();
            foreach ($rows as $key => $val) {
                if(isset($user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['read'])
                    && !isset($user_to_appointment_status[$val['tz_exhibitors_appointment_id']]['delete_at']))
                {
                    //has nothing to do here
                }
                else
                {
                    $has_unread[$key] = $val['tz_exhibitors_appointment_id'];
                }
            }

            $user_has_unread = false;
            if(sizeof($has_unread) > 0)
            {
                $user_has_unread = true;
            }

            // Set the response and exit
            $this->response($user_has_unread, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => '<li class="swipeout">
                    <div class="swipeout-content">
                      <div class="item-inner">
                        <div class="item-title-row">
                          <div class="item-title" style="margin:0 auto;">No Request Found</div>
                        </div>
                      </div>
                    </div>
                </li>'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function request_post()
    {
        $message['status'] = 'danger';
        $message['alert'] = 'No Data Received';

        $user_id = $this->get('u');
        $this->load->library('form_validation');
        $post_data = $this->input->post(null, false);
        if(sizeof($_POST) > 0)
        {
            $count = 0;
            foreach($post_data as $field_name => $field_val)
            {
                if($field_name == 'exhibitors_id')
                {
                    $config_data[$count]['field'] = $field_name;
                    $config_data[$count]['label'] = 'Company Name';
                    $config_data[$count]['rules'] = 'trim|required';
                    $count++;
                }
                elseif(!in_array($field_name, array('appointment_message')))
                {
                    $config_data[$count]['field'] = $field_name;
                    $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                    $config_data[$count]['rules'] = 'trim|required';
                    $count++;
                }
            }

            $this->config_data = $config_data;
            $this->form_validation->set_rules($this->config_data);
            if($this->form_validation->run() == true)
            {
                $clean_data['exhibitors_id'] = $post_data['exhibitors_id'];
                $clean_data['appointment_message'] = $post_data['appointment_message'];
                $clean_data['appointment_date'] = $post_data['appointment_date'].' '.$post_data['appointment_time'].':00';
                $clean_data['appointment_status'] = 'pending';
                $clean_data['user_id'] = $user_id;
                $clean_data['created_at'] = datenow();
                $clean_data['modified_at'] = datenow();
                $id = $this->Base_model->insert($clean_data, 'tz_exhibitors_appointment');
                if(!empty($id))
                {
                    $message['exhibitors_id'] = $post_data['exhibitors_id'];
                    $message['status'] = 'success';
                    $message['alert'] = 'Request Successfully Sent';
                }
                else
                {
                    $message['status'] = 'danger';
                    $message['alert'] = 'Data Failed to Save';
                }
            }
            else
            {
                #array form variables need to be declare as array
                $message = array();
                $message['status'] = 'danger';
                // $message['alert'] = validation_errors('<span>', '</span>');
                foreach($post_data as $field_name => $field_val)
                {
                    $error_msg = form_error($field_name, '<span class="error">', '</span>');
                    if(!empty($error_msg))
                    {
                        $message['alert'][$field_name] = $error_msg;   
                    }
                }
            }
        }        

        // Set the response and exit
        $this->response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function login_post()
    {
        $this->load->library('form_validation');
        $post_data = $this->input->post(null, false);
        if(sizeof($_POST) > 0)
        {
            $message['status'] = 'danger';
            $message['alert'] = 'Fill all the fields.';

            $this->config_login = array(
                array(
                    'field'   => 'company_id',
                    'label'   => 'Company ID',
                    'rules'   => 'trim|required'
                ),
                array(
                    'field'   => 'password',
                    'label'   => 'Password',
                    'rules'   => 'trim|required'
                )
            );
            $this->form_validation->set_rules($this->config_login);
            if($this->form_validation->run() == true)
            {
                $cond['trashed_by'] = 0;
                $cond['exhibitors_status'] = 2;
                $cond['company_id'] = $post_data['company_id'];
                $cond['password'] = do_hash($post_data['password'], 'md5');
                $row = $this->Base_model->search_one($cond, 'tz_exhibitors');
                if(isset($row) && is_array($row))
                {
                    $message['exhibitors_id'] = $row['exhibitors_id'];
                    $message['status'] = 'success';
                    $message['alert'] = 'Welcome back!';
                }
                else
                {
                    $message['status'] = 'danger';
                    $message['alert'] = 'Company ID or Password Incorrect!';
                }
            }
            else
            {
                #array form variables need to be declare as array
                $message = array();
                $message['status'] = 'danger';
                foreach($post_data as $field_name => $field_val)
                {
                    $error_msg = form_error($field_name, '<span class="error">', '</span>');
                    if(!empty($error_msg))
                    {
                        $message['alert'][$field_name] = $error_msg;   
                    }
                }
            }
        }

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function exhibitorrequestlist_get()
    {
        $exhibitors_id = $this->get('exhibitors_id');

        $order_by = 'ORDER BY `created_at` DESC;';
        $search_query = '`ea`.`exhibitors_id` = "'.$exhibitors_id.'"';
        $query = $this->db->query(
            'SELECT `ea`.*,
                `eta`.`read_at`,
                `eta`.`delete_at`,
                CONCAT(`u`.`user_first_name`, " ", `u`.`user_last_name`) as `name`,
                `u`.`user_company_name`
            FROM `tz_exhibitors_appointment` `ea`
            LEFT JOIN `tz_exhibitors_to_appointment` `eta` ON `eta`.`tz_exhibitors_appointment_id`=`ea`.`tz_exhibitors_appointment_id`
            LEFT JOIN `tz_users` `u` ON `u`.`user_id`=`ea`.`user_id`
            WHERE '.$search_query.$order_by
        );
        $rows = $query->result_array();

        $rows_accepted['accepted'] = array();
        $rows_pending['pending'] = array();
        $rows_declined['declined'] = array();
        if ($query->num_rows() > 0) {
            foreach ($rows as $key => $val) {
                if(!empty($val['read_at']))
                {
                    $val['read'] = 1;
                }

                if(!empty($val['delete_at']))
                {
                    $val['delete'] = 1;
                }

                switch($val['appointment_status'])
                {
                    case 'accepted':
                        $rows_accepted['accepted'][$key] = $val;
                    break;
                    case 'pending':
                        $rows_pending['pending'][$key] = $val;
                    break;
                    case 'declined':
                        $rows_declined['declined'][$key] = $val;
                    break;
                }
            }
        }
        $rows_new_sort_appointment = array_merge($rows_accepted['accepted'], $rows_pending['pending'], $rows_declined['declined']);
        if(sizeof($rows_new_sort_appointment) > 0)
        {
            $li_item = '';
            foreach($rows_new_sort_appointment as $key => $val)
            {
                $unread = 'unread';
                if(isset($val['read']) && !isset($val['delete_at']))
                {
                    $unread = '';
                }

                $li_item .= '<li class="swipeout">
                    <div class="swipeout-content">
                    <a href="appointment_exhibitor_detail.html?id='.$val['tz_exhibitors_appointment_id'].'" class="item-link item-content '.$unread.'">
                        <div class="item-media">
                        <i class="icon material-icons">event</i>
                        <span class="badge color-red noti-badge">&nbsp;</span>
                        </div>
                        <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title" style="white-space:normal">'.$val['user_company_name'].' - '.$val['name'].'</div>
                        </div>
                        <div class="item-subtitle">'.dateformat($val['appointment_date'], 'Y-m-d H:i').' - <span class="'.$val['appointment_status'].'">'.strtoupper($val['appointment_status']).'</span></div>
                        </div>
                    </a>
                    </div>
                </li>';
            }

            $message['status'] = TRUE;
            $message['request_list'] = $li_item;
            // Set the response and exit
            $this->response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => '<li class="swipeout">
                    <div class="swipeout-content">
                      <div class="item-inner">
                        <div class="item-title-row">
                          <div class="item-title" style="margin:0 auto;">No Request Found</div>
                        </div>
                      </div>
                    </div>
                </li>'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function hasunreadexhibitorrequestlist_get()
    {
        $exhibitors_id = $this->get('exhibitors_id');

        $search_query = '`ea`.`exhibitors_id` = "'.$exhibitors_id.'"';
        $query = $this->db->query(
            'SELECT `ea`.*,
                `eta`.`read_at`,
                `eta`.`delete_at`
            FROM `tz_exhibitors_appointment` `ea`
            LEFT JOIN `tz_exhibitors_to_appointment` `eta` ON `eta`.`tz_exhibitors_appointment_id`=`ea`.`tz_exhibitors_appointment_id`
            WHERE '.$search_query
        );
        $rows = $query->result_array();
        if ($query->num_rows() > 0) {
            foreach ($rows as $key => $val) {
                if(!empty($val['read_at']))
                {
                    $rows[$key]['read'] = 1;
                }

                if(!empty($val['delete_at']))
                {
                    $rows[$key]['delete'] = 1;
                }
            }
        }
        if(sizeof($rows) > 0)
        {
            $has_unread = array();
            foreach($rows as $key => $val)
            {
                if(isset($val['read']) && !isset($val['delete_at']))
                {
                    //has nothing to do here
                }
                else
                {
                    $has_unread[$key] = $val['tz_exhibitors_appointment_id'];
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
                'message' => '<li class="swipeout">
                    <div class="swipeout-content">
                      <div class="item-inner">
                        <div class="item-title-row">
                          <div class="item-title" style="margin:0 auto;">No Request Found</div>
                        </div>
                      </div>
                    </div>
                </li>'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function requestexhibitordetail_get()
    {
        $id = $this->get('id');
        $search_query = '`tz_exhibitors_appointment_id` = "'.$id.'"';
        $rows = $this->Base_model->list_all('tz_exhibitors_appointment', '', '', '', '', $search_query, 'tz_exhibitors_appointment_id,exhibitors_id,user_id,appointment_date,appointment_status,appointment_message');
        if(is_array($rows))
        {
            $arr_user_id = array();
            foreach($rows as $key => $val)
            {
                $tz_exhibitors_to_appointment_row = $this->Base_model->search_one('tz_exhibitors_appointment_id="'.$id.'" AND exhibitors_id="'.$val['exhibitors_id'].'"', 'tz_exhibitors_to_appointment');
                if(!is_array($tz_exhibitors_to_appointment_row))
                {
                    $tz_exhibitors_to_appointment['exhibitors_id'] = $val['exhibitors_id'];
                    $tz_exhibitors_to_appointment['tz_exhibitors_appointment_id'] = $id;
                    $tz_exhibitors_to_appointment['read_at'] = datenow();
                    $this->Base_model->insert($tz_exhibitors_to_appointment, 'tz_exhibitors_to_appointment');
                }

                $arr_user_id[$key] = $val['user_id'];
                $exhibitors_appointment_rows[$val['user_id']] = $val;

                if($key == 'appointment_date')
                {
                   $exhibitors_appointment_rows[$val['user_id']]['appointment_date'] = dateformat($val['appointment_date'], 'Y-m-d');
                   $exhibitors_appointment_rows[$val['user_id']]['appointment_time'] = dateformat($val['appointment_date'], 'H:i');
                }
                
            }
            $user_id_list = implode(',', $arr_user_id);
        }

        if(isset($user_id_list) && !empty($user_id_list))
        {
            $search_query = ' `user_id` IN('.$user_id_list.')';
            $rows = $this->Base_model->list_all('tz_users', '', '', '', '', $search_query);
        }
        
        // Check if the users data store contains users (in case the database result returns NULL)
        if(is_array($rows) && sizeof($rows) > 0)
        {
            foreach($rows as $key => $val)
            {
                if(isset($exhibitors_appointment_rows[$val['user_id']]))
                {
                    $data = array_merge($val,$exhibitors_appointment_rows[$val['user_id']]);
                }
            }
            // prearr($rows);
            // prearr($data);
            // Set the response and exit
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

    public function requestexhibitorupdate_get()
    {
        $id = $this->get('id');
        $status = $this->get('status');
        $message['id'] = $id;
        
        $cond = array('tz_exhibitors_appointment_id' => $id);
        $clean_data = array('appointment_status' => $status, 'modified_at' => datenow());
        $this->Base_model->update($clean_data, $cond, 'tz_exhibitors_appointment');

        $message['status'] = 'success';
        $message['alert'] = 'Status Updated Successfully';
        $this->response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}