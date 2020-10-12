

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_day_process extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
    	parent::__construct();
	    $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
    }
    
    public function base_details(){
        $a['trans']=$this->select();

        $a['max']=$this->max_no();
        $a['max_date']=$this->max_date();
        return $a;
    }


    public function PDF_report(){

        $r_detail['type']=$_POST['type'];        
        $r_detail['dt']=$_POST['dt'];
        $r_detail['qno']=$_POST['qno'];

        $r_detail['page']=$_POST['page'];
        $r_detail['header']=$_POST['header'];
        $r_detail['orientation']=$_POST['orientation'];
        $r_detail['title']="SERIALS DETAILS";

        $r_detail['all_det']=$_POST;
        


        $this->db->select(array('name','address','tp','fax','email'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['branch']=$this->db->get('m_branch')->result();


        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }


    public function select(){
        $query = $this->db->get('t_trans_code');
        $s = "<select name='trans_code' id='trans_code'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
        }
        $s .= "</select>";
        return $s;
    }


    public function validation(){
        $this->max_no = $this->max_no = $this->max_no();
        $status = 1;
        $account_update=$this->account_update(0,0,0);
        if($account_update!=1){
            return "Invalid account entries";
        } 
        return $status;
    }


    public function save(){
        $validation_status = $this->validation();
        if ($validation_status == 1) {

           
            $this->max_no = $this->max_no();

            $this->inactive_po();
            $this->instalment();
            $this->add_penalty();
            $this->day_end_sum();
           
           
            $a=1;
           

            echo json_encode($a);
        } else {
            echo $validation_status;
        }
    }


    public function inactive_po(){
        $sql2="SELECT nno,type FROM t_po_sum WHERE ddate <= '".$_POST['date']."' - INTERVAL 1 MONTH AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."' AND inactive='0'";
        $query3=$this->db->query($sql2);

        $sql="UPDATE t_po_sum s,
            (SELECT nno,type FROM t_po_sum WHERE ddate <= '".$_POST['date']."' - INTERVAL 1 MONTH AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."' AND inactive='0')t
            SET inactive = '1'
            WHERE s.`nno` = t.nno
            AND s.type = t.type
            AND cl ='".$this->sd['cl']."' 
            AND bc ='".$this->sd['branch']."'";

   
        $query= $this->db->query($sql);   
        $query2= $this->db->affected_rows($sql);  

        
        foreach($query3->result() as $t){
            $this->utility->save_logger("Inactive PO no ". $t->nno." type ".$t->type,47,0,$this->mod);
        }
    }


    public function instalment(){
        $sqll="SELECT * FROM t_ins_schedule WHERE due_date = '".$_POST['date']."'  AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'";
        $queryy=$this->db->query($sqll);


        foreach($queryy->result() as $t){
           $this->account_update(1,$t->trans_no,$t->ins_amount);
        }
    }


    public function add_penalty(){

        $sql="SELECT t_credit_sales_sum.`cus_id`, due_date, trans_no, ROUND(SUM(ins_amount-ins_paid) * ((SELECT penalty_rate FROM def_option_account) /100),2) AS amount
            FROM t_ins_schedule 
            JOIN t_credit_sales_sum ON t_credit_sales_sum.`nno` = t_ins_schedule.`trans_no` AND t_credit_sales_sum.`cl` = t_ins_schedule.cl AND t_credit_sales_sum.`bc` = t_ins_schedule.bc
            WHERE DATE_ADD(`due_date`,INTERVAL (SELECT grace_period FROM def_option_account) DAY) <= '".$_POST['date']."' 
            AND t_ins_schedule.cl ='".$this->sd['cl']."' AND t_ins_schedule.bc ='".$this->sd['branch']."'
            GROUP BY trans_no
            HAVING amount > 0";
        $query = $this->db->query($sql); 
        
        

        foreach($query->result() as $r){
            $penalty[] = array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "nno" => $r->trans_no,
                "ddate" => $r->due_date,
                "cus_id" => $r->cus_id,
                "dr_type" => 47,
                "dr_no" => $this->max_no,
                "cr_type" => "",
                "cr_no" => "",
                "dr" => $r->amount,
                "cr" => "",
                "oc" => $this->sd['oc']
            );
        }  

        if (isset($penalty)) {
            if (count($penalty)) {
                $this->db->insert_batch("t_penalty_trance", $penalty);
            }
        } 

    }

    public function day_end_sum(){
        $day_sum = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "nno" => $this->max_no,
            "ddate" => $_POST['date'],
            "oc" => $this->sd['oc']
        );
        $this->db->insert('t_day_end', $day_sum);
    }

    public function account_update($condition,$des,$amount) {

        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 47);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");

        

        $config = array(
            "ddate" => $_POST['date'],
            "trans_code" => 47,
            "trans_no" => $this->max_no,
            "op_acc" => 0,
            "reconcile" => 0,
            "cheque_no" => 0,
            "narration" => "",
            "ref_no" => ""
        );

        $des = "Day End Process - " . $des;
        $this->load->model('account');
        $this->account->set_data($config);
  
        $acc_code=$this->utility->get_default_acc('INTEREST_INCOME');
        $this->account->set_value2($des, $amount, "cr", $acc_code,$condition);

        $acc_code=$this->utility->get_default_acc('INTEREST_SUSPENCE');
        $this->account->set_value2($des, $amount, "dr", $acc_code,$condition);



        if($condition==0){
             $query = $this->db->query("
                 SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
                 FROM `t_check_double_entry` t
                 LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
                 WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='47'  AND t.`trans_no` ='" . $this->max_no . "' AND 
                 a.`is_control_acc`='0'");

            if ($query->row()->ok == "0") {
                $this->db->where("trans_no", $this->max_no);
                $this->db->where("trans_code", 47);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_check_double_entry");
                return "0";
            } else {
                return "1";
            }
        }
    }


    public function load_penalty(){

        $sql="SELECT ins_amount-ins_paid AS amount, trans_no,due_date
                FROM t_ins_schedule 
                WHERE DATE_ADD(`due_date`,INTERVAL (SELECT grace_period FROM def_option_account) DAY) <= '".$_POST['date']."' 
                AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'
                HAVING amount > 0";
        $query=$this->db->query($sql);
        $x=0;
        $a="";
        foreach($query->result() as $r){
            $a .= "<tr>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='0_".$x."' value='".$r->due_date."' title='".$r->due_date."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%;cursor:pointer;text-align:center;'/></td>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_num' name='1_".$x."' value='".$r->trans_no."' title='".$r->trans_no."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;text-align:left;'/></td>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='n_".$x."' value='".$r->amount."' title='".$r->amount."'  style='border:dotted 1px #ccc;background-color: #f9f9ec; cursor:pointer; width:100%;text-align:center;'/></td>";
            $a .= "</tr>";
            $x++;
        }
        echo $a;       
    }



    public function load_po(){
        $sql="SELECT * FROM t_po_sum WHERE ddate  <= '".$_POST['date']."' - INTERVAL 1 MONTH AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."' AND inactive='0' ";
        $query=$this->db->query($sql);
         $x=0;
         $a="";
        foreach($query->result() as $r){
            $a .= "<tr>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='0_".$x."' value='".$r->ddate."' title='".$r->ddate."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%;cursor:pointer;text-align:center;'/></td>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_num' name='1_".$x."' value='".$r->nno."' title='".$r->nno."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;text-align:left;'/></td>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='n_".$x."' value='".$r->supplier."' title='".$r->supplier."'  style='border:dotted 1px #ccc;background-color: #f9f9ec; cursor:pointer; width:100%;text-align:center;'/></td>";
            $a .= "</tr>";
            $x++;
        }
        echo $a;
    }

    public function load_intrest(){
        $sql="SELECT * FROM t_ins_schedule WHERE due_date = '".$_POST['date']."'  AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'";
        $query=$this->db->query($sql);
        
         $x=0;
         $a="";
        foreach($query->result() as $r){
            $a .= "<tr>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='0_".$x."' value='".$r->due_date."' title='".$r->due_date."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%;cursor:pointer;text-align:center;'/></td>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_num' name='1_".$x."' value='".$r->trans_no."' title='".$r->trans_no."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;text-align:left;'/></td>";
            $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='n_".$x."' value='".$r->ins_amount."' title='".$r->ins_amount."'  style='border:dotted 1px #ccc;background-color: #f9f9ec; cursor:pointer; width:100%;text-align:center;'/></td>";
            $a .= "</tr>";
            $x++;
        }
        echo $a;
    }

    public function max_no(){    
        $this->db->select_max('nno');
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);    
        return $this->db->get('t_day_end')->first_row()->nno+1;    
    }

    public function max_date(){
        $sql="SELECT DATE_ADD(IFNULL(MAX(ddate),CURRENT_DATE),INTERVAL 1 DAY) AS date 
        FROM t_day_end 
        WHERE cl='".$this->sd['cl']."' 
        AND bc='".$this->sd['branch']."'
        limit 1";  
        return $this->db->query($sql)->first_row()->date;    
    }

    public function transaction_list(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $trans_no=$_POST['trans_no'];
        $trans_code=$_POST['trans_code'];
        $sql="SELECT t_logger.oc,t_logger.action_date,t_logger.action,t_logger.ip_address,users.discription FROM t_logger join users on t_logger.oc=users.cCode WHERE trans_code='$trans_code' AND trans_no='$trans_no' AND t_logger.cl='$cl' AND t_logger.bc='$bc'";
        $query=$this->db->query($sql);
        $a = "<table id='item_list' style='width : 100%' border='0'>";
            $x=0;
            foreach($query->result() as $r){

                    $a .= "<tr>";
                    $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='0_".$x."' value='".$r->oc."' title='".$r->oc."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%;cursor:pointer;text-align:center;'/></td>";
                    $a .= "<td ><input type='text' readonly='readonly' class='g_input_num' name='1_".$x."' value='".$r->discription."' title='".$r->discription."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;text-align:left;'/></td>";
                    $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='2_".$x."' value='".$r->action."' title='".$r->action."'  style='border:dotted 1px #ccc;background-color: #f9f9ec; cursor:pointer; width:100%;text-align:center;'/></td>";
                    $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='2_".$x."' value='".$r->action_date."' title='".$r->action_date."'  style='border:dotted 1px #ccc;background-color: #f9f9ec; cursor:pointer; width:100%;text-align:right;'/></td>";
                    $a .= "<td ><input type='text' readonly='readonly' class='g_input_txt' name='3_".$x."' value='".$r->ip_address."' title='".$r->ip_address."' style='border:dotted 1px #ccc;background-color: #f9f9ec; cursor:pointer;width:100%;text-align:right;'/></td>";
                    $a .= "</tr>";
            $x++;
            }
        $a .= "</table>";
        
        echo $a;
    }

}