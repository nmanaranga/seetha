<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sales_target extends CI_Model {

  private $sd;
  private $mtb;


  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');    
    $this->mtb = $this->tables->tb['t_sales_target'];

  }

  public function base_details(){
    $a['table_data'] = "adasdasd";

    $a['txtCluster'] = $this->sd['cl'];
    $a['txtBranch'] = $this->sd['branch'];

    // $a['table_data'] = $this->data_table();
    return $a;
  }

  public function load(){
   echo $this->data_table();  
 }

 public function data_table(){
  $this->load->library('table');
  $this->load->library('useclass');


  $dayCount= $_POST['dayCount'];
  $year= $_POST['year'];
  $month= $_POST['month'];

  $this->table->set_template($this->useclass->grid_style());

    // $tblID = array("data"=>"tblID", "style"=>"display: none;");
  $day = array("data"=>"Day", "style"=>"width: 50px; cursor : pointer;");
  $trgt = array("data"=>"Target", "style"=>"cursor : pointer;");
  $archment = array("data"=>"Achievement");
  $varince = array("data"=>"Variance Amount");
  $varincePre = array("data"=>"Variance Pre%");
  $reason = array("data"=>"Reason");

  $this->table->set_heading($day, $trgt, $archment,$varince,$varincePre,$reason);

  $sql0 = $this->db->query("SELECT auto_no FROM `t_sales_target_sum` 
    WHERE `mmonth`='".$year.'-'.$month."'  
    AND cl='".$this->sd['cl']."'  
    AND bc='".$this->sd['branch']."'");


  $targetID=0;
  $status='';
  $sumId='';

  if ($sql0->num_rows() > 0){  

    foreach($sql0->result() as $R){

      $targetID=$R->auto_no;
    }

  }


  $sql1= $this->db->query("SELECT * FROM `t_sales_target_det` 
    WHERE `t_sales_target`='".$targetID."'
    AND cl='".$this->sd['cl']."'  AND bc='".$this->sd['branch']."'");

  if($sql1->num_rows() > 0){

    $status='OLD';
    $sumId=$targetID;
    $totAmt=0;
    foreach($sql1->result() as $r){

      $today=$year."-".$month."-".$r->dday;
      $yrdata= strtotime($today);

      $archiveAmount=number_format($this->getAchivementForDay($today , $this->sd['cl'] , $this->sd['branch']), 2, '.', '');
      $totAmt+=$archiveAmount;

      $varAmount=number_format(((float)$archiveAmount) - ((float) $r->target),2);

      $varAmountPres=$archiveAmount < 1 ? 0.00 :  ( (float)$archiveAmount - (float)$r->target ) / (float)$archiveAmount  * 100;
     // $varAmountPres= (100 - 50) / 100 *100 ;

       // $tblID="<input type='hidden' value='".'a_'.$r->dday."'      name='tblid_".$r->dday."'";
      $dayField="<input type='text' class='input_txt'       value='".$r->dday. '- '.date('D', $yrdata)."'  readonly=''   name='day_".$r->dday."' style='width:60px' ";
      $trgtField="<input type='text' class='input_amo fot '      value='".$r->target."'  id='target_".$r->dday."' name='target_".$r->dday."' style='width:150px;' ";
      $archmentField="<input type='text' class='input_amo foa '  value='".$archiveAmount."'  readonly='' id='archment_".$r->dday."' name='archment_".$r->dday."' style='width:150px' ";
      $varianceAmountField="<input type='text' class='input_amo foc'  value='".$varAmount."'  readonly='' id='var_".$r->dday."'  style='width:150px' ";
      $variancePreField="<input type='text' class='input_amo foc'  value='".number_format($varAmountPres,2)."%'  id='varPre_".$r->dday."' readonly=''  style='width:150px' ";
      $resonField="<input type='text' class='input_txt'     value='".$r->reason."'   name='reson_".$r->dday."' style='width:300px' ";

      $day = array("data"=>$dayField, "name","style"=>"text-align: center; width: 50px;");
      $trgt = array("data"=>$trgtField, "style"=>"text-align: center;  width: 150px;");
      $archment = array("data"=>$archmentField, "style"=>"text-align: center;  width: 150px;");
      $varians = array("data"=>$varianceAmountField, "style"=>"text-align: center;  width: 150px;");
      $variansPre = array("data"=>$variancePreField, "style"=>"text-align: center;  width: 150px;");
      $reason = array("data"=>$resonField,"style"=>"cursor : pointer;width: 5px;");


        // $r->action_date
      $this->table->add_row($day, $trgt, $archment,$varians,$variansPre,$reason);
    }
    $trgtTot="<input type='text' class='input_amo'      value='00.00' readonly='' id='target_tot' name='target_tot' style='width:150px' ";
    $archmentTot="<input type='text' class='input_amo'  value='".$totAmt."'  readonly='' id='archment_tot' name='archment_tot' style='width:150px' ";
    $xday = array("data"=>'Total :', "name","style"=>"text-align: center; width: 50px;");
    $xtrgt = array("data"=>$trgtTot, "style"=>"text-align: center;  width: 150px;");
    $xarchment = array("data"=>$archmentTot, "style"=>"text-align: center;  width: 150px;");
    $xreason = array("data"=>'',"style"=>"cursor : pointer;width: 5px;");
    $this->table->add_row($xday, $xtrgt, $xarchment,$xreason);
  }else{
    $status='NEW';
    $sumId='0';
    $totAmt=0;
    for($i=1; $i<=$dayCount; $i++){

      $today=$year."-".$month."-".$i;
      $yrdata= strtotime($today);

      $archiveAmount=number_format($this->getAchivementForDay($today , $this->sd['cl'] , $this->sd['branch']), 2, '.', '');
      $totAmt+=$archiveAmount;

      $dayField="<input type='text' class='input_txt'       value='".$i.'- '.date('D', $yrdata)."'  readonly=''   name='day_".$i."' style='width:60px' ";
      $trgtField="<input type='text' class='input_amo fot'      value='0.00' id='target_".$i."'   name='target_".$i."' style='width:150px' ";
      $archmentField="<input type='text' class='input_amo foa'  value='".$archiveAmount."'  readonly='' id='archment_".$i."' name='archment_".$i."' style='width:150px' ";
      $varianceAmountField="<input type='text' class='input_amo foc'  value='0.0' id='var_".$i."'  readonly=''  style='width:150px' ";
      $variancePreField="<input type='text' class='input_amo foc'  value='0.0' id='varPre_".$i."'  readonly=''  style='width:150px' ";
      $resonField="<input type='text' class='input_txt'     value=''   name='reson_".$i."' style='width:300px' ";

      $day = array("data"=>$dayField, "name","style"=>"text-align: center; width: 50px;");
      $trgt = array("data"=>$trgtField, "style"=>"text-align: center;  width: 150px;");
      $archment = array("data"=>$archmentField, "style"=>"text-align: center;  width: 150px;");
      $varians = array("data"=>$varianceAmountField, "style"=>"text-align: center;  width: 150px;");
      $variansPre = array("data"=>$variancePreField, "style"=>"text-align: center;  width: 150px;");
      $reason = array("data"=>$resonField,"style"=>"cursor : pointer;width: 5px;");


        // $r->action_date
      $this->table->add_row($day, $trgt, $archment,$varians,$variansPre,$reason);
    }

    $trgtTot="<input type='text' class='input_amo '      value='00.00' readonly=''  id='target_tot' name='target_tot' style='width:150px' ";
    $archmentTot="<input type='text' class='input_amo '   value='".$totAmt."'  readonly=''  id='archment_tot' name='archment_tot' style='width:150px' ";
    $xday = array("data"=>'Total :', "name","style"=>"text-align: center; width: 50px;");
    $xtrgt = array("data"=>$trgtTot, "style"=>"text-align: center;  width: 150px;");
    $xarchment = array("data"=>$archmentTot, "style"=>"text-align: center;  width: 150px;");
    $xreason = array("data"=>'',"style"=>"cursor : pointer;width: 5px;");
    $this->table->add_row($xday, $xtrgt, $xarchment,$xreason);

  }

  $a['T'] = $this->table->generate();        
  $a['SMID'] = $sumId;        
  $a['ST'] = $status;   

  $sql1x= $this->db->query("SELECT IFNULL( SUM(sd.`target`),0)  as tar ,
    IFNULL( SUM(sd.`achivement`),0) as arc
    FROM `t_sales_target_det` sd 
    WHERE `mmonth`<'".$year."-".$month."' 
    AND sd.cl='".$this->sd['cl']."'  AND sd.bc='".$this->sd['branch']."'");

  if($sql1x->num_rows() > 0){
    foreach($sql1x->result() as $r){
      $a['cumlTar'] =number_format($r->tar,2);  
      $a['cumlArch'] =number_format($r->arc,2); 
      $a['cumlVar'] =number_format((float)$r->arc - (float)$r->tar ,2) ; 
      $a['cumlVarPre'] =$r->arc < 1 ? 0.00 : number_format( ( (float)$r->arc - (float)$r->tar / (float)$r->arc ) * 100 ,2); 
    }     
  }else{
    $a['cumlTar'] ="0.00";  
    $a['cumlArch'] ="0.00";  
    $a['cumlVar'] ="0.00";  
    $a['cumlVarPre'] ="0.00%"; 
  }





  return json_encode($a); 


}


public function save(){
 $this->db->trans_begin();
 error_reporting(E_ALL); 
 function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
  throw new Exception($errMsg); 
}
set_error_handler('exceptionThrower'); 
try {

  $dayCount=$_POST['txtDayC'];
  $txtStatus=$_POST['txtStatus'];
  $txtSumid=$_POST['txtSumid'];
  $txtDate=explode('-', $_POST['txtDate']);
  $year=$txtDate[0];
  $month=$txtDate[1];


  $sql1="SELECT COUNT(*) FROM `t_sales_target_sum` as no 
  WHERE `mmonth`='".$_POST['txtDate']."'";


  if($txtStatus=='NEW'){
        // NEW
   $maxNo=$this->get_next_no();

   $t_sales_target_sum = array(
    'auto_no' =>$maxNo,
    'cl' => $this->sd['cl'] ,
    'bc' => $this->sd['branch'] ,
    'mmonth' => $_POST['txtDate'] ,
    'target_tot' => $_POST['archment_1'] ,
    'achivement_tot' => $_POST['reson_1'],
    'oc' => $this->sd['oc'] 
    );
   $this->db->insert('t_sales_target_sum', $t_sales_target_sum);




   for($i=1; $i<=$dayCount; $i++){

    $t_sales_target_det = array
(      't_sales_target' =>$maxNo ,
      'cl' => $this->sd['cl'] ,
      'bc' => $this->sd['branch'] ,
      'ddate' => $year."-".$month."-".$i,
      'yyear' => $year,
      'mmonth' => $_POST['txtDate'] ,
      'dday' => $i ,
      'target' => $_POST['target_'.$i] ,
      'achivement' => $_POST['archment_'.$i] ,
      'reason' => $_POST['reson_'.$i],
      'oc' => $this->sd['oc']
      );
    $this->db->insert('t_sales_target_det', $t_sales_target_det);
  }
}

if($txtStatus=='OLD'){
        // OLD
  for($i=1; $i<=$dayCount; $i++){

    $t_sales_target_det_UP = array(
      'target' => $_POST['target_'.$i] ,
      'achivement' => $_POST['archment_'.$i] ,
      'reason' => $_POST['reson_'.$i]
      );

//var_dump($t_sales_target_det_UP);
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $this->db->where('yyear', $year);
    $this->db->where('mmonth', $year."-".$month);
    $this->db->where('dday', "".$i);
    $this->db->where('t_sales_target', $txtSumid);
    $this->db->update('t_sales_target_det', $t_sales_target_det_UP);

  }
}

}catch(Exception $e){ 
  $this->db->trans_rollback();
  echo $e->getMessage()."Operation fail please contact admin"; 
}   

}

public function get_next_no(){ 
  $sql2="SELECT IFNULL(MAX(auto_no),0)+1 as no  FROM `t_sales_target_sum` ";
  $code=$this->db->query($sql2)->first_row()->no;
  return $code; 
}

public function getAchivement($date , $cluster , $branch , $table){ 
  $amount=0;
  $sql="SELECT  IFNULL(SUM(tbl.net_amount) , 0.0) as amount
  FROM ".$table." tbl 
  WHERE tbl.is_cancel='0' 
  AND tbl.cl='".$cluster."' 
  AND tbl.bc='".$branch."'
  AND tbl.ddate='".$date."'
  GROUP BY tbl.ddate
  ORDER BY tbl.ddate";
  
  if($this->db->query($sql)->num_rows()>0){
    $amount=$this->db->query($sql)->first_row()->amount;
  }

  return $amount; 
}

public function getAchivementForDay($date , $cluster , $branch ){ 

  $cashAmount=(float) $this->getAchivement($date , $cluster , $branch , 't_cash_sales_sum');
  $creditAmount=(float) $this->getAchivement($date , $cluster , $branch , 't_credit_sales_sum');
  $posAmount=(float) $this->getAchivement($date , $cluster , $branch , 't_pos_sales_sum');
  $returnAmount=(float) $this->getAchivement($date , $cluster , $branch , 't_sales_return_sum');

  $amount=($cashAmount + $creditAmount+ $posAmount) - $returnAmount;
  return $amount; 
}



public function PDF_report(){

  $pbranch=isset($_POST['branch']) ? $_POST['branch'] :$this->sd['branch'];
  $pcluster=isset($_POST['cluster']) ? $_POST['cluster']  :$this->sd['cl'];

  $sql="SELECT auto_no AS no , oc FROM t_sales_target_sum t 
  WHERE  t.cl='".$pcluster."' 
  AND t.bc='".$pbranch."'
  AND t.mmonth='".$_POST['txtmMonth']."'";
  
  if($this->db->query($sql)->num_rows()>0){
    $tsalesTarget=$this->db->query($sql)->first_row()->no;
    $occ=$this->db->query($sql)->first_row()->oc;
  }
  $r_detail['month']=$_POST['txtmMonth'];

  $this->db->select(array('discription'));
  $this->db->where("cCode",$occ);
  $r_detail['occ']=$this->db->get('users')->result();

  $this->db->select(array('code','description'));
  $this->db->where("code",$pcluster);
  $r_detail['cluster']=$this->db->get('m_cluster')->result();

  $this->db->select(array('bc','name','address','tp','fax','email'));
  $this->db->where("cl",$pcluster);
  $this->db->where("bc",$pbranch);
  $r_detail['branch']=$this->db->get('m_branch')->result();

  $this->db->select(array('ddate','dday',  'target',  'achivement',  'reason'));
  $this->db->where("cl",$pcluster);
  $this->db->where("bc",$pbranch);
  $this->db->where("t_sales_target",$tsalesTarget);
  $r_detail['salesTrget']=$this->db->get('t_sales_target_det')->result();

  $sql1x= $this->db->query("SELECT IFNULL( SUM(sd.`target`),0)  as tar ,
    IFNULL( SUM(sd.`achivement`),0) as arc
    FROM `t_sales_target_det` sd 
    WHERE `mmonth`<'".$_POST['txtmMonth']."' 
    AND sd.cl='".$pcluster."'  AND sd.bc='".$pbranch."'");

  if($sql1x->num_rows() > 0){
    foreach($sql1x->result() as $r){
      $r_detail['cumlTar'] =number_format($r->tar,2);  
      $r_detail['cumlArch'] =number_format($r->arc,2); 
      $r_detail['cumlVar'] =number_format((float)$r->arc - (float)$r->tar ,2) ; 
      $r_detail['cumlVarPre'] =$r->arc < 1 ? 0.00 : number_format( ( (float)$r->arc - (float)$r->tar / (float)$r->arc ) * 100 ,2); 
    }     
  }


  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];


  $r_detail['dd']=$_POST['dd'];

  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];

  $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    // }else{
    //   echo "<script>alert('No Data');</script>";
    // }
}










}




