<?php if($this->user_permissions->is_view('t_pettycash')){ ?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/t_forms.css" />
<script type="text/javascript" src="<?= base_url() ?>js/t_pettycash.js"></script>
<h2 style="text-align: center;">Petty Cash </h2>
<div class="msgBox">
    <div class="msgInner">Saving Success</div>
</div>
<div class="dframe" id="mframe">
    <form method="post" action="<?= base_url() ?>index.php/main/save/t_pettycash" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" style="width:100%" class="input_active_num" name="id" id="id" title="<?= $max_no ?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />                </td>
            </tr>
            <tr>
                <td><span style="width: 100px;"> Account </span></td>
                <td>
                    <input type="text" class="hid_value" id="saccount" name="saccount" readonly='readonly' title="" style="width: 150px;" />
                    <input type="hidden" name="account" id="account" title="0" />
                    <input type="text" class="hid_value" title='' readonly="readonly" id="account_des"  style="width: 300px;"/>
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_pettycash')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?= date('Y-m-d') ?>" style="text-align:right;"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?= date('Y-m-d') ?>" style="text-align:right;"/>
                    <?php } ?>    
                </td>
            </tr>
            <tr>
                <td><span style="width: 100px;">To</span></td>
                <td><input name="description" type="text" class="input_txt" id="description"  style="width: 450px;" title='' maxlength="50"/></td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;" maxlength="10"/></td>
            </tr>
            <tr>
                <td>Narration</td>
                <td><input name="narration" type="text" class="input_txt" id="narration"  style="width: 450px;" title='' maxlength="50" /></td>
                <td style="width: 100px;">&nbsp;</td>
                <td style="width: 80px;">&nbsp;</td>
            </tr>
             <tr>
                <td>Category</td>
                <td>
                    <?php echo $sales_category;?>
                    <input type="hidden" id="sales_category1" name="sales_category1" title="0" />
                </td>
                <td style="width: 100px;"></td>
                <td style="width: 80px;"></td>
            </tr>
            <tr>
                <td>Group</td>
                <td>
                    <?php   echo $groups;?>
                </td>
                <td style="width: 100px;"></td>
                <td style="width: 80px;"></td>
            </tr>
         
            <tr>
                <td colspan="4" style="text-align: center;">

                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 200px;"> Account Code </th>
                                <th class="tb_head_th">Account</th>
                                <th class="tb_head_th">Description</th>
                                <th class="tb_head_th" style="width: 200px;">Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                            $y = $grid->value;
                            $xx = $option->autofill_payable;

                            for ($x = 0; $x < $y; $x++) {
                                echo "<tr>";
                                echo "<td><input type='hidden' name='h_" . $x . "' id='h_" . $x . "' title='0' />
                                             <input type='text' class='g_input_txt fo' id='0_" . $x . "' name='0_" . $x . "'  /></td>";
                                echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_" . $x . "' name='n_" . $x . "' style='width:100%' readonly='readonly' maxlength='150'/></td>";
                                echo "<td><input type='text' class='g_input_txt' id='2_" . $x . "' name='2_" . $x . "' style='width:100%' /></td>";
                                echo "<td><input type='text' class='g_input_amo cr' id='1_" . $x . "' name='1_" . $x . "' style='width:100%' /></td>";

                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_pettycash')){ ?><input type="button" id="btnDelete1" title="Delete" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_pettycash')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                        <?php if($this->user_permissions->is_add('t_pettycash')){ ?><input type="button"  id="btnSave1" title='Save <F8>' /><?php } ?>

                        <span style="text-align:right;margin-left:315px;font-weight: bold;">Total

                            <input type="text" class="g_input_amo" name="total" id="total" title="0.00" style="width:200px;font-weight: bold;"/>
                            <input type="hidden" class="g_input_txt" name="grid_row" id="grid_row" title="<?= $y ?>" style="width:100px;"/>
                            <input type="hidden" class="g_input_txt" name="autofill_payable" id="autofill_payable" title="<?= $xx ?>" style="width:100px;"/>
                        </span>    
                                        </div>                </td>
            </tr>
        </table>
        <?php 
        if($this->user_permissions->is_print('t_pettycash')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
        <?php } ?> 
    </form>
</div>
<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

    <input type="hidden" name='by' value='t_pettycash' title="t_pettycash" class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" name='type' value='' title="" >
    <input type="hidden" name='header' value='false' title="false" >
    <input type="hidden" name='qno' value='' title="" id="qno" >
     <input type="hidden" name='gno' value='' title="" id="gno" >
    <input type="hidden" name='pblt' value='' title="" id="pblt" >
    <input type="hidden" name='clus' value='' title="" id="clus" >
    <input type="hidden" name='price' value='' title="" id="price" >
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