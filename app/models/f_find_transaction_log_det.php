

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class f_find_transaction_log_det extends CI_Model {
    
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

    public function transaction_list(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $trans_no=$_POST['trans_no'];
        $trans_code=$_POST['trans_code'];
        
        $sql="SELECT t_log_det.oc,
                    t_log_det.action_date,
                    t_log_det.action,
                    t_log_det.ip_address,
                    s_users.discription 
              FROM t_log_det 
              LEFT JOIN s_users ON t_log_det.oc = s_users.cCode 
              WHERE trans_code='$trans_code' 
              
              AND t_log_det.cl='$cl' 
              AND t_log_det.bc='$bc'";

           
        if($trans_no!=""){
            $sql.="AND trans_no='$trans_no'";
        }

        $query=$this->db->query($sql);

        if($query->num_rows()>0){       
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
         }else{
            $a=2;
         }
        
        echo $a;
    }

}