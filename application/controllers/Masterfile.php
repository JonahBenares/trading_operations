<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterfile extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        date_default_timezone_set("Asia/Manila");
        $this->load->model('super_model');
        $this->load->database();
        $personal_id =  $this->session->userdata('user_id');
        $data['position_display'] = $this->super_model->custom_query_single("j_position","SELECT j_position FROM job_history WHERE personal_id = '$personal_id' ORDER BY effective_date DESC LIMIT 1");
        $this->load->vars($data);
        function arrayToObject($array){
            if(!is_array($array)) { return $array; }
            $object = new stdClass();
            if (is_array($array) && count($array) > 0) {
                foreach ($array as $name=>$value) {
                    $name = strtolower(trim($name));
                    if (!empty($name)) { $object->$name = arrayToObject($value); }
                }
                return $object;
            } 
            else {
                return false;
            }
        }
    } 

	public function index()
	{
        $this->load->view('template/header');
        $this->load->view('masterfile/form');
		$this->load->view('template/footer');
	}
}
