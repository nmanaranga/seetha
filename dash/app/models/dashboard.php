<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends CI_Model {

  private $sd;


  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database('seetha', true);

  }

  public function base_details(){
    /*$a['s'] = "adasdasd";
    return $a;*/
  }

  public function init(){

    $month=$_POST['month'];

    if($month=="1"){
      $month=date("Y-m"); 
    }



    $sql1x= $this->db->query("select cl as cluster,sum(tar) as target 
      ,SUM(arc) as archi,SUM(cum_tar) cum_target,SUM(cum_arc) as cum_archi from 
      (SELECT sd.cl,
      IFNULL( SUM(sd.`target`),0) AS tar ,
      IFNULL( SUM(sd.`achivement`),0) AS arc ,
      0 AS cum_tar,
      0 AS cum_arc 
      FROM `t_sales_target_det` sd WHERE `mmonth`='".$month."' 
      GROUP BY  sd.cl ASC
      union all
      SELECT sd.cl,
      0 as tar,
      0 as arc,
      IFNULL( SUM(sd.`target`),0) AS cum_tar ,
      IFNULL( SUM(sd.`achivement`),0) AS cum_arc 
      FROM `t_sales_target_det` sd WHERE `mmonth`<'".$month."'
      GROUP BY  sd.cl ASC ) as appp
      group by cl");


    if($sql1x->num_rows() > 0){

      $tar = array();
      $arch = array();
      $cl = array();

      $i=0;
      $tbl="<tr>";
      $targetTotal=0;
      $ArchiveTotal=0;
      $varinceTotal=0;
      $cumTargetTotal=0;
      $cumArchiTotal=0;
      foreach($sql1x->result() as $r){
      // $a['cum0lTar'] =number_format($r->tar,2);  

        $tar[$i]=intval($r->target);  
        $arch[$i]=intval($r->archi); 
        $cl[$i]=$r->cluster;
        $i++;
        $targetTotal+=$r->target;
        $ArchiveTotal+=$r->archi;
        $cumTargetTotal+=$r->cum_target;
        $cumArchiTotal+=$r->cum_archi;

        $tbl.="<td>".$r->cluster."</td>";
        $tbl.="<td>".$r->target."</td>";
        $tbl.="<td>".$r->archi."</td>";
        $tbl.="<td>".((float)$r->target - (float)$r->archi)."</td>";
        $pre=$r->archi < 1  ? "0" :( (float)$r->target - (float)$r->archi )/(float)$r->archi *100;
        $tbl.="<td>".number_format($pre,2)."%</td>";

        $tbl.="<td>".$r->cum_target."</td>";
        $tbl.="<td>".$r->cum_archi."</td>";
        $tbl.="<td>".((float)$r->cum_target - (float)$r->cum_archi)."</td>";
        $prex=$r->cum_archi < 1  ? "0" :( (float)$r->cum_target - (float)$r->cum_archi )/(float)$r->cum_archi *100;
        $tbl.="<td>".number_format($prex,2)."%</td>";

        $tbl.="</tr>";


      }
      // $tbl.="<td>Totals :</td>";
      // $tbl.="<td>".$targetTotal."</td>";
      // $tbl.="<td>".$ArchiveTotal."</td>";
      // $tbl.="<td>".$targetTotal-$ArchiveTotal."</td>";
      // $tbl.="<td>fff</td>";

      // $tbl.="<td>".$cumTargetTotal."</td>";
      // $tbl.="<td>".$cumArchiTotal."</td>";
      // $tbl.="<td>".$cumTargetTotal-$cumArchiTotal."</td>";
      // $tbl.="<td>ff</td>";

      $a['tar'] =$tar; 
      $a['arch'] =$arch;
      $a['CL'] =$cl; 
      $a['tbl'] =$tbl; 

    }

// ---------------------Supervisor -------------------

    $sql1x= $this->db->query("select `code` as emp_code,name as emp_name, sum(tar) as target 
      ,SUM(arc) as archi,SUM(cum_tar) cum_target,SUM(cum_arc) as cum_archi from 
      (SELECT e.`code`,
      e.`name`,  
      0 AS tar ,
      0 AS arc ,
      IFNULL( SUM(sd.`target`),0) AS cum_tar,
      IFNULL( SUM(sd.`achivement`),0) AS cum_arc 
      FROM `t_sales_target_det` sd 
      left join m_branch b on sd.`bc`=b.`bc`
      left join `m_employee` e on b.`supervisor`=e.auto_no
      WHERE `mmonth`<'".$month."' 
      GROUP BY  e.auto_no ASC

      union all 

      SELECT e.`code`,
      e.`name`,  
      IFNULL( SUM(sd.`target`),0) AS tar ,
      IFNULL( SUM(sd.`achivement`),0) AS arc ,
      0 AS cum_tar,
      0 AS cum_arc 
      FROM `t_sales_target_det` sd 
      left join m_branch b on sd.`bc`=b.`bc`
      left join `m_employee` e on b.`supervisor`=e.auto_no
      WHERE `mmonth`='".$month."' 
      GROUP BY  e.auto_no ASC)
      as appp
      group by code
      ");


    if($sql1x->num_rows() > 0){

      $tar = array();
      $arch = array();
      $employee = array();

      $i=0;
      $tbly="<tr>";
      foreach($sql1x->result() as $r){
      // $a['cum0lTar'] =number_format($r->tar,2);  

        $tar[$i]=intval($r->target);  
        $arch[$i]=intval($r->archi); 
        $employee[$i]=$r->emp_name;
        $i++;

        $tbly.="<td>".$r->emp_name."</td>";
        $tbly.="<td>".$r->target."</td>";
        $tbly.="<td>".$r->archi."</td>";
        $tbly.="<td>".((float)$r->target - (float)$r->archi)."</td>";
        $pre=$r->archi < 1  ? "0" :( (float)$r->target - (float)$r->archi )/(float)$r->archi *100;
        $tbly.="<td>".number_format($pre,2)."%</td>";

        $tbly.="<td>".$r->cum_target."</td>";
        $tbly.="<td>".$r->cum_archi."</td>";
        $tbly.="<td>".((float)$r->cum_target - (float)$r->cum_archi)."</td>";
        $prex=$r->cum_archi < 1  ? "0" :( (float)$r->cum_target - (float)$r->cum_archi )/(float)$r->cum_archi *100;
        $tbly.="<td>".number_format($prex,2)."%</td>";

        $tbly.="</tr>";


      }
      $a['emp_tar'] =$tar; 
      $a['emp_arch'] =$arch;
      $a['emp_det'] =$employee; 
      $a['emp_tbl'] =$tbly; 
      $a['month'] =$month; 



    }

    return json_encode($a); 
  }


  public function load(){
   echo $this->init();  
 }












}

