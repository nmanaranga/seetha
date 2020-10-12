<?php if($this->user_permissions->is_view('t_privilege_card')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_privilege_card.js'></script>

<h2>Privilege Card Issue</h2>
<div class="dframe" id="mframe">
<table style="width:100%;" id="tbl1">
    <tr>
        <td valign="top" class="content">
            
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_privilege_card" >
					<table style="width:100%;" id="tbl2">
						<tr>
							<td colspan="2">
								<fieldset>
									<legend>Main</legend>
									<table>
										<tr>
											<td width="100">Card No</td>
											<td><input type="text" class="input_txt" style="width:100%" id="card_no" name="card_no" style="width:100px;" maxlength="20" title="" /></td>
										</tr>
										<tr>
											<td>Issue Date</td>
											<td style="width: 100px;">
												<?php if($this->user_permissions->is_back_date('t_privilege_card')){ ?>
													<input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" />
												<?php } else { ?>
													<input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" />
												<?php } ?>	
											</td>
										</tr>
										<tr>
											<td>Expire Date</td>
											<td style="width: 100px;">
												<?php if($this->user_permissions->is_back_date('t_privilege_card')){ ?>
													<input type="text" class="input_date_down_future" readonly="readonly" name="edate" id="edate" title="<?=date('Y-m-d')?>" />
												<?php } else { ?>
													<input type="ext" class="input_txt" readonly="readonly" name="edate" id="edate" title="<?=date('Y-m-d')?>" />	
												<?php } ?>	
											</td>
										</tr>

									</table>
								</fieldset>
							</td>
							<td></td>	
						</tr>

						<tr>
							<td colspan="3">
								<fieldset>
									<legend>Customer</legend>
									<table>
										<tr>
											<td width="100">ID</td>
											<td><input type="text" class="input_txt" id="customer_id" name="customer_id" title="" style="width: 130px;" />
        										<input type="hidden" id="customer_id_" name="customer_id_" title="0" /></td>
											<td><input type="text" name="id" class="hid_value" id="id" style="width:300px;" maxlength="10"/></td>
										</tr>

										<tr>
											<td>Address</td>
											<td colspan="2">
											<input type="text" name="address" class="input_txt" id="address" style="width:455px;" /></td>
										</tr>

										<tr>
											<td>TP</td>
											<td><input type="text" class="input_txt" id="tp" name="tp" style="width:100px;" maxlength="15"/></td>
										</tr>

											<tr>
											<td>Email</td>
											<td colspan="2">
											<input type="text" name="email" class="input_txt" id="email" style="width:455px;" /></td>
										</tr>

									</table>
								</fieldset>


							</td>

						</tr>
							<tr>
								<td colspan="3">
									<fieldset>
										<legend>Inactive</legend>
										<table>	
											<tr>
												<td width="100">Inactive reason</td>
												<td><input type="hidden" name="id" class="input_txt hid_value" id="id" style="width:455px;" /></td>
											</tr>

											<tr>
												<td>Inactive Date</td>
												<td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="idate" id="idate" title="<?=date('Y-m-d')?>" /></td>
											</tr>
										</table>
										</fieldset>

								</td>
							</tr>

							<tr>
								<td>
									<fieldset>
										<legend>Invoice History</legend>
										<table style="width:100%" id="tbl4">
												<thead>
													<th class="tb_head_th" style="width:50px;">Inv No</th>
													<th class="tb_head_th" style="width:80px;">Date</th>
													<th class="tb_head_th" style="width50px;">Amount</th>
												</thead>
												<tbody>
												<?php
																		   
														for($x=0; $x<5; $x++){
															echo "<tr>";
															echo "<td><input type='hidden' name='code_".$x."' id='code_".$x."' title='0' />
															<input style='background-color: #f9f9ec;' type='text' class='g_input_txt tb_row_tdr txt_align' id='invno_".$x."' name='invno_".$x."' /></td>";
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='date".$x."' name='date_".$x."'/></td>";				
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='amount_".$x."' name='amount".$x."'/></td>";				
															echo "</tr>";
														}
																			
													?>												
												</tbody>
											</table>
									</fieldset>
								</td>
								<td>
									<fieldset>
										<legend>Points</legend>
											<table>
												<tr>
													<td>Earned</td>
													<td><input type="text" id="earned" name="earned" class="input_txt tb_row_tdr txt_align" /></td>
												</tr>


												<tr>
													<td>Used</td>
													<td><input type="text" id="used" name="used" class="input_txt tb_row_tdr txt_align" /></td>
												</tr>

												<tr>
													<td>Balance</td>
													<td><input type="text" id="balance" name="balance" class="input_txt tb_row_tdr txt_align" /></td>
												</tr>

											</table>

									</fieldset>
								</td>

								<td>
									<fieldset><legend>Points History</legend>
										<table style="width:100%" id="tbl4">
												<thead>
													<tr>
														<th class="tb_head_th" style="width:50px;">TR Code</th>
														<th class="tb_head_th" style="width:50px;">TR No</th>
														<th class="tb_head_th" style="width50px;">Date</th>
														<th class="tb_head_th" style="width:50px;">+Points</th>
														<th class="tb_head_th" style="width50px;">-Points</th>
													</tr>
												</thead>
												<tbody>
													<?php
																				   
														for($x=0; $x<5; $x++){
															echo "<tr>";
															echo "<td><input type='hidden' name='code_".$x."' id='code_".$x."' title='0' />
															<input style='background-color: #f9f9ec;' type='text' class='g_input_txt tb_row_tdr txt_align' id='trcode_".$x."' name='trcode_".$x."' /></td>";
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='trno_".$x."' name='trno_".$x."'/></td>";
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='ddate".$x."' name='ddate_".$x."'/></td>";				
																			
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='point1".$x."' name='point1".$x."'/></td>";				
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='point2_".$x."' name='point2_".$x."'/></td>";				
															
															echo "</tr>";
														}
																			
													?>											
												</tbody>
											</table>

									</fieldset>

								</td>

							</tr>
						<tr>
							<td style="text-align:left" colspan="3">
								<input type="hidden" id="code_" name="code_" title="0" />
								<input type="button" id="btnExit" title='Exit' />
								<input type="button" id="btnReset" title='Reset'>
								<?php if($this->user_permissions->is_delete('t_privilege_card')){ ?><input type="button" id="btnDelete" title='Delete' /><?php } ?>
								<?php if($this->user_permissions->is_re_print('t_privilege_card')){ ?><input type="button" id="btnPrint" title='Print'><?php } ?>
								<?php if($this->user_permissions->is_add('t_privilege_card')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
								                        
							</td>
						</tr>
					</table><!--tbl2-->
					<?php 
					if($this->user_permissions->is_print('t_privilege_card')){ ?>
					    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
					<?php } ?> 
                </form><!--form_-->
            
      </td>
			
    </tr>
</table><!--tbl1-->
        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_privilege_card' title="t_privilege_card" class="report">
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
