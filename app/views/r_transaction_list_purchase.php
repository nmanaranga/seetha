<?php if($this->user_permissions->is_view('r_transaction_list_purchase')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_transaction_list_purchase.js'></script>
<script type='text/javascript' src='<?=base_url()?>js/m_client_application.js'></script>
<h2>Purchase Reports</h2>
<table width="100%">
	<tr>
		<td valign="top" class="content" style="width: 480px;">
			<div class="form" id="form">
				<form id="print_pdf" class="printExcel" action="<?php echo site_url(); ?>" method="post" target="_blank">
					

					<table border="0">
						<tr>
							<td>Date </td>
							<td><input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" /></td>
							<td>	To &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  /></td>
						</tr>
						<tr>
							<td style="width:83px;">Cluster</td>
							<td><?php echo $cluster; ?></td>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<td>Branch</td>
							<td>
								<select name='branch' id='branch' >

								</select>
								<td>&nbsp;</td>
								<!--  <?php echo $branch; ?>  -->
							</td>
						</tr>
						<tr>
							<td>Supplier</td>
							<td>
								<?php echo $supplier; ?>  
							</td>
						</tr>
						<tr>

							<?php if($this->user_permissions->is_view('r_purchase_summary')){ ?>
							<tr>
								<td colspan="3">
									<input type='radio' name='by' value='r_purchase_summary' title="r_purchase_summary" class="report" excel="true" />Purchase Summary</td>

								</tr>
								<?php } ?>

								<?php if($this->user_permissions->is_view('r_purchase_details')){ ?>	
								<tr>
									<td colspan="3">
										<input type='radio' name='by' value='r_purchase_details' title="r_purchase_details" class="report" />Purchase Details</td>

									</tr>
									<?php } ?>

									<?php if($this->user_permissions->is_view('r_purchase_ret')){ ?>
									<tr>
										<td colspan="3">
											<input type='radio' name='by' value='r_purchase_ret' title="r_purchase_ret" class="report" />Purchase Return Summary</td>

										</tr>
										<?php } ?>

										<?php if($this->user_permissions->is_view('r_purchase_ret_detail')){ ?>
										<tr>
											<td colspan="3">
												<input type='radio' name='by' value='r_purchase_ret_detail' title="r_purchase_ret_detail" class="report" />Purchase Return Details</td>

											</tr>
											<?php } ?>

											<?php if($this->user_permissions->is_view('r_purchase_order_summary')){ ?>
											<tr>
												<td colspan="3">
													<input type='radio' name='by' value='r_purchase_order_summary' title="r_purchase_order_summary" class="report" />Purchase Order Summary</td>

												</tr>
												<?php } ?>

												<?php if($this->user_permissions->is_view('r_purchase_order_details')){ ?>
												<tr>
													<td colspan="3">
														<input type='radio' name='by' value='r_purchase_order_details' title="r_purchase_order_details" class="report" />Purchase Order Details</td>

													</tr>
													<?php } ?>

													<?php if($this->user_permissions->is_view('r_po_status')){ ?>
													<tr>
														<td colspan="3">
															<input type='radio' name='by' value='r_po_status' title="r_po_status" id="r_po_status" class="report"/>Purchase Request Status
														</td>
													</tr>
													<?php } ?>
													<?php if($this->user_permissions->is_view('r_cluster_wise_purchase')){ ?>
													<tr>
														<td colspan="3">
															<input type='radio'  excel='true' name='by' value='r_cluster_wise_purchase' title="r_cluster_wise_purchase" id="r_cluster_wise_purchase" class="report"/>Cluster Wise Purchase Details
														</td>
													</tr>
													<?php } ?>
													<tr>
														<td colspan="2" style="text-align: right;">
															<input type="hidden" name="type" id="type"  title=""/>
															<input type="button" title="Exit" id="btnExit" value="Exit">
															<input type="button" id="btnPrint" title="Print"/>
															<input type="button" id="printExcel" title="Excel" disabled="true" />
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