<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_privilege_card_update extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_privilege_card'];
    $this->m_customer = $this->tables->tb['m_customer'];
    $this->t_sales_sum=$this->tables->tb['t_credit_sales_sum'];
    $this->t_previlliage_trans=$this->tables->tb['t_previlliage_trans'];
    $this->t_privilege_card=$this->tables->tb['t_privilege_card']; 
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
    $a['table_data'] = $this->data_table();
    
    return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $invno = array("data"=>"Inv No", "style"=>"width: 50px;");
        $description = array("data"=>"Date", "style"=>"");
        $amount = array("data"=>"Amount", "style"=>"width: 60px;");
        
        $this->table->set_heading($invno, $description, $amount);
        if(isset($_POST['code'])){
        $this->db->select(array('nno', 'ddate', 'net_amount'));
        $this->db->where('cus_id', $_POST['code']);
        $query = $this->db->get($this->t_sales_sum);
        
        foreach($query->result() as $r){
            $invno = array("data"=>$r->nno);
            $description = array("data"=>$r->ddate);
            $amount = array("data"=>$r->net_amount);
           
        
            $this->table->add_row($invno, $description, $amount);
        }
        }
        return $this->table->generate();
    }
    
    public function get_data_table(){
    echo $this->data_table();
    }
    
    public function save(){   
        $data= array('card_no' =>$_POST['card_no']  ,
                'customer_id' =>$_POST['customer_id']  ,
                'issue_date' =>$_POST['ddate']  ,
                'expire_date' =>$_POST['edate']  ,
                'inactive_date' =>$_POST['idate'],
                'inactive_reason'=>$_POST['ireason'],
                'inactive'=>1
                );

        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
            if($this->user_permissions->is_add('t_privilege_card_update')){
                unset($_POST['code_']);
                $this->db->where("card_no", $_POST['card_no']);
                echo $this->db->update($this->t_privilege_card, $data);
            }else{
                echo "No permission to save records";
            }    
        }else{
            if($this->user_permissions->is_edit('t_privilege_card_update')){
                $this->db->where("card_no", $_POST['card_no']);
                unset($_POST['code_']);
                echo $this->db->update($this->t_privilege_card, $data);
            }else{
                echo "No permission to edit records";
            }     
        
        }
 }
    
    public function check_code(){
    $this->db->where('card_no', $_POST['card_no']);
    $this->db->limit(1);
    
    echo $this->db->get($this->t_privilege_card)->num_rows;
    }
    
    public function load(){
    $this->db->where('card_no', $_POST['code']);
    $this->db->limit(1);
    
    echo json_encode($this->db->get($this->t_privilege_card)->first_row());
    }
    
    public function delete(){
        if($this->user_permissions->is_delete('t_privilege_card_update')){
            $this->db->where('code', $_POST['code']);
            $this->db->limit(1);
            
            echo $this->db->delete($this->mtb);
        }else{
        echo "No permission to delete records";
        }
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

     public function auto_com(){
        $this->db->like('customer_id', $_GET['q']);
        //$this->db->or_like($this->mtb.'.description', $_GET['q']);
        $this->db->limit(5);
        $query = $this->db->select(array('customer_id'))->get($this->mtb);

        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->customer_id;
                $abc .= "\n";
            }
        
        echo $abc;
        } 

    public function item_list_all(){

       if($_POST['search'] == 'Key Word: card_no, name'){$_POST['search'] = "";}

    
       $this->db->select('*');
       $this->db->from($this->m_customer);
       $this->db->join($this->t_privilege_card , $this->m_customer.'.code='.$this->t_privilege_card.'.customer_id');
       $this->db->like('card_no', $_POST['search'], 'after'); 
       $this->db->limit(25);
       $query = $this->db->get();


        $a = "<table id='item_list' style='width : 100%' >";
        $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>ID</th>";
            $a .= "<th class='tb_head_th'>Address</th>"; 
            $a .= "<th class='tb_head_th'>TP</th>"; 
            $a .= "<th class='tb_head_th'>Email</th>"; 
            $a .= "</thead></tr>
                <tr class='cl'>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
            ";

            
            foreach($query->result() as $r){
                    $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->code."</td>";
                    $a .= "<td>".$r->address2."</td>"; 
                    $a .= "<td>".$r->tp."</td>";
                    $a .= "<td>".$r->email."</td>";    
                    $a .= "</tr>";
            }
        $a .= "</table>";
        echo $a;
    }

    public function get_item(){
        $this->db->select(array('code','address2','email','tp'));
        $this->db->where("code",$this->input->post('code'));
        $this->db->limit(1);
        $query=$this->db->get($this->m_customer);
        if($query->num_rows() > 0){
            $data['a']=$query->result();
        }else{
            $data['a']=2;
        }
        
        echo json_encode($data);
    }


  public function get_point_history(){
                 
         $data['b']=$this->db->select_sum('dr' ,'sdr')
         ->where('card_no',$_POST['card_no'])
         ->get($this->t_previlliage_trans)->result();

         $data['c']=$this->db->select_sum('cr' ,'scr')
         ->where('card_no',$_POST['card_no'])
         ->get($this->t_previlliage_trans)->result();
         
         $query= $this->db->select()->where('card_no',$_POST['card_no'])->get($this->t_previlliage_trans);                  
                           
            if($query->num_rows() > 0){
                $data['a']=$query->result();
                }
                else{
                    $data['a']=2;
                    }
        
            echo json_encode($data);
    }

   public function get_invoice_history(){
        $this->db->select(array('nno','net_amount','ddate'));
        $this->db->where("previlliage_card_no",$this->input->post('code'));
        $this->db->limit(5);
        $query=$this->db->get($this->t_sales_sum);
        if($query->num_rows() > 0){
            $data['a']=$query->result();
        }else{
            $data['a']=2;
        }
        
        echo json_encode($data);
    }

     public function get_customer(){
       $this->db->select('*');
       $this->db->from($this->m_customer);
       $this->db->join($this->t_sales_sum , $this->m_customer.'.code='.$this->t_sales_sum.'.cus_id');
       $this->db->where('previlliage_card_no', $_POST['card_no']); 
       $this->db->limit(1);
       $query = $this->db->get();
        if($query->num_rows() > 0){
            $data['a']=$query->result();
        }else{
            $data['a']=2;
        }
        
        echo json_encode($data);
    }
   



}