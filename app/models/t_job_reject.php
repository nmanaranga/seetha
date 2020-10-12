<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_job_reject extends CI_Model{

    private $sd;
    private $m_sup;
    private $mod = '003';
    
    function __construct(){
       parent::__construct();

       $this->sd = $this->session->all_userdata();
       $this->load->database($this->sd['db'], true);
       $this->m_sup = $this->tables->tb['m_supplier'];
       $this->load->model('user_permissions');
   }

   public function base_details(){
    $this->load->model("utility");
    $a['max_no'] = $this->utility->get_max_no("t_job_reject", "nno");
    return $a;
}

public function validation(){
    $status=1;
    $this->max_no = $this->utility->get_max_no("t_job_reject", "nno");
    return $status;
}

public function save(){
	
    $this->db->trans_begin();
    error_reporting(E_ALL);
    function exceptionThrower($type,$errMsg,$errFile, $errLine){
        throw new Exception($errMsg);     
    }
    set_error_handler('exceptionThrower');
    try{
        $validate = $this->validation();
        if($validate==1){
            $sum = array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "nno"=>$this->max_no,
                "supplier"=>$_POST['supplier'],
                "memo"=>$_POST['comment'],
                "ddate"=>$_POST['date'],
                "oc"=>$this->sd['oc']
                );
            for($x =0; $x<=25; $x++){
                if (isset($_POST['0_' . $x], $_POST['n_' . $x],$_POST['sel_'.$x])){
                    if ($_POST['0_' . $x] != "" && $_POST['n_' . $x] != "") {
                        $det[]= array(
                            "cl"=>$this->sd['cl'],
                            "bc"=>$this->sd['branch'],
                            "nno"=>$this->max_no,
                            "job_no"=>$_POST['0_'.$x],
                            "reason"=>$_POST['4_'.$x] 
                            );
                    }
                }
            }
            if($_POST['hid'] == "0" || $_POST['hid'] == ""){
                if($this->user_permissions->is_add('t_job_reject')){
                    $this->db->insert("t_job_reject",$sum);
                    if(count($det)) {$this->db->insert_batch("t_job_reject_det", $det);}                   
                    
                    for($x=0;$x<25;$x++){
                      if(isset($_POST['sel_' . $x])){
                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where("nno", $_POST['0_'.$x]);
                        $this->db->update("t_job", array("status"=>"1"));
                    }
                }
                $this->utility->save_logger("SAVE",98,$this->max_no,$this->mod);
                echo $this->db->trans_commit();
            }else{
                $this->db->trans_commit();
                echo "No permission to save records";
            }
        }else{
            if($this->user_permissions->is_edit('t_job_reject')){
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("nno", $_POST['hid']);
                $this->db->update("t_job_reject",$sum);

                    //---------------------me for loop eka oni ne-----------------
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("nno", $_POST['hid']);
                $this->db->delete("t_job_reject_det");


                if (count($det)) {$this->db->insert_batch("t_job_reject_det", $det);}

                for($x=0;$x<25;$x++){
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("nno", $_POST['0_'.$x]);
                    $this->db->update("t_job", array("status"=>"0"));

                    if(isset($_POST['sel_' . $x])){
                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where("nno", $_POST['0_'.$x]);
                        $this->db->update("t_job", array("status"=>"1"));
                    }
                }

                $this->utility->save_logger("EDIT",91,$this->max_no,$this->mod);
                echo $this->db->trans_commit();
            }else{
                $this->db->trans_commit();
                echo "No permission to edit records";
            }
        }
    }else{
        $this->db->trans_commit();
        echo $validate;
    } 
}catch(Exception $e){
    $this->db->trans_rollback();
    echo $e->getmessage()."Operation fail Please conatact Admin";
}

}

public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
}


public function delete(){

    $this->db->trans_begin();
    error_reporting(E_ALL);

    function exceptionThrower($type,$errMsg,$errFile,$errLine){
        throw new Exception($errMsg); 
    }

    set_error_handler('exceptionThrower');
    try{
        if($this->user_permissions->is_delete('t_job_reject')){
            $this->db->where("nno",$_POST['id']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("cl",$this->sd['cl']);
            $this->db->update("t_job_reject", array("is_cancel"=>"1"));

            $sql = "SELECT nno,job_no FROM `t_job_reject_det` where nno= '".$_POST['id']."'";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row) {
                $this->db->where("cl",$this->sd['cl']);
                $this->db->where("bc",$this->sd['branch']);
                $this->db->where("nno",$row->job_no);
                $this->db->update("t_job",array("status"=>"0"));
            }
            
            $this->utility->save_logger("CANCEL",91,$_POST['id'],$this->mod);
            echo $this->db->trans_commit();
        }else{
            echo "No permission to delete records";
            $this->db->trans_commit();
        }
    }catch(Exception $e){
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin";
    }
}

