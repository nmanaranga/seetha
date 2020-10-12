<?php if($this->user_permissions->is_view('t_credit_note')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_credit_note.js'></script>
<h2>Credit Note</h2>
<div style="width:700px;margin:0 auto;">
<table style="width:100%;" id="tbl1" border="0" >
    <tr>
        <td valign="top" class="content">
        	<!--<div class="dframe" id="mframe">-->
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_credit_note" >
					<table style="width:100%;" id="tbl2" border="0">
						<tr>
							<td width="100">
							
							</td>
							<td width="300"></td>
							<td width="50">No</td>
							<td>
							<input type="text" class="input_active_num" id="no" name="no" style="width:150px;" title="<?=$max_no?>" />
							<input type="hidden" id="hid" name="hid" title="0" />
							<input type="hidden" id="ref_code" name="ref_code" title="0" />
							</td>
						</tr>

						<tr>
							<td><input type="radio" name="customer" id="customer"/>Customer</td>
							<td><input type="radio" name="customer" id="supplier"/>Supplier</td>
							<input type="hidden" name="c_type" id="c_type" title='0'/>
							<td >Date</td>
							<td>
								<?php if($this->user_permissions->is_back_date('t_credit_note')){ ?>
									<input type="text" class="input_date_down_future" id="date" name="date" readonly='readonly' title="<?=date('Y-m-d')?>" style="width:150px; text-align:right;"/>
								<?php } else { ?>
									<input type="text" class="input_txt" id="date" name="date" title="<?=date('Y-m-d')?>" readonly='readonly' style="width:150px; text-align:right;"/>	
								<?php } ?>
							</td>
						</tr>

						<tr>
							<td></td>
							<td></td>
							<td>Ref No</td>
							<td><input type="text" class="input_txt" id="ref_no" name="ref_no" style="text-align:right;"/></td>
						</tr>
						<tr>
							<td>Type </td>
							<td>
								<select name="typ" id="typ">
									<option value="0">OTHER</option>
									<option value="4">CASH</option>
									<option value="5">CREDIT</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Code </td>
							<td colspan="3"><input type="text"  class="input_active s_ac" title="" name="code_s" id="code_s" />
                       		<input type="text" class="hid_value s_ac_" id="s_ac_"  name="s_ac_" title="" readonly="readonly" style="width: 355px;" /></td>
							
						</tr>
						<tr>
							<td>Opposite A/C</td>
							<td colspan="3"><input type="text" id="acc" class="input_active" title="" name="acc" />
							<input type="text" class="hid_value" id="acc_id" name="acc_id" style="width:355px;"/></td>
						</tr>
						<tr>
							<td>Amount</td>
							<td><input type="text" name="amount" id="amount" class="input_active g_input_amo" style="width:150px" /></td>
						</tr>
						<tr>
							<td>Description</td>
							<td colspan="3">
								<input type='text'   id='description' name='description' class="input_active" style="width:508px;"/>							</td>
						</tr>
						<tr>
							<td>Officer</td>
							<td colspan="3"><input type="text" id="emp" class="input_active" title="" name="emp" />
							<input type="text" class="hid_value" id="emp_des" name="emp_des" style="width:355px;"/></td>
						</tr>

						<tr>
							<td style="text-align:left" colspan="4">
								<input type="hidden" id="is_customer" name="is_customer" title="0" value="0" />
								<input type="hidden" id="code_" name="code_" title="0" />
								<input type="button" id="btnExit" title='Exit' />
								<input type="button" id="btnReset" title='Reset'>  
								<?php if($this->user_permissions->is_re_print('t_credit_note')){ ?><input type="button" id="btnPrint" title='Print' value="Print" /><?php } ?>
								<?php if($this->user_permissions->is_delete('t_credit_note')){ ?><input type="button" id="btnDelete" title='Cancel' /><?php } ?>
								<?php if($this->user_permissions->is_add('t_credit_note')){ ?><input type="button" id="btnSave" class="prntPdf" title='Save <F8>' /><?php } ?>
								<?php if($this->user_permissions->is_approve('t_credit_note')){ ?><input type="button" id="btnApprove" title='Approve' disabled /><?php } ?>
								<?php if($this->user_permissions->is_approve('t_credit_note')){ ?><input type="button" id="btnStatuss" title='Status Update' style="color:red;" /><?php } ?>
								<input type="hidden" name='is_approve' id='is_approve' title="0">                     
							</td>
						</tr>
						<?php 
					if($this->user_permissions->is_print('t_credit_note')){ ?>
					    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
					<?php } ?> 
					</table><!--tbl2-->
					<br><br>
					<label class="settle" style="display:none;"><b>Settlement Details</b></label>
					<table class="settlement" id="tgrid" style="width:100%"></table>	
                </form><!--form_-->
            </div><!--form-->
      </td>
		
    </tr>
</table><!--tbl1-->

	<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_credit_note' title="t_credit_note" class="report">
                 <input type="hidden" name='page' value='A5' title="A5" >
                 <input type="hidden" name='pdf_no' id="pdf_no" value='' title="" >
                 <input type="hidden" name='orientation' value='L' title="L" >
                 <input type="hidden" name='type1' value='Credit' title="Credit" >
                 <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='cus_or_sup' value='' title="" id="cus_or_sup" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name='dt' value='' title="" id="dt" > 
                 <input type="hidden" name='is_duplicate' value='0' title="0" id="is_duplicate" >
        
        </form>
    


</div>
</div>
<?php } ?>

