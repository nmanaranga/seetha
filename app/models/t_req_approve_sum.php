<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_req_approve_sum extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['m_items'];
    $this->max_no = $this->utility->get_max_no("t_req_approve_sum", "nno");
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
    $a['cluster']=$this->sd['cl'];
    $a['branch']=$this->count_branches();
    $a['max_no'] = $this->utility->get_max_no("t_req_approve_sum", "nno");
    $a['det_box']=$this->pending_requisition();

    return $a;
    }   
    
    
    public function load_cluster(){
        $this->db->select(array('description'));
        $this->db->where('code',$this->sd['cl']);
        return $this->db->get('m_cluster')->row()->description; 
    }


    public function validation(){
        $status=1;
        $this->transfer_max_no=$this->utility->get_max_no("t_internal_transfer_order_sum","nno");

        $this->max_no_sub=$this->utility->get_max_no_in_type("t_internal_transfer_order_sum","sub_no","request");

        $this->max_no=$this->utility->get_max_no("t_req_approve_sum","nno");
        
        $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_req_approve_sum');
        if($check_is_delete!=1){
          return "Purchase requestion approve already canceled.";
        }

        $check_is_cancel=$this->validation->request_is_approve2($this->max_no);
        if($check_is_cancel!=1){
          return $check_is_cancel;
        }


        return $status;
    }

    public function save(){

       // $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 

        $validation_status=$this->validation();
        if($validation_status==1){
            for ($x = 0; $x < 25; $x++) {
                if (isset($_POST['0_'.$x], $_POST['approve_'.$x])) {
                    if ($_POST['0_'.$x] != "" && $_POST['approve_'.$x]!=""){
                            $approve = $_POST['approve_'.$x];
                            $approveItem = explode(",", $approve);

                            for($i = 0; $i < count($approveItem); $i++){
                                $sub = explode("~", $approveItem[$i]);
                               

                                if($sub[10]!=""){
                                    $this->db->where('cl',$sub[1]);
                                    $this->db->where('bc', $sub[2]);
                                    $this->db->where('nno',$sub[3]);
                                    $this->db->where('item', $sub[0]);
                                    $this->db->update('t_req_det', array("level_2_approve_qty"=>$sub[11]));                    

                                    $t_req_approve_additional_det[]= array(
                                        "cl"          => $this->sd['cl'],
                                        "bc"          => $this->sd['branch'],
                                        "nno"         => $this->max_no,
                                        "sub_cl"      => $sub[1],
                                        "sub_bc"      => $sub[2],
                                        "sub_nno"     => $sub[3],
                                        "item"        => $sub[0],
                                        "roq"         => $sub[5],
                                        "rol"         => $sub[4],
                                        "current_qty" => $sub[6],
                                        "request"     => $sub[7],
                                        "req_0_level_approve"  =>$sub[10],
                                        "approve" =>  $sub[11],
                                        "note" =>  $sub[12]
                                    );
                                  

                                    if($this->utility->get_is_store_in_branch('1')) {
                                        if($_POST['type'] == 1){

                                            $t_internal_transfer_order_det[]= array(
                                                "cl"          => $sub[1],
                                                "bc"          => $sub[2],
                                                "nno"         => $this->transfer_max_no,
                                                "item_code"   => $sub[0],
                                                "item_cost"   => 0,
                                                "qty"         => $sub[11],
                                                "type"        => "request",
                                                "sub_no"              => $this->max_no_sub,  
                                                "req_no"              => $sub[3],
                                                "req_2_approve_no"    => $this->max_no,

                                            );


                                             $t_internal_transfer_order_sum[] = array(
                                                "cl"                  => $sub[1],
                                                "bc"                  => $sub[2],
                                                "nno"                 => $this->transfer_max_no,
                                                "ddate"               => $_POST['date'],
                                                "ref_no"              => '',
                                                "to_bc"               => $this->utility->main_store_branch(),
                                                "note"                => '',  
                                                "oc"                  => $this->sd['oc'],
                                                "type"                => 'request',
                                                "sub_no"              => $this->max_no_sub,  
                                                "req_no"              => $sub[3],
                                                "req_2_approve_no"    => $this->max_no,
                                            );


                                            $t_internal_transfer_order_det1= array(
                                                "cl"          => $sub[1],
                                                "bc"          => $sub[2],
                                               
                                                "item_code"   => $sub[0],
                                                "item_cost"   => 0,
                                                "qty"         => $sub[11],
                                                "type"        => "request",
                                               
                                                "req_no"              => $sub[3],
                                                "req_2_approve_no"    => $this->max_no,

                                            );


                                             $t_internal_transfer_order_sum1 = array(
                                                "cl"                  => $sub[1],
                                                "bc"                  => $sub[2],
                                            
                                                "ddate"               => $_POST['date'],
                                                "ref_no"              => '',
                                                "to_bc"               => $this->utility->main_store_branch(),
                                                "note"                => '',  
                                                "oc"                  => $this->sd['oc'],
                                                "type"                => 'request',
                                             
                                                "req_no"              => $sub[3],
                                                "req_2_approve_no"    => $this->max_no,
                                            );

                                            if($_POST['hid'] != "0" || $_POST['hid'] != ""){
                                                $this->db->where("req_2_approve_no",$this->max_no);
                                                $this->db->where("cl", $sub[1]);
                                                $this->db->where("bc", $sub[2]);
                                                $this->db->where("item_code", $sub[0]);
                                                $this->db->where("type", 'request');
                                                $this->db->update("t_internal_transfer_order_det",$t_internal_transfer_order_det1);

                                                $this->db->where("req_2_approve_no",$this->max_no);
                                                $this->db->where("cl", $sub[1]);
                                                $this->db->where("bc", $sub[2]);
                                                $this->db->where("type", 'request');
                                                $this->db->update("t_internal_transfer_order_sum", $t_internal_transfer_order_sum1);

                                            }     

                                                                                    
                                        }

                                    }

                                } 

                                $sql="SELECT  d.cl ,d.bc,d.nno , COUNT( d.nno) AS tRec
                                      FROM t_req_det d  
                                      WHERE cl='".$sub[1]."'
                                      AND bc='".$sub[2]."' AND nno='".$sub[3]."'";
                                $req_qty = $this->db->query($sql)->first_row()->tRec;

                                $sql="SELECT d.cl ,d.bc,d.nno , COUNT( d.nno) AS Rec
                                      FROM t_req_det d  
                                      WHERE d.level_2_approve_qty !=0 
                                      AND cl='".$sub[1]."'
                                      AND bc='".$sub[2]."' AND nno='".$sub[3]."'";
                                $app_qty = $this->db->query($sql)->first_row()->Rec;

                                if((int)$req_qty==(int)$app_qty){
                                    $sql="UPDATE t_req_sum s
                                          SET s.is_level_2_approved=1
                                          WHERE cl='".$sub[1]."'
                                          AND bc='".$sub[2]."' AND nno='".$sub[3]."'";
                                    $this->db->query($sql);
                                }
                            }
                        $item_det=explode("~",$_POST['item_price_'.$x]);

                        $t_req_approve_det[]= array(
                            "cl"          => $this->sd['cl'],
                            "bc"          => $this->sd['branch'],
                            "nno"         => $this->max_no,
                            "code"        => $_POST['0_'.$x],
                            "request"     => $_POST['2_'.$x],
                            "roq"         => $_POST['3_'.$x],
                            "current_qty" => $_POST['4_'.$x],
                            "approve_qty" => $_POST['5_'.$x],
                            "cost"        => $item_det[0],
                            "last_price"  => $item_det[1],
                            "max_price"   => $item_det[2],
                            "last_pre"    => $item_det[3],
                            "max_pre"     => $item_det[4]
                        );

                    }
                }
            }

            $t_req_approve_sum = array(
                "cl"                  => $this->sd['cl'],
                "bc"                  => $this->sd['branch'],
                "nno"                 => $this->max_no,
                "ddate"               => $_POST['date'],
                "supplier"            => $_POST['supplier_id'],
                "oc"                  => $this->sd['oc'],
                "type"                => $_POST['type'],
                "is_level_3_approved" => isset($_POST['approve'])?1:0
            );

           

           
            //start update t_req_sum - is_level_2_approved 
                /*$sql="UPDATE t_req_sum s LEFT JOIN (SELECT  d.cl ,d.bc,d.nno , COUNT( d.nno) AS Rec
                      FROM t_req_det d  
                        WHERE ( d.level_2_approve_qty !=0)
                        GROUP BY d.cl ,d.bc,d.nno
                        ) AS A ON s.cl=a.cl  AND s.bc=a.bc AND  s.nno=a.nno
                        LEFT JOIN      
                        (SELECT  d.cl ,d.bc,d.nno , COUNT( d.nno) AS tRec
                        FROM t_req_det d  
                        GROUP BY d.cl ,d.bc,d.nno
                        ) AS t ON s.cl=t.cl  AND s.bc=t.bc AND  s.nno=t.nno
                        SET s.is_level_2_approved=1
                        WHERE a.rec = t.trec";
                $this->db->query($sql);*/
            //end update t_req_sum is_level_2_approved


            if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                if($this->user_permissions->is_add('t_req_approve_sum')){
                    $this->db->insert('t_req_approve_sum', $t_req_approve_sum);
                    if(isset($t_req_approve_det)){if(count($t_req_approve_det)){$this->db->insert_batch("t_req_approve_det",$t_req_approve_det);}}
                    if(isset($t_req_approve_additional_det)){if(count($t_req_approve_additional_det)){$this->db->insert_batch("t_req_approve_additional_det",$t_req_approve_additional_det);}}

                    if(isset($t_internal_transfer_order_det)){if(count($t_internal_transfer_order_det)){$this->db->insert_batch("t_internal_transfer_order_det",$t_internal_transfer_order_det);}}

                    if(isset($t_internal_transfer_order_sum)){if(count($t_internal_transfer_order_sum)){$this->db->insert_batch("t_internal_transfer_order_sum",$t_internal_transfer_order_sum);}}

                    $this->utility->save_logger("SAVE",32,$this->max_no,$this->mod);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit(); 
                }
            }else{
                if($this->user_permissions->is_edit('t_req_approve_sum')){
                $status=$this->validation->purchase_req_approve_status($_POST['hid']);
                if($status==1){

                    $this->db->where("cl",$this->sd['cl']);
                    $this->db->where("bc",$this->sd['branch']);
                    $this->db->where('nno',$_POST['hid']);
                    $this->db->update('t_req_approve_sum', $t_req_approve_sum);

                    $this->db->where("nno",$this->max_no);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->delete("t_req_approve_det");

                    $this->db->where("nno",$this->max_no);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->delete("t_req_approve_additional_det");

                    //if(isset($t_internal_transfer_order_det)){if(count($t_internal_transfer_order_det)){$this->db->insert_batch("t_internal_transfer_order_det",$t_internal_transfer_order_det);}}

//                    if(isset($t_internal_transfer_order_sum)){if(count($t_internal_transfer_order_sum)){$this->db->insert_batch("t_internal_transfer_order_sum",$t_internal_transfer_order_sum);}}



                    if(isset($t_req_approve_det)){if(count($t_req_approve_det)){$this->db->insert_batch("t_req_approve_det",$t_req_approve_det);}}
                    if(isset($t_req_approve_additional_det)){if(count($t_req_approve_additional_det)){$this->db->insert_batch("t_req_approve_additional_det",$t_req_approve_additional_det);}}
                    $this->utility->save_logger("UPDATE",32,$this->max_no,$this->mod);

                    echo $this->db->trans_commit();

                }else{
                    echo "This Requisition Approve cannot update";
                    $this->db->trans_commit();
                }

                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }  
            }

          // echo $this->db->trans_commit();
        }else{
            echo $validation_status;
            $this->db->trans_commit();
        }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
        } 
    }
    



    public function check_code(){
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);
        echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function delete(){
        if($this->user_permissions->is_delete('t_req_approve_sum')){ 
        $this->db->trans_begin();
            error_reporting(E_ALL); 
            function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
                throw new Exception($errMsg); 
            }
            set_error_handler('exceptionThrower'); 
            try {
                $status=$this->validation->purchase_req_approve_status($_POST['code']);
               
                if($status==1){
                    $data=array('is_cancel'=>'1');
                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('nno',$_POST['code']);
                    $this->db->update('t_req_approve_sum',$data);

                        $sql="SELECT item, d.sub_cl, d.sub_bc, d.sub_nno, d.roq,
                            d.rol, d.current_qty, d.request,
                            d.approve FROM t_req_approve_additional_det d
                            JOIN t_req_approve_det 
                        ON t_req_approve_det.`nno` = d.`nno` 
                        AND d.`cl` = t_req_approve_det.`cl` 
                        AND d.`bc` = t_req_approve_det.`bc` 
                            JOIN m_item 
                        ON m_item.`code` = item WHERE d.nno = '".$_POST['code']."' 
                        AND d.cl = '".$this->sd['cl']."' 
                        AND d.bc = '".$this->sd['branch']."' GROUP BY d.`nno`,
                        sub_bc,
                        item";
                
                        $query=$this->db->query($sql);

                        foreach($query->result() as $row){
                            $this->db->where('cl',$row->sub_cl);
                            $this->db->where('bc', $row->sub_bc);
                            $this->db->where('nno',$row->sub_nno);
                            $this->db->where('item', $row->item);
                            $this->db->update('t_req_det', array("level_2_approve_qty"=>(NULL)));

                            $this->db->where('cl',$row->sub_cl);
                            $this->db->where('bc', $row->sub_bc);
                            $this->db->where('nno',$row->sub_nno);
                            $this->db->update('t_req_sum', array("is_level_2_approved"=>0));

                            if($this->utility->get_is_store_in_branch('1')) {
                                //if($_POST['type'] == 1){
                                    $this->db->where("req_2_approve_no",$row->sub_nno);
                                    $this->db->where("cl", $row->sub_cl);
                                    $this->db->where("bc", $row->sub_bc);
                                    $this->db->where("type", 'request');
                                    $this->db->update("t_internal_transfer_order_sum", array("is_cancel"=>1));
                                //}
                            }

                       }


                        

                    $this->utility->save_logger("CANCEL",32,$this->max_no,$this->mod);    
                    echo $this->db->trans_commit();    
                }else{
                    $this->db->trans_commit();
                }
                
            }catch(Exception $e){ 
                $this->db->trans_rollback();
                echo $e->getMessage()."Operation fail please contact admin"; 
            }
            }else{
                echo "No permission to cancel records";
                $this->db->trans_commit();
            } 
        }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        $s     = "<select name='sales_ref' id='sales_ref'>";
        $s    .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
        }
        $s .= "</select>";
        return $s;
    }

    public function count_branches(){        
        $this->db->distinct();
        $this->db->select('BC');
        $this->db->where('cl',$this->sd['cl']);      
        $query = $this->db->get("t_req_det");
        return (float)$query->num_rows();   
    }

    public function num_of_branches(){        
        $this->db->distinct();
        $this->db->select('BC');
        $this->db->where('cl',$this->sd['cl']);    
        $this->db->where('Item',$_POST['item']);    
        $query = $this->db->get("t_req_det");
        echo json_encode($query->num_rows());   
    }


    public function pending_req(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $sup=$_POST['supplier'];
        $type=$_POST['type'];

        if($type!="2"){
            $sql="SELECT a.nno,
                         a.ddate,
                         a.supplier,
                         su.`name`
                  FROM m_item i
                  JOIN (SELECT item, level_1_approve_qty AS r_qty, s.type ,s.nno,d.supplier,s.ddate
                        FROM t_req_det d 
                        JOIN t_req_sum s ON (s.nno = d.nno) AND (s.cl = d.cl) AND (s.bc = d.bc) 
                        WHERE (s.is_cancel = '0') 
                            AND s.is_expire='0'
                            AND (s.is_level_1_approved = 1) 
                            AND (is_level_2_approved = 0)  
                            AND (s.`type` = '$type') 
                            AND (`level_2_approve_qty`=0)) a 
                        ON a.item = i.code 
                  JOIN m_supplier su ON su.`code` = a.supplier
                  WHERE (a.nno LIKE '%$_POST[search]%' 
                    OR a.supplier LIKE '%$_POST[search]%'
                    OR su.`name` LIKE '%$_POST[search]%'
                    )                   
                   ";
        }else{
            $sql="SELECT a.nno,
                         a.ddate,
                         a.supplier,
                         su.`name`
                  FROM m_item i
                  JOIN (SELECT item, level_1_approve_qty AS r_qty, s.type ,s.nno,d.supplier,s.ddate
                        FROM t_req_det d 
                        JOIN t_req_sum s ON (s.nno = d.nno) AND (s.cl = d.cl) AND (s.bc = d.bc) 
                        WHERE (s.is_cancel = '0') 
                            AND s.is_expire='0'
                            AND (s.is_level_1_approved = 1) 
                            AND (is_level_2_approved = 0)  
                            AND (s.`type` = '$type') 
                            AND (s.`cl` = '$cl') 
                            AND (s.`bc` = '$bc') 
                            AND (`level_2_approve_qty`=0)) a 
                        ON a.item = i.code 
                  JOIN m_supplier su ON su.`code` = a.supplier
                  WHERE (a.nno LIKE '%$_POST[search]%' 
                    OR a.supplier LIKE '%$_POST[search]%'
                    OR su.`name` LIKE '%$_POST[search]%'
                    )
                    ";
        }

        if($sup !=""){
            $sql .=" AND a.supplier='$sup' GROUP BY a.nno";
        }else{
            //$sql .="GROUP BY a.nno";
            $sql .=" GROUP BY a.supplier,a.nno";
        }

        $sql .=" ORDER BY a.nno";

        $query=$this->db->query($sql);

        $a  = "<table id='item_list' style='width : 100%' >";
        $a .= "<tr>";
        $a .= "<th class='tb_head_th'>Requation No</th>";
        $a .= "<th class='tb_head_th'>Date</th>";
        $a .= "<th class='tb_head_th'>Supplier Code</th>";
        $a .= "<th class='tb_head_th'>Supplier Name</th>";
        $a .= "</tr>";

        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "</tr>";
        
        foreach($query->result() as $r){
            $a .= "<tr class='cl'>";
            $a .= "<td>".$r->nno."</td>";
            $a .= "<td>".$r->ddate."</td>";
            $a .= "<td>".$r->supplier."</td>";
            $a .= "<td>".$r->name."</td>";
            $a .= "</tr>";
        }
        $a .= "</table>";
        echo $a;

    }



    public function load_sup_data(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $sup=$_POST['sup'];
        $type=$_POST['type'];

        if($type!="2"){
            $sql="SELECT i.code,
                    i.description,
                    i.model,
                    i.rol,
                    a.type,
                    i.roq,
                    IFNULL( im.bal_qty,0) bal_qty, 
                    SUM(a.r_qty) r_qty
                    FROM m_item i
                    LEFT JOIN (SELECT item, SUM(qty) AS bal_qty FROM qry_current_stock GROUP BY item) im ON  im.item=i.code
                    JOIN (SELECT item, level_1_approve_qty AS r_qty, s.type, d.auto_no FROM t_req_det d
                    JOIN t_req_sum s ON (s.nno=d.nno) AND (s.cl=d.cl) AND (s.bc=d.bc) WHERE (s.is_cancel='0') AND (s.is_level_1_approved=1) AND (is_level_2_approved=0) AND (d.supplier='$sup') AND (s.`type`='$type') AND (level_2_approve_qty=0)) a        
                    ON a.item=i.code  GROUP BY i.code
                    ORDER BY a.auto_no";
        }else{
            $sql="SELECT i.code,
                    i.description,
                    i.model,
                    i.rol,
                    a.type,
                    i.roq,
                    IFNULL( im.bal_qty,0) bal_qty, 
                    SUM(a.r_qty) r_qty
                    FROM m_item i
                    LEFT JOIN (SELECT item, SUM(qty) AS bal_qty FROM qry_current_stock GROUP BY item) im ON  im.item=i.code
                    JOIN (SELECT item, level_1_approve_qty AS r_qty, s.type, d.auto_no FROM t_req_det d
                    JOIN t_req_sum s ON (s.nno=d.nno) AND (s.cl=d.cl) AND (s.bc=d.bc) WHERE (s.is_cancel='0') AND (s.is_level_1_approved=1) AND (is_level_2_approved=0) AND (d.supplier='$sup') AND (s.cl='$cl') AND (s.bc='$bc')AND (s.`type`='$type') AND (level_2_approve_qty=0)) a        
                    ON a.item=i.code  GROUP BY i.code
                    ORDER BY a.auto_no";
        }


        $query=$this->db->query($sql);
         
       if($query->num_rows()>0){
          $a['det']=$query->result();
          echo json_encode($a);
       }else{
        echo json_encode(1);
       }
    }

    public function set_different_branches_values(){
        $_POST['cl']=$this->sd['cl'];
        $sql="SELECT `t_req_det`.`CL`,`t_req_det`.`BC`,`t_req_det`.`rol`,
            `t_req_det`.`roq`,`t_req_det`.`Item`,`t_req_det`.`cur_qty`,
            `t_req_det`.`total`,`m_item`.`model`,`m_item`.`description`
            FROM `t_req_det`
            LEFT JOIN `m_item` 
            ON `t_req_det`.`Item`=`m_item`.`code`
            WHERE `t_req_det`.`CL`='$_POST[cl]' AND `t_req_det`.`Item`='$_POST[item]'
            ORDER BY `t_req_det`.`Item`,`t_req_det`.`BC`";
            $query=$this->db->query($sql);
             
            if($query->num_rows()>0){
              $a['det']=$query->result();
              echo json_encode($a);
           }
           else{
            echo json_encode(1);
           }

    }

    public function get_item_total(){
        $this->db->select_sum('total');
        $this->db->where('cl',$this->sd['cl']);  
        $this->db->group_by("Item");   
        $this->db->order_by("Item", "asc"); 
      
        $query = $this->db->get('t_req_det');

        $a['tt']=$query->result();     
        echo json_encode($a);
    }



