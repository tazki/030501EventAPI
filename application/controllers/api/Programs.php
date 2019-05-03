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
class Programs extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Base_model');
    }

    public function index_get()
    {
        $user_id = $this->get('u');
        $event_id = $this->get('e');
        $promo_code_type = $this->get('type');

        $event_row = $this->Base_model->search_one('event_id="'.$event_id.'"', 'tz_event');

        $search_query = 'event_id="'.$event_id.'" AND program_status="2" AND `program_trashed_at` IS NULL';
        $rows_program = $this->Base_model->list_all('tz_program', '', '', 'program_sort', 'asc', $search_query, 'program_id,program_name,program_start_date,program_start_time,program_end_date,program_end_time');
        if(is_array($rows_program) && sizeof($rows_program) > 0)
        {
            $search_query = 'user_id="'.$user_id.'" AND event_id="'.$event_id.'" AND `trashed_at` IS NULL';
            $rows_user_to_program = $this->Base_model->list_all('tz_user_to_program', '', '', '', '', $search_query);
            $selected_program_id = array();
            if(is_array($rows_user_to_program))
            {
                foreach($rows_user_to_program as $key => $val)
                {
                    $selected_program_id[$val['program_id']] = $val['program_id'];
                }
            }

            $arr_program = array();
            $arr_program_id = array();
            foreach($rows_program as $key => $val)
            {
                $arr_program[$val['program_id']] = $val;
                $arr_program_id[$key] = $val['program_id'];
            }
            $list_program_id = implode(',', $arr_program_id);
            $rows_program_price = $this->Base_model->list_all('tz_program_price', '', '', '', '', 'program_id IN('.$list_program_id.')');
            foreach($rows_program_price as $key => $val)
            {
                $arr_program[$val['program_id']][$val['program_member_type']][$val['program_currency']] = $val;
            }

            $program_list = '';
            foreach ($arr_program as $key => $val)
            {
                #disable forum if already selected previously
                $forum_already_selected = '';
                $forum_already_selected_icon = '';
                if(in_array($val['program_id'], $selected_program_id))
                {
                    $forum_already_selected = 'disabled="disabled"';
                    $forum_already_selected_icon = 'style="background:#737373;"';
                }

                $complimentary = '';
                if($promo_code_type == 'complimentary')
                {
                    $complimentary = '<label class="label-radio item-content label-complimentary-holder">
                        <input type="radio" name="program_is_complimentary" value="'.$val['program_id'].'" />
                        <div class="item-media"><i class="icon icon-form-radio"></i></div>
                        <div class="item-inner">
                           <div class="item-title-row">
                              <div class="item-title">Use Complimentary Code Here</div>
                        </div>
                    </label>';
                }

                #check if event has early bird discount and deadline is not pass yet
                $sgd_program_price = $val['nonmember']['sgd']['program_price'];
                $usd_program_price = $val['nonmember']['usd']['program_price'];
                if(isset($event_row['event_early_bird_deadline']) && !empty($event_row['event_early_bird_deadline']))
                {
                  if(timestampnow(dateformat(datenow(),'Y-m-d')) <= timestampnow($event_row['event_early_bird_deadline']))
                  {
                    $sgd_program_price = $val['nonmember']['sgd']['program_early_bird_price'];
                    $usd_program_price = $val['nonmember']['usd']['program_early_bird_price'];
                  }
                }

                $program_price = '<span class="currency-sgd">SGD '.$sgd_program_price.'</span>
                    <span class="currency-sgd-zero" style="display:none;">SGD 0</span>
                    <span class="currency-usd" style="display:none;">USD '.$usd_program_price.'</span>
                    <span class="currency-usd-zero" style="display:none;">USD 0</span>';

                #Wednesday, May 9, 2018
                $program_date = dateformat($val['program_start_date'], 'l, M j, Y');
                if($val['program_end_date'] != '0000-00-00') {
                    $program_date .= '<br />'.dateformat($val['program_end_date'], 'l, M j, Y');
                }
                $program_list .= '<li class="program-'.$val['program_id'].'">
                    <label class="label-checkbox item-content">
                        <input type="checkbox" name="program_selection[]" value="'.$val['program_id'].'" class="event-program-selection" '.$forum_already_selected.' />
                        <div class="item-media"><i class="icon icon-form-checkbox" '.$forum_already_selected_icon.'></i></div>
                        <div class="item-inner">
                           <div class="item-title-row">
                              <div class="item-title">'.$val['program_name'].'</div>
                              <div class="item-after">'.$program_price.'</div>
                           </div>
                           <div class="item-subtitle">'.$val['program_start_time'].' - '.$val['program_end_time'].'</div>
                           <div class="item-text">'.$program_date.'</div>
                        </div>
                    </label>
                    '.$complimentary.'
                </li>';
            }
 
            $user_row = $this->Base_model->search_one('user_id="'.$user_id.'"', 'tz_users');            
            if(!empty($user_row['semi_membership_id']))
            {
                #check if membership code exist and not expired yet
                $semi_membership = $this->Base_model->search_one('semi_membership_id="'.$user_row['semi_membership_id'].'"', 'tz_semicon_member_company');                
                if(isset($semi_membership['membership_expiry_date']) && !empty($semi_membership['membership_expiry_date']))
                {
                    if(timestampnow($semi_membership['membership_expiry_date']) >= timestampnow(dateformat(datenow(),'Y-m-d')))
                    {
                        $program_list = '';                                
                        foreach ($arr_program as $key => $val)
                        {
                            #disable forum if already selected previously
                            $forum_already_selected = '';
                            $forum_already_selected_icon = '';
                            if(in_array($val['program_id'], $selected_program_id))
                            {
                                $forum_already_selected = 'disabled="disabled"';
                                $forum_already_selected_icon = 'style="background:#737373;"';
                            }

                            $complimentary = '';
                            if($promo_code_type == 'complimentary')
                            {
                                $complimentary = '<label class="label-radio item-content label-complimentary-holder">
                                    <input type="radio" name="program_is_complimentary" value="'.$val['program_id'].'" />
                                    <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                    <div class="item-inner">
                                       <div class="item-title-row">
                                          <div class="item-title">Use Complimentary Code Here</div>
                                    </div>
                                </label>';
                            }

                            #check if event has early bird discount and deadline is not pass yet
                            $sgd_program_price = $val['semimember']['sgd']['program_price'];
                            $usd_program_price = $val['semimember']['usd']['program_price'];
                            if(isset($event_row['event_early_bird_deadline']) && !empty($event_row['event_early_bird_deadline']))
                            {
                              if(timestampnow(dateformat(datenow(),'Y-m-d')) <= timestampnow($event_row['event_early_bird_deadline']))
                              {
                                $sgd_program_price = $val['semimember']['sgd']['program_early_bird_price'];
                                $usd_program_price = $val['semimember']['usd']['program_early_bird_price'];
                              }
                            }

                            $program_price = '<span class="currency-sgd">SGD '.$sgd_program_price.'</span>
                                <span class="currency-sgd-zero" style="display:none;">SGD 0</span>
                                <span class="currency-usd" style="display:none;">USD '.$usd_program_price.'</span>
                                <span class="currency-usd-zero" style="display:none;">USD 0</span>';

                            #Wednesday, May 9, 2018
                            $program_date = dateformat($val['program_start_date'], 'l, M j, Y');
                            if($val['program_end_date'] != '0000-00-00') {
                                $program_date .= '<br />'.dateformat($val['program_end_date'], 'l, M j, Y');
                            }
                            $program_list .= '<li class="program-'.$val['program_id'].'">
                                <label class="label-checkbox item-content">
                                    <input type="checkbox" name="program_selection[]" value="'.$val['program_id'].'" class="event-program-selection" '.$forum_already_selected.' />
                                    <div class="item-media"><i class="icon icon-form-checkbox" '.$forum_already_selected_icon.'></i></div>
                                    <div class="item-inner">
                                       <div class="item-title-row">
                                          <div class="item-title">'.$val['program_name'].'</div>
                                          <div class="item-after">'.$program_price.'</div>
                                       </div>
                                       <div class="item-subtitle">'.$val['program_start_time'].' - '.$val['program_end_time'].'</div>
                                       <div class="item-text">'.$program_date.'</div>
                                    </div>
                                </label>
                                '.$complimentary.'
                            </li>';
                        }          
                    }
                }
            }

            $program_tpl['program'] = $program_list;
        }

        $this->response($program_tpl, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function selected_get()
    {
        $user_id = $this->get('u');
        $event_id = $this->get('e');
        $promo_code_type = $this->get('type');

        $event_row = $this->Base_model->search_one('event_id="'.$event_id.'"', 'tz_event');

        $program_is_complimentary = $this->get('program_is_complimentary');
        $program_selected_value = $this->get('program_selected_value');
        $search_query = 'event_id="'.$event_id.'" AND program_status="2"';
        $search_query .= ' AND program_id IN('.$program_selected_value.')';
        $rows_program = $this->Base_model->list_all('tz_program', '', '', 'program_sort', 'asc', $search_query, 'program_id,program_name,program_start_date,program_start_time,program_end_date,program_end_time');
        if(is_array($rows_program) && sizeof($rows_program) > 0)
        {
            $arr_program = array();
            $arr_program_id = array();
            foreach($rows_program as $key => $val)
            {
                $arr_program[$val['program_id']] = $val;
                $arr_program_id[$key] = $val['program_id'];
            }
            $list_program_id = implode(',', $arr_program_id);
            $rows_program_price = $this->Base_model->list_all('tz_program_price', '', '', '', '', 'program_id IN('.$list_program_id.')');

            foreach($rows_program_price as $key => $val)
            {
                $arr_program[$val['program_id']][$val['program_member_type']][$val['program_currency']] = $val;
            }

            $program_list = '';
            foreach ($arr_program as $key => $val)
            {
                #check if event has early bird discount and deadline is not pass yet
                $sgd_program_price = $val['nonmember']['sgd']['program_price'];
                $usd_program_price = $val['nonmember']['usd']['program_price'];
                if(isset($event_row['event_early_bird_deadline']) && !empty($event_row['event_early_bird_deadline']))
                {
                  if(timestampnow(dateformat(datenow(),'Y-m-d')) <= timestampnow($event_row['event_early_bird_deadline']))
                  {
                    $sgd_program_price = $val['nonmember']['sgd']['program_early_bird_price'];
                    $usd_program_price = $val['nonmember']['usd']['program_early_bird_price'];
                  }
                }

                $program_price = '<span class="currency-sgd">SGD '.$sgd_program_price.'</span>
                    <span class="currency-sgd-zero" style="display:none;">SGD 0</span>
                    <span class="currency-usd" style="display:none;">USD '.$usd_program_price.'</span>
                    <span class="currency-usd-zero" style="display:none;">USD 0</span>';
                if($program_is_complimentary == $val['program_id'])
                {
                    $program_price = '<span class="currency-sgd">SGD 0</span>
                    <span class="currency-usd" style="display:none;">USD 0</span>';
                }

                #Wednesday, May 9, 2018
                $program_date = dateformat($val['program_start_date'], 'l, M j, Y');
                if($val['program_end_date'] != '0000-00-00') {
                    $program_date .= '<br />'.dateformat($val['program_end_date'], 'l, M j, Y');
                }
                $program_list .= '<li class="program-'.$val['program_id'].'">
                    <label class="label-checkbox item-content">                        
                        <div class="item-inner">
                           <div class="item-title-row">
                              <div class="item-title">'.$val['program_name'].'</div>
                              <div class="item-after">'.$program_price.'</div>
                           </div>
                           <div class="item-subtitle">'.$val['program_start_time'].' - '.$val['program_end_time'].'</div>
                           <div class="item-text">'.$program_date.'</div>
                        </div>
                    </label>
                </li>';
            }
 
            $user_row = $this->Base_model->search_one('user_id="'.$user_id.'"', 'tz_users');            
            if(!empty($user_row['semi_membership_id']))
            {
                #check if membership code exist and not expired yet
                $semi_membership = $this->Base_model->search_one('semi_membership_id="'.$user_row['semi_membership_id'].'"', 'tz_semicon_member_company');                
                if(isset($semi_membership['membership_expiry_date']) && !empty($semi_membership['membership_expiry_date']))
                {
                    if(timestampnow($semi_membership['membership_expiry_date']) >= timestampnow(dateformat(datenow(),'Y-m-d')))
                    {
                        $program_list = '';                                
                        foreach ($arr_program as $key => $val)
                        {
                            #check if event has early bird discount and deadline is not pass yet
                            $sgd_program_price = $val['semimember']['sgd']['program_price'];
                            $usd_program_price = $val['semimember']['usd']['program_price'];
                            if(isset($event_row['event_early_bird_deadline']) && !empty($event_row['event_early_bird_deadline']))
                            {
                              if(timestampnow(dateformat(datenow(),'Y-m-d')) <= timestampnow($event_row['event_early_bird_deadline']))
                              {
                                $sgd_program_price = $val['semimember']['sgd']['program_early_bird_price'];
                                $usd_program_price = $val['semimember']['usd']['program_early_bird_price'];
                              }
                            }

                            $program_price = '<span class="currency-sgd">SGD '.$sgd_program_price.'</span>
                                <span class="currency-sgd-zero" style="display:none;">SGD 0</span>
                                <span class="currency-usd" style="display:none;">USD '.$usd_program_price.'</span>
                                <span class="currency-usd-zero" style="display:none;">USD 0</span>';
                            if($program_is_complimentary == $val['program_id'])
                            {
                                $program_price = '<span class="currency-sgd">SGD 0</span>
                                <span class="currency-usd" style="display:none;">USD 0</span>';
                            }

                            #Wednesday, May 9, 2018
                            $program_date = dateformat($val['program_start_date'], 'l, M j, Y');
                            if($val['program_end_date'] != '0000-00-00') {
                                $program_date .= '<br />'.dateformat($val['program_end_date'], 'l, M j, Y');
                            }
                            $program_list .= '<li class="program-'.$val['program_id'].'">
                                <label class="label-checkbox item-content">
                                    <div class="item-inner">
                                       <div class="item-title-row">
                                          <div class="item-title">'.$val['program_name'].'</div>
                                          <div class="item-after">'.$program_price.'</div>
                                       </div>
                                       <div class="item-subtitle">'.$val['program_start_time'].' - '.$val['program_end_time'].'</div>
                                       <div class="item-text">'.$program_date.'</div>
                                    </div>
                                </label>
                            </li>';
                        }          
                    }
                }
            }

            $program_tpl['program'] = $program_list;
        }

        $this->response($program_tpl, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}