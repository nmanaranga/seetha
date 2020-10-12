<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class loder extends CI_Model {
    
    private $sd;
    private $log_user;
    private $tb_users;
    private $tb_per;
    private $tb_per_send;
    private $tb_cus;
    private $tb_tab;
    private $tb_ser;
    private $cluster;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->log_user = $this->tables->tb['a_log_users'];
	$this->tb_users = $this->tables->tb['a_users'];
	$this->tb_per = $this->tables->tb['t_customer_credit_permission'];
	$this->tb_per_send = $this->tables->tb['t_customer_credit_permission_senders'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_tab = $this->tables->tb['s_tabs'];
	$this->tb_ser = $this->tables->tb['t_serial_movement'];
	$this->cluster=$this->tables->tb['m_cluster'];
	$this->user_log_update();
    }
    
    public function base_details(){
	$this->load->model('s_company');
	$this->load->model('s_users');
       
	
	
	$a['company'] = $this->s_company->get_company_name();
	$a['user'] = $this->s_users->get_log_user();
	$a['branch_det'] = $this->branch_det();
	$a['isAdmin'] = $this->sd['up_isAdmin'];
    $a['is_process'] = $this->sd['is_process'];
    $a['prv_date'] = $this->sd['prv_date'];
    $a['ds']=$this->default_settings(); 
   
	
	return $a;
    }


    public function default_settings(){

    	$a = array();
    	$query=$this->db->get('def_option_stock');
    	foreach($query->result() as $row){
    		$a['use_sub_items']=$row->use_sub_items;
    		$a['use_serial_no_items']=$row->use_serial_no_items;
    		$a['use_item_batch']=$row->use_item_batch;
    		$a['use_additional_items']=$row->use_additional_items;
    		$a['use_multi_stores']=$row->use_multi_stores;
    		$a['def_store_code']=$row->def_store_code;
    		$a['def_purchase_store_code']=$row->def_purchase_store_code;
    		$a['def_sales_store_code']=$row->def_sales_store_code;
    		$a['use_sales_category']=$row->use_sales_category;
    		$a['def_sales_category_code']=$row->def_sales_category_code;
    		$a['use_sales_group']=$row->use_sales_group;
    		$a['sales_group_code']=$row->sales_group_code;
    	}

    	$query=$this->db->get('def_option_sales');
    	foreach($query->result() as $row){
    		// $a['use_salesman']=$row->use_salesman;
    		// $a['def_salesman_code']=$row->salesman_cat_code;
    		// $a['use_collection_officer']=$row->use_collection_officer;
    		// $a['def_collection_officer_code']=$row->collection_officer_cat_code;
    	}

    	$query=$this->db->get('def_option_account');
    	foreach($query->result() as $row){
    		$a['sales_discount_to_separate_acc']=$row->sales_discount_to_separate_acc;
    		$a['sales_return_to_separate_acc']=$row->sales_return_to_separate_acc;
    	}

    	return $a;
    }
    
    public function select(){
    	$query = $this->db->get($this->cluster);
    
        $s = "<select name='cluster' id='cluster'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
        
		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." - ".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;

    }


    public function branch_det(){
    	$cl="";
    	$bc="";
    	// $this->db->select('description');
    	// $this->db->where('code',$this->sd['cl']);
    	// $cl=$this->db->get('m_cluster')->first_row()->description;

    	$this->db->select('name');
    	$cl=$this->db->get('s_company')->first_row()->name;

    	$this->db->select('name');
    	$this->db->where('bc',$this->sd['up_branch']);
    	$bc=$this->db->get('m_branch')->first_row()->name;

    	return "(".$this->sd['up_cl'].") ".$cl." | (".$this->sd['up_branch'].") ".$bc;
    }

    public function select2(){
    	$query = $this->db->get_where($this->mtb,array('cl'=>'C1'));
    
        $s = "<select name='branch' id='company' style='width:180px;box-shadow:1px 1px 4px #000 inset;'>";
       // $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
        
		$s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc." - ".$r->name."</option>";
        }
        $s .= "</select>";
     $data['result']=$s;
       echo json_encode($data);

    }


    
    public function load(){
	$this->db->select(array(
	   $this->tb_per.".id",
	   $this->tb_cus.".name",
	   $this->tb_users.".discription",
	   "request_balance",
	   $this->tb_per.".action_date",
	   $this->tb_per.".l1",
	   $this->tb_per.".l2",
	   $this->tb_per.".l3"
	));
	
	$this->db->join($this->tb_per, $this->tb_per.".id = ".$this->tb_per_send.".request_id", "INNER");
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_per.".customer", "INNER");
	$this->db->join($this->tb_users, $this->tb_users.".cCode = ".$this->tb_per.".oc", "INNER");
	$this->db->limit(1);
	$this->db->where('conform', 0);
	$this->db->where($this->tb_per_send.'.oc', $this->sd['oc']);
	$query = $this->db->get($this->tb_per_send);
	
	$a['permission'] = $query->num_rows;
	
	if($query->num_rows){
	    $a['per_data'] = $query->first_row();
	}
	
	echo json_encode($a);
    }
    
    public function permission_request()
    {
    
        
        
        $sql="SELECT
                `t_cash_sales_sum`.`no`	
                ,`t_cash_sales_sum`.`id`	
                ,`t_cash_sales_sum`.`oc`
                , `s_users`.`loginName`
                , `t_cash_sales_sum`.`date`
                , `t_cash_sales_sum`.`customer`
                , `m_customer`.`name`
                ,  per.permission AS per
                ,  per_high.permission AS per_high
                ,  per.discription AS user

            FROM `t_cash_sales_sum` 
                INNER JOIN  `m_customer`
                    ON (`m_customer`.`code` = `t_cash_sales_sum`.`customer`)
                INNER JOIN `s_users` ON (`s_users`.`cCode`= `t_cash_sales_sum`.`oc`)
                INNER JOIN (SELECT DISTINCT(cCode),permission,discription FROM s_users INNER JOIN(SELECT oc FROM t_cash_sales_sum WHERE response='0' group by `no` order by `no` ASC) AS op
                ON op.oc=s_users.cCode 
                WHERE cCode=op.oc) AS per
                INNER JOIN (SELECT permission FROM s_users WHERE cCode='".$this->sd['oc']."') AS per_high
            WHERE `is_cancel`  =  0 AND is_approve=0 AND is_request='1' AND feed_back=0 AND per.permission < per_high.permission";
       
        
        
        $q=$this->db->query($sql);
        $a['per'] = $q->result();
        
        $sql="SELECT
                `t_cash_sales_sum`.`no`	
                ,`t_cash_sales_sum`.`id`	
                ,`t_cash_sales_sum`.`post_by` AS oc
                , `s_users`.`loginName`
                , `t_cash_sales_sum`.`date`
                , `t_cash_sales_sum`.`customer`
                , `m_customer`.`name`
                , is_approve
                , is_reject
                , discription AS `user`

            FROM `t_cash_sales_sum` 
                INNER JOIN  `m_customer`
                    ON (`m_customer`.`code` = `t_cash_sales_sum`.`customer`)
                INNER JOIN `s_users` ON (`s_users`.`cCode`= `t_cash_sales_sum`.`post_by`)
                INNER JOIN (SELECT oc FROM t_cash_sales_sum WHERE show_confrom_alert='0' group by `no` order by `no` ASC) AS op 
            WHERE `is_cancel`  =  0 AND (is_approve='1' OR is_reject='1')
                 AND response='1' AND show_confrom_alert='0' AND op.oc='".$this->sd['oc']."' ";

        
        $q=$this->db->query($sql);
        $a['response'] = $q->result();
        
        
        
        $sql="SELECT
                `t_sales_sum`.`no`	
                ,`t_sales_sum`.`id`	
                ,`t_sales_sum`.`oc`
                , `s_users`.`loginName`
                , `t_sales_sum`.`date`
                , `t_sales_sum`.`customer`
                , `m_customer`.`name`
                ,  per.permission AS per
                ,  per_high.permission AS per_high
                ,  per.discription AS user

            FROM `t_sales_sum` 
                INNER JOIN  `m_customer`
                    ON (`m_customer`.`code` = `t_sales_sum`.`customer`)
                INNER JOIN `s_users` ON (`s_users`.`cCode`= `t_sales_sum`.`oc`)
                INNER JOIN (SELECT DISTINCT(cCode),permission,discription FROM s_users INNER JOIN(SELECT oc FROM t_sales_sum WHERE response='0' group by `no` order by `no` ASC) AS op
                ON op.oc=s_users.cCode 
                WHERE cCode=op.oc) AS per
                INNER JOIN (SELECT permission FROM s_users WHERE cCode='".$this->sd['oc']."') AS per_high
            WHERE `is_cancel`  =  0 AND is_approve=0 AND is_request='1' AND feed_back=0 AND per.permission < per_high.permission";
       
        
        
        $q=$this->db->query($sql);
        $a['pero'] = $q->result();
        
        $sql="SELECT
                `t_sales_sum`.`no`	
                ,`t_sales_sum`.`id`	
                ,`t_sales_sum`.`post_by` AS oc
                , `s_users`.`loginName`
                , `t_sales_sum`.`date`
                , `t_sales_sum`.`customer`
                , `m_customer`.`name`
                , is_approve
                , is_reject
                , discription AS user

            FROM `t_sales_sum` 
                INNER JOIN  `m_customer`
                    ON (`m_customer`.`code` = `t_sales_sum`.`customer`)
                INNER JOIN `s_users` ON (`s_users`.`cCode`= `t_sales_sum`.`post_by`)
                INNER JOIN (SELECT oc FROM t_sales_sum WHERE show_confrom_alert='0' group by `no` order by `no` ASC) AS op 
            WHERE `is_cancel`  =  0 AND (is_approve='1' OR is_reject='1')
                AND  response='1' AND show_confrom_alert='0' AND op.oc='".$this->sd['oc']."' ORDER BY `t_sales_sum`.`no` ASC";
        
        
        $q=$this->db->query($sql);
        $a['responseo'] = $q->result();

        echo json_encode($a);
        
    }

    public function user_log_update(){
	if(isset($this->sd['oc'])){
	    $this->db->where('oc', $this->sd['oc']);
	    $this->db->update($this->log_user, array("last_active"=>time()));
	}
    }
    
    public function get_online_users(){
	$this->db->select(array('cCode AS code', 'discription'));
	$this->db->join($this->log_user, $this->log_user.".oc = ".$this->tb_users.".cCode", "INNER");
	$this->db->where('permission >=', $_POST['user_level']);
	$this->db->where('last_active >=', (time()-6));
	
	echo json_encode($this->db->get($this->tb_users)->result());
    }
    
    public function save_request(){
	$a = array(
	    "customer"=>$_POST['customer'],
	    "oc"=>$this->sd['oc'],
	    "l1"=>$_POST['l1'],
	    "l2"=>$_POST['l2'],
	    "l3"=>$_POST['l3'],
	    "request_balance"=>$_POST['total']
	);
	
	$this->db->insert($this->tb_per, $a);
	$lid = $this->db->insert_id();
	
	$a = array();
	foreach($_POST['users'] as $r){
	    $a[] = array(
		"request_id"=>$lid,
		"oc"=>$r
	    );
	}
	
	$this->db->insert_batch($this->tb_per_send, $a);
	
	echo json_encode(array("lid"=>$lid));
    }
    
    public function cheque_permission(){
	$this->db->select(array("conform", "discription"));
	$this->db->join($this->tb_users, $this->tb_users.".cCode = ".$this->tb_per.".conform_by", "LEFT OUTER");
	$this->db->where("id", $_POST['request_id']);
	$this->db->limit(1);
	
	echo json_encode($this->db->get($this->tb_per)->first_row());
    }
    
    public function cancel_request(){
	$this->db->where("id", $_POST['request_id']);
	$this->db->limit(1);
	$this->db->update($this->tb_per, array("conform"=>3));
	
	echo 1;
    }
    
    public function respons_for_request(){
	$this->db->where("id", $_POST['request_id']);
	$this->db->limit(1);
	$this->db->update($this->tb_per, array("conform"=>$_POST['action'], "conform_by"=>$this->sd['oc']));
	
	echo 1;
    }
}