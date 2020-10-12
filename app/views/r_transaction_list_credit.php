<?php if($this->user_permissions->is_view('r_transaction_list_credit')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_transaction_list_credit.js'></script>
<script type='text/javascript' src='<?=base_url()?>js/m_client_application.js'></script>
<h2>Credit Sale Reports</h2>
<table width="100%">
	<tr>
		<td valign="top" class="content" style="width: 480px;">
			<div class="form" id="form">
				<form id = "print_pdf" class="printExcel" action="<?php echo site_url();?>" method="post" target="_blank">

					<table id="">
						<tr>
							<td>Date</td>
							<td colspan="2">
								<input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" />
								To <input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  />
							</td>
						</tr>

						<tr>
							<td>Category</td>
							<td>
								<?php echo $sales_category;?>
							</td>
						</tr>
						<tr>
							<td><span class="gr_f">Group</span></td>
							<td><span class="gr_f">
								<input type='text' name='group_id' id='group_id' class='input_txt'/>
							</span>
						</td>
						<td>
							<span class="gr_f">
								<input type='text' class='hid_value' id='group_name' name='group_name' style='width:250px;'/>
							</span>
						</td>
					</tr>

					<input type='hidden' id='cluster' name='cluster' title='<?=$cl?>'/>
					<input type='hidden' id='branch' name='branch' title='<?=$bc?>' />

					<tr>

						<tr>
							<td style="width:150px;">Reliance Customer</td>
							<td style="width:150px;"><input type="text" name="r_customer" id="r_customer" style="width:150px;" value="<?php echo $cus_code ?>" title="<?php echo $cus_code ?>" class="input_txt ac_input input_active" autocomplete="off"></td>
							<td ><input type="text" title="<?php echo $cus_name ?>" value="<?php echo $cus_name?>" style="width: 250px;" id="r_customer_des" readonly="readonly" class="hid_value"></td> 
						</tr>


						<?php if($this->user_permissions->is_view('r_credit_sales_summary')){ ?>
						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_credit_sales_summary_2' excel='true' title="r_credit_sales_summary_2" class="report" />Total Credit Sales Summary
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_credit_sales_summary' title="r_credit_sales_summary" class="report gr" />Credit Sales Breakeup Summary
							</td>
						</tr>

						<?php } ?>

						<?php if($this->user_permissions->is_view('r_credit_sales_sum_02')){ ?>
						<tr>
							<td colspan="2"> <input type='radio' name='by' value='r_credit_sales_sum_02' title='r_credit_sales_sum_02' class="report gr"/>Credit Sales Summary 02</td>
						</tr>
						<?php } ?>

						<?php if($this->user_permissions->is_view('r_credit_sales_details')){ ?>
						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_credit_sales_details' title="r_credit_sales_details" class="report gr" />Credit Sales Details
							</td>
						</tr>
						<?php } ?>

						<?php if($this->user_permissions->is_view('r_credit_sales_ret')){ ?>
						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_credit_sales_ret' title="r_credit_sales_ret" class="report" />Credit Sales Return Summary
							</td>
						</tr>
						<?php } ?>

						<?php if($this->user_permissions->is_view('r_credit_sales_ret2')){ ?>
						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_credit_sales_ret2' title="r_credit_sales_ret2" class="report" />Credit Sales Return Summary 02
							</td>
						</tr>
						<?php } ?>

						<?php if($this->user_permissions->is_view('r_credit_sales_ret_detail')){ ?>
						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_credit_sales_ret_detail' title="r_credit_sales_ret_detail" class="report" />Credit Sales Return Details
							</td>
						</tr>
						<?php } ?>

						<?php if($this->user_permissions->is_view('r_reliance_sales_sum')){ ?>
						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_reliance_sales_sum' title="r_reliance_sales_sum" class="report" excel="true"/>Reliance Sales Summery
							</td>			    
						</tr>
						<?php } ?>

						<?php if($this->user_permissions->is_view('r_credit_sales_summary')){ ?>
						<tr>
							<td colspan="2">
								<input type='radio' name='by' value='r_credit_sales_summary_group' excel='true' title="r_credit_sales_summary_group" class="report gr" />Total Credit Sales Summary - Group
							</td>
						</tr>

						<?php } ?>


						<tr>
							<td colspan="2" style="text-align: right;">
								<input type="hidden" name="type" id="type"  title=""/>
								<input type="button" title="Exit" id="btnExit" value="Exit">
								<input type="button" id="btnPrint" title="Print PDF" value="Print PDF">
								<input type="button" id="printExcel" title="Excel" value="printExcel" disabled="true">
							</td>
						</tr>
					</table>

					<input type="hidden" name='page' value='A4' title="A4" >
					<input type="hidden" name='orientation' value='P' title="P" >
					<input type="hidden" name='type' value='19' title="19" >
					<input type="hidden" name='header' value='false' title="false" >
					<input type="hidden" name='qno' value='' title="" id="qno" >
					<input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
					<input type="hidden" name='dt' value='' title="" id="dt" >
				</form>
			</div>

		</table>
		<?php } ?>