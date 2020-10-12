<?php if($this->user_permissions->is_view('022')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_supplier_receipt.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_supplier_receipt/?id='+pid, '_blank');
            window.open('?action=t_supplier_receipt', '_self');
        }else{
            window.open('?action=t_supplier_receipt', '_self');
        }
    });
</script>
<?php } ?>
<style>
.hide_field{ display:none;}
.hid_chq{ display:none;}
</style>
<h2 style="text-align: center;">Supplier Voucher</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_supplier_receipt" id="form_">
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
                <td>Memo</td>
                <td><input type="text" class="input_txt" name="memo" id="memo" title="Memo" style="width: 453px;" maxlength="255" /></td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr><tr>
                <td>Balance</td>
                <td>
                    <input type="text" class="input_amount" name="balance" id="balance" title="Supplier Balance" style="width: 150px;" />
                    <input type="checkbox" id="auto_fill" /> Auto Fill
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;" maxlength="100"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr><th class="tb_head_th">Trans Code</th>
                                <th class="tb_head_th" style="width: 100px;">Purchase No</th>
                                <th class="tb_head_th">Amount</th>
                                <th class="tb_head_th">Balance</th>
                                <th class="tb_head_th">Paid Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                         echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt fo' id='4_".$x."' name='4_".$x."' readonly='readonly' /></td>";                       
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_amo amo' id='1_".$x."' name='1_".$x."' readonly='readonly' style='width : 100%;'/></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo tamo' id='3_".$x."' name='3_".$x."' style='width : 100%;' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <td style="text-align: right;" colspan="3"></td>
                            <td style="padding-right: 20px; text-align: right;">
                                Pay Total : <input type="text" class="input_amount" readonly="readonly" id="total" name="tota" title="" style="border: 1px dotted #000;width: 100px;" />
                            </td>
                        </tfoot>
                    </table>
                    
                    <div id="payment_methods"></div>
                  <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <input type="button" id="btnPayments" title="Payments" />
                        <?php if($this->user_permissions->is_view('022')){ ?>
                        <input type="hidden"  id="btnSave" title='Save <F8>' />
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php } ?>