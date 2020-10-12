<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class default_settings extends CI_Model {

    private $sd;
    private $mod = '003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');
    }

    public function base_details() {
         // $a['load_stock'] = $this->load_stock();
         // $a['load_account'] = $this->load_account();
         // $a['load_sales'] = $this->load_sales();
         // return $a;
    }

    public function save() {
     $this->db->trans_begin();
    function exceptionThrower($type, $errMsg, $errFile,$errLine ) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_add('default_settings')){

                $this->db->empty_table('def_option_stock');

               $this->db->empty_table('def_option_sales');
               $this->db->empty_table('def_option_account');
               $this->db->empty_table('def_opt_common');

                if (!isset($_POST['is_m_chq'])) {
                    $_POST['is_m_chq'] = 0;
                }else{
                     $_POST['is_m_chq'] =1;
                }
               
                if (!isset($_POST['sub_item'])) {
                    $_POST['sub_item'] = 0;
                }else{
                     $_POST['sub_item'] =1;
                }

                if (!isset($_POST['serial_no'])) {
                    $_POST['serial_no'] = 0;
                }else{
                    $_POST['serial_no'] = 1;
                }

                if (!isset($_POST['item_batch'])) {
                    $_POST['item_batch'] = 0;
                }else{
                    $_POST['item_batch'] = 1;
                }

                if (!isset($_POST['add_item'])) {
                    $_POST['add_item'] = 0;
                }else{
                    $_POST['add_item'] = 1;
                }

                // if (!isset($_POST['multi_store'])) {
                //     $_POST['multi_store'] = 0;
                // }else{
                //     $_POST['multi_store'] = 1;
                // }

                // if (!isset($_POST['is_sales_cat'])) {
                //     $_POST['is_sales_cat'] = 0;
                // }else{
                //     $_POST['is_sales_cat'] = 1;
                // }

                // if (!isset($_POST['is_sales_group'])) {
                //     $_POST['is_sales_group'] = 0;
                // }
                // else{
                //     $_POST['is_sales_group'] = 1;
                // }
                if (!isset($_POST['is_sales_man'])) {
                    $_POST['is_sales_man'] = 0;
                }else{
                    $_POST['is_sales_man'] = 1;
                }

                if (!isset($_POST['is_collection_off'])) {
                    $_POST['is_collection_off'] = 0;
                }else{
                    $_POST['is_collection_off'] = 1;
                }

                if (!isset($_POST['is_driver'])) {
                    $_POST['is_driver'] = 0;
                }else{
                    $_POST['is_driver'] = 1;
                }

                if (!isset($_POST['sep_sales_dis'])) {
                    $_POST['sep_sales_dis'] = 0;
                }else{
                     $_POST['sep_sales_dis'] = 1;
                }

                if (!isset($_POST['sep_sales_ret'])) {
                    $_POST['sep_sales_ret'] = 0;
                }else{
                    $_POST['sep_sales_ret'] = 1;
                }
                if (!isset($_POST['auto_dep_id'])) {
                    $_POST['auto_dep_id'] = 0;
                }else{
                    $_POST['auto_dep_id'] = 1;
                }
                if (!isset($_POST['auto_main_cat_id'])) {
                    $_POST['auto_main_cat_id'] = 0;
                }else{
                    $_POST['auto_main_cat_id'] = 1;
                }

                if (!isset($_POST['auto_sub_cat_id'])) {
                    $_POST['auto_sub_cat_id'] = 0;
                }else{
                    $_POST['auto_sub_cat_id'] = 1;
                }

                 if (!isset($_POST['auto_unit_id'])) {
                    $_POST['auto_unit_id'] = 0;
                }else{
                    $_POST['auto_unit_id'] = 1;
                }

                if (!isset($_POST['auto_brand_id'])) {
                    $_POST['auto_brand_id'] = 0;
                }else{
                    $_POST['auto_brand_id'] = 1;
                }

                if (!isset($_POST['auto_clr_id'])) {
                    $_POST['auto_clr_id'] = 0;
                }else{
                    $_POST['auto_clr_id'] = 1;
                }

                if (!isset($_POST['auto_area_id'])) {
                    $_POST['auto_area_id'] = 0;
                }else{
                    $_POST['auto_area_id'] = 1;
                }

                if (!isset($_POST['auto_route_id'])) {
                    $_POST['auto_route_id'] = 0;
                }else{
                    $_POST['auto_route_id'] = 1;
                }

                if (!isset($_POST['auto_town_id'])) {
                    $_POST['auto_town_id'] = 0;
                }else{
                    $_POST['auto_town_id'] = 1;
                }

                 if (!isset($_POST['auto_supplier_id'])) {
                    $_POST['auto_supplier_id'] = 0;
                }else{
                    $_POST['auto_supplier_id'] = 1;
                }

                if (!isset($_POST['auto_national_id'])) {
                    $_POST['auto_national_id'] = 0;
                }else{
                    $_POST['auto_national_id'] = 1;
                }

                 if (!isset($_POST['auto_c_category_id'])) {
                    $_POST['auto_c_category_id'] = 0;
                }else{
                    $_POST['auto_c_category_id'] = 1;
                }

                 if (!isset($_POST['auto_c_type_id'])) {
                    $_POST['auto_c_type_id'] = 0;
                }else{
                    $_POST['auto_c_type_id'] = 1;
                }


    //============================================================            

                if (!isset($_POST['auto_item_id'])) {
                    $_POST['auto_item_id'] = 0;
                }else{
                    $_POST['auto_item_id'] = 1;
                }

                if (!isset($_POST['gen_itemcode_by_department'])) {
                    $_POST['gen_itemcode_by_department'] = 0;
                } else {
                    $_POST['gen_itemcode_by_department'] = 1;
                }

                if (!isset($_POST['gen_itemcode_by_maincat'])) {
                    $_POST['gen_itemcode_by_maincat'] = 0;
                } else {
                    $_POST['gen_itemcode_by_maincat'] = 1;
                }

                if (!isset($_POST['gen_itemcode_by_subcat'])) {
                    $_POST['gen_itemcode_by_subcat'] = 0;
                } else {
                    $_POST['gen_itemcode_by_subcat'] = 1;
                }

                if (!isset($_POST['gen_supplier_by_auto_id'])) {
                    $_POST['gen_supplier_by_auto_id'] = 0;
                } else {
                    $_POST['gen_supplier_by_auto_id'] = 1;
                }

                if (!isset($_POST['gen_itemcode_by_standard'])) {
                    $_POST['gen_itemcode_by_standard'] = 0;
                } else {
                    $_POST['gen_itemcode_by_standard'] = 1;
                }

                if (!isset($_POST['gen_itemcode_by_normal'])) {
                    $_POST['gen_itemcode_by_normal'] = 0;
                } else {
                    $_POST['gen_itemcode_by_normal'] = 1;
                }

                if (!isset($_POST['use_seettu'])) {
                    $_POST['use_seettu'] = 0;
                } else {
                    $_POST['use_seettu'] = 1;
                   
                }

                if (!isset($_POST['use_hp'])) {
                    $_POST['use_hp'] = 0;

                } else {
                    $_POST['use_hp'] = 1;
                }

                if (!isset($_POST['use_service'])) {
                    $_POST['use_service'] = 0;

                } else {
                    $_POST['use_service'] = 1;
                }

                 if (!isset($_POST['use_cheque'])) {
                    $_POST['use_cheque'] = 0;

                } else {
                    $_POST['use_cheque'] = 1;
                }

                if (!isset($_POST['use_gift'])) {
                    $_POST['use_gift'] = 0;

                } else {
                    $_POST['use_gift'] = 1;
                }

                if (!isset($_POST['use_privilage'])) {
                    $_POST['use_privilage'] = 0;

                } else {
                    $_POST['use_privilage'] = 1;
                }

             //============================================================

                if (!isset($_POST['print_cur_time'])) {
                    $_POST['print_cur_time'] = 0;

                } else {
                    $_POST['print_cur_time'] = 1;
                }

                if (!isset($_POST['print_sav_time'])) {
                    $_POST['print_sav_time'] = 0;

                } else {
                    $_POST['print_sav_time'] = 1;
                }

                $def_option_stock = array(
                    "use_sub_items" => $_POST['sub_item'],
                    "use_serial_no_items" => $_POST['serial_no'],
                    "use_item_batch" => $_POST['item_batch'],
                    "use_additional_items" => $_POST['add_item'],
                    "is_auto_genarate_department" => $_POST['auto_dep_id'],
                    "department_code_type" => $_POST['auto_dep'],
                    "is_auto_genarate_category" => $_POST['auto_main_cat_id'],
                    "category_code_type" => $_POST['auto_main_cat'],
                    "is_auto_genarate_s_category" => $_POST['auto_sub_cat_id'],
                    "s_category_code_type" => $_POST['auto_sub_cat'],
                    "is_auto_genarate_unit" =>$_POST['auto_unit_id'],
                    "unit_code_type" => $_POST['auto_unit'],
                    "is_auto_genarate_brand" => $_POST['auto_brand_id'],
                    "brand_code_type" => $_POST['auto_brand'],
                    "is_auto_genarate_color" => $_POST['auto_clr_id'],
                    "color_code_type" => $_POST['auto_clr'],
                    "is_auto_genarate_area" => $_POST['auto_area_id'],
                    "area_code_type" => $_POST['auto_area'],
                    "is_auto_genarate_root" => $_POST['auto_route_id'],
                    "root_code_type" => $_POST['auto_route'],
                    "is_auto_genarate_town" => $_POST['auto_town_id'],
                    "town_code_type" => $_POST['auto_town'],
                    "is_auto_genarate_nationality" => $_POST['auto_national_id'],
                    "nationality_code_type" => $_POST['auto_national'],
                    "is_auto_genarate_cus_cat" => $_POST['auto_c_category_id'], 
                    "cus_cat_code_type" => $_POST['auto_c_category'],
                    "is_auto_genarate_cus_type" => $_POST['auto_c_type_id'],
                    "cus_type_code_type" => $_POST['auto_c_type'],
                    "is_auto_genarate_sup_cat" => $_POST['auto_supplier_id'],
                    "sup_cat_code_type" => $_POST['auto_supplier'],
                    "gen_itemcode_by_department" => $_POST['gen_itemcode_by_department'],
                    "gen_itemcode_by_maincat" => $_POST['gen_itemcode_by_maincat'],
                    "gen_itemcode_by_subcat" => $_POST['gen_itemcode_by_subcat'],
                    "gen_supplier_by_auto_id" => $_POST['gen_supplier_by_auto_id'],
                    "gen_itemcode_by_standard" => $_POST['gen_itemcode_by_standard'],
                    "gen_itemcode_by_normal" => $_POST['gen_itemcode_by_normal'],

                );
                


            $this->db->insert('def_option_stock', $def_option_stock);

            
            $def_option_sales = array(
                "use_salesman"                => $_POST['is_sales_man'],
                "def_salesman_code"           => $_POST['desc_sales_man'],
                "def_salesman"                => $_POST['sales_man'],
                "use_collection_officer"      => $_POST['is_collection_off'],
                "def_collection_officer_code" => $_POST['desc_collection_off'],
                "def_collection_officer"      => $_POST['collection_off'],
                "use_driver"                  => $_POST['is_driver'],
                "def_driver_code"             => $_POST['desc_driver'],
                "def_driver"                  => $_POST['driver'],
                "use_helper"                  => $_POST['is_helper'],
                "def_helper_code"             => $_POST['desc_helper'],
                "def_helper"                  => $_POST['helper'], 
                "def_use_seettu"              => $_POST['use_seettu'],
                "def_use_hp"                  => $_POST['use_hp'],
                "def_use_service"             => $_POST['use_service'],
                "def_use_cheque"              => $_POST['use_cheque'],
                "def_use_giftV"               => $_POST['use_gift'],
                "def_use_privilege"           => $_POST['use_privilage'],
            );
            
            $this->db->insert('def_option_sales', $def_option_sales);

            $def_option_account = array(
                "sales_discount_to_separate_acc" => $_POST['sep_sales_dis'],
                "sales_return_to_separate_acc" => $_POST['sep_sales_ret'],
                // "open_bal_date" => $_POST['open_bal_date'],
                "Penalty_Rate" => "2",
                "grace_period" => "3",
                "is_multi_chq_pay_voucher" => $_POST['is_m_chq'] 
            );
           $this->db->insert('def_option_account', $def_option_account);

           $def_option_common=array(
                    "is_print_cur_time_rec" => $_POST['print_cur_time'],
                    "is_print_save_time_rec" => $_POST['print_sav_time'],
                    "heading_align" => $_POST['type'],
                    );
            $this->db->insert('def_opt_common', $def_option_common);

           if (isset($_POST['use_seettu'])) {
                    $this->active_modules("def_use_seettu","1");

            }
             if (isset($_POST['use_hp'])) {
                    $this->active_modules("def_use_hp","2");

            }
             if (isset($_POST['use_service'])) {
                    $this->active_modules("def_use_service","3");

            }
             if (isset($_POST['use_cheque'])) {
                    $this->active_modules("def_use_cheque","4");

            }
             if (isset($_POST['use_gift'])) {
                    $this->active_modules("def_use_giftV","5");

            }


                echo $this->db->trans_commit();
            }else{
                echo "No permission to save records";
                $this->db->trans_commit();
            }    
            
         }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
        }  
    }

    private function set_delete() {
        
    }

    public function check_code() {
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);
        echo $this->db->get($this->mtb)->num_rows;
    }

    public function load() {
        $query = $this->db->get('def_option_account');
        if ($query->num_rows() > 0) {
            $a['option_account'] = $query->result();
        } else {
            $a['option_account'] = 2;
        }
       
        $query = $this->db->get('def_option_sales');

        if ($query->num_rows() > 0) {
            $a['option_sales'] = $query->result();
        } else {
             $a['option_sales']  = 2;
        }


        $sql="SELECT
                  `use_sub_items`,
                  `use_serial_no_items`,
                  `use_item_batch`,
                  `use_additional_items`,
                  `gen_itemcode_by_department`,
                  `gen_itemcode_by_maincat`,
                  `gen_itemcode_by_subcat`,
                  `gen_supplier_by_auto_id`,
                  `gen_itemcode_by_standard`,
                  `gen_itemcode_by_normal`,
                  `is_auto_genarate_department`,
                  `department_code_type`,
                  `is_auto_genarate_category`,
                  `category_code_type`,
                  `is_auto_genarate_s_category`,
                  `s_category_code_type`,
                  `is_auto_genarate_unit`,
                  `unit_code_type`,
                  `is_auto_genarate_brand`,
                  `brand_code_type`,
                  `is_auto_genarate_color`,
                  `color_code_type`,
                  `is_auto_genarate_area`,
                  `area_code_type`,
                  `is_auto_genarate_town`,
                  `town_code_type`,
                  `is_auto_genarate_root`,
                  `root_code_type`,
                  `is_auto_genarate_nationality`,
                  `nationality_code_type`,
                  `is_auto_genarate_cus_cat`,
                  `cus_cat_code_type`,
                  `is_auto_genarate_cus_type`,
                  `cus_type_code_type`,
                  `is_auto_genarate_sup_cat`,
                  `sup_cat_code_type`
                 
                FROM `def_option_stock`";
       $query=$this->db->query($sql);  

        // $query = $this->db->get('def_option_sales');
        if ($query->num_rows() > 0) {
            $a['option_stock'] = $query->result();
        } else {
            $a['option_stock'] = 2;
        }
        $query = $this->db->get('def_option_stock');
        if ($query->num_rows() > 0) {
            $a['auto_deptm'] = $query->result();
        } else {
            $a['auto_deptm'] = 2;
        }

        $query = $this->db->get('def_opt_common');
        if ($query->num_rows() > 0) {
            $a['option_common'] = $query->result();
        } else {
            $a['option_common'] = 2;
        }
        
        // $query = $this->db->get('def_option_stock');
        // if ($query->num_rows() > 0) {
        //     $a['auto_mcat'] = $query->result();
        // } else {
        //     $a['auto_mcat'] = 2;
        // }
        // $query = $this->db->get('def_option_stock');
        // if ($query->num_rows() > 0) {
        //     $a['auto_sbcat'] = $query->result();
        // } else {
        //     $a['auto_sbcat'] = 2;
        // }

        echo json_encode($a);
    }

    public function delete() {
        $p = $this->user_permissions->get_permission($this->mod, array('is_delete'));
        if ($p->is_delete) {
            $this->db->where('code', $_POST['code']);
            $this->db->limit(1);

            echo $this->db->delete($this->mtb);
        } else {
            echo 2;
        }
    }

    public function get_payment_option() {
        $this->db->where("code", $_POST['code']);
        $data['result'] = $this->db->get("r_payment_option")->result();
        echo json_encode($data);
    }

    public function check_pv_no() {
        $this->db->select(array("card_no"));
        $this->db->where('card_no', $_POST['privi_card']);
        $this->db->limit(1);
        echo $this->db->get("t_privilege_card")->num_rows;
    }

    public function is_serial_entered($trans_no, $item, $serial) {
        $this->db->select(array('available'));
        $this->db->where("serial_no", $serial);
        $this->db->where("item", $item);
        $query = $this->db->get("t_serial");

        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    
    public function active_modules($colum,$main_mod){
        $sql="SELECT `$colum` as status FROM `def_option_sales` LIMIT 1 ";

        $query = $this->db->query($sql)->row()->status;

        $sql2 = "UPDATE `def_option_module` SET is_active ='$query' WHERE main_mod='$main_mod'";
        $query2 = $this->db->query($sql2);
    }
}
