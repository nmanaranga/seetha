<?php if($this->user_permissions->is_view('t_credit_card_reconcil')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_credit_card_reconcil.js"></script>
<h2 style="text-align: center;">Credit Card Reconcillation</h2>

<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_credit_card_reconcil" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Merchant ID</td>
                <td>
                    <input type="text" name="merchant_id" id="merchant_id" class="input_txt"/> 
                    <input type="text" class="hid_value" title='' readonly="readonly" id="merchant_des"  style="width: 300px;">
                    <input type="hidden" id="merchant_acc" name="merchant_acc">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$id?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr>
            <tr>
                <td style="width: 100px;">Bank Acc</td>
                <td>
                    <input type="text" class="input_txt" title='' id="bank_acc"  name='bank_acc' >
                    <input type="text" class="hid_value" title='' readonly="readonly" id="bank_des"  style="width: 300px;">
                </td>
                <td style="width: 50px;">Date</td>
                <td>
                    <?php if($this->user_permissions->is_back_date('t_credit_card_reconcil')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                    <?php } ?> 
                </td>
            </tr>
            <tr>
                <td style="width: 100px;">Description</td>
                <td>
                    <input type="text" class="input_txt" title='' id="description"  name='description' style="width: 451px;">
                </td>
                <td style="width: 50px;"></td>
                <td>
                   <!--  <?php if($this->user_permissions->is_back_date('t_credit_card_reconcil')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                    <?php } ?>  -->
                </td>

            </tr>
            <tr>
                <td>Date From</td>
                <td>
                    <input type="text" style="text-align:right; width:120px;" class="input_date_down_future input_txt" readonly="readonly" name="from_date" id="from_date" title="<?=date('Y-m-d')?>" />    
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Date To
                    <input type="text" style="text-align:right; width:120px;" class="input_date_down_future input_txt" readonly="readonly" name="to_date" id="to_date" title="<?=date('Y-m-d')?>" /> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="button" name="load_credits" id="load_credits" title="Load Data" style="width:100px;"/>
                </td>
                <td style="width: 100px;"></td>
                <td style="width: 100px;"></td>
            </tr>  
          <tr>
                <td colspan="4" style="text-align: center;">
                    
                    <table style="width: 875px;" id="tgrid" border="0">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 130px;">Date</th>
                                <th class="tb_head_th" style="width: 200px;">Trans Code</th>
                                <th class="tb_head_th" style="width: 50px; ">Trans No</th>
                                <th class="tb_head_th" style="width: 200px;">Branch</th>
                                <th class="tb_head_th" style="width: 150px;">Card No</th>
                                <th class="tb_head_th" >Amount</th>
                                <th class="tb_head_th" style="width: 100px;">Sys.Commission</th>
                                <th class="tb_head_th" style="width: 100px;">Actual Commission</th>
                                <th class="tb_head_th" style="width: 100px;">Balance</th>
                                <th class="tb_head_th" style="width: 20px;">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='text' class='hid_value' id='date_".$x."' name='date_".$x."'  style='width:100%'/></td>";
                                        echo "<td>
                                                <input type='hidden' name='tcode_".$x."' id='tcode_".$x."'/>
                                                <input type='text' class='hid_value'  id='tcodedes_".$x."' name='tcodedes_".$x."' style='width:100%' readonly='readonly' maxlength='150'/>
                                              </td>";
                                        echo "<td><input type='text' class='hid_value' id='tno_".$x."' name='tno_".$x."' style='width:100%;text-align:right;' /></td>";
                                        echo "<td>
                                                <input type='hidden' name='tbc_".$x."' id='tbc_".$x."'/>
                                                <input type='hidden' name='tcl_".$x."' id='tcl_".$x."'/>
                                                <input type='text' class='hid_value' id='bcname_".$x."' name='bcname_".$x."' style='width:100%;' /> 
                                              </td>";
                                        echo "<td><input type='text' class='hid_value' id='cardn_".$x."' name='cardn_".$x."' style='width:100%;text-align:right;'/></td>";
                                        echo "<td><input type='text' class='hid_value g_input_amo' id='amnt_".$x."' name='amnt_".$x."' style='width:100%'/></td>";
                                        echo "<td><input type='text' class='hid_value g_input_amo bl' readonly='readonly' id='scom_".$x."' name='scom_".$x."' style='width:100%'/></td>";
                                        echo "<td><input type='text' class='g_input_txt g_input_amo pr' id='acom_".$x."' name='acom_".$x."' style='width:100%'/></td>";
                                        echo "<td><input type='text' class='hid_value g_input_amo' id='bal_".$x."' name='bal_".$x."' style='width:100%'/></td>";
                                        echo "<td><input type='checkbox' class='chk' id='5_".$x."' name='5_".$x."' value='1' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                 <td></td>                                  
                                 <td></td>
                                 <td></td>
                                 <td style="text-align:right; width:84px !important;">Total</td>                                 
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="total_amount" id="total_amount" style="width:72px;border: 1px solid #003399;margin-right:-91px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="total_sys" id="total_sys" style="width:92px;border: 1px solid #003399;margin-right:-50px; "/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="total_act" id="total_act" style="width:84px;border: 1px solid #003399;margin-right:-40px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="total_bal" id="total_bal" style="width:80px;border: 1px solid #003399;margin-right:-10px;"/></td>
                                 <td>&nbsp;</td>
                            </tr>
                            <tr>
                                 <td></td>                                  
                                 <td></td>
                                 <td></td>

                                 <td style="text-align:right; width:84px !important;"><input type="checkbox"  checked="true" disabled="true"> </td>                                 
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="chk_amount" id="chk_amount" style="width:72px;border: 1px solid #003399;margin-right:-91px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="chk_sys" id="chk_sys" style="width:92px;border: 1px solid #003399;margin-right:-50px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="chk_act" id="chk_act" style="width:84px;border: 1px solid #003399;margin-right:-40px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="chk_bal" id="chk_bal" style="width:80px;border: 1px solid #003399;margin-right:-10px;"/></td>
                                 <td>&nbsp;</td>
                            </tr>
                            <tr>
                                 <td></td>                                  
                                 <td></td>
                                 <td></td>

                                 <td style="text-align:right ; width:84px !important;"><input type="checkbox"  disabled="true"> </td>                                 
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="unchk_amount" id="unchk_amount" style="width:72px;border: 1px solid #003399;margin-right:-91px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="unchk_sys" id="unchk_sys" style="width:92px;border: 1px solid #003399;margin-right:-50px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="unchk_act" id="unchk_act" style="width:84px;border: 1px solid #003399;margin-right:-40px;"/></td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="unchk_bal" id="unchk_bal" style="width:80px;border: 1px solid #003399;margin-right:-10px;"/></td>
                                 <td>&nbsp;</td>
                            </tr>
                            <tr>
                                 <td></td>                                  
                                 <td></td>
                                 <td></td>
                                 <td ></td>
                                 <td ></td>                                 
                                 <td style="text-align:right; width:84px !important;">Difference</td>
                                 <td style="text-align:center; width:84px !important;"><input type="text" class="hid_value g_input_amounts" name="difference" id="difference" style="width:83px;border: 1px solid #003399;margin-right:-40px;"/></td>
                                 <td style="text-align:center; width:84px !important;"></td>
                                 <td>&nbsp;</td>
                            </tr>
                        </tfoot>                  
                    </table> 
                    <div style="text-align: left; padding-top: 7px;">
                        <input type="hidden" id="is_approve" name="is_approve"/>
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_credit_card_reconcil')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_credit_card_reconcil')){ ?><input type="button" id="btnPrint" title="Print" />  <?php } ?>                     
                        <?php if($this->user_permissions->is_add('t_credit_card_reconcil')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>
                     <!--   <?php if($this->user_permissions->is_approve('t_credit_card_reconcil')){ ?><input type="button"  id="btnApprove" title='Approve' /><?php } ?> -->

                    </div>
                
                <?php if($this->user_permissions->is_print('t_credit_card_reconcil')){ ?>
                    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                <?php } ?> 
                </td>
            </tr>
        </table>
    </form>
      <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">      
        <input type="hidden" name='by' value='t_credit_card_reconcil' title="t_credit_card_reconcil" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >                 
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
        <input type="hidden" name='qno' value='' title="" id="qno" >
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
        <input type="hidden" name='cus_id' value='' title="" id="cus_id" > 
        <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" > 
    </form>
</div>
<?php } ?>