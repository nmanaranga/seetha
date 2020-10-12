<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tables {
    public $tb = array();
    
    function __construct(){
        
        $this->tb['a_log_users'] = 'a_log_users';

        $this->tb['qry_current_stock'] = 'qry_current_stock';
        $this->tb['a_users'] = 's_users';
        $this->tb['s_company'] = 's_company';
        $this->tb['s_branches'] = 's_branches';
        $this->tb['s_reg'] = 's_reg';
        $this->tb['s_tabs'] = 's_tabs';
        

        $this->tb['m_stores'] = 'm_stores';
        $this->tb['m_sales_ref'] = 'm_sales_ref';
        $this->tb['m_person'] = 'm_person';
        $this->tb['m_main_regon'] = 'm_main_regon';
        $this->tb['m_sub_regon'] = 'm_sub_regon';
        $this->tb['m_area'] = 'm_area';
        $this->tb['m_root'] = 'm_root';
        $this->tb['m_customer'] = 'm_customer';
        $this->tb['m_customer_contacts'] = 'm_customer_contacts';
        $this->tb['m_agent'] = 'm_agent';
        $this->tb['m_agency'] = 'm_agency';
        $this->tb['t_loading_sum'] = 't_loading_sum';
        $this->tb['t_unloading_sum'] = 't_unloading_sum';

        $this->tb['m_items'] = 'm_items';
        $this->tb['m_supplier'] = 'm_supplier';
        $this->tb['m_banks'] = 'm_banks';
        $this->tb['m_bank_branch'] = 'm_bank_branch';
        $this->tb['m_supplier_contacts'] = 'm_supplier_contacts';
        $this->tb['m_age_analyze_setup'] = 'm_age_analyze_setup';
        $this->tb['m_age_analyze_2'] = 'm_age_analyze_2';
        $this->tb['t_damage_sum'] = 't_damage_sum';
        $this->tb['r_return_reason'] = 'r_return_reason';
        
        $this->tb['t_customer_credit_note'] = 't_customer_credit_note';
        $this->tb['t_customer_debit_note'] = 't_customer_debit_note';
        
        $this->tb['t_supplier_credit_note'] = 't_supplier_credit_note';
        $this->tb['t_supplier_debit_note'] = 't_supplier_debit_note';
        $this->tb['t_advance'] = 't_advance';
        $this->tb['t_cus_opening_bal_sum'] = 't_cus_opening_bal_sum';
        $this->tb['t_cus_opening_bal_det'] = 't_cus_opening_bal_det';
        $this->tb['t_sup_opening_bal_sum'] = 't_sup_opening_bal_sum';
        $this->tb['t_sup_opening_bal_det'] = 't_sup_opening_bal_det';
        
        $this->tb['m_defult_account'] = 'm_defult_account';
        $this->tb['m_accounts'] = 'm_accounts';

        $this->tb['t_interest_rate'] = 't_interest_rate';
        $this->tb['t_account_trans'] = 't_account_trans';
        $this->tb['t_customer_debit_credit_note'] = 't_customer_debit_credit_note';
        $this->tb['t_supplier_debit_credit_note'] = 't_supplier_debit_credit_note';
        $this->tb['t_serial_movement'] = 't_serial_movement';
        $this->tb['t_batch'] = 't_batch';
        $this->tb['t_cheques'] = 't_cheques';
        $this->tb['t_cheques_trans'] = 't_cheques_trans';
        $this->tb['t_cheques_issued'] = 't_cheques_issued';
        $this->tb['u_modules'] = 'u_modules';
        $this->tb['t_customer_transaction'] = 't_customer_transaction';
        $this->tb['t_supplier_transaction'] = 't_supplier_transaction';
        $this->tb['t_advance_refund_det'] = 't_advance_refund_det';
        $this->tb['t_interest_cal_date'] = 't_interest_cal_date';
        
        $this->tb['def_option_module'] = 'def_option_module';
        $this->tb['u_user_role'] = 'u_user_role';
        $this->tb['s_users'] = 's_users';
        $this->tb['u_user_role_detail'] = 'u_user_role_detail';
        $this->tb['u_add_user_role'] = 'u_add_user_role';
        $this->tb['u_add_user_role_log'] = 'u_add_user_role_log';
        
        $this->tb['t_cost_log'] = 't_cost_log';
        
        $this->tb['t_customer_credit_permission'] = 't_customer_credit_permission';
        $this->tb['t_customer_credit_permission_senders'] = 't_customer_credit_permission_senders';
        
        $this->tb['t_purchse_order_sum'] = 't_purchse_order_sum';
        $this->tb['t_purchse_order_det'] = 't_purchse_order_det';
        
        $this->tb['t_supplier_cheques'] = 't_supplier_cheques';
        $this->tb['t_customer_cheques'] = 't_customer_cheques';
        
        $this->tb['t_cash_sales_sum'] = 't_cash_sales_sum';
        $this->tb['t_cash_sales_det'] = 't_cash_sales_det';
                
        $this->tb['t_supplier_acc_trance'] = 't_supplier_acc_trance';
        $this->tb['t_customer_acc_trance'] = 't_customer_acc_trance';
        
        $this->tb['t_supplier_receipt_sum'] = 't_supplier_receipt_sum';
        $this->tb['t_supplier_receipt_det'] = 't_supplier_receipt_det';
        
        $this->tb['t_customer_receipt_sum'] = 't_customer_receipt_sum';
        $this->tb['t_customer_receipt_det'] = 't_customer_receipt_det';
        
        $this->tb['t_advance_pay_sum'] = 't_advance_pay_sum';
        $this->tb['t_advance_pay_det'] = 't_advance_pay_det';
        $this->tb['t_advance_pay_trance'] = 't_advance_pay_trance';
        $this->tb['t_advance_refund'] = 't_advance_refund';
        
        
        $this->tb['t_customer_settle_sum'] = 't_customer_settle_sum';
        $this->tb['t_customer_settle_det'] = 't_customer_settle_det';
        
        $this->tb['t_supplier_settle_sum'] = 't_supplier_settle_sum';
        $this->tb['t_supplier_settle_det'] = 't_supplier_settle_det';
        
        $this->tb['t_purchse_sum'] = 't_purchse_sum';
        $this->tb['t_purchse_det'] = 't_purchse_det';
        
        $this->tb['t_cheque_book_sum'] = 't_cheque_book_sum';
        $this->tb['t_cheque_book_det'] = 't_cheque_book_det';
        
        $this->tb['t_purchse_return_sum'] = 't_purchse_return_sum';
        $this->tb['t_purchse_return_det'] = 't_purchse_return_det';
        
        $this->tb['t_open_stock_sum'] = 't_open_stock_sum';
        $this->tb['t_open_stock_det'] = 't_open_stock_det';
        
        $this->tb['t_stock_adj_sum'] = 't_stock_adj_sum';
        $this->tb['t_stock_adj_det'] = 't_stock_adj_det';
        
        $this->tb['t_purchse_order_trance'] = 't_purchse_order_trance';
        $this->tb['t_item_movement'] = 't_item_movement';
        
        $this->tb['t_credit_sales_sum'] = 't_credit_sales_sum';
        $this->tb['t_credit_sales_det'] = 't_credit_sales_det';

        $this->tb['t_cash_sales_sum'] = 't_cash_sales_sum';
        $this->tb['t_cash_sales_det'] = 't_cash_sales_det';
        
        $this->tb['t_sales_return_sum'] = 't_sales_return_sum';
        $this->tb['t_sales_return_det'] = 't_sales_return_det';
        
        $this->tb['t_damage_free_issu_sum'] = 't_damage_free_issu_sum';
        $this->tb['t_damage_free_issu_det'] = 't_damage_free_issu_det';
        
        $this->tb['t_price_change'] = 't_price_change';
        $this->tb['m_sub_item_list'] = 'm_sub_item_list';
        $this->tb['t_dispatch_sum'] = 't_dispatch_sum';
        $this->tb['t_dispatch_det'] = 't_dispatch_det';
        
        $this->tb['t_dispatch_return_sum'] = 't_dispatch_return_sum';
        $this->tb['t_dispatch_return_det'] = 't_dispatch_return_det';
        $this->tb['t_dispatch_trance'] = 't_dispatch_trance';
        
        $this->tb['t_sales_trance'] = 't_sales_trance';
        $this->tb['t_purchse_trance'] = 't_purchse_trance';
		
		$this->tb['r_brand'] = 'r_brand';
		$this->tb['r_department']='r_department';
		$this->tb['r_additional_item']='r_additional_item';
		$this->tb['r_unit']='r_unit';
		$this->tb['r_category']='r_category';
		$this->tb['r_sub_item'] = 'r_sub_item';
		$this->tb['r_sub_category']='r_sub_category';
		$this->tb['r_sup_category']='r_sup_category';
        
		$this->tb['r_area'] = 'r_area';
		$this->tb['r_cus_category']='r_cus_category';
        $this->tb['r_customer_type']='r_customer_type';

		$this->tb['m_branch']='m_branch';
		$this->tb['m_cluster']='m_cluster';
        $this->tb['r_town']='r_town';
        $this->tb['r_nationality']='r_nationality';
        $this->tb['m_item']='m_item';
        $this->tb['m_account_category']='m_account_category';
        $this->tb['m_account']='m_account';
        $this->tb['m_account_type']='m_account_type';
        $this->tb['m_bank']='m_bank';
        $this->tb['m_bank_branch']='m_bank_branch';
        $this->tb['r_payment_option']='r_payment_option';
        $this->tb['m_item_free']='m_item_free';
        $this->tb['r_groups']='r_groups';
        $this->tb['r_designation']='r_designation';
        $this->tb['m_employee']='m_employee';
        $this->tb['m_item_rol']='m_item_rol';
        $this->tb['t_op_stock']='t_op_stock';
        $this->tb['t_quotation_sum']='t_quotation_sum';
        $this->tb['r_sales_category']='r_sales_category';
        $this->tb['t_receipt']='t_receipt';
        $this->tb['t_grn_sum']='t_grn_sum';  
        $this->tb['t_privilege_card']='t_privilege_card'; 
        $this->tb['t_pur_ret_sum']='t_pur_ret_sum'; 
        $this->tb['t_previlliage_trans']='t_previlliage_trans'; 
        $this->tb['t_cus_settlement']='t_cus_settlement'; 
        $this->tb['t_sup_settlement']='t_sup_settlement'; 
        $this->tb['t_po_sum']='t_po_sum'; 
        $this->tb['m_default_account']='m_default_account'; 
        $this->tb['t_adt_adjustment_det']='t_adt_adjustment_det'; 
        $this->tb['t_reg_sum']='t_reg_sum'; 
        $this->tb['t_req_sum']='t_req_sum'; 
        $this->tb['m_account'] = 'm_account';
        $this->tb['t_voucher'] = 't_voucher';
        $this->tb['r_root'] = 'r_root';
        $this->tb['t_advance_sum'] = 't_advance_sum';
        
        $this->tb['t_debit_note'] = 't_debit_note';
        $this->tb['t_debit_note_trans']= 't_debit_note_trans';

        $this->tb['t_credit_note'] = 't_credit_note';
        $this->tb['t_credit_note_trans']= 't_credit_note_trans';

        $this->tb['t_supplier_settlement_cr_temp'] = 't_supplier_settlement_cr_temp';
        $this->tb['t_supplier_settlement_dr_temp'] = 't_supplier_settlement_dr_temp';
        
        $this->tb['t_po_trans'] = 't_po_trans';
        $this->tb['t_check_double_entry'] = 't_check_double_entry';
        $this->tb['r_credit_card_rate'] = 'r_credit_card_rate';
        $this->tb['t_special_sales_sum'] = 't_special_sales_sum';

        $this->tb['m_journal_type_sum']='m_journal_type_sum'; 
        $this->tb['m_journal_type_det']='m_journal_type_det';

        $this->tb['t_opening_bal_sum']='t_opening_bal_sum'; 
        $this->tb['t_opening_bal_det']='t_opening_bal_det';
        $this->tb['m_option_setup']='m_option_setup';

        $this->tb['t_journal_entry_sum']='t_journal_entry_sum'; 
        $this->tb['t_journal_entry_det']='t_journal_entry_det';

        $this->tb['t_payable_invoice_sum']='t_payable_invoice_sum'; 
        $this->tb['t_payable_invoice_det']='t_payable_invoice_det';
        $this->tb['t_payable_invoice_transe']='t_payable_invoice_transe';
        $this->tb['t_check_double_entry']='t_check_double_entry';

        $this->tb['t_receivable_invoice_sum']='t_receivable_invoice_sum'; 
        $this->tb['t_receivable_invoice_det']='t_receivable_invoice_det';
        $this->tb['t_receivable_invoice_transe']='t_receivable_invoice_transe';

        $this->tb['t_petty_cash_sum']='t_petty_cash_sum'; 
        $this->tb['t_petty_cash_det']='t_petty_cash_det';

        $this->tb['t_receipt_gl']='t_receipt_gl'; 
        $this->tb['t_cheque_recgl']='t_cheque_recgl';
        $this->tb['t_bank_recgl']='t_bank_recgl';
        $this->tb['t_receivable_recgl']='t_receivable_recgl';

        $this->tb['t_bank_entry']='t_bank_entry';

        $this->tb['t_cheque_issued']='t_cheque_issued'; 
        $this->tb['t_cheque_deposit_sum']='t_cheque_deposit_sum';
        $this->tb['t_cheque_deposit_det']='t_cheque_deposit_det';

        $this->tb['s_permission_level_sum']='s_permission_level_sum';
        $this->tb['s_permission_level_det']='s_permission_level_det';
            
    }
}

?>