//--------------------cash Sales--------------
// SELECT tcash.`ddate` , SUM(tcash.`net_amount`) 
// FROM `t_cash_sales_sum` tcash
// WHERE tcash.`is_cancel`='0' AND tcash.cl='SC' AND tcash.bc='AM'
// AND tcash.`ddate` BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY tcash.`ddate`
// ORDER BY tcash.`ddate`


//--------------------credit Sales--------------
// SELECT tcredit.`ddate` , SUM(tcredit.`net_amount`) 
// FROM `t_credit_sales_sum` tcredit
// WHERE tcredit.`is_cancel`='0' AND tcredit.cl='SC' AND tcredit.bc='AM'
// AND tcredit.`ddate` BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY tcredit.`ddate`
// ORDER BY tcredit.`ddate` ASC



//--------------------pos Sales--------------
// SELECT tpos.`ddate` , SUM(tpos.`net_amount`) 
// FROM `t_pos_sales_sum` tpos
// WHERE tpos.`is_cancel`='0' AND tpos.cl='SH' AND tpos.bc='PD'
// AND tpos.`ddate` BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY tpos.`ddate`
// ORDER BY tpos.`ddate` ASC


//-------------------- Sales return--------------
// SELECT treturn.`ddate` , SUM(treturn.`net_amount`) 
// FROM `t_sales_return_sum` treturn
// WHERE treturn.`is_cancel`='0' AND treturn.cl='SH' AND treturn.bc='PD'
// AND treturn.`ddate` BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY treturn.`ddate`
// ORDER BY treturn.`ddate` ASC





















