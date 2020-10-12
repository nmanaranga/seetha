<?php if($this->user_permissions->is_view('t_pur_ret_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_pur_ret_sum.js"></script>

<div id="fade" class="black_overlay"></div>

<?php 
    if($ds['use_serial_no_items'] ){
        $this->load->view('t_serial_out.php'); 
    }
?>

<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Purchase Return</h2>
<div class="dframe" id="mframe" style="width:1150px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_pur_ret_sum" id="form_">
        <table style="width: 100%" border="0" cellpadding="0">
            <tr>
                <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
                <td width="50">Supplier</td>
				<td width="100"><input type="text" class="input_txt" id="supplier_id" title="" name="supplier_id"/></td>
                <td colspan="3"><input type="text" class="hid_value" id="supplier" title="" style="width:630px;"/></td>
                <td width="50">&nbsp;</td>    
                <td width="50">No</td>
                <td>
                    <input type="text" class="input_active_num" style="width:150px;" name="id" id="id" title="<?php echo $nno; ?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                    <input type="hidden" id="hid_grn" value='0' title="0" />
                   <input type="hidden" id="total_discount" name="total_discount" title="0" />
                </td>
            </tr>
			
			<tr>
                <td>GRN No</td>
                <td><input type="text" class="input_number" name="grnno" id="grnno" style="width:150px"/></td>
				<td>
					<!-- &nbsp;&nbsp;Update PO (Cancel GRN)&nbsp; <input type='checkbox' id='update_po' name='update_po' title='1' value='1'> -->
					<input type='hidden' id='update_po_status' name='update_po_status' value='0'>
				</td>
				<td>&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
                <td>Type</td>
                <td>                     
					<select name='ret_type' id='ret_type'>
						<option value="1">GRN</option>
						<option value="2">Open Stock</option>
					</select>
                </td>
            </tr>

            <tr>
                <td>DRN No</td>
                <td width="100"><input type="text" class="input_active_num" id="drn_no" name="drn_no" readonly="readonly" style="width:150px" title="<?php echo $drn_no;?>"/></td>
                <td colspan="3">&nbsp; PO No <input type='text' readonly="readonly" id='update_po_no' name='update_po_no' style="width:150px; margin-left:37px;" class="input_number hid_value"></td>
				<td>&nbsp;</td>
				<td>Date</td>
				<td>
					<?php if($this->user_permissions->is_back_date('t_pur_ret_sum')){ ?>
                        <input type="text" class="input_date_down_future" style="width:150px; text-align:right;" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php }else{ ?> 
                        <input type="text" class="input_txt" style="width:150px; text-align:right;" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /> 
                    <?php } ?>      
				</td>	
            </tr>
			
			<tr>
                <td>Store</td>
                <td width="100"><?php echo $stores;?></td>
                <td colspan="3"> <input type="text" class="hid_value" id="store_id" title="" style="width: 407px; "  title="" maxlength="255" /></td>
				<td>&nbsp;</td>
				<td>Ref No</td>
				<td><input type="text" class="input_number" style="width:150px;" name="ref_no" id="ref_no" title="" /></td>	
            </tr>
			   
			
			
			
			   <tr>
                <td colspan="8" style="text-align: center;">
                    
                	 <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 108px;">Code</th>
                                <th class="tb_head_th" style="width: 170px;">Description</th>
                                <th class="tb_head_th" style="width: 75px;">Model</th>
                                <th class="tb_head_th" style="width: 60px;">Return Qty</th>
                                <th class="tb_head_th" style="width: 70px;">Batch</th>
                                <th class="tb_head_th" style="width: 50px;">QTY</th>
                               
                                <th class="tb_head_th" style="width: 70px;">Price</th>
                                <th class="tb_head_th" style="width: 70px;">Discount%</th>
                                <th class="tb_head_th" style="width: 70px;">Discount</th>
                            
								<th class="tb_head_th" style="width: 70px;">Amount</th>
								
                            </tr>
                        </thead><tbody>
                        <input type='hidden' id='transtype' title='PURCHASE RETURN' value='PURCHASE RETURN' name='transtype' />
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr class='cl1'>";
                                        echo "<td style=''><input type='hidden' name='h1_".$x."' id='h1_".$x."' title='0' />
                                                <input type='text' style='width:100%;' class='g_input_txt g_col_fixed' id='01_".$x."' name='01_".$x."' maxlength='150' style='width : 100%;' /></td>
												<input type='hidden' id='setserial1_".$x."' title='0' name='setserial1_".$x."' />
												<input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
                                                <input type='hidden' id='numofserial1_".$x."' title='' name='numofserial1_".$x."' />
												<input type='hidden' id='itemcode1_".$x."' title='0' name='itemcode1_".$x."' />";
                                        echo "<td><input type='text' class='g_input_txt  g_col_fixed'  id='n1_".$x."' name='n1_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 g_col_fixed' id='101_".$x."' name='101_".$x."' style='width : 100%;' readonly='readonly' /></td>";
                                        echo "<td><input type='text' class='g_input_amo dis g_col_fixed' id='rq_".$x."' name='rq_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_num batch g_col_fixed' id='31_".$x."' name='31_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_num qty1 g_col_fixed' id='21_".$x."' name='21_".$x."' style='width : 100%;'/>


                                        </td>";
                                        
                                        echo "<td style=''><input type='text' class='g_input_amo price1 g_col_fixed' id='41_".$x."' name='41_".$x."' style='width : 100%;'/></td>";
                                       
									    echo "<td style=''><input type='text' class='g_input_amo dis_pre1 g_col_fixed' id='51_".$x."' name='51_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_amo dis1 g_col_fixed' id='61_".$x."' name='61_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_amo dis' id='rmax1_".$x."' name='rmax1_".$x."' /></td>";
									   echo "<td><input type='text' id='t1_".$x."' name='t1".$x."' style='text-align: right; width : 100%;' class='g_col_fixed tf' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        </table>



                    <table style="width: 875px;" id="tgrid1">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 120px;">Code</th>
                                <th class="tb_head_th" style="width: 175px;">Description</th>
                                <th class="tb_head_th" style="width: 75px;">Model</th>
                                <th class="tb_head_th" style="width: 60px;">Batch</th>
                                <th class="tb_head_th" style="width: 60px;">QTY</th>
                               
                                <th class="tb_head_th" style="width: 70px;">Price</th>
                                <th class="tb_head_th" style="width: 70px;">Discount%</th>
                                <th class="tb_head_th" style="width: 70px;">Discount</th>
								<th class="tb_head_th" style="width: 70px;">Amount</th>
								<th class="tb_head_th" style="width: 70px;">Reason</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td style=''><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' style='width:100%;' class='g_input_txt fo g_col_fixed' id='0_".$x."' name='0_".$x."' style='width : 100%;' /></td>
												<input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
												<input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
												<input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";
                                        echo "<td><input type='text' class='g_input_txt  g_col_fixed'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 g_col_fixed qun ' id='1_".$x."' name='1_".$x."' style='width : 100%;' readonly='readonly' /></td>";
                                        echo "<td style=''><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                        <input type='text' class='g_input_num batch btt_".$x."' id='3_".$x."' name='3_".$x."' style='width : 40px;float: right;'/></td>";
                                        echo "<td style=''><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                        <input type='text' class='g_input_num qty qun vali qtycl".$x."' id='2_".$x."' name='2_".$x."' style='float: right;width : 40px;'/>
                                        <input type='hidden' class='g_input_num qty1 g_col_fixed' id='21h_".$x."' name='21h_".$x."' style='width : 100%;'/>
                                        </td>";
                                        
                                        echo "<td style=''><input type='text' class='g_input_amo price' id='4_".$x."' name='4_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_amo dis' id='rmax_".$x."'  name='rmax_".$x."'/></td>";
                                       
									    echo "<td class='g_col_fixed'><input type='text' readonly class='g_input_amo dis_pre' id='5_".$x."' name='5_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_amo dis' id='6_".$x."' name='6_".$x."' style='width : 100%;'/></td>";
									   echo "<td><input type='text' id='t_".$x."' name='t_".$x."' style='text-align: right; width : 100%;' class='g_col_fixed tf'/></td>";
									   echo "<td style=''><input type='text' class='g_input_txt reason' id='7_".$x."' name='7_".$x."' style='width : 100%;'/>
                                                <input type='hidden' id='ret_".$x."' name='ret_".$x."' />
                                                <input type='hidden' id='maxretqty_".$x."' name='maxretqty_".$x."' />
                                                <input type='hidden' id='subcode_".$x."' title='0' name='subcode_".$x."' />
                                                </td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
							<table border="0">
									<tr>
										<td>
										<fieldset style="background:transparent;">
											<legend>Other Amount</legend>
												
												<table id="tgrid2" style="width: 100%">
													<thead>
														<tr>
															<th class="tb_head_th" style="width: 100px;">Type</th>
															<th class="tb_head_th" style="width: 300px;">Description</th>
															<th class="tb_head_th" style="width: 100px;">Rate%</th>
															<th class="tb_head_th" style="width: 100px;">Amount</th>
														</tr>	
													</thead>
														<tbody>
														
															<?php
                               
																for($x=0; $x<25; $x++){
																	echo "<tr>";
																		echo "<td style=''><input type='hidden' name='hh_".$x."' id='hh_".$x."' title='0' />
																		<input type='hidden' name='hhh_".$x."' id='hhh_".$x."' title='0' />
																				<input type='text' class='g_input_txt foo' id='00_".$x."' name='00_".$x."' readonly='readonly' style='width : 100%;' /></td>";
																		echo "<td ><input type='text' class='g_input_txt g_col_fixed'  id='nn_".$x."' name='nn_".$x."' maxlength='150' style='width : 100%;'/></td>";
																		echo "<td style=''><input type='text' class='g_input_amo rate' id='11_".$x."' name='11_".$x."' style='width : 100%;'/></td>";
																		echo "<td style=''><input type='text' class='g_input_amo aa' id='22_".$x."' name='22_".$x."' style='width : 100%;'/></td>";
																		
																		echo "</tr>";
																}
															?>
														</tbody>
														
													<tr>
														
													</tr>
												</table>
												<input type='hidden' name='additional_add' id="additional_add" />
                								<input type='hidden' name='additional_deduct' id="additional_deduct" />
												
										</fieldset>
										
										</td>
								
											<td>
							
												<table>
											
														<tr style="background-color: transparent;">
															<td style="padding-left : 7px;"></td>
															<td colspan="9" style="padding-left : 10px;"></td>
															<td style="text-align: right; font-weight: bold; font-size: 12px;" width="100">Gross Amount</td>
															<td><input type='text' class='hid_value g_input_amounts' id='gross_amount' name='gross_amount' style="margin-right: 15px; margin-left: 5px;" /></td>
														</tr>
														<tr style="background-color: transparent;">
															<td style="padding-left : 7px;"></td>
															<td colspan="9" style="padding-left : 10px;"></td>
															<td style="text-align: right; font-weight: bold; font-size: 12px;">Other Add</td>
															<td><input type='text' class='hid_value g_input_amounts' id='total2' readonly="readonly" name='total' style="margin-right: 15px; margin-left: 5px;"/></td>
														</tr>	
														
														<tr style="background-color: transparent;">
															<td style="padding-left : 107px;"></td>
															<td colspan="9" style="padding-left : 100px;"></td>
															<td style="text-align: right; font-weight: bold; font-size: 12px;">Net Amount</td>
															<td><input type='text' class='hid_value g_input_amounts' id='net_amount' readonly="readonly" name='net_amount' style="margin-right: 15px; margin-left: 5px;"/></td>
														</tr>
														
													
													
													
												</table>
											</td>
									</tr>
									<tr>
											<td colspan="2">
												<table>
													<tr style="background-color: transparent;">
															<td style="padding-left : 7px;">Memo</td>
															<td colspan="4" style="padding-left : 10px;"><input type="text" class="input_txt" name="memo" id="memo" title="" style="width:570px;" maxlength="255" /></td>
													</tr>
												</table>
											</td>
									</tr>
							</table>
                        </tfoot>
                        
                    </table border="0">
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_pur_ret_sum')){ ?><input type="button" id="btnDelete5" title="Cancel" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_pur_ret_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                        <input type="hidden" name="srls" id="srls"/>
                        <input type='hidden' id='transCode' value='10' title='10'/>
                        <input type="button"  id="btnSavee" title='Save <F8>' />
                        <?php if($this->user_permissions->is_add('t_pur_ret_sum')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>
                        <?php if($this->user_permissions->is_approve('t_pur_ret_sum')){ ?><input type="button"  id="btnApprove" title='Approve' /><?php } ?>
                       	<input type='hidden' id='app_status' name='approve' title='1' value='1'/>
                    </div>
                </td>
            </tr>
            <tr>
              <td colspan="6"><font color="red">** For View Item Details, Double Click Item Code</font></td>
            </tr>
        </table>
        <?php 
        if($this->user_permissions->is_print('t_pur_ret_sum')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
        <?php } ?>
    </form>




   <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_pur_ret_sum' title="t_pur_ret_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='purchase_return' title="purchase_return" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='rep_sup' value='' title="" id="rep_sup" >
                 <input type="hidden" name='rep_ship_bc' value='' title="" id="rep_ship_bc" >
                 <input type="hidden" name='inv_date' value='' title="" id="inv_date" >
                 <input type="hidden" name='netAmnt' value='' title="" id="netAmnt" >
                 <input type="hidden" name='v_id' value='' title="" id="v_id" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='drn' value='' title="" id="drn" >
                 <input type="hidden" name='is_duplicate' value='0' title="0" id="is_duplicate" >
                 <input type="hidden" name='rep_deliver_date' value='' title="" id="rep_deliver_date" >
        
        </form>

</div>
<?php } ?>
