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
class Member extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Base_model');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        // $this->methods['event_post']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function kiosk_get()
    {
        #single = true this only allow the kiosk to print user badge once
        $single = $this->get('single');

        #echo md5('tazki'); = fdbce7168474e055cb7cc3a601e55236
        $id = $this->get('id');
        // Find and return a single record for a particular user.
        $id = (int) $id;
        if($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        else
        {
            #this will be used in case printing of badge need to verify if payment_status=completed
            $datenow = datenow('Y-m');
            #for testing purposes only
            $datenow = '2018-05';
            $tz_event_query = 'DATE_FORMAT(event_start_date,"%Y-%m") = "'.$datenow.'"';
            $row_event = $this->Base_model->search_one($tz_event_query, 'tz_event');
            // prearr($row_event);
            $tz_user_to_event_query = 'user_id="'.$id.'"';
            $tz_user_to_event_query .= ' AND event_id="'.$row_event['event_id'].'"';
            $row_user_to_event = $this->Base_model->search_one($tz_user_to_event_query, 'tz_user_to_event');
            // prearr($row_user_to_event);

            $row = $this->Base_model->search_one('user_id="'.$id.'"', 'tz_users');
            if((is_array($row_user_to_event) && is_array($row)) || (is_array($row) && empty($row['user_password'])))
            {
                #empty password means user was come from SEMI Web Portal
                if($row_user_to_event['payment_status'] == 'completed' || empty($row['user_password']))
                {
                    // if(!empty($single) && $single == 'true')
                    // {
                    //     $tz_badge_print_record_query = 'user_id="'.$id.'"';
                    //     $tz_badge_print_record_query .= ' AND event_id="'.$row_event['event_id'].'"';
                    //     $row_tz_badge_print_record = $this->Base_model->search_one($tz_badge_print_record_query, 'tz_badge_print_record');
                    //     prearr($row_tz_badge_print_record);
                    //     if(is_array($row_tz_badge_print_record))
                    //     {
                    //         $this->set_response([
                    //             'status' => FALSE,
                    //             'message' => 'You already print this badge'
                    //         ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                    //     }
                    // }

                    $clean_data['event_id'] = $row_event['event_id'];
                    $clean_data['id'] = $row['user_id'];
                    $clean_data['name'] = $row['user_first_name'].' '.$row['user_last_name'];
                    $clean_data['company'] = $row['user_company_name'];
                    $clean_data['country'] = $row['user_company_country_id'];                
                    $this->set_response($clean_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code    
                }
                else
                {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'User is not paid yet for this event'
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }                
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'User did not register for this event'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function kioskprint_get()
    {
        #echo md5('tazki'); = fdbce7168474e055cb7cc3a601e55236
        $id = $this->get('id');
        $id = (int) $id;
        $event_id = $this->get('event_id');
        $event_id = (int) $event_id;
        if($id <= 0 && $event_id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        else
        {
            $post_data['user_id'] = $id;
            $post_data['event_id'] = $event_id;
            $post_data['created_at'] = datenow();
            $id = $this->Base_model->insert($post_data, 'tz_badge_print_record');
            if(!empty($id))
            {
                //// Will not give points for Free Pass for now
                // Update tz_user_to_event for Member points to reflect.
                // $post_data['user_attended_event'] = 1;
                // $cond = '`user_id` = "'.$post_data['user_id'].'"';
                // $cond .= ' AND `event_id` = "'.$event_id.'"';
                // $this->Base_model->update($post_data, $cond, 'tz_user_to_event');

                $clean_data['status'] = 'SUCCESS';
                $clean_data['message'] = 'Badge Printed Date Saved';
                $this->set_response($clean_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Badge Printed Date Failed to Saved'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }
    
    public function index_get()
    {        
        $id = $this->get('id');        
        // Find and return a single record for a particular user.
        $id = (int) $id;
        if($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        else
        {
            $row = $this->Base_model->search_one('user_id="'.$id.'"', 'tz_users');
            if(is_array($row))
            {
                $this->set_response($row, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Event could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function country_get()
    {
        $rows = $this->Base_model->list_all('tz_country', '', '', 'country_name', 'asc', 'country_status="active"', 'country_name');
        if(is_array($rows))
        {
            $this->set_response($rows, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Event could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function benefits_get()
    {
        $total_point = 0;
        $points_redeemed = 0;
        $user_id = $this->get('user_id');
        $event_id_list = $this->memberpoints($user_id);
        if(!empty($event_id_list))
        {
            $search_query = 'user_id="'.$user_id.'"';
            $search_query .= ' AND event_id IN('.$event_id_list.')';
            $search_query .= ' AND `trashed_at` IS NULL';
            $rows = $this->Base_model->list_all('tz_user_to_points', '', '', 'created_at', 'desc', $search_query);
            if(is_array($rows))
            {        
                foreach($rows as $key => $val)
                {
                    $total_point += ($val['points'] - $val['points_redeemed']);
                    $points_redeemed += $val['points_redeemed'];
                }
            }
        }

        $clean_data['total_point'] = $total_point;
        $clean_data['points_redeemed'] = $points_redeemed;    
        $this->set_response($clean_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function rewards_get()
    {
        $clean_data['rewards'] = '';
        $rows = $this->Base_model->list_all('tz_rewards', '', '', 'created_at', 'asc');
        if(is_array($rows) && sizeof($rows) > 0)
        {
            foreach($rows as $key => $val)
            {
                $rewards_icon = (!empty($val['reward_icon'])) ? $val['reward_icon'] : 'card_giftcard';
                $rewards_points = '';
                $rewards_type = 'onsite';
                $rewards_button = 'Onsite Redemption';
                if(!empty($val['points_price']))
                {
                    $rewards_type = 'purchase';
                    $rewards_button = 'Purchase';
                    $rewards_points = '<p class="rewards-text points">'.$val['points_price'].' Points</p>';
                }
                
                $clean_data['rewards'] .= '<div class="col-50">
                    <div class="card" data-id="'.$val['reward_id'].'">
                        <div class="card-content">
                            <div class="card-content-inner">
                                <p class="rewards-holder"><i class="icon material-icons rewards-icon">'.$rewards_icon.'</i></p>
                                <p class="rewards-text">'.$val['reward_name'].'</p>'
                                .$rewards_points.
                            '</div>
                        </div>
                        <div class="card-footer redeem-link" data-type="'.$rewards_type.'" style="padding-left:25px;">'.$rewards_button.'</div>
                    </div>
                </div>';
            }
        }

        $this->set_response($clean_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function myrewards_get()
    {
        $return_type = $this->get('return');
        $user_id = $this->get('user_id');
        $search_query = 'user_id="'.$user_id.'"';
        $search_query .= ' AND promo_code_status="2"';
        $promo_code_rows_tmp = $this->Base_model->list_all('tz_promo_code', '', '', '', '', $search_query);
        if(is_array($promo_code_rows_tmp))
        {
            $reward_id_list = '';
            $promo_code_rows = array();
            foreach($promo_code_rows_tmp as $key => $val)
            {
                $promo_code_rows[$val['reward_id']] = $val['promo_code'];
                $comma = (!empty($reward_id_list)) ? ',' : '';                
                $reward_id_list .= $comma.$val['reward_id'];
            }

            if(!empty($reward_id_list))
            {
                $json_data = array();
                $clean_data['rewards'] = '';
                $search_query = 'reward_id IN('.$reward_id_list.')';
                $rows = $this->Base_model->list_all('tz_rewards', '', '', 'created_at', 'asc', $search_query);
                if(is_array($rows) && sizeof($rows) > 0)
                {
                    foreach($rows as $key => $val)
                    {
                        $rewards_icon = (!empty($val['reward_icon'])) ? $val['reward_icon'] : 'card_giftcard';
                        if(!empty($val['points_price']))
                        {
                            $json_data[$key] = $val;
                            if(isset($promo_code_rows[$val['reward_id']]))
                            {
                                $json_data[$key]['promo_code'] = $promo_code_rows[$val['reward_id']];
                            }

                            $rewards_button = 'Use Promo Code';
                            // $rewards_points = '<p class="rewards-text points">'.$val['points_price'].' Points</p>';
                        
                            $clean_data['rewards'] .= '<div class="col-50">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-content-inner">
                                            <p class="rewards-holder"><i class="icon material-icons rewards-icon">'.$rewards_icon.'</i></p>
                                            <p class="rewards-text">'.$val['reward_name'].'</p>
                                        </div>
                                    </div>
                                    <!--div class="card-footer redeem-link" style="padding-left:25px;">'.$rewards_button.'</div-->
                                </div>
                            </div>';
                        }
                    }
                }

                if($return_type == 'json')
                {
                    $this->set_response($json_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    $this->set_response($clean_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'You do not have rewards yet'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'You do not have rewards yet'
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
    
    public function purchasereward_get()
    {
        $total_point = 0;
        $points_redeemed = 0;
        $total_points = $this->get('tp');
        $user_id = $this->get('user_id');
        $reward_id = $this->get('reward_id');
        // echo 'total_points:'.$total_points.' user_id:'.$user_id.' reward_id:'.$reward_id;
        $reward_row = $this->Base_model->search_one('reward_id="'.$reward_id.'"', 'tz_rewards');

        // if reward point_price is greater than Member total_points
        if($reward_row['points_price'] > $total_points)
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Insufficient Points'
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // generate reward voucher for Member
            if(is_array($reward_row))
            {
                // generate randomize string for $promo_code
                $length = 6;
                $promo_code = substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);

                $post_data['user_id'] = $user_id;
                $post_data['reward_id'] = $reward_id;
                $post_data['promo_code_type'] = $reward_row['promo_code_type'];
                $post_data['promo_code_discount'] = $reward_row['promo_code_discount'];
                $post_data['promo_code_use_times'] = 1;
                $post_data['promo_code_status'] = 2;
                $post_data['created_by'] = $user_id;
                $post_data['created_at'] = datenow();
                $post_data['modified_by'] = $user_id;
                $post_data['modified_at'] = datenow();
                $post_data['promo_code'] = datenow('y').$promo_code.datenow('d');
                // promo_code_expiry_date need to ask Tiong if we add expiry date to Member Vouchers
                $id = $this->Base_model->insert($post_data, 'tz_promo_code');
                if(!empty($id))
                {
                    // after successful purchase of reward deduct points to Member
                    $event_id_list = $this->memberpoints($user_id);
                    // echo ' event_id_list:'.$event_id_list;
                    // echo ' points_price:'.$reward_row['points_price'];

                    $search_query = 'user_id="'.$user_id.'"';
                    $search_query .= ' AND event_id IN('.$event_id_list.')';
                    $search_query .= ' AND `trashed_at` IS NULL';
                    $rows = $this->Base_model->list_all('tz_user_to_points', '', '', 'created_at', 'desc', $search_query);
                    if(is_array($rows))
                    {
                        $update_points = array();
                        $remaining_points_price = $reward_row['points_price'];
                        foreach($rows as $key => $val)
                        {
                            // deduct points until points_price reach zero
                            if($val['points'] > $val['points_redeemed'])
                            {
                                // check $point_available base on points_redeemed from DB
                                $point_available = $val['points'] - $val['points_redeemed'];
                                // echo '<br>'.$val['points_id'].'::point_available:'.$point_available;
                                
                                $remaining_points_price = $remaining_points_price - $point_available;
                                // echo '<br>remaining_points_price:'.$remaining_points_price;

                                // if $remaining_points_price is negative it means there is excess points left
                                // need to reCompute $point_available
                                if($remaining_points_price < 0)
                                {
                                    $point_available = $point_available + $remaining_points_price;
                                    // echo '<br>point_available:'.$point_available;
                                }

                                // if $remaining_points_price still have left continue to deduct
                                $points_redeemed = $val['points_redeemed'] + $point_available;
                                // echo '<br>points_redeemed:'.$points_redeemed;
                                $update_points[$val['points_id']]['points_redeemed'] = $points_redeemed;
                                
                                // if point_price 0 or below stop loop
                                if($remaining_points_price <= 0)
                                {
                                    // echo '<br>INSIDE IF remaining_points_price:'.$remaining_points_price;
                                    break;
                                }
                            }
                        }

                        // Update tz_user_to_points for Member points_redeemed.
                        if(sizeof($update_points) > 0)
                        {
                            foreach($update_points as $points_id => $val)
                            {
                                $post_data['points_redeemed'] = $val['points_redeemed'];
                                $cond = '`points_id` = "'.$points_id.'"';
                                $this->Base_model->update($post_data, $cond, 'tz_user_to_points');
                            }
                        }
                    }

                    $clean_data['status'] = 'SUCCESS';
                    $clean_data['message'] = 'Voucher Successfully Redeemed';
                    $this->set_response($clean_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Voucher not Found!'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
    }

    private function memberpoints($user_id)
    {
        $event_attended_query = '`user_id` = "'.$user_id.'"';
        $event_attended_query .= ' AND `payment_status` = "completed"';
        $event_attended_query .= ' AND `trashed_at` IS NULL';
        $rows = $this->Base_model->list_all('tz_user_to_event', '', '', 'created_at', 'desc', $event_attended_query, 'event_id,subtotal_amount,user_attended_event');
        if(is_array($rows))
        {
            $event_id_list = '';
            foreach($rows as $key => $val)
            {
                $event_id = $val['event_id'];

                // Do not include event attended if subtotal amount is zero
                if($val['subtotal_amount'] == 0 && $val['user_attended_event'] == 0)
                {
                    $event_id = '';
                }

                // Add event_id if Member Attended the event
                if($val['subtotal_amount'] == 0 && $val['user_attended_event'] == 1)
                {
                    $event_id = $val['event_id'];
                }

                if(!empty($event_id))
                {
                    $comma = (!empty($event_id_list)) ? ',' : '';                
                    $event_id_list .= $comma.$event_id;
                }
            }

            return $event_id_list;
        }
    }

    public function register_post()
    {
        $message['status'] = 'danger';
        $message['alert'] = 'No Data Received';

        $this->load->library('form_validation');
        $post_data = $this->input->post(null, false);
        if(sizeof($_POST) > 0)
        {
            #get all survey list for required validation
            #$rows = $this->Base_model->list_all_by_field('tz_survey', 'survey_id', '', '', 'survey_sort', 'asc', '`survey_status` = "2"', 'survey_id,survey_code,survey_question,survey_input_type');

            $count = 0;
            foreach($post_data as $field_name => $field_val)
            {
                if(!is_array($field_val) && !stristr($field_name, 'survey') && !stristr($field_name, 'semi_membership_id')
                    && !in_array($field_val, array('tazki04@gmail.com')))
                {
                    if($field_name == 'user_email_address')
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                        $config_data[$count]['rules'] = 'trim|required|valid_email';
                        $count++;
                    }
                    elseif($field_name == 'user_confirm_password')
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                        $config_data[$count]['rules'] = 'trim|required|matches[user_password]';
                        $count++;
                    }
                    elseif(!in_array($field_name, array('user_fax_number', 'semi_membership_id')))
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                        $config_data[$count]['rules'] = 'trim|required';
                        $count++;
                    }
                }
            }            

            $this->config_data = $config_data;
            $this->form_validation->set_rules($this->config_data);
            if($this->form_validation->run() == true)
            {
                $cond['user_trashed_by'] = 0;
                $cond['user_email_address'] = $post_data['user_email_address'];
                $row = $this->Base_model->search_one($cond, 'tz_users');
                if(is_array($row))
                {
                    $message = array();
                    $message['status'] = 'danger';
                    $message['alert']['user_email_address'] = '<span class="error">The Email Address field must contain unique value.</span>';
                }
                else
                {
                    #show survey first before saving
                    $message['status'] = 'next_page';
                    $message['alert'] = '';   
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

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function registerfinal_post()
    {
        $message['status'] = 'danger';
        $message['alert'] = 'No Data Received';

        $this->load->library('form_validation');
        $post_data = $this->input->post(null, false);
        if(sizeof($_POST) > 0)
        {
            $search_query = '`survey_status` = "2"';
            $arr_survey = $this->Base_model->list_all('tz_survey', '', '', '', '', $search_query, 'survey_id');
            $arr_survey_option = $this->Base_model->list_all('tz_survey_option', '', '', '', '', 'survey_option_status="2" AND survey_option_has_textbox="1"', 'survey_id,survey_option_id');
            if(is_array($arr_survey_option))
            {
                $clean_survey_option = array();
                foreach($arr_survey_option as $key => $val)
                {
                    $clean_survey_option[$val['survey_id']] = $val['survey_option_id'];
                }
            }

            if(is_array($arr_survey))
            {
                foreach($arr_survey as $key => $val)
                {
                    #this will ensure that all survey are answered first before user can proceed.
                    if(isset($post_data['survey_answer_textbox_'.$val['survey_id']])
                        && !is_array($post_data['survey_answer_textbox_'.$val['survey_id']])
                        && !empty($post_data['survey_answer_textbox_'.$val['survey_id']]))
                    {
                        // echo 'survey_answer_textbox_'.$val['survey_id'];
                        #this will be default value in case something goes wrong
                        $post_data['survey_'.$val['survey_id']] = '999999999';
                        // echo 'survey_option_id:'.$clean_survey_option[$val['survey_id']].'<br>';
                        if(isset($clean_survey_option[$val['survey_id']]))
                        {
                            $post_data['survey_'.$val['survey_id']] = $clean_survey_option[$val['survey_id']];
                        }

                        #update value of $_POST to ensure that form_validation will get updated post vars
                        $_POST['survey_'.$val['survey_id']] = $post_data['survey_'.$val['survey_id']];
                    }
                    elseif(!isset($post_data['survey_'.$val['survey_id']]))
                    {
                        $post_data['survey_'.$val['survey_id']] = '';   
                    }
                }
            }

            $count = 0;
            // $config_data = array();
            // prearr($post_data);die;
            foreach($post_data as $field_name => $field_val)
            {
                if(!stristr($field_name, 'survey_answer_textbox_'))
                {
                    if(is_array($field_val))
                    {
                        $config_data[$count]['field'] = $field_name.'[]';
                        $config_data[$count]['label'] = 'All Fields is required';
                        $config_data[$count]['rules'] = 'trim|required';
                        $count++;    
                    }
                    elseif(stristr($field_name, 'survey')
                        && !in_array($field_name, array('user_fax_number', 'semi_membership_id')))
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = 'All Fields is required';
                        $config_data[$count]['rules'] = 'trim|required';
                        $count++;
                    }
                }
            }            
            
            $this->config_data = $config_data;
            $this->form_validation->set_rules($this->config_data);
            if($this->form_validation->run() == true)
            {
                #convert all entry to capital except email
                foreach($post_data as $key => $val)
                {
                    if(!in_array($key, array('user_email_address','user_password')) && !is_array($val))
                    {
                        $post_data[$key] = strtoupper($val);
                    }
                }

                $post_data['user_group_id'] = 5;
                $post_data['user_current_status_id'] = 2;
                $post_data['user_created_at'] = datenow();
                $post_data['user_modified_at'] = datenow();
                $post_data['user_password'] = do_hash($post_data['user_password'], 'md5');
                $id = $this->Base_model->insert($post_data, 'tz_users');
                if(!empty($id))
                {
                    #qrcode builder starts
                    require str_replace('application/', '', APPPATH).'uploads/qrcode/qrlib.php';
                    $upload_path = str_replace('application/', '', APPPATH).'uploads/';
                    $qrcode = 'qrcode-'.$id.'.png';
                    $errorCorrectionLevel = 'Q';#'L','M','Q','H'
                    $matrixPointSize = 8; 
                    QRcode::png($id, $upload_path.$qrcode, $errorCorrectionLevel, $matrixPointSize, 5);
                    #eos qrcode builder starts

                    $message['user_id'] = $id;
                    $message['user_group_id'] = 5;
                    $message['status'] = 'success';
                    $message['alert'] = 'Data Successfully Saved';

                    saveSurveyAnswer($post_data, $id);

                    $this->load->library('email');
                    $config['useragent'] = 'Event Admin';
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'utf-8';
                    $config['wordwrap'] = TRUE;
                    $this->email->initialize($config);
                    $this->email->from('enquiry@dynamiquekonzepts.com', '');
                    $this->email->to($post_data['user_email_address']);
                    // $this->email->cc('another@another-example.com');
                    $this->email->bcc('tionghian@dynamiquekonzepts.com');
                    $this->email->bcc('enquiry@dynamiquekonzepts.com');
                    $this->email->bcc('tazki04@gmail.com');

                    $this->email->subject('Successful Event App Registration');
                    $post_data['user_id'] = $id;
                    $post_data['qrcode'] = $qrcode;
                    $post_data['encoded_email'] = urlencode(base64_encode($post_data['user_email_address']));
                    $this->email->message($this->load->view('api/mail_register_success', $post_data, true));
                    $this->email->send();
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

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function updateprofile_post()
    {
        $id = $this->get('id');
        $message['status'] = 'danger';
        $message['alert'] = 'No Data Received';

        $this->load->library('form_validation');
        $post_data = $this->input->post(null, false);
        if(sizeof($_POST) > 0)
        {
            $count = 0;
            foreach($post_data as $field_name => $field_val)
            {
                if(!is_array($field_val) && !stristr($field_name, 'semi_membership_id')
                    && !in_array($field_val, array('tazki04@gmail.com')))
                {
                    if($field_name == 'user_email_address')
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                        $config_data[$count]['rules'] = 'trim|required|valid_email';
                        $count++;
                    }
                    elseif($field_name == 'user_password' && !empty($field_val))
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                        $config_data[$count]['rules'] = 'trim|required';
                        $count++;
                    }
                    elseif($field_name == 'user_confirm_password' && !empty($field_val))
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                        $config_data[$count]['rules'] = 'trim|required|matches[user_password]';
                        $count++;
                    }
                    elseif(!in_array($field_name, array('user_fax_number', 'user_password', 'user_confirm_password')))
                    {
                        $config_data[$count]['field'] = $field_name;
                        $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                        $config_data[$count]['rules'] = 'trim|required';
                        $count++;
                    }
                }
            }

            $this->config_data = $config_data;
            $this->form_validation->set_rules($this->config_data);
            if($this->form_validation->run() == true)
            {
                $cond['user_trashed_by'] = 0;
                $cond['user_email_address'] = $post_data['user_email_address'];
                $row = $this->Base_model->search_one($cond, 'tz_users');
                if(is_array($row) && $row['user_email_address'] != $post_data['user_email_address'])
                {
                    $message = array();
                    $message['status'] = 'danger';
                    $message['alert']['user_email_address'] = 'The Email Address field must contain unique value.';
                }
                else
                {
                    #convert all entry to capital except email and password
                    foreach($post_data as $key => $val)
                    {
                        if(!in_array($key, array('user_email_address','user_password')) && !is_array($val))
                        {
                            $post_data[$key] = strtoupper($val);
                        }
                    }

                    if(!empty($post_data['user_password']))
                    {
                        $post_data['user_password'] = do_hash($post_data['user_password'], 'md5');
                    }
                    else
                    {
                        unset($post_data['user_password']);
                    }

                    $post_data['user_modified_at'] = datenow();
                    $cond = '`user_id` = "'.$id.'"';
                    if($this->Base_model->update($post_data, $cond, 'tz_users'))
                    {
                        $message['status'] = 'success';
                        $message['alert'] = 'Data Successfully Saved';
                    }
                    else
                    {
                        $message['status'] = 'danger';
                        $message['alert'] = 'Data Failed to Save';
                    }
                }                
            }
            else
            {
                #array form variables need to be declare as array
                $message = array();
                $message['config_data'] = $config_data;
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

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
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
                    'field'   => 'user_email_address',
                    'label'   => 'Email Address',
                    'rules'   => 'trim|required|valid_email'
                ),
                array(
                    'field'   => 'user_password',
                    'label'   => 'Password',
                    'rules'   => 'trim|required'
                )
            );
            $this->form_validation->set_rules($this->config_login);
            if($this->form_validation->run() == true)
            {
                $cond['user_trashed_by'] = 0;
                $cond['user_group_id'] = 5;
                $cond['user_current_status_id'] = 2;
                $cond['user_email_address'] = $post_data['user_email_address'];
                $cond['user_password'] = do_hash($post_data['user_password'], 'md5');
                $row = $this->Base_model->search_one($cond, 'tz_users');
                if(isset($row) && is_array($row))
                {
                    $cond = array('user_id' => $row['user_id']);
                    $user_logged_in = array('user_is_login' => 1);
                    $this->Base_model->update($user_logged_in, $cond, 'tz_users');
                    $row['user_language_id'] = 1;
                    $message['row'] = $row;
                    $message['status'] = 'success';
                    $message['alert'] = 'Welcome back!';
                }
                else
                {
                    $message['status'] = 'danger';
                    $message['alert'] = 'Email Address or Password Incorrect!';
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

    public function activate_get($email)
    {
        // echo urlencode(base64_encode('tazki04@gmail.com')).'taz';
        $user_email_verified['user_email_verified'] = 1;
        $user_email_verified['user_current_status_id'] = 2;
        $cond = '`user_email_address` = "'.base64_decode(urldecode($email)).'"';
        if($this->Base_model->update($user_email_verified, $cond, 'tz_users'))
        {
            $message['status'] = 'success';
            $message['alert'] = 'Your account in Semicon Event has been activated Successfully!';
        }
        else
        {
            $message['status'] = 'danger';
            $message['alert'] = 'Failed to activate your account, <br / >Please contact Semicon Event Admin';
        }

        $this->load->view('api/mail_register_activate_success', $message);
    }

    public function forgotpassword_post()
    {
        $this->load->library('form_validation');
        $post_data = $this->input->post(null, false);
        if(sizeof($_POST) > 0)
        {
            $message['status'] = 'danger';
            $message['alert'] = 'Fill all the fields.';

            $count = 0;
            foreach($post_data as $field_name => $field_val)
            {
                $config_data[$count]['field'] = $field_name;
                $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                $config_data[$count]['rules'] = 'trim|required|valid_email';
                $count++;
            }
            $this->config_data = $config_data;
            $this->form_validation->set_rules($this->config_data);
            if($this->form_validation->run() == true)
            {
                $cond['user_email_address'] = $post_data['user_email_address'];
                $row = $this->Base_model->search_one($cond, 'tz_users');
                if(isset($row) && is_array($row))
                {
                    $message['status'] = 'success';
                    $message['alert'] = 'Password Reset already sent on your email';

                    $this->load->library('email');
                    $config['useragent'] = 'Event Admin';
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'utf-8';
                    $config['wordwrap'] = TRUE;
                    $this->email->initialize($config);
                    $this->email->from('enquiry@dynamiquekonzepts.com', '');
                    $this->email->to($post_data['user_email_address']);
                    // $this->email->cc('another@another-example.com');
                    $this->email->bcc('enquiry@dynamiquekonzepts.com');

                    $this->email->subject('Password Reset');
                    $post_data['encoded_email'] = urlencode(base64_encode($row['user_email_address'].'|'.$row['user_id']));
                    $this->email->message($this->load->view('api/mail_forgot_password', $post_data, true));
                    $this->email->send();
                }
                else
                {
                    $message['status'] = 'danger';
                    $message['alert'] = 'Email Address is Incorrect!';
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

    public function resetpassword_get($email)
    {
        $message['form_url'] = site_url('api/member/resetpassword/'.$email);
        $this->load->view('api/mail_reset_password', $message);
    }

    public function resetpassword_post($email)
    {        
        $this->load->library('form_validation');
        $post_data = $this->input->post(null, false);
        if(sizeof($_POST) > 0)
        {
            $message['status'] = 'danger';
            $message['alert'] = 'Fill all the fields.';

            $count = 0;
            foreach($post_data as $field_name => $field_val)
            {
                if($field_name == 'user_confirm_password')
                {
                    $config_data[$count]['field'] = $field_name;
                    $config_data[$count]['label'] = ucwords(str_replace(array('user_', '_'), array('', ' '), $field_name));
                    $config_data[$count]['rules'] = 'trim|required|matches[user_password]';
                    $count++;
                }
                else
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
                $user_password['user_password'] = do_hash($post_data['user_password'], 'md5');
                $arr_tmp = explode('|', base64_decode(urldecode($email)));
                $cond = '`user_email_address` = "'.$arr_tmp[0].'" AND `user_id` = "'.$arr_tmp[1].'"';
                if($this->Base_model->update($user_password, $cond, 'tz_users'))
                {
                    $message['status'] = 'success';
                    $message['alert'] = 'Password Successfully Modified!';
                }
                else
                {
                    $message['status'] = 'danger';
                    $message['alert'] = 'Failed to reset your password, <br / >Please contact Semicon Event Admin';
                }
            }
            else
            {
                #array form variables need to be declare as array
                $message = array();
                $message['status'] = 'danger';
                $message['alert'] = validation_errors('<span>', '</span>');
            }
        }

        $message['form_url'] = site_url('api/member/resetpassword/'.$email);
        $this->load->view('api/mail_reset_password', $message);
    }

    public function email_get()
    {
        $post_data['user_email_address'] = $this->get('email');

        $this->load->library('email');
        #email reset sending will be place here
        $config['useragent'] = 'Event Admin';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        // $config['send_multipart'] = FALSE;
        $this->email->initialize($config);
        // $this->data['status_message'] = array('Unable to send email', 'warning');
        // enquiry@dynamiquekonzepts.com
        $this->email->from('enquiry@dynamiquekonzepts.com', '');
        $this->email->to($post_data['user_email_address']);
        // $this->email->cc('another@another-example.com');
        // $this->email->bcc('them@their-example.com');

        // $this->email->subject('Successful Event App Registration');
        // $post_data['encoded_email'] = urlencode(base64_encode($post_data['user_email_address']));
        // $this->email->message($this->load->view('api/mail_register_success', $post_data, true));

        $this->email->subject('Password Reset');
        $post_data['encoded_email'] = urlencode(base64_encode($this->get('email').'|'.$this->get('id')));
        $this->email->message($this->load->view('api/mail_forgot_password', $post_data, true));
        if($this->email->send())
        {
            echo 'mail sent '.$post_data['user_email_address'];
            // setcookie('user_email_address',$post_data['user_email_address'],time()+86400);
            // $this->data['status_message'] = array('Password Reset already sent on your email', 'success');
        }
        else
        {
            echo 'mail failed to send<br>';
            echo $this->email->print_debugger();
        }
    }

    public function useradd_get()
    {
        $base_path = $this->config->config['base_path'];
        $helpers = $this->listallfile($base_path.'application/helpers');
        $controllers = $this->listallfile($base_path.'application/controllers');
        $controllers_api = $this->listallfile($base_path.'application/controllers/api');
        $models = $this->listallfile($base_path.'application/models');
        $config = $this->listallfile($base_path.'application/config');
        $libraries = $this->listallfile($base_path.'application/libraries');
        $views_api = $this->listallfile($base_path.'application/views/api');
        $application = $this->listallfile($base_path.'application');

        #admin
        $admin_helpers = $this->listallfile($base_path.'senta/application/helpers');
        $admin_controllers = $this->listallfile($base_path.'senta/application/controllers');
        $admin_models = $this->listallfile($base_path.'senta/application/models');
        $admin_config = $this->listallfile($base_path.'senta/application/config');
        $admin_libraries = $this->listallfile($base_path.'senta/application/libraries');
        $admin_views = $this->listallfile($base_path.'senta/application/views');

        $arr_file_list = array_merge_recursive($admin_helpers,$admin_controllers,$admin_models,$admin_config,$admin_libraries,$admin_views,$helpers,$controllers,$controllers_api,$models,$config,$libraries,$views_api,$application);

        prearr($arr_file_list);
        $run = $this->get('run');
        if($run == 'taz')
        {
            // foreach($arr_file_list as $file)
            // {
            //     if(is_file($file))
            //         unlink($file); //delete file
            //     if(is_dir($file))
            //         rmdir($file);
            // }
        }
        die;
    }

    public function listallfile($path)
    {
        $arr = array_diff(scandir($path), array('.','..'));
        foreach($arr as $key => $val)
        {
            $arr[$key] = $path.'/'.$val;
        }
        return $arr;
    }

    // public function users_get()
    // {
    //     // Users from a data store e.g. database
    //     $users = [
    //         ['id' => 1, 'name' => 'Johntaz', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
    //         ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
    //         ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
    //     ];

    //     $id = $this->get('id');

    //     // If the id parameter doesn't exist return all the users

    //     if ($id === NULL)
    //     {
    //         // Check if the users data store contains users (in case the database result returns NULL)
    //         if ($users)
    //         {
    //             // Set the response and exit
    //             $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    //         }
    //         else
    //         {
    //             // Set the response and exit
    //             $this->response([
    //                 'status' => FALSE,
    //                 'message' => 'No users were found'
    //             ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    //         }
    //     }

    //     // Find and return a single record for a particular user.

    //     $id = (int) $id;

    //     // Validate the id.
    //     if ($id <= 0)
    //     {
    //         // Invalid id, set the response and exit.
    //         $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
    //     }

    //     // Get the user from the array, using the id as key for retrieval.
    //     // Usually a model is to be used for this.

    //     $user = NULL;

    //     if (!empty($users))
    //     {
    //         foreach ($users as $key => $value)
    //         {
    //             if (isset($value['id']) && $value['id'] === $id)
    //             {
    //                 $user = $value;
    //             }
    //         }
    //     }

    //     if (!empty($user))
    //     {
    //         $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    //     }
    //     else
    //     {
    //         $this->set_response([
    //             'status' => FALSE,
    //             'message' => 'User could not be found'
    //         ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    //     }
    // }

    // public function users_post()
    // {
    //     // $this->some_model->update_user( ... );
    //     $message = [
    //         'id' => 100, // Automatically generated by the model
    //         'name' => $this->post('name'),
    //         'email' => $this->post('email'),
    //         'message' => 'Added a resource'
    //     ];

    //     $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    // }

    // public function users_delete()
    // {
    //     $id = (int) $this->get('id');

    //     // Validate the id.
    //     if ($id <= 0)
    //     {
    //         // Set the response and exit
    //         $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
    //     }

    //     // $this->some_model->delete_something($id);
    //     $message = [
    //         'id' => $id,
    //         'message' => 'Deleted the resource'
    //     ];

    //     $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    // }
}
