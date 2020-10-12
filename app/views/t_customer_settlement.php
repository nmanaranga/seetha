<?php if($this->user_permissions->is_view('034')){ ?>
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
<script type="text/javascript" src="<?=base_url()?>js/t_customer_settlement.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_customer_settlement/?id='+pid, '_blank');
            window.open('?action=t_customer_settlement', '_self');
        }else{
            window.open('?action=t_customer_settlement', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'";</script>';
?>
<h2 style="text-align: center;">Customer Settlement</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_customer_settlement" id="form_">
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
                    Debit : <input type="text" class="input_amount" readonly="readonly" name="dbalance" id="dbalance" title="Debit Balance" style="width: 100px;" />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Credit : <input type="text" class="input_amount" readonly="readonly" name="cbalance" id="cbalance" title="Credit Balance" style="width: 100px;" />
                    <input type="checkbox" id="auto_fill" /> Auto Fill
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;" maxlength="100"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;">
                        <tr>
                            <td style="width: 50%;" >
                                <table style="width: 100%;" id="tgrid">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width: 70px;">Type</th>
                                            <th class="tb_head_th">No</th>
                                            <th class="tb_head_th">Total</th>
                                            <th class="tb_head_th">Paid</th>
                                            <th class="tb_head_th">Balance</th>
                                            <th class="tb_head_th">Settle.</th>
                                        </tr>
                                    </thead><tbody>
                                        <?php
                                            //if will change this counter value of 25. then have to change edit model save function.
                                            for($x=0; $x<25; $x++){
                                                echo "<tr>";
                                                    echo "<td><input type='text' class='g_input_txt fo' id='c0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c1_".$x."' readonly='readonly' style='width : 100%;'/></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c2_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c3_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c4_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo pay_amo' id='c5_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </td><td style="width: 50%;" >
                                <table style="width: 100%;" id="tgrid1">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width: 70px;">Type</th>
                                            <th class="tb_head_th">No</th>
                                            <th class="tb_head_th">Total</th>
                                            <th class="tb_head_th">Paid</th>
                                            <th class="tb_head_th">Balance</th>
                                            <th class="tb_head_th">Settle.</th>
                                        </tr>
                                    </thead><tbody>
                                        <?php
                                            //if will change this counter value of 25. then have to change edit model save function.
                                            for($x=0; $x<25; $x++){
                                                echo "<tr>";
                                                    echo "<td><input type='text' class='g_input_txt fo' id='d0_".$x."' name='d0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d1_".$x."' name='d1_".$x."' readonly='readonly' style='width : 100%;'/></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d2_".$x."' name='d2_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d3_".$x."' name='d3_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d4_".$x."' name='d4_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo set_amo' id='d5_".$x."' name='d5_".$x."' style='width : 100%;' /></td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>                        
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                   <!-- <div id="payment_methods">
                        
                    </div>-->
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <!--<input type="button" id="btnPrint" title="Print" />-->
                        <!--<input type="button" id="btnPayments" title="Payments" />-->
                        <?php if($this->user_permissions->is_view('034')){ ?>
                        <input type="button"  id="btnSave" title='Save <F8>' />
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