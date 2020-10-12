

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link type="image/png" href="<?=base_url(); ?>img/ico.png" rel="icon">
<title>Seetha (pvt) LTD</title>
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/main.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/inputs.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/grid.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery-ui-1.8.4_.custom.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery.tablescroll.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery.autocomplete.css" />
<link href="<?=base_url(); ?>css/superfish.css" rel="stylesheet" type="text/css">
<!-- <link href="<?=base_url(); ?>css/slider.css" rel="stylesheet" type="text/css"> -->
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/menu.css" />

<!-- <script type="text/javascript" src="<?=base_url(); ?>js/jquery.js"></script> -->


<script type="text/javascript" src="<?=base_url(); ?>js/slides.min.jquery.js"></script>
<!-- <script type="text/javascript" src="<?=base_url(); ?>superfish.js"></script> -->
<script type="text/javascript" src="<?=base_url(); ?>js/script.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/autoNumeric1.6.2.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.tablescroll.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.form.js"></script>

<script type="text/javascript" src="<?=base_url(); ?>js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/inputs.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/menu.js"></script>

<script>
$(function(){
    $('#slides').slides({
        preload: true,
        generateNextPrev: true
    });
});
</script>
<style type="text/css" media="screen">
.slides_container { width:630px; display:none; margin: auto;}
.slides_control{ height:440px !important; }
.prev{ position: absolute; top: 50px; text-decoration: none;}
.next{position: absolute; top: 50px; right:0; text-decoration: none;}
.slides_container div.slide { width:600px; height:170px; display:block; }
.item { float:left; width:135px;  height:135px; margin:0 10px; background:#efefef; }
.pagination { list-style:none;  margin:0; padding:0; display:none;}
.container{ width:750px; margin:auto; position: relative;}
.pagination .current a { color:red;}
</style>
    </head>
    <body ondragstart="return false;" ondrop="return false;">
    
    <div id="blocker"></div>
    <div id="logout">
      
        </div>
    </div>
    <div class="company_wrapper">
        <div class="company">
        <div id="logo"><!--<img src="<?=base_url(); ?>images/logo.jpg" alt="" >--></div>
        <div class="company_name"><h1><?php echo $company; ?></h1></div>
        </div>
    </div>
    <div id="slides">
        <div class="container">
        <div class="slides_container">
            <div class="slide">
            <div id="menu">
                <ul class="menu">

                
                <?php 

            if($this->user_permissions->is_view('m_stores')||
                 $this->user_permissions->is_view('r_department')||
                 $this->user_permissions->is_view('r_category')|| 
                 $this->user_permissions->is_view('r_sub_cat')||
                 $this->user_permissions->is_view('r_units')||
                 $this->user_permissions->is_view('r_brand')||
                 $this->user_permissions->is_view('r_subitem')||
                 $this->user_permissions->is_view('m_items')||
                 $this->user_permissions->is_view('r_additional_items')||
                 $this->user_permissions->is_view('m_item_free')||
                 $this->user_permissions->is_view('m_item_rol')||
                 $this->user_permissions->is_view('t_price_change_sum')||
                 $this->user_permissions->is_view('t_price_change_batch_sum')||
                 $this->user_permissions->is_view('return_reason')||
                 $this->user_permissions->is_view('r_area')||
                 $this->user_permissions->is_view('r_town')||
                 $this->user_permissions->is_view('r_root')|| 
                 $this->user_permissions->is_view('r_nationality')||
                 $this->user_permissions->is_view('r_cus_category')||
                 $this->user_permissions->is_view('r_customer_type')||
                 $this->user_permissions->is_view('r_sup_category')||
                 $this->user_permissions->is_view('m_supplier')||
                 $this->user_permissions->is_view('m_account_category')||
                 $this->user_permissions->is_view('m_account_type')||
                 $this->user_permissions->is_view('m_bank')||
                 $this->user_permissions->is_view('m_bank_branch')||
                 $this->user_permissions->is_view('m_default_account')||
                 $this->user_permissions->is_view('m_account')||
                 $this->user_permissions->is_view('r_journal_type'))
                {
          ?>
                <li><a href="#" class="parent"><span>Master</span><img src="<?php echo base_url(); ?>images/master.png" alt=""  /></a>
                    <ul>
                         
                         <?php if($this->user_permissions->is_view('m_stores')||
                         $this->user_permissions->is_view('r_department')||
                         $this->user_permissions->is_view('r_category')|| 
                         $this->user_permissions->is_view('r_sub_cat')||
                         $this->user_permissions->is_view('r_units')||
                         $this->user_permissions->is_view('r_brand')||
                         $this->user_permissions->is_view('r_subitem')||
                         $this->user_permissions->is_view('m_items')||
                         $this->user_permissions->is_view('r_additional_items')||
                         $this->user_permissions->is_view('m_item_free')||
                         $this->user_permissions->is_view('m_item_rol')||
                         $this->user_permissions->is_view('t_price_change_sum')||
                         $this->user_permissions->is_view('t_price_change_batch_sum')||
                         $this->user_permissions->is_view('m_free_issue')||
                         $this->user_permissions->is_view('m_free_issue_sales')||
                         $this->user_permissions->is_view('return_reason'))
                        { 
                         
                ?>         
                    <li><a href="#" class="parent"><span>Item</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('m_stores')){ ?><li ><a target="_blank" href="?action=m_stores">Store</a></li><?php } ?>
                            <hr class="hline"/> 
                            <?php if($this->user_permissions->is_view('r_department')){ ?><li ><a target="_blank" href="?action=r_department">Department</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_category')){ ?><li><a target="_blank" href="?action=r_category">Category</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_sub_cat')){ ?><li><a target="_blank" href="?action=r_sub_cat">Sub Category</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_units')){ ?><li><a target="_blank" href="?action=r_units">Unit</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_brand')){ ?><li><a target="_blank" href="?action=r_brand">Brand</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('r_subitem')){ if($ds['use_sub_items']){ ?> <li><a target="_blank" href="?action=r_subitem">Sub Item</a></li><?php }} ?>
                            <?php if($this->user_permissions->is_view('m_items')){ ?><li><a target="_blank" href="?action=m_items">Item Details</a></li><?php } ?>
                            <hr class="hline"/>
                                            
                            <?php if($this->user_permissions->is_view('r_additional_items')){ ?><li><a target="_blank" href="?action=r_additional_items">Additional Item</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('m_item_free')){ ?><li><a target="_blank" href="?action=m_item_free">Item Free Issue(FOC)</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('m_free_issue')){ ?><li><a target="_blank" href="?action=m_free_issue">FOC Bulk Purchase</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('m_free_issue_sales')){ ?><li><a target="_blank" href="?action=m_free_issue_sales">FOC Bulk Sales</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('m_item_rol')){ ?><li><a target="_blank" href="?action=m_item_rol">Branch wise Re-Order Level</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_price_change_sum')){ ?><li><a target="_blank" href="?action=t_price_change_sum">Price Change</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_price_change_batch_sum')){ ?><li><a target="_blank" href="?action=t_price_change_batch_sum">Price Change In Batch</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('return_reason')){ ?><li><a target="_blank" href="?action=return_reason">Sales\Purchase Return Reason</a></li><?php } ?>
                        </ul>
                </li>
                <?php } ?>
                
                <?php
                    if($this->user_permissions->is_view('r_area')||
                         $this->user_permissions->is_view('r_town')||
                         $this->user_permissions->is_view('r_root')|| 
                         $this->user_permissions->is_view('r_nationality')||
                         $this->user_permissions->is_view('r_cus_category')||
                         $this->user_permissions->is_view('r_customer_type'))
                    { 
                        
                ?>
                <li><a href="#" class="parent"><span>Customer</span></a>
                    <ul>
                        <?php if($this->user_permissions->is_view('r_area')){ ?><li><a target="_blank" href="?action=r_area">Area</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('r_town')){ ?><li><a target="_blank" href="?action=r_town">Town</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('r_root')){ ?><li><a target="_blank" href="?action=r_root">Root</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('r_nationality')){ ?><li><a target="_blank" href="?action=r_nationality">Nationality</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('r_cus_category')){ ?><li><a target="_blank" href="?action=r_cus_category">Customer Category</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('r_customer_type')){ ?><li><a target="_blank" href="?action=r_customer_type">Customer Type</a></li><?php } ?>
                        <hr class="hline"/>
                        <?php if($this->user_permissions->is_view('m_customer')){ ?><li><a target="_blank" href="?action=m_customer">Customer</a></li><?php } ?>
                    </ul>
                </li>
                <?php } ?>
                
                <?php 
                if($this->user_permissions->is_view('r_sup_category')||
                     $this->user_permissions->is_view('m_supplier'))
                { 
                    
                ?>      
                <li><a href="#" class="parent"><span>Supplier</span></a>
                       <ul>
                            <?php if($this->user_permissions->is_view('r_sup_category')){ ?><li><a target="_blank" href="?action=r_sup_category">Supplier Category</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('m_supplier')){ ?><li><a target="_blank" href="?action=m_supplier">Supplier</a></li><?php } ?>
                        </ul>
                </li>
                <?php } ?>

                <?php 
                if(  $this->user_permissions->is_view('r_credit_card_rate')||
                     $this->user_permissions->is_view('m_account_category')||
                     $this->user_permissions->is_view('m_account_type')||
                     $this->user_permissions->is_view('m_bank')||
                     $this->user_permissions->is_view('m_bank_branch')||
                     $this->user_permissions->is_view('m_default_account')||
                     $this->user_permissions->is_view('m_account')||
                     $this->user_permissions->is_view('r_journal_type'))
                { 
                    
                ?>  
                <li><a href="#" class="parent"><span>Account</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('m_account_category')){ if(false){ ?><li><a target="_blank" href="?action=m_account_category">Account Category</a></li><?php } }?>
                            <?php if($this->user_permissions->is_view('m_account_type')){ ?><li><a target="_blank" href="?action=m_account_type">Account Type</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('m_bank')){ ?><li><a target="_blank" href="?action=m_bank">Bank</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('m_bank_branch')){ ?><li><a target="_blank" href="?action=m_bank_branch">Bank Branch</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('m_default_account')){ ?><li><a target="_blank" href="?action=m_default_account">Default Account</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('m_account')){ if(false){ ?><li><a target="_blank" href="?action=m_account">Account Setup</a></li><?php } }?>
                            <?php if($this->user_permissions->is_view('r_journal_type')){ ?><li><a target="_blank" href="?action=r_journal_type">Journal Type</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_credit_card_rate')){ ?><li><a target="_blank" href="?action=r_credit_card_rate">Credit Card Rate Setup</a></li><?php } ?>
                        </ul>
                </li>
                <?php } ?>

                </ul>
                </li>
