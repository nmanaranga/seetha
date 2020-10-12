<?php if($this->user_permissions->is_view('t_loyalty_card_issue')){ ?>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
	<script type='text/javascript' src='<?=base_url()?>js/t_loyalty_card_issue.js'></script>

	<h2>Loyalty Card Issue</h2>
	<div class="dframe" id="mframe">
		<table style="width:100%;" id="tbl1" border="0">
			<tr>
				<td valign="top" class="content">

					<form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_loyalty_card_issue" >
						<table style="width:100%;" id="tbl2" border="0">
							<tr>
								<td width="120">Card No</td>
								<td><input type="text" class="input_txt" style="width:100%" id="card_no" name="card_no" style="width:100px;" maxlength="20" title="" />
									<input type="hidden" class="input_txt" style="width:100%" id="code_" name="code_" style="width:100px;" maxlength="20" title="" /></td>
									<td width="300">&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Issue Date</td>
									<td style="width: 120px;">
										<?php if($this->user_permissions->is_back_date('t_loyalty_card_issue')){ ?>
											<input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" style="width:100%" title="<?=date('Y-m-d')?>" />
										<?php } else { ?>
											<input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate" style="width:100%" title="<?=date('Y-m-d')?>" />
										<?php } ?>	
									</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Expire Date</td>
									<td style="width: 120px;">
										<?php if($this->user_permissions->is_back_date('t_loyalty_card_issue')){ ?>
											<input type="text" class="input_txt" readonly="readonly" name="edate" style="width:100%" id="edate" title="<?=date('Y-m-d', strtotime('+2 years'))?>" />
										<?php } else { ?>
											<input type="ext" class="input_txt" readonly="readonly" name="edate" id="edate" style="width:100%" title="<?=date('Y-m-d', strtotime('+2 years'))?>"/>	
										<?php } ?>	
									</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4">&nbsp;</td>
								</tr>
								<tr>
									<td>Customer Code</td>
									<td><input type="text" class="input_txt" id="customer_id" name="customer_id" title="" style="width: 100%;" /></td>
									<td><input type="text" name="customer_des" class="hid_value" id="customer_des" style="width:100%;" maxlength="10"/></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Address</td>
									<td colspan="2"><input type="text" name="address" class="input_txt" id="address" style="width:455px;" /></td>
									<td>&nbsp;</td>
								</tr>

								<tr>
									<td>TP</td>
									<td colspan="2"><input type="text" class="input_txt" id="tp" name="tp" style="width:100%;" maxlength="15"/></td>
									<td>&nbsp;</td>
								</tr>

								<tr>
									<td>Email</td>
									<td colspan="2"><input type="text" name="email" class="input_txt" id="email" style="width:455px;" /></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4">&nbsp;</td>
								</tr>
								<tr>
									<td>Card Category</td>
									<td><input type="text" class="input_txt" id="card_cat" name="card_cat" title="" style="width: 100%;" /></td>
									<td><input type="text" name="card_cat_name" class="hid_value" id="card_cat_name" style="width:100%;" maxlength="10"/></td>
									<td>&nbsp;</td>
								</tr>

							</table>



						</td>

					</tr>
					<tr>
						<td style="text-align:left" colspan="3">
							<input type="hidden" id="code_" name="code_" title="0" />
							<input type="button" id="btnExit" title='Exit' />
							<input type="button" id="btnReset" title='Reset'>
							<?php if($this->user_permissions->is_delete('t_loyalty_card_issue')){ ?><input type="button" id="btnDelete" title='Delete' /><?php } ?>
							<?php if($this->user_permissions->is_re_print('t_loyalty_card_issue')){ ?><input type="button" id="btnPrint" title='Print'><?php } ?>
							<?php if($this->user_permissions->is_add('t_loyalty_card_issue')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>

						</td>
					</tr>
				</table><!--tbl2-->
			</form><!--form_-->

		</td>

	</tr>
</table><!--tbl1-->
<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

	<input type="hidden" name='by' value='t_loyalty_card_issue' title="t_loyalty_card_issue" class="report">
	<input type="hidden" name='page' value='A4' title="A4" >
	<input type="hidden" name='orientation' value='P' title="P" >
	<input type="hidden" name='type' value='19' title="19" >
	<input type="hidden" name='header' value='false' title="false" >
	<input type="hidden" name='qno' value='' title="" id="qno" >
	<input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
	<input type="hidden" name="sales_type" id="sales_type" value="" title="" >
	<input type="hidden" name='dt' value='' title="" id="dt" >
	<input type="hidden" name='card_no1'  id="card_no1" >
	<input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >

</form>
</div>
<?php } ?>
