<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {
	
	private $sd;
	
	function __construct(){
		parent::__construct();
		$this->sd = $this->session->all_userdata();
		
		
		if(isset($this->sd['db'])){ 
			$this->load->database($this->sd['db'], true); 
		}
		
	}
	
	public function index(){
		if(isset($this->sd['is_dashboard']) == true && $this->sd['is_dashboard']==true){
			$this->load->database("default", true); 
			$this->load->model('dashboard');
			$this->load->view('dashboard', $this->dashboard->base_details());

		}else{
			$this->load->model('dashboard_login');
			$this->load->view('dashboard_login', $this->dashboard_login->base_details());
		}
		
	}

	public function login(){

		if($_POST['userName']=="md" && $_POST['userPassword']=="md@213"){
			$this->session->set_userdata(array(
				'is_dashboard'=>true));
			echo "1";
		}else{
			
			
			$this->session->set_userdata(array(
				'is_dashboard'=>false));
			echo "1";
		}
		
		
	}
	
	public function logout(){

		$sd = array(
			"is_dashboard"=>false
			);
		
		$this->session->set_userdata($sd);
		redirect(base_url());
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
	
	public function load_return_qty(){
		$this->load->model($this->uri->segment(3));
		$this->{$this->uri->segment(3)}->load_return_qty();
	}
	
	public function get_return_data(){
		$this->load->model($this->uri->segment(3));
		$this->{$this->uri->segment(3)}->load_return();
	}
	
	public function get_department(){
		$this->load->model($this->uri->segment(3));
		$this->{$this->uri->segment(3)}->load_main_cat();
	}
	
	public function get_sub_cat(){
		$this->load->model($this->uri->segment(3));
		$this->{$this->uri->segment(3)}->load_sub_cat();
	}
	
	public function get_subitem_data(){
		$this->load->model($this->uri->segment(3));
		$this->{$this->uri->segment(3)}->load_subitem();
	}
	

	public function select(){
		$this->load->model($this->uri->segment(3));
		$this->{$this->uri->segment(3)}->select();
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
	
	public function backup(){
		$this->load->dbutil();
		$backup =& $this->dbutil->backup();
		
		$this->load->helper('download');
		force_download(time().'.gz', $backup); 
	}

	
	
}