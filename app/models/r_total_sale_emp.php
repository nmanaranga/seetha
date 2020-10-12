<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_total_sale_emp extends CI_Model 
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
        $s .= "<option value='0'>---</option>";
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
        $emp2=" AND t.rep='$emp'";
    }else{
        $emp1=" ";
        $emp2=" ";
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
    /*$op_code*/
    SELECT    CONCAT(t.bc,' - ',bb.name) AS bc,
    t.rep,
    CONCAT(t.rep,' - ',ee.name) AS emp_name,
    t.ddate, 
    IFNULL(cash_gross,0.00) AS cash_gross,
    IFNULL(cash_dis,0.00)+IFNULL(cash_deduct,0.00) AS cash_dis,
    IFNULL(cash_net, 0.00) AS cash_net,
    IFNULL(cash_add, 0.00) AS cash_add,
    IFNULL(credit_gross,0.00) AS credit_gross,
    IFNULL(credit_dis,0.00)+IFNULL(credit_deduct,0.00) AS credit_dis,
    IFNULL(credit_net,0.00) AS credit_net,
    IFNULL(credit_add, 0.00) AS credit_add,
    (IFNULL(return_net,0.00)+IFNULL(rcn.amnt,0.00))-IFNULL(rdn.amnt,0.00) AS return_net,
    IFNULL(return_dis, 0.00) AS return_dis,
    (IFNULL(cash_net, 0.00)+IFNULL(credit_net,0.00)+IFNULL(rdn.amnt,0.00))-(IFNULL(return_net,0.00)+IFNULL(cash_add,0.00)+IFNULL(credit_add,0.00)+IFNULL(rcn.amnt,0.00)) AS total,
    IFNULL(cash_add,0.00)+IFNULL(credit_add,0.00) AS add_tot                     
    FROM(SELECT cl, bc, ddate,rep FROM t_cash_sales_sum
    UNION ALL
    SELECT cl, bc, ddate,rep FROM t_credit_sales_sum
    UNION ALL
    SELECT cl, bc, ddate,rep FROM t_sales_return_sum
    UNION ALL
    SELECT cl, bc, ddate,employee as rep FROM t_credit_note
    UNION ALL
    SELECT cl, bc, ddate,employee as rep FROM t_debit_note
    UNION ALL
    SELECT cl, bc,ddate, salesman_id as rep FROM t_pos_sales_sum
    UNION ALL
    SELECT cl, bc,ddate, rep FROM t_sales_return_sum)t 

    LEFT JOIN (
    SELECT  dd.cl,dd.bc,dd.rep,dd.ddate,IFNULL(c.cash_gross,0.00)+IFNULL(pos.pos_gross,0.00) as cash_gross, (IFNULL(c.cash_dis,0)+IFNULL(ss.amnt,0)+IFNULL(pos_discount,0.00))-(IFNULL(ssd.amnt, 0)+IFNULL(sddc.amnt, 0)+IFNULL(qq.amnt,0)) AS cash_dis ,(IFNULL(c.cash_net,0)+IFNULL(pos_net,0)+IFNULL(ssd.amnt,0)+IFNULL(sddc.amnt, 0))-IFNULL(ss.amnt,0)-IFNULL(qq.amnt,0) AS cash_net ,c.cash_add ,c.cash_deduct,c.foc,sddc.amnt AS srn_dis_ca
    FROM (SELECT cl,bc,ddate,rep AS rep FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date'  $cl3 $bc3
    UNION  ALL
    SELECT cl,bc,ddate,employee AS rep FROM t_credit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'  $cl3 $bc3
    UNION ALL
    SELECT cl,bc,ddate,employee AS rep FROM t_debit_note WHERE is_approve!='0' AND ddate BETWEEN '$f_date' AND '$to_date'  $cl3 $bc3
    UNION ALL
    SELECT cl,bc,ddate,salesman_id as rep FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    UNION ALL
    SELECT cl,bc,ddate,rep FROM t_sales_return_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    GROUP BY rep
    ) dd 
    LEFT JOIN (SELECT cl,bc,ddate,rep,
    SUM(IFNULL(gross_amount,0.00)- IFNULL(`total_foc_amount`,0.00)) AS cash_gross , 
    SUM(IFNULL(discount_amount,0))  AS cash_dis,
    SUM(IFNULL(net_amount,0)) AS cash_net,
    SUM(IFNULL(additional_add,0)) AS cash_add,
    SUM(IFNULL(additional_deduct,0)) AS cash_deduct,
    SUM(IFNULL(total_foc_amount, 0)) AS foc
    
    FROM t_cash_sales_sum
    WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date'  $cl3 $bc3 GROUP BY rep) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.rep=dd.rep
    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.employee = dd.`rep`    
    
    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_credit_note tt WHERE tt.`acc_code`  ='400000001002' AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS qq ON qq.cl = dd.cl AND qq.bc = dd.bc AND qq.employee = dd.`rep`    
    
    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.employee = dd.`rep`    
    LEFT JOIN (SELECT cl,bc,ddate,salesman_id as rep,sum(gross_amount) as pos_gross ,sum(total_discount) as pos_discount , sum(net_amount) as pos_net FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 AND is_cancel=0 GROUP BY salesman_id)pos ON pos.cl = dd.cl AND pos.bc=dd.bc AND pos.rep=dd.rep
    LEFT JOIN (SELECT tt.cl,tt.bc,tt.`nno`,tt.ddate,tt.`rep`,SUM(IFNULL(tt.`discount`, 0) + IFNULL(ai.amount, 0)) AS amnt,IFNULL(ai.type,'001')AS type FROM t_sales_return_sum tt LEFT JOIN t_srn_ret_additional_item ai ON ai.`cl`=tt.`cl` AND ai.bc=tt.`bc` AND ai.`nno`=tt.`nno` AND ai.type='001' WHERE tt.ddate BETWEEN '$f_date' AND '$to_date' AND tt.sales_type='4' AND tt.is_approve='1' AND tt.is_cancel='0'  $cl2 $bc2 GROUP BY tt.rep  HAVING type='001' )AS sddc ON sddc.cl = dd.cl AND sddc.bc = dd.bc AND sddc.rep = dd.`rep` GROUP BY dd.rep
    ) cs ON (t.cl=cs.cl) AND (t.bc=cs.bc) AND (cs.rep=t.rep)


    LEFT JOIN (
    SELECT dd.cl,dd.bc,dd.rep, dd.ddate,c.credit_gross-IFNULL(qq.amnt, 0) as credit_gross,IFNULL(sdd.amnt,0) as srn_dis_cr, (IFNULL(c.credit_dis,0)+IFNULL(ss.amnt,0))-(IFNULL(ssd.amnt,0)+IFNULL(sdd.amnt,0))  AS credit_dis ,(IFNULL(c.credit_net,0)+IFNULL(ssd.amnt,0)+IFNULL(sdd.amnt,0))-(IFNULL(ss.amnt,0)+IFNULL(qq.amnt, 0)) AS credit_net ,c.credit_add ,c.credit_deduct,c.foc 
    FROM (SELECT cl,bc,ddate,rep AS rep FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    UNION  ALL
    SELECT cl,bc,ddate,employee as rep FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 
    UNION  ALL
    SELECT cl,bc,ddate,employee as rep FROM t_debit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
    UNION  ALL
    SELECT cl,bc,ddate,rep FROM t_sales_return_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 ) dd 
    LEFT JOIN (SELECT cl,bc,ddate,rep,
    SUM(IFNULL(gross_amount,0.00) - IFNULL(`total_foc_amount`,0.00)) AS credit_gross , 
    SUM(IFNULL(discount_amount,0))  AS credit_dis,
    SUM(IFNULL(net_amount,0)) AS credit_net,
    SUM(IFNULL(additional_add,0)) AS credit_add,
    SUM(IFNULL(additional_deduct,0)) AS credit_deduct,
    SUM(IFNULL(total_foc_amount, 0)) AS foc 
    FROM t_credit_sales_sum
    WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP BY rep) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.rep=dd.rep
    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type in('5','0') AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.employee = dd.`rep`

    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_credit_note tt WHERE tt.`acc_code`='400000001012' AND dis_type in('5','0') AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS qq ON qq.cl = dd.cl AND qq.bc = dd.bc AND qq.employee = dd.`rep`

    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_debit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type IN ('5', '0')   AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS ssd ON ssd.cl = dd.cl AND ssd.bc = dd.bc AND ssd.employee = dd.`rep`    
    LEFT JOIN (SELECT tt.cl,tt.bc,tt.`nno`,tt.ddate,tt.`rep`,SUM(IFNULL(tt.`discount`, 0) + IFNULL(ai.amount, 0)) AS amnt, IFNULL(ai.type, '001') AS type  FROM t_sales_return_sum tt LEFT JOIN t_srn_ret_additional_item ai ON ai.`cl`=tt.`cl` AND ai.bc=tt.`bc` AND ai.`nno`=tt.`nno` AND ai.type='001' WHERE tt.ddate BETWEEN '$f_date' AND '$to_date' AND tt.sales_type='5' AND tt.is_approve='1' AND tt.is_cancel='0' $cl2 $bc2 GROUP BY tt.rep   HAVING TYPE = '001' )AS sdd ON sdd.cl = dd.cl AND sdd.bc = dd.bc AND sdd.rep = dd.`rep` GROUP BY dd.rep
    ) cr ON (t.cl=cr.cl) AND (t.bc=cr.bc) AND (cr.rep=t.rep)

    LEFT JOIN (SELECT cl, bc, c.ddate, SUM(IFNULL(c.gross_amount,0)) AS return_net, rep FROM t_sales_return_sum c WHERE c.is_cancel='0' AND is_approve=1 AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1   GROUP BY cl, bc,c.rep) rt ON (rt.cl=t.cl) AND (rt.bc=t.bc) AND (rt.rep=t.rep)
    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_credit_note tt WHERE tt.`acc_code`='400000003002' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS rcn ON rcn.cl = t.cl AND rcn.bc = t.bc AND rcn.employee = t.`rep`
    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_debit_note tt WHERE tt.`acc_code`='400000003002' AND is_cancel=0 AND is_approve!='0' AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS rdn ON rdn.cl = t.cl AND rdn.bc = t.bc AND rdn.employee = t.`rep`
    
    LEFT JOIN (
    SELECT 
    cl,
    bc,
    ddate,
    return_dis,
    rep 
    FROM
    (SELECT c.cl,c.bc,c.ddate,SUM(IFNULL(c.`discount`, 0)) AS return_dis,c.rep FROM t_sales_return_sum c 
    JOIN 
    (SELECT * FROM t_cash_sales_sum WHERE ddate NOT BETWEEN '$f_date' AND '$to_date' $cl3 $bc3) cr 
    ON cr.cl = c.`cl` AND cr.bc = c.`bc` AND cr.nno = c.`inv_no` AND c.`sales_type` = '4' 
    WHERE c.is_cancel = '0' AND c.is_approve = 1 AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1
    GROUP BY c.cl,c.bc,c.rep 
    UNION ALL 
    SELECT c.cl,c.bc,c.ddate,SUM(IFNULL(c.`discount`, 0)) AS return_dis,c.rep 
    FROM t_sales_return_sum c 
    JOIN (SELECT * FROM t_credit_sales_sum WHERE ddate NOT BETWEEN '$f_date' AND '$to_date' $cl3 $bc3) cr 
    ON cr.cl = c.`cl` AND cr.bc = c.`bc` AND cr.nno = c.`inv_no` AND c.`sales_type` = '5' 
    WHERE c.is_cancel = '0' AND c.is_approve = 1 AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1
    GROUP BY c.cl,c.bc,c.rep) a 
    GROUP BY cl,bc,rep 
    ) rtd ON (rtd.cl=t.cl) AND (rtd.bc=t.bc) AND (rtd.rep=t.rep)




    LEFT JOIN m_employee ee ON ee.code=t.rep
    JOIN m_branch bb ON bb.bc=t.bc  
    WHERE t.ddate BETWEEN '$f_date' AND '$to_date' $cl1 $bc1 $emp2 
    GROUP BY t.cl, t.bc, t.rep
    Having total!=0  || return_net>0
    ORDER BY t.bc, t.ddate ASC";

            /*$sql="SELECT    CONCAT(t.bc,' - ',bb.name) AS bc,
                    t.rep,
                    CONCAT(t.rep,' - ',ee.name) AS emp_name,
                    t.ddate, 
                    IFNULL(cash_gross,0.00) AS cash_gross,
                    IFNULL(cash_dis,0.00)+IFNULL(cash_deduct,0.00) AS cash_dis,
                    IFNULL(cash_net, 0.00) AS cash_net,
                    IFNULL(cash_add, 0.00) AS cash_add,
                    IFNULL(credit_gross,0.00) AS credit_gross,
                    IFNULL(credit_dis,0.00)+IFNULL(credit_deduct,0.00) AS credit_dis,
                    IFNULL(credit_net,0.00) AS credit_net,
                    IFNULL(credit_add, 0.00) AS credit_add,
                    IFNULL(return_net,0.00) AS return_net,
                    (IFNULL(cash_net, 0.00)+IFNULL(credit_net,0.00))-(IFNULL(return_net,0.00)+IFNULL(cash_add,0.00)+IFNULL(credit_add,0.00)) AS total,
                    -- (IFNULL(cash_net, 0.00)+IFNULL(credit_net,0.00))-IFNULL(return_net,0.00) AS total  
                    IFNULL(cash_add,0.00)+IFNULL(credit_add,0.00) AS add_tot                     
                FROM(SELECT cl, bc, ddate,rep FROM t_cash_sales_sum
                        UNION ALL
                    SELECT cl, bc, ddate,rep FROM t_credit_sales_sum
                        UNION ALL
                    SELECT cl, bc, ddate,rep FROM t_sales_return_sum
                        UNION ALL
                    SELECT cl, bc, ddate,employee as rep FROM t_credit_note
                        UNION ALL
                    SELECT cl, bc,ddate, salesman_id as rep FROM t_pos_sales_sum)t 
                
                   LEFT JOIN (
                    SELECT  dd.cl,dd.bc,dd.rep,dd.ddate,IFNULL(c.cash_gross,0.00)+IFNULL(pos.pos_gross,0.00) as cash_gross, IFNULL(c.cash_dis,0)+IFNULL(ss.amnt,0)+IFNULL(pos_discount,0.00)  AS cash_dis ,(IFNULL(c.cash_net,0)+IFNULL(pos_net,0))-IFNULL(ss.amnt,0) AS cash_net ,c.cash_add ,c.cash_deduct,c.foc 
                    FROM (SELECT cl,bc,ddate,rep AS rep FROM t_cash_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date'  $cl3 $bc3
                            UNION  ALL
                          SELECT cl,bc,ddate,employee AS rep FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date'  $cl3 $bc3
                            UNION ALL
                          SELECT cl,bc,ddate,salesman_id as rep FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                           GROUP BY rep
                          ) dd 
                    LEFT JOIN (SELECT cl,bc,ddate,rep,
                                      SUM(IFNULL(gross_amount,0.00)- IFNULL(`total_foc_amount`,0.00)) AS cash_gross , 
                                      SUM(IFNULL(discount_amount,0))  AS cash_dis,
                                      SUM(IFNULL(net_amount,0)) AS cash_net,
                                      SUM(IFNULL(additional_add,0)) AS cash_add,
                                      SUM(IFNULL(additional_deduct,0)) AS cash_deduct,
                                      SUM(IFNULL(total_foc_amount, 0)) AS foc 
                                 FROM t_cash_sales_sum
                        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date'  $cl3 $bc3 GROUP BY rep) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.rep=dd.rep
                    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='4' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.employee = dd.`rep`    
                    
                    LEFT JOIN (SELECT cl,bc,ddate,salesman_id as rep,sum(gross_amount) as pos_gross ,sum(total_discount) as pos_discount , sum(net_amount) as pos_net FROM t_pos_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 AND is_cancel=0 GROUP BY salesman_id)pos ON pos.cl = dd.cl AND pos.bc=dd.bc AND pos.rep=dd.rep
                    
                    ) cs ON (t.cl=cs.cl) AND (t.bc=cs.bc) AND (cs.rep=t.rep)
   

                     LEFT JOIN (
                    SELECT dd.cl,dd.bc,dd.rep, dd.ddate,c.credit_gross, IFNULL(c.credit_dis,0)+IFNULL(ss.amnt,0)  AS credit_dis ,IFNULL(c.credit_net,0)-IFNULL(ss.amnt,0) AS credit_net ,c.credit_add ,c.credit_deduct,c.foc 
                    FROM (SELECT cl,bc,ddate,rep AS rep FROM t_credit_sales_sum WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3
                            UNION  ALL
                          SELECT cl,bc,ddate,employee as rep FROM t_credit_note WHERE ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 ) dd 
                    LEFT JOIN (SELECT cl,bc,ddate,rep,
                                      SUM(IFNULL(gross_amount,0.00) - IFNULL(`total_foc_amount`,0.00)) AS credit_gross , 
                                      SUM(IFNULL(discount_amount,0))  AS credit_dis,
                                      SUM(IFNULL(net_amount,0)) AS credit_net,
                                      SUM(IFNULL(additional_add,0)) AS credit_add,
                                      SUM(IFNULL(additional_deduct,0)) AS credit_deduct,
                                      SUM(IFNULL(total_foc_amount, 0)) AS foc 
                                 FROM t_credit_sales_sum
                        WHERE is_cancel='0' AND ddate BETWEEN '$f_date' AND '$to_date' $cl3 $bc3 GROUP BY rep) c ON c.cl=dd.cl AND c.bc=dd.bc AND c.rep=dd.rep
                    LEFT JOIN (SELECT cl,bc,ddate,employee,SUM(amount) AS amnt FROM t_credit_note tt WHERE tt.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND dis_type='5' AND is_cancel=0 AND tt.ddate BETWEEN '$f_date' AND '$to_date'  $cl2 $bc2 GROUP BY employee) AS ss ON ss.cl = dd.cl AND ss.bc = dd.bc AND ss.employee = dd.`rep`    
                    GROUP BY dd.rep) cr ON (t.cl=cr.cl) AND (t.bc=cr.bc) AND (cr.rep=t.rep)
  
                LEFT JOIN (SELECT cl, bc, c.ddate, SUM(IFNULL(c.net_amount,0)) AS return_net, rep FROM t_sales_return_sum c WHERE c.is_cancel='0' AND c.ddate BETWEEN '$f_date' AND '$to_date' $cl $bc $emp1   GROUP BY cl, bc,c.rep) rt ON (rt.cl=t.cl) AND (rt.bc=t.bc) AND (rt.rep=t.rep)
                LEFT JOIN m_employee ee ON ee.code=t.rep
                JOIN m_branch bb ON bb.bc=t.bc  
                WHERE t.ddate BETWEEN '$f_date' AND '$to_date' $cl1 $bc1 $emp1 
                GROUP BY t.cl, t.bc, t.rep
                Having total!=0  
                ORDER BY t.bc, t.ddate ASC";*/

                $data=$this->db->query($sql);  
                if($data->num_rows()>0){
                    $r_detail['r_data']=$data->result();
                    $r_detail['emp_data']=$data->result();
                    $exTy=($RepTyp=="")?'pdf':'excel';
                    $this->load->view($_POST['by'].'_'.$exTy,$r_detail);
                }else{
                    echo "<script>alert('No data found');close();</script>";
                } 

            }

            public function Excel_report(){
                $this->PDF_report("Excel");
            }

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

}
?>