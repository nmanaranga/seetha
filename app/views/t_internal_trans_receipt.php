<?php if($this->user_permissions->is_view('t_internal_trans_receipt')){ ?>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
    <script type="text/javascript" src="<?=base_url()?>js/t_internal_trans_receipt.js"></script>
    <?php $this->load->view('t_payment_option.php'); ?> 
    <h2 style="text-align: center;">Internal Transfer Reciept</h2>

    <div class="dframe" id="mframe" style="padding-right:25px;width:1100px;">
        <table style="width: 100%" border="0" cellpadding="0">
            <tr>
                <td width="100">Account Code</td>
                <td width="100"><input type="text" class="input_txt" id="acc_code" title="" name="acc_code">
                    <input type="hidden" class="input_txt" id="customer" title="" name="customer"></td>
                    <td colspan="3"><input type="text" class="hid_value" id="acc_des" readonly="readonly" style="width:407px;"/></td>
                    <td width="200">&nbsp;</td>    
                    <td width="50">No</td>
                    <td>
                        <input type="text" class="input_active_num" name="id" id="id" title="<?php echo $max_no;?>" style="width:150px;"/>
                        <input type="hidden" id="hid" name="hid" title="0" />
                        <input type='hidden' name='save_status' id="save_status"/>
                    </td>
                </tr>

                <tr>
                    <td>Balance</td>
                    <td><input type="text" class="hid_value" name="balance" id="balance" readonly='readonly' style="width:150px;text-align:right;" title=""/></td>
                    <td width="200"></td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Date</td>
                    <td>
                        <?php if($this->user_permissions->is_back_date('t_internal_trans_receipt')){ ?>
                            <input type="text" class="input_date_down_future" readonly="readonly" style="width:150px;text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
                        <?php }else{ ?>
                            <input type="text" class="" style="width:150px;text-align:right;" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                        <?php } ?>    
                    </td>
                </tr>
                <tr>
                    <td>Memo</td>
                    <td colspan="5">
                       <input type="text" class="input_txt" id="memo" name="memo" title=""  style="width:560px;"/></td>
                       <td style="width: 100px;">Ref No</td>
                       <td><input type="text" class="input_number" name="ref_no" id="ref_no" title="" style="width:150px;"/></td>
                   </tr>

                   <tr>
                    <td>Payment</td>
                    <td><input type="text" class="g_input_amo" id="net" name="net" title="" style="border:1px solid #039;padding:3px 0;width:150px;"/></td>
                    <td colspan="3">
                        <input type="checkbox" id="auto_fill" name="auto_fill"/>Auto Fill
                        <input type="button"  style="margin-left:10px;" title="Load Details" value="Load Details" id="load_details"/>
                    </td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center;">
                        <table style="width: 100%;" id="tgrid">
                            <thead>
                                <tr>
                                    <th class="tb_head_th" style="width:50px;" >Cluster</th>
                                    <th class="tb_head_th" style="width:50px;" >Branch</th>
                                    <th class="tb_head_th" style="width:50px;">Transfer No</th>
                                    <th class="tb_head_th" style="width:50px;">Sub No</th>
                                    <th class="tb_head_th" style="width:100px;">Date</th>
                                    <th class="tb_head_th" style="width:200px;">Description</th>
                                    <th class="tb_head_th" style="width:100px;">Amount</th>
                                    <th class="tb_head_th" style="width:100px;">Balance</th>
                                    <th class="tb_head_th" style="width:100px;">Payment</th>

                                </tr>
                            </thead><tbody>
                                <?php
                            //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<100; $x++){
                                    echo "<tr>";
                                    echo "<td><input type='text' class='g_input_txt g_col_fixed' id='cl0_".$x."' name='cl0_".$x."' style='width : 100%;' /></td>";
                                    echo "<td><input type='text' class='g_input_txt g_col_fixed' id='bc0_".$x."' name='bc0_".$x."' style='width : 100%;' /></td>";
                                    echo "<td><input type='text' class='g_input_num g_col_fixed' id='2_".$x."' name='2_".$x."' style='width : 100%;' />
                                    <input type='hidden'  id='trans_code".$x."' name='trans_code".$x."' />
                                    <input type='hidden'  id='refcl_".$x."' name='refcl_".$x."' />
                                    <input type='hidden'  id='refbc_".$x."' name='refbc_".$x."' />  
                                    </td>";
                                    echo "<td><input type='text' class='g_input_num g_col_fixed' id='2r_".$x."' name='2r_".$x."' style='width:100%;' /></td>";
                                    echo "<td><input type='text' class='g_input_txt g_col_fixed' id='3_".$x."' name='3_".$x."' style='width : 100%;' /></td>";
                                    echo "<td><input type='text' class='g_input_txt g_col_fixed' id='descrip_".$x."'  name='descrip_".$x."' style='width : 100%;' /></td>";
                                    echo "<td><input type='text' class='g_input_amo g_col_fixed payss' id='4_".$x."' name='4_".$x."' value='' style='width : 100%;' /></td>";
                                    echo "<td style='background:#F9F9EC';><input type='text' class='g_input_amo fo g_col_fixed' id='5_".$x."' name='5_".$x."' style='width :80px ;float: right;'/></td>";
                                    echo "<td><input type='text' class='g_input_amo pay ' id='6_".$x."' name='6_".$x."' style='width : 100%;'/></td>";
                                    echo "</tr>";
                                }       
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr style="background:#e6eeff">
                    <td colspan="3" ></td>
                    <td colspan="5">
                        <b style="padding-left:109;text-align:right;">Total</b>
                        <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_amount" name="total_amount" style="background:#e6eeff;margin-left:102px; width:130px;" />
                        <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_balance" name="total_balance" style="background:#e6eeff;width:130px;" />
                    </td>
                </tr>
                <tr>
                   <td colspan="6" rowspan="2">
                    <input type="button" id="btnExit" title="Exit" />
                    <input type="button" id="btnResett" title="Reset" />
                    <?php if($this->user_permissions->is_delete('t_internal_trans_receipt')){ ?><input type="button" id="btnDelete5" title="Cancel" /><?php } ?>
                    <?php if($this->user_permissions->is_re_print('t_internal_trans_receipt')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                    <input type="button" title="Payments" id='showPayments'/>
                    <input type="button" id="btnSavee" title='Save <F8>' />
                        <?php if($this->user_permissions->is_add('t_internal_trans_receipt')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                    </td>
                    <td><b>Settle Amount</b></td>
                    <td>
                        <input type="text" class="hid_value g_input_amounts" readonly="readonly" title="" id="net_val" name="net_val" />
                    </td>
                </tr>
                <tr>
                   <td><b>Balance</b></td>
                   <td><input type="text" class="hid_value g_input_amounts" readonly="readonly" title="" id="balance2" name="balance2" /></td>
               </tr>

           </table>

           <?php 
           if($this->user_permissions->is_print('t_internal_trans_receipt')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
        <?php } ?> 
    </div>    
</form>
<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
   <input type="hidden" name='by' value='t_internal_trans_receipt' title="t_internal_trans_receipt" class="report">
   <input type="hidden" name='page' value='A4' title="A4" >
   <input type="hidden" name='orientation' value='P' title="P" >
   <input type="hidden" name='type' value='t_internal_trans_receipt' title="t_internal_trans_receipt" >
   <input type="hidden" name='recivied' value='' title=""  id='recivied'>
   <input type="hidden" name='header' value='false' title="false" >
   <input type="hidden" name='qno' value='' title="" id="qno" >
   <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
   <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
   <input type="hidden" name='dt' value='' title="" id="dt" >
   <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
   <input type="hidden" name='is_duplicate' value='0' title="0" id="is_duplicate" >
   <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
</form>

<div id="light" class="white_content">
    <div id='install_payment_det' style='margin:10px'>
    </div>
    <input type='button' value='close' title='close' id='popclose' style="position:absolute;bottom:5px;right:5px;"/>
</div>
<div id="fade" class="black_overlay"></div>


<div id="light3" class="white_content">
    <div id='penalty_payment_det' style='margin:10px'>
    </div>
    <input type='button' value='close' title='close' id='popclose2' style="position:absolute;bottom:5px;right:5px;"/>
</div>

<?php } ?>
