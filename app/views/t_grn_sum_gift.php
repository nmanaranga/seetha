<?php if($this->user_permissions->is_view('t_grn_sum_gift')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_grn_sum_gift.js"></script>

<div id="fade" class="black_overlay"></div>
<?php 
    if($ds['use_serial_no_items'] ){
        $this->load->view('g_t_serial_in.php'); 
    }
?>
 
<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Gift Voucher Purchase</h2>
<div class="dframe" id="mframe" style="width:1190px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_grn_sum_gift" id="form_">
    	<input type="hidden" name="df_is_serial" id="df_is_serial" value="1" title="<?php echo $ds['use_serial_no_items'] ?>"/>
    	 <input type="hidden" name="srls" id="srls"/>
         <input type='hidden' id='transCode' value='3' title='3'/>
         <table style="width: 100%" border="0" cellpadding="0">
            <tr>
                <td width="50">Supplier</td>
				<td width="100"><input type="text" class="input_active" id="supplier_id" name="supplier_id"title="" /></td>
                <td colspan="3">
                    <input type="text" class="hid_value" id="supplier" title="" style="width:347px;"/>
                    <input type='button' value="..." title="..." id="supplier_create"/>
                    Print Serial <input type='checkbox' name='p_serial' id='p_serial' checked="true" value="1"/>
                </td>
                <td width="50">&nbsp;</td>    
                <td >No</td>
                <td align="right" >
                    <input type="text" class="input_active_num" name="id" id="id" title="<?php echo $max_no;?>" style="width:150px;"/>
                    <input type="hidden" id="hid" name="hid" title="0" />
                    <input type="hidden" id="base_url" title="<?php echo base_url();?>" value="<?php echo base_url();?>" />
                </td>
            </tr>
			
			<tr>
				<td>Store</td>
				<td><?php echo $stores; ?></td>
				<td colspan="3"><input type="text" class="hid_value" name="store_no" id="store_no" title="" style="width:348px;"/></td>
				<td>&nbsp;</td>

				<!-- <td>&nbsp;</td>
				<td>&nbsp;</td> -->
				<td>Ref No</td>
				<td><input type="text" class="input_txt" name="ref_no" id="ref_no" title=""  style="text-align:right;"/></td>	
			</tr>

			<tr>
                <td>Inv No</td>
                <td align="right"><input type="text" class="input_txt" name="inv_no" id="inv_no" title=""  style="text-align:right;"/></td>
				<td style="padding-left:100px; width:46px;">Inv Date</td>
				<td colspan="2" style="padding-left:49px">
					<?php if($this->user_permissions->is_back_date('t_grn_sum_gift')){ ?>
						<input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate"  title="<?=date('Y-m-d')?>" style="width:150px; text-align:right;"/>
					<?php }else{ ?>	
						<input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate"  title="<?=date('Y-m-d')?>" style="width:150px; text-align:right;"/>	
					<?php } ?>	
				</td>
				
				<td>&nbsp;</td>
                <td>Date</td>
                <td align="right" style="width:50px">
                	<?php if($this->user_permissions->is_back_date('t_grn_sum_gift')){ ?>
                		<input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>"  style="width:150px; text-align:right;"/>
                	<?php }else{ ?>	
                		<input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>"  style="width:150px; text-align:right;"/>	
                	<?php } ?>	
                </td>
            </tr>
			
			
			
			   <tr>
                <td colspan="8" style="text-align: center;">
                    <table style="width: 1000px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 150px;">Code</th>
                                <th class="tb_head_th" >Description</th>
                                <th class="tb_head_th" style="width: 70px;">QTY</th>
                                <th class="tb_head_th" style="width: 70px;">Price</th>
                                <th class="tb_head_th" style="width: 70px;">Max Price</th>
								<th class="tb_head_th" style="width: 40px;">Amount</th>
                            </tr>
                        </thead><tbody>
                                <input type='hidden' id='transtype' title='GIFT VOUCHER PURCHASE' value='GIFT VOUCHER PURCHASE' name='transtype' />
                                <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                               
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td style=''><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                              
                                              <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='width:100%;' /></td>
                                              <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                              <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
                                              <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                              <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' /> 
                                              ";   	
                                        echo "<td style='background-color:#f9f9ec;'><input type='text' class='g_input_txt g_col_fixed'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/>
                                       
                                       </td>";

                                        
                                        echo "<td style=''><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer' style='width:100%;'/>
                                        <input type='text' class='g_input_num qty qun qtt_".$x."' id='2_".$x."' name='2_".$x."' style='width : 50px; float:right;' style='width:100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_amo price' id='4_".$x."' name='4_".$x."' style='width : 100%;'/>
                                        				   <input type='hidden' id='ccc_".$x."' name='ccc_".$x."'/>
                                        	  </td>";

                                        echo "<td style=''><input type='text' class='g_input_amo price' id='max_".$x."' name='max_".$x."' style='width : 100%;'/></td>";
                                        
									    echo "<td><input type='text' id='t_".$x."' name='t_".$x."' style='text-align: right;' class='tf g_col_fixed' style='width:100%;'/>
									    	 <input type='hidden' id='qtyt_".$x."' title='0' name='qtyt_".$x."'/>	
									    	 <input type='hidden' id='subcode_".$x."' title='0' name='subcode_".$x."'/> 
									    	 <input type='hidden' id='is_click_".$x."' title='0' name='is_click_".$x."'/>	
									   		 </td>";
									   
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
							<table border="0">
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
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="width:313px;">&nbsp;</td>

										<td>
											<table>
													<tr style="background-color: transparent;">
														<td style="padding-left : 31px;"></td>
														<td colspan="4" style="padding-left : 0px;"></td>
														<td style="text-align: right; font-weight: bold; font-size: 12px;">Net Amount</td>
														<td><input type='text' style="width:105px;" class='hid_value g_input_amo' id='net_amount' readonly="readonly" name='net_amount' /></td>
													</tr>
											</table>
										</td>
									</tr>
							</table>
                        </tfoot>
                        
                    </table>

                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_grn_sum_gift')){ ?><input type="button" id="btnDelete5" title="Cancel" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_grn_sum_gift')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                        <input type="button"  id="btnSavee" title='Save <F8>' />
                        <?php if($this->user_permissions->is_add('t_grn_sum_gift')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>
                       
                    </div>


                </td>
            </tr>
        </table>
        <?php 
if($this->user_permissions->is_print('t_grn_sum_gift')){ ?>
    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
<?php } ?> 
    </form>



   <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_grn_sum_gift' title="t_grn_sum_gift" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='gift_purchase' title="gift_purchase" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='org_print' value='' title="" id="org_print">
                 <input type="hidden" name='is_print_serial' value='1' title="1" id="is_print_serial">
        
        </form>
</div>
<?php } ?>