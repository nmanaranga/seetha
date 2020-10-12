<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_group_sales_budget extends CI_Model {
    
    private $sd;
    private $mtb;
    private $mod = '003';
    private $max_no;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
  $this->mtb = $this->tables->tb['t_group_sales_budget_sum'];

    }
    
    public function base_details(){

      $a['max_no']= $this->utility->get_max_no("t_group_sales_budget_sum","nno");
      return $a;
    
    }

    function load_all_data(){
      $code=$_POST['code'];
      $sql="SELECT 
					  fdate,
					  tdate,
					  category,
					  description 
					  FROM
					  r_groups 
					  JOIN r_sales_category 
				      ON r_sales_category.`code` = r_groups.`category`  WHERE r_groups.code='$code'";
		      $query= $this->db->query($sql);
		        if ($query->num_rows() > 0) {
		            $a['det'] = $query->result();
		        } else {
		            $a['det'] = 2;
		        }
    	echo json_encode($a);
    }


  public function save(){
  
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
        $_POST['cl']=$this->sd['cl'];
        $_POST['branch']=$this->sd['branch'];
        $_POST['oc']=$this->sd['oc']; 

        $t_group_sales_budget_sum=array(
       "cl"           =>$_POST['cl'],
       "bc"           =>$_POST['branch'],
       "nno"          =>$_POST['no'],
       "ddate"        =>$_POST['ddate'],
       "code"  		    =>$_POST['code'],
       "total_dr"     =>$_POST['dr_tot'],
       "total_cr"     =>$_POST['cr_tot'],
       "note"         =>$_POST['note'],
       "oc"           =>$_POST['oc'],
    );
    for($x = 0; $x<25; $x++){
      if(isset($_POST['0_'.$x],$_POST['n_'.$x],$_POST['1_'.$x],$_POST['2_'.$x])){
        if($_POST['0_'.$x] != "" && $_POST['n_'.$x] != "" && $_POST['1_'.$x] != "" && $_POST['2_'.$x] != ""){
          $t_group_sales_budget_det[]= array(
            "cl"         =>$_POST['cl'],
            "bc"         =>$_POST['branch'],
            "nno"        =>$_POST['no'],
            "acc_code"   =>$_POST['0_'.$x],
            "dr_amount"  =>$_POST['1_'.$x],
            "cr_amount"  =>$_POST['2_'.$x],
            
          ); 
        }
      }
    }

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('r_group_sales_budget')){ 
            $this->db->insert("t_group_sales_budget_sum",  $t_group_sales_budget_sum);
            if(count($t_group_sales_budget_det)){$this->db->insert_batch("t_group_sales_budget_det",$t_group_sales_budget_det);}  
            $this->utility->save_logger("SAVE",59,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          } 
        }else{
          if($this->user_permissions->is_edit('r_group_sales_budget')){ 
            $this->db->where('nno',$_POST['hid']);
            $this->db->update("t_group_sales_budget_sum", $t_group_sales_budget_sum);
            $this->set_delete();
            if(count($t_group_sales_budget_det)){$this->db->insert_batch("t_group_sales_budget_det",$t_group_sales_budget_det);}  
            $this->utility->save_logger("EDIT",59,$this->max_no,$this->mod); 
            echo $this->db->trans_commit();
          }else{
            echo "No permission to edit records";
            $this->db->trans_commit();
          }
        }        
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
    } 
  }


