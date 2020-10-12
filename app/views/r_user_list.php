<?php if($this->user_permissions->is_view('r_transaction_list_purchase')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_transaction_list_purchase.js'></script>
<script type='text/javascript' src='<?=base_url()?>js/m_client_application.js'></script>
<h2>Purchase Reports</h2>
<table width="100%">
	<tr>
		<td valign="top" class="content" style="width: 480px;">
			<div class="form" id="form">
				<form id="print_pdf" class="printExcel" action="<?php echo site_url();?>/reports/generate" method="post" target="_blank">
					

					<table border="0">
				
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

							<?php if($this->user_permissions->is_view('r_purchase_summary')){ ?>
							<tr>
								<td colspan="3">
									<input type='radio' name='by' value='r_user_list' title="r_user_list" class="report"  />User List</td>

								</tr>
								<?php } ?>

								

													<tr>
														<td colspan="2" style="text-align: right;">
															<input type="hidden" name="type" id="type"  title=""/>
															<input type="button" title="Exit" id="btnExit" value="Exit">
															<input type="button" id="btnPrint" title="Print"/>
															
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