<?php if($this->user_permissions->is_view('t_loyalty_card_category')){ ?>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
	<script type='text/javascript' src='<?=base_url()?>js/t_loyalty_card_category.js'></script>

	<h2>Loyalty Card Category Setup</h2>
	<div class="dframe" id="mframe" style="width: 1240px;">
		<table style="width:100%;" id="tbl1">
			<tr>
				<td valign="top" class="content">
					<form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_loyalty_card_category" >
						<table style="width:100%;" id="tbl2" border="0">
							<tr>
								<td style="width: 120px;"><b>Code</b></td>
								<td style="width: 120px;"><input type="text" id="code" class="input_active" title="<?=$max_no?>" name="code" readonly="readonly" />
									<input type="hidden" id="code_" class="input_active" title="" name="code_" /></td>
								<td colspan="2"> </td>
							</tr>
							<tr>
								<td style="width: 120px;"><b>Name</b></td>
								<td colspan="3"> <input type="text" class="input_active" name="description" id="description"  title=""  style="width:100%" /></td>
							</tr>
							<tr>
								<td style="width: 120px;"><b>Card Category</b></td>
								<td style="width: 120px;"><input type="text" id="card_cat" class="input_active" title="" name="card_cat" /></td>
								<td colspan="2"> <input type="text" class="hid_value" id="card_cat_name"  title="" readonly="readonly" style="width:100%" /></td>
							</tr>
							<tr>
								<td style="width: 120px;"><b>Earn Rs</b> </td>
								<td style="width: 120px;"><input type="text" id="earn_rs" class="input_active g_input_amo" title="" name="earn_rs" /></td>
								<td style="width: 30px;text-align: center;"><b>As</b></td>
								<td ><input type="text" id="earn_point" class="input_active g_input_amo" title="" name="earn_point" />&nbsp;<b>Point</b></td>

							</tr>
							<tr>
								<td style="width: 120px;"><b>Redeem Point</b> </td>
								<td style="width: 120px;"><input type="text" id="red_point" class="input_active g_input_amo" title="" name="red_point" /></td>
								<td style="width: 30px;text-align: center;"><b>As</b></td>
								<td ><input type="text" id="red_rs" class="input_active g_input_amo" title="" name="red_rs" />&nbsp;<b>Rupees</b></td>
							</tr>
							<tr>
								<td style="width: 120px;"><b>Update Point Level</b> </td>
								<td style="width: 120px;"><input type="text" id="upd_point" class="input_active g_input_amo" title="" name="upd_point" /></td>
							</tr>

							<tr>
								<td style="text-align:right" colspan="6">
									<input type="hidden" id="code_" name="code_" title="0" />
									<input type="button" id="btnExit" title='Exit' />
									<input type="button" id="btnReset" title='Reset'>
									<?php if($this->user_permissions->is_add('t_loyalty_card_category')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>

								</td>
							</tr>
						</table><!--tbl2-->
					</form><!--form_-->
				</td>
				<td valign="top" class="content" style="width: 600px;">
					<div class="form" id="form" style="width:600px;" >
						
						<div id="grid_body"><?=$table_data;?></div>
					</div>
				</td>
			</tr>
		</table>
		
	</div>
<?php } ?>
