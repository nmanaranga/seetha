<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_special_sales_sum.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_sales_sum.css" />


<div id="fade" class="black_overlay"></div> 
<div id="det_box">
    <img id="close_det_box" src="<?=base_url(); ?>/images/close_button.png"/>
    <div id="det_box_inner"><?php if(isset($det_box)){ echo $det_box;} ?>
    </div>
</div>



<div id="fade" class="black_overlay"></div>
<h2>Special Sales Approve</h2>

<div class="dframe" id="mframe" style="padding-right:25px;">
<table style="width:100%;" id="tbl1" border="0">
    <tr>
    	<td width="65">Customer</td>
    	<td width="100"><input type="text" id="customer" class="input_active" title="" name="customer" /></td>
    	<td colspan="3"><input type="text" class="hid_value" id="customer_id"  title="" readonly="readonly" style="width:324px;" /><input type='button' value="..." title="..." id="cutomer_create"/></td>
    	<td width="20">&nbsp;</td>
        <td width="50">No</td>
    	<td width="150"><input type="text" class="input_active_num" name="id" id="id" maxlength="10" title="<?=$max_no?>" style="width:150px;"/>
			<input type="hidden" id="hid" name="hid" title="0" />
            <input type="hidden" id="base_url" title="<?php echo base_url();?>" value="<?php echo base_url();?>" />
		</td>  		
    </tr>

    <tr>
        <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
    	<td>Address</td>
    	<td colspan="4"><input type="text" class="hid_value" id="address" title="" readonly="readonly" style="width:510px;"/></td>
    	
    	<td>&nbsp;</td>
    	<td>Date</td>
    	<td><input type="text" class="input_date_down_future" readonly="readonly" style="width:150px;" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
    </tr>

    <tr>
    	<td>Balance</td>
    	<td><input type="text" id="balance" name="balance" class="hid_value g_input_amo" title=""/></td>
    	<td width="50">&nbsp;</td>
    	<td>Category</td>
    	<td><?php echo $sales_category;?></td>
    	<td>&nbsp;</td>
        <input type="hidden" name="type" id="type" title="4" value="4"/>
       
    	 <td>Ref No</td>
    	<td>
            <input type="text" name="ref_no" id="ref_no" class="input_txt" title="" maxlength="10"/>
        </td>

    </tr>


    <tr>
    	 <td>SO No</td>
    	<td><input type="text" id="serial_no" name="serial_no" class="input_txt" title="" /></td> 
    	<td>&nbsp;</td>
    	<td>Group</td>
    	<td><?php echo $groups;?></td>
    	<td>&nbsp;</td>
    

        <td>CRN No</td>
        <td>
            <input type="text" class="input_active_num" name="crn_no" id="crn_no" title="" style="width:150px;"/>
            <input type="hidden" class="input_active_num" name="crn_no_hid" id="crn_no_hid" value='' style="width:150px;"/>
        </td>

    </tr>

    <tr>
    	<td>Store</td>
    	<td><?php echo $stores; ?></td>
    	<td colspan="3"><input type="text"  id="store_id" style="width:355px;" class="hid_value" title="" readonly="readonly"/></td>
    	
    	<td>&nbsp;</td>
    	<td></td>
    	<td><!-- <input type="text" name="ref_no" id="ref_no" class="input_txt" title="" maxlength="10"/> --></td>
    </tr>

    <tr>
    	<td colspan="8">
    		<table style="width:100%" id="tgrid" border="0" class="tbl">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 50px;">Code</th>
                                <th class="tb_head_th" style='width: 50px;'>Description</th>
                                <th class="tb_head_th" style="width: 50px;">Model</th>
                                <th class="tb_head_th" style="width: 50px;">Batch</th>
                                <th class="tb_head_th" style="width: 60px;">QTY</th>
                                <th class="tb_head_th" style="width: 50px;">FOC</th>
                                <th class="tb_head_th" style="width: 50px;">Price</th>
                                <th class="tb_head_th" style="width: 50px;">Dis %</th>
                                <th class="tb_head_th" style="width: 50px;">Discount</th>
                                <th class="tb_head_th" style="width: 50px;">Amount</th>
                                <th class="tb_head_th" style="width: 50px;">Warranty</th>
                            </tr>
                        </thead><tbody>
                        <input type='hidden' id='transtype' title='CASH' value='CASH' name='transtype' />
                            <?php
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                              <input type='text' class='g_input_txt fo items_".$x."' id='0_".$x."' name='0_".$x."' style='width:100%;' />
                                              <input type='hidden' name='bqty_'".$x."' id='bqty_".$x." title='0' style='display:block;visibility:hidden;'/>
                                              </td>
                                                <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                                <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                                <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";

                                        echo "<td style='width:100px;' class='g_col_fixed'><input type='text' class='g_input_txt' readonly   id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 87%;' readonly='readonly'/>
                                        <input type='button'  class='subs' id='sub_".$x."' style='margin:0;padding:5px;float: right; height:6px;width:6px;cursor:pointer'/></td>";
                                        echo "<td><input type='text' class='g_input_txt g_col_fixed model' id='2_".$x."' name='2_".$x."' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td class='g_col_fixed'><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float:left;height:6px;width:6px;cursor:pointer'/>
                                                  <input type='text' class='g_input_txt g_col_fixed batch btt_".$x."' id='1_".$x."' name='1_".$x."' style='margin:0;padding:0;width :56px; float: right; text-align:right;' readonly='readonly'/></td>";
                                        echo "<td><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float:left; height:6px;width:6px;cursor:pointer'/>
                                                  <input type='text' class='g_input_num qty qun qtycl".$x."' id='5_".$x."' name='5_".$x."' style='margin:0;padding:0;width :70px;float: right;'/></td>";
                                        echo "<td><input type='text' class='g_input_num foc' id='4_".$x."' name='4_".$x."' style='width : 100%;'/></td>";
                                        echo "<td ><input type='text' class='g_input_amo price' id='3_".$x."' name='3_".$x."' style='width : 100%;'/></td>";
                                        echo "<td class='g_col_fixed'><input type='text' class='g_input_amo dis_pre' id='6_".$x."' name='6_".$x."' readonly='readonly' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' id='7_".$x."' name='7_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo amount g_col_fixed' id='8_".$x."' name='8_".$x."' style='width : 100%;text-align:right;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_num warranty' id='9_".$x."' name='9_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_txt' id='f_".$x."' name='f_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_txt' id='bal_free_".$x."' name='bal_free_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_txt' id='bal_tot_".$x."' name='bal_tot_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_txt' id='free_price_".$x."' name='free_price_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_txt' id='issue_qty_".$x."' name='issue_qty_".$x."' style='width : 100%;'/>
                                        <input type='hidden' id='subcode_".$x."' title='0' name='subcode_".$x."' />
                                        </td>";
                                    
                                    echo "</tr>";
                                }
                            ?>
                </tbody>
              </table>
            
    	</td>
    </tr>
