<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_po_status extends CI_Model {

    private $tb_items;
    private $tb_main_cat;
    private $tb_sub_cat;
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);


    }
    


    public function base_details(){

    }
    
    public function PDF_report(){

        $this->db->select(array('name','address','tp','fax','email'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['branch']=$this->db->get('m_branch')->result();
        $r_detail['page']=$_POST['page'];
        $r_detail['type']=$_POST['type']; 

    //////////////////

        $this->db->select(array('description','code'));
        $this->db->where("code",$_POST['cluster']);
        $r_detail['clus']=$this->db->get('m_cluster')->result();


        $this->db->select(array('name','bc'));
        $this->db->where("bc",$_POST['branch']);
        $r_detail['bran']=$this->db->get('m_branch')->result();

        $this->db->select(array('description','code'));
        $this->db->where("cl",$_POST['cluster']);
        $this->db->where("code",$_POST['store']);
        $r_detail['str']=$this->db->get('m_stores')->result();

        $this->db->select(array('description','code'));
        $this->db->where("code",$_POST['department']);
        $r_detail['dp']=$this->db->get('r_department')->result();

        $this->db->select(array('description','code'));
        $this->db->where("code",$_POST['main_category']);
        $r_detail['cat']=$this->db->get('r_category')->result();

        $this->db->select(array('description','code'));
        $this->db->where("code",$_POST['sub_category']);
        $r_detail['scat']=$this->db->get('r_sub_category')->result();

        $this->db->select(array('description','code'));
        $this->db->where("code",$_POST['item']);
        $r_detail['itm']=$this->db->get('m_item')->result();

        $this->db->select(array('description','code'));
        $this->db->where("code",$_POST['unit']);
        $r_detail['unt']=$this->db->get('r_unit')->result();

        $this->db->select(array('description','code'));
        $this->db->where("code",$_POST['brand']);
        $r_detail['brnd']=$this->db->get('r_brand')->result();

        $this->db->select(array('name','code'));
        $this->db->where("code",$_POST['supplier']);
        $r_detail['sup']=$this->db->get('m_supplier')->result();

    ///////

        $this->db->select(array('code','description'));
        $this->db->where("cl",$_POST['cluster']);
        $this->db->where("bc",$_POST['branch']);
        $this->db->where("code",$_POST['stores']);
        $r_detail['store_des']=$this->db->get('m_stores')->row()->description;

        $cl=$_POST['cluster'];
        $bc=$_POST['branch'];

        $r_detail['store_code']=$_POST['stores']; 
        $r_detail['type']=$_POST['type'];        
        $r_detail['dd']=$_POST['dd'];
        $r_detail['qno']=$_POST['qno'];

        $r_detail['page']=$_POST['page'];
        $r_detail['header']=$_POST['header'];
        $r_detail['orientation']="L";
        $r_detail['from']=$_POST['from'];
        $r_detail['to']=$_POST['to'];
        $r_detail['cluster']=$_POST['cluster'];
        $r_detail['branchs']=$_POST['branch'];
        $r_detail['store']=$_POST['store'];
        $r_detail['department']=$_POST['department'];
        $r_detail['main_category']=$_POST['main_category'];
        $r_detail['sub_category']=$_POST['sub_category'];
        $r_detail['item']=$_POST['item'];
        $r_detail['unit']=$_POST['unit'];
        $r_detail['brand']=$_POST['brand'];
        $r_detail['supplier']=$_POST['supplier'];
        $r_detail['ref_no']=$_POST['ref_no'];

        $to=$_POST['to'];
        $from=$_POST['from'];
        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];
        $store=$_POST['store'];
        $department=$_POST['department'];
        $main_category=$_POST['main_category'];
        $sub_category=$_POST['sub_category'];
        $item=$_POST['item'];
        $unit=$_POST['unit'];
        $brand=$_POST['brand'];
        $supplier=$_POST['supplier'];
        $ref_no=$_POST['ref_no'];



    /*  $sql="SELECT s.`nno` ,
                    m.`name`,
                    s.`ddate` ,
                    s.`supplier`  ,
                    d.`item` , 
                    d.`qty` , 
                    IFNULL( o.qty,0) AS Received,
                    (d.`qty` - IFNULL(o.qty,0)) AS ToBeReceive,
                    i.`description`
            FROM `t_po_sum` s
                LEFT JOIN `t_po_det` d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
                JOIN m_supplier m ON s.`supplier` = m.`code`
                LEFT JOIN `m_item` i ON d.`item`=i.`code`
                LEFT JOIN (SELECT s.`cl` ,s.`bc` , s.`po_no` , d.`code` ,d.`qty`
                        FROM  `t_grn_sum` s 
                        LEFT JOIN `t_grn_det` d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`)
                        o ON d.cl=o.cl AND d.`bc`=o.bc AND d.`nno`=o.po_no AND d.`item`=o.code
            WHERE s.cl='$cl' AND s.`bc`='$bc' 
            ORDER BY s.`nno` ASC

      ";
   */

      $sql="SELECT  s.`cl` ,s.`bc` , s.`nno` ,s.`ddate` , d.`total_qty` 
      ,IF(s.`is_level_0_approved`,'Y','N') AS is_level_0_approved ,d.`level_0_approve_qty`
      ,IF(s.`is_level_1_approved`,'Y','N') AS is_level_1_approved , d.`level_1_approve_qty` 
      ,IF(s.`is_level_2_approved`,'Y','N') AS is_level_2_approved , d.`level_2_approve_qty`
      ,d.`orderd`    ,d.`orderd_no`                 
      ,g.nno AS grn_no    ,g.qty AS grn_qty
      ,d.`item` 
      ,i.`description`
      ,i.code as i_code
      FROM  `t_req_sum` s
      LEFT JOIN  `t_req_det` d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
      LEFT JOIN (SELECT s.`cl` ,s.`bc` ,s.`nno` ,  s.`po_no` , d.`code` ,  d.`qty`  FROM `t_grn_sum` s
      LEFT JOIN `t_grn_det` d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`) g ON 
      d.`cl`=g.cl AND d.`bc` = g.bc AND d.`orderd_no`=g.po_no
      LEFT JOIN `m_item` i ON d.`item`=i.`code`       
      WHERE s.cl='$cl' AND s.`bc`='$bc'  and  
      s.`ddate` between '$_POST[from]' and '$_POST[to]' ";

            /*
            if ($ref_no!="") {
                    $sql=$sql.' and s.nno='.$ref_no;
            }
            */
            //echo($r_detail['ref_no']);
            //echo $sql;
            //exit();

            $r_detail['customer']=$this->db->query($sql)->result(); 

            if($this->db->query($sql)->num_rows()>0){
              $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
          }else{
              echo "<script>alert('No Data');window.close();</script>";
          }

      }
  }
  ?>