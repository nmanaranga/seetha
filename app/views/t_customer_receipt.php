<?php if($this->user_permissions->is_view('028')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    #root_area{
        z-index: 35;
        position: absolute;
        width: 500px;
        background-color: #FFF;
        padding: 7px;
        top: 180px;
        display: none;
        border: 1px dotted #CCC;
        color: #5270e9;
        width: 350px;
    }
    
    #massage2{
        font-size: 18px;
        text-align: center;
        font-family: Times;
        color: #da1033;
        font-weight: bold;
        padding-bottom: 7px;
    }
    
    #tgrid tr:hover{
        cursor: pointer;
        background-color: #f3f5c0;
    }
</style>
<script type="text/javascript" src="<?=base_url()?>js/t_customer_receipt.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_customer_receipt/?id='+pid, '_blank');
            window.open('?action=t_customer_receipt', '_self');
        }else{
            window.open('?action=t_customer_receipt', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'";</script>';
?>
<style>

.hid_chq_issue{ display:none;}
</style>
<h2 style="text-align: center;">Customer Receipt</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_customer_receipt" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Customer</td>
                <td>
                    <input type="text" class="input_txt" id="scustomers" title="Customer Search" style="width: 150px;" />
                    <input type="hidden" name="customer" id="customer" title="0" />
                    <input type="text" class="input_txt" title='Customer Name' readonly="readonly" id="cus_des"  style="width: 300px;">
                    <!--<input type="button" title="Set Root/Area" id="btnSetRootArea" />-->
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
                    <input type="text" class="input_amount" name="balance" id="balance" title="Customer Balance" style="width: 150px;" />
                   <input type="checkbox" id="is_auto" name="is_auto"/> 
                   Auto Fill
                Amount&nbsp;<input type="text" class="input_amount" name="t_amount" id="t_amount" title="Amount" maxlength="255" /></td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;" maxlength="100"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr><th class="tb_head_th">Trans Code</th>
                                <th class="tb_head_th" style="width: 100px;">Invoice No</th>
                                <th class="tb_head_th">Amount</th>
                                <th class="tb_head_th">Balance</th>
                                <th class="tb_head_th">Paid Amount</th>                                
                                <th class="tb_head_th">Description</th>
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
                                         echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt fo' id='5_".$x."' name='5_".$x."' readonly='readonly' /></td>";
                                       
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>                        
                    </table>
                    <table style="width: 100%;">
                        <tr>
                            <td width="39"></td>
                            <td width="39">                            </td>
                            <td width="576" style="text-align: right;">Pay Total</td>
                            <td width="153" style="text-align: right;"><span style="width: 150px; padding-right: 20px;">
                              <input type="text" class="input_amount" id="total" name="total" title="Pay Amount" style="width: 150px;" />
                            </span></td>
                          <td width="169" style="width: 150px; padding-right: 20px;">&nbsp;</td>
                        </tr>
                    </table>
                    <div id="payment_methods">
                        
                    </div>
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <input type="button" id="btnPayments" title="Payments" />
                        <?php if($this->user_permissions->is_view('028')){ ?>
                        <input type="hidden"  id="btnSave" title='Save <F8>' />
                        <?php } ?>
                    </div>
                </td>
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
<?php } ?>