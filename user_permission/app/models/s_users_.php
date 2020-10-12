<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s_users extends CI_Model {

    private $sd;
    private $mtb;

    function __construct(){
	parent::__construct();

	$this->sd = $this->session->all_userdata();
	$this->mtb = $this->tables->tb['a_users'];
    }

    public function base_details(){
	$this->load->model('s_branch');

	$a['table_data'] = $this->data_table();
	$a['branch'] = $this->s_branch->select();

	return $a;
    }

    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');

        $this->table->set_template($this->useclass->grid_style());

        $code = array("data"=>"Code", "style"=>"width: 50px;");
        $name = array("data"=>"User Name", "style"=>"width: 150px;");
        $address = array("data"=>"Description", "style"=>"");
        $action = array("data"=>"Action", "style"=>"width: 60px;");

        $this->table->set_heading($code, $name, $address, $action);//, $phone

        $this->db->select(array('cCodes', 'loginName', 'discription', 'isAdmin'));
        $query = $this->db->get($this->mtb);

        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->cCode."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->cCode."\")' title='Delete' />";

            $code = array("data"=>$r->cCode, "value"=>"code");
            $name = array("data"=>$this->useclass->limit_text($r->loginName, 25));
            $address = array("data"=>$r->discription);
            //$phone = array("data"=>$this->useclass->limit_text($r->phone01.", ".$r->phone02.", ".$r->phone03, 20));
            $action = array("data"=>$but, "style"=>"text-align: center;");

            $this->table->add_row($code, $name, $address, $action);//, $phone
        }

        return $this->table->generate();
    }

    public function save(){

	if(! isset($_POST['isAdmin'])){ $_POST['isAdmin'] = 0; }
	$_POST['userPassword'] = md5($_POST['userPassword']);

        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
            unset($_POST['code_']);
            $this->db->insert($this->mtb, $_POST);
        }else{
            $this->db->where("cCode", $_POST['code_']);
            unset($_POST['code_']);
            $this->db->update($this->mtb, $_POST);
        }

	redirect(base_url()."?action=s_users");
    }

    public function check_code(){
	$this->db->where('cCode', $_POST['code']);
	$this->db->limit(1);

	echo $this->db->get($this->mtb)->num_rows;
    }

    public function load(){
	$this->db->where('cCode', $_POST['code']);
	$this->db->limit(1);

	echo json_encode($this->db->get($this->mtb)->first_row());
    }

    public function delete(){
	$this->db->where('cCode', $_POST['code']);
	$this->db->limit(1);

	echo $this->db->delete($this->mtb);
    }

    public function authenticate(){
	$this->load->database('company', true);
	$this->db->select(array('code', 'db_name'));
	$this->db->where('code', $_POST['company']);
	$query = $this->db->get('db');

	if($query->num_rows){
	    $r = $query->first_row();
	    $this->session->set_userdata(array('db_code'=>$r->code, 'db'=>$r->db_name));

	    $this->load->database($r->db_name, true);

	    $query = "SELECT * FROM `".$this->mtb."`
		    WHERE `loginName` = ".$this->db->escape($_POST['userName'])."
		    AND `userPassword` = md5(".$this->db->escape($_POST['userPassword']).")
		    LIMIT 1 ";

	    $result = $this->db->query($query);

	    if($result->num_rows){
		$r = $result->first_row();

		$a = array(
		    "oc"=>$r->cCode,
		    "last_login"=>time(),
		    "last_active"=>time()
                        );

		$this->db->where("oc", $r->cCode);
		$this->db->delete($this->tables->tb['a_log_users']);
		$this->db->insert($this->tables->tb['a_log_users'], $a);

		$session_data = array(
		    "is_login"=>true,
		    "isAdmin"=>$r->isAdmin,
		    "oc"=>$r->cCode,
		    "user_des"=>$r->discription,
		    "bc"=>$r->bc,
		    "is_reg"=>true
		);

		$this->session->set_userdata($session_data);
		return 1;
	    }else{
		return 0;
	    }
	}else{
	    return 2;
	}
    }

    public function get_log_user(){
	$this->db->limit(1);
	$this->db->where('cCode', $this->sd['oc']);

	return $this->db->get($this->mtb)->first_row();
    }
}