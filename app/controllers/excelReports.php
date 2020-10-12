<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
class excelReports extends CI_Controller {
	
    private $session_data;
    
	function __construct(){
		parent::__construct();
        $this->load->helper("url");
		$this->load->helper("form");
		$this->load->database();
		$this->load->library('session');
        $this->session_data = $this->session->all_userdata();
		$this->load->library('Excel');
	}



	function generate(){
		if($_POST['by']==""){
			$_POST['by']="r_stock_in_hand";
		}


		$this->load->model($_POST['by']);
		$meHv= new 	$_POST['by'];
		if(method_exists($meHv, 'Excel_report')){
			$this->{$_POST['by']}->Excel_report();
		}
		else
		{
			echo "<script>alert('No Excel Report Found');window.close();</script>";			
		}

    }




	
}