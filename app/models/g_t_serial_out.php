<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class g_t_serial_out extends CI_Model {
    
  private $sd;
  private $max_no; 
  private $trans_code;

  function __construct(){
  	parent::__construct();
  	$this->sd = $this->session->all_userdata();
  	$this->load->database($this->sd['db'], true);
	}

 public function serial_item(){
          $item=$_POST['item'];
          $store=$_POST['stores'];
          $cl=$this->sd['cl'];
          $bc=$this->sd['branch'];
          $search=$_POST['search'];
          $trans_no=$_POST['trans_no'];
          $trans=$_POST['trans_code'];

          if($trans_no=='0'){
            
            if($trans=='8'){

              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                      FROM g_t_serial 
                      WHERE g_t_serial.`item`='$item' AND g_t_serial.`cl`='$cl' AND g_t_serial.`bc`='$bc' AND g_t_serial.`available`='1' 
                      AND g_t_serial.trans_type='$trans'
                      AND ( g_t_serial.serial_no LIKE '%$search%' OR g_t_serial.other_no1 LIKE '%$search%' OR g_t_serial.other_no2 LIKE '%$search%' ) 
                       ORDER BY auto_num
                      LIMIT 50";

            }
            else if($trans=='11' && $_POST['hid'] != '0'){
              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                     FROM g_t_serial_movement 
                     JOIN g_t_serial ON g_t_serial.serial_no =  g_t_serial_movement.serial_no 
                     WHERE g_t_serial_movement.trans_type='11' 
                     AND g_t_serial_movement.trans_no='$_POST[hid]'  
                     AND g_t_serial_movement.cl='$cl' 
                     AND g_t_serial_movement.bc='$bc' 
                     AND g_t_serial_movement.qty_out='1'
                     AND g_t_serial_movement.item='$item'
                     AND g_t_serial_movement.store_code='$store'
                     GROUP BY  g_t_serial_movement.serial_no
                      ORDER BY auto_no";

            }else if($trans=='12' && $_POST['hid'] != '0'){
              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                     FROM g_t_serial_movement 
                     JOIN g_t_serial ON g_t_serial.serial_no =  g_t_serial_movement.serial_no 
                     WHERE g_t_serial_movement.trans_type='12' 
                     AND g_t_serial_movement.trans_no='$_POST[hid]'  
                     AND g_t_serial_movement.cl='$cl' 
                     AND g_t_serial_movement.bc='$bc' 
                     AND g_t_serial_movement.qty_out='1'
                     AND g_t_serial_movement.item='$item'
                     AND g_t_serial_movement.store_code='$store'
                     GROUP BY  g_t_serial_movement.serial_no
                      ORDER BY auto_no";

           }else if($trans=='13' && $_POST['hid'] != '0'){
              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                     FROM g_t_serial_movement 
                     JOIN g_t_serial ON g_t_serial.serial_no =  g_t_serial_movement.serial_no 
                     WHERE g_t_serial_movement.trans_type='13' 
                     AND g_t_serial_movement.trans_no='$_POST[hid]'  
                     AND g_t_serial_movement.cl='$cl' 
                     AND g_t_serial_movement.bc='$bc' 
                     AND g_t_serial_movement.qty_out='1'
                     AND g_t_serial_movement.item='$item'
                     AND g_t_serial_movement.store_code='$store'
                     GROUP BY  g_t_serial_movement.serial_no
                      ORDER BY auto_no";

           }else if($trans=='14' && $_POST['hid'] != '0'){
              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                     FROM g_t_serial_movement 
                     JOIN g_t_serial ON g_t_serial.serial_no =  g_t_serial_movement.serial_no 
                     WHERE g_t_serial_movement.trans_type='14' 
                     AND g_t_serial_movement.trans_no='$_POST[hid]'  
                     AND g_t_serial_movement.cl='$cl' 
                     AND g_t_serial_movement.bc='$bc' 
                     AND g_t_serial_movement.qty_out='1'
                     AND g_t_serial_movement.item='$item'
                     AND g_t_serial_movement.store_code='$store'
                     GROUP BY  g_t_serial_movement.serial_no
                      ORDER BY auto_no";

            }else if($trans=='64'){
              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                     FROM g_t_serial_movement 
                     JOIN g_t_serial ON g_t_serial.serial_no =  g_t_serial_movement.serial_no 
                     WHERE g_t_serial_movement.trans_type='62' 
                     AND g_t_serial_movement.trans_no='$_POST[max_no]'   
                     AND g_t_serial_movement.qty_in='1'
                     AND g_t_serial.store_code='$_POST[v_store]'
                     AND g_t_serial_movement.item='$item'                    
                     GROUP BY  g_t_serial_movement.serial_no
                      ORDER BY auto_no";         

           
           }else{
              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                  FROM g_t_serial 
                  WHERE g_t_serial.`item`='$item' AND g_t_serial.`store_code`='$store' AND g_t_serial.`cl`='$cl' AND g_t_serial.`bc`='$bc' AND g_t_serial.`available`='1' 
                  AND ( g_t_serial.serial_no LIKE '%$search%' OR g_t_serial.other_no1 LIKE '%$search%' OR g_t_serial.other_no2 LIKE '%$search%' )
                   ORDER BY auto_num
                  LIMIT 50";
            }
            
          }else{

            if($trans=='117'){

              if($_POST['hid']!='0')
              { 
                //$type=$_POST['type'];
                $hid=$_POST['hid'];

                $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                FROM g_t_serial 
                INNER JOIN g_t_serial_movement ON g_t_serial_movement.`item` = g_t_serial.`item` AND g_t_serial.`serial_no`=g_t_serial_movement.`serial_no`
                WHERE g_t_serial_movement.`item`='$item' 
                AND g_t_serial_movement.trans_type='60' 
                AND g_t_serial_movement.trans_no='$trans_no'
                AND g_t_serial.`cl`='$cl' 
                AND g_t_serial.`bc`='$bc'
                AND g_t_serial.`available`='1'
                AND ( g_t_serial.serial_no LIKE '%$search%' OR g_t_serial.other_no1 LIKE '%$search%' OR g_t_serial.other_no2 LIKE '%$search%' )
                GROUP BY g_t_serial_movement.`serial_no` 
                -- ORDER BY auto_no
               
                UNION ALL

                SELECT g_t_serial.`date`,g_t_serial_movement_out.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                FROM g_t_serial_movement_out
                JOIN g_t_serial ON g_t_serial.`serial_no` = g_t_serial_movement_out.`serial_no`
                WHERE g_t_serial_movement_out.`trans_type`='117'
                AND g_t_serial_movement_out.`trans_no`='$hid'
                AND g_t_serial_movement_out.`item`='$item'
                AND g_t_serial_movement_out.`cl`='$cl'
                AND g_t_serial_movement_out.`bc`='$bc'
                AND ( g_t_serial.serial_no LIKE '%$search%' OR g_t_serial.other_no1 LIKE '%$search%' OR g_t_serial.other_no2 LIKE '%$search%' )
                GROUP BY g_t_serial_movement_out.`serial_no`
                -- ORDER BY g_t_serial_movement_out.auto_no
                ";

              }
              else
              {
                //$type=$_POST['type'];

                $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                FROM g_t_serial 
                INNER JOIN g_t_serial_movement ON g_t_serial_movement.`item` = g_t_serial.`item` AND g_t_serial.`serial_no`=g_t_serial_movement.`serial_no`
                WHERE g_t_serial_movement.`item`='$item' 
                AND g_t_serial_movement.trans_type='60' 
                AND g_t_serial_movement.trans_no='$trans_no'
                AND g_t_serial.`cl`='$cl' 
                AND g_t_serial.`bc`='$bc'
                AND g_t_serial.`available`='1'
                AND ( g_t_serial.serial_no LIKE '%$search%' OR g_t_serial.other_no1 LIKE '%$search%' OR g_t_serial.other_no2 LIKE '%$search%' )
                GROUP BY g_t_serial_movement.`serial_no` 
                 ORDER BY auto_no
                LIMIT 50";
              }

            }
            else
            {
              $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`,g_t_serial.`other_no1`,g_t_serial.`other_no2`
                    FROM g_t_serial 
                    WHERE g_t_serial.`item`='$item' AND g_t_serial.`store_code`='$store' AND g_t_serial.`cl`='$cl' AND g_t_serial.`bc`='$bc' AND g_t_serial.`available`='1' AND g_t_serial.trans_type='60' 
                    AND g_t_serial.trans_no='$trans_no'
                    AND ( g_t_serial.serial_no LIKE '%$search%' OR g_t_serial.other_no1 LIKE '%$search%' OR g_t_serial.other_no2 LIKE '%$search%' )
                     ORDER BY auto_num
                    LIMIT 50";
            }
          }

              $query = $this->db->query($sql);
              $a = "<table id='serial_item_list' style='width : 100%' >";
              $a .= "<thead><tr>";
              $a .= "<th class='tb_head_th'>Date</th>";
              $a .= "<th class='tb_head_th'>Serial No</th>";
              $a .= "<th class='tb_head_th'>Other No 1</th>";
              $a .= "<th class='tb_head_th'>Other No 2</th>";
              $a .= "</thead></tr>";

                    $a .= "<tr class='cl'>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "</tr>";

            foreach($query->result() as $r){
                    $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->date."</td>";
                    $a .= "<td>".$r->serial_no."</td>";
                    $a .= "<td>".$r->other_no1."</td>";
                    $a .= "<td>".$r->other_no2."</td>";
                    $a .= "</tr>";
            }
            $a .= "</table>";
        echo $a;
    }


  public function check_lasg_t_serial(){
      $this->db->select("serial_no");
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);  
      $this->db->where("item",$_POST['item']);
      $this->db->order_by("auto_num", "desc");
      $this->db->limit(1);
      $query=$this->db->get("g_t_serial");

      if($query->num_rows()>0){
        echo $query->first_row()->serial_no;
          }else{
         echo 0;
      }
    }


     public function check_is_serial_item(){
      /*$this->db->select(array('serial_no'));
      $this->db->where("code",$this->input->post('code'));
      $this->db->limit(1);
      echo  $this->db->get("m_item")->first_row()->serial_no;*/
      echo "1";
    }


     public function is_serial_available(){

      // if(!isset($_POST['nno'])){
      //     $this->db->select(array('available'));
      //     $this->db->where("serial_no",$_POST['serial']);
      //     $this->db->where("item",$_POST['item']);
      //     $query=$this->db->get("t_serial");

      //     if($query->num_rows()>0){
      //       foreach($query->result() as $row){
      //         echo $row->available;
      //       }
      //     }

      //   }else{
      //     $result=0;

      //     $this->db->where("serial_no",$_POST['serial']);
      //     $this->db->where("item",$_POST['item']);
      //     $this->db->where("trans_no",$_POST['nno']);
      //     //$this->db->where("trans_type",$_POST['type']);
      //     $query=$this->db->get("g_t_serial_movement_out")->num_rows();
          

      //     if($query==0){
      //       $this->db->select(array('available'));
      //       $this->db->where("serial_no",$_POST['serial']);
      //       $this->db->where("item",$_POST['item']);
      //       $qry=$this->db->get("g_t_serial");
      //       if($qry->num_rows()>0){
      //          foreach($qry->result() as $row){
      //             echo $row->available;
      //           }
      //       }else{
      //         //$result=$qry->first_row()->available;
      //       }
      //     }else{
      //         $result=1;
      //     }

      //     echo $result;
      // } 

        $item=$_POST['item'];
        $type=8;
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $serial_no=$_POST['serial'];
        $trans_no=$_POST['hid'];

          if($_POST['trans_code']=='8'){
            $trans_retun_code=$_POST['trans_code_return'];
            $trans_return_no=$_POST['trans_no_return'];
            $sql="SELECT g_t_serial.`date`,g_t_serial.`serial_no`
                FROM g_t_serial 
                INNER JOIN g_t_serial_movement_out ON g_t_serial_movement_out.`item` = g_t_serial.`item` AND g_t_serial.`serial_no`=g_t_serial_movement_out.`serial_no`
                WHERE g_t_serial_movement_out.`item`='$item' 
                AND g_t_serial_movement_out.trans_type='$trans_retun_code' 
                AND g_t_serial_movement_out.trans_no='$trans_return_no'
                AND g_t_serial.`cl`='$cl' 
                AND g_t_serial.`bc`='$bc'
                AND g_t_serial.`serial_no`='$serial_no'
                GROUP BY g_t_serial_movement_out.`serial_no` 
               
                UNION ALL

                SELECT g_t_serial.`date`,g_t_serial_movement.`serial_no`
                FROM g_t_serial_movement
                JOIN g_t_serial ON g_t_serial.`serial_no` = g_t_serial_movement.`serial_no`
                WHERE g_t_serial_movement.`trans_type`='8'
                AND g_t_serial_movement.`trans_no`='$trans_no'
                AND g_t_serial_movement.`item`='$item'
                AND g_t_serial_movement.`cl`='$cl'
                AND g_t_serial_movement.`bc`='$bc'
                AND g_t_serial_movement.`serial_no`='$serial_no'
                GROUP BY g_t_serial_movement.`serial_no`
              ";

              $query=$this->db->query($sql);


            // if($_POST['hid']!='0')
            // {
            //   //$this->db->select(array('item'));
            //   $this->db->where("cl",$this->sd['cl']);
            //   $this->db->where("bc",$this->sd['branch']);
            //   $this->db->where("trans_no",$trans_return_no);
            //   $this->db->where("trans_type",$trans_retun_code);
            //   $this->db->where("item",$_POST['item']);
            //   $this->db->where("serial_no",$_POST['serial']);
            //   $query= $this->db->get("g_t_serial_movement_out");
            // }
            // else
            // {
            //   //$this->db->select(array('item'));
            //   $this->db->where("cl",$this->sd['cl']);
            //   $this->db->where("bc",$this->sd['branch']);
            //   $this->db->where("trans_no",$trans_return_no);
            //   $this->db->where("trans_type",$trans_retun_code);
            //   $this->db->where("item",$_POST['item']);
            //   $this->db->where("serial_no",$_POST['serial']);
            //   $query= $this->db->get("g_t_serial_movement_out");
            // }

            

           

            if($query->num_rows()<=0){
              echo $result=0;
            }else{
              echo $result=1;
            }

          }else if($_POST['trans_code']=='43'){
            $sql="SELECT  s.`date`,s.`serial_no`
                  FROM g_t_serial s ";

            $query=$this->db->query($sql);
            if($query->num_rows()<=0){
              echo $result=0;
            }else{
              echo $result=1;
            }
          }else{

            $result=1;
            $this->db->where('available','1');
            $this->db->where("serial_no",$_POST['serial']);
            $this->db->where("item",$_POST['item']);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);  
            $query=$this->db->get("g_t_serial");

            if($query->num_rows()<=0){
              $result=0;
            }

            if($_POST['hid']!=0 && $result==0){

              $this->db->where("cl",$this->sd['cl']);
              $this->db->where("bc",$this->sd['branch']);
              $this->db->where("trans_no",$_POST['hid']);
              $this->db->where("trans_type",$_POST['trans_code']);
              $this->db->where("item",$_POST['item']);
              $this->db->where("serial_no",$_POST['serial']);
              $query=$this->db->get("g_t_serial_movement_out");

              if($query->num_rows()<=0){
                $result=0;
              }else{
                $result=1;
              }

            } 
            echo $result;     
          }     
 
  }

    public function check_last_serial(){
      $this->db->select("serial_no");
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);  
      $this->db->where("item",$_POST['item']);
      $this->db->order_by("auto_num", "desc");
      $this->db->limit(1);
      $query=$this->db->get("t_serial");

      if($query->num_rows()>0){
        echo $query->first_row()->serial_no;
          }else{
         echo 0;
      }
    }

}