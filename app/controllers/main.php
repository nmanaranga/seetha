<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {

	private $sd;

	function __construct(){
		parent::__construct();
		$this->sd = $this->session->all_userdata();
		//var_dump($this->sd);

		if(isset($this->sd['db'])){ 
			$this->load->database($this->sd['db'], true); 
		}
		
	}

	public function index(){
		if(base_url() == 'http://softmasterlk.com/mthree/' || base_url() == 'http://www.softmasterlk.com/mthree/'){
			redirect('http://mthree.softmasterlk.com');
		}else{
			if(isset($this->sd['is_login']) == true){

				if($this->sd['is_process']!=false){        
					$this->load->model("user_permissions");
					$this->load->view('header', $this->loder->base_details());

					if(isset($_GET['action'])){
						$this->load->model($_GET['action']);
						$this->load->view($_GET['action'], $this->{$_GET['action']}->base_details());
					}else{
						$this->load->view('welcome');
						$this->s_users->expire_penndings();
					}
					$this->load->view('footer');

				}else{
					$this->load->model("user_permissions");
					$this->load->view('header', $this->loder->base_details());
					if(isset($_GET['action'])){
						$this->load->model($_GET['action']);
						$this->load->view($_GET['action'], $this->{$_GET['action']}->base_details());
					}else{
						$this->load->view('welcome');
						$this->s_users->expire_penndings();
					}
					$this->load->view('footer');    
				}    
			}else{
				$this->load->model('login');
				$this->load->view('login', $this->login->base_details());
			}
		}
	}

	public function login(){
		$this->load->model('s_users');
		$this->s_users->check_previous_process_login();
		echo $this->s_users->authenticate();
		$this->load->model("user_permissions");
		$this->user_permissions->add_permission();


	}

	public function logout(){

		$sd = array(
			"is_login"=>false
			);

		$this->session->set_userdata($sd);
		$this->session->unset_userdata("oc");
		$this->session->unset_userdata("bc");
		$this->session->unset_userdata("db");
		$this->session->unset_userdata("is_login");

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

	public function smsgatewaycallback(){
		$data=$this->input->get();

		echo $data;
	}

}