public function item_list_all($code,$type){
    if($type==2){
        $sql="SELECT  s.cl,
                mc.description cl_name,
                s.bc,
                mb.name bc_name,
                d.nno,
                IFNULL(br.rol,0) rol,
                IFNULL(br.roq,0) roq,
                IFNULL(im.bal_qty,0) AS bal_qty,
                level_1_approve_qty AS r_qty,
                d.level_0_approve_qty as lel_0
                FROM t_req_det d
                    JOIN t_req_sum s ON (s.nno=d.nno) AND (s.cl=d.cl) AND (s.bc=d.bc) 
                    JOIN m_cluster mc ON d.cl=mc.code
                    JOIN m_branch mb ON (mc.code=mb.cl) AND (d.bc=mb.bc)
                    LEFT JOIN m_item_rol br ON (d.cl=br.cl) AND (d.bc=br.bc) AND (d.item=br.code) 
                    LEFT JOIN (SELECT cl ,bc , item, qty AS bal_qty FROM qry_current_stock GROUP BY item) im
                        ON (d.cl=im.cl) AND (d.bc=im.bc) AND (d.item=im.item)
                    WHERE (s.is_level_1_approved=1) AND (s.is_level_2_approved=0) AND (d.item='$code') AND (s.is_cancel=0) AND (s.type = '$type') 
                    AND s.cl='".$this->sd['cl']."'
                    AND s.bc='".$this->sd['branch']."'
                    AND (d.`level_2_approve_qty`=0) ORDER BY cl,bc";
    }else{
        $sql="SELECT  s.cl,
                mc.description cl_name,
                s.bc,
                mb.name bc_name,
                d.nno,
                IFNULL(br.rol,0) rol,
                IFNULL(br.roq,0) roq,
                IFNULL(im.bal_qty,0) AS bal_qty,
                level_1_approve_qty AS r_qty,
                d.level_0_approve_qty as lel_0
                FROM t_req_det d
                    JOIN t_req_sum s ON (s.nno=d.nno) AND (s.cl=d.cl) AND (s.bc=d.bc) 
                    JOIN m_cluster mc ON d.cl=mc.code
                    JOIN m_branch mb ON (mc.code=mb.cl) AND (d.bc=mb.bc)
                    LEFT JOIN m_item_rol br ON (d.cl=br.cl) AND (d.bc=br.bc) AND (d.item=br.code) 
                    LEFT JOIN (SELECT cl ,bc , item, qty AS bal_qty FROM qry_current_stock GROUP BY item) im
                        ON (d.cl=im.cl) AND (d.bc=im.bc) AND (d.item=im.item)
                    WHERE (s.is_level_1_approved=1) AND (s.is_level_2_approved=0) AND (d.item='$code') AND (s.is_cancel=0) AND (s.type = '$type') AND (d.`level_2_approve_qty`=0) ORDER BY cl,bc";
    }    
    return $this->db->query($sql)->result();
}