public function load(){

                $x=0;

                $this->db->select(array(
                    't_group_sales_budget_sum.cl' ,
                    't_group_sales_budget_sum.bc' ,
                    't_group_sales_budget_sum.nno',
                    't_group_sales_budget_sum.ddate',
                    't_group_sales_budget_sum.code',
                    'r_groups.name',
                    'r_groups.fdate',
                    'r_groups.tdate',
                    'r_groups.category AS cat_id',
                    'r_sales_category.description AS category',
                    't_group_sales_budget_sum.total_dr',
                    't_group_sales_budget_sum.total_cr',
                    't_group_sales_budget_sum.note',
                    't_group_sales_budget_sum.is_cancel'
                  ));

                $this->db->from('t_group_sales_budget_sum');
                $this->db->join('r_groups','t_group_sales_budget_sum.code=r_groups.code');
                $this->db->join('r_sales_category','r_sales_category.code=r_groups.category');
                $this->db->where('t_group_sales_budget_sum.cl',$this->sd['cl'] );
                $this->db->where('t_group_sales_budget_sum.bc',$this->sd['branch'] );
                $this->db->where('t_group_sales_budget_sum.nno',$_POST['id']);
                $query=$this->db->get();

                     if($query->num_rows()>0){
                      $a['sales']=$query->result();
                         }else{
                        $x=2;
                      }

                $this->db->select(array(
                    't_group_sales_budget_det.cl' ,
                    't_group_sales_budget_det.bc',
                    't_group_sales_budget_det.acc_code',
                    'm_account.description',
                    't_group_sales_budget_det.dr_amount',
                    't_group_sales_budget_det.cr_amount'
                  ));

                $this->db->from('t_group_sales_budget_det');
                $this->db->join('m_account','m_account.code=t_group_sales_budget_det.acc_code');
                $this->db->where('t_group_sales_budget_det.cl',$this->sd['cl'] );
                $this->db->where('t_group_sales_budget_det.bc',$this->sd['branch'] );
                $this->db->where('t_group_sales_budget_det.nno',$_POST['id']);
               
                $query=$this->db->get();

                     if($query->num_rows()>0){
                      $a['det']=$query->result();
                         }else{
                        $x=2;
                      }           

                        if($x==0){
                            echo json_encode($a);
                        }else{
                            echo json_encode($x);
                        }
        }

        public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if($this->user_permissions->is_delete('r_group_sales_budget')){
              $this->db->where('nno', $_POST['id']);
              $this->db->limit(1);
              $this->db->delete($this->mtb);
                echo $this->db->trans_commit();
            }else{
                $this->db->trans_commit();
                echo "No permission to delete records";
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }

    public function cancel(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if($this->user_permissions->is_delete('r_group_sales_budget')){
              
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('nno',$_POST['id']);
            $this->db->update('t_group_sales_budget_sum',array('is_cancel' => 1)); 
                echo $this->db->trans_commit();
            }else{
                $this->db->trans_commit();
                echo "No permission to delete records";
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }

    public function set_delete(){
    $this->db->where("no", $_POST['no']);
    $this->db->where("cl", $_POST['cl']);
    $this->db->where("bc", $_POST['branch']);
    $this->db->delete("t_group_sales_budget_det");

  }

   public function PDF_report(){
         
      $r_detail['no']=$_POST['nno'];
      $r_detail['page']=$_POST['page'];
      $r_detail['header']=$_POST['header'];
      $r_detail['orientation']=$_POST['orientation'];

      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $r_detail['branch']=$this->db->get('m_branch')->result();

      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$r_detail['ship_to_bc']);
      $r_detail['ship_branch']=$this->db->get('m_branch')->result();


      $sql="SELECT 
                s.CODE,
                NAME,
                fdate,
                tdate,
                s.total_dr,
                s.total_cr,
                category,
                description 
                FROM
                t_group_sales_budget_sum s 
                JOIN r_groups 
                  ON r_groups.`code` = s.code 
                JOIN r_sales_category 
                ON r_sales_category.`code` = r_groups.`category` 
                WHERE nno='".$_POST['nno']."' AND s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' 
                LIMIT 1";

      $r_detail['sum']=$this->db->query($sql)->result();
      

       $sql="SELECT acc_code,description,dr_amount,cr_amount 
       FROM t_group_sales_budget_det JOIN m_account ON m_account.`code`=t_group_sales_budget_det.`acc_code`
       WHERE nno=".$_POST['nno']." AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";

      $query=$this->db->query($sql);
      if($query->num_rows>0){
        $r_detail['det']=$query->result();
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
      
      }else{
        $r_detail['det']=2;
      }

      

    }

  }