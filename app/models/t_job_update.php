<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_job_update extends CI_Model{
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
  function __construct(){
	parent::__construct();
	
      $this->sd = $this->session->all_userdata();
      $this->load->database($this->sd['db'], true);
      $this->mtb = $this->tables->tb['t_job_send_sum'];
      $this->load->model('user_permissions');
      $this->max_no = $this->utility->get_max_no("t_job_send_sum", "nno");
    }
    
public function base_details(){
	$a['max_no'] = $this->utility->get_max_no("t_job_send_sum", "nno");
  return $a;
    }

public function load_data(){
                $sup=$_POST['sup_code'];
                $sql="SELECT 
                          j.nno,
                          j.ddate,
                          j.type,
                          j.cus_id,
                          c.`name`,
                          j.item_code,
                          i.`description` as itm,
                          j.item_name,
                          j.model,
                          j.serial_no,
                          j.is_guarantee,
                          j.guarantee_cardno,
                          j.fault 
                        FROM
                          t_job j
                          JOIN m_customer c ON c.`code`=j.`cus_id`
                        LEFT JOIN m_item i ON i.`code`=j.`item_code`
                        WHERE j.supplier='$sup' AND j.`status`='0' 
                        AND j.`cl` = '".$this->sd['cl']."'
                        AND j.`bc` = '".$this->sd['branch']."'
                        AND j.is_cancel!='1'
                        ";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0) {
                    $a["a"] = $this->db->query($sql)->result();
                } else {
                    $a["a"] = 2;
                }  
                echo json_encode($a);
    }
    

public function save(){

        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errLine); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
                      

        $t_job_update_sum = array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "nno"=>$_POST['id'],
                    "ref_no"=>$_POST['ref_no'],
                    "ddate"=>$_POST['date'],
                    "supplier"=>$_POST['sup_id'],
                    "memo"=>$_POST['comment'],              
                    "oc"=>$this->sd['oc']
                
            );

      for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x], $_POST['1_' . $x],$_POST['sel_' . $x])) {
              if ($_POST['0_' . $x] != "" && $_POST['1_' . $x] != "") {

              $t_job_update_det[] = array(
                          "cl"=>$this->sd['cl'],
                          "bc"=>$this->sd['branch'],
                          "nno"=>$_POST['id'],
                          "job_no"=>$_POST['0_'.$x]  
                  );

              
            }
          }
        }


       
            if($_POST['hid'] == "0" || $_POST['hid'] == ""){
                if($this->user_permissions->is_add('t_job_update')){
                    $this->db->insert($this->mtb, $t_job_update_sum);
                if (count($t_job_update_det)) {$this->db->insert_batch("t_job_send_det", $t_job_update_det);}
                    for($x=0;$x<25;$x++){
                      if(isset($_POST['sel_' . $x])){
                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where("nno", $_POST['0_'.$x]);
                        $this->db->update("t_job", array("status"=>"2"));
                      }
                    }
                    
                    $this->utility->save_logger("SAVE",91,$this->max_no,$this->mod); 
                    echo $this->db->trans_commit();
                }else{
                    $this->db->trans_commit();
                    echo "No permission to save records";
                }
            }else{
                if($this->user_permissions->is_edit('t_job_update')){
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("nno", $_POST['hid']);
                    $this->db->update($this->mtb, $t_job_update_sum);
                    for($x=0;$x<25;$x++){
                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where("nno", $_POST['0_'.$x]);
                        $this->db->delete("t_job_send_det");
                    }
                    if (count($t_job_update_det)) {$this->db->insert_batch("t_job_send_det", $t_job_update_det);}
                    for($x=0;$x<25;$x++){
                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where("nno", $_POST['0_'.$x]);
                        $this->db->update("t_job", array("status"=>"0"));
                    }
                    for($x=0;$x<25;$x++){
                      if(isset($_POST['sel_' . $x])){
                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where("nno", $_POST['0_'.$x]);
                        $this->db->update("t_job", array("status"=>"2"));
                      }
                    }
                    $this->utility->save_logger("EDIT",91,$this->max_no,$this->mod);
                    echo $this->db->trans_commit();
                }else{
                    $this->db->trans_commit();
                    echo "No permission to edit records";
                }
            }
            
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getmessage()."Operation fail please contact admin"; 
        } 
    }