public function item_load_list_all(){
        $i=0;
        $sql="SELECT item,
                    description,
                    t_req_approve_additional_det.branch,
                    t_req_approve_additional_det.no,
                    t_req_approve_additional_det.roq, 
                    t_req_approve_additional_det.rol, 
                    t_req_approve_additional_det.current_qty,
                    t_req_approve_additional_det.request,
                    t_req_approve_additional_det.approve                  
                FROM t_req_approve_additional_det
                JOIN t_req_approve_det ON t_req_approve_det.`nno`= t_req_approve_additional_det.`nno` 
                AND t_req_approve_additional_det.`cl`=t_req_approve_det.`cl`
                AND t_req_approve_additional_det.`bc`=t_req_approve_det.`bc`
                JOIN m_item ON m_item.`code` = item
                WHERE t_req_approve_additional_det.nno='".$_POST['id']."' AND item='".$_POST['item']."' 
                AND t_req_approve_additional_det.cl='".$this->sd['cl']."'
                AND t_req_approve_additional_det.bc='".$this->sd['branch']."'
                GROUP BY t_req_approve_additional_det.`nno`, branch,item";
        
        $query=$this->db->query($sql);

        $a  = "<table style='width: 100%'>";
        $a .= "<tr>";
        $a .= "<td>Item Code</td>";
        $a .= "<td><input type='text' class='hid_value' readonly='readonly' name='iCode' value='".$query->row()->item."'/></td>";
        $a .= "</tr>";
        $a .= "<tr>";
        $a .= "<td>Item Name</td>";
        $a .= "<td><input type='text' readonly='readonly' id='iName' value='".$query->row()->description."' name='iName' style='width:290px;' class='hid_value'/></td>";  
        $a .= "</tr>";
        $a .= "</table>";
        $a .= "<table id='item_list' style='width : 100%' >";
        $a .= "<tr>";
        $a .= "<th class='tb_head_th'>Branch</th>";
        $a .= "<th class='tb_head_th'>No</th>";
        $a .= "<th class='tb_head_th' style='display:none;'>Item</th>";
        $a .= "<th class='tb_head_th'>ROQ</th>";
        $a .= "<th class='tb_head_th'>ROL</th>";
        $a .= "<th class='tb_head_th'>Cur QTY</th>";
        $a .= "<th class='tb_head_th'>Request</th>";
        $a .= "<th class='tb_head_th'>Approve</th>";
         
        $a .= "</tr>";
            $a .= "<tr class='cl'>";
                   
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td style='display:none;'>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    
                $a .= "</tr>";
            foreach($query->result() as $r){
                
                $a .= "<tr class='cl'>";
                    $a .= "<td><span id='bc_".$i."' value='".$r->branch."' title='".$r->branch."'>".$r->branch."</span></td>";
                    $a .= "<td><span id='nn_".$i."' value='".$r->no."' title='".$r->no."'>".$r->no."</span></td>";
                    $a .= "<td style='display:none;'><span id='itm_".$i."' value='".$r->item."' title='".$r->item."'>".$r->item."</td>";
                    $a .= "<td align='center' id='roq_".$i."' >".$r->roq."</td>";
                    $a .= "<td align='center' id='rol_".$i."'>".$r->rol."</td>";
                    $a .= "<td align='center'><span id='cq_".$i."'>".$r->current_qty."</span> </td>";
                    $a .= "<td align='center' id='rqst_".$i."'>".$r->request."</td>";
                    $a .= "<td align='right' style='width:100px;'><input id=".$i."  type='text' class='ap g_input_num2 g_input_amo'  name=".$i."  value='".$r->approve."'  style='width:100px;background:#f9f9ec;' /></td>";
                    
                    
                $a .= "</tr>";
                $i++;
            }
        $a .= "</table>";
        echo $a;
}


