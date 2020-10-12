<?php if($this->user_permissions->is_view('m_barcode_print')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/m_barcode_print.js'></script>

<div id="fade" class="black_overlay"></div>
<?php $this->load->view('t_serial_out.php'); ?>
<div id="fade" class="black_overlay"></div>

<h2>Barcode Print</h2>
<div align="center">
	<form id="print_pdf" style="width: 1000px;" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
		<table width="100%" >

			<tr>
				<td valign="top" align="center" class="content" >
					<div class="form" id="mframe" style="width: 1000px;">
						<table style="width: 1000px;" border="0">
							<tr>
								<td height="26" colspan="7" style="display: none;">
									<input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
									<input type="hidden" name="barcode" id="barcode" title="true" value="true" />
								</td>
							</tr>
							<tr>
								<td width="7%" height="26">Supplier</td>
								<td colspan="3"><label for="supCd"></label>
									<input type="text" name="supCd" id="supCd" class="input_active"  readonly="readonly" />
									<label for="sup_des"></label>
									<input type="text" class="hid_value" id="sup_des" style='width:300px;'/>
								</td>
								<td width="20%">&nbsp;</td>
								<td width="5%">Date</td>
								<td width="10%">
									<?php if($this->user_permissions->is_back_date('t_cash_sales_sum')){ ?>    
									<input type="text" class="input_date_down_future " readonly="readonly" style="width:100%; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
									<?php }else{?>   
									<input type="text" class="" readonly="readonly" style="width:100%; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
									<?php }?> 
									<!-- <input type="text" name="tDate" id="tDate"  class="input_active"/> -->
								</td>
							</tr>
							<tr>
								<td>GRN No.</td>
								<td colspan="3">
									<label for="lGrnNo"></label>
									<input type="text" name="lGrnNo" id="lGrnNo"  class="input_active g_input_num" />
									&nbsp;&nbsp; <b>Print : </b>
									&nbsp;&nbsp; Large&nbsp;<input type="radio" name="size" class="print_type" id="large" value="l" title="l" checked="checked">
									&nbsp;&nbsp; Small&nbsp;<input type="radio" name="size" class="print_type" id="small" value="s" title="s">
								</td>
								<td>&nbsp;</td>
								<td>No.</td>
								<td><label for="hno"></label>
									<input type="text" name="hno" id="hno" style="width:100%;"class="input_active g_input_num" value="<?=$mxNo?>" title="<?=$mxNo?>"/>
									<input type="hidden" id="by" name="by" value="m_barcode_print" title="m_barcode_print">
									<input type="hidden" id="ptype" name="ptype" value="" title="">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="3">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="7">
									<table border='0' class="tgrid" id="tgrid">
										<thead>
											<tr>
												<th style="width:100px;"  class="tb_head_th">Item Id</th>
												<th style=""  	class="tb_head_th">Item Name</th>
												<th style="width:100px;"  class="tb_head_th">Model</th>
												<th style="width:100px;"  class="tb_head_th">Batch</th>
												<th style="width:100px;"  class="tb_head_th">Selling Price</th>
												<th style="width:100px;"  class="tb_head_th">Cost Code</th>
												<th style="width:100px;"  class="tb_head_th">Qty</th>
												<th style="width:40px;"  class="tb_head_th">S.</th>
											</tr>


										</thead>

										<?php 
										$y=-1;
										for($x=0;$x<25;$x++){

											echo "<tr>";
											echo "	<td style='width:100px;' > <!-- <input type='text' style='width:100%;' id='itmId_".$x."' name='itmId_".$x."' class='g_input_txt itmId' readonly='readonly'/> -->
											<input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='width:100%;'/>
											<input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
											<input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
											<input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
											<input type='hidden' id='mxserials_".$x."' title='' name='mxserials_".$x."' />
											<!-- <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' /> </td> -->";
											echo "	<td style='width=auto;'  ><input type='text' style='width:100%;' id='itmNm_".$x."' name='itmNm_".$x."' class='g_input_txt g_col_fixed' /></td>";
											echo "	<td style='width:100px;' ><input type='text' style='width:100%;' id='itmMod_".$x."' name='itmMod_".$x."' class='g_input_txt g_col_fixed' /></td>";
											// echo "	<td style='width:100px;' ><input type='text' style='width:100%;' id='cost_".$x."' name='cost_".$x."' class='g_input_txt g_col_fixed' /></td>";
											// echo "	<td style='width:100px;' ><input type='text' style='width:100%;' id='coPrCod_".$x."' name='coPrCod_".$x."' class='g_input_txt g_col_fixed' /></td>";
											echo "	<td style='width:100px;'class='g_col_fixed' ><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float:left;height:6px;width:6px;cursor:pointer'/>
											<input type='text' id='btcno_".$x."' name='btcno_".$x."' class='g_input_txt g_input_num btcno btt_".$x."' style='margin:0;padding:0;width :70%; float: right; text-align:right;' readonly='readonly' /></td>";
											echo "	<td style='width:100px;' ><input type='text' style='width:100%;' id='selPr_".$x."' name='selPr_".$x."' class='g_input_txt g_input_num' />
											<input type='hidden' style='width:100%;' id='cost_".$x."' name='cost_".$x."' class='g_input_txt g_input_num' /></td>";
											echo "	<td style='width:100px;' ><input type='text' style='width:100%;' id='cosPr_".$x."' name='cosPr_".$x."' class='g_input_txt' /></td>";
											
											echo "	<td style='width:100px;' ><input type='text' style='width:100%;' id='qty_".$x."' name='qty_".$x."' class='g_input_txt g_input_num qty qtycl".$x."' />
											<input type='hidden' style='width:100%;' id='curqty_".$x."' name='curqty_".$x."' class='g_input_txt g_input_num ' /></td>";
											echo "	<td style='width:40px;' > <input type='checkbox'  disabled='disabled' class='chkSerial' id='chkSerial_".$x."' name='chkSerial_".$x."' style='margin:0 5px ;padding:5px;display:inline-block;'/> 
											<input type='button' disabled='disabled' class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: right; height:6px;width:6px;cursor:pointer'/>
										</td>";
										echo "</tr>";	
										$y++;
									} 

									echo "<tr style='visibility: hidden;'><td colspan='5'><input type='hidden' value='".$y."' title='".$y."' name='rCount' /></td></tr>";
									?>
								</table>

							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="3">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td width="13%"><input name="pr_name" type="checkbox" id="pr_name" checked="checked" />
								<label for="pr_name">Item Name</label>
							</td>
							<td width="13%"><input name="pr_price" type="checkbox" id="pr_price" checked="checked" />
								<label for="pr_price">Price</label></td>
								<td width="18%"><input name="model" type="checkbox" id="model" checked="checked" />
									<label for="model">Model</label></td>
									<td colspan="2" align="right">
										Total Item</td>
										<td><input type="text" name="totItms" id="totItms" style="width: 50px;" class="hid_value g_input_num" /></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td><input name="pr_comlogo" type="checkbox" id="pr_comlogo" checked="checked" /> 
											<label for="pr_comlogo">Company Logo</label>
										</td>
										<td><input name="pr_btcno" type="checkbox" id="pr_btcno" />
											<label for="pr_btcno">Batch No.</label></td>
											<td><input name="pr_icode" type="checkbox" id="pr_icode" checked="checked" />
												<label for="pr_icode">Item Code</label></td>

												<td><input name="pr_coscode" type="checkbox" id="pr_coscode" checked="checked" />
												<label for="pr_coscode">Cost Code</label></td>

												<td colspan="1" align="right">
													Total Item Qty</td>
													<td><input type="text" name="totQty" id="totQty" style="width: 50px;" class="hid_value g_input_num" /></td>
												</tr>

												<tr>
													<td>&nbsp;</td>
													<td colspan="3">&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
												<tr>

													<td colspan="7"><div align="left">
														<input type="button" title="Exit" id="btnExit" value="Exit">											
														<input type="button" title="Reset" id="btnResett" value="Reset">
														<input type="button" title="Clear History" id="btnClrHis" value="Clear History">
														<input type="button" title="Delete" id="btnDelete" value="Delete">
														<input type="button" id="btnPrint" title='Print' />
													</div></td>
												</tr>





							<!-- 					<table >

							<tr>
								<td>Code</td>
								<td>
									<input type="text" class="input_txt" title='' id="code" name="code"  readonly="readonly"style="width:150px">
									<input type="hidden" id="by" name="by" value="m_barcode_print" title="m_barcode_print">
								</td>
							</tr>
							<tr>
								<td>Item Name</td>
								<td>
									<input type="text" class="hid_value" title='' id="itmNm" name="itmNm" readonly="readonly" style="width:300px">
								</td>
							</tr>
							<tr>
								<td>Model</td>
								<td>
									<input type="text" class="hid_value" title='' id="Mdl" name="Mdl"  readonly="readonly"style="width:300px">
								</td>
							</tr>
							<tr>
								<td>Max Sales Price</td>
								<td>
									<input type="text" class="input_txt" title='' id="mxSlPris" name="mxSlPris" style="width:150px">
								</td>
							</tr>
							<tr>
								<td>No of Labels</td>
								<td>
									<input type="text" class="g_input_num input_txt" title='' id="nOLbl" name="nOLbl" style="width:150px">
								</td>
							</tr>
							<tr>
								<td>Label Type</td>
								<td>
									<select>
										<option value="S">Small</option>
										<option value="S">Small</option>
										<option value="S">Small</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Label Company Name</td>
								<td>
									<input type="text" class="hid_value" title='<?=$nme?>' id="lblCoNm" name="lblCoNm" style="width:300px">
								</td>
							</tr>

							<tr>
								<td style="text-align:right" colspan="2">
									<input type="button" id="btnPrint" title='Print' />
								</td>
							</tr>--> 

						</table>
					</div>
				</td>

			</tr>

		</table>
	</form>
</div>
<?php } ?>