<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_items extends CI_Model {

    private $sd;

    private $mtb;


    function __construct(){

	parent::__construct();

    $this->load->helper(array('form', 'url'));

    $this->load->helper('directory');

    $this->load->helper('file');





	$this->sd = $this->session->all_userdata();

	$this->load->database($this->sd['db'], true);

    $this->load->model('user_permissions');

	$this->mtb = $this->tables->tb['m_item'];

	

    }



    public function base_details(){



	$this->load->model('r_department');

	$this->load->model('r_units');

	$this->load->model('r_category');

	$this->load->model('r_sub_cat');

	$this->load->model('m_supplier');

	$this->load->model('r_brand');

    $this->load->model('r_stock_report');



	$a['table_data'] = $this->data_table();

    $a['cluster']=$this->r_stock_report->get_cluster_name();

	$a['units'] = $this->r_units->select();

	$a['main_cat'] = $this->r_category->select();

	$a['sub_cat'] = $this->r_sub_cat->select();

	$a['supplier'] = $this->m_supplier->select();

	$a['brand'] = $this->r_brand->select(); 

    //$a['m_date']=$this->last_modified_date();



	return $a;

    }



    





    public function data_table(){

        $this->load->library('table');

        $this->load->library('useclass');



        $this->table->set_template($this->useclass->grid_style());



        $code = array("data"=>"Code", "style"=>"width: 80px; cursor : pointer;");

        $des = array("data"=>"Description", "style"=>"width: 150px;");

        $min = array("data"=>"Min Price", "style"=>"width: 80px;");

        $max = array("data"=>"Max Price", "style"=>"width: 80px;");

		$pur = array("data"=>"Purchase", "style"=>"width: 100px;");

		$action = array("data"=>"Action", "style"=>"width: 80px;");



        $this->table->set_heading($code, $des, $min,$max, $pur, $action);



        $this->db->select(array('code', 'description', 'max_price','min_price','purchase_price'));

		$this->db->limit(10);

        $query = $this->db->get($this->mtb);



        foreach($query->result() as $r){

            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";

            if($this->user_permissions->is_delete('m_items')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";} 

            $ed = array("data"=>$but, "style"=>"text-align: center;");

            $code = array("data"=>$r->code, "value"=>"code");

			$des = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");

            $max = array("data"=>number_format($r->max_price, 2, ".", ","), "style"=>"text-align: right;");

			$min = array("data"=>number_format($r->min_price, 2, ".", ","), "style"=>"text-align: right;");

            $pur = array("data"=>number_format($r->purchase_price, 2, ".", ","), "style"=>"text-align: right;");

            $this->table->add_row($code, $des, $min, $max, $pur, $ed);

        }



        return $this->table->generate();

    }





    public function save(){



    	$this->db->trans_begin();

        error_reporting(E_ALL); 

        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 

          //  throw new Exception($errMsg); 

        }

        set_error_handler('exceptionThrower'); 

        try{ 



        $file=$_POST["code"];

        if(!is_dir("./upload/".$file)){

                mkdir("./upload/".$file);

         }

        

        for($i=1;$i<6;$i++){

             if(!empty($_POST['image_name'.$i])){

                unset($config);

                $config['file_name'] = $_POST['image_name'];

                $config['upload_path'] = "./upload/".$file;

                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $config['max_size'] = '10000';

                $this->load->library('upload', $config);

                $this->upload->do_upload('userfile'.$i);

             }

        }



        if(!isset($_POST['inactive'])){$_POST['inactive']=0;}else{$_POST['inactive']=1;}

        if(!isset($_POST['serial_no'])){$_POST['serial_no']=0;}else{$_POST['serial_no']=1;}

        if(!isset($_POST['batch_item'])){$_POST['batch_item']=0;}else{$_POST['batch_item']=1;}



     $item=array(

            "code"=>strtoupper($_POST["code"]),

            "description"=>$_POST["description"],

            "department"=>$_POST["department"],

            "main_category"=>$_POST["main_category"],

            "category"=>$_POST["sub_category"],

            "inactive"=>$_POST["inactive"],

            "serial_no"=>$_POST["serial_no"],

            "batch_item"=>$_POST["batch_item"],

            "unit"=>$_POST["unit"],

            "brand"=>$_POST["brand"],

            "model"=>$_POST["model"],

            "rol"=>$_POST["rol"],

            "roq"=>$_POST["roq"],

            "supplier"=>$_POST["supplier"],

            "barcode"=>$_POST["barcode"],

            "purchase_price"=>$_POST["purchase_price"],

            "min_price"=>$_POST["min_price"],

            "max_price"=>$_POST["max_price"],

            "oc"=>$this->sd['oc']

            );



     /*-----Save Image Path On Database-----*/



            for($x = 0; $x<3; $x++){

            if(isset($_POST['0_'.$x])){

                    if($_POST['0_'.$x] != "" && $_POST['n_'.$x] != "" ){

                        $b[]= array(

                            "sub_item"=>$_POST['0_'.$x],

                            "item_code"=>$_POST['code']

                        );              

                    }

                }

            }   



        $codee = $_POST["code"];



   		for($x = 1; $x<6; $x++){

            if(!empty($_POST['image_name'.$x]) && isset($_POST['image_name'.$x])){

                if($_POST['image_name'.$x] != "" || $_POST['userfile'.$x] != "" ){



                    $imgloop[]= array(

                    	"item_code" =>$_POST["code"],

                    	"name" => $_POST["image_name".$x],

    		     		"picture" =>"upload/". $codee. "/". $_FILES["userfile".$x]["name"]

                    );              

                }

            }

        }   





        if($_POST['code_'] == "0" || $_POST['code_'] == ""){

            if($this->user_permissions->is_add('m_items')){

                unset($_POST['code_']);

                $this->db->insert($this->mtb,$item);

                //echo $this->db->insert("m_item_picture",$imgNames);

                if(isset($b)){if(count($b)){ $this->db->insert_batch("m_item_sub", $b );}}

                if(isset($imgloop)){if(count($imgloop)){$this->db->insert_batch("m_item_picture", $imgloop);}}

                $this->db->trans_commit();

            }else{

                 echo"<script>alert('No permission to save records');history.go(-1);</script>";

                 $this->db->trans_commit();

            }    

        }else{

            if($this->user_permissions->is_edit('m_items')){

                $this->set_delete();   

                $this->db->where(array("code"=>$_POST['code_']));

                $this->db->update($this->mtb,$item);  

                if(isset($imgloop)){if(count($imgloop)){$this->db->insert_batch("m_item_picture", $imgloop);}}



                

                $this->db->where("code", $_POST['code_']);    

                if(isset($b)){if(count($b)){ $this->db->insert_batch("m_item_sub", $b );}}

                $this->db->trans_commit();

            }else{

                echo"<script>alert('No permission to edit records');history.go(-1);</script>";

                $this->db->trans_commit();

            }

        }

        echo"<script>alert('Save Completed');history.go(-1);</script>";

        //redirect(base_url()."?action=m_items");

        } catch ( Exception $e ) { 

            $this->db->trans_rollback();

            echo"<script>alert('Operation fail please contact admin');history.go(-1);</script>";

        } 

    }





    /*-------View Images on Table ------*/


public function get_next_no(){ 

        $pre_code = $_POST['pre'];

        $sql2="SELECT LPAD((SELECT IFNULL(MAX(SUBSTRING(`code`,11)),0)+1 as no FROM m_item WHERE LEFT(`code`,8)='$pre_code'),4,'0') as v";

        $code=$this->db->query($sql2)->first_row()->v;
        echo $code; 
    }


    public function img_list_all(){

    

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

    

      

       $sql = "SELECT * FROM m_item_picture  WHERE item_code LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' AND inactive='1' LIMIT 25";

       $query = $this->db->query($sql);

       $a = "<table id='item_list' style='width : 100%' >";

            $a .= "<thead><tr>";

            $a .= "<th class='tb_head_th'>Code</th>";

            $a .= "<th class='tb_head_th'>Item Name</th>";

         

        $a .= "</thead></tr>

                <tr class='cl'>

                <td>&nbsp;</td>

                <td>&nbsp;</td>

                </tr>



        ";

            foreach($query->result() as $r){

                $a .= "<tr class='cl'>";

                    $a .= "<td>".$r->code."</td>";

                    $a .= "<td>".$r->description."</td>";        

                    

                $a .= "</tr>";

            }

        $a .= "</table>";

        

        echo $a;

    }







	/*--- END ---*/



    private function set_delete(){

    	$this->db->where("item_code", $_POST['code_']);

        $this->db->delete("m_item_sub");



        if(isset($_POST['pp'])){

        $this->db->where("item_code", $_POST['code_']);

        $this->db->where("picture", $_POST['pp']);

        $this->db->delete("m_item_picture");

        }



    } 



    public function check_code(){

	$this->db->where('code', $_POST['code']);

	$this->db->limit(1);

	echo $this->db->get($this->mtb)->num_rows;

    }



    public function load(){



    	$this->db->select(array('IFNULL(t_price_change_sum.ddate,0) as ddate'));

        $this->db->from('t_price_change_det');

        $this->db->join('t_price_change_sum', 't_price_change_det.nno = t_price_change_sum.nno', 'left');

        $this->db->where('item', $_POST['code']);

        $this->db->order_by("ddate", "desc"); 

        $this->db->limit(1);

        $a['cc']=$this->db->get()->first_row();



        $this->db->where('code', $_POST['code']);

    	$this->db->limit(1);

        $a['c']=$this->db->get($this->mtb)->first_row();



   

    	$this->db->select(array('r_sub_item.code','r_sub_item.description','m_item_sub.item_code'));

        $this->db->from('r_sub_item');

        $this->db->join('m_item_sub', 'm_item_sub.sub_item= r_sub_item.code');

        $this->db->where("item_code", $_POST['code']);

        $query = $this->db->get();

        $a['det'] = $query->result();



 //---------load image------

       	 

   	  $this->db->select(array('name as pic_name','picture as pic_picture'));

   	  $this->db->where('item_code',$_POST['code']);

   	  $this->db->limit(5);

   	  $query=$this->db->get('m_item_picture');



   	  if($query->num_rows()>0){

   	  	$a['pic']=$query->result();

   	  }else{

   	  	$a['pic']=2;

   	  }



	   echo json_encode($a);

    }



    public function delete_validation(){

        $status=1;

        $codes=$_POST['code'];

        $check_cancellation = $this->utility->check_account_trans($codes,'Item','m_item','t_item_movement','item');

        if ($check_cancellation != 1) {

          return $check_cancellation;

        }

        return $status;

    }



    public function delete(){

        $this->db->trans_begin();

        error_reporting(E_ALL); 

        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 

            throw new Exception($errMsg); 

        }

        set_error_handler('exceptionThrower'); 

        try { 

            if($this->user_permissions->is_delete('m_items')){

              $delete_validation_status=$this->delete_validation();

              if($delete_validation_status==1){

                $this->db->query("DELETE FROM m_item_sub WHERE item_code='$_POST[code]'");

                $this->db->where('code', $_POST['code']);

                $this->db->limit(1);

                $this->db->delete($this->mtb);

                echo $this->db->trans_commit();

              }else{

                echo $delete_validation_status;

                $this->db->trans_commit();

              }

            }else{

                echo "No permission to delete records";

                $this->db->trans_commit();

            }   

        } catch ( Exception $e ) { 

            $this->db->trans_rollback();

            echo "Operation fail please contact admin"; 

        } 

    }



    public function get_data_table(){

    echo $this->data_table();

    }







     public function auto_com(){

        $this->db->like('code', $_GET['q']);

        $this->db->or_like($this->mtb.'.description', $_GET['q']);

        $this->db->limit(5);

        $query = $this->db->select(array('code','description','model','rol','roq'))->get($this->mtb);



        $abc = "";

            foreach($query->result() as $r){

                $abc .= $r->code."|".$r->description."|".$r->model."|".$r->rol."|".$r->roq;

                $abc .= "\n";

            }

        

        echo $abc;

        }  





    public function select($name = "code", $style =""){

        $query = $this->db->get($this->mtb);

        

        $s = "<select name='items' id='items' style='".$style."'>";

        $s .= "<option value='0'>---</option>";

        foreach($query->result() as $r){

            if($name == "code"){

		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." - ".$r->description."</option>";

	    }else{

		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->description."</option>";

	    }

        }

        $s .= "</select>";

        

        return $s;

    }







    public function item_list_all(){



       if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

       $sql = "SELECT * FROM m_item  WHERE description LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%' AND inactive='0' LIMIT 25";

       $query = $this->db->query($sql);

       $a = "<table id='item_list' style='width : 100%' >";

       $a .= "<thead><tr>";

       $a .= "<th class='tb_head_th'>Code</th>";

       $a .= "<th class='tb_head_th'>Item Name</th>";

       $a .= "</thead></tr>

             <tr class='cl'>

             <td>&nbsp;</td>

             <td>&nbsp;</td>

             </tr>

        ";

        foreach($query->result() as $r){

            $a .= "<tr class='cl'>";

            $a .= "<td>".$r->code."</td>";

            $a .= "<td>".$r->description."</td>";        

            $a .= "</tr>";

        }

        $a .= "</table>";

        echo $a;

    }





