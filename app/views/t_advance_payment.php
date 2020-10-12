<?php if($this->user_permissions->is_view('t_advance_payment')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_advance_payment.js"></script>


<div id="fade" class="black_overlay"></div>
<?php $this->load->view('t_payment_option.php'); ?>

<h2 style="text-align: center;">Advance Receipt</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
   <!--<form method="post" action="<?=base_url()?>index.php/main/save/t_advance_payment" id="form_">-->

	 <table style="width: 100%" border="0">


	         <tr>
	            <td style="width: 100px;"></td>
	            <td style="width: 600px;"></td>
	            <td style="width: 100px; padding-left:30px;">No</td>
	            <td>
	               <input type="text" class="input_active_num" style="width:100%" name="id" id="id" title="<?=$max_no?>" />
	               <input type="hidden" id="hid" name="hid" title="0" />
	            </td>
	         </tr>


	         <tr>
	         	<td style="width: 100px;"></td>
	            <td style="width: 600px;"></td>
	            <td style="width: 100px; padding-left:30px;">Date</td>
	         	<td style="width: 100px;">
	         		<?php if($this->user_permissions->is_back_date('t_advance_payment')){ ?>
	         			<input type="text" class="input_date_down_future" style='text-align:right;' readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
	         		<?php } else{ ?>
	         			<input type="text" class="input_txt" readonly="readonly" style='text-align:right;' name="date" id="date" title="<?=date('Y-m-d')?>" />
	         		<?php } ?>
	         	</td>
	         </tr>


	  		 <tr>
	         	<td style="width: 100px;"></td>
	            <td style="width: 600px;"></td>
	            <td style="width: 100px; padding-left:30px;">Reference No</td>
	         	<td style="width: 100px;">
	         	<input type="text" class="input_active_num" name="ref_no" id="ref_no" title="" style="width:100%;"/>
	         	</td>
	         </tr>



	         <tr>
	         	<td style="width: 100px;">Customer</td>
	            <td>
	            	<input type="text" id="customer" name="customer"class="input_active" title=""/>
	            	<input type="text" class="hid_value" id="customer_id" title="" readonly="readonly" style="width: 300px;" />
	           		<input type="button" id="cus_s" title="...">
	           </td>
	           	<td style="width: 100px; padding-left:30px;">CN NO</td>
	            <td>
	               <input type="text" class="input_active_num" name="cn_no" readonly="readonly" id="cn_no" title="<?=$max_cn_no?>" style="width:100%;"/>
	            </td>
	         </tr>



	         <tr>
	            <td>Description</td>
	            <td>
	               <textarea rows='2' cols='50' name='description' id='description' class="input_txt" style="width:455px;"></textarea>
	            </td>
	            <td style="width: 100px;"></td>
	            <td style="width: 100px;"></td>
	         </tr>



	         <tr>
	            <td>Valid Till</td>
	            <td>
	               <input type="text" class="input_date_down_future" readonly="readonly" name="edate" id="edate" title="<?=date('Y-m-d')?>" />
	            </td>
	            <td style="width: 100px;"></td>
	            <td style="width: 100px;"></td>
	         </tr>



	          <tr>
	            <td>Amount</td>
	            <td>
	               <input type="text" class="g_input_amo" name="net" id="net" title="" style="border:1px solid #003399;padding:3px 0;width:150px;"/>
	            </td>
	            <td style="width: 100px;"></td>
	            <td style="width: 100px;"></td>
	         </tr>

	         <tr>
	            <td>Officer</td>
	            <td>
	               <input type="text" class="input_txt" name="officer" id="officer" title="" style="width:150px;"/>
	               <input type="text" class="hid_value" id="officer_des" readonly="readonly" style="width: 300px;" />
	               <input type="button" id="off_b" title="...">
	            </td>
	            <td style="width: 100px;"></td>
	            <td style="width: 100px;"></td>
	         </tr>


	          <tr>
	            <td colspan="7">
	               <input type="button" id="btnExit" title="Exit" />
	               <input type="button" id="btnReset" title="Reset" />
	               <?php if($this->user_permissions->is_delete('t_advance_payment')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?> 
	               <?php if($this->user_permissions->is_re_print('t_advance_payment')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?> 
	               <input type="button" id="showPayments" title="Payments" />
	               <input type="button" id="btnSavee" title='Save <F8>'/>
	               <?php if($this->user_permissions->is_add('t_advance_payment')){ ?>
	               <input type="button" id="btnSave" title='Save <F8>' />
	               <?php } ?> 
	            </td>
	         </tr>

	 </table>
	 <?php 
	if($this->user_permissions->is_print('t_advance_payment')){ ?>
	    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
	<?php } ?> 


  </form>
 
   <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_advance_payment' title="t_advance_payment" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='advance_payment' title="advance_payment" >
                 <input type="hidden" name='reciviedAmount' value='' title=""  id='reciviedAmount'>
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
                 <input type="hidden" name='org_print' value='1' title="1" id="org_print" >
        
        </form>
</div>
<?php } ?>
