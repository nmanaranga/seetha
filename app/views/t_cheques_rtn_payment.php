<?php if($this->user_permissions->is_view('t_cheques_rtn_payment')){ ?>
<style>
.sc
{
width:500px;
height:50px;
overflow:scroll;
}
</style>
<script type='text/javascript' src='<?=base_url()?>js/t_cheques_rtn_payment.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<h2>Cheque Payment Return</h2>
<div class="dframe" id="mframe" style=" margin-top:10px; width:800px;" >
    <form method="post" action="<?=base_url()?>index.php/main/save/t_cheques_rtn_payment" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width:300px;" colspan="2" rowspan="2">
            		<input type="radio" class="chk_t" id="chq_return" checked="true" name="chck" >Cheque Return
            		<span style='display:none;'><input type="radio" class="chk_t" id="chq_refund" name="chck" >Cheque Refund</span>
                    <input type="hidden" id="chq_type" name="chq_type" value="1">
                    <input type="hidden" id="pre_status" name="pre_status" value="">
                </td>
                <td></td>
                <td></td>	
                <td style="width:80px;">Type</td>
                <td>
                    <select style="width:100%;" name="type" id="type">
                        <option value='1'>GRN</option>
                        <!-- <option value='2'>HP</option>
                        <option value='3'>GRN</option> -->

                    </select>
                </td>
            </tr>
            <tr>
                 <td></td>
                 <td></td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" id="id" name="id" title="<?=$id?>" style="width:100px;" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr>
            <tr>
            <td>Cheque No</td>
           <td colspan="1">
                    <input type="text" class="input_active_num" id="cheque_no" name="cheque_no" style="width:100px;" />
                    <input type="hidden" name="load_no" id="load_no">
                    <input type="hidden" name="supplier" id="supplier">
                </td>
                <td><span style='display:none;'>Credit Note</span></td>
                <td><span style='display:none;'>
                    <input type="radio" id="drn" name="refund_type" value="1" title="1">
                   Unsettle <input type="radio" id="unsettle" name="refund_type" value="2" title="2">
                   </span>
                </td>
                <td>Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_cheques_rtn_payment')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                    <?php } ?> 
                </td>
            </tr>
            <tr>
            	<td>New Bank Date</td>
            	<td colspan="1">
                    <input type="text" class="input_date_down_future" readonly="readonly" name="new_ddate" id="new_ddate" title="<?=date('Y-m-d')?>" style="width:100px; text-align:right;"/>
                </td>
                <td style="width:100px">Credit Note No</td>
                <td><input type="text" class="hid_value" id="credit_no" name="credit_no" title="<?=$credit_no?>" style="width:100px;" /></td>
                <td>Ref No</td>
                <td><input type="text" class="input_txt" id="ref_no" name="ref_no" style="width:100px;" /></td>
           </tr>

            <tr>
            	<td>Bank</td>
            	<td colspan="3">
                    <input type="text" class="input_active_num" id="bank" name="bank" style="width:100px;" />
                    <input type="text" class="hid_value" name="bank_des" id="bank_des" maxlength="255" style="width:319px;"/>
                    <input type="hidden" name="bank_acc" id="bank_acc"/>
                </td>
                <td>Trans Code</td>
                <td>
                    <input type="text" class="hid_value" id="trans_code" title="" style="width:100px;" />
                    <input type="hidden"  id="trans_code_c" name="trans_code_c"/>
                </td>
            </tr>

            <tr>
            	<td>Cheque value</td>
            	<td colspan="1"><input type="text" class="input_active_num" id="cheque_val_1" name="cheque_val_1" style="width:100px;" /></td>
            	<td>Realize Date</td>
                <td><input type="text" class="hid_value" id="realize_date" name="realize_date" style="width:100px;" /></td>
                <td>Trans No</td>
                <td><input type="text" class="hid_value" id="Trans_no" name="Trans_no" style="width:100px;" /></td>
            </tr>

            <tr>
            	<td>Account</td>
            	<td colspan="3"><input type="text" name="account" class="input_active_num" id="account" style="width:100px;"/><input type="text" class="hid_value" name="acc" id="acc" maxlength="255" style="width:322px;"/></td>
                <td>Cheque Value</td>
                <td><input type="text" class="hid_value" id="cheque_value" title="" style="width:100px;" /></td>
            </tr>

            <tr>
            	<td>Memo</td>
            	<td colspan="3"><input type="text" class="hid_value" name="memo" id="memo" style="width:422px;"/></td>
            	<td>Cash Value</td>
                <td><input type="text" class="hid_value" id="cash_value" title="" style="width:100px;" /></td>
                 
           </tr>
           
        </table>
        <br>
        <div id="tabs">
                <ul>
                    <li><a href="#tabs-1" >Purchase</a></li>
                   <!--  <li><a href="#tabs-2" >Hp Sales</a></li> -->
                  
                
                </ul>
        <div id="tabs-1">
                    <table id="tgrid" style="width: 765px;" class="sc">
                        <thead>
                            <tr>
                            <th class="tb_head_th" style="width:100px;">Date</th>
                            <th class="tb_head_th" style="width:100px;">Inv.No</th>
                            <th class="tb_head_th" style="width:100px;">Amont</th>
                            <th class="tb_head_th" style="width:100px;">Balance</th>
                            <th class="tb_head_th" style="width:100px;">Paid</th>
                            <th class="tb_head_th" style="width:100px;">Return</th>
                            <!-- <th class="tb_head_th" style="width:100px;">Capital</th>
                            <th class="tb_head_th" style="width:100px;">Interest</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for($x=0; $x<25; $x++){
                                echo "<tr>";
                                echo "<td style='width:100px;'><input type='text' class='hid_value'  id='date_".$x."' name='date_".$x."' style='width:100%;text-align:Right;'/></td>";
                                echo "<td style='width:100px;'><input type='text' class='hid_value'  id='inv_".$x."' name='inv_".$x."' style='width:100%;text-align:Right;'/></td>";
                                echo "<td style='width:100px;'><input type='text' class='hid_value'  id='amount_".$x."' name='amount_".$x."' style='width:100%; text-align:Right;' maxlength='20'/></td>";
                                echo "<td style='width:100px;'><input type='text' class='hid_value'  id='balance_".$x."' name='balance_".$x."' style='width:100%;text-align:Right;'/></td>";
                                echo "<td style='width:100px;'><input type='text' class='hid_value'  id='paid_".$x."' name='paid_".$x."' style='width:100%;text-align:Right;'/></td>";
                                echo "<td style='width:100px;'><input type='text' class='hid_value'  id='return_".$x."' name='return_".$x."' style='width:100%; text-align:Right;' maxlength='20'/></td>";
                                /*
                                echo "<td ><input type='text' class='hid_value'  id='capital' name='capital' style='width:100%;text-align:Right;'/></td>";
                                echo "<td ><input type='text' class='hid_value'  id='interst' name='interst' style='width:100%;text-align:right;'/></td>";
                                */
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td style='text-align:right; font-weight:bold;'>Total</td>
                            <td><input type='text' class='hid_value'  id='amount_tot' name='amount_tot' style='width:100%; text-align:right; font-weight: bold;' /></td>
                            <td><input type='text' class='hid_value'  id='balance_tot' name='balance_tot' style='width:100%; text-align:right; font-weight: bold;'/></td>
                            <td><input type='text' class='hid_value'  id='paid_tot' name='paid_tot' style='width:100%; text-align:right; font-weight: bold;' /></td>
                            <td><input type='text' class='hid_value'  id='return_tot' name='return_tot' style='width:100%; text-align:right; padding-right: 23px; font-weight: bold;' /></td>
                            <!-- <td><input type='text' class='hid_value'  id='capital_tot' name='capital_tot' style='width:100%; text-align:right;' maxlength='20'/></td>
                            <td><input type='text' class='hid_value'  id='interest_tot' name='interest_tot' style='width:100%; text-align:right;' maxlength='20'/></td> -->
                        </tr>
                        </tfoot>
                        </table>
        </div>
       <!--  <div id="tabs-2">
            <table style="width: 765px;" class="sc">
                                    <thead>
                                    <tr>
                                    <th class="tb_head_th" style="width:100px;">No</th>
                                    <th class="tb_head_th" style="width:100px;">Ins.No</th>
                                    <th class="tb_head_th" style="width:100px;">Type</th>
                                    <th class="tb_head_th" style="width:100px;">Trans No</th>
                                    <th class="tb_head_th" style="width:100px;">Date</th>
                                    <th class="tb_head_th" style="width:100px;">Amount</th>
                                    <th class="tb_head_th" style="width:100px;">Balance</th>
                                    <th class="tb_head_th" style="width:100px;">Paid</th>
                                    <th class="tb_head_th" style="width:100px;">Return</th>
                                    </tr>
                                </thead><tbody>
                                <?php
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                    echo "<td ><input type='text' class='hid_value'  id='no' name='no' style='width:100%;text-align:Right;'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='ins_no' name='ins_no' style='width:100%;text-align:Right;'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='types' name='types' style='width:100%; text-align:right;' maxlength='20'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='trans_no' name='trans_no' style='width:100%;text-align:right;'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='date' name='date' style='width:100%;text-align:right;'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='amount' name='amount' style='width:100%; text-align:right;' maxlength='20'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='balance' name='balance' style='width:100%;text-align:right;'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='paid' name='paid' style='width:100%;text-align:right;'/></td>";
                                    echo "<td ><input type='text' class='hid_value'  id='return' name='return' style='width:100%;text-align:right;'/></td>";    
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style='text-align:right; font-weight:bold;'>Total</td>
                            <td><input type='text' class='hid_value'  id='amount_tot' name='amount_tot' style='width:100%; text-align:right;' maxlength='20'/></td>
                            <td><input type='text' class='hid_value'  id='balance_tot' name='balance_tot' style='width:100%; text-align:right;' maxlength='20'/></td>
                            <td><input type='text' class='hid_value'  id='paid_tot' name='paid_tot' style='width:100%; text-align:right;' maxlength='20'/></td>
                            <td><input type='text' class='hid_value'  id='return_tot' name='return_tot' style='width:100%; text-align:right;' maxlength='20'/></td>
                        </tr>
                        </table>
        </div> -->
        
    </div>

        <table>
            <tr>
                <td>Description</td>
                <td><input type='text' name='des' id='des' class="input_txt" style="width:400px;"/></td>
                <td style="width:50px;">&nbsp;</td>
                <td>Cheque Return Chargers</td>
                <td><input type='text' name='cqh_ret_charge' title='0.00' id='cqh_ret_charge' class="amt g_input_amo" style="width:117px;border: 1px solid #003399;font-size: 10px;font-weight: bold;padding: 3px;"/></td>
            </tr>

            <tr>
                <td>DR Account</td>
                <td>
                    <input type='text' name='dr_acc' id='dr_acc' class="hid_value" style="width:150px;"/>
                    <input type='text' name='dr_acc_des' id='dr_acc_des' class="hid_value" readonly="readonly" style="width:250px;"/>
                </td>
                <td style="width:50px;">&nbsp;</td>
                <td>Other Chargers</td>
                <td><input type='text' name='other_charge' title='0.00' id='other_charge' class="amt g_input_amo" style="width:117px;border: 1px solid #003399;font-size: 10px;font-weight: bold;padding: 3px;"/></td>
            </tr>

            <tr>
                <td>CR Account</td>
                <td>
                    <input type='text' name='cr_acc' id='cr_acc' class="hid_value" style="width:150px;"/>
                    <input type='text' name='cr_acc_des' id='cr_acc_des' class="hid_value" readonly="readonly" style="width:250px;"/>
                </td>
                <td style="width:50px;">&nbsp;</td>
                <td>Total</td>
                <td><input type='text' name='tot_charge' title='0.00' id='tot_charge' class=" g_input_amo" style="width:117px;border: 1px solid #003399;font-size: 10px;font-weight: bold;padding: 3px;"/></td>
            </tr>
        </table>
                <div style="text-align:right; padding-top: 7px;">
                <input type="button" id="btnExit" title="Exit" />
                <input type="button" id="btnReset" title="Reset" />
                <?php if($this->user_permissions->is_delete('t_cheques_rtn_payment')){ ?>
                    <input type="button" id="btnDelete" title="Delete" />
                <?php } ?>
                <?php if($this->user_permissions->is_re_print('t_cheques_rtn_payment')){ ?>
                    <input type="button" id="btnPrint" title="Print" /> 
                <?php } ?>
                <?php if($this->user_permissions->is_add('t_cheques_rtn_payment')){ ?>
                    <input type="button"  id="btnSave" title='Save <F8>' />
                <?php } ?>     
                </div>
        <?php if($this->user_permissions->is_print('t_cheques_rtn_payment')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
        <?php } ?>         
    </form>

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">      
        <input type="hidden" name='by' value='t_cheques_rtn_payment' title="t_cheques_rtn_payment" class="report">
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