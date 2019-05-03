<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Apiui extends CI_Controller {

	public function firstload()
	{
		$this->controller = strtolower(get_class($this));
		$this->data['controller'] = $this->controller;
		$this->load->model('User_model');
	}

	public function tpl()
    {
    	$this->load->view(''.$this->tpl_body, $this->data);
    }

	public function index($page='login')
	{
		$this->data = '';
		$this->tpl_body = 'api/'.$page;
		$this->tpl();
	}
}
