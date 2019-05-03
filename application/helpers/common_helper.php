<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('alert'))
{
	function alert($alert, $content='', $type='status', $url='')
	{
		$status_message = array(
			'alert' => $alert,
			'content' => trim(preg_replace('/\s+/', ' ', $content)),
			'type' => $type,
			'url' => $url);
		return json_encode($status_message);
	}
}

if ( ! function_exists('sorter'))
{
    function sorter($sort_field, $controller, $get_query, $search_string, $action='index')
    {
        $active_asc = '';
        $active_desc = '';
        if(isset($get_query['sort']) && $get_query['sort']==$sort_field && isset($get_query['order']) && $get_query['order'] == 'desc')
        {
            $active_asc = '';
            $active_desc = 'active';
        }

        if(isset($get_query['sort']) && $get_query['sort']==$sort_field && isset($get_query['order']) && $get_query['order'] == 'asc')
        {
            $active_asc = 'active';
            $active_desc = '';
        }

        $asc_url = site_url($controller.'/'.$action.'/'.((!empty($search_string)) ? $search_string.'&' : '?').'sort='.$sort_field.'&order=asc');
        $desc_url = site_url($controller.'/'.$action.'/'.((!empty($search_string)) ? $search_string.'&' : '?').'sort='.$sort_field.'&order=desc');
        return '<div class="caret-holder">
            <a href="'.$asc_url.'">
              <span class="fa fa-caret-up '.$active_asc.'"></span>
            </a>
            <a href="'.$desc_url.'">
              <span class="fa fa-caret-down '.$active_desc.'"></span>
            </a>
        </div>';
    }
}

if ( ! function_exists('pagination'))
{
    function pagination($controller, $total_rows, $total_segments, $per_page='', $base_url='')
    {
        $ci =& get_instance();
        $ci->load->library('pagination');
        #pagination library
        #comment out this code "$get[$this->query_string_segment]" unset($get['c'], $get['m'], $get[$this->query_string_segment])
        $config['base_url'] = (!empty($base_url)) ? $base_url : site_url($controller.'/index/');
        $config['reuse_query_string'] = false;
        $config['suffix'] = (sizeof($_GET) > 0) ? '?'.http_build_query($_GET) : '';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = (isset($_REQUEST['per_page'])) ? $_REQUEST['per_page'] : 50;
        $config['per_page'] = (isset($per_page)) ? $per_page : 50;
        $config['uri_segment'] = $total_segments;        
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '&lsaquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_url'] = $config['base_url'].$config['suffix'];
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li>'; 
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $ci->pagination->initialize($config);

        $data['pagination'] = $ci->pagination->create_links();
        $data['current_page'] = ($ci->pagination->cur_page > 0) ? $ci->pagination->cur_page : 1;
        $data['total_page'] = ceil(($config['total_rows']/$config['per_page']));
        $data['total_rows'] = $config['total_rows'];
        $data['per_page'] = $config['per_page'];

        return $data;
    }
}

/** 
 * Checks an ip address (and whether the server is dev or staging on a MediaTemple (dv) subdomain),
 * prints a message, and ends page execution if the IP isn't allowed.
 *
 * Use it in the instantiation block of any controller to restrict the page, or in any method
 * you want restricted.
 * 
 * @author         Sean Gates
 * @link         http://seangates.com
 * @license        GNU Public License (GPL)
 * 
 * @access         public
 * @param         string [$ip] valid IP address
 * 
 * USAGE:
 *
 *         class Home extends Controller {
 *         
 *             function Home()
 *             {
 *                 parent::Controller();
 * 
 *                 // bounce the person if they don't have the right IP address
 *                 ip_bouncer($ci->input->ip_address());
 *             }
 * 
 *             ...
 *         }
 * 
 */
if (! function_exists('ip_bouncer'))
{
    function ip_bouncer($ip)
    {
        // restrict to these IP addresses
        $ip_addresses = array('::1','127.0.0.1', '112.209.36.188','112.201.255.60');

        // check if the ip is allowed, and whether we're on the dev or staging servers
        # && strstr(getcwd(),'/htdocs/')
        if(!in_array($ip, $ip_addresses))
        {
            echo 'This is a restricted area. Move along ...';
            exit();
        }
    }
}