<?php } ?>


  <?php 

        if($this->user_permissions->is_view('r_groups')||
             $this->user_permissions->is_view('r_sales_category')||
             $this->user_permissions->is_view('t_quotation_sum')||
             $this->user_permissions->is_view('t_cash_sales_sum')||
             $this->user_permissions->is_view('t_credit_sales_sum')||
             $this->user_permissions->is_view('t_sales_return_sum')||
             $this->user_permissions->is_view('t_sales_return_sum_without_invoice')||
             $this->user_permissions->is_view('t_receipt')||
             $this->user_permissions->is_view('t_cus_settlement')||
             $this->user_permissions->is_view('t_req_sum')||
             $this->user_permissions->is_view('t_req_approve_sum')||
             $this->user_permissions->is_view('t_po_sum')||
             $this->user_permissions->is_view('t_grn_sum')||
             $this->user_permissions->is_view('t_pur_ret_sum')||
             $this->user_permissions->is_view('t_voucher')||
             $this->user_permissions->is_view('t_sup_settlement')||
             $this->user_permissions->is_view('t_open_stock')||
             $this->user_permissions->is_view('t_serial_adjustment_sum')||
             $this->user_permissions->is_view('t_adjustment_sum')||
             $this->user_permissions->is_view('t_damage_sum')||
             $this->user_permissions->is_view('t_dispatch_sum')||
             $this->user_permissions->is_view('t_dispatch_unloading')||
             $this->user_permissions->is_view('t_dispatch_note')||
             $this->user_permissions->is_view('t_privilege_card')||
             $this->user_permissions->is_view('t_privilege_card_update')||
             $this->user_permissions->is_view('t_privilage_trans')||
             $this->user_permissions->is_view('t_job')||
             $this->user_permissions->is_view('t_job_update')||
             $this->user_permissions->is_view('t_job_reject')||
             $this->user_permissions->is_view('t_job_receive')||
             $this->user_permissions->is_view('t_job_issue')||
             $this->user_permissions->is_view('t_repair_dash_bord')||
             
             $this->user_permissions->is_view('t_credit_note')||
             $this->user_permissions->is_view('t_debit_note')||
             $this->user_permissions->is_view('r_credit_card_rate')||
             $this->user_permissions->is_view('t_advance_payment')||
             $this->user_permissions->is_view('t_receipt_general')||
             $this->user_permissions->is_view('t_gift_voucher')||
             $this->user_permissions->is_view('t_opening_balance')||
             $this->user_permissions->is_view('t_journal_sum')||
             $this->user_permissions->is_view('t_payable_invoice')||
             $this->user_permissions->is_view('t_receivable_invoice')||
             $this->user_permissions->is_view('t_pettycash')||
             $this->user_permissions->is_view('t_account_receipt')||
             $this->user_permissions->is_view('t_bank_entry')||
             $this->user_permissions->is_view('t_bankrec')||
             $this->user_permissions->is_view('t_cheque_deposit')||
             $this->user_permissions->is_view('t_cheques')||
             $this->user_permissions->is_view('t_cheque_issue_trans')||
             $this->user_permissions->is_view('t_receipt_temp')||
             $this->user_permissions->is_view('m_employee')||
             $this->user_permissions->is_view('r_designation')||
             $this->user_permissions->is_view('m_authorization')||
             $this->user_permissions->is_view('t_day_process')||
             $this->user_permissions->is_view('t_authorization_update'))
            {
         ?>
                <li><a href="#" class="parent"><span>Transaction</span><img src="<?php echo base_url(); ?>images/transaction.png" alt=""  /></a>
                    <ul>
                <?php 

                if($this->user_permissions->is_view('r_groups')||
                     $this->user_permissions->is_view('r_sales_category')||
                     $this->user_permissions->is_view('t_quotation_sum')||
                     $this->user_permissions->is_view('t_cash_sales_sum')||
                     $this->user_permissions->is_view('t_credit_sales_sum')||
                     $this->user_permissions->is_view('t_sales_return_sum')||
                     $this->user_permissions->is_view('t_sales_return_sum_without_invoice')||
                     $this->user_permissions->is_view('t_receipt')||
                     $this->user_permissions->is_view('t_advance_payment')||
                     $this->user_permissions->is_view('t_gift_voucher')||
                     $this->user_permissions->is_view('t_cus_settlement'))
                { 
                ?>
                    <li><a href="#" class="parent"><span>Sales</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('r_sales_category')){ ?><li><a target="_blank" href="?action=r_sales_category">Sales Category</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_groups')){ ?><li><a target="_blank" href="?action=r_groups">Group Sales</a></li><?php } ?>
                             <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_quotation_sum')){ ?><li><a target="_blank" href="?action=t_quotation_sum">Quotation</a></li><?php } ?>
                            <hr class="hline"/>

                            <?php if($this->user_permissions->is_view('t_cash_sales_sum')){ ?><li><a target="_blank" href="?action=t_cash_sales_sum">Cash Sales</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_credit_sales_sum')){ ?><li><a target="_blank" href="?action=t_credit_sales_sum">Credit Sales</a></li><?php } ?>

                            <!-- <li><a target="_blank" href="?action=t_special_sales_sum">Special Approve (Sales)</a></li> -->

                            <?php if($this->user_permissions->is_view('t_sales_return_sum')){ ?><li><a target="_blank" href="?action=t_sales_return_sum">Sales Return With Invoice</a></li><?php } ?>
                            <!-- <?php if($this->user_permissions->is_view('t_sales_return_sum_without_invoice')){ ?><li><a target="_blank" href="?action=t_sales_return_sum_without_invoice">Sales Return Without Invoice</a></li><?php } ?> -->
                            
                            <hr class="hline"/>

                            
                            <?php if($this->user_permissions->is_view('t_receipt')){ ?><li><a target="_blank" href="?action=t_receipt">Reciept</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_cus_settlement')){ ?><li><a target="_blank" href="?action=t_cus_settlement">Customer Settlement</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_advance_payment')){ ?><li><a  href="?action=t_advance_payment">Advance Payment</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_gift_voucher')){ ?><li><a  href="?action=t_gift_voucher">Gift Voucher</a></li><?php } ?>
                        </ul>
                </li>
                <?php } ?>

                <?php 
                if($this->user_permissions->is_view('t_req_sum')||
                     $this->user_permissions->is_view('t_req_approve_sum')||
                     $this->user_permissions->is_view('t_po_sum')||
                     $this->user_permissions->is_view('t_grn_sum')||
                     $this->user_permissions->is_view('t_pur_ret_sum')||
                     $this->user_permissions->is_view('t_voucher')||
                     $this->user_permissions->is_view('t_sup_settlement'))
                { 
                ?>
                <li><a href="#" class="parent"><span>Purchase</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('t_req_sum')){ ?><li><a target="_blank" href="?action=t_req_sum">Purchase Requisition</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_req_approve_sum')){ ?><li><a target="_blank" href="?action=t_req_approve_sum">Purchase Requisition Approve</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_po_sum')){ ?><li><a target="_blank" href="?action=t_po_sum">Purchase Order</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_grn_sum')){ ?><li><a target="_blank" href="?action=t_grn_sum">Purchase</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_pur_ret_sum')){ ?><li><a target="_blank" href="?action=t_pur_ret_sum">Purchase Return</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_voucher')){ ?><li><a target="_blank" href="?action=t_voucher">Supplier Payment</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_sup_settlement')){ ?><li><a target="_blank" href="?action=t_sup_settlement">Supplier Settlement</a></li><?php } ?>
                        </ul>
                </li>
                <?php } ?>  

                <?php 
                if($this->user_permissions->is_view('t_open_stock')||
                     $this->user_permissions->is_view('t_serial_adjustment_sum')||
                     $this->user_permissions->is_view('t_adjustment_sum')||
                     $this->user_permissions->is_view('t_damage_sum')||
                     $this->user_permissions->is_view('t_dispatch_sum')||
                     $this->user_permissions->is_view('t_dispatch_unloading')||
                     $this->user_permissions->is_view('t_dispatch_note'))
                { 
                ?>  
                <li><a href="#" class="parent"><span>Stock</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('t_open_stock')){ ?><li><a target="_blank" href="?action=t_open_stock">Opening Stock</a></li><?php } ?>
                            <hr class="hline"/>
                            <li><a href="?action=t_internal_transfer_order">Internal Transfer Order</a></li>
                            <li><a  href="?action=t_internal_transfer">Internal Transfer</a></li>
                            <li><a  href="?action=t_internal_transfer_receive">Internal Transfer Receive</a></li>
                            <?php if($this->user_permissions->is_view('t_serial_adjustment_sum')){ ?><li><a target="_blank" href="?action=t_serial_adjustment_sum">Serial Number Adjustment</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_adjustment_sum')){ ?><li><a target="_blank" href="?action=t_adjustment_sum">Stock Adjustment</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_damage_sum')){ ?><li><a target="_blank" href="?action=t_damage_sum">Item Damage</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_dispatch_sum')){ ?><li><a target="_blank" href="?action=t_dispatch_sum">Dispatch(Loading)</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_dispatch_unloading')){ ?><li><a target="_blank" href="?action=t_dispatch_unloading">Dispatch(Unloading)</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_dispatch_note')){ ?><li><a target="_blank" href="?action=t_dispatch_note">Dispatch Note</a></li><?php } ?>
                            
                        </ul>
                </li>  
                <?php } ?> 

                <?php 
                 if($this->user_permissions->is_view('t_privilege_card')||
                     $this->user_permissions->is_view('t_privilege_card_update')||
                     $this->user_permissions->is_view('t_privilage_trans'))
                { 

                 ?> 
                <li><a href="#" class="parent"><span>Priviliage Card</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('t_privilege_card')){ ?><li><a target="_blank" href="?action=t_privilege_card">Issue Card</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('t_privilege_card_update')){ ?><li><a target="_blank" href="?action=t_privilege_card_update">Deactivate Card</a></li><?php } ?>
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_privilage_trans')){ ?><li><a target="_blank" href="?action=t_privilage_trans">Dash Board</a></li><?php } ?>                 
                        </ul>
                </li> 
                <?php } ?>  
  
                <?php 
                if($this->user_permissions->is_view('t_job')||
                     $this->user_permissions->is_view('t_job_update')||
                     $this->user_permissions->is_view('t_job_reject')||
                     $this->user_permissions->is_view('t_job_receive')||
                     $this->user_permissions->is_view('t_job_issue')||
                     $this->user_permissions->is_view('t_repair_dash_bord'))
                { 
                ?> 
                <li><a href="#" class="parent"><span>Service Item</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('t_job')){ ?><li><a target="_blank" href="?action=t_job">Service Job</a></li><?php } ?> 
                            <?php if($this->user_permissions->is_view('t_job_update')){ ?><li><a target="_blank" href="?action=t_job_update">Send to Supplier</a></li><?php } ?> 
                            <?php if($this->user_permissions->is_view('t_job_reject')){ ?><li><a target="_blank" href="?action=t_job_reject">Reject Job</a></li><?php } ?> 
                            <?php if($this->user_permissions->is_view('t_job_receive')){ ?><li><a target="_blank" href="?action=t_job_receive">Receive From Supplier</a></li><?php } ?> 
                            <?php if($this->user_permissions->is_view('t_job_issue')){ ?><li><a target="_blank" href="?action=t_job_issue">Issue to Customer</a></li><?php } ?> 
                            <hr class="hline"/>
                            <?php if($this->user_permissions->is_view('t_repair_dash_bord')){ ?><li><a target="_blank" href="?action=t_repair_dash_bord">Dash Bord</a></li><?php } ?> 
                        </ul>
                </li>
                <?php } ?>

              <!--   <?php 
                if($this->user_permissions->is_view('m_cluster')||
                     $this->user_permissions->is_view('m_branch'))
                {
                ?>
                 <li><a href="#" class="parent"><span>Company Profile</span></a>
                        <ul>
                        <?php if($this->user_permissions->is_view('m_cluster')){ ?><li><a target="_blank" href="?action=m_cluster">Cluster</a></li><?php } ?> 
                        <?php if($this->user_permissions->is_view('m_branch')){ ?><li><a target="_blank" href="?action=m_branch">Branches</a></li><?php } ?> 
                        </ul>
                </li>  
                <?php } ?> -->

                <?php 
                 if($this->user_permissions->is_view('t_credit_note')||
                     $this->user_permissions->is_view('t_debit_note')||
                     
                    
                     $this->user_permissions->is_view('t_receipt_general')||
                    
                     $this->user_permissions->is_view('t_opening_balance')||
                     $this->user_permissions->is_view('t_journal_sum')||
                     $this->user_permissions->is_view('t_payable_invoice')||
                     $this->user_permissions->is_view('t_receivable_invoice')||
                     $this->user_permissions->is_view('t_pettycash')||
                     $this->user_permissions->is_view('t_account_receipt'))
                { 
                ?>
                <li><a href="#" class="parent"><span>Account</span></a>
                    <ul>
                       <?php if($this->user_permissions->is_view('t_credit_note')){ ?><li><a target="_blank" href="?action=t_credit_note">Credit Note</a></li><?php } ?>
                       <?php if($this->user_permissions->is_view('t_debit_note')){ ?> <li><a target="_blank" href="?action=t_debit_note">Debit Note</a></li><?php } ?>
                        <hr class="hline"/>
                      

                       <!--  <li><a target="_blank" href="?action=t_receipt">Reciept</a></li> -->
                        
                        <?php if($this->user_permissions->is_view('t_receipt_general')){ ?><li><a target="_blank" href="?action=t_receipt_general">General Reciept</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_voucher_general')){ ?><li><a target="_blank" href="?action=t_voucher_general">General Voucher</a></li><?php } ?>
                        
                        <hr class="hline"/>
                        <?php if($this->user_permissions->is_view('t_opening_balance')){ ?><li><a target="_blank" href="?action=t_opening_balance">Opening Balance</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_journal_sum')){ ?><li><a target="_blank" href="?action=t_journal_sum">Journal Entry</a></li><?php } ?>

                        <?php if($this->user_permissions->is_view('t_payable_invoice')){ ?><li><a target="_blank" href="?action=t_payable_invoice">Payable Invoice</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_receivable_invoice')){ ?><li><a target="_blank" href="?action=t_receivable_invoice">Receivable Invoice</a></li><?php } ?>

                        <?php if($this->user_permissions->is_view('t_pettycash')){ ?><li><a target="_blank" href="?action=t_pettycash">Petty Cash</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_account_receipt')){ ?><li><a target="_blank" href="?action=t_account_receipt">Reciept</a></li><?php } ?>
                        <!-- <li><a target="_blank" href="?action=t_bank_entry">Bank Entry</a></li> -->
                        <!-- <li><a target="_blank" href="?action=t_cheque_deposit">Cheque Deposit</a></li> -->
                        
                    </ul>
                </li>
                <?php } ?>

                <?php 
                 if($this->user_permissions->is_view('t_bank_entry')||
                     $this->user_permissions->is_view('t_bankrec')||
                     $this->user_permissions->is_view('t_cheque_deposit')||
                     $this->user_permissions->is_view('t_cheques')||
                     $this->user_permissions->is_view('t_cheque_issue_trans')||
                     $this->user_permissions->is_view('t_receipt_temp'))
                { 
                ?>
                <li><a href="#" class="parent"><span>Bank</span></a>
                    <ul>
                        <?php if($this->user_permissions->is_view('t_bank_entry')){ ?><li><a target="_blank" href="?action=t_bank_entry">Bank Entry</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_bankrec')){ ?><li><a target="_blank" href="?action=t_bankrec">Bank Reconciliation</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_cheque_deposit')){ ?><li><a target="_blank" href="?action=t_cheque_deposit">Cheque Deposit</a></li><?php } ?>
                       <!--  <li><a target="_blank" href="?action=t_cheque_trans">Cheque Deposit</a></li> -->
                        <?php if($this->user_permissions->is_view('t_cheques')){ ?><li><a target="_blank" href="?action=t_cheques">Cheque Return</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_cheque_issue_trans')){ ?><li><a target="_blank" href="?action=t_cheque_issue_trans">Cheque Withdraw</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_receipt_temp')){ ?><li><a target="_blank" href="?action=t_receipt_temp">Cheque Acknowledgement</a></li><?php } ?>
                                               
                    </ul>
                </li>
                <?php } ?>

                <?php 
                 if($this->user_permissions->is_view('m_employee')||
                     $this->user_permissions->is_view('r_designation'))
                { 
                ?>
                <li><a href="#" class="parent"><span>Sales Officer</span></a>
                    <ul>
                        <?php if($this->user_permissions->is_view('m_employee')){ ?><li><a target="_blank" href="?action=m_employee">Employee</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('r_designation')){ ?><li><a target="_blank" href="?action=r_designation">Employee Designation</a></li><?php } ?>
                    </ul>
                </li> 
                <?php } ?>


                <?php 
                 if($this->user_permissions->is_view('m_authorization')||
                     $this->user_permissions->is_view('t_authorization_update'))
                { 
                ?>
                 <li><a href="#" class="parent"><span>User</span></a>
                    <ul>
                        <?php if($this->user_permissions->is_view('m_authorization')){ ?><li><a target="_blank" href="?action=m_authorization">User Authorization Levels</a></li><?php } ?>
                        <?php if($this->user_permissions->is_view('t_authorization_update')){ ?><li><a target="_blank" href="?action=t_authorization_update">User Authorization</a></li><?php } ?>
                    </ul>
                </li>
                <?php } ?>          
                
                
                    

                <?php 
                if($this->user_permissions->is_view('t_day_process'))
                { 
                ?>
                    <li><a href="#" class="parent"><span>Day Process</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('t_day_process')){ ?><li><a  href="?action=t_day_process">Day Process</a></li><?php } ?> 
                        </ul>
                    </li> 
                <?php } ?>   


                </ul>
            </li>
            <?php } ?> 

            <?php 
            if($this->user_permissions->is_view('r_account_report')||
                 $this->user_permissions->is_view('r_stock_report')||
                 $this->user_permissions->is_view('r_debiter_list')||
                 $this->user_permissions->is_view('r_crediter_list')||
                 $this->user_permissions->is_view('r_transaction_list_purchase')||
                 $this->user_permissions->is_view('r_transaction_list_cash')||
                 $this->user_permissions->is_view('r_transaction_list_credit')||
                 $this->user_permissions->is_view('m_dialy_summery')||
                 $this->user_permissions->is_view('r_transaction_list_voucher'))
            { 
            ?>  
                <li>
                    <a href="#" class="parent"><span>Report</span><img src="<?php echo base_url(); ?>images/Reports.png" alt=""  /></a>
                    <ul>
                    <!-- <li><a href="#" class="parent"><span>Report</span></a>
                        <ul>
                            <li><a target="_blank" href="?action=r_transaction_list">Report</a></li>
                        </ul>
                    </li> -->

                    <?php 
                    if($this->user_permissions->is_view('r_account_chart')||
                         $this->user_permissions->is_view('r_account_report')||
                         $this->user_permissions->is_view('r_account_update')||
                         $this->user_permissions->is_view('r_credit_note')||
                         $this->user_permissions->is_view('r_debit_note'))
                    { 
                    ?>  
                        <?php if($this->user_permissions->is_view('r_account_report')){ ?><li><a target="_blank" href="?action=r_account_report" class="parent"><span>Account</span></a></li><?php } ?>         
                    <?php } ?>
                        <li><a  href="?action=r_bank_entry" class="parent"><span>Bank</span></a></li>
                    <?php 
                    if($this->user_permissions->is_view('r_stock_in_hand')||
                         $this->user_permissions->is_view('r_batch_in_hand')||
                         $this->user_permissions->is_view('r_serial_in_hand')||
                         $this->user_permissions->is_view('r_bin_card_stock')||
                         $this->user_permissions->is_view('r_item_department')||
                         $this->user_permissions->is_view('r_item_category')||
                         $this->user_permissions->is_view('r_sub_item_category')||
                         $this->user_permissions->is_view('item_lists')||
                         $this->user_permissions->is_view('r_stock_detail'))
                    { 
                    ?>  
                        <?php if($this->user_permissions->is_view('r_stock_report')){ ?><li><a target="_blank" href="?action=r_stock_report" class="parent"><span>Stock</span></a></li><?php } ?> 
                    <?php } ?>

                    <?php 
                    if($this->user_permissions->is_view('r_customer_list')||
                         $this->user_permissions->is_view('r_customer_area_list')||
                         $this->user_permissions->is_view('r_customer_town_list')||
                         $this->user_permissions->is_view('r_customer_balances')||
                         $this->user_permissions->is_view('r_customer_analysis'))
                    { 
                    ?> 
                        <?php if($this->user_permissions->is_view('r_debiter_list')){ ?><li><a target="_blank" href="?action=r_debiter_list" class="parent"><span>Debitor</span></a> </li><?php } ?>
                    <?php } ?>

                    <?php 
                    if($this->user_permissions->is_view('r_category_wise_supplier')||
                         $this->user_permissions->is_view('r_purchase_bill')||
                         $this->user_permissions->is_view('r_supplier_balances')||
                         $this->user_permissions->is_view('r_supplier_analysis'))
                    { 
                    ?> 
                        <?php if($this->user_permissions->is_view('r_crediter_list')){ ?><li><a target="_blank" href="?action=r_crediter_list" class="parent"><span>Creditor</span></a> </li><?php } ?>
                    <?php } ?>

                    <?php 
                    if($this->user_permissions->is_view('r_transaction_list_purchase')||
                         $this->user_permissions->is_view('r_transaction_list_cash')||
                         $this->user_permissions->is_view('r_transaction_list_credit')||
                         $this->user_permissions->is_view('r_transaction_list_voucher'))
                    { 
                    ?> 
                    <li><a href="#" class="parent"><span>Transaction</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('r_transaction_list_purchase')){ ?><li><a target="_blank" href="?action=r_transaction_list_purchase">Purchase</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_transaction_list_cash')){ ?><li><a target="_blank" href="?action=r_transaction_list_cash">Cash Sales</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_transaction_list_credit')){ ?><li><a target="_blank" href="?action=r_transaction_list_credit">Credit Sales</a></li><?php } ?>
                            <?php if($this->user_permissions->is_view('r_transaction_list_voucher')){ ?><li><a target="_blank" href="?action=r_transaction_list_voucher">Receipt & Voucher  Lists</a></li><?php } ?>
                        </ul>
                    </li>
                    <?php } ?> 

                    <?php 
                if($this->user_permissions->is_view('m_dialy_summery'))
                { 
                ?>
                    <li><a href="#" class="parent"><span>Summery</span></a>
                        <ul>
                            <?php if($this->user_permissions->is_view('m_dialy_summery')){ ?><li><a target="_blank" href="?action=m_dialy_summery">Daily Summery</a></li><?php } ?> 
                        </ul>
                    </li> 

                <?php } ?> 

                </ul>
            </li>
            <?php } ?> 

            <?php 
            if($this->user_permissions->is_view('s_company')||
                $this->user_permissions->is_view('m_cluster')||
                 $this->user_permissions->is_view('m_branch')||
                 $this->user_permissions->is_view('s_users')||
                 $this->user_permissions->is_view('s_module')||
                 $this->user_permissions->is_view('s_role')||
                 $this->user_permissions->is_view('s_add_role')||
                 $this->user_permissions->is_view('backup'))
            { 
            ?> 
                <?php if($isAdmin == 1){ ?>
                    <li><li><a href="#" class="parent"><span>System</span><img src="<?php echo base_url(); ?>images/system.png" alt="" /></a>
                        <ul>
                   
                <?php 
                    if($this->user_permissions->is_view('s_company')||
                         $this->user_permissions->is_view('m_cluster')||    
                         $this->user_permissions->is_view('m_branch'))
                    { 
                ?> 
                    <li><a  href="#">Company</a>
                        <ul>
                                <?php if($this->user_permissions->is_view('s_company')){ ?><li><a  href="?action=s_company">Company Profile</a></li><?php } ?> 
                                <?php if($this->user_permissions->is_view('m_cluster')){ ?><li><a  href="?action=m_cluster">Cluster</a></li><?php } ?>
                                <?php if($this->user_permissions->is_view('m_branch')){ ?><li><a  href="?action=m_branch">Branches</a></li><?php } ?>
                        </ul>
                    </li>

                <?php } ?>




                    <?php if($this->user_permissions->is_view('s_users')){ ?><li><a target="_blank" href="?action=s_users">Users</a></li><?php } ?>
                    <?php if($this->user_permissions->is_view('s_module')){ ?><li><a target="_blank" href="?action=s_module">Module</a></li><?php } ?>
                    <?php if($this->user_permissions->is_view('s_role')){ ?><li><a target="_blank" href="?action=s_role">User Role</a></li><?php } ?>
                    <?php if($this->user_permissions->is_view('s_add_role')){ ?><li><a target="_blank" href="?action=s_add_role">Add Role</a></li><?php } ?> 
                    <?php if($this->user_permissions->is_view('backup')){ ?><li><a target="_blank" href="index.php/main/backup" target="_blank">Backup</a></li><?php } ?> 
                </ul>
                    </li>
                    
                <?php } ?>
                <?php } ?>
                </ul>
            </div>
            </div>
            <div class="slide">
                <div id="menu">
                <ul class="menu">
                                         
                          <?php
            if($this->user_permissions->is_view('f_find_item_all')||
                 $this->user_permissions->is_view('f_find_item')||
                 $this->user_permissions->is_view('f_find_transaction_log_det')||
                 $this->user_permissions->is_view('f_find_serial'))
            { 
            ?>
            <li><a href="#"><span>Find</span><img src="<?=base_url()?>images/search.png"  width="90" height="90" /></a>

                <ul>
                    <?php if($this->user_permissions->is_view('f_find_item_all')){ ?><li><a target="_blank" href="?action=f_find_item_all">Find Item - All</a></li><?php } ?> 
                    <?php if($this->user_permissions->is_view('f_find_item')){ ?><li><a target="_blank" href="?action=f_find_item">Find Master</a></li><?php } ?> 
                    <?php if($this->user_permissions->is_view('f_find_serial')){ ?><li><a target="_blank" href="?action=f_find_serial">Find Serial</a></li><?php } ?> 
                    <?php if($this->user_permissions->is_view('f_find_transaction_log_det')){ ?><li><a  href="?action=f_find_transaction_log_det">Find Transaction Log Detail</a></li><?php } ?>  
                </ul>
            </li>
            <?php } ?>

            <?php
              if(($this->utility->is_developer())&&(
               $this->user_permissions->is_view('default_settings')|| 
               $this->user_permissions->is_view('r_payment_option')))
            {  
            ?>
            <li><a href="#"><span>Settings</span><img src="<?=base_url()?>images/settings.png"  width="90" height="90" /></a>
                <ul>
                    <?php if($this->user_permissions->is_view('default_settings')){ ?><li><a target="_blank" href="?action=default_settings">Default Settings</a></li><?php } ?> 
                    <?php if($this->user_permissions->is_view('r_payment_option')){ ?><li><a target="_blank" href="?action=r_payment_option">Payment Option Setup</a></li><?php } ?> 
                </ul>
            </li>
            <?php } ?>

                </ul>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div id="pay_form" style="display: none;">
        <fieldset style='background-color: #f9f9f9; padding: 7px;'>
        <legend>Payment Method</legend>
                Cash : <input type="text" name="cash" id="cash" title="Cash Payment" class="input_amount" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Cheque : <input type="text" name="cheque" id="cheque" title="Cheque Payment" class="input_amount" readonly='readonly' />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span id="pay_credit_label">Credit</span> : <input type="text" name="credit" id="credit" title="Credit" class="input_amount"  readonly='readonly'/>
        </fieldset>
        <fieldset>
        <legend>Cheque Details</legend>
        <table style="width: 100%;" id="tgrid2">
                    <thead>
                        <tr>
                            <th class="tb_head_th">Bank</th>
                            <th class="tb_head_th">Bank Branch</th>
                            <th class="tb_head_th" style="width: 80px;">Cheque No</th>
                            <th class="tb_head_th" style="width: 80px;">Acount No</th>
                            <th class="tb_head_th" style="width: 80px;">R. Date</th>
                            <th class="tb_head_th" style="width: 80px;">Amount</th>
                        </tr>
                    </thead><tbody>
                        <?php
                            //if will change this counter value of 10. then have to change edit model save function.
                            for($x=0; $x<10; $x++){
                                echo "<tr>";
                                    echo "<td><input type='hidden' name='qbh_".$x."' id='qbh_".$x."' title='0' />
                                                <input type='text' class='g_input_txt bank' id='q0_".$x."' name='q0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                    echo "<td><input type='hidden' name='qbbh_".$x."' id='qbbh_".$x."' title='0' />
                        <input type='text' class='g_input_txt branch'  id='qn_".$x."' name='qn_".$x."' style='width : 100%;' /></td>";
                                    echo "<td><input type='text' class='g_input_txt cheque_no' id='q1_".$x."' name='q1_".$x."' style='width : 100%;'  /></td>";
                                    echo "<td><input type='text' class='g_input_txt account_no' id='q2_".$x."' name='q2_".$x."' style='width : 100%;'  /></td>";
                                    echo "<td><input type='text' class='input_date_down_future' style='border : none; background-color : transparent;' readonly='readonly' id='q4_".$x."' name='q4_".$x."' title='' /></td>";
                                    echo "<td><input type='text' class='g_input_amo ttt' id='q3_".$x."' name='q3_".$x."' style='width : 100%;'  /></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
        </table>
        </fieldset>
        <div style="text-align: right; padding-top: 7px;">
        <input type="button" title="Close" id="btnClosePay" />
        <input type="button" title='Save <F8>' id="btnSavePay" />
        </div>
    </div>
    <!--<div id="posting_label"></div>-->
    <div id="copyright" style="display: none;">Copyright &copy; 2012 <a href="http://apycom.com/">Apycom jQuery Menus</a></div>
