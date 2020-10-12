<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class prints extends CI_Controller {

    private $sd;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    }
    
    public function trance_forms(){
	$this->load->model($this->uri->segment(3));
	$this->load->view("print_trnce", $this->{$this->uri->segment(3)}->print_view());
    }
    
    
    public function trance(){
	$this->load->model($this->uri->segment(3));
	$this->load->view("print_trnce", $this->{$this->uri->segment(3)}->print_delivery_note());
    }
    
    public function report_forms(){
	$this->load->model($this->uri->segment(3));
	$this->load->view("r_print", $this->{$this->uri->segment(3)}->base_details());
    }
}