<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plot extends CI_Controller {
    
    private $sd;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    }
    
    public function index(){
	if(isset($this->sd['is_login']) == true){
	    $this->load->view("plot");
	}else{
	    $this->load->model('login');
	    $this->load->view('login', $this->login->base_details());
	}
    }
    
    public function login(){
	$this->load->model('s_users');
	
	echo $this->s_users->authenticate();
    }
    
    public function logout(){
	$sd = array(
            "is_login"=>false
        );
	
        $this->session->set_userdata($sd);
	$this->session->unset_userdata("uid");
	$this->session->unset_userdata("is_login");
	
	redirect("");
    }
    
    public function save(){
	$this->load->model($this->uri->segment(3));
	$this->{$this->uri->segment(3)}->save();
    }
    
    public function delete(){
	$this->load->model($this->uri->segment(3));
	$this->{$this->uri->segment(3)}->delete();
    }
    
    public function get_data(){
	$this->load->model($this->uri->segment(3));
	$this->{$this->uri->segment(3)}->load();
    }
    
    public function load_data(){
	$this->load->model($this->uri->segment(3));
	
	if(isset($_GET['echo'])){
	    if($_GET['echo'] == "true"){
		echo json_encode($this->{$this->uri->segment(3)}->{$this->uri->segment(4)}());
	    }else{
		$this->{$this->uri->segment(3)}->{$this->uri->segment(4)}();
	    }
	}else{
	    $this->{$this->uri->segment(3)}->{$this->uri->segment(4)}();
	}
    }
}