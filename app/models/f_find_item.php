<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class f_find_item extends CI_Model{    
    private $sd;
    private $mtb;    
    private $mod = '003';    
    function __construct()
    {
        parent::__construct();
        
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->mtb = $this->tables->tb['m_item_rol'];
    }
    
    public function base_details()
    {
        $a['branch'] = $this->get_branch();
        return $a;
    }
    
    
    
    
    
    public function save()
    {
        $this->db->trans_start();
        
        $_POST['cl']     = $this->sd['cl'];
        $_POST['branch'] = $this->sd['branch'];
        $_POST['oc']     = $this->sd['oc'];
        
        for ($x = 0; $x < 12; $x++) {
            if (isset($_POST['0_' . $x])) {
                if ($_POST['0_' . $x] != "" && $_POST['n_' . $x] != "") {
                    $b[] = array(
                        "cl" => $this->sd['cl'],
                        "bc" => $this->sd['branch'],
                        "code" => $_POST['0_' . $x],
                        "rol" => $_POST['3_' . $x],
                        "roq" => $_POST['4_' . $x],
                        "oc" => $this->sd['oc']
                    );
                }
            }
        }
        
        
        
        
        $this->set_delete();
        if (count($b)) {
            echo $this->db->insert_batch("m_item_rol", $b);
        }
        
        $this->db->trans_complete();
        
    }
    
    private function set_delete()
    {
        $this->db->where("bc", $_POST['bc']);
        $this->db->delete("m_item_rol");
    }
    
    
    public function check_code()
    {
        $this->db->where('bc', $_POST['bc']);
        $this->db->limit(1);
        
        echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load()
    {
        $this->db->select(array(
            'm_item.code',
            'm_item.model',
            'm_item.description',
            'm_item_rol.cl',
            'm_item_rol.bc',
            'm_item_rol.rol',
            'm_item_rol.roq'
        ));
        $this->db->join('m_item', 'm_item.code= m_item_rol.code');
        $this->db->where('bc', $_POST['bc']);
        $query  = $this->db->get($this->mtb);
        $a['c'] = $query->result();
        echo json_encode($a);
    }
    
    public function delete()
    {
        $p = $this->user_permissions->get_permission($this->mod, array(
            'is_delete'
        ));
        
        if ($p->is_delete) {
            $this->db->where('bc', $_POST['code']);
            $this->db->limit(1);
            
            echo $this->db->delete($this->mtb);
        } else {
            echo 2;
        }
    }
    
    public function select()
    {
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='sales_ref' id='sales_ref'>";
        $s .= "<option value='0'>---</option>";
        foreach ($query->result() as $r) {
            $s .= "<option title='" . $r->name . "' value='" . $r->code . "'>" . $r->code . " | " . $r->name . "</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function item_list_all()
    {
        $cl = $this->sd['cl'];
        $bc = $this->sd['branch'];
        
        
        if ($_POST['type'] == 'item') {
            
            if ($_POST['search'] == 'Key Word: code, name') {
                $_POST['search'] = "";
            }
            
            $sql   = "SELECT `m_item`.`code`,
	        				`m_item`.`description` ,
	        				`m_item`.`model`, 
	        				`m_item`.`min_price`,
	        				`m_item`.`max_price`, 
	        				sum(`qry_current_stock`.`qty`) as qty,
	        				`qry_current_stock`.`batch_no`
	        		FROM m_item 
	        		JOIN `qry_current_stock` ON `m_item`.`code`=`qry_current_stock`.`item`
	        		WHERE `m_item`.`description` LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%' OR model LIKE '%$_POST[search]%' 
	        				OR `m_item`.`min_price` LIKE '%$_POST[search]%' OR `m_item`.`max_price` LIKE '%$_POST[search]%' AND inactive='0' 
	        				AND `qry_current_stock`.`cl` ='$cl' AND `qry_current_stock`.`bc`='$bc' 
	        	    GROUP BY `m_item`.`code`
	        	    LIMIT 50";
            $query = $this->db->query($sql);
            
            $a = "<table id='item_list' style='width:100%;' border='0'>";
            $a .= "<thead>";
            $a .= "<tr>";
            $a .= "<th width='196' class='tb_head_th' style='width: 80px;'>Code</th>
	               <th width='327' class='tb_head_th'>Description</th>
				   <th width='196' class='tb_head_th' style='width: 100px;''>Model</th>
	               <th width='327' class='tb_head_th' style='width: 80px;'>Min Price</th>
	               <th width='327' class='tb_head_th' style='width: 80px;''>Max Price</th>
				   <th width='196' class='tb_head_th' style='width: 80px;'>QTY</th>
				   </tr>
				   </thead>";
            
            foreach ($query->result() as $r) {
                $a .= "<tr class='cl'>";
                $a .= "<td style='width:100px'><input type='text' readonly='readonly' class='g_input_txt fo' id='' name='' value='" . $r->code . "' title='" . $r->code . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:250px'><input type='text' readonly='readonly' class='g_input_txt' value='" . $r->description . "' title='" . $r->description . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:90px'><input type='text' readonly='readonly' class='g_input_txt ' id='' name='' value='" . $r->model . "' title='" . $r->model . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:70px'> <input type='text' readonly='readonly' class='g_input_num' id='' name='' value='" . $r->min_price . "' title='" . $r->min_price . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:70px'><input type='text' readonly='readonly' class='g_input_num' id='' name='' value='" . $r->max_price . "' title='" . $r->max_price . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:70px'><input type='text' readonly='readonly' class='g_input_num ' id='' name='' value='" . $r->qty . "' title='" . $r->qty . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='display:none;'><input type='text' readonly='readonly' class='g_input_num ' id='' name='' value='" . $r->batch_no . "' title='" . $r->batch_no . "' style='display:none;'/></td>";
                
                $a .= "</tr>";
            }
            
            
            
            $a .= "</table>";
            $a .= "<table style='width:100%;'>
				  <tr><td height='20'><hr class='hline'/></td></tr>
				  </table>
				  <table style='width:80%; id='itmld'>
				  <tr>
				  <td >Item</td>
				  <td id='itm'><input type='text' class='g_input_txt hid_value' id='itm2' readonly='readonly' name='itm'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  <td>Min Price</td>
			      <td id='mPrice'><input type='text' class='g_input_num hid_value' id='mPrice2' readonly='readonly' name='mPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			      </tr>
				  <tr>
				  <td>Description</td>
				  <td id='des'><input type='text' class='g_input_txt hid_value' id='des2' readonly='readonly' name='des'  style='border:dotted 1px #003399;background:transparent; width:300px;'/></td>
				  <td>Max Price</td>
				  <td id='mxPrice'><input type='text' class='g_input_num hid_value' id='mxPrice2' readonly='readonly' name='mxPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
				  <tr>
				  <td>Batch</td>
				  <td id='btch'><input type='text' class='g_input_txt hid_value' id='btch2' readonly='readonly' name='btch'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
  				  <tr>
				  <td>Quantity</td>
				  <td id='qnty'><input type='text' class='g_input_txt hid_value' id='qnty2' readonly='readonly' name='qnty'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
				  </table>
				  <table style='width:100%;''>
				  <tr>
				  <td align='right'>
                  <input type='button' id='btnExit' value='Exit' title='Exit' />

                  <input type='hidden' name='code_' id='code_'/>   
                  <input name='button' type='button' id='btnReset' value='Reset' title='Reset' /></td>
				  </tr>
				  </table>";
            echo $a;
            
        } else if ($_POST['type'] == 'customer') {
            if ($_POST['search'] == 'Key Word: code, name') {
                $_POST['search'] = "";
            }
            
            $cl = $_POST['cluster_id'];  if ($cl == ""){ $q_cl = ""; }else{ $q_cl = " cl = '$cl' AND "; }
            $BC = $_POST['branch_id']; if ($BC == ""){ $q_bc = ""; }else{ $q_bc = "  bc = '$BC' AND "; }
            $age= "";
            if($_POST['age_limit_from']!=""){
                $age =" age >= '".$_POST['age_limit_from']."'";
            }
            if($_POST['age_limit_to']!=""){
                $age =" age <= '".$_POST['age_limit_to']."'";
            }
            if($_POST['age_limit_from']!="" && $_POST['age_limit_to']!=""){
                $age =" age >= '".$_POST['age_limit_from']."' AND age <= '".$_POST['age_limit_to']."' ";
            }
            if($age==""){
                $sql   = "SELECT c.*, 
                                CONCAT(r.code,' - ',r.description) as area_des                              
    	        		FROM m_customer c
                        JOIN r_area r ON r.code = c.area
    	        		WHERE $q_cl $q_bc (r.description LIKE '%$_POST[search]%' OR r.code LIKE '%$_POST[search]%' OR c.`name` LIKE '%$_POST[search]%' OR tp LIKE '%$_POST[search]%' OR email LIKE '%$_POST[search]%' OR c.code LIKE '%$_POST[search]%')
    	        	    LIMIT 50";
            }else{
                $sql   = "SELECT m_customer.*,
                                CONCAT(r.code,' - ',r.description) as area_des,  
                                CONCAT('19',SUBSTRING(nic,1,2))AS id_age, 
                                YEAR(CURDATE()) AS this_year ,
                                YEAR(CURDATE()) - CONCAT('19',SUBSTRING(nic,1,2)) AS age
                        FROM m_customer 
                        JOIN r_area r ON r.code = m_customer.area
                        WHERE $q_cl $q_bc (r.description LIKE '%$_POST[search]%' OR r.code LIKE '%$_POST[search]%' OR `m_customer`.`name` LIKE '%$_POST[search]%' OR tp LIKE '%$_POST[search]%' OR email LIKE '%$_POST[search]%' OR m_customer.code LIKE '%$_POST[search]%')
                        HAVING $age
                        LIMIT 50";
            }
            $query = $this->db->query($sql);
            
            $a = "<table id='item_list' style='width : 100%' border='0'>";
            $a .= "<thead>";
            $a .= "<tr>";
            $a .= "<th width='196' class='tb_head_th' style='width: 80px;'>Code</th>
                   <th width='80' class='tb_head_th'>NIC</th>
	               <th width='327' class='tb_head_th'>Name</th>
                   <th width='130' class='tb_head_th'>Area</th>
				   <th width='196' class='tb_head_th' style='width: 100px;'>Telephone</th>
	               <th width='327' class='tb_head_th' style='width: 80px;'>Email</th>
	               </tr>
				   </thead>";
            
            foreach ($query->result() as $r) {
                $a .= "<tr class='cl'>";
                $a .= "<td style='width:100px'><input type='text' readonly='readonly' class='g_input_txt fo' id='' name='' value='" . $r->code . "' title='" . $r->code . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:100px'><input type='text' readonly='readonly' class='g_input_txt' id='' name='' value='" . $r->nic . "' title='" . $r->nic . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:150px'><input type='text' readonly='readonly' class='g_input_txt' value='" . $r->name . "' title='" . $r->name . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:90px'><input type='text' readonly='readonly' class='g_input_txt ' id='' name='' value='" . $r->area_des . "' title='" . $r->area_des . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:90px'><input type='text' readonly='readonly' class='g_input_txt ' id='' name='' value='" . $r->tp . "' title='" . $r->tp . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:150px'> <input type='text' readonly='readonly' class='g_input_txt' id='' name='' value='" . $r->email . "' title='" . $r->email . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "</tr>";
            }
            
            
            $a .= "</table>";
            $a .= "<table style='width:100%;'>
				  <tr><td height='20'><hr class='hline'/></td></tr>
				  </table>
				  <table style='width:80%; display:none;' id='cusld'>
				  <tr>
				  <td >Item</td>
				  <td id='itm'><input type='text' class='g_input_txt hid_value' id='itm2' readonly='readonly' name='itm'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  <td>Min Price</td>
			      <td id='mPrice'><input type='text' class='g_input_num hid_value' id='mPrice2' readonly='readonly' name='mPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			      </tr>
				  <tr>
				  <td>Description</td>
				  <td id='des'><input type='text' class='g_input_txt hid_value' id='des2' readonly='readonly' name='des'  style='border:dotted 1px #003399;background:transparent; width:300px;'/></td>
				  <td>Max Price</td>
				  <td id='mxPrice'><input type='text' class='g_input_num hid_value' id='mxPrice2' readonly='readonly' name='mxPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
				  <tr>
				  <td>Batch</td>
				  <td id='btch'><input type='text' class='g_input_txt hid_value' id='btch2' readonly='readonly' name='btch'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
  				  <tr>
				  <td>Quantity</td>
				  <td id='qnty'><input type='text' class='g_input_txt hid_value' id='qnty2' readonly='readonly' name='qnty'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
				  </table>
				  <table style='width:100%;''>
				  <tr>
				  <td align='right'><input type='button' id='btnExit' value='Exit' title='Exit' /><input name='button2' type='button' id='btnSave' value='Save' title='Save' />
                  <input type='hidden' name='code_' id='code_'/>   
                  <input name='button' type='button' id='btnReset' value='Reset' title='Reset' /></td>
				  </tr>
				  </table>";
            echo $a;
            
        } else if ($_POST['type'] == 'supplier') {
            if ($_POST['search'] == 'Key Word: code, name') {
                $_POST['search'] = "";
            }
            
            $sql   = "SELECT *
	        		FROM m_supplier 
	        		WHERE `m_supplier`.`name` LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%' OR category LIKE '%$_POST[search]%' OR address1 LIKE '%$_POST[search]%'
	        	    LIMIT 50";
            $query = $this->db->query($sql);
            
            $a = "<table id='item_list' style='width : 100%' border='0'>";
            $a .= "<thead>";
            $a .= "<tr>";
            $a .= "<th width='196' class='tb_head_th' style='width: 80px;'>Code</th>
	               <th width='327' class='tb_head_th'>Name</th>
				   <th width='196' class='tb_head_th' style='width: 100px;'>Address</th>
	               <th width='327' class='tb_head_th' style='width: 80px;'>Category</th>
	               </tr>
				   </thead>";
            
            foreach ($query->result() as $r) {
                $a .= "<tr class='cl'>";
                $a .= "<td style='width:70px'><input type='text' readonly='readonly' class='g_input_txt fo' id='' name='' value='" . $r->code . "' title='" . $r->code . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:80px'><input type='text' readonly='readonly' class='g_input_txt' value='" . $r->name . "' title='" . $r->name . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:250px'><input type='text' readonly='readonly' class='g_input_txt ' id='' name='' value='" . $r->address1 . "' title='" . $r->address1 . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "<td style='width:70px'> <input type='text' readonly='readonly' class='g_input_txt' id='' name='' value='" . $r->category . "' title='" . $r->category . "' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                $a .= "</tr>";
            }
            
            
            $a .= "</table>";
            
            $a .= "<table style='width:100%;'>
				  <tr><td height='20'><hr class='hline'/></td></tr>
				  </table>
				  <table style='width:80%; display:none;' id='supld'>
				  <tr>
				  <td >Item</td>
				  <td id='itm'><input type='text' class='g_input_txt hid_value' id='itm2' readonly='readonly' name='itm'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  <td>Min Price</td>
			      <td id='mPrice'><input type='text' class='g_input_num hid_value' id='mPrice2' readonly='readonly' name='mPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			      </tr>
				  <tr>
				  <td>Description</td>
				  <td id='des'><input type='text' class='g_input_txt hid_value' id='des2' readonly='readonly' name='des'  style='border:dotted 1px #003399;background:transparent; width:300px;'/></td>
				  <td>Max Price</td>
				  <td id='mxPrice'><input type='text' class='g_input_num hid_value' id='mxPrice2' readonly='readonly' name='mxPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
				  <tr>
				  <td>Batch</td>
				  <td id='btch'><input type='text' class='g_input_txt hid_value' id='btch2' readonly='readonly' name='btch'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
  				  <tr>
				  <td>Quantity</td>
				  <td id='qnty'><input type='text' class='g_input_txt hid_value' id='qnty2' readonly='readonly' name='qnty'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
				  </tr>
				  </table>
				  <table style='width:100%;''>
				  <tr>
				  <td align='right'><input type='button' id='btnExit' value='Exit' title='Exit' /><input name='button2' type='button' id='btnSave' value='Save' title='Save' />
                  <input type='hidden' name='code_' id='code_'/>   
                  <input name='button' type='button' id='btnReset' value='Reset' title='Reset' /></td>
				  </tr>
				  </table>";
            
            
            echo $a;
            
        }    
    }
    
    
    public function get_branch()
    {
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $result = $this->db->get("m_branch")->result_array();
        
        return $result;
    }
    
    public function get_item()
    {
        $this->db->select(array(
            'code',
            'description',
            'model',
            'rol',
            'roq'
        ));
        $this->db->where("code", $this->input->post('code'));
        $this->db->limit(1);
        $query = $this->db->get('m_item');
        if ($query->num_rows() > 0) {
            $data['a'] = $query->result();
        } else {
            $data['a'] = 2;
        }
        
        echo json_encode($data);
    }
    
    
    
    /*
    public function item_list_alls(){
    
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
    
    $sql = "SELECT * FROM m_item WHERE description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' AND inactive='1' LIMIT 25";
    
    
    $query=$this->db->query($sql);
    
    if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
    }else{
    echo json_encode("2");
    }
    
    
    }
    
    */    

    public function set_BC_and_Cluster(){        
        $Q = $this->db->query("SELECT `code`,`description` FROM `m_cluster`");
        $TD = "";
        foreach ($Q->result() as $R){ $TD .= "<option value='".$R->code."'>".$R->code. " - ". $R->description."</option>";}        

        $a['d1'] = $TD;

        $Q = $this->db->query("SELECT `bc`,`name` FROM `m_branch`");
        $TD = "";
        foreach ($Q->result() as $R){ $TD .= "<option value='".$R->bc."'>".$R->bc. " - ". $R->name."</option>";}

        $a['d2'] = $TD;        

        echo json_encode($a);
    }

    public function set_BC_by_cluster(){
        $cluster_id = $_POST['cluster_id'];

        if ($cluster_id != ""){
            $Qx = " WHERE cl = '$cluster_id'";
        }else{
            $Qx = "";
        }

        $Q = $this->db->query("SELECT `bc`,`name` FROM `m_branch` $Qx ");
        $TD = "<option value=''>All Branches</option>";
        foreach ($Q->result() as $R){ $TD .= "<option value='".$R->bc."'>".$R->bc. " - ". $R->name."</option>";}
        echo $TD;
    }


}