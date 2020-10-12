<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    #permission, #root_area{
        z-index: 35;
        position: absolute;
        width: 500px;
        background-color: #FFF;
        padding: 7px;
        top: 180px;
        display: none;
        border: 1px dotted #CCC;
    }
    
    #massage, #massage2{
        font-size: 18px;
        text-align: center;
        font-family: Times;
        color: #da1033;
        font-weight: bold;
        padding-bottom: 7px;
    }
    
    #root_area {
        color: #5270e9;
        width: 350px;
    }
</style>
<script type="text/javascript" src="<?=base_url()?>js/t_dispatch_return.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_dispatch_return/?id='+pid, '_blank');
            window.open('?action=t_dispatch_return', '_self');
        }else{
            window.open('?action=t_dispatch_return', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
if(! isset($sd['sales_ref'])){ $sd['sales_ref'] = 0; } if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'"; sales_ref = "'.$sd['sales_ref'].'"; storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Dispatch Return</h2>
<div id="permission">
    <div id="massage">
        You Have To Get Permission From <span id="perid"></span> User.
    </div>
    <div id="request_body">
        <fieldset style="height: 200px; overflow: auto;">
            <legend>Online Users</legend>
            <span id="online_users">Loding...</span>
        </fieldset>
    </div>
    <div style="text-align: right; padding-top: 7px;">
        <button id="btnRequest">Request</button>
        <button id="btnRefresh">Refresh</button>
        <button id="btnCancel">Cancel</button>
        <button id="btnCloseRequest">Close</button>
    </div>
</div>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_dispatch_return" id="form_">
        <table style="width: 100%" border="0">
            <tr>
              <td style="width: 80px;">Dispatch No </td>
              <td><input type="text" class="input_number" name="pono" id="pono" title="000" /></td>
              <td style="width: 50px;">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td style="width: 80px;">Stores</td>
              <td><?=$stores;?>
                <input name="text3" type="text" class="input_txt" id="sto_des" style="width: 297px; border: 1px dotted #111;" title="Stores Name" readonly="readonly" /></td>
              <td style="width: 50px;">No</td>
              <td><input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                <input type="hidden" id="hid" name="hid" title="0" /></td>
            </tr>
            <tr>
                <td style="width: 80px;">Customer</td>
                <td>
                    <input type="text" class="input_txt" id="scustomers" title="Customer Search" name="cus" style="width: 150px;" />
                    <input type="hidden" name="customer" id="customer" title="0" />
                    <input type="text" class="input_txt" title='Customer Name' readonly="readonly" id="cus_des"  style="width: 404px;">
                    <!--<input type="button" title="Set Root/Area" id="btnSetRootArea" />-->                </td>
                <td style="width: 50px;"><span style="width: 100px;">Date</span></td>
                <td><span style="width: 100px;">
                  <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                </span></td>
            </tr>
            <tr>
              <td>Address</td>
              <td><input name="address" type="text" class="input_txt" id="address"  style="width: 300px;" title='Address' /></td>
              <td style="width: 100px;">Ref. No</td>
              <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;"/></td>
            </tr>
            <tr>
                <td>Reason</td>
                <td><input name="reson" type="text" class="input_txt" id="reson"  style="width: 300px;" title='Reason' />
                    &nbsp;<input type="hidden" class="input_amount" id="rm" readonly="readonly" name="rm" title="Request Mr." style="width: 80px;" maxlength="25" />
                    &nbsp;&nbsp;&nbsp;
                    <!--CM.(%)--> &nbsp;&nbsp;<input type="hidden" class="input_amount" id="cm" readonly="readonly" name="cm" title="Current Mr." style="width: 80px;" maxlength="25" />                </td>
                <td style="width: 100px;">&nbsp;</td>
                <td style="width: 100px;">&nbsp;</td>
            </tr><tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="width: 100px;">&nbsp;</td>
                <td style="width: 100px;">&nbsp;</td>
            </tr><tr>
                <td>&nbsp;</td>
                <td colspan="3">&nbsp;</td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 70px;">Item Code</th>
                                <th class="tb_head_th">Item Name</th>
                                <th class="tb_head_th" style="width: 70px;">Current Stock</th>
                                <th class="tb_head_th" style="width: 70px;">Quantity</th>
                                <th class="tb_head_th" style="width: 70px;"><span class="tb_head_th" style="width: 70px;">QtyC</span></th>
                                <th class="tb_head_th" style="width: 70px;">Cartoon</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' class='item_h' name='h_".$x."' id='h_".$x."' title='0' />
                                        <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt' readonly='readonly' id='n_".$x."' name='n_".$x."' style='width: 100%' maxlength='150'/></td>";
                                         echo "<td><input type='text' readonly='readonly' class='g_input_amo dis_pre' style='text-align: right;' id='4_".$x."' name='4_".$x."' autocomplete='off' /></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun1' id='1_".$x."' name='1_".$x."' autocomplete='off' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' autocomplete='off' />
                                        <input type='hidden' id='hc_".$x."' title='0' /></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' style='text-align: right;' id='3_".$x."' name='3_".$x."' autocomplete='off' /></td>";
                                        echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: transparent;">
                              <td style="padding-left : 7px;">Marketing Co.</td>
                              <td colspan="4" style="padding-left : 10px;"><?=$person;?>
                              <input name="trans_des" type="text" class="input_txt" id="trans_des" style="width: 297px; border: 1px dotted #111;" title="Marketing Coordinator" readonly="readonly" /></td>
                              <td style="text-align: right; font-weight: bold; font-size: 12px;">&nbsp;</td>
                              <td>&nbsp;</td>
                            <tr style="background-color: transparent;">
                              <td style="padding-left : 7px;">Transport Co.</td>
                              <td colspan="4" style="padding-left : 10px;"><?=$person1;?>
                                <input name="mar_des" type="text" class="input_txt" id="mar_des" style="width: 297px; border: 1px dotted #111;" title="Transport Coordinator" readonly="readonly" /></td>
                              <td style="text-align: right; font-weight: bold; font-size: 12px;">&nbsp;</td>
                              <td>&nbsp;</td>
                            <tr style="background-color: transparent;">
                                <td style="padding-left : 7px;">Driver</td>
                                <td colspan="4" style="padding-left : 10px;"><?=$person2;?>
                                <input name="driver_des" type="text" class="input_txt" id="driver_des" style="width: 297px; border: 1px dotted #111;" title="Driver" readonly="readonly" /></td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">&nbsp;</td>
                                <td>&nbsp;</td>
                        </tfoot>
                        <div id="payment_methods">                        </div>
                    </table>
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
						<input type="button"  id="btnSave" title='Save <F8>' />
                    </div>                </td>
            </tr>
        </table>
    </form>
    
    <div id="root_area">
        <div id="massage2">Select Area & Root.</div>
            <table style="width: 100%">
                <tr>
                    <td>Area</td>
                    <td>
                        <?=$area;?>
                    </td>
                </tr><tr>
                    <td>Route</td>
                    <td>
                        <select id="route" style="width: 300px;">
                            <option value="0">---</option>
                        </select>
                    </td>
                </tr><tr>
                    <td colspan="2" style="text-align: right; padding-top: 7px;">
                        <input type="button" title='Save <F8>' id="btnSaveAreaRoot" />
                    </td>
                </tr>
            </table>
    </div>
</div>