public function qty_save(){
    var_dump("expression");
    exit();
        $this->db->trans_start();
        $data = array(
                'Approve_Qty' => $_POST['y'],
                'approved'=>1
                );

        $this->db->where('nno', $_POST['z']);
        $this->db->where('Item', $_POST['a']);
        $this->db->where('BC', $_POST['x']);
        $this->db->where('CL', $this->sd['cl']);       
        $this->db->update('t_req_det', $data);

        $data = array(               
                'approved'=>1
                );
       
        $this->db->where('nno', $_POST['z']);   
        $this->db->where('BC', $_POST['x']);
        $this->db->where('CL', $this->sd['cl']);
        $this->db->update('t_req_sum', $data);

        $this->db->trans_complete();
        //echo  json_encode(2);
}

// public function validation(){
//     $status=1;
//     $chek_supplier_validation=$this->validation->check_is_supplier($_POST['supplier_id']);
//     if($chek_supplier_validation!=1){
//         return json_encode("5");
//     }
//     return $status;
// }

public function update_check(){
    $validation_status=$this->validation();
    if($validation_status==1){
      $cl = $this->sd['cl'];
      $chk_qty=$_POST['chek'];
 
      if($chk_qty != '0'){
        $approve = $_POST['approve'];
        $stringsApprove = implode(",", $approve);
        $approveItem = explode(",", $stringsApprove);
          for($i = 0; $i < count($approveItem); $i++){
              $sub = explode("-", $approveItem[$i]);

          $sql="SELECT * 
              FROM `t_req_det` 
              WHERE `cl` ='$cl' AND `bc` ='$sub[0]' AND `item`='$sub[2]' AND `nno`='$sub[1]'
              LIMIT 1";   
              $query=$this->db->query($sql);
        }
        
          if($query->num_rows()>0)
          {
            $a['det']=$query->result();
            echo json_encode("10");
          }
        else
        {
          echo json_encode("2");
        }
    }else{
      echo json_encode("4");
    }
}else{
    echo $validation_status;
}
    
}


 public function load(){
       $x=0;
       
       $this->db->select(array(
          'nno' ,
          'ddate',
          't_req_approve_sum.supplier',
          'm_supplier.name',
          't_req_approve_sum.is_cancel',
          't_req_approve_sum.type',
          't_req_approve_sum.is_level_3_approved'
          
        ));

        $this->db->from('t_req_approve_sum');
        $this->db->join('m_supplier','m_supplier.code=t_req_approve_sum.supplier');
        $this->db->where('t_req_approve_sum.cl',$this->sd['cl'] );
        $this->db->where('t_req_approve_sum.bc',$this->sd['branch'] );
        $this->db->where('t_req_approve_sum.nno',$_POST['id']);
        $query=$this->db->get();

        if($query->num_rows()>0){
          $a['sum']=$query->result();
        }else{
          $x=2;
        }


        $this->db->select(array(
          't_req_approve_det.code' ,
          'm_item.description',
          'm_item.model',
          't_req_approve_det.request',
          't_req_approve_det.roq',
          't_req_approve_det.current_qty',
          't_req_approve_det.approve_qty',
          't_req_approve_det.cost',
          't_req_approve_det.last_price',
          't_req_approve_det.max_price',
          't_req_approve_det.last_pre',
          't_req_approve_det.max_pre'
        ));

        $this->db->from('t_req_approve_det');
        $this->db->join('m_item','m_item.code=t_req_approve_det.code');
        $this->db->where('t_req_approve_det.cl',$this->sd['cl'] );
        $this->db->where('t_req_approve_det.bc',$this->sd['branch'] );
        $this->db->where('t_req_approve_det.nno',$_POST['id']);
        $this->db->order_by("t_req_approve_det.auto_num");
        $query=$this->db->get();

        if($query->num_rows()>0){
          $a['det']=$query->result();
        }else{
          $x=2;
        }

        // $sql="select sub_no from t_internal_transfer_order_sum where cl='".$this->sd['cl']."' and bc='".$this->sd['branch']."' and req_2_approve_no='".$_POST['id']."'";
        // $query=$this->db->query($sql);
        // var_dump($query);
        // exit();    
        // $a['transfer']=$query->row()->sub_no;
        

        $sql="SELECT d.item, d.sub_cl, d.sub_bc, d.sub_nno, d.roq, d.rol, d.current_qty, d.request, d.approve, d.req_0_level_approve,d.note,
            m_cluster.description AS cl_name, m_branch.name AS bc_name
            FROM t_req_approve_additional_det d
            JOIN t_req_approve_sum s
              ON s.`nno` = d.`nno` 
              AND d.`cl` = s.`cl` 
              AND d.`bc` = s.`bc` 
          LEFT  JOIN m_item 
              ON m_item.`code` = item 
           LEFT JOIN m_cluster
              ON m_cluster.code=d.`sub_cl`
          LEFT  JOIN m_branch
              ON m_branch.bc=d.`sub_bc`
            WHERE d.nno = '".$_POST['id']."' 
            AND d.cl = '".$this->sd['cl']."' 
            AND d.bc = '".$this->sd['branch']."' 
            GROUP BY d.`nno`,
            d.sub_bc,
            d.item ";        
        
        $query=$this->db->query($sql);

        if($query->num_rows()>0){
          $a['table']=$query->result();
        }else{
          $x=2;
        }

        if($x==0){
            echo json_encode($a);
        }else{
            echo json_encode($x);
       }
    }


    public function load_req_details(){
        $code=$_POST['code'];
        $sup =$_POST['supplier'];
        $type =$_POST['type'];

        $sql ="SELECT cl.code cl, cl.description, IFNULL(i.qty,0) qty
              FROM m_cluster cl
              LEFT JOIN (SELECT cl, item, IFNULL(qty,0) AS qty  FROM qry_current_stock 
              WHERE item='$code') i
              ON i.cl=cl.code";      
        $a['qty_cl']=$this->db->query($sql)->result();

        $sql="SELECT purchase_price,min_price,max_price FROM m_item
              WHERE code='$code' LIMIT 1";
        $a['item_price']=$this->db->query($sql)->result();

        $sql="SELECT s.cl,c.description,SUM(d.`total_qty`) AS qty FROM t_req_sum s
              JOIN t_req_det d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno` 
              JOIN (SELECT `code`,description FROM m_cluster)c ON c.code=s.cl  
              WHERE d.`item`='$code' AND d.`supplier`='$sup' AND s.`is_level_1_approved`='1' 
              AND s.`is_cancel`='0' AND s.`is_level_2_approved`='0'AND s.`type`='$type' AND (d.`level_2_approve_qty`=0) GROUP BY s.`cl`";

        $a['req_det']=$this->db->query($sql)->result();    
        $a['tbl_det']=$this->item_list_all($code,$type);
        echo json_encode($a);
    }


    public function load_req_details2(){
        $code=$_POST['code'];
        $date=$_POST['date'];

        $sql ="SELECT cl.code cl, cl.description, IFNULL(i.qty,0) qty
               FROM m_cluster cl
               LEFT JOIN (SELECT cl, item, IFNULL(qty,0) AS qty  FROM qry_current_stock 
               WHERE item='$code') i
               ON i.cl=cl.code";        

        $a['qty_cl']=$this->db->query($sql)->result();
        echo json_encode($a);
    }

    public function pending_requisition(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $sql="SELECT * FROM t_req_approve_sum WHERE cl='$cl' AND bc='$bc' AND is_level_3_approved='0' AND is_cancel='0'";
        $query=$this->db->query($sql);

        $html="<div style='margin:5px auto;width:800px;border:1px solid #ccc;'><table><tr>";
        $html.="<td>Is User Available For Approve</td>";
        $html.="<td>&nbsp;</td>";
        $html.="<td></td>";
        $html.="</tr></table><hr>";
        $html.="<table border='1' style='width:100%;'>

                <tr><td colspan='5' style='background:#AAA;color:#fff;font-weight:bolder;'>PENDING REQUISITION APPROVE LIST</td></tr>
                <tr>
                <td style='background:#ccc;width:75px;'>&nbsp;Invoice No</td>
                <td style='background:#ccc; width:400px;'>&nbsp;Approved By</td>
                <td style='background:#ccc; width:100px;'>&nbsp;Date</td>
                <td style='background:#ccc; width:100px;'>&nbsp;Time</td>
                <td style='background:#ccc; width:100px;'>&nbsp;</td>
                </tr>";
        foreach($query->result() as $row){
            $time=explode(" ",$row->action_date);
            $html.="<tr>
                    <td style='width:75px;text-align:center;'>".$row->nno."</td>
                    <td >&nbsp;</td>
                    <td style='width:100px;'>&nbsp;".$row->ddate."</td>
                    <td style='width:100px;'>&nbsp;".$time[1]."</td>
                    <td style='width:100px;text-align:center;'><input type='button' title='Load' onclick='load_data_form(\"".$row->nno."\"),disable_form()' /></td>
                    </tr>";    
        }        
        $html.="</table>";
        $html.="</div>";
        return $html;
    }


    public function qty_branch(){

        $item = trim($_POST['item']);
        $cl = trim($_POST['cluster']); 

        $sql="SELECT item,sum(qty) as qty,m_branch.name,qry_current_stock.bc 
            FROM qry_current_stock 
            JOIN m_branch ON m_branch.bc = qry_current_stock.bc
            WHERE item = '$item'
            AND qry_current_stock.cl = '$cl'
            GROUP BY qry_current_stock.bc";


        $query=$this->db->query($sql);

        $a  = "<table id='item_list' style='width : 100%' >";
        $a .= "<tr>";
        $a .= "<th class='tb_head_th'>Branch Code</th>";
        $a .= "<th class='tb_head_th'>Branch Name</th>";
        $a .= "<th class='tb_head_th'>Qty</th>";
        $a .= "</tr>";

        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "</tr>";
        
        foreach($query->result() as $r){
            $a .= "<tr class='cl'>";   
            $a .= "<td>".$r->bc."</td>";        
            $a .= "<td>".$r->name."</td>";
            $a .= "<td>".$r->qty."</td>";
            $a .= "</tr>";
        }
        $a .= "</table>";
        echo $a;

    }


}

