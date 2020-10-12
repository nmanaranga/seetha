<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_serial_movement.js'></script>
<h2>Add Serials</h2>
<div class="dframe" id="mframe">
<table style="width:100%;" id="tbl1" border="0">
    <tr>
        <td valign="top" class="content" style="width:100%">
            
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_serial_movement" >
					<table style="width:100%;" id="tbl2" border="0">
						<tr>
							<td>Type</td>
							<td colspan="4"><input type="text" id="type" name="type" class="input_txt" style="width:150px;" /></td>
							
						</tr>

						<tr>
							<td>No</td>
							<td><input type="text" id="no" name="no" class="input_txt" style="width:150px;" /></td>
							<td></td>
							<td>QTY</td>
							<td><input type="text" id="qty" name="qty" class="input_number" style="width:150px;" /></td>
						</tr>
						
						<tr>
							<td>Item</td>
							<td colspan="4"><select></select>
							<input type="text" id="item" name="item" class="hid_value" style="width:375px;"  />		
							</td>
							
						</tr>


						<tr>
							<td colspan="2" width="240px;">
								<fieldset>
									<legend>Serials</legend>
									<table style="width:100%" cellpadding="0">
											<tbody>
												<?php
																			   
													for($x=0; $x<10; $x++){
														echo "<tr>";
														echo "<td><input type='text' class='g_input_txt'  id='serial_".$x."' name='serial_".$x."'/></td>";				
														echo "</tr>";
													}
																		
												?>											
											</tbody>
									</table>
								</fieldset>

							</td>

							<td colspan="3" width="360px;">
								<fieldset>
									<legend>Add Serials</legend>
									<table style="width:100%" cellpadding="0">
										<tr>
											<td colspan="2">

												<table>
													<tr>
														<td colspan="2"><input type="hidden" readonly="readonly" class="input_txt" title="Serial Get Media"/></td>
													<tr>
													<tr>
														<td colspan="2"><input type="hidden" readonly="readonly" class="input_txt" title="Generate"/></td>
													<tr>

													<tr>
														<td>Free Fix</td>
														<td><input type="text" name="free_fix" id="free_fix" class="input_txt" style="width:250px;"/></td>
													</tr>

													<tr>
														<td>Post Fix</td>
														<td><input type="text" name="post_fix" id="post_fix" class="input_txt" style="width:250px;"/></td>
													</tr>	

													<tr>
														<td>Start NO</td>
														<td><input type="text" name="start_no" id="start_no" class="input_txt" style="width:250px;"/></td>
													</tr>	
													
													<tr>
														<td>QTY</td>
														<td><input type="text" name="qty" id="qty" class="input_txt" style="width:250px;"/></td>
													</tr>	

													<tr>
														<td colspan="2">
															<input type="button" title="Generate"/>
															<input type="button" title="Clear"/>
															<input type="button" title="Add"/>
														</td>
													</tr>
														
												</table>
											</td>
											<td>
												<table style="width:100%" cellpadding="0">
												<tbody>
															<?php
																						   
																for($x=0; $x<10; $x++){
																	echo "<tr>";
																	echo "<td><input type='text' class='g_input_txt'  id='serial_".$x."' name='serial_".$x."'/></td>";				
																	echo "</tr>";
																}
																					
															?>											
														</tbody>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>	
							</td>
						</tr>
					
						<tr>
							<td style="text-align:right" colspan="5">
								<input type="hidden" id="code_" name="code_" title="0" />
								<input type="button" id="btnExit" title='Exit' />
								<input type="button" id="btnSave" title='Save <F8>' />
								<input type="button" id="btnReset" title='Reset'>                        
							</td>
						</tr>
					</table><!--tbl2-->
                </form><!--form_-->
            
      </td>
			
    </tr>
</table><!--tbl1-->
</div>