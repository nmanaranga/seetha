<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tables {
    public $tb = array();
    
    function __construct(){
        
        $this->tb['a_log_users'] = 'a_log_users';
        
        $this->tb['a_users'] = 's_users';
        $this->tb['s_company'] = 's_company';
        $this->tb['s_branches'] = 's_branches';
        $this->tb['s_reg'] = 's_reg';
        $this->tb['s_tabs'] = 's_tabs';
        
        $this->tb['m_department'] = 'm_department';
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
        $this->tb['m_main_cat'] = 'm_main_category';
        $this->tb['m_sub_cat'] = 'm_sub_category';
        $this->tb['m_units'] = 'm_units';
        $this->tb['m_items'] = 'm_items';
        $this->tb['m_supplier'] = 'm_supplier';
        $this->tb['m_banks'] = 'm_banks';
        $this->tb['m_bank_branch'] = 'm_bank_branch';
        $this->tb['m_supplier_contacts'] = 'm_supplier_contacts';
        $this->tb['m_age_analyze_setup'] = 'm_age_analyze_setup';
        $this->tb['m_age_analyze_2'] = 'm_age_analyze_2';
        
        $this->tb['defult_account'] = 'm_defult_account';
        $this->tb['m_accounts'] = 'm_accounts';
        $this->tb['t_account_trans'] = 't_account_trans';
        $this->tb['t_customer_debit_credit_note'] = 't_customer_debit_credit_note';
        $this->tb['t_supplier_debit_credit_note'] = 't_supplier_debit_credit_note';
        $this->tb['t_serial_movement'] = 't_serial_movement';
        $this->tb['t_advance_pay_history'] = 't_advance_pay_history';
        
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
        
        $this->tb['t_sales_sum'] = 't_sales_sum';
        $this->tb['t_sales_det'] = 't_sales_det';
        
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
    }
}

?>