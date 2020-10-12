<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard_login extends CI_Model {

  private $sd;
  private $mtb;


  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database('seetha', true);

  }

  public function base_details(){
    $a['s'] = "adasdasd";
    return $a;
  }




 }