function getRandomDarkColor()
{
    $r = rand(0,255);
    $g = rand(0,150);
    $b = rand(0,100);
    
    $arr = array('rgba('.$r.','.$g.','.$b.',0.2)','rgba('.$r.','.$g.','.$b.',1)');
    
    return $arr;
}

if ( ! function_exists('imageUpload'))
{
    function imageUpload($image_filename_uploader, $cWidth = 0, $cHeight = 0, $suffix="_thumb", $path='repository/deals/')
    {
        $CI =& get_instance();
        if(isset($_FILES[$image_filename_uploader]) && is_array($_FILES[$image_filename_uploader]) && !empty($_FILES[$image_filename_uploader]['name']))
        {   
            $upload_config['upload_path'] = str_replace('hadmin', $path, $CI->config->config['base_path']);
            $upload_config['allowed_types'] = 'gif|jpg|jpeg|png';

            if(!is_dir($upload_config['upload_path']))
            {
                mkdir($upload_config['upload_path'], 0777, TRUE);
            }

            $CI->load->library('upload', $upload_config);

            #Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class:
            $CI->upload->initialize($upload_config);
            
            $_FILES[$image_filename_uploader]['name'] = date('Ymdhis').rand(0,999).'.'.pathinfo($_FILES[$image_filename_uploader]['name'], PATHINFO_EXTENSION);

            $CI->upload->do_upload($image_filename_uploader);
            $upload_data = $CI->upload->data();

            $upload_errors = $CI->upload->display_errors();
        
            $CI->data['uploader_error'] = $upload_errors;
            if(empty($upload_errors))
            {
                $resizeImg = 0;
                $condition = false;
                if($cWidth != 0 || $cHeight != 0)
                {   
                    $condition = imageResize($upload_config['upload_path'], $upload_data['file_name'], $cWidth, $cHeight, $suffix);
                }

                $newfname = $upload_data['file_name'];
                if($condition == true)
                {
                    $path_parts = pathinfo($upload_data['file_name']);
                    $newfname =$path_parts['filename'] .  $suffix ."." . $path_parts['extension'];
                }

                return $newfname;
            }
        }
    }
}

if ( ! function_exists('saveSurveyAnswer'))
{
    function saveSurveyAnswer($data, $user_id, $event_id=0)
    {
        $CI =& get_instance();
        if(is_array($data))
        {
            // prearr($data);die;
            $clean_data = array();
            foreach($data as $key => $val)
            {
                if(stristr($key, 'survey'))
                {
                    $answer = $val;
                    if(is_array($val))
                    {
                        $answer = implode(',', $val);
                    }

                    $survey_id = str_replace('survey_', '', $key);
                    if(stristr($key, 'survey_answer_textbox_'))
                    {
                        if(is_array($val) && sizeof($val) > 0)
                        {
                            $answer = '';
                            foreach($val as $skey => $sval)
                            {
                                if(!empty($sval))
                                {
                                    $comma = (!empty($answer)) ? ',' : '';
                                    $answer .= $comma.$skey.'___'.$sval;
                                }
                            }
                        }

                        $survey_id = str_replace('survey_answer_textbox_', '', $key);
                        $clean_data[$key]['survey_answer_textbox'] = $answer;
                    }
                    else
                    {
                        $clean_data[$key]['survey_answer_option_id'] = $answer;   
                    }

                    $clean_data[$key]['user_id'] = $user_id;
                    $clean_data[$key]['event_id'] = $event_id;
                    $clean_data[$key]['survey_id'] = $survey_id;
                }
            }
            // prearr($clean_data);die;

            foreach($clean_data as $key => $val)
            {
                if(!empty($val['survey_answer_textbox']) || !empty($val['survey_answer_option_id']))
                {
                    $CI->Base_model->insert($val, 'tz_survey_answer');
                }
            }
        }
    }
}


// ------------------------------------------------------------------------

/* End of file common_helper.php */
/* Location: ./senta/application/helpers/common_helper.php */