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
class Survey extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Base_model');
    }

    public function index_get()
    {
        $arr_answer = array();
        $id = $this->get('id');
        if(!empty($id))
        {
            $survey_answer = $this->Base_model->list_all('tz_survey_answer', '', '', '', '', 'user_id="'.$id.'" AND event_id="0"');
            foreach($survey_answer as $key => $val)
            {
                if(!empty($val['survey_answer_option_id']))
                {
                    if(stristr($val['survey_answer_option_id'], ','))
                    {
                        $answer_option = explode(',', $val['survey_answer_option_id']);
                        foreach($answer_option as $skey => $sval)
                        {
                            $arr_answer['option_'.$val['survey_id'].'_'.$sval] = $sval;
                        }
                        
                    }
                    else
                    {
                        $arr_answer['option_'.$val['survey_id']] = $val['survey_answer_option_id'];
                    }
                }

                if(!empty($val['survey_answer_textbox']))
                {
                    $arr_answer['textbox_'.$val['survey_id']] = $val['survey_answer_textbox'];
                }
            }
        }
        // prearr($arr_answer);
        
        $search_string = $this->get('q');
        $search_query = '`survey_status` = "2"';
        if($search_string !== NULL)
        {
            $search_query .= ' AND (`survey_code` LIKE "%'.$search_string.'%"
                OR `survey_question` LIKE "%'.$search_string.'%"
            )';
        }
        $arr_rows = $this->Base_model->list_all('tz_survey', '', '', 'survey_sort', 'asc', $search_query, 'survey_id,survey_code,survey_question,survey_input_type');
        $rows_option = $this->Base_model->list_all('tz_survey_option', '', '', 'survey_option_sort', 'asc', 'survey_option_status="2"', 'survey_id,survey_option_id,survey_option,survey_option_code,survey_option_has_textbox');
        // Check if the users data store contains users (in case the database result returns NULL)
        if(is_array($arr_rows) && sizeof($arr_rows) > 0)
        {
            $rows = array();
            foreach($arr_rows as $key => $val)
            {
                $rows[$val['survey_id']] = $val;
            }

            foreach($rows_option as $key => $val)
            {
                if(isset($rows[$val['survey_id']]))
                {
                  $rows[$val['survey_id']]['option'][$val['survey_option_id']] = $val;
                    
                  #this is for checkbox input types
                  if(isset($arr_answer['option_'.$val['survey_id'].'_'.$val['survey_option_id']]))
                  {
                    $rows[$val['survey_id']]['option'][$val['survey_option_id']]['answer_option'] = $arr_answer['option_'.$val['survey_id'].'_'.$val['survey_option_id']];
                  }
                  
                  if(isset($arr_answer['option_'.$val['survey_id']]))
                  {
                    $rows[$val['survey_id']]['option'][$val['survey_option_id']]['answer_option'] = $arr_answer['option_'.$val['survey_id']];
                  }

                  if(isset($arr_answer['textbox_'.$val['survey_id']]))
                  {
                    $rows[$val['survey_id']]['option'][$val['survey_option_id']]['answer_textbox'] = $arr_answer['textbox_'.$val['survey_id']];
                  }
                }
            }
            $for_json_rows = array();
            $for_json_count = 0;
            foreach($rows as $key => $val)
            {
                $for_json_rows[$for_json_count] = $val;
                $for_json_count++;
            }
            // prearr($for_json_rows);die;

            // Set the response and exit
            $this->response($for_json_rows, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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