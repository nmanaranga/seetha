<?php if($this->user_permissions->is_view('t_open_stock')){ ?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/t_forms.css" />
<script type="text/javascript" src="<?= base_url() ?>js/t_open_stock.js"></script>
<script type='text/javascript' src='<?= base_url() ?>js/t_serial_movement.js'></script>

<div id="fade" class="black_overlay"></div>
<?php
if ($ds['use_serial_no_items']) {
    $this->load->view('t_serial_in.php');
}
?>


<div id="fade" class="black_overlay"></div>

<!--t_open_stock form begin here-->

<h2 style="text-align: center;">Opening Stock</h2>
<div class="dframe" id="mframe" style="width:940px;">
    <form method="post" action="<?= base_url() ?>index.php/main/save/t_open_stock" id="form_">
        <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
        <input type="hidden" name="srls" id="srls"/>
        <input type='hidden' id='transCode' value='2' title='2'/>
        <table style="width: 100%" border="0">
            <tr>
                <td>Stores</td>
                <td>
                    <?php echo $stores; ?>
                    <input type="text" class="hid_value" id="sto_des" title="" style="width: 300px;" readonly="readonly" />
                </td>
                <td style="width: 50px;">No</td>
                <td>

                    <input type="text" class="input_active_num" name="id" id="id" style="width:100%;" value="<?php echo $max_no; ?>" title="<?php echo $max_no; ?>"/>
                    <input type="hidden" id="hid" name="hid" title="0" />

                </td>
            </tr><tr>
                <td></td>
                <td></td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_open_stock')){ ?>
                        <input type="text" readonly="readonly" name="ddate" id="ddate" title="<? echo $open_bal_date; ?>"  readonly style="text-align:right;"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate" title="<?= date('Y-m-d') ?>" style="text-align:right;"/>
                    <?php } ?>    
                    </td>
            </tr><tr>
                <td colspan="2">&nbsp;</td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt_f" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;" maxlength="25"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">


                    <table style="width:920px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 130px;">Item Code</th>
                                <th class="tb_head_th" style="width: 190px;">Item Name</th>
                                <th class="tb_head_th" style="width: 80px;">Model</th>
                                <th class="tb_head_th" style="width: 80px;">Quantity</th>
                                <th class="tb_head_th" style="width: 80px;">Cost</th>
                                <th class="tb_head_th" style="width: 80px;">Min Price</th>
                                <th class="tb_head_th" style="width: 80px;">Max Price</th>
                                <th class="tb_head_th" style="width: 80px;">Total Amount</th>
                            </tr>
                        </thead><tbody>
                        <input type='hidden' id='transtype' title='OPENING STOCK' value='OPENING STOCK' name='transtype' />
                        <?php
                        for ($x = 0; $x < 40; $x++) {
                            echo "<tr>";
                            echo "<td><input type='hidden' name='h_" . $x . "' id='h_" . $x . "' title='0' />
                                          <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />   
                                          <input type='text' class='g_input_txt fo' id='0_" . $x . "' name='0_" . $x . "'  style='width:100%;'/></td>";
                            echo "<td class='g_col_fixed'><input type='text' class='g_input_txt g_col_fixed'  id='n_" . $x . "' name='n_" . $x . "' style='width:100%;'/>
                                          <input type='button'  class='subs' id='sub_" . $x . "' style='margin:0;padding:5px;float: right; height:6px;width:6px;cursor:pointer'/></td>";
                            echo "<td ><input type='text' class='g_input_txt g_col_fixed' readonly id='m_" . $x . "' name='m_" . $x . "' style='width:100%;'/></td>";
                            echo "<td ><input type='button'  class='quns' id='btn_" . $x . "' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                          <input type='text' class='g_input_num2 qun qtt_" . $x . "' id='1_" . $x . "' style='width:100%; float:right;' name='1_" . $x . "' />
                                          <input type='hidden' id='setserial_" . $x . "' title='0' name='setserial_" . $x . "' />
                                          <input type='hidden' id='numofserial_" . $x . "' title='' name='numofserial_" . $x . "' />
                                          <input type='hidden' id='itemcode_" . $x . "' title='0' name='itemcode_" . $x . "' />
                                                                                     
                                          </td>";
                            echo "<td ><input type='text' class='g_input_amo amo' id='2_" . $x . "' name='2_" . $x . "' /></td>";
                            echo "<td><input type='text'  class='g_input_amo amo ' id='min_" . $x . "' name='min_" . $x . "' /></td>";
                            echo "<td><input type='text'  class='g_input_amo amo ' id='max_" . $x . "' name='max_" . $x . "' /></td>";
                            echo "<td style='background-color: #f9f9ec; text-align:right;' id='t_" . $x . "'  class='tf'> </td>";
                            echo "<td><input type='hidden' id='subcode_" . $x . "' title='0' name='subcode_" . $x . "' />
                                         <input type='hidden' id='is_click_" . $x . "' title='0' name='is_click_" . $x . "'/></td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>



                    <table style="width:100%" boder="1">
                        <tr style="background-color: transparent;">
                            <td width="712">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: left; font-weight: bold; font-size: 12px;">Amount</td>
                            <td><input type='text' class='hid_value g_input_amounts' id='total2' readonly="readonly" name='total'/></td>
                        </tr>
                    </table>



                    <div style="text-align: right; padding-top: 7px;">

                        
                        <input type="button" id="chkserial" title="Check Serials" />
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_open_stock')){ ?><input type="button" id="btnDelete5" title="Cancel" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_open_stock')){ ?><input type="button" id="btnPrint" title="Print" /> <?php } ?>                    
                        <?php if($this->user_permissions->is_add('t_open_stock')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
              <td colspan="6" style="color:red; font-size:12px;">** For View Item Details, Double Click Item Code</td>
            </tr>
        </table>
        <?php 
        if($this->user_permissions->is_print('t_open_stock')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
        <?php } ?> 
    </form>
</div>
<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

    <input type="hidden" name='by' value='t_open_stock' title="t_open_stock" class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" name='type' value='' title="" >
    <input type="hidden" name='header' value='false' title="false" >
    <input type="hidden" name='qno' value='' title="" id="qno" >
    <input type="hidden" name='rep_sup' value='' title="" id="rep_sup" >
    <input type="hidden" name='rep_ship_bc' value='' title="" id="rep_ship_bc" >
    <input type="hidden" name='inv_date' value='' title="" id="inv_date" >
    <input type="hidden" name='inv_nop' value='' title="" id="inv_nop" >
    <input type="hidden" name='po_nop' value='' title="" id="po_nop" >
    <input type="hidden" name='po_dt' value='' title="" id="po_dt" >
    <input type="hidden" name='credit_prd' value='' title="" id="credit_prd" >
    <input type="hidden" name='rep_deliver_date' value='' title="" id="rep_deliver_date" >
    <input type="hidden" name='jtype' value='' title="" id="jtype" >
    <input type="hidden" name='jtype_desc' value='' title="" id="jtype_desc" >

</form>
<?php } ?>