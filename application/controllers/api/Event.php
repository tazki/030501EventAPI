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
class Event extends REST_Controller {

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

      $this->data['event_detail_type']['agenda']['label'] = 'Agenda at a Glance';
      $this->data['event_detail_type']['agenda']['icon'] = 'view_agenda';
      $this->data['event_detail_type']['forums']['label'] = 'Forums';
      $this->data['event_detail_type']['forums']['icon'] = 'mic';
      $this->data['event_detail_type']['sponsors']['label'] = 'Sponsors';
      $this->data['event_detail_type']['sponsors']['icon'] = 'assignment_ind';
      $this->data['event_detail_type']['venue']['label'] = 'Venue';
      $this->data['event_detail_type']['venue']['icon'] = 'pin_drop';
      $this->data['event_detail_type']['exhibition_floorplan']['label'] = 'Exhibition Floorplan';
      $this->data['event_detail_type']['exhibition_floorplan']['icon'] = 'import_contacts';
      $this->data['event_detail_type']['exhibitors']['label'] = 'Exhibitors';
      $this->data['event_detail_type']['exhibitors']['icon'] = 'business_center';
      $this->data['event_detail_type']['hotel_travels']['label'] = 'Hotel & Travels';
      $this->data['event_detail_type']['hotel_travels']['icon'] = 'hotel';
  }

  public function index_get()
  {
    $show_promo_code_page = false;
    $attended_event_only = $this->get('attended_event_only');

    $user_id = $this->get('user_id');
    $user_id = (int) $user_id;

    $event_id = $this->get('event_id');
    $event_id = (int) $event_id;
      
    $search_string = $this->get('q');
    $search_query = '`event_status` = "2"';
    if($search_string !== NULL)
    {
        // $search_query .= ' AND (`event_name` LIKE "%'.$search_string.'%"
        //     OR `event_description` LIKE "%'.$search_string.'%"
        //     OR `event_content` LIKE "%'.$search_string.'%"
        //     OR `event_location` LIKE "%'.$search_string.'%"
        // )';
      $search_query .= ' AND date_format(`event_start_date`, "%M%Y") = "'.$search_string.'"';
    }

    if(!empty($event_id))
    {
      $search_query .= ' AND event_id = "'.$event_id.'"'; 
    }

    if(!empty($user_id) && $attended_event_only == 'yes')
    {
      $event_attended_query = '`user_id` = "'.$user_id.'"';
      $event_attended_query .= ' AND `trashed_at` IS NULL';
      $event_attended = $this->Base_model->list_all('tz_user_to_event', '', '', '', '', $event_attended_query, 'event_id');
      if(sizeof($event_attended) > 0)
      {
        $event_attended_id = '';
        foreach($event_attended as $key => $val)
        {
          $comma = (!empty($event_attended_id)) ? ',' : '';
          $event_attended_id .= $comma.$val['event_id'];
        }
        $search_query .= ' AND `event_id` IN('.$event_attended_id.')';
      }
      // prearr($event_attended);
      // echo $search_query;
    }

    $rows = $this->Base_model->list_all('tz_event', '', '', 'event_start_date', 'asc', $search_query);
    // Check if the users data store contains users (in case the database result returns NULL)
    if(is_array($rows) && sizeof($rows) > 0)
    {
      $arr_event_id = array();
      foreach($rows as $key => $val)
      {
        $arr_event_id[$key] = $val['event_id'];

        $start_month = dateformat($val['event_start_date'], 'F');
        $end_month = dateformat($val['event_end_date'], 'F');
        
        $start_day = dateformat($val['event_start_date'], 'd');
        $end_day = dateformat($val['event_end_date'], 'd');
        
        $start_year = dateformat($val['event_start_date'], 'Y');
        $end_year = dateformat($val['event_end_date'], 'Y');

        $start_date = dateformat($val['event_start_date'], 'F d, Y');
        $end_date = dateformat($val['event_end_date'], 'F d, Y');

        $rows[$key]['event_id'] = $val['event_id'];
        $rows[$key]['event_date'] = '';
        if(!empty($start_date) && !empty($end_date))
        {
          if($start_day == $end_day && $start_month == $end_month && $start_year == $end_year)
          {
              $rows[$key]['event_date'] = $start_month.' '.$start_day.', '.$start_year;
          }
          elseif($start_month == $end_month && $start_year == $end_year)
          {
              $rows[$key]['event_date'] = $start_month.' '.$start_day.'-'.$end_day.', '.$start_year;
          }
          else
          {
              $rows[$key]['event_date'] = $start_date.' - '.$end_date;
          }
        }
        elseif(!empty($start_date) && empty($end_date))
        {
          $rows[$key]['event_date'] = $start_date;
        }
        elseif(empty($start_date) && !empty($end_date))
        {
          $rows[$key]['event_date'] = $end_date;
        }
      }

      if(sizeof($arr_event_id) > 0)
      {
        #fetch event detail list
        $list_event_id = implode(',', $arr_event_id);
        $row_detail = $this->Base_model->list_all('tz_event_detail', '', '', 'event_detail_sort_order', 'asc', 'event_id IN('.$list_event_id.') AND `event_detail_deleted_at` IS NULL');
        if(is_array($row_detail))
        {
          $prep_detail = array();
          foreach($row_detail as $key => $val)
          {
            $prep_detail[$val['event_id']][$val['event_detail_type']][$val['event_detail_id']]['event_detail_title'] = $val['event_detail_title'];
          }
        }

        #fetch event program list
        $list_event_id = implode(',', $arr_event_id);
        $row_program = $this->Base_model->list_all('tz_program', '', '', 'program_sort', 'asc', 'event_id IN('.$list_event_id.') AND program_status="2" AND `program_trashed_at` IS NULL');
        if(is_array($row_program))
        {
          $prep_program = array();
          foreach($row_program as $key => $val)
          {
            // $detail = dateformat($val['program_start_date'], 'l, j M');
            // $detail .= ', '.dateformat($val['program_start_time'], 'h:ia').' to '.dateformat($val['program_end_time'], 'h:ia');
            // $detail .= ' - '.$val['program_name'];
            // $prep_program[$val['event_id']][$val['program_id']]['detail'] = $detail;
            $program_date = dateformat($val['program_start_date'], 'l, M j, Y');
            if($val['program_end_date'] != '0000-00-00') {
              $program_date .= '<br />'.dateformat($val['program_end_date'], 'l, M j, Y');
            }            

            $prep_program[$val['event_id']][$val['program_id']]['program_name'] = $val['program_name'];            
            $prep_program[$val['event_id']][$val['program_id']]['program_start_time'] = $val['program_start_time'];
            $prep_program[$val['event_id']][$val['program_id']]['program_end_time'] = $val['program_end_time'];
            $prep_program[$val['event_id']][$val['program_id']]['program_date'] = $program_date;
            $prep_program[$val['event_id']][$val['program_id']]['url'] = $val['program_url'];
          }
        }

        #Event Attend Button
        if(!empty($user_id))
        {
          #fetch list of promo code to determine if we will redirect to that page.
          $promo_code_query = '`promo_code_status` = "2"';
          $rows_promo_code = $this->Base_model->list_all('tz_promo_code', '', '', '', '', $promo_code_query);
          if(is_array($rows_promo_code) && sizeof($rows_promo_code) > 0)
          {
            $show_promo_code_page = true;
          }

          $event_attended_query = 'event_id IN('.$list_event_id.') AND user_id="'.$user_id.'"';
          $event_attended_query .= ' AND `trashed_at` IS NULL';
          $rows_attended_event = $this->Base_model->list_all('tz_user_to_event', '', '', '', '', $event_attended_query);          
          if(is_array($rows_attended_event))
          {
            foreach($rows_attended_event as $key => $val)
            {
              $prep_attended_event[$val['event_id']] = $val['payment_status'];
            }
          }
        }
        #EOS Event Attend Button

        foreach($rows as $key => $val)
        {
          #SOS event detail list
          $event_detail_type_tpl = '';
          foreach($this->data['event_detail_type'] as $event_detail_type => $sval)
          {
            $event_detail_tpl = '';
            if($event_detail_type == 'forums')
            {
              if(isset($prep_program[$val['event_id']]) && is_array($prep_program[$val['event_id']]))
              {
                foreach($prep_program[$val['event_id']] as $tkey => $tval)
                {

                  $event_detail_tpl .= '<li>
                    <div class="item-content">
                      <div class="item-inner">
                         <div class="item-title-row">
                            <div class="item-title">'.$tval['program_name'].'</div>
                            <div class="item-after"></div>
                         </div>
                         <div class="item-subtitle">'.$tval['program_start_time'].' - '.$tval['program_end_time'].'</div>
                         <div class="item-text">'.$tval['program_date'].'</div>
                      </div>
                    </div>
                  </li>'; 
                  if(!empty($tval['url']))
                  {
                    $event_detail_tpl .= '<li>
                      <div class="item-content">
                        <div class="item-inner">
                          <div class="item-title">
                            <a href="'.$tval['url'].'" onClick="javascript:return openExternal(this)">for more information..</a>
                          </div>
                        </div>
                      </div>
                    </li>'; 
                  }
                }
              }
            }
            else
            {
              if(isset($prep_detail[$val['event_id']]))
              {
                if(isset($prep_detail[$val['event_id']][$event_detail_type])
                  && is_array($prep_detail[$val['event_id']][$event_detail_type]))
                {
                  $arr_event_detail = $prep_detail[$val['event_id']][$event_detail_type];
                  foreach($arr_event_detail as $tkey => $tval)
                  {
                    foreach($tval as $fkey => $fval)
                    {
                      if(stristr($fval, 'http'))
                      {
                        $fval = '<a href="'.$fval.'" onClick="javascript:return openExternal(this)">for more information..</a>';
                      }
                      $event_detail_tpl .= '<li><div class="item-content"><div class="item-inner"><div class="item-title">'.$fval.'</div></div></div></li>'; 
                    }
                  }
                }
              }
            }

            if(!empty($event_detail_tpl))
            {
              $icon = $this->data['event_detail_type'][$event_detail_type]['icon'];
              $label = $this->data['event_detail_type'][$event_detail_type]['label'];
              $event_detail_type_tpl .= '<li id="'.$event_detail_type.'-holder" class="accordion-item">
                 <a href="#" class="item-link item-content">
                    <div class="item-inner">
                       <div class="item-title"><i class="icon material-icons">'.$icon.'</i> '.$label.'</div>
                    </div>
                 </a>
                 <div class="accordion-item-content">
                    <div class="list-block media-list">
                      <ul id="'.$event_detail_type.'">'.$event_detail_tpl.'</ul>
                    </div>
                 </div>
              </li>';
            }
          }

          if($val['event_appointment_status'] == 2)
          {
              $event_detail_type_tpl .= '<li id="meeting_request-holder" class="accordion-item">
               <a href="#" class="item-link item-content">
                  <div class="item-inner">
                     <div class="item-title"><i class="icon material-icons">event</i> Meeting Request</div>
                  </div>
               </a>
               <div class="accordion-item-content">
                  <div class="list-block">
                    <ul id="meeting_request">
                      <li>
                        <div class="item-content">
                          <div class="item-inner">
                            <div class="item-title">
                              <!--(This feature will only be available during the show)-->
                              <a href="appointment.html?id='.$val['event_id'].'">
                                Request Appointment
                              </a>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
               </div>
            </li>';
          }

          $rows[$key]['event_detail'] = '<div class="list-block accordion-list">
            <ul>'.$event_detail_type_tpl.'</ul>
          </div>';
          #EOS event detail list

          $event_is_done = '';
          $event_is_ongoing = '';
          if(isset($val['event_start_date']) && !empty($val['event_start_date']) 
            && isset($val['event_end_date']) && $val['event_end_date'] == '0000-00-00 00:00:00')
          {
            if(timestampnow(dateformat(datenow(),'Y-m-d')) > timestampnow(dateformat($val['event_start_date'],'Y-m-d')))
            {
              $event_is_done = 1;
              $rows[$key]['event_is_done'] = 1;
            }

            #event will be mark as on going if current date and start date are equal
            if(timestampnow(dateformat(datenow(),'Y-m-d')) == timestampnow(dateformat($val['event_start_date'],'Y-m-d')))
            {
              $event_is_ongoing = 1;
              $rows[$key]['event_is_ongoing'] = 1;              
            }
          }
          else if(isset($val['event_end_date']) && !empty($val['event_end_date'])
            && $val['event_end_date'] != '0000-00-00 00:00:00')
          {
            if(timestampnow(dateformat(datenow(),'Y-m-d')) > timestampnow(dateformat($val['event_end_date'],'Y-m-d')))
            {
              $event_is_done = 1;
              $rows[$key]['event_is_done'] = 1;
            }

            if(timestampnow(dateformat(datenow(),'Y-m-d')) >= timestampnow(dateformat($val['event_start_date'],'Y-m-d'))
              && timestampnow(dateformat(datenow(),'Y-m-d')) <= timestampnow(dateformat($val['event_end_date'],'Y-m-d')))
            {
              $event_is_ongoing = 1;
              $rows[$key]['event_is_ongoing'] = 1;
            }
          }  

          if($val['event_registration_status'] == 2 && empty($event_is_done))
          {
            #this will manage what page to display for registration
            $notification_status = 'Register now by clicking below';
            $notification_button_label = 'Register';
            $notification_button_url = 'event-registration-programselection.html?id='.$val['event_id'];            
            if($show_promo_code_page == true && empty($event_is_ongoing)
              && !isset($prep_attended_event[$val['event_id']]))
            {
              $notification_button_label = 'Register';
              $notification_button_url = 'event-registration-promocode.html?id='.$val['event_id'];
            }
            else if(isset($prep_attended_event[$val['event_id']]) && !empty($prep_attended_event[$val['event_id']]))
            {
              $payment_status = $prep_attended_event[$val['event_id']];
              switch($payment_status)
              {
                case 'pending':
                  $notification_status = 'Waiting for Payment Confirmation';
                  // $notification_button = '';#'<a class="button eventEdit">Edit my registration</a>';
                  $notification_button_label = 'View my registration';
                  $notification_button_url = 'event-edit.html?id='.$val['event_id'];
                break;                
                case 'completed':
                  $notification_status = 'Attending this event.';
                  $notification_button_label = 'View my registration';
                  $notification_button_url = 'event-edit.html?id='.$val['event_id'];
                break;
              }
            }

            $rows[$key]['notification_section'] = '<div class="col-100">
              <p class="attending"><span>'.$notification_status.'</span></p>
            </div>
            <div class="col-100">
              <p class="registration">
                <span>
                  <a href="'.$notification_button_url.'" class="button eventContinue">'
                    .$notification_button_label.
                  '</a>
                </span>
              </p>
            </div>';
          }
        }
      }

      $arr_events = array();
      $arr_ongoing_event = array();
      $arr_done_event = array();
      #transfer done event at the end of list
      if(is_array($rows))
      {

        foreach($rows as $key => $val)
        {
          if(isset($val['event_is_done']) && $val['event_is_done'] == 1)
          {
            $arr_done_event[$key] = $val;
          }
          else
          {
            $arr_ongoing_event[$key] = $val;
          }
        }

        $arr_events = array_merge($arr_ongoing_event, $arr_done_event);
      }

      if(!empty($user_id) && $attended_event_only == 'yes'
        && !isset($event_attended))
      {
        $arr_events = array();
      }

      // Set the response and exit
      $this->response($arr_events, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

  public function detail_get()
  {
      $user_id = $this->get('user_id');
      $event_id = $this->get('event_id');
      $event_attended_query = 'event_id="'.$event_id.'" AND user_id="'.$user_id.'"';
      $event_attended_query .= ' AND `trashed_at` IS NULL';
      $rows_attended_event = $this->Base_model->list_all('tz_user_to_event', '', '', '', '', $event_attended_query);
      $row_event = $this->Base_model->search_one('event_id="'.$event_id.'"', 'tz_event');
      $li_tpl = '';
      if(is_array($rows_attended_event))
      {
        $arr_program_id = array();
        $users_to_event_id = array();
        $event_attended = array();
        foreach($rows_attended_event as $key => $val)
        {
          $event_attended[$val['users_to_event']]['users_to_event'] = $val['users_to_event'];
          $event_attended[$val['users_to_event']]['subtotal_amount'] = $val['subtotal_amount'];
          $event_attended[$val['users_to_event']]['total_amount'] = $val['total_amount'];
          $event_attended[$val['users_to_event']]['promo_code_discount'] = $val['promo_code_discount'];
          $event_attended[$val['users_to_event']]['currency'] = $val['currency'];
          $event_attended[$val['users_to_event']]['payment_type'] = $val['payment_type'];
          $event_attended[$val['users_to_event']]['payment_status'] = $val['payment_status'];
          $event_attended[$val['users_to_event']]['created_at'] = $val['created_at'];
          $event_attended[$val['users_to_event']]['promo_code_id'] = $val['promo_code_id'];      

          $users_to_event_id[$key] = $val['users_to_event'];
        }
        // prearr($users_to_event_id);
        // prearr($event_attended);            

        $search_query = 'users_to_event IN('.implode(',', $users_to_event_id).') AND `trashed_at` IS NULL';
        $rows_attended_program = $this->Base_model->list_all('tz_user_to_program', '', '', '', '', $search_query);
        // prearr($rows_attended_program);
        if(is_array($rows_attended_program))
        {
          foreach($rows_attended_program as $key => $val)
          {
            $arr_program_id[$val['program_id']] = $val['program_id']; 
            $arr_program_price[$val['users_to_event']][$val['program_id']]['program_price'] = $val['program_price'];
            $arr_program_price[$val['users_to_event']][$val['program_id']]['users_to_event'] = $val['users_to_event'];
          }

          $search_query = 'program_id IN('.implode(',', $arr_program_id).')';
          $rows_program = $this->Base_model->list_all('tz_program', '', '', '', '', $search_query);
          if(is_array($rows_program))
          {
            foreach($rows_program as $key => $val)
            {
              foreach($arr_program_price as $users_to_event => $sval)
              { 
                if(isset($sval[$val['program_id']]))
                {
                  $event_attended[$users_to_event]['program_selected'][$key] = $val;
                  $event_attended[$users_to_event]['program_selected'][$key]['program_price'] = $sval[$val['program_id']]['program_price'];
                }
              }
            }
          }
          // prearr($rows_program);
          // prearr($event_attended);

          if(is_array($event_attended))
          {
            $count = 1;
            foreach($event_attended as $key => $val)
            {
              $program_li_tpl = '';
              if(isset($val['program_selected']) && is_array($val['program_selected']))
              {
                foreach($val['program_selected'] as $skey => $sval)
                {
                  #Wednesday, May 9, 2018
                  $program_date = dateformat($sval['program_date'], 'l, M j, Y');
                  $program_li_tpl .= '<li>
                    <label class="label-radio item-content">
                      <div class="item-inner">
                        <div class="item-title-row">
                          <div class="item-title">'.$sval['program_name'].'</div>
                          <div class="item-after">'.$val['currency'].' '.number_format($sval['program_price'],2).'</div>
                        </div>
                        <div class="item-subtitle">'.$sval['program_start_time'].' - '.$sval['program_end_time'].'</div>
                        <div class="item-text">'.$program_date.'</div>
                      </div>
                    </label>
                  </li>';
                }
              }

              $subtotal_tpl = '';
              $promo_code = '';
              if(!empty($val['promo_code_id']))
              {
                $promo_code_row = $this->Base_model->search_one('promo_code_id="'.$val['promo_code_id'].'"', 'tz_promo_code');
                $discount_info = '';
                if($promo_code_row['promo_code_use_times'] == 1)
                {
                  $discount_info = ' (COMPLIMENTARY)';
                }
                else
                {
                  // $discount_info = ' ('.$promo_code_row['promo_code_discount'].'%)';
                  $subtotal_tpl = '<p>Subtotal Amount: <span style="float:right;">'.number_format($val['subtotal_amount'], 2).'  '.$val['currency'].'</span></p>
                    ';
                  $promo_code_discount = $val['subtotal_amount'] * ($val['promo_code_discount']/100);
                  $subtotal_tpl .= '<p>Promo Code Discount ('.$val['promo_code_discount'].'%'.'): <span style="float:right;">('.$promo_code_discount.'  '.$val['currency'].')</span></p>';
                }

                $promo_code = '<p>Promo Code: <span style="float:right;">'.strtoupper($promo_code_row['promo_code']).$discount_info.'</span></p>';  
              }

              $payment_type = '';
              if(!empty($val['payment_type']))
              {
                $payment_type = '<p>Payment Type: <span style="float:right;">'.strtoupper($val['payment_type']).'</span></p>';  
              }
              
              //'.$count.'. 
              $li_tpl .= '<li class="accordion-item accordion-item-expanded">
                <a href="#" class="item-link item-content">
                  <div class="item-inner">
                     <div class="item-title review-title">FORUM</div>
                  </div>
                </a>
                <div class="accordion-item-content">
                  <div class="content-block">
                     <div class="list-block media-list">
                        <ul>'.$program_li_tpl.'</ul>
                     </div>
                  </div>
                </div>
              </li>
              <li class="accordion-item accordion-item-expanded">
                 <a href="#" class="item-link item-content">
                    <div class="item-inner">
                       <div class="item-title review-title">PAYMENT</div>
                    </div>
                 </a>
                 <div class="accordion-item-content">
                    <div class="content-block">
                      '.$promo_code.$payment_type.'
                      <p>Payment Status: <span style="float:right;">'.strtoupper($val['payment_status']).'</span></p>
                      '.$subtotal_tpl.'
                      <p style="font-weight:bold;">
                        Total Amount: <span style="float:right;">'.number_format($val['total_amount'], 2).' '.$val['currency'].'</span>
                      </p>
                    </div>
                 </div>
              </li>';
              $count++;
            }
          }
        }
      }

      echo '<li class="accordion-item accordion-item-expanded">
        <a href="#" class="item-link item-content">
          <div class="item-inner">
             <div class="item-title review-title">EXHIBITION</div>
          </div>
        </a>
        <div class="accordion-item-content">
          <div class="content-block">
             <div class="list-block media-list">
                <ul id="edit-exhibition">
                  <li>
                    <label class="label-checkbox item-content">
                      <div class="item-inner">
                        <div class="item-title-row">
                          <div class="item-title exhibition-title">'.$row_event['event_name'].'</div>
                        </div>
                        <div class="item-text exhibition-text">'.$row_event['event_description'].'</div>
                      </div>
                    </label>
                  </li>
                </ul>
             </div>
          </div>
        </div>
      </li>'
      .$li_tpl.
      '<li class="accordion-item accordion-item-expanded">
        <div class="accordion-item-content">
          <div class="content-block">
            <p>
              <b>Note:</b> If you wish to add/remove Forums, please send your request to <a>enquiry@dynamiquekonzepts.com</a>
              <br /><br />
              We will reply within one working day.
              <br /><br />
              SEMI SEA Team
            </p>
          </div>
        </div>
      </li>';
  }

  public function promocode_post()
  {
    $post_data = $this->input->post(null, false);
    if(sizeof($_POST) > 0)
    {
      #promo code discount
      $promo_code_tpl = '';
      $promo_code_discount = 0;
      $promo_code_discount_tpl = '';
      if(isset($post_data['promo_code']) && !empty($post_data['promo_code']))
      {
        $post_data['promo_code'] = strtoupper($post_data['promo_code']);

        $search_query = 'promo_code="'.$post_data['promo_code'].'"';
        $search_query .= ' AND promo_code_status="2"';
        $promo_row = $this->Base_model->search_one($search_query, 'tz_promo_code');
        #check if promo code exist and not expired yet
        if(isset($promo_row['promo_code_expiry_date']) && !empty($promo_row['promo_code_expiry_date']))
        {
          if(timestampnow($promo_row['promo_code_expiry_date']) >= timestampnow(dateformat(datenow(),'Y-m-d')))
          {
            $row['status'] = 'success';
            $row['promo_code'] = $promo_row['promo_code'];
            $row['promo_code_type'] = $promo_row['promo_code_type'];
            #No need for msg because it just go to next page : $row['msg'] = '';
          }
          else
          {
            $row['status'] = 'danger';
            $row['msg'] = 'Promo Code: Expired <br />Remove Promo Code to Continue';
          }
        }
        else if (!empty($promo_row['user_id']) && !empty($promo_row['reward_id']))
        {
          $row['status'] = 'success';
          $row['promo_code'] = $promo_row['promo_code'];
          $row['promo_code_type'] = $promo_row['promo_code_type'];
          #No need for msg because it just go to next page : $row['msg'] = '';
        }
        else
        {
          $row['status'] = 'danger';
          $row['msg'] = 'Promo Code: Invalid <br />Remove Promo Code to Continue';
        }
      }
    }

    $user_id = $this->get('u');
    $user_row = $this->Base_model->search_one('user_id="'.$user_id.'"', 'tz_users');
    $row['company_country'] = $user_row['user_company_country_id'];

    // Set the response and exit
    $this->response($row, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
  }

  public function attend_post()
  {
    $user_id = $this->get('u');
    $event_id = $this->get('e');
    $post_data = $this->input->post(null, false);
    if(sizeof($_POST) > 0)
    {
      $user_row = $this->Base_model->search_one('user_id="'.$user_id.'"', 'tz_users');
      $event_row = $this->Base_model->search_one('event_id="'.$event_id.'"', 'tz_event');

      $payment_tpl = '';
      #program selected for email use
      $program_registerd = '';
      #promo code discount
      $promo_code_tpl = '';
      $promo_code_discount = 0;
      $promo_code_discount_tpl = '';
      #semi member discount
      $membership_discount = 0;
      $membership_discount_tpl = '';
      $expired_membership_tpl = '';
      #early bird discount
      $early_bird_discount = 0;
      $early_bird_discount_tpl = '';  
      $program_points = 0;
      $subtotal = 0;
      $currency = (isset($post_data['currency'])) ? strtoupper($post_data['currency']) : '';
      if(isset($post_data['program_selection']) && !empty($post_data['program_selection']))
      {
        $search_query = 'program_id IN('.$post_data['program_selection'].')';
        $program_list = $this->Base_model->list_all_by_field('tz_program', 'program_id','', '', '', '', $search_query);
        $program_registered = '';
        if(is_array($program_list))
        {
          foreach($program_list as $key => $val)
          {
            #Wednesday, May 9, 2018
            $program_time = '- '.$val['program_start_time'].' - '.$val['program_end_time'].'hrs';
            $program_date = dateformat($val['program_date'], 'F d');
            $program_registered .= '<li>'.$val['program_name'].' - '.$program_date.' '.$program_time.'</li>';
          }
        }
        $post_data['program_registered'] = $program_registered;
        
        $search_query .= ' AND program_currency = "'.$post_data['currency'].'"';
        $program_rows = $this->Base_model->list_all('tz_program_price', '', '', '', '', $search_query, 'program_id,program_member_type,program_price,program_early_bird_price');
        if(is_array($program_rows))
        {
          $clean_data_program = array();
          foreach($program_rows as $key => $val)
          {
            if($val['program_member_type'] == 'nonmember')
            {
              if(isset($post_data['program_is_complimentary']) && $post_data['program_is_complimentary'] == $val['program_id'])
              {
                #do not add amount on subtotal
                $clean_data_program[$val['program_id']] = 0;                
              }
              else
              {
                $program_price = $val['program_price'];
                #check if event has early bird discount and deadline is not pass yet
                if(isset($event_row['event_early_bird_deadline']) && !empty($event_row['event_early_bird_deadline']))
                {
                  if(timestampnow(dateformat(datenow(),'Y-m-d')) <= timestampnow($event_row['event_early_bird_deadline']))
                  {
                    $program_price = $val['program_early_bird_price'];
                  }
                }

                $clean_data_program[$val['program_id']] = $program_price;
                $subtotal += $program_price;

                if(isset($program_list[$val['program_id']]['program_points']))
                {
                  $program_points += $program_list[$val['program_id']]['program_points'];
                }
              }
            }
          }
        }

        if(!empty($user_row['semi_membership_id']))
        {
          #check if membership code exist and not expired yet
          $semi_membership = $this->Base_model->search_one('semi_membership_id="'.$user_row['semi_membership_id'].'"', 'tz_semicon_member_company');
          if(isset($semi_membership['membership_expiry_date']) && !empty($semi_membership['membership_expiry_date']))
          {
            if(timestampnow($semi_membership['membership_expiry_date']) >= timestampnow(dateformat(datenow(),'Y-m-d')))
            {
              if(is_array($program_rows))
              {
                $subtotal = 0;
                $program_points = 0;
                $clean_data_program = array();
                foreach($program_rows as $key => $val)
                {
                  if($val['program_member_type'] == 'semimember')
                  {
                    if(isset($post_data['program_is_complimentary']) && $post_data['program_is_complimentary'] == $val['program_id'])
                    {
                      #do not add amount on subtotal
                      $clean_data_program[$val['program_id']] = 0;
                    }
                    else
                    {
                      $program_price = $val['program_price'];
                      #check if event has early bird discount and deadline is not pass yet
                      if(isset($event_row['event_early_bird_deadline']) && !empty($event_row['event_early_bird_deadline']))
                      {
                        if(timestampnow(dateformat(datenow(),'Y-m-d')) <= timestampnow($event_row['event_early_bird_deadline']))
                        {
                          $program_price = $val['program_early_bird_price'];
                        }
                      }

                      $clean_data_program[$val['program_id']] = $program_price;
                      $subtotal += $program_price;
                      
                      if(isset($program_list[$val['program_id']]['program_points']))
                      {
                        $program_points += $program_list[$val['program_id']]['program_points'];
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    if(isset($post_data['promo_code']) && !empty($post_data['promo_code']))
    {
      $post_data['promo_code'] = strtoupper($post_data['promo_code']);
      $promo_code_tpl = '<p>Promo Code: '.$post_data['promo_code'].'</p>';
      $search_query = 'promo_code="'.$post_data['promo_code'].'"';
      $search_query .= ' AND promo_code_status="2"';
      $promo_row = $this->Base_model->search_one($search_query, 'tz_promo_code');
      #check if promo code exist and not expired yet
      if(isset($promo_row['promo_code_expiry_date']) && !empty($promo_row['promo_code_expiry_date']))
      {
        if(timestampnow($promo_row['promo_code_expiry_date']) >= timestampnow(dateformat(datenow(),'Y-m-d')))
        {
          $promo_code_discount = $subtotal * ($promo_row['promo_code_discount']/100);
          $promo_code_tpl = '<p>Promo Code: '.$post_data['promo_code'].'</p>';
          $promo_code_discount_tpl = '<p>Promo Code Discount ('.$promo_row['promo_code_discount'].'%'.'): <span style="float:right;">('.$promo_code_discount.'  '.$currency.')</span></p>';
        }
        else
        {
          $promo_code_tpl = '<p>Promo Code: Expired</p>';
        }
      }
      else if (!empty($promo_row['user_id']) && !empty($promo_row['reward_id']))
      {
        $promo_code_discount = $subtotal * ($promo_row['promo_code_discount']/100);
        $promo_code_tpl = '<p>Promo Code: '.$post_data['promo_code'].'</p>';
        $promo_code_discount_tpl = '<p>Promo Code Discount ('.$promo_row['promo_code_discount'].'%'.'): <span style="float:right;">('.$promo_code_discount.'  '.$currency.')</span></p>';
      }
      else
      {
        $promo_code_tpl = '<p>Promo Code: Invalid</p>';
      }
    }

    if(isset($post_data['payment']) && !empty($post_data['payment']))
    {
      $payment_tpl = '<p>Payment Type: '.ucwords($post_data['payment']).'</p>';
    }


    $final_result = '';
    // $final_result .= '<p>Payment Type: '.$post_data['payment'].'</p>';
    $final_result .= $payment_tpl;
    $final_result .= $promo_code_tpl;
    $final_result .= $expired_membership_tpl;
    $final_result .= '<p>Subtotal Amount: <span style="float:right;">'.$subtotal.' '.$currency.'</span></p>';
    $final_result .= $membership_discount_tpl;
    $final_result .= $early_bird_discount_tpl;
    $final_result .= $promo_code_discount_tpl;
    #total computation
    $total_amount = $subtotal - ($membership_discount + $early_bird_discount + $promo_code_discount);
    $final_result .= '<input type="hidden" name="total_amount" value="'.$total_amount.'" />';
    $final_result .= '<p style="font-weight:bold;">Total Amount: <span style="float:right;">'.$total_amount.' '.$currency.'</span></p>';

    #save to db
    if(!empty($this->get('s')))
    {
      // saveSurveyAnswer($post_data, $user_id, $event_id);

      #this will ensure that User will be registered only once.
      $user_to_event_query = 'user_id="'.$user_id.'"';
      $user_to_event_query .= ' AND event_id="'.$event_id.'"';
      $user_to_event_query .= ' AND `trashed_at` IS NULL';
      $is_already_registered = $this->Base_model->search_one($user_to_event_query, 'tz_user_to_event');
      if(!is_array($is_already_registered))
      {
        #tz_user_to_event
        $tz_user_to_event = $user_row;
        $tz_user_to_event['event_id'] = $event_id;
        $tz_user_to_event['user_id'] = $user_id;
        $tz_user_to_event['subtotal_amount'] = $subtotal;
        $tz_user_to_event['points_count_used'] = 0;
        $tz_user_to_event['semi_member_discount'] = (!empty($membership_discount)) ? 25 : 0;
        $tz_user_to_event['early_bird_discount'] = (!empty($early_bird_discount)) ? $event_row['event_early_bird_discount'] : 0;
        $tz_user_to_event['total_amount'] = $total_amount;
        $tz_user_to_event['currency'] = $currency;
        $tz_user_to_event['payment_type'] = ($subtotal > 0) ? $post_data['payment'] : '';
        $tz_user_to_event['payment_status'] = ($total_amount == 0) ? 'completed' : 'pending';
        $tz_user_to_event['created_at'] = datenow();
        $tz_user_to_event['modified_at'] = datenow();
        if(isset($promo_row['promo_code_id']) && !empty($promo_row['promo_code_id']))
        {        
          $tz_user_to_event['promo_code_id'] = $promo_row['promo_code_id'];
          $tz_user_to_event['promo_code_discount'] = $promo_row['promo_code_discount'];

          #change promo code status to 4 = Used if it is a Complimentary Code
          if($promo_row['promo_code_use_times'] == 1)
          {
            $promo_data = array();
            $promo_data['promo_code_status'] = 4;
            $cond = '`promo_code_id` = "'.$promo_row['promo_code_id'].'"';
            $this->Base_model->update($promo_data, $cond, 'tz_promo_code');
          }
        }
        $users_to_event = $this->Base_model->insert($tz_user_to_event, 'tz_user_to_event');

        #tz_user_to_program
        $tz_user_to_program = array();      
        $tz_user_to_program['created_at'] = datenow();
        $tz_user_to_program['modified_at'] = datenow();
        if(isset($clean_data_program) && is_array($clean_data_program))
        {
          foreach($clean_data_program as $program_id => $program_price)
          {
            $selected_program_id[$program_id] = $program_id;

            $tz_user_to_program['event_id'] = $event_id;
            $tz_user_to_program['user_id'] = $user_id;
            $tz_user_to_program['program_id'] = $program_id;
            $tz_user_to_program['program_price'] = $program_price;
            $tz_user_to_program['users_to_event'] = $users_to_event;
            // $tz_user_to_program['program_name'] = $val['program_name'];
            $this->Base_model->insert($tz_user_to_program, 'tz_user_to_program');
          }
        }

        #tz_user_to_points
        $tz_user_to_points['event_id'] = $event_id;
        $tz_user_to_points['user_id'] = $user_id;
        $tz_user_to_points['points'] = $program_points;
        $tz_user_to_points['users_to_event'] = $users_to_event;
        $tz_user_to_points['created_at'] = datenow();
        $tz_user_to_points['modified_at'] = datenow();
        $this->Base_model->insert($tz_user_to_points, 'tz_user_to_points');

        #this is use for email sending
        #November 14-15, 2017 - 0900 - 1700
        $start_month = dateformat($event_row['event_start_date'], 'F');
        $end_month = dateformat($event_row['event_end_date'], 'F');
        
        $start_day = dateformat($event_row['event_start_date'], 'd');
        $end_day = dateformat($event_row['event_end_date'], 'd');
        
        $start_year = dateformat($event_row['event_start_date'], 'Y');
        $end_year = dateformat($event_row['event_end_date'], 'Y');

        $start_date = dateformat($event_row['event_start_date'], 'F d, Y');
        $end_date = dateformat($event_row['event_end_date'], 'F d, Y');
        if(!empty($start_date) && !empty($end_date))
        {
          if($start_day == $end_day && $start_month == $end_month && $start_year == $end_year)
          {
              $event_date_time = $start_month.' '.$start_day.', '.$start_year;
          }
          elseif($start_month == $end_month && $start_year == $end_year)
          {
              $event_date_time = $start_month.' '.$start_day.'-'.$end_day.', '.$start_year;
          }
          else
          {
              $event_date_time = $start_date.' - '.$end_date;
          }
        }
        elseif(!empty($start_date) && empty($end_date))
        {
          $event_date_time = $start_date;
        }
        elseif(empty($start_date) && !empty($end_date))
        {
          $event_date_time = $end_date;
        }
        $post_data['event_date_time'] = $event_date_time;

        $post_data = array_merge($post_data, $user_row);
        $post_data = array_merge($post_data, $event_row);
        $post_data = array_merge($post_data, $tz_user_to_event);
        $this->load->library('email');
        $config['useragent'] = 'Event Admin';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        //will process base on type of payment
        if(isset($post_data['payment']))
        {
          switch($post_data['payment'])
          {
            case 'Bank to Bank':
            case 'Cheque':
              $this->email->initialize($config);
              $this->email->from('support@semicon.com', '');
              $this->email->to($user_row['user_email_address']);
              // $this->email->cc('another@another-example.com');
              $this->email->bcc('tazki04@gmail.com');
              $post_data['event_name'] = $event_row['event_name'];
              $this->email->subject($event_row['event_name'].' Event Registration Payment');
              $post_data['encoded_email'] = urlencode(base64_encode($user_row['user_email_address']));
              $this->email->message($this->load->view('api/mail_event_register_bank_cheque_success', $post_data, true));
              // $this->email->send();
            break;
            /*
            case 'Credit Card':
              $cc_payment['return_data'] = $post_data['return_data'];
              $cc_payment['payment_status'] = 'completed';
              $post_data['payment_type'] = 'Credit Card';#this will update payment on email
              $post_data['payment_status'] = 'COMPLETED';#this will update payment status on email
              $cc_payment['receipt_number'] = strtoupper(datenow('YmdHis').$user_id.$event_id);
              $cc_payment['modified_at'] = datenow();
              $cond = '`users_to_event` = "'.$users_to_event.'"';
              if($this->Base_model->update($cc_payment, $cond, 'tz_user_to_event'))
              {
                // $this->email->initialize($config);
                // $this->email->from('support@semicon.tinkermak.com', '');
                // $this->email->to($user_row['user_email_address']);
                // // $this->email->cc('another@another-example.com');
                // $this->email->bcc('support@semicon.tinkermak.com');

                // $post_data = array_merge($post_data, $user_row);
                // $post_data = array_merge($post_data, $event_row);
                // $post_data['receipt_number'] = $cc_payment['receipt_number'];
                // $post_data['total_amount'] = $total_amount;
                // $post_data['currency'] = $currency;
                // $post_data['mode_of_payment'] = 'CREDIT CARD';
                // $post_data['status_of_payment'] = 'PAID';
                // $post_data['date_paid'] = datenow('F d, Y, h:i a');
                // $this->email->subject('Successful '.$event_row['event_name'].' Event Registration');
                // $this->email->message($this->load->view('api/mail_event_register_receipt', $post_data, true));
                // $this->email->send();
              }
            break;
            */
          }
        }

        if(!isset($post_data['payment'])
            || (isset($post_data['payment']) && $post_data['payment'] != 'Credit Card'))
        {
          #Event Success Registration
          $this->email->initialize($config);
          $this->email->from('support@semicon.com', '');
          $this->email->to($user_row['user_email_address']);
          // $this->email->cc('another@another-example.com');
          $this->email->bcc('tionghian@dynamiquekonzepts.com');
          $this->email->bcc('tazki04@gmail.com');
          $this->email->subject('Successful '.$event_row['event_name'].' Event Registration');
          $this->email->message($this->load->view('api/mail_event_register_confirmation_afterregistration', $post_data, true));
          $this->email->send();
        }

        $success_data = array();
        $success_data['user_email_address'] = $user_row['user_email_address'];
        $success_data['event_name'] = $event_row['event_name'];
        $success_data['event_image'] = $event_row['event_image'];
        // Set the response and exit
        $this->response($success_data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
      }
    }
    else
    {
      #review details
      echo $final_result; 
    }
  }

  public function ccpayment_post()
  {
    $user_id = $this->get('u');
    $event_id = $this->get('e');
    $user_row = $this->Base_model->search_one('user_id="'.$user_id.'"', 'tz_users');

    $this->load->library('form_validation');
    $post_data = $this->input->post(null, false);
    if(sizeof($_POST) > 0)
    {
      $count = 0;
      foreach($post_data as $field_name => $field_val)
      {
          if(!is_array($field_val)
              && !in_array($field_val, array('tazki04@gmail.com')))
          {
              if(!in_array($field_name, array('return_data')))
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
        $reference = base64_encode(datenow('ymdhis').'_'.$user_id.'|'.$event_id);
        $total_amount = $post_data['total_amount'];
        $currency = 'SGD';#strtoupper($post_data['currency']);
        if($currency == "USD")
        {
          #testing
            // $mid = "3117120008";
            // $dynkey = "3117120008";
          #live
            $mid = "3117030010";
            $dynkey = "5KD8Vk26wD1pOBBOT99HQJ48o1FX44I9";
        }
        if($currency== "SGD")
        {
          #testing
            // $mid = "3117120007";
            // $dynkey = "3117120007";
          #live
            $mid = "33117030009";
            $dynkey = "5KD8Vk26wD1pOBBOT99HQJ48o1FX44I9";
        }

        $linkBuf = $dynkey. "?mid=" . $mid ."&ref=" . $reference."&cur=" .$currency ."&amt=" .$total_amount;
        $fgkey = md5($linkBuf);
        $return = "http://semicon.tinkermak.com/api/event/ccpayment";
        
        // MID (MCPTID): 3116090007
        // Merchant secretkey: 3116090007
        // Currency: SGD

        // To test a payment, please use card no 4111111111111111 for Visa and 5555555555554444 for MasterCard (expiry date: 03/2018, cvv: 123)
        // Once the integration is working as expected, you may use your production keys.

        // $url = 'https://map.uat.mcpayment.net/api/PaymentAPI/Purchase'; #Test URL
        $url = 'https://map.mcpayment.net/api/PaymentAPI/Purchase'; #Live URL
        $params = array(
          'mid' => $mid,
          'txntype' => 'SALE',
          'reference' => $reference,
          'cur' => $currency,
          'amt' => $total_amount,
          'shop' => 'SEMICON',
          'product' => 'Item',
          'lang' => 'EN',
          'returnurl' => $return,
          'statusurl' => '',
          'charset' => 'UTF-8',
          'fgkey' => $fgkey,
          'cardno' => $post_data['cardno'],
          'cvv' => $post_data['cvv'],
          'expmonth' => $post_data['expmonth'],
          'expyear' => $post_data['expyear'],
          'cardholder' => $post_data['cardholder'],
          'city' => '',
          'region' => '',
          'postal' => '',
          'country' => '',
          'email' => $user_row['user_email_address'],
          'buyer' => $user_row['user_first_name'].' '.$user_row['user_last_name']
        );
        $data = json_encode($params);
        $headers= array(
          "Content-type: application/json",
          "Authorization: sk_0ccc54d6-9c4d-4f7e-94d9-63eaac66ffac"
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);  // Seems like good practice
        if(isset($result['rescode']))
        {
          if(in_array($result['rescode'], array('200','0000')))
          {
            $message['status'] = 'success';
            $message['return_data'] = json_encode($result);
          }
          else
          {
            $message['status'] = 'danger';
            $message['alert'] = $result['resmsg'];
          }
        }
        else
        {
          $message['status'] = 'danger';
          $message['alert'] = 'Failed Transaction, Please try again later.';
        }
      }
      else
      {
        #array form variables need to be declare as array
        $alert = '';
        $message = array();
        $message['status'] = 'danger';
        // $message['alert'] = validation_errors('<span>', '</span>');
        foreach($post_data as $field_name => $field_val)
        {
          $error_msg = form_error($field_name, '<span class="error">', '</span>');
          if(!empty($error_msg))
          {
            // $message['alert'][$field_name] = $error_msg;
            $alert .= $error_msg.'<br>';
          }
        }
        $message['alert'] = $alert;
      }        

      $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
  }
}




