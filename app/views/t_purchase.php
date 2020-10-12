<?php if($this->user_permissions->is_view('020')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_purchase.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        
       
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_purchase/?id='+pid, '_blank');
            window.open('?action=t_purchase', '_self');
        }else{
            window.open('?action=t_purchase', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Purchase</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_purchase" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Supplier</td>
                <td>
                    <input type="text" class="input_txt" id="ssupplier" title="Supplier Search" style="width: 150px;" />
                    <input type="hidden" name="supplier" id="supplier" title="0" />
                    <input type="text" class="input_txt" title='Supplier' readonly="readonly" id="sup_des"  style="width: 300px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr><tr>
                <td>PO No</td>
                <td>
                    <input type="text" class="input_number" name="pono" id="pono" title="000" />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Invoice No &nbsp;&nbsp;<input type="text" class="input_txt" id="invoice_no" name="invoice_no" title="Invoice No" style="width: 100px;" maxlength="25" />
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr><tr>
                <td>Stores</td>
                <td>
                    <?=$stores;?>
                    <input type="text" class="input_txt" id="sto_des" title="Stores Name" style="width: 300px;" readonly="readonly" />
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;" maxlength="25"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 70px;">Item Code</th>
                                <th class="tb_head_th">Item Name</th>
                                <th class="tb_head_th" style="width: 70px;">Quantity</th>
                                <th class="tb_head_th" style="width: 70px;">Cost</th>
                                <th class="tb_head_th" style="width: 70px;">Discount</th>
                                <th class="tb_head_th" style="width: 70px;">Discount(%)</th>
                                <th class="tb_head_th" style="width: 70px;">Free Issue</th>
                                <th class="tb_head_th" style="width: 70px;">Total Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis_pre' id='4_".$x."' name='4_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo free_is' id='5_".$x."' name='5_".$x."' style='width : 100%;'/></td>";
                                        echo "<td id='t_".$x."' name='t_".$x."' style='text-align: right;background-color: #f9f9ec;' class='tf'>&nbsp;</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: transparent;">
                                <td style="padding-left : 7px;">Memo</td>
                                <td colspan="4" style="padding-left : 10px;"><input type="text" class="input_txt" name="memo" id="memo" title="Memo" style="width: 450px; border: 1px dotted #111;" maxlength="255" /></td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Gross</td>
                                <td><input type='text' class='g_input_amo' id='total2' readonly="readonly" name='total' style="margin-right: 15px; margin-left: 5px; font-weight: bold;" /></td>
                            </tr><tr style="background-color: transparent;">
                                <td style="padding-left : 7px;"></td>
                                <td colspan="4" style="padding-left : 10px;"></td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Discount</td>
                                <td><input type='text' class='g_input_amo' id='discount' name='discount' style="margin-right: 15px; margin-left: 5px; font-weight: bold; border: 1px dotted #111;" /></td>
                            </tr><tr style="background-color: transparent;">
                                <td style="padding-left : 7px;"></td>
                                <td colspan="4" style="padding-left : 10px;"></td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Net Amount</td>
                                <td><input type='text' class='g_input_amo' id='net_amount' readonly="readonly" name='net_amount' style="margin-right: 15px; margin-left: 5px; font-weight: bold;" /></td>
                            </tr>
                        </tfoot>
                        
                    </table>
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Clear" />
                        <input type="button" id="btnDelete5" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <?php if($this->user_permissions->is_view('020')){ ?>
                        <input type="button"  id="btnSave5" title='Save <F8>' />
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php } ?>