<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_total_sale extends CI_Model 
{

    private $sd;
    private $w = 210;
    private $h = 297;
    
    private $mtb;
    private $tb_client;
    private $tb_branch;
    
    function __construct()
    {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
    }
    
    public function base_details()
    {
    	$this->load->model('r_sales_category');
        $a['sales_category'] = $this->r_sales_category->select();	
        $a['cluster']=$this->get_cluster_name();
        //$a['branch']=$this->get_branch_name();

        $a['d_cl']=$this->sd['cl'];
        $a['d_bc']=$this->sd['branch'];
        return $a;
        
        
    }

    public function get_cluster_name(){
        $sql="  SELECT `code`,description 
        FROM m_cluster m
        JOIN u_branch_to_user u ON u.cl = m.code
        WHERE user_id='".$this->sd['oc']."'
        GROUP BY m.code";
        $query=$this->db->query($sql);
        
        $s = "<select name='cluster' id='cluster' style='width:179px;'>";
        //$s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }


    public function get_branch_name(){
        $this->db->select(array('bc','name'));
        $query = $this->db->get('m_branch');

        $s = "<select name='branch' id='branch' style='width:179px;'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        return $s;
    }


    public function get_branch_name2(){
        $sql="  SELECT m.`bc`,name 
        FROM m_branch m
        JOIN u_branch_to_user u ON u.bc = m.bc
        WHERE user_id='".$this->sd['oc']."' AND m.cl='".$_POST['cl']."'
        GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' style='width:179px;'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        echo $s;
    }

    public function get_branch_name3(){
        $sql="  SELECT m.`bc`,name 
        FROM m_branch m
        JOIN u_branch_to_user u ON u.bc = m.bc
        WHERE user_id='".$this->sd['oc']."'
        GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' style='width:179px;'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        echo $s;
    }
    
   /* public function PDF_report($RepTyp=""){

      $r_detail['type']=$_POST['type'];
      $r_detail['page']=$_POST['page'];
      $r_detail['header']=$_POST['header'];
      $r_detail['orientation']="L";
      $r_detail['type']="";
      $r_detail['to']=$_POST['to'];
      $r_detail['from']=$_POST['from'];
      $cluster=$_POST['cluster'];
      $branch =$_POST['branch'];
      $emp    =$_POST['emp'];
      $to_date=$_POST['to'];
      $f_date =$_POST['from'];
      $r_detail['cluster']=$_POST['cluster'];
      $r_detail['branchs']=$_POST['branch'];
      $r_detail['emp']=$_POST['emp'];
      $r_detail['emp_des']=$_POST['emp_des'];

      if(!empty($cluster)){
        $cl=" AND c.cl='$cluster'";
        $cl1=" AND t.cl='$cluster'";
        $cl2=" AND tt.cl='$cluster'";
        $cl3=" AND cl='$cluster'";
    }else{
        $cl=" ";
        $cl1=" ";
        $cl2=" ";
        $cl3=" ";
    }

    if(!empty($branch)){
        $bc=" AND c.bc='$branch'";
        $bc1=" AND t.bc='$branch'";
        $bc2=" AND tt.bc='$branch'";
        $bc3=" AND bc='$branch'"; 
    }else{
        $bc=" ";
        $bc1=" ";
        $bc2=" ";
        $bc3=" "; 
    }


    if(!empty($emp)){
        $emp1=" AND c.rep='$emp'";
    }else{
        $emp1=" ";
    }

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();


    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['cluster']);
    $r_detail['clus']=$this->db->get('m_cluster')->result();

    $this->db->select(array('name','bc'));
    $this->db->where("bc",$_POST['branch']);
    $r_detail['bran']=$this->db->get('m_branch')->result();

    $op_code= $this->sd['oc'];
    $sql="

  


    SELECT CONCAT(t.bc,' - ',bb.name) AS bc,
    t.ddate, 
    IFNULL(cash_gross,0.00) AS cash_gross,
    IFNULL(IFNULL(cash_dis,0)+IFNULL(cash_deduct,0),0.00) AS cash_dis,
    IFNULL(cash_net, 0.00) AS cash_net,
    IFNULL(cash_add, 0.00) AS cash_add,
    IFNULL(credit_gross,0.00) AS credit_gross,
    IFNULL(IFNULL(credit_dis,0) + IFNULL(credit_deduct,0),0.00) AS credit_dis,
    IFNULL(credit_net,0.00) AS credit_net,
    IFNULL(credit_add, 0.00) AS credit_add,
    IFNULL(return_net,0.00) AS return_net,
    IFNULL(credit_add,0.00) + IFNULL(cash_add,0.00) AS tot_additional,
    ((IFNULL(cash_net, 0.00)+IFNULL(credit_net,0.00))-IFNULL(return_net,0.00)) AS total                       
    FROM (SELECT cl, bc, ddate FROM t_cash_sales_sum
        UNION ALL
        SELECT cl, bc, ddate FROM t_credit_sales_sum
        UNION ALL
        SELECT cl, bc, ddate FROM t_sales_return_sum
        UNION ALL
        SELECT cl, bc, ddate FROM t_credit_note
        UNION ALL
        SELECT cl, bc, ddate FROM t_pos_sales_sum)t 
        LEFT JOIN (
            SELECT  dd.ddate, IFNULL(c.cash_gross,0.00)+IFNULL(pos.pos_gross,0.00) as cash_gross, (IFNULL(c.cash_dis,0)+IFNULL(ss.amnt,0.00)+IFNULL(pos.pos_discount,0.00)-IFNULL(ssd.amnt,0.00))  AS cash_dis ,(IFNULL(c.cash_net,0)+IFNULL(pos.pos_net,0.00)+IFNULL(ssd.amnt,0.00))-IFNULL(ss.amnt,0.00) AS cash_net ,c.cash_add ,c.cash_deduct,c.foc 
            FROM (SELECT cl,bc,ddate FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
            UNION  ALL
            SELECT cl,bc,ddate FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 
            UNION ALL
            SELECT cl,bc,ddate FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 
            UNION ALL
            SELECT cl,bc,ddate FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
            GROUP BY ddate) dd 
            LEFT JOIN (SELECT cl,bc,ddate,
                SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS cash_gross , 
                SUM(IFNULL(discount_amount,0))  AS cash_dis,
                SUM(IFNULL(net_amount,0)) AS cash_net,
                SUM(IFNULL(additional_add,0)) AS cash_add,
                SUM(IFNULL(additional_deduct,0)) AS cash_deduct,
                SUM(IFNULL(total_foc_amount, 0)) AS foc 
                FROM t_cash_sales_sum
                WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP BY ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
                LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
                LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.ddate = dd.`ddate`    
                LEFT JOIN (SELECT cl,bc,ddate,sum(gross_amount) as pos_gross ,sum(total_discount) as pos_discount , sum(net_amount) as pos_net FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 AND is_cancel=0 GROUP BY ddate)pos ON pos.cl = dd.cl AND pos.bc=dd.bc AND pos.ddate=dd.ddate
                -- GROUP BY dd.ddate
            ) cs ON cs.ddate=t.ddate
            LEFT JOIN (
                SELECT  dd.ddate,c.credit_gross, (IFNULL(c.credit_dis,0)+IFNULL(ss.amnt,0))-IFNULL(ssd.amnt,0) as credit_dis ,(IFNULL(c.credit_net,0)-IFNULL(ss.amnt,0))+IFNULL(ssd.amnt,0) as credit_net ,c.credit_add ,c.credit_deduct,c.credit_foc 
                FROM (SELECT cl,bc,ddate FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                UNION  ALL
                SELECT cl,bc,ddate FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                UNION  ALL
                SELECT cl,bc,ddate FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 ) dd 
                LEFT JOIN (SELECT cl,bc,ddate,
                    SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS credit_gross , 
                    SUM(IFNULL(discount_amount,0))  AS credit_dis,
                    SUM(IFNULL(net_amount,0)) AS credit_net,
                    SUM(IFNULL(additional_add,0)) AS credit_add,
                    SUM(IFNULL(additional_deduct,0)) AS credit_deduct,
                    SUM(IFNULL(total_foc_amount, 0)) AS credit_foc 
                    FROM t_credit_sales_sum
                    WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP by ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
                    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='5' AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
                    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='5' AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.ddate = dd.`ddate`   
                    GROUP BY dd.ddate) cr ON cr.ddate=t.ddate
                    LEFT JOIN (SELECT  c.ddate, SUM(IFNULL(c.net_amount,0)) AS return_net FROM t_sales_return_sum c WHERE c.is_cancel='0' AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1  GROUP BY c.ddate) rt ON rt.ddate=t.ddate
                    JOIN m_branch bb ON bb.bc=t.bc
                    WHERE t.ddate BETWEEN '$f_date' AND '$to_date' $cl1 $bc1
                    GROUP BY t.ddate
                    ORDER BY t.bc, t.ddate ASC";*/

        /*$sql="SELECT CONCAT(t.bc,' - ',bb.name) AS bc,
                    t.ddate, 
                    IFNULL(cash_gross,0.00) AS cash_gross,
                    IFNULL(IFNULL(cash_dis,0)+IFNULL(cash_deduct,0),0.00) AS cash_dis,
                    IFNULL(cash_net, 0.00) AS cash_net,
                    IFNULL(cash_add, 0.00) AS cash_add,
                    IFNULL(credit_gross,0.00) AS credit_gross,
                    IFNULL(IFNULL(credit_dis,0) + IFNULL(credit_deduct,0),0.00) AS credit_dis,
                    IFNULL(credit_net,0.00) AS credit_net,
                    IFNULL(credit_add, 0.00) AS credit_add,
                    IFNULL(return_net,0.00) AS return_net,
                    (IFNULL(cash_net, 0.00)+IFNULL(credit_net,0.00))-IFNULL(return_net,0.00) AS total                     
                FROM (SELECT cl, bc, ddate FROM t_cash_sales_sum
                        UNION ALL
                      SELECT cl, bc, ddate FROM t_credit_sales_sum
                        UNION ALL
                      SELECT cl, bc, ddate FROM t_sales_return_sum
                       UNION ALL
                      SELECT cl, bc, ddate FROM t_credit_note
                      UNION ALL
                      SELECT cl, bc, ddate FROM t_pos_sales_sum)t 
                LEFT JOIN (
                    SELECT  dd.ddate, IFNULL(c.cash_gross,0.00)+IFNULL(pos.pos_gross,0.00) as cash_gross, IFNULL(c.cash_dis,0)+IFNULL(ss.amnt,0.00)+IFNULL(pos.pos_discount,0.00)  AS cash_dis ,(IFNULL(c.cash_net,0)+IFNULL(pos.pos_net,0.00))-IFNULL(ss.amnt,0.00) AS cash_net ,c.cash_add ,c.cash_deduct,c.foc 
                    FROM (SELECT cl,bc,ddate FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                            UNION  ALL
                          SELECT cl,bc,ddate FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 
                            UNION ALL
                           SELECT cl,bc,ddate FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                           GROUP BY ddate) dd 
                    LEFT JOIN (SELECT cl,bc,ddate,
                                      SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS cash_gross , 
                                      SUM(IFNULL(discount_amount,0))  AS cash_dis,
                                      SUM(IFNULL(net_amount,0)) AS cash_net,
                                      SUM(IFNULL(additional_add,0)) AS cash_add,
                                      SUM(IFNULL(additional_deduct,0)) AS cash_deduct,
                                      SUM(IFNULL(total_foc_amount, 0)) AS foc 
                                 FROM t_cash_sales_sum
                        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP BY ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
                    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
                    
                    LEFT JOIN (SELECT cl,bc,ddate,sum(gross_amount) as pos_gross ,sum(total_discount) as pos_discount , sum(net_amount) as pos_net FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 AND is_cancel=0 GROUP BY ddate)pos ON pos.cl = dd.cl AND pos.bc=dd.bc AND pos.ddate=dd.ddate
                    -- GROUP BY dd.ddate
                    ) cs ON cs.ddate=t.ddate
                LEFT JOIN (
                    SELECT  dd.ddate,c.credit_gross, IFNULL(c.credit_dis,0)+IFNULL(ss.amnt,0) as credit_dis ,IFNULL(c.credit_net,0)-IFNULL(ss.amnt,0) as credit_net ,c.credit_add ,c.credit_deduct,c.credit_foc 
                    FROM (SELECT cl,bc,ddate FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                            UNION  ALL
                          SELECT cl,bc,ddate FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 ) dd 
                    LEFT JOIN (SELECT cl,bc,ddate,
                                      SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS credit_gross , 
                                      SUM(IFNULL(discount_amount,0))  AS credit_dis,
                                      SUM(IFNULL(net_amount,0)) AS credit_net,
                                      SUM(IFNULL(additional_add,0)) AS credit_add,
                                      SUM(IFNULL(additional_deduct,0)) AS credit_deduct,
                                      SUM(IFNULL(total_foc_amount, 0)) AS credit_foc 
                                   FROM t_credit_sales_sum
                        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP by ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
                    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='5' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
                    GROUP BY dd.ddate) cr ON cr.ddate=t.ddate
                LEFT JOIN (SELECT  c.ddate, SUM(IFNULL(c.net_amount,0)) AS return_net FROM t_sales_return_sum c WHERE c.is_cancel='0' AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1  GROUP BY c.ddate) rt ON rt.ddate=t.ddate
                JOIN m_branch bb ON bb.bc=t.bc
                WHERE t.ddate BETWEEN '$f_date' AND '$to_date' $cl1 $bc1
                GROUP BY t.ddate
                
                ORDER BY t.bc, t.ddate ASC";*/
                
                
                
 /*               
                $data=$this->db->query($sql);  
                if($data->num_rows()>0){
                 $this->utility->save_logger("View",215,0,0);
                 $r_detail['r_data']=$data->result();
                 $exTy=($RepTyp=="")?'pdf':'excel';
                 $this->load->view($_POST['by'].'_'.$exTy,$r_detail);
             }else{
                echo "<script>alert('No data found');close();</script>";
            } 
            
        }*/

        public function get_branch(){	
          $q = $this->db->select(array('code', 'name'))
          ->where('code', $this->sd['bc'])
          ->get($this->tb_branch);        
          $s = "<select name='bc' id='bc'>";
        //$s .= "<option value='0'>---</option>";
          foreach($q->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."-".$r->name."</option>";
        }
        $s .= "</select>";        
        return $s;
    }



    public function PDF_report($RepTyp=""){

      $r_detail['type']=$_POST['type'];
      $r_detail['page']=$_POST['page'];
      $r_detail['header']=$_POST['header'];
      $r_detail['orientation']="L";
      $r_detail['type']="";
      $r_detail['to']=$_POST['to'];
      $r_detail['from']=$_POST['from'];
      $cluster=$_POST['cluster'];
      $branch =$_POST['branch'];
      $emp    =$_POST['emp'];
      $to_date=$_POST['to'];
      $f_date =$_POST['from'];
      $r_detail['cluster']=$_POST['cluster'];
      $r_detail['branchs']=$_POST['branch'];
      $r_detail['emp']=$_POST['emp'];
      $r_detail['emp_des']=$_POST['emp_des'];

      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $r_detail['branch']=$this->db->get('m_branch')->result();


      $this->db->select(array('description','code'));
      $this->db->where("code",$_POST['cluster']);
      $r_detail['clus']=$this->db->get('m_cluster')->result();

      $this->db->select(array('name','bc'));
      $this->db->where("bc",$_POST['branch']);
      $r_detail['bran']=$this->db->get('m_branch')->result();

      $sql="SELECT a.cl , a.bc , a.ddate ,IFNULL(c.`gross_amount`,0) AS cash_gross,IFNULL(c.`dis_amount`,0) AS cash_dis,IFNULL(c.`additonal_amount`,0) AS cash_add,IFNULL(c.`net_amount`,0) AS cash_net,
      IFNULL(cr.`gross_amount`,0) AS credit_gross,IFNULL(cr.`dis_amount`,0) AS credit_dis,IFNULL(cr.`additonal_amount`,0) AS credit_add,IFNULL(cr.`net_amount`,0) AS credit_net,
      IFNULL(rt.`net_amount`,0) AS return_net,IFNULL(rtd.`dis_amount`,0) AS ret_dis_lm,IFNULL(c.`additonal_amount`,0)+IFNULL(cr.`additonal_amount`,0) AS tot_additional,
      (IFNULL(c.`net_amount`,0)+IFNULL(cr.`net_amount`,0))-(IFNULL(rt.`net_amount`,0)+IFNULL(c.`additonal_amount`,0)+IFNULL(cr.`additonal_amount`,0)) AS total
      FROM 
      (SELECT cl,bc,ddate FROM(
      SELECT cl, bc, ddate FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch' GROUP BY cl, bc, ddate
      UNION ALL
      SELECT cl, bc, ddate FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch'  GROUP BY cl, bc, ddate
      UNION ALL
      SELECT cl, bc, ddate FROM t_sales_return_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch'  GROUP BY cl, bc, ddate
      UNION ALL
      SELECT cl, bc, ddate FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch'  GROUP BY cl, bc, ddate
      UNION ALL
      SELECT cl, bc, ddate FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch'  GROUP BY cl, bc, ddate ) b GROUP BY  b.cl , b.bc , b.ddate ) AS  a 
      LEFT JOIN (SELECT * FROM `r_total_sale_report` WHERE `type`=4 AND ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch' ) c ON a.cl=c.cl AND a.bc=c.bc AND a.ddate=c.ddate
      LEFT JOIN (SELECT * FROM `r_total_sale_report` WHERE `type`=5 AND ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch' ) cr ON a.cl=cr.cl AND a.bc=cr.bc AND a.ddate=cr.ddate    
      LEFT JOIN (SELECT * FROM `r_total_sale_report` WHERE `type`=8 AND ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch' ) rt ON a.cl=rt.cl AND a.bc=rt.bc AND a.ddate=rt.ddate
      LEFT JOIN (SELECT * FROM `r_total_sale_report` WHERE `type`=9 AND ddate BETWEEN '$f_date' AND '$to_date' AND cl='$cluster' AND bc='$branch' ) rtd ON a.cl=rtd.cl AND a.bc=rtd.bc AND a.ddate=rtd.ddate
      GROUP BY cl, bc, ddate";
    /*$sql="SELECT CONCAT(t.bc,' - ',bb.name) AS bc,
    t.ddate, 
    IFNULL(cash_gross,0.00) AS cash_gross,
    IFNULL(IFNULL(cash_dis,0)+IFNULL(cash_deduct,0),0.00) AS cash_dis,
    IFNULL(cash_net, 0.00) AS cash_net,
    IFNULL(cash_add, 0.00) AS cash_add,
    IFNULL(credit_gross,0.00) AS credit_gross,
    IFNULL(IFNULL(credit_dis,0) + IFNULL(credit_deduct,0),0.00) AS credit_dis,
    IFNULL(credit_net,0.00) AS credit_net,
    IFNULL(credit_add, 0.00) AS credit_add,
    IFNULL(return_net,0.00) AS return_net,
    IFNULL(credit_add,0.00) + IFNULL(cash_add,0.00) AS tot_additional,
    ((IFNULL(cash_net, 0.00)+IFNULL(credit_net,0.00))-IFNULL(return_net,0.00)) AS total                       
    FROM (SELECT cl, bc, ddate FROM t_cash_sales_sum
    UNION ALL
    SELECT cl, bc, ddate FROM t_credit_sales_sum
    UNION ALL
    SELECT cl, bc, ddate FROM t_sales_return_sum
    UNION ALL
    SELECT cl, bc, ddate FROM t_credit_note
    UNION ALL
    SELECT cl, bc, ddate FROM t_pos_sales_sum)t 
    


    LEFT JOIN (
    SELECT  dd.ddate, IFNULL(c.cash_gross,0.00)+IFNULL(pos.pos_gross,0.00) as cash_gross, (IFNULL(c.cash_dis,0)+IFNULL(ss.amnt,0.00)+IFNULL(pos.pos_discount,0.00)-IFNULL(ssd.amnt,0.00))  AS cash_dis ,(IFNULL(c.cash_net,0)+IFNULL(pos.pos_net,0.00)+IFNULL(ssd.amnt,0.00))-IFNULL(ss.amnt,0.00) AS cash_net ,c.cash_add ,c.cash_deduct,c.foc 
    FROM (SELECT cl,bc,ddate FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    UNION  ALL
    SELECT cl,bc,ddate FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 
    UNION ALL
    SELECT cl,bc,ddate FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 
    UNION ALL
    SELECT cl,bc,ddate FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    GROUP BY ddate) dd 
    LEFT JOIN (SELECT cl,bc,ddate,
    SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS cash_gross , 
    SUM(IFNULL(discount_amount,0))  AS cash_dis,
    SUM(IFNULL(net_amount,0)) AS cash_net,
    SUM(IFNULL(additional_add,0)) AS cash_add,
    SUM(IFNULL(additional_deduct,0)) AS cash_deduct,
    SUM(IFNULL(total_foc_amount, 0)) AS foc 
    FROM t_cash_sales_sum
    WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP BY ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.ddate = dd.`ddate`    
    LEFT JOIN (SELECT cl,bc,ddate,sum(gross_amount) as pos_gross ,sum(total_discount) as pos_discount , sum(net_amount) as pos_net FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 AND is_cancel=0 GROUP BY ddate)pos ON pos.cl = dd.cl AND pos.bc=dd.bc AND pos.ddate=dd.ddate

    ) cs ON cs.ddate=t.ddate
    



    LEFT JOIN (
    SELECT  dd.ddate,c.credit_gross, (IFNULL(c.credit_dis,0)+IFNULL(ss.amnt,0))-IFNULL(ssd.amnt,0) as credit_dis ,(IFNULL(c.credit_net,0)-IFNULL(ss.amnt,0))+IFNULL(ssd.amnt,0) as credit_net ,c.credit_add ,c.credit_deduct,c.credit_foc 
    FROM (SELECT cl,bc,ddate FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    UNION  ALL
    SELECT cl,bc,ddate FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    UNION  ALL
    SELECT cl,bc,ddate FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 ) dd 
    LEFT JOIN (SELECT cl,bc,ddate,
    SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS credit_gross , 
    SUM(IFNULL(discount_amount,0))  AS credit_dis,
    SUM(IFNULL(net_amount,0)) AS credit_net,
    SUM(IFNULL(additional_add,0)) AS credit_add,
    SUM(IFNULL(additional_deduct,0)) AS credit_deduct,
    SUM(IFNULL(total_foc_amount, 0)) AS credit_foc 
    FROM t_credit_sales_sum
    WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP by ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='5' AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='5' AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.ddate = dd.`ddate`   
    GROUP BY dd.ddate) cr ON cr.ddate=t.ddate
    

    LEFT JOIN (SELECT  c.ddate, SUM(IFNULL(c.net_amount,0)) AS return_net FROM t_sales_return_sum c WHERE c.is_cancel='0' AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1  GROUP BY c.ddate) rt ON rt.ddate=t.ddate
    JOIN m_branch bb ON bb.bc=t.bc
    WHERE t.ddate BETWEEN '$f_date' AND '$to_date' $cl1 $bc1
    GROUP BY t.ddate
    ORDER BY t.bc, t.ddate ASC";
*/
        /*$sql="SELECT CONCAT(t.bc,' - ',bb.name) AS bc,
                    t.ddate, 
                    IFNULL(cash_gross,0.00) AS cash_gross,
                    IFNULL(IFNULL(cash_dis,0)+IFNULL(cash_deduct,0),0.00) AS cash_dis,
                    IFNULL(cash_net, 0.00) AS cash_net,
                    IFNULL(cash_add, 0.00) AS cash_add,
                    IFNULL(credit_gross,0.00) AS credit_gross,
                    IFNULL(IFNULL(credit_dis,0) + IFNULL(credit_deduct,0),0.00) AS credit_dis,
                    IFNULL(credit_net,0.00) AS credit_net,
                    IFNULL(credit_add, 0.00) AS credit_add,
                    IFNULL(return_net,0.00) AS return_net,
                    (IFNULL(cash_net, 0.00)+IFNULL(credit_net,0.00))-IFNULL(return_net,0.00) AS total                     
                FROM (SELECT cl, bc, ddate FROM t_cash_sales_sum
                        UNION ALL
                      SELECT cl, bc, ddate FROM t_credit_sales_sum
                        UNION ALL
                      SELECT cl, bc, ddate FROM t_sales_return_sum
                       UNION ALL
                      SELECT cl, bc, ddate FROM t_credit_note
                      UNION ALL
                      SELECT cl, bc, ddate FROM t_pos_sales_sum)t 
                LEFT JOIN (
                    SELECT  dd.ddate, IFNULL(c.cash_gross,0.00)+IFNULL(pos.pos_gross,0.00) as cash_gross, IFNULL(c.cash_dis,0)+IFNULL(ss.amnt,0.00)+IFNULL(pos.pos_discount,0.00)  AS cash_dis ,(IFNULL(c.cash_net,0)+IFNULL(pos.pos_net,0.00))-IFNULL(ss.amnt,0.00) AS cash_net ,c.cash_add ,c.cash_deduct,c.foc 
                    FROM (SELECT cl,bc,ddate FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                            UNION  ALL
                          SELECT cl,bc,ddate FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 
                            UNION ALL
                           SELECT cl,bc,ddate FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                           GROUP BY ddate) dd 
                    LEFT JOIN (SELECT cl,bc,ddate,
                                      SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS cash_gross , 
                                      SUM(IFNULL(discount_amount,0))  AS cash_dis,
                                      SUM(IFNULL(net_amount,0)) AS cash_net,
                                      SUM(IFNULL(additional_add,0)) AS cash_add,
                                      SUM(IFNULL(additional_deduct,0)) AS cash_deduct,
                                      SUM(IFNULL(total_foc_amount, 0)) AS foc 
                                 FROM t_cash_sales_sum
                        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP BY ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
                    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
                    
                    LEFT JOIN (SELECT cl,bc,ddate,sum(gross_amount) as pos_gross ,sum(total_discount) as pos_discount , sum(net_amount) as pos_net FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 AND is_cancel=0 GROUP BY ddate)pos ON pos.cl = dd.cl AND pos.bc=dd.bc AND pos.ddate=dd.ddate
                    -- GROUP BY dd.ddate
                    ) cs ON cs.ddate=t.ddate
                LEFT JOIN (
                    SELECT  dd.ddate,c.credit_gross, IFNULL(c.credit_dis,0)+IFNULL(ss.amnt,0) as credit_dis ,IFNULL(c.credit_net,0)-IFNULL(ss.amnt,0) as credit_net ,c.credit_add ,c.credit_deduct,c.credit_foc 
                    FROM (SELECT cl,bc,ddate FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                            UNION  ALL
                          SELECT cl,bc,ddate FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 ) dd 
                    LEFT JOIN (SELECT cl,bc,ddate,
                                      SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS credit_gross , 
                                      SUM(IFNULL(discount_amount,0))  AS credit_dis,
                                      SUM(IFNULL(net_amount,0)) AS credit_net,
                                      SUM(IFNULL(additional_add,0)) AS credit_add,
                                      SUM(IFNULL(additional_deduct,0)) AS credit_deduct,
                                      SUM(IFNULL(total_foc_amount, 0)) AS credit_foc 
                                   FROM t_credit_sales_sum
                        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP by ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
                    LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='5' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' $cl2 $bc2 GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`    
                    GROUP BY dd.ddate) cr ON cr.ddate=t.ddate
                LEFT JOIN (SELECT  c.ddate, SUM(IFNULL(c.net_amount,0)) AS return_net FROM t_sales_return_sum c WHERE c.is_cancel='0' AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1  GROUP BY c.ddate) rt ON rt.ddate=t.ddate
                JOIN m_branch bb ON bb.bc=t.bc
                WHERE t.ddate BETWEEN '$f_date' AND '$to_date' $cl1 $bc1
                GROUP BY t.ddate
                
                ORDER BY t.bc, t.ddate ASC";*/




                $data=$this->db->query($sql);  
                if($data->num_rows()>0){
                  $r_detail['r_data']=$data->result();
                  $this->utility->save_logger("View",215,0,0);
                  $exTy=($RepTyp=="")?'pdf':'excel';
                  $this->load->view($_POST['by'].'_'.$exTy,$r_detail);
              }else{
                  echo "<script>alert('No data found');close();</script>";
              } 

          }




          public function Excel_report(){
            $this->PDF_report("Excel");
        }


















        public function get_loanNo()
        {
           $q = $this->db->select(array('loan_no'))
           ->where('bc', $this->sd['bc'])
           ->get('t_loan_sum');

           $s = "<select name='loan_no' id='loan_no'>";
           $s .= "<option value='0'>---</option>";
           foreach($q->result() as $r)
           {
            $s .= "<option title='".$r->loan_no."' value='".$r->loan_no."'>".$r->loan_no."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

    public function get_all_branch()
    {

      $q = $this->db->select(array('code', 'name', ))
      
      ->get($this->tb_branch);
      
      $s = "<select name='bc' id='bc'>";
        //$s .= "<option value='0'>---</option>";
      foreach($q->result() as $r)
      {
        	//echo $r->num_rows() ;
        $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."-".$r->name."</option>";
    }
    $s .= "</select>";

      // $a['d'] = $s ;
    echo json_encode($s);

}

public function set_group()
{

     	//echo $_POST['center'];
       // $query = $this->db->where("center_code", $_POST['center'])->get($this->tb_group);

	/*	$query ="SELECT 
					`m_group`.`code`, 
					`m_group`.`description`
FROM 
				  	 `m_group`
WHERE
m_group.center_code =  'KT001'
";
        
        $s = "<select name='group' id='group'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        
        return $s;*/

        $qry = $this->db

        ->select(array("code","description"))
        ->where("center_code",$_POST['center'])
           // ->where("mem_no","KT000001")
            //->where("is_approved",1)
        ->get($this->tb_group);

        $op="<select name='group' id='group'>";
        $op .="<option value='0'>---</option>"; 
        foreach($qry->result() as $r){

            $op .="<option title='".$r->description."'value='".$r->code."'>".$r->code."-".$r->description."</option>"; 

        }
        

        echo $op;
    }

    public function select_center(){
        $query = $this->db->where("bc", $this->sd['bc'])->get($this->tb_center);
        
        $s = "<select name='center' id='center'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    

    public function delete_totalSale(){
        $f_date=$_POST['f_date'];
        $to_date=$_POST['to_date'];
        $cl3=$_POST['cl3'];
        $bc3=$_POST['bc3'];


        $sql_del="DELETE FROM r_total_sale_report ";

        if($cl3!='0'){
          $sql_del.=" WHERE cl='$cl3' ";
      }
      if($bc3!='0'){
          $sql_del.=" AND bc='$bc3'";
      }

      $this->db->query($sql_del);
      echo json_encode(1); 
  }



  public function process_totalSale(){

    $f_date=$_POST['f_date'];
    $to_date=$_POST['to_date'];
    $cl3=$_POST['cl3'];
    $bc3=$_POST['bc3'];


    $sql_cash="INSERT INTO `r_total_sale_report`
    (`cl`,
    `bc`,
    `type`,
    `ddate`,
    `gross_amount`,
    `dis_amount`,
    `additonal_amount`,
    `deduct_amount`,
    `foc`,
    `net_amount`)
    (
    SELECT  dd.cl,
    dd.bc,
    '4' AS typ,
    dd.ddate, 
    IFNULL(c.cash_gross,0.00)+IFNULL(pos.pos_gross,0.00)-IFNULL(qq.amnt, 0) as cash_gross, 
    ((IFNULL(c.cash_dis,0)+IFNULL(c.cash_deduct, 0.00) +IFNULL(ss.amnt,0.00)+IFNULL(pos.pos_discount,0.00))-(IFNULL(ssd.amnt,0.00)+IFNULL(sddc.amnt,0.00)))  AS cash_dis ,
    IFNULL(c.cash_add, 0.00)AS cash_add ,
    IFNULL(c.cash_deduct, 0.00) AS cash_deduct,
    IFNULL(c.foc, 0.00) AS foc,
    ((IFNULL(c.cash_net,0)+IFNULL(pos.pos_net,0.00)+IFNULL(sddc.amnt,0.00)+IFNULL(ssd.amnt,0.00))-IFNULL(ss.amnt,0.00)) AS cash_net
    FROM (SELECT cl,bc,ddate FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' ";
    if($cl3!='0'){    
        $sql_cash.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate 
        UNION  ALL
        SELECT cl,bc,ddate FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_cash.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate 
        UNION ALL
        SELECT cl,bc,ddate FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_cash.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate 
        UNION ALL
        SELECT cl,bc,ddate FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_cash.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate
        UNION ALL
        SELECT cl,bc,ddate FROM t_sales_return_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_cash.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate) dd 
        LEFT JOIN (SELECT cl,bc,ddate,
        SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS cash_gross , 
        SUM(IFNULL(discount_amount,0))  AS cash_dis,
        SUM(IFNULL(net_amount,0)) AS cash_net,
        SUM(IFNULL(additional_add,0)) AS cash_add,
        SUM(IFNULL(additional_deduct,0)) AS cash_deduct,
        SUM(IFNULL(total_foc_amount, 0)) AS foc
        FROM t_cash_sales_sum
        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_cash.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate
        LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_cash.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND tt.bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate`  

        LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` ='400000001002' AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_cash.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND tt.bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate) AS qq ON qq.cl = dd.cl AND qq.bc = dd.bc AND qq.ddate = dd.`ddate`    

        LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_cash.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND tt.bc='$bc3' ";}
        $sql_cash.=" GROUP BY ddate) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.ddate = dd.`ddate`    
        LEFT JOIN (SELECT cl,bc,ddate,sum(gross_amount) as pos_gross ,sum(total_discount) as pos_discount , sum(net_amount) as pos_net FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_cash.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND bc='$bc3' ";}        
        $sql_cash.="  AND is_cancel=0 GROUP BY ddate)pos ON pos.cl = dd.cl AND pos.bc=dd.bc AND pos.ddate=dd.ddate

        LEFT JOIN (SELECT tt.cl,tt.bc,tt.`nno`,tt.ddate,tt.`rep`,SUM(IFNULL(tt.`discount`, 0) + IFNULL(ai.amount, 0)) AS amnt,IFNULL(ai.type,'001')AS type FROM t_sales_return_sum tt
        LEFT JOIN t_srn_ret_additional_item ai ON ai.`cl`=tt.`cl` AND ai.bc=tt.`bc` AND ai.`nno`=tt.`nno` and ai.type='001' WHERE ddate BETWEEN '$f_date' AND '$to_date' AND tt.is_approve='1' AND tt.is_cancel='0'  AND tt.sales_type='4' ";
        if($cl3!='0'){ $sql_cash.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_cash.=" AND tt.bc='$bc3' ";}        
        $sql_cash.="  GROUP BY tt.ddate HAVING type='001')sddc ON sddc.cl = dd.cl AND sddc.bc=dd.bc AND sddc.ddate=dd.ddate GROUP BY ddate )";

        $this->db->query($sql_cash);

        $sql_credit="INSERT INTO `r_total_sale_report`
        (`cl`,
        `bc`,
        `type`,
        `ddate`,
        `gross_amount`,
        `dis_amount`,
        `additonal_amount`,
        `deduct_amount`,
        `foc`,
        `net_amount`)
        (
        SELECT  dd.cl,
        dd.bc,
        '5' AS typ,
        dd.ddate,
        c.credit_gross-IFNULL(qq.amnt, 0) AS credit_gross, 
        (IFNULL(c.credit_dis,0)+IFNULL(c.credit_deduct,0)+IFNULL(ss.amnt,0)+IFNULL(gv.amnt,0))-(IFNULL(ssd.amnt,0)+IFNULL(sdd.amnt,0.00))as credit_dis ,
        IFNULL(c.credit_add,0) AS credit_add  ,
        IFNULL(c.credit_deduct,0) AS credit_deduct ,
        IFNULL(c.credit_foc,0) AS credit_foc,  
        (IFNULL(c.credit_net,0)-IFNULL(ss.amnt,0)-IFNULL(gv.amnt,0)-IFNULL(qq.amnt,0.00))+IFNULL(ssd.amnt,0) + IFNULL(sdd.amnt, 0.00) as credit_net 
        FROM (SELECT cl,bc,ddate FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_credit.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate 
        UNION  ALL
        SELECT cl,bc,ddate FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_credit.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate 
        UNION  ALL
        SELECT cl,bc,ddate FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_credit.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate 
        UNION  ALL
        SELECT cl,bc,ddate FROM t_sales_return_sum WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_credit.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate  ) dd 
        LEFT JOIN (SELECT cl,bc,ddate,
        SUM(IFNULL(gross_amount - `total_foc_amount`,0)) AS credit_gross , 
        SUM(IFNULL(discount_amount,0))  AS credit_dis,
        SUM(IFNULL(net_amount,0)) AS credit_net,
        SUM(IFNULL(additional_add,0)) AS credit_add,
        SUM(IFNULL(additional_deduct,0)) AS credit_deduct,
        SUM(IFNULL(total_foc_amount, 0)) AS credit_foc
        FROM t_credit_sales_sum
        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_credit.=" AND cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND bc='$bc3' ";}
        $sql_credit.=" GROUP by ddate) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.ddate=dd.ddate

        LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND  dis_type in('5','0') AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_credit.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND tt.bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.ddate = dd.`ddate` 

        LEFT JOIN (SELECT ss.cl,ss.bc,ddate,0 AS amnt FROM `t_voucher_gl_det` tt JOIN `t_voucher_gl_sum` ss ON ss.cl=tt.cl AND ss.bc=tt.bc AND ss.nno=tt.nno WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND is_cancel=0 AND ss.ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_credit.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND tt.bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate) AS gv ON gv.cl = dd.cl AND gv.bc = dd.bc AND gv.ddate = dd.`ddate`    
        
        LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_credit_note tt WHERE tt.`acc_code` ='400000001012' AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_credit.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND tt.bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate) AS qq ON qq.cl = dd.cl AND qq.bc = dd.bc AND qq.ddate = dd.`ddate`    


        LEFT JOIN (SELECT cl,bc,ddate,sum(amount) as amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type IN ('5', '0')   AND is_approve!='0' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_credit.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND tt.bc='$bc3' ";}
        $sql_credit.=" GROUP BY ddate) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.ddate = dd.`ddate`   

        LEFT JOIN (SELECT tt.cl,tt.bc,tt.`nno`,tt.ddate,tt.`rep`,SUM(IFNULL(tt.`discount`, 0) + IFNULL(ai.amount, 0)) AS amnt,IFNULL(ai.type,'001')AS type FROM t_sales_return_sum tt
        LEFT JOIN t_srn_ret_additional_item ai ON ai.`cl`=tt.`cl` AND ai.bc=tt.`bc` AND ai.`nno`=tt.`nno` AND ai.type='001' WHERE ddate BETWEEN '$f_date' AND '$to_date' AND tt.is_approve='1' AND tt.is_cancel='0' AND tt.sales_type='5' ";
        if($cl3!='0'){ $sql_credit.=" AND tt.cl='$cl3' ";}
        if($bc3!='0'){ $sql_credit.=" AND tt.bc='$bc3' ";}        
        $sql_credit.="  GROUP BY tt.ddate HAVING type='001')sdd ON sdd.cl = dd.cl AND sdd.bc=dd.bc AND sdd.ddate=dd.ddate  
        GROUP BY ddate )";

        $this->db->query($sql_credit);
/*
        $sql_ret="INSERT INTO `r_total_sale_report`
        (`cl`,
        `bc`,
        `type`,
        `ddate`,
        `gross_amount`,
        `dis_amount`,
        `additonal_amount`,
        `deduct_amount`,
        `foc`,
        `net_amount`)
        (
        SELECT  
        c.cl,
        c.bc,
        '8' AS typ,
        c.ddate,
        0.00 AS gross_amount,
        0.00 AS dis_amount,
        0.00 AS additonal_amount,
        0.00 AS deduct_amount,
        0.00 AS foc,
        SUM(IFNULL(c.gross_amount, 0)) AS return_net 
        FROM t_sales_return_sum c 
        WHERE c.is_cancel='0' 
        AND c.ddate BETWEEN '$f_date' AND '$to_date' AND c.is_cancel='0' AND is_approve=1";
        if($cl3!='0'){ $sql_ret.=" AND cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret.=" AND bc='$bc3' ";}
        $sql_ret.=" GROUP BY c.ddate)";
        $this->db->query($sql_ret);*/


        $sql_ret="INSERT INTO `r_total_sale_report`
        (`cl`,
        `bc`,
        `type`,
        `ddate`,
        `gross_amount`,
        `dis_amount`,
        `additonal_amount`,
        `deduct_amount`,
        `foc`,
        `net_amount`)
        (
        SELECT aa.cl,
        aa.bc,
        '8' AS typ,
        aa.ddate,
        0.00 AS gross_amount,
        0.00 AS dis_amount,
        0.00 AS additonal_amount,
        0.00 AS deduct_amount,
        0.00 AS foc,
        (IFNULL((rtn.return_net), 0) - IFNULL(rdn.amnt, 0)+IFNULL(rcn.amnt, 0)) AS return_net 
        FROM (
        SELECT cl,bc,ddate FROM `t_sales_return_sum` WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_ret.=" AND cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret.=" AND bc='$bc3' ";}
        $sql_ret.=" GROUP BY ddate";
        $sql_ret.=" UNION ALL
        SELECT cl,bc,ddate FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_ret.=" AND cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret.=" AND bc='$bc3' ";}
        $sql_ret.=" GROUP BY ddate";
        $sql_ret.=" UNION ALL
        SELECT cl,bc,ddate FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date' ";
        if($cl3!='0'){ $sql_ret.=" AND cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret.=" AND bc='$bc3' ";}
        $sql_ret.=" GROUP BY ddate";
        $sql_ret.=" )aa 
        LEFT JOIN (SELECT  
        c.cl,
        c.bc,
        '8' AS typ,
        c.ddate,
        0.00 AS gross_amount,
        0.00 AS dis_amount,
        0.00 AS additonal_amount,
        0.00 AS deduct_amount,
        0.00 AS foc,
        SUM(IFNULL(c.gross_amount, 0)) AS return_net 
        FROM t_sales_return_sum c 
        WHERE c.is_cancel='0' 
        AND c.ddate BETWEEN '$f_date' AND '$to_date' AND c.is_cancel='0' AND is_approve=1";
        if($cl3!='0'){ $sql_ret.=" AND cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret.=" AND bc='$bc3' ";}
        $sql_ret.=" GROUP BY c.ddate)rtn ON rtn.cl=aa.cl AND rtn.bc=aa.bc AND rtn.ddate=aa.ddate
        LEFT JOIN (SELECT  
        cl,
        bc,
        ddate,
        SUM(amount) AS amnt 
        FROM
        t_debit_note tt 
        WHERE tt.`acc_code`  ='400000003002'
        AND is_approve != '0' 
        AND is_cancel = 0 AND tt.ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_ret.=" AND cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret.=" AND bc='$bc3' ";}
        $sql_ret.=" GROUP BY ddate ) rdn ON rdn.cl=aa.cl AND rdn.bc=aa.bc AND rdn.ddate=aa.ddate
        LEFT JOIN (SELECT 
        cl,
        bc,
        ddate,
        SUM(amount) AS amnt 
        FROM
        t_credit_note tt 
        WHERE tt.`acc_code`  ='400000003002'
        AND is_approve != '0' 
        AND is_cancel = 0 
        AND tt.ddate BETWEEN '$f_date' AND '$to_date'";
        if($cl3!='0'){ $sql_ret.=" AND cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret.=" AND bc='$bc3' ";}
        $sql_ret.=" GROUP BY ddate ) rcn ON rcn.cl=aa.cl AND rcn.bc=aa.bc AND rcn.ddate=aa.ddate
        GROUP BY aa.ddate )";
        $this->db->query($sql_ret);

        $sql_ret_dis="INSERT INTO `r_total_sale_report`
        (`cl`,
        `bc`,
        `type`,
        `ddate`,
        `gross_amount`,
        `dis_amount`,
        `additonal_amount`,
        `deduct_amount`,
        `foc`,
        `net_amount`)
        (
        SELECT 
        cl,
        bc,
        typ,
        ddate,
        gross_amount,
        dis_amount,
        additonal_amount,
        deduct_amount,
        foc,
        return_net 
        FROM(
        SELECT  
        c.cl,
        c.bc,
        '9' AS typ,
        c.ddate,
        0.00 AS gross_amount,
        SUM(c.`discount`) AS dis_amount,
        0.00 AS additonal_amount,
        0.00 AS deduct_amount,
        0.00 AS foc,
        SUM(IFNULL(c.gross_amount, 0)) AS return_net 
        FROM t_sales_return_sum c 
        JOIN (SELECT * FROM t_credit_sales_sum WHERE ddate NOT BETWEEN '$f_date' AND '$to_date' AND bc='$bc3')cr  ON cr.cl=c.`cl` AND cr.bc=c.`bc` AND cr.nno=c.`inv_no` AND c.`sales_type`='5'
        WHERE c.is_cancel='0' 
        AND c.ddate BETWEEN '$f_date' AND '$to_date' AND c.is_cancel='0' AND c.is_approve=1";
        if($cl3!='0'){ $sql_ret_dis.=" AND c.cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret_dis.=" AND c.bc='$bc3' ";}
        $sql_ret_dis.=" GROUP BY c.ddate
        UNION ALL
        SELECT  
        c.cl,
        c.bc,
        '9' AS typ,
        c.ddate,
        0.00 AS gross_amount,
        SUM(c.`discount`) AS dis_amount,
        0.00 AS additonal_amount,
        0.00 AS deduct_amount,
        0.00 AS foc,
        SUM(IFNULL(c.gross_amount, 0)) AS return_net 
        FROM t_sales_return_sum c 
        JOIN (SELECT * FROM t_cash_sales_sum WHERE ddate NOT BETWEEN '$f_date' AND '$to_date' AND bc='$bc3')cr  ON cr.cl=c.`cl` AND cr.bc=c.`bc` AND cr.nno=c.`inv_no` AND c.`sales_type`='4'
        WHERE c.is_cancel='0' 
        AND c.ddate BETWEEN '$f_date' AND '$to_date' AND c.is_cancel='0' AND c.is_approve=1";
        if($cl3!='0'){ $sql_ret_dis.=" AND c.cl='$cl3' ";} 
        if($bc3!='0'){ $sql_ret_dis.=" AND c.bc='$bc3' ";}
        $sql_ret_dis.=" GROUP BY c.ddate)a GROUP BY a.ddate)";

        $this->db->query($sql_ret_dis);


        echo json_encode(1); 
    }

}

?>