public function get_dep_main(){

    $sub_item=$_POST['sub_item'];

   

            $sql = "SELECT d.`code` AS dep,

            d.description AS dep_name,

            c.`code` AS cate,

            c.`description` AS main_cat 

            FROM r_sub_category s

            JOIN r_category c 

            ON c.code = s.`main_category`

            JOIN r_department d 

            ON d.`code` = c.`de_code`  

            WHERE s.`code`='$sub_item'

            LIMIT 25";

    $query = $this->db->query($sql);

    $query = $this->db->query($sql)->first_row();

    echo json_encode( $query);

  

}



    public function get_code(){

      $table=$_POST['data_tbl'];

      $field=isset($_POST['field'])?$_POST['field']:'code';

      $field2=isset($_POST['field2'])?$_POST['field2']:'description';

      $hid_field=isset($_POST['hid_field'])?$_POST['hid_field']:'none';

      $filter_value=$_POST['filter_value'];



    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}



      if($hid_field!='none'){

          if($filter_value!='0' && $table=='r_category'){

            $sql = "SELECT * FROM $table  WHERE de_code='$filter_value' AND ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' OR $hid_field LIKE '%$_POST[search]%') LIMIT 25";

          }else if($filter_value!='0' && $table=='r_sub_category'){

            $sql = "SELECT * FROM $table  WHERE main_category='$filter_value' AND ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' OR $hid_field LIKE '%$_POST[search]%') LIMIT 25";

          }else{

            $sql = "SELECT * FROM $table  WHERE $field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' OR $hid_field LIKE '%$_POST[search]%' LIMIT 25";

          }

      }else{

          if($filter_value!='0' && $table=='r_category'){

            $sql = "SELECT * FROM $table  WHERE de_code='$filter_value' AND ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%') LIMIT 25";

          }else if($filter_value!='0' && $table=='r_sub_category'){

            $sql = "SELECT * FROM $table  WHERE main_category='$filter_value' AND ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%') LIMIT 25";

          }else{

            $sql = "SELECT * FROM $table  WHERE $field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' LIMIT 25";

          }

      }  

      // if($filter_value!='0' && $table=='r_category'){

      //   $sql = "SELECT * FROM $table  WHERE de_code='$filter_value' AND ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' OR $hid_field LIKE '%$_POST[search]%') LIMIT 25";

      // }else if($filter_value!='0' && $table=='r_sub_category'){

      //   $sql = "SELECT * FROM $table  WHERE main_category='$filter_value' AND ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' OR $hid_field LIKE '%$_POST[search]%') LIMIT 25";

      // }else{

      //   $sql = "SELECT * FROM $table  WHERE $field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' OR $hid_field LIKE '%$_POST[search]%' LIMIT 25";

      // }

      $query = $this->db->query($sql);

      $a = "<table id='item_list' style='width : 100%' >";

      $a .= "<thead><tr>";

      $a .= "<th class='tb_head_th'>Code</th>";

      $a .= "<th class='tb_head_th' colspan='2' >Description</th>";

      $a .= "<th class='tb_head_th' >Code Gen</th>";

      $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td>&nbsp;</td></tr>";



    foreach($query->result() as $r){

      $a .= "<tr class='cl'>";

      $a .= "<td>".$r->code."</td>";

      $a .= "<td>".$r->{$field2}."</td>";

      

      if($hid_field!='none'){

        $a .= "<td><input type='hidden' class='code_gen' value='".$r->{$hid_field}."' title='".$r->{$hid_field}."' /></td>"; 

        $a .= "<td>".$r->$hid_field."</td>";       

      }



      $a .= "</tr>";

    }

      $a .= "</table>";

      echo $a;

    }



    



    public function get_available_stock(){



        $cluster=$_POST['cl'];

        $branch=$_POST['bc'];

        $store=$_POST['store'];

        $item=$_POST['item'];





        $sql="SELECT m_cluster.`description` AS c_name, m_cluster.code AS c_code, m_branch.`name` AS b_name, m_branch.`bc` AS b_code, m_stores.`description` AS s_name,  m_stores.`code` AS s_code,qty 

                FROM qry_current_stock

                JOIN m_cluster ON m_cluster.`code` = qry_current_stock.`cl`

                JOIN m_branch ON m_branch.bc = qry_current_stock.`bc`

                JOIN m_stores ON m_stores.`code` = qry_current_stock.`store_code`

                WHERE qry_current_stock.`item`='$item'

              ";





        if(!empty($cluster))

        {

            $sql.=" AND qry_current_stock.`cl` = '$cluster'";

        }

        

        if(!empty($branch))

        {

            $sql.=" AND qry_current_stock.`bc` = '$branch'";

        }

        

        if(!empty($store))

        {

            $sql.=" AND qry_current_stock.`store_code` = '$store'";

        }



        $sql.= " GROUP BY qry_current_stock.`store_code`, qry_current_stock.`batch_no`, qry_current_stock.`item` HAVING qry_current_stock.qty>0";



        $query=$this->db->query($sql);



        if($this->db->query($sql)->num_rows()>0)

        {

            $a['det']=$query->result();           

        }

        else

        {

            $a='2';

        }

        echo json_encode($a);

    }

public function PDF_report(){

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
 
    $r_detail['type']=$_POST['type'];        
    $r_detail['dd']=$_POST['dd'];
    $r_detail['qno']=$_POST['qno'];

    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];
    

    $sql="SELECT i.code,i.description,i.purchase_price,i.min_price,i.max_price FROM `m_item` i";

    $r_detail['item_det']=$this->db->query($sql)->result();  

        if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }

  }    



}





