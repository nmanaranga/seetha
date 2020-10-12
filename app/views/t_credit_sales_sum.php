<?php if($this->user_permissions->is_view('t_credit_sales_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_credit_sales_sum.js?<?= rand() ?>'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_sales_sum.css" />

<div id="fade" class="black_overlay"></div>

<?php 
    if($ds['use_serial_no_items'] ){
        $this->load->view('t_serial_out.php'); 
    }
?>

<div id='dis_box' style="display:none;font-size: 22px;padding-left:8;padding-top:6; text-align: center; width:172px;height:100px; border-radius: 4px; border: 5px solid #ccc;position: absolute;margin-left: 1150; color: ;">
    <b>Total Discount</b>
    <br>
    <div id="dis_val" style="padding-top:20px;"></div>
</div>
<div id="fade" class="black_overlay"></div>
<?php $this->load->view('t_payment_option.php'); ?>
<h2>Credit Sales</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
<table style="width:100%;" id="tbl1" border="0">
    
    <!-- 	<td width="65">Customer</td>
    	<td width="100">
            <input type="text" id="customer" class="input_active" title="" name="customer" />
        </td>
    	<td colspan="3">
            <input type="text" class="hid_value" id="customer_id"  title="" readonly="readonly" style="width:330px;" />
            <input type='button' value="..." title="..." id="cutomer_create"/>
        </td>
    	<td width="20">&nbsp;</td> -->

   <!--  <tr>
        <td width="65">Customer</td>
        <td width="100">
            <input type="text" id="customer" class="input_active" title="" name="customer" />            
        </td>       
        <td colspan="3">
            <input type="text" class="hid_value main_cus" id="customer_id"  title="" readonly="readonly" style="width:361px;" />
             <input type="text" id="bill_cuss_name" placeholder="Customer Name" class="input_active bill_to_cus" title="" style="display:none; width:200px; float: left; " name="bill_cuss_name" />
             <input type="text" placeholder="Do No" id="do_no" class="input_active bill_to_cus" title="" style="display:none; margin-left:10px; width:146px;float: left; " name="do_no" />
        </td>        
        <td class="" width="118">
            <input type='button' value="..." title="..." id="cutomer_create"/>
             &nbsp; &nbsp; Bill To<input type="checkbox" style="align:right;" name="is_main" id="is_main">
            
        </td>

        <td width="50">No</td>
    	<td width="150"><input type="text" class="input_active_num" name="id" id="id" maxlength="10" title="<?=$max_no?>" style="width:150px;"/>
			<input type="hidden" id="hid" name="hid" title="0" />
            <input type="hidden" id="base_url" title="<?php echo base_url();?>" value="<?php echo base_url();?>" />
		</td>  		
    </tr> -->

    <tr>
        <td style="width:500px;" colspan="5" rowspan="3">
            <div id="tabs9">
                <ul>
                    <li><a href="#tabs-1" >Customer</a></li>
                    <li><a href="#tabs-2" >Bill To</a></li>
                    
                </ul>
                <div id="tabs-1">
                    <table>
                        <tr>
                            <td>
                                <input type="text" id="customer" class="input_active" title="" name="customer" />   
                                <input type="text" class="hid_value main_cus" id="customer_id"  title="" readonly="readonly" style="width:390px;" />
                                <input type='button' value="..." title="..." id="cutomer_create"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="hid_value main_cus" id="address" title="" readonly="readonly" style="width:578px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tabs-2">
                    <table>
                        <tr>
                            <td style="width:150px;">
                                <input type="text" id="bill_cus_id" placeholder="Customer Code" class="input_active" title="" name="bill_cus_id" />
                            </td>
                            <td>
                                <input type="text" id="bill_cuss_name" placeholder="Customer Name" class="input_active bill_to_cus" title="" style="width:267px; float: left; " name="bill_cuss_name" />
                            </td>
                            <td>
                                <input type="text" placeholder="Do No" id="do_no" class="input_active bill_to_cus" title="" style="margin-left:10px; width:146px;float: left; " name="do_no" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"> 
                                <input type="text" class="input_active bill_to_cus" id="cus_address" placeholder="Customer Address" name="cus_address" title="" style="width:421px; float: left;"/>
                            </td>

                            <td>
                                
                                <input type="text" placeholder="Receipt No" id="rcpt_no" class="input_active bill_to_cus" title="" style="margin-left:10px; width:146px;float: left; " name="rcpt_no" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>  
        <input type="hidden" id="cl" name="cl" title="<?=$cl?>" />
        <input type="hidden" id="bc" name="bc" title="<?=$bc?>" />       
        <td style="width:50px;">&nbsp;</td>        
        <td width="80">No</td>
        <td width="150" ><input type="text" class="input_active_num" name="id" id="id" maxlength="10" title="<?=$max_no?>" style="width:150px;vertical-align: bottom;"/>
            <input type="hidden" id="hid" name="hid" title="0" />
            <input type="hidden" id="base_url" title="<?php echo base_url();?>" value="<?php echo base_url();?>" />
        </td>       
    </tr> 
    
       <tr>
        <td>&nbsp;</td>
        <td>Date</td>
        <td>
            <?php if($this->user_permissions->is_back_date('t_credit_sales_sum')){ ?>
                <input type="text" class="input_date_down_future" readonly="readonly" style="width:150px; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
            <?php } else{ ?>    
                <input type="text" class="" readonly="readonly" style="width:150px; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
            <?php } ?>
        </td>
    </tr>

    <tr>
        <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
        
       
        <!-- <td>Address</td>
        <td colspan="4">
            <input type="text" class="hid_value main_cus" id="address" title="" readonly="readonly" style="width:567px;"/>
            
        </td> -->
        
        <td>&nbsp;</td>
        <td>Ref No</td>
        <td><input type="text" name="ref_no" id="ref_no" class="input_txt" title="" maxlength="10"/></td>
    </tr>

    <tr>
        <td>Balance</td>
        <td><input type="text" id="balance" name="balance" class="hid_value g_input_amo" title=""/></td>
        <td width="155">&nbsp;</td>
        <td style="width:100px;">Category</td>
        <td>
            <?php echo $sales_category;?>
            <input type="hidden" id="sales_category1" name="sales_category1" title="0" />
        </td>
        <td>&nbsp;</td>
        <input type="hidden" name="type" id="type" value="5" title="5"/>
        <td>CRN No</td>
        <td>
            <input type="text" class="input_active_num" name="crn_no" id="crn_no" title="<?=$crn_no?>" style="width:150px;"/>
            <input type="hidden" class="input_active_num" name="crn_no_hid" id="crn_no_hid" value='' style="width:150px;"/>
        </td>

    </tr>

    <tr>
        <td>SO No</td>
        <td><input type="text" id="serial_no" name="serial_no" class="input_txt" title="" /></td> 
        <td>&nbsp;</td>
        <td>Group</td>
        <td><?php echo $groups;?></td>
        <td>&nbsp;</td>
        
        <td>Credit Period</td>
        <td>
            <input type="text" class="input_active_num" name="credit_period" id="credit_period" title="" style="width:150px;"/>
        </td>
        
    </tr>

    <tr>
        <td>Store</td>
        <td><?php echo $stores; ?></td>
        <td colspan="3"><input type="text"  id="store_id" style="width:413px;" class="hid_value" title="" readonly="readonly"/></td>
        
        <td>&nbsp;</td>
        <td>Sub No</td>
        <td>
            <input type="text" class="input_active_num" readonly="readonly" name="sub_no" id="sub_no" title="0" style="width:150px;"/>
        </td>
    </tr>

     <tr>
        <td>Quotation</td>
        <td><input type="text" class="input_txt" title="" id="quotation" name="quotation"/></td>
        <td colspan="3">
            <input type="button" name="b_foc" id="b_foc" title="Bulk FOC" />
            <input type="hidden" name="is_foc" id="is_foc" title="0" />
         </td>
     
        <td>&nbsp;</td>
         <td>&nbsp;</td>
        
    </tr>

    <tr>
    	<td colspan="8">
       
    		<table style="width:100%" id="tgrid" border="0" class="tbl">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Code</th>
                                <th class="tb_head_th" style='width: 100px;'>Description</th>
                                <th class="tb_head_th" style="width: 50px;">Model</th>
                                <th class="tb_head_th" style="width: 20px;">Batch</th>
                                <th class="tb_head_th" style="width: 50px;">QTY</th>
                                <!-- <th class="tb_head_th" style="width: 50px;">FOC</th> -->
                                <th class="tb_head_th" style="width: 50px;">Price</th>
                                <th class="tb_head_th" style="width: 50px;">Dis %</th>
                                <th class="tb_head_th" style="width: 50px;">Discount</th>
                                <th class="tb_head_th" style="width: 50px;">Amount</th>
                                <th class="tb_head_th" style="width: 50px;">Warranty</th>
                            </tr>
                        </thead><tbody>
                        <input type='hidden' id='transtype' title='CREDIT' value='CREDIT' name='transtype' />
                        <input type='hidden' id='transCode' value='5' title='5'/>
                        <input type='hidden' id='all_foc_amount' name='all_foc_amount' value='0' title='0'/>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                              <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='width:100%;' />
                                              <input type='hidden' name='bqty_'".$x."' id='bqty_".$x." title='0' style='display:block;visibility:hidden;'/>
                                              </td>
                                                <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
                                                <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                                <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                                <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";

                                        echo "<td style='width:100px;' class='g_col_fixed'><input type='text' class='g_input_txt' readonly id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;'/>
                                        <input type='button'  class='subs' id='sub_".$x."' style='margin:0;padding:5px;float: right; height:6px;width:6px;cursor:pointer'/></td>";
                                        echo "<td><input type='text' class='g_input_txt model g_col_fixed' id='2_".$x."' name='2_".$x."' style='width : 100%;' /></td>";
                                        echo "<td class='g_col_fixed'><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                        <input type='text' class='g_input_txt batch btt_".$x."' id='1_".$x."' name='1_".$x."' style='width :40px; float: right; text-align:right;' readonly='readonly' /></td>";
                                        echo "<td>
                                                <input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                                <input type='text' class='g_input_num qty qun qtycl".$x."' id='5_".$x."' name='5_".$x."' style='width : 40px;float: right;'/>
                                                <input type='hidden' class='g_input_num foc' id='4_".$x."' name='4_".$x."' style='width : 100%;'/>
                                                <input type='hidden' class='tot_foc' id='tot_foc_".$x."'  name='tot_foc_".$x."' value='0'/> 
                                                <input type='hidden' id='cost_".$x."'  name='cost_".$x."' value='0'/> 
                                                <input type='hidden' id='item_min_price_".$x."'  name='item_min_price_".$x."'/> 
                                            </td>";

                                        echo "<td ><input type='text' class='g_input_amo price' id='3_".$x."' name='3_".$x."' style='width : 100%;' readonly/></td>";
                                        echo "<td class='g_col_fixed'><input type='text' readonly='readonly' class='g_input_amo dis_pre' id='6_".$x."' name='6_".$x."' style='width : 100%;background-color: #fffff;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' id='7_".$x."' name='7_".$x."' style='width : 100%;'/>
                                                    <input type='hidden' class='tot_discount' id='tot_dis_".$x."'  name='tot_dis_".$x."' value='0'/> 
                                                </td>";
                                        
                                        echo "<td class='g_col_fixed'><input type='text' class='g_input_amo amount' id='8_".$x."' name='8_".$x."' style='width : 100%;text-align:right;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_num warranty' id='9_".$x."' name='9_".$x."' style='width : 100%;background-color: #fffff;'/>
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
    	<td align="right">
         <b>Total Free</b>
        <input type="text" name="free_tot" class="hid_value g_input_amounts g_input_amo" readonly="readonly" style="width:120px;" id="free_tot"/>
        <input type="hidden" name="free_tot2" class="hid_value g_input_amounts g_input_amo" readonly="readonly" style="width:120px;" id="free_tot2"/>
        
        <b>Gross</b></td>
    	<td width="150">
            <input type="hidden" class="hid_value g_input_amounts" readonly='readonly' id="gross" name="gross" style="margin-left:25px;"/>
            <input type="text" class="hid_value g_input_amounts" readonly='readonly' id="gross1" name="gross1" style="margin-left:25px;"/>
        </td>
    </tr>

    <tr>
    	<td>Sales Officer</td>
    	<td colspan="5">
           <input type="text" name="sales_rep" id="sales_rep" style="width:120px;" class="input_active" title=""/>
    	   <input type="text" id="sales_rep2" style="width:260px;" class="hid_value" readonly="readonly" title=""/>
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
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<15; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='hh_".$x."' id='hh_".$x."' title='0' />
                                        <input type='hidden' name='hhh_".$x."' id='hhh_".$x."' title='0' />
                                                <input type='text' class='g_input_txt foo' id='00_".$x."' name='00_".$x."' readonly='readonly' style='width : 100%;background-color: #f9f9ec;' /></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='nn_".$x."' name='nn_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_amo rate' id='11_".$x."' name='11_".$x."' style='width : 100%; text-align:right; background-color:#f9f9ec;'/></td>";
                                       
                                        echo "<td style='background-color:#f9f9ec;'><input type='text' id='tt_".$x."' name='tt_".$x."' class='g_input_amo aa' style='text-align: right;background-color:#f9f9ec;'/></td>";
                                    echo "</tr>";
                                }
                            ?>
                </tbody>
            </table>
                <input type='hidden' name='additional_amount' id="additional_amount"/>
                <input type='hidden' name='save_status' id="save_status"/>
                <input type='hidden' name='total_discount' id="total_discount" />
                <input type="hidden" name="total_amount" id="total_amount"/>
                <input type='hidden' name='additional_add' id="additional_add" />
                <input type='hidden' name='additional_deduct' id="additional_deduct" />
    	</td>
    </tr>
    <tr>
    		<td colspan="6">
    			<fieldset>
    				<legend></legend>
    				Privilege Card
    				<input type="text" name="privi_card" class="input_txt" id="privi_card" title="" style="width:100px;"/>
    				Points
    				<input type="text" name="points" class="input_txt" id="points" title="" style="width:100px;text-align: right;"/>
    			</fieldset>
    		</td>
    </tr>

    <tr>
    	<td colspan="6"></td>

    </tr>

    <tr>
    	<td colspan="6">

    		<input type="button" id="btnExit" title="Exit" />
			<input type="button" id="btnResett" title="Reset" />
			<?php if($this->user_permissions->is_delete('t_credit_sales_sum')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
			<?php if($this->user_permissions->is_re_print('t_credit_sales_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
            <input type="hidden" name="srls" id="srls"/>
            <input type='hidden' id='transCode' value='5' title='5'/>
			<input type="hidden" name="credit_amount" id="credit_amount"/> 
			<input type="button" title="Payments" id='showPayments'/>
            <input type='button' title='Save <F8>' id='btnSavee'/>
            <?php if($this->user_permissions->is_add('t_credit_sales_sum')){ ?><input type='button' title='Save <F8>' id='btnSave'/><?php } ?>
    	</td>
        <td align='right'><b>Discount Amount</b></td>
        <td>
            <input type="text" name="dis_amount" class="hid_value g_input_amounts g_input_amo" readonly="readonly" style="width:120px; margin-left:25px;" id="dis_amount"/>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="color:red; font-size:12px;">** For View Item Details, Double Click Item Code</td>
        <td colspan="3" style="text-align:right; "> 
            <b style='margin-right:0px;'>Net Amount</b>        
        </td>
        <td>
            <input type="text" id="net" name="net" class="hid_value g_input_amounts" style="margin-left:25px;" readonly='readonly'/>
            <input type="hidden" id="net_hid" name="net_hid"/>
        </td>
    </tr>

</table><!--tbl1-->
<?php 
if($this->user_permissions->is_print('t_credit_sales_sum')){ ?>
    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
<?php } ?>

</form>
        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                 <input type="hidden" name='by' value='t_credit_sales_sum' title="t_credit_sales_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='credit_sales' title="credit_sales" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='is_duplicate' value='0' title="0" id="is_duplicate" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
        </form>
</div>
<?php } ?>