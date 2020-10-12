<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_interest_calculation extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	
	$this->tb_sum = $this->tables->tb['t_interest_rate'];
	$this->tb_cal_date = $this->tables->tb['t_interest_cal_date'];
	$this->tb_sales = $this->tables->tb['t_sales_sum'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
    }
    
    public function base_details(){

    }
    
    public function load_customer()
    {
        $sql = "SELECT `code`,`name` FROM `".$this->tb_cus."`";
        $query = $this->db->query($sql);
        if($a['count'] = $query->num_rows){
            $a['emp'] = false;
            $a['det'] = $query->result();
            $a['com'] = $this->sd['bc'];
        }else{
            $a['emp'] = true;
        }

        echo json_encode($a); 
    }
    
    public function interest_calculation()
    {
        $num=$noof_months=$ins_rate=0;

        $sql="SELECT `date`,`customer`,`dr_trnce_no`,SUM(`dr_amount`)-SUM(`cr_amount`) AS bal
              FROM `t_customer_acc_trance`
              WHERE `customer`='".$_POST['customer']."' AND `cr_trnce_code`<>'INS_CAL'
              GROUP BY `dr_trnce_code`,`dr_trnce_no`
              HAVING bal>0";
        
        $qry=$this->db->query($sql);
        if($qry->num_rows() > 0) 
        {   
            foreach($qry->result() AS $r)
            {
                if(!empty($r->customer))
                {        
                $sql2="SELECT date FROM $this->tb_sales WHERE `no`='".$r->dr_trnce_no."' AND bc='".$this->sd['bc']."'";
                $qry2=$this->db->query($sql2);
                $ro=$qry2->first_row();

                $st=$ro->date;
                $ed=$_POST['date'];
                
                
                if($st!=$ed)
                {    
                $num=$this->days_between($st, $ed, 1,'d M Y');

                $noof_months=(int)($num/30);
                
                if($noof_months !='1' && $noof_months>=2)
                {
                    if($noof_months=='2')
                    {
                        $this->calculate_interest('0.02',$noof_months,$r->customer,$r->dr_trnce_no,$_POST['date'],$r->bal);
                    }
                    else
                    {   
                        $this->calculate_interest('0.05',$noof_months,$r->customer,$r->dr_trnce_no,$_POST['date'],$r->bal);
                    }     

                }

               } 
            }
          }
        }
        echo '1';
 
    }
    
    public function is_pross()
    {
        $sql="SELECT
                `invoice_no`
                , `customer`
            FROM
                `t_interest_rate`
            WHERE `invoice_no`=".$_POST['id']." AND bc='".$this->sd['bc']."'
            GROUP BY `invoice_no`";
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        if(!empty($r->invoice_no))
        {
            echo '1';
        }        
        else
        {
            echo '0';
        }    
    }

    private function calculate_interest($rate,$noof_months,$customer,$invoice_no,$date,$balance) 
   {
           $ins_rate=0;  
       
           $sql1="SELECT *
                 FROM
                 $this->tb_sum
                 WHERE `customer`='$customer' AND `invoice_no`='$invoice_no' AND `m_month`='$noof_months'";

            $qry1=$this->db->query($sql1);                
            $r1=$qry1->first_row();
            $a=$b=array();
            
            $ins_rate=$balance*$rate;
            if(empty($r->m_month))
            {
                    $b=array(
                    "date"=>$date,
                    "m_month"=>$noof_months,
                    "customer"=>$customer,
                    "invoice_no"=>$invoice_no,
                    "int_rate"=>$ins_rate,
                    "bc"=>$this->sd['bc']    
                    );
                
                    $a = array(
                    "id"=>$noof_months,
                    "module"=>'INS_CAL',
                    "customer"=>$customer,
                    "dr_trnce_code"=>"CR_SALES",
                    "dr_trnce_no"=>$invoice_no,
                    "cr_trnce_code"=>"INS_CAL",
                    "cr_trnce_no"=>$noof_months,
                    "cr_amount"=>0,
                    "dr_amount"=>$ins_rate,
                    "bc"=>$this->sd['bc'],
                    "oc"=>$this->sd['oc'],
                    "date"=>$date
                    );
            
                    $this->db->insert($this->tb_sum, $b);  
                    $this->db->insert($this->tb_acc_trance, $a);  
            }
            
            return true;
   } 
    
   function days_between($date1, $date2, $inclusive = true, $return_format = 'Y-m-d') 
      {
            $date_array = array();
            $output_array = array();

            $date1_ts = strtotime($date1);
            $date2_ts = strtotime($date2);
            $second_difference = $date2_ts-$date1_ts;
            $num_days = floor($second_difference / 86400);

            if (is_numeric($num_days) && $num_days > 0) {

            for ($i = 0; $i <= $num_days; $i++) {
                $new_day = $date1_ts + ($i*86400);
                $date_array[] = date($return_format,$new_day);
            }

            if (!$inclusive) {
                unset($date_array[0]);
                array_pop($date_array);
            }

            $output_array['number_of_days'] = sizeof($date_array);
            $output_array['dates_between'] = $date_array;  

        }
       
            
        return $num=$output_array['number_of_days'];        
        } 
        
     public function check_previous_process()
     {
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
        $previous_date = date('Y-m-d', strtotime($date .' -1 day'));
        
        $sql="SELECT `date` FROM $this->tb_cal_date WHERE `date`='$previous_date'";

        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        
        if(empty($r->date))
        {
            $a['prev']=$previous_date;
            $a['r']='1';
            
        }
        else
        {
            $a['r']='0';
        } 
        
        echo json_encode($a);
     } 

     public function check_is_process()
     {
        $sql="SELECT `date` FROM $this->tb_cal_date WHERE `date`='".$_POST['date']."'";
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        
        if(!empty($r->date))
        {
            echo '1';
        }
        else
        {
            $c=array();
            $c=array("date"=>$_POST['date']);
            $this->db->insert($this->tb_cal_date, $c);
            
            echo '0';
        }        
     }   
        
    
}