</table>
<table border="0" style="width:100%;">
    <tr>
    	<td>Memo</td>
    	<td colspan="5"><input type="text" class="input_txt" title="" id="memo" name="memo" style="width:423px;" maxlength="100"/></td>
    	<td align="right"><b>Gross</b></td>
    	<td width="150"><input type="text" class="hid_value g_input_amounts" readonly='readonly' id="gross" name="gross" style="margin-left:25px;"/></td>
    </tr>

    <tr>
    	<td>Sales Rep</td>
    	<td colspan="5"><input type="text" name="sales_rep" id="sales_rep" style="width:120px;" class="input_active" title=""/>
    	<input type="text" id="sales_rep2" style="width:265px;" class="hid_value" readonly="readonly" title=""/>
        <input type="button" id="sales_rep_create" title="..." value="...">
    </td>
    	<td rowspan="3" colspan="3">
    		
    		<table style="width:100%" id="tgrid2">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 50px;">Type</th>
                                <th class="tb_head_th">Description</th>
                                <th class="tb_head_th" style="width: 50px;">Rate%</th>
                                <th class="tb_head_th" style="width: 50px;">Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                for($x=0; $x<15; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='hh_".$x."' id='hh_".$x."' title='0' />
                                        <input type='hidden' name='hhh_".$x."' id='hhh_".$x."' title='0' />
                                                <input type='text' class='g_input_txt foo' id='00_".$x."' name='00_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td><input type='text' class='g_input_txt g_col_fixed'  id='nn_".$x."' name='nn_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_amo rate' id='11_".$x."' name='11_".$x."' style='width : 100%; text-align:right; '/></td>";
                                       
                                        echo "<td style=''><input type='text' id='tt_".$x."' name='tt_".$x."' class='g_input_amo aa' style='text-align: right;'/></td>";
                                    echo "</tr>";
                                }
                            ?>
                </tbody>
              </table>
            <input type='hidden' name='additional_amount' id="additional_amount" />
            <input type='hidden' name='save_status' id="save_status"/>
            <input type='hidden' name='total_discount' id="total_discount" />
            <input type="hidden" name="total_amount" id="total_amount"/>
    	</td>
    </tr>
    <tr>
    		<td colspan="6">
    			<fieldset    >
    				<legend></legend>
    				Privilege Card
    				<input type="text" name="privi_card" class="input_txt" id="privi_card" title="" style="width:100px;"/>
    				Points
    				<input type="text" name="points" class="input_txt" id="points" title="" style="width:100px;"/>
    			</fieldset>
    		</td>
    </tr>

    <tr>
    	<td colspan="6">

    	</td>
    </tr>

    <tr>
    	<td colspan="7">
    		<input type="button" id="btnExit" title="Exit" />
			<input type="button" id="btnResett" title="Reset" />
			<input type="button" id="btnDelete" title="Cancel" />
			<input type="button" id="btnPrint" title="Print" />
            <input type="hidden" name="srls" id="srls"/>
            <input type='hidden' id='transCode' value='4' title='4'/>

			<input type="hidden" name="credit_amount" id="credit_amount"/> 
          
			<input type="button" title="Payments" id='showPayments'/>
			<input type="button" title='Save <F8>' id="btnSave"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span style="margin-left:190px;"><b>Net Amount</b></span>
    	</td>
        
        <td ><input type="text" id="net" name="net" class="hid_value g_input_amounts"  style="margin-left:25px;" readonly='readonly'/>
        </td>
    </tr>



</table><!--tbl1-->

</form>

        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_cash_sales_sum' title="t_cash_sales_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='cash_sales' title="cash_sales" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
        
        </form>



</div>