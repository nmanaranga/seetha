<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class find_item_promotions extends CI_Model {

    private $tb_items;
    private $tb_storse;
    private $tb_department;
    private $sd;
    private $w = 297;
    private $h = 210;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);

        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_storse = $this->tables->tb['m_stores'];
    }
    
    public function base_details(){
        $detail = $this->default_cluster();
        $cl=$detail[0]['code'];
        $cl_name=$detail[0]['description'];
        $a['cluster_code']=$cl;
        $a['cluster_name']=$cl_name;

        $detail1=$this->default_branch();
        $bc=$detail1[0]['bc'];
        $bc_name=$detail1[0]['name'];

        $a['branch_code']=$bc;
        $a['branch_name']=$bc_name;

        $detail2=$this->default_store();
        if($detail2!=2){
            $store=$detail2[0]['code'];
            $store_name=$detail2[0]['description'];
            $a['store_code']=$store;
            $a['store_name']=$store_name;
        }else{
            $a['store_code']="";
            $a['store_name']="";
        }   
        return $a;
    }

    public function get_cluster_name(){
        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
            $sql="  SELECT `code`,description 
            FROM m_cluster m
            JOIN u_branch_to_user u ON u.cl = m.code
            WHERE user_id='".$this->sd['oc']."' AND (m.code LIKE '%$_POST[search]%' OR m.description LIKE '%$_POST[search]%') 
            GROUP BY m.code
            LIMIT 25";
            $query=$this->db->query($sql);

            $a  = "<table id='item_list' style='width : 100%'>";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Code</th>";
            $a .= "<th class='tb_head_th' colspan='2'>Description</th>";
            $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                $a .= "<td>".$r->code."</td>";
                $a .= "<td>".$r->description."</td>";
                $a .= "</tr>";
            }
            $a.="</table>";
            echo $a;
        }

        public function get_branch_name(){

          if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
              $sql = "SELECT
              mb.`bc`,
              mb.`name`
              FROM m_branch mb
              JOIN u_branch_to_user u ON u.cl=mb.`cl` AND u.`bc` = mb.`bc`
              JOIN m_cluster mc ON mc.`code` = mb.`cl`
              WHERE user_id = '".$this->sd['oc']."' AND mb.cl= '".$_POST['cluster']."' AND (mb.bc LIKE '%$_POST[search]%' OR mb.name LIKE '%$_POST[search]%') LIMIT 25 ";
              $query=$this->db->query($sql);

              $a  = "<table id='item_list' style='width : 100%'>";
              $a .= "<thead><tr>";
              $a .= "<th class='tb_head_th'>Code</th>";
              $a .= "<th class='tb_head_th' colspan='2'>Name</th>";
              $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

              foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                $a .= "<td>".$r->bc."</td>";
                $a .= "<td>".$r->name."</td>";
                $a .= "</tr>";
            }
            $a.="</table>";
            echo $a;

        }


        public function get_store(){
            if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
                $sql="SELECT code,description
                FROM m_stores ms
                WHERE bc='".$_POST['bc']."' AND (code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%') LIMIT 25  ";

                $query=$this->db->query($sql);

                $a  = "<table id='item_list' style='width : 100%'>";
                $a .= "<thead><tr>";
                $a .= "<th class='tb_head_th'>Code</th>";
                $a .= "<th class='tb_head_th' colspan='2'>Description</th>";
                $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

                foreach($query->result() as $r){
                    $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->code."</td>";
                    $a .= "<td>".$r->description."</td>";
                    $a .= "</tr>";
                }
                $a.="</table>";
                echo $a;
            }

            public function default_cluster(){

             $sql=" SELECT `code` ,description
             FROM m_cluster m
             WHERE code='".$this->sd['cl']."' ";

             $query=$this->db->query($sql)->result_array();
             return  $query;
         }

         public function default_branch(){

             $sql=" SELECT bc,name
             FROM m_branch mb
             WHERE bc='".$this->sd['bc']."' ";

             $query=$this->db->query($sql)->result_array();
             return  $query;

         }

         public function default_store(){

            $sql=" SELECT m.`code`,m.`description`
            FROM m_stores m
            JOIN m_branch mb ON mb.`def_sales_store_code` = m.`code` AND mb.`cl`= m.`cl` AND mb.`bc` = m.`bc`
            WHERE mb.`bc` = '".$this->sd['bc']."' ";

            $query=$this->db->query($sql);
            if($query->num_rows()>0){
                $result = $query->result_array();
            }else{
                $result=2;
            }
            return  $result;

        }

        public function get_stock(){
         if($_POST['add_search']=="0"){
            $add_f=" AND (p.code LIKE '%$_POST[search]%' OR p.description LIKE '%$_POST[search]%' OR i.model LIKE '%$_POST[search]%')";
        }else if($_POST['add_search']=="1"){
            $add_f=" AND (p.code LIKE '%$_POST[search]%')";
        }else if($_POST['add_search']=="2"){
            $add_f=" AND (i.description LIKE '%$_POST[search]%')";
        }else{
            $add_f=" AND (i.model LIKE '%$_POST[search]%')";
        }

        if($_POST['promo_type']=='0'){
            $pro_type= " ";
        }else if($_POST['promo_type']=='1'){
            $pro_type= " AND p.promo_type='1'";
        }else if($_POST['promo_type']=='2'){
            $pro_type= " AND p.promo_type='2'";
        }else if($_POST['promo_type']=='3'){
            $pro_type= " AND p.promo_type='3'";
        }else{
            $pro_type= " AND p.promo_type='4'";
        }



        $sql="  SELECT p.*,i.`model` FROM `m_item_promotions` p
        JOIN `m_item` i ON i.`code`=p.`code`
        WHERE p.date_from <='".$_POST['fdate']."' AND p.date_to >='".$_POST['fdate']."' $add_f $pro_type";

        if(!empty($_POST['cl'])){
            $sql.= " AND p.cl='".$_POST['cl']."'";
        }

        if(!empty($_POST['bc'])){
            $sql.= " AND p.bc='".$_POST['bc']."'";
        }

        $sql.= " LIMIT 50";

        $query=$this->db->query($sql);

        if($query->num_rows()>0){
            $data['det']=$query->result(); 
        }else{
            $data['det']=2;                
        }
        echo json_encode($data);

    }


    public function item_list(){
        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
            $sql = "SELECT 
            code,
            description,
            model,
            purchase_price,
            min_price,
            max_price
            FROM m_item 
            WHERE(code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%')";

            $query = $this->db->query($sql);

            $a['data'] = $query->result();
            echo json_encode($a);

        }



        public function PDF_report() {

            $_POST['search'] = $_POST['txt_search'];


            $this->db->select(array(
              'name',
              'address',
              'tp',
              'fax',
              'email'
          ));

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $r_detail['branch']        = $this->db->get('m_branch')->result();

            $r_detail['cl']            = $_POST['txt_cluster'];
            $r_detail['cl_name']            = $_POST['hid_cluster'];
            $r_detail['bc']            = $_POST['txt_branch'];
            $r_detail['bc_name']            = $_POST['hid_branch'];
            $r_detail['store']            = $_POST['txt_store'];
            $r_detail['store_name']            = $_POST['hid_store'];


            if(!empty($_POST['txt_cluster'])){
                $cluster=" AND q.cl='".$_POST['txt_cluster']."'";
            }else{
                $cluster="";
            }

            if(!empty($_POST['txt_branch'])){
                $branch=" AND q.bc ='".$_POST['txt_branch']."'";
            }else{            
                $branch="";
            }

            if(!empty($_POST['txt_store'])){
                $store=" AND q.store_code ='".$_POST['txt_store']."'";
            }else{            
                $store="";
            }

            if($_POST['ptype']=="1"){
                $price_type="purchase_price";
            }elseif ($_POST['ptype']=="1"){
                $price_type="min_price";
            }else{
                $price_type="max_price";
            }

            if(!empty($_POST['from_price']) && empty($_POST['to_price'])){
            //$from_price =" AND t.$price_type >= '".$_POST['from_price']."'";
                $from_price =" AND t.$price_type = '".$_POST['from_price']."'";
            }else{
                $from_price ="";
            }

            if(!empty($_POST['to_price']) && empty($_POST['from_price'])){
            //$to_price =" AND t.$price_type <= '".$_POST['to_price']."'";
                $to_price =" AND t.$price_type = '".$_POST['to_price']."'";
            }else{
                $to_price ="";
            }

            if(!empty($_POST['from_price']) && !empty($_POST['to_price'])){
                $price_between = " AND t.$price_type BETWEEN '".$_POST['from_price']."' AND '".$_POST['to_price']."'";
            }else{
                $price_between ="";
            }

            if(isset($_POST['fine'])){
                $find_all="";
            }else{
                $find_all="AND q.qty>0";
            }

            if($_POST['add_search']=="0"){
                $add_f=" AND (q.item LIKE '%$_POST[search]%' OR i.description LIKE '%$_POST[search]%' OR i.model LIKE '%$_POST[search]%')";
            }else if($_POST['add_search']=="1"){
                $add_f=" AND (q.item LIKE '%$_POST[search]%')";
            }else if($_POST['add_search']=="2"){
                $add_f=" AND (i.description LIKE '%$_POST[search]%')";
            }else{
                $add_f=" AND (i.model LIKE '%$_POST[search]%')";
            }

            $sql="  SELECT  q.item,
            q.batch_no,
            q.qty,
            i.`description`,
            i.`model`,
            t.purchase_price AS b_cost,
            t.min_price AS b_min,
            t.max_price AS b_max    
            FROM qry_current_stock q
            JOIN t_item_batch t ON q.item = t.`item` AND q.batch_no=t.`batch_no`
            JOIN m_item i ON i.`code` = q.item
            WHERE q.item !='' 
            $find_all $cluster $branch $store $from_price $to_price $price_between
            $add_f
            GROUP BY t.item,t.batch_no
            Limit 1000";

            $query=$this->db->query($sql);

            if ($query->num_rows() > 0) {
                $r_detail['det'] = $query->result();
                $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
            }else{
                echo "<script>alert('No Data');window.close();</script>";
            }
        }



    }	
    ?>