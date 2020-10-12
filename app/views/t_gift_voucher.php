<?php if($this->user_permissions->is_view('t_gift_voucher')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_gift_voucher.js'></script>

<div id="fade" class="black_overlay"></div>
<?php $this->load->view('t_payment_option.php'); ?>

<h2>Gift Voucher</h2>
<div style="width:700px;margin:0 auto;">
<table style="width:100%;" id="tbl1" border="0" >
    <tr>
        <td valign="top" class="content">
            <div class="form" id="form">
                <!-- <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_gift_voucher" > -->
					<table style="width:100%;" id="tbl2" border="0">
						<tr>
								<td width="100"></td>
								<td width="300"></td>
								<td></td>
								<td></td>
						</tr>
						<tr>
							
							<td colspan="2" rowspan="3" >
								<fieldset style="width:150px;">
									<legend>Origion</legend>
									<input type="radio" class="radi" name="issue" id="issue1">Issued By Comany<br>
									<input type="radio" class="radi" name="issue" id="issue2">Issued By Customer<br>
									<input type="radio" class="radi" name="issue" id="issue3">Issued By Supplier<br>
									<input type="hidden" id="type" name="type" />
								</fieldset>
							</td>
							<td width="50">No</td>
							<td><input type="text" class="input_txt" id="no" name="no" style='text-align:right;' title="<?=$max_no?>"/>
								<input type="hidden" id="hid" name="hid" title="0" />
							</td>
						</tr>

						<tr>
							
							<td >Date</td>
							<td>
								<?php if($this->user_permissions->is_back_date('t_gift_voucher')){ ?>
									<input type="text" style="width:150px; text-align:right;" class="input_date_down_future"  readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
								<?php } else { ?>
									<input type="text" style="width:150px; text-align:right;" class="input_txt"  readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
								<?php } ?>	
							</td>
						</tr>


						<tr>
							
							<td>Ref No</td>
							<td><input type="text" class="input_txt" id="ref_no" name="ref_no"/></td>
						</tr>


						<tr>
							<td>From</td>
						<td colspan="3">

							<input type="text" class="input_txt" id="from" name="from" style="width:150px;"/>
							<input type="text" class="hid_value" id="from_des" name="from_des" style="width:355px;"/></td>
						</tr>

		
						<tr>
							<td>To</td>
							<td colspan="3">
							<input type="text" class="input_txt" id="description" name="description" style="width:510px;"/></td>
							
						</tr>


						<tr>
							<td>Amount</td>
						<td colspan="3">
							<input type="text" class="g_input_amo" name="net" id="net" title="" style="border:1px solid #003399;padding:3px 0;width:150px;"/></td>
						</tr>

						<tr>
							<td colspan="4">
								
							</td>
						</tr>
						
						<tr>
							<td style="text-align:left" colspan="4">
								<input type="hidden" id="code_" name="code_" title="0" />
								<input type="button" id="btnExit" title='Exit' />

								<input type="button" id="btnReset" title='Reset'>  
								
								<?php if($this->user_permissions->is_delete('t_gift_voucher')){ ?><input type="button" id="btnDelete" title='Delete' /><?php } ?>
								<?php if($this->user_permissions->is_re_print('t_gift_voucher')){ ?><input type="button" id="btnPrint" title='Print' /><?php } ?>
								<input type="button" id="showPayments" title='Payments' />
								<?php if($this->user_permissions->is_add('t_gift_voucher')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
								
								                      
							</td>
						</tr>
					</table><!--tbl2-->
                </form><!--form_-->
            </div><!--form-->
      </td>
		
    </tr>
</table><!--tbl1-->
</div>
<?php } ?>