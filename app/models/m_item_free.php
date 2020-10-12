
	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_item_free extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->mtb = $this->tables->tb['m_item_free'];
    }
    
    public function base_details(){
    $this->load->model('m_items');
       
    $a['table_data'] = $this->data_table();
    $a['item']=$this->m_items->select(); 
    $a['max_no']=$this->max_no();
    return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"No", "style"=>"width: 50px;");
        $item = array("data"=>"Item", "style"=>"width: 50px;");
        $description = array("data"=>"Description", "style"=>"");
        $from = array("data"=>"From Date", "style"=>"width: 60px;");
        $to = array("data"=>"To Date", "style"=>"width: 50px;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($code,$item, $description, $from,$to,$action);
        
        $this->db->select(array('code', 'nno', 'ddate', 'description', 'qty', 'date_from', 'date_to'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->nno."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_item_free')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->nno."\")' title='Delete' />";}
            
            $no = array("data"=>$r->nno);
            $item = array("data"=>$r->code);
            $description = array("data"=>$this->useclass->limit_text($r->description, 25));
            $from = array("data"=>$r->date_from);
            $to = array("data"=>$r->date_to);
            $action = array("data"=>$but, "style"=>"text-align: center;");
        
            $this->table->add_row($no, $item, $description, $from,$to, $action);
        }
         
        return $this->table->generate();
    }
    
    public function get_data_table(){
    echo $this->data_table();
    }
    
    public function save(){
     
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {

        if(!isset($_POST['inactive'])){$_POST['inactive']=0;}else{$_POST['inactive']=1;}

        $a=array(
             "code"=>$_POST['code'],
             "nno"=>$_POST['nno'],
             "ddate"=>$_POST['ddate'],
             "description"=>$_POST['description'],
             "qty"=>$_POST['qty'],
             "inactive"=>$_POST['inactive'],
             "date_from"=>$_POST['date_from'],
             "date_to"=>$_POST['date_to'],
             "oc"=>$this->sd['oc']
            );


        for($x = 0; $x<12; $x++){
            if(isset($_POST['0_'.$x])){
                    if($_POST['0_'.$x] != "" && $_POST['n_'.$x] != "" ){
                        $b[]= array(
                            "item"=>$_POST['0_'.$x],
                            "nno"=>$_POST['nno']
                        );              
                    }
                }
            }   



        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
            if($this->user_permissions->is_add('m_item_free')){
                unset($_POST['code_']);
                $this->db->insert($this->mtb, $a);
                if(count($b)){ $this->db->insert_batch("m_item_free_det", $b );}
                echo $this->db->trans_commit();
                $this->emails();
            }else{
                echo "No permission to save records";
                $this->db->trans_commit();
            }  
        }else{
            if($this->user_permissions->is_edit('m_item_free')){
                $this->set_delete();
                $this->db->where("nno", $_POST['code_']);
                $this->db->update($this->mtb, $a);
                unset($_POST['code_']);
                if(count($b)){ $this->db->insert_batch("m_item_free_det", $b );}
                echo $this->db->trans_commit();
                $this->emails();
            }else{
                echo "No permission to edit records";
                $this->db->trans_commit();
            }
         }
      
      } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
      }  
     
 }
     
 public function emails(){
    

	$this->load->library('email');

		$this->db->select(array('email'));
        $r_details=$this->db->get('m_branch')->result();

		$em_sub="Item Free Issue ". date('Y-m-d');
		
		
		  for($x = 0; $x<12; $x++){
            if(isset($_POST['0_'.$x])){
                    if($_POST['0_'.$x] != "" && $_POST['n_'.$x] != "" ){
                        $bb[]= array();              
                    }
                }
            }   

 	 $em_body ="<table border='1' cellpadding='10px'>";
 	 $em_body.="<tr>";
 	 $em_body.="<th>Item Code</th>";
 	 $em_body.="<th>Item Name</th>";
 	 $em_body.="<th>Quantity</th>";
 	 $em_body.="<th>From Date</th>";
 	 $em_body.="<th>To Date</th>";

 	 $em_body.="<th bgcolor='#F0F0F0'>Free Item Code</th>";
 	 $em_body.="<th bgcolor='#F0F0F0'>Free Item Desctiption</th>";
 	 $em_body.="</tr>";

 	for($y=0;$y<sizeof($bb);$y++)
     	 {
	     	 $em_body.="<tr>";
	     	 $em_body.="<td>".$_POST['code']."</td>";
	     	 $em_body.="<td>".$_POST['test']."</rd>";
	     	 $em_body.="<td>".$_POST['qty']."</td>";
	     	 $em_body.="<td>".$_POST['date_from']."</td>";
	     	 $em_body.="<td>".$_POST['date_to']."</td>";
	     	 $em_body.="<td>".$_POST['0_'.$y]."</td>";
	     	 $em_body.="<td>".$_POST['n_'.$y]."</td>";
	     	 $em_body.="</tr>";
     	 }

 	 $em_body.="</table>";
 	 //echo $em_body;

		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->from('system@seetha.lk', 'Item Free Issue');
		
		$this->email->subject($em_sub);
		$this->email->message($em_body);

		foreach($r_details as $row)
		{
			//echo $row->email;
			$this->email->to($row->email);
			$this->email->send();
		}


 }

    public function check_code(){
    $this->db->where('nno', $_POST['nno']);
    $this->db->limit(1);  
    echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
        $this->db->select(array(
            'm_item.code',
            'm_item.description',
            'm_item_free.nno',
            'm_item_free.ddate',
            'm_item_free.description as mif_desc',
            'm_item_free.qty',
            'm_item_free.date_from',
            'm_item_free.date_to',
            'm_item_free.inactive'
            ));
        $this->db->from("m_item");
        $this->db->join("m_item_free","m_item.code=m_item_free.code");
        $this->db->where("nno", $_POST['nno']);
        $query = $this->db->get();
        $a['c'] = $query->result(); 
        
        
        $this->db->select(array('m_item.description','m_item_free_det.nno','m_item_free_det.item'));
        $this->db->from('m_item');
        $this->db->join('m_item_free_det', 'm_item_free_det.item= m_item.code');
        $this->db->where("nno", $_POST['nno']);
        $query = $this->db->get();
        $a['det'] = $query->result(); 

     
    
        echo json_encode($a);
    }
   
    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if($this->user_permissions->is_delete('m_item_free')){
                $this->db->where('nno', $_POST['nno']);
                $this->db->delete("m_item_free_det");
                
                $this->db->where('nno', $_POST['nno']);
                $this->db->limit(1);
                $this->db->delete($this->mtb);
                echo $this->db->trans_commit();
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }

   private function set_delete(){
        $this->db->where("nno", $_POST['code_']);
        $this->db->delete("m_item_free_det");
    } 



    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='sales_ref' id='sales_ref'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

    public function max_no(){
    $this->db->select_max("nno");
    return $this->db->get($this->mtb)->first_row()->nno+1;
    }



    
}