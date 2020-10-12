<?php if($this->user_permissions->is_view('r_transaction_list_cash')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_transaction_list_cash.js'></script>
<script type='text/javascript' src='<?=base_url()?>js/m_client_application.js'></script>
<h2>Cash Sale Reports</h2>
<table width="100%">
	<tr>
		<td valign="top" class="content" style="width: 480px;">
			<div class="form" id="form">
				<form id = "print_pdf" class="printExcel" action="<?php echo site_url();?>" method="post" target="_blank">
					<table>
						<tr><td>Date </td><td ><input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" />
							To <input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  /></td></tr>
							<tr>
								<td>Category</td>
								<td>
									<?php echo $sales_category;?>
								</td>
							</tr>
							<tr >
								<td><span class="gr_f">Group</span></td>
								<td class="gr_f">
									<input type='text' name='group_id' id='group_id' class='input_txt'/>
									<input type='text' class='hid_value' id='group_name' name='group_name' style='width:250px;'/>
								</td>
							</tr>
							<input type='hidden' id='cluster' name='cluster' title='<?=$cl?>'/>
							<input type='hidden' id='branch' name='branch' title='<?=$bc?>' />

							<tr>
								<td>Customer</td>

								<td style="width:550px;"><input type="text" name="r_customer" id="r_customer" style="width:150px;" title="" class="input_txt ac_input input_active" autocomplete="off">
									<input type="text" style="width: 250px;" id="r_customer_des" readonly="readonly" class="hid_value"></td>
								</tr>


								<tr>

									<?php if($this->user_permissions->is_view('r_cash_sales_summary')){ ?>
									<tr>
										<td>
											<input type='radio' name='by' excel='true' value='r_cash_sales_summary' title="r_cash_sales_summary" class="report gr" /> Total Cash Sales Summary
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_cash_sales_summary2')){ ?>
									<tr>
										<td>
											<input type='radio' name='by' value='r_cash_sales_summary2' title="r_cash_sales_summary2" class="report gr" /> Total Cash Sales Summary 02
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_cash_sales_details')){ ?>
									<tr>
										<td>
											<input type='radio' name='by' value='r_cash_sales_details' title="r_cash_sales_details" class="report gr" />Cash Sales Details
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('payment_cash_sales_sum')){ ?>
									<tr>
										<td colspan="2">
											<input type='radio' name='by' value='payment_cash_sales_sum' title="payment_cash_sales_sum" class="report gr" />Payment Type Wise Sales Summery
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_cash_sales_ret')){ ?>	
									<tr>
										<td>
											<input type='radio' name='by' value='r_cash_sales_ret' title="r_cash_sales_ret" class="report" />Cash Sales Return Summary
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_cash_sales_ret2')){ ?>	
									<tr>
										<td>
											<input type='radio' name='by' value='r_cash_sales_ret2' title="r_cash_sales_ret2" class="report" />Cash Sales Return Summary 02
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_cash_sales_ret_detail')){ ?>
									<tr>
										<td>
											<input type='radio' name='by' value='r_cash_sales_ret_detail' title="r_cash_sales_ret_detail" class="report" />Cash Sales Return Details
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_cash_sales_sum')){ ?>
									<tr>
										<td>
											<input type='radio' name='by' value='r_cash_sales_sum' title="r_cash_sales_sum" class="report" />Cash Sales Summery
										</td>			    
									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_credit_card_sales_sum')){ ?>
									<tr>
										<td>
											<input type='radio' name='by' value='r_credit_card_sales_sum' title="r_credit_card_sales_sum" class="report" />Credit Card Sales Summery
										</td>			    
									</tr>
									<?php } ?>



									<?php if($this->user_permissions->is_view('r_reliance_cash_sales_sum')){ ?>
									<tr>
										<td colspan="2">
											<input type='radio' name='by' value='r_reliance_cash_sales_sum' title="r_reliance_cash_sales_sum" class="report" />Customer Sales Summery
										</td>			    
									</tr>
									<?php } ?>

									<tr>
										<td colspan="2">
											<input type='radio' name='by' value='r_pos_sales_sum' title="r_pos_sales_sum" class="report" />POS Sales Summery
										</td>			    
									</tr>

									<tr>

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