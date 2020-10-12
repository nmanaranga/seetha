<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class chq_pending_list extends CI_Model {

  private $sd;
  private $mtb;
  private $mod = '003';
  function __construct(){
   parent::__construct();
   $this->sd = $this->session->all_userdata();
   $this->load->database($this->sd['db'], true);
   $this->mtb = $this->tables->tb['m_item_rol'];
 }

 public function base_details(){
   $a['branch']=$this->get_branch();
   return $a;
 }

 public function load(){
   $this->db->select(array('m_item.code','m_item.model','m_item.description','m_item_rol.cl','m_item_rol.bc','m_item_rol.rol','m_item_rol.roq'));
   $this->db->join('m_item', 'm_item.code= m_item_rol.code');
   $this->db->where('bc', $_POST['bc']);
   $query=$this->db->get($this->mtb);
   $a['c'] = $query->result();	
   echo json_encode($a);
 }

 public function voucher_list_all(){
   $cl=$this->sd['cl'];
   $bc=$this->sd['branch'];
   $from=$_POST['from_date'];
   $to=$_POST['to_date'];
   
  /*  $sql = "SELECT  v.nno,
                    v.ddate,
                    v.`payment`,
                    c.cheque_date,
                    c.cheque_no,
                    c.bank,
                    c.description,
                    v.acc_code,
                    s.name,
                    c.trans_code
            FROM t_voucher_sum v
            JOIN (SELECT * FROM `opt_issue_cheque_det` ic 
                  WHERE cl='$cl' AND bc='$bc') c 
            ON c.cl = v.cl 
            AND c.bc = v.`bc` 
            AND c.trans_no = v.`nno`
            LEFT JOIN m_supplier s ON s.code = v.`acc_code`  
            WHERE c.`is_chq_print` ='0' 
              AND v.cl='$cl'
              AND v.bc='$bc'
              AND v.ddate between '$from' and '$to'";*/

              $sql="SELECT v.nno,
              v.ddate,
              c.amount AS`payment`,
              c.cheque_date,
              c.cheque_no,
              c.bank,
              c.description,
              v.acc_code,
              s.name,
              c.trans_code 
              FROM t_voucher_sum v 
              JOIN (SELECT * FROM `opt_issue_cheque_det` ic WHERE cl = '$cl' AND bc = '$bc' AND trans_code='19')c ON c.cl = v.cl AND c.bc = v.`bc` AND c.trans_no = v.`nno` 
              JOIN m_supplier s ON s.code = v.`acc_code` 
              WHERE  v.cl = '$cl' 
              AND v.bc = '$bc' 
              AND c.`is_chq_print` ='0'
              AND v.`is_cancel` ='0'  
              AND c.cheque_date BETWEEN '$from' 
              AND '$to' 

              UNION ALL

              SELECT v.nno,
              v.ddate,
              c.amount AS`payment`,
              c.cheque_date,
              c.cheque_no,
              c.bank,
              c.description,
              v.paid_acc AS acc_code,
              s.name,
              c.trans_code 
              FROM t_voucher_gl_sum v 
              JOIN (SELECT * FROM `opt_issue_cheque_det` ic WHERE cl = '$cl' AND bc = '$bc' AND trans_code='48')c ON c.cl = v.cl AND c.bc = v.`bc` AND c.trans_no = v.`nno` 
              LEFT JOIN m_supplier s ON s.code = v.`paid_acc`  
              WHERE  v.cl = '$cl' 
              AND v.bc = '$bc' 
              AND c.`is_chq_print` ='0' 
              AND v.`is_cancel` ='0' 
              AND c.cheque_date BETWEEN '$from' 
              AND '$to' 
              AND TYPE='chaque'

              UNION ALL

              SELECT v.nno,
              v.ddate,
              c.amount AS`payment`,
              c.cheque_date,
              c.cheque_no,
              c.bank,
              c.description,
              v.acc_code,
              s.description AS name,
              c.trans_code 
              FROM t_internal_trans_voucher_sum v 
              JOIN (SELECT * FROM `opt_issue_cheque_det` ic WHERE cl = '$cl' AND bc = '$bc' AND trans_code='131')c ON c.cl = v.cl AND c.bc = v.`bc` AND c.trans_no = v.`nno` 
              JOIN m_account s ON s.code = v.`acc_code`
              WHERE  v.cl = '$cl' 
              AND v.bc = '$bc' 
              AND c.`is_chq_print` ='0'
              AND v.`is_cancel` ='0'  
              AND c.cheque_date BETWEEN '$from' 
              AND '$to' 
              GROUP BY  v.nno ,c.cheque_no";

              $query = $this->db->query($sql);

              if($query->num_rows() > 0){
                $a = "<table id='item_list' style='width : 100%' border='0'>";
                $x = 0;
                foreach($query->result() as $r){
                  $a .= "<tr class='cl'>";
                  $a .= "<td style='width:0px;'><input type='hidden' readonly='readonly' class='g_input_txt fo' id='".$x."' name='".$x."'/></td>";
                  $a .= "<td style='width:50px;'><input type='text' readonly='readonly' class='g_input_txt fo' id='nno_".$x."' name='nno_".$x."' value='".$r->nno."' title='".$r->nno."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:90px'><input type='text' readonly='readonly' class='g_input_num' id='date_".$x."' name='date_".$x."' value='".$r->ddate."' title='".$r->ddate."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:100px'><input type='text' readonly='readonly' class='g_input_num ' id='amount_".$x."' name='amount_".$x."' value='".$r->payment."' title='".$r->payment."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:90px'> <input type='text' readonly='readonly' class='g_input_num' id='cdate_".$x."' name='cdate_".$x."' value='".$r->cheque_date."' title='".$r->cheque_date."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:100px'><input type='text' readonly='readonly' class='g_input_num' id='cno_".$x."' name='cno_".$x."' value='".$r->cheque_no."' title='".$r->cheque_no."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:130px'><input type='text' readonly='readonly' class='g_input_txt ' id='bank_".$x."' name='bank_".$x."' value='".$r->bank."' title='".$r->bank."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td><input type='text' readonly='readonly' class='g_input_txt ' id='bdes_".$x."' name='bdes_".$x."' value='".$r->description."' title='".$r->description."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:130px'><input type='text' readonly='readonly' class='g_input_txt ' id='acc_".$x."' name='acc_".$x."' value='".$r->acc_code."' title='".$r->acc_code."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:200px'><input type='text' readonly='readonly' class='g_input_txt ' id='sname_".$x."' name='sname_".$x."' value='".$r->name."' title='".$r->name."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                  $a .= "<td style='width:200px display:none'><input type='hidden' id='tcode_".$x."' name='tcode_".$x."' value='".$r->trans_code."' title='".$r->trans_code."' /></td>";
                  $a .= "</tr>";
                  $x++;
                }
                $a .= "</table>";
              }else{
                $a =2;      
              }    
              echo $a;
            }


            public function get_branch(){
              $this->db->where("cl",$this->sd['cl']);
              $this->db->where("bc",$this->sd['branch']);   
              $result=$this->db->get("m_branch")->result_array();

              return $result; 
            }



          }