public function load(){
    $nno=$_POST['id'];
    $sql_sum="SELECT js.ref_no,js.ddate,js.supplier,s.`name`,js.memo,js.is_cancel FROM `t_job_send_sum` js
          JOIN m_supplier s ON s.`code`=js.`supplier`
          WHERE js.nno='$nno'
          AND js.`cl`='".$this->sd['cl']."' 
          AND js.`bc`= '".$this->sd['branch']."'";

          $query_sum = $this->db->query($sql_sum);

    $sql_det="SELECT jd.nno,jd.job_no,j.ddate,j.cus_id,c.`name`,j.`item_code`,i.`description`,j.`Item_name`,j.`fault`,j.`serial_no`,j.`is_guarantee`,j.`guarantee_cardno` FROM `t_job_send_det` jd
              JOIN `t_job` j ON j.`nno`=jd.`job_no`
              JOIN m_customer c ON c.`code`=j.`cus_id`
              LEFT JOIN m_item i ON i.`code`=j.`item_code`
              WHERE jd.nno='$nno' 
              AND j.`cl`= '".$this->sd['cl']."'
              AND j.`bc` = '".$this->sd['branch']."'
              AND j.`status`='2'  group by jd.job_no";

          $query_det = $this->db->query($sql_det);

          if ($query_sum->num_rows() > 0) {
              $a["a"] = $this->db->query($sql_sum)->result();
          } else {
              $a["a"] = 2;
          }  

          if ($query_det->num_rows() > 0) {
              $a["b"] = $this->db->query($sql_det)->result();
          } else {
              $a["b"] = 2;
          }  
            echo json_encode($a);
    }

public function delete(){
    
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine)
    {
      throw new Exception($errLine);
    }
    
    set_error_handler('exceptionThrower');
    try {
      if($this->user_permissions->is_delete('t_job_update')){
        $this->db->where("nno", $_POST['id']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->limit(1);
        $this->db->update($this->mtb, array("is_cancel" =>"1"));  

          $sql="SELECT nno,job_no FROM `t_job_send_det` where nno='".$_POST['id']."'";
          $query = $this->db->query($sql);
          foreach ($query->result() as $row){
                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->where("nno", $row->job_no);
                  $this->db->update("t_job", array("status"=>"0"));
          }
   
        $this->utility->save_logger("CANCEL", 91, $_POST['id'], $this->mod);
        echo $this->db->trans_commit();
      } else {
        echo "No permission to delete records";
        $this->db->trans_commit();
      }
    }
    catch (Exception $e) {
      $this->db->trans_rollback();
      echo $e->getMessage() . "Operation fail please contact admin";
    }
  }
  public function PDF_report(){
    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();

    $this->db->select( array('loginName'));
    $this->db->where("cCode",$this->sd['oc']);
    $r_detail['user'] = $this->db->get('users')->result();

    $r_detail['session']=$session_array;
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']='L';

    $r_detail['page']="A5";

    $sql = "SELECT 
              dt.`nno`,
              dt.`job_no`,
              ds.`ddate`,
              ds.`ref_no`,
              ds.`supplier` AS sup_code,
              ms.`name` AS sup_name,
              tj.`fault`,
              tj.`serial_no`,
              tj.`item_code`,
              mi.`description` AS item_name,
              tj.`guarantee_cardno` AS gur_no,
              ds.`memo`
        FROM t_job tj
        JOIN t_job_send_det dt ON dt.`cl` = tj.`cl` AND dt.`bc`=tj.`bc` AND dt.`job_no` = tj.`nno`
        JOIN t_job_send_sum ds ON ds.`cl` = dt.`cl` AND ds.`bc`=dt.`bc` AND ds.`nno`=dt.`nno`
        JOIN m_supplier ms ON ms.`code` = ds.`supplier`
        JOIN m_item mi ON mi.`code` = tj.`item_code` 
        WHERE dt.`nno`= '".$_POST['qno']."'
        AND tj.`cl` = '".$this->sd['cl']."'
        AND tj.`bc` = '".$this->sd['branch']."'
        LIMIT 25";
        
        $r_detail['jobs'] = $this->db->query($sql)->result();
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

  }

}
    