public function load_service_items(){

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

    $sql= "SELECT 
    tb.`nno` AS job_no,
    tb.`ddate`,
    tb.`cus_id`,
    mc.`name`,
    tb.`item_code`,
    tb.`status`
    FROM t_job tb
    JOIN `m_customer` mc ON mc.`code` = tb.`cus_id`
    JOIN `m_supplier` ms ON ms.`code` = tb.`supplier`
    WHERE supplier = '".$_POST['supplier']."'
    AND tb.`cl`='".$this->sd['cl']."'
    AND tb.`bc`='".$this->sd['branch']."'
    AND tb.`status` = '0'
    AND tb.is_cancel != '1' 
    AND (tb.cus_id LIKE '%$_POST[search]%' OR mc.name LIKE '%$_POST[search]%' OR tb.nno LIKE '%$_POST[search]%' OR tb.item_code LIKE '%$_POST[search]%')";

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
        $a['a'] = $this->db->query($sql)->result();
    } else {
        $a['a'] = 2;
    }  
    echo json_encode($a);
}

public function load(){

    $sql_sum = "SELECT 
    tr.`bc`,
    tr.`cl`,
    tr.`nno`,
    tr.`ddate`,
    tr.`ref_no`,
    tr.`is_cancel`,
    tr.`memo`,
    tr.`supplier` AS supplier_code,
    tr.`memo`,
    ms.`name` AS suppier_name
    FROM `t_job_reject` tr 
    JOIN `t_job_reject_det` tjd ON tjd.`cl` = tr.`cl` AND tjd.`bc` = tr.`bc` AND tjd.`nno`= tr.`nno`
    JOIN `m_supplier` ms ON ms.`code` = tr.`supplier` 
    WHERE tjd.`nno` = '".$_POST['id']."'
    AND tr.`cl`='".$this->sd['cl']."'
    AND tr.`bc`='".$this->sd['branch']."'";

    $query_sum = $this->db->query($sql_sum);

    $sql_det = "SELECT 
    td.`job_no`,
    tb.`ddate`,
    tb.`cus_id`,        
    mc.`name`,
    tb.`item_code`,
    tb.`Item_name`,
    td.`reason`
    FROM t_job_reject_det td
    JOIN `t_job`  tb ON  tb.`nno` = td.`job_no` AND tb.`cl`=td.`cl` AND tb.`bc`=td.`bc`
    JOIN `m_customer` mc ON mc.`code` = tb.`cus_id`
    WHERE td.`nno` = '".$_POST['id']."'
    AND td.`cl`='".$this->sd['cl']."'
    AND td.`bc`='".$this->sd['branch']."'
    GROUP BY td.`job_no` ";

    $query_det = $this->db->query($sql_det);

    if($query_sum->num_rows()>0){
        $a["a"]=$this->db->query($sql_sum)->result();

    }else{
        $a["a"] = 2;
    }

    if($query_det->num_rows()>0){
        $a["b"]=$this->db->query($sql_det)->result();

    }else{
        $a["b"]=2;
    }
    echo json_encode($a);
} 
public function select_supplier(){
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
    $sql = "SELECT 
    tj.`supplier`,
    ms.`name`  
    FROM t_job tj
    JOIN `m_supplier` ms ON ms.`code`= tj.`supplier`
    WHERE tj.`status` = '0'
    AND tj.`cl`='".$this->sd['cl']."'
    AND tj.`bc`='".$this->sd['branch']."'
    AND(tj.supplier LIKE '%$_POST[search]%'OR ms.name LIKE '%$_POST[search]%')
    GROUP BY tj.`supplier` LIMIT 25";

    $query = $this->db->query($sql);
    $a ="<table id='item_list' style='width:100%'>";
    $a.="<thead><tr>";
    $a.="<th class ='tb_head_th'>Code</th>";
    $a.="<th class='tb_head_th' colspan='2'>Description</th>";
    $a.="</thead></tr><tr class='cl' style='visibility:hidden;'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
        $a.="<tr class='cl'>";
        $a.="<td>".$r->supplier."</td>";
        $a.="<td colspan='2'>".$r->name."</td>";
        $a.="</tr>";
    }
    $a."</table>";
    echo $a;

}

public function PDF_report(){
    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();

    $this->db->select(array('loginName'));
    $this->db->where('cCode',$this->sd['oc']);
    $r_detail['user']=$this->db->get('users')->result();

    $r_detail['session'] = $session_array;

    $r_detail['orientation'] = "L";
    $r_detail['page']="A5";

    $sql = "SELECT 
    td.`nno`,
    td.`job_no`,
    tj.`ddate` AS r_date,
    tr.`ddate`AS t_date,
    tj.`cus_id`,
    mc.`name`,
    tj.`item_code`,
    tj.`Item_name`,
    td.`reason`,
    tr.`ref_no`,
    tr.`memo`
    FROM t_job tj
    JOIN t_job_reject_det td ON td.`cl`=tj.`cl`AND  td.`bc`=tj.`bc` AND td.`job_no`=tj.`nno`
    JOIN t_job_reject tr ON tr.`cl`=td.`cl` AND tr.`bc`=td.`bc` AND tr.`nno`=td.`nno`
    JOIN m_customer mc ON mc.`code`=tj.`cus_id`
    WHERE tj.`cl`= '".$this->sd['cl']."'
    AND tj.`bc` = '".$this->sd['branch']."'
    AND td.`nno` ='".$_POST['qno']."'";

    $r_detail['reject']= $this->db->query($sql)->result();
    $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

}
}