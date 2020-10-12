
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_customer_opening_cheque.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_customer_opening_cheque/?id='+pid, '_blank');
            window.open('?action=t_customer_opening_cheque', '_self');
        }else{
            window.open('?action=t_customer_opening_cheque', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Customer Opening Cheque</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_customer_opening_cheque" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
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
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th">Customer</th>
                                <th class="tb_head_th">Bank</th>
                                <th class="tb_head_th">Bank Branch</th>
                                <th class="tb_head_th" style="width: 80px;">Cheque No</th>
                                <th class="tb_head_th" style="width: 80px;">Acount No</th>
                                <th class="tb_head_th" style="width: 80px;">R. Date</th>
                                <th class="tb_head_th" style="width: 80px;">Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' />
                                                <input type='hidden' name='h_".$x."' id='h_".$x."' /></td>";
                                        echo "<td><input type='hidden' name='qbh_".$x."' id='qbh_".$x."' title='0' />
                                                    <input type='text' class='g_input_txt bank' id='q0_".$x."' name='q0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td><input type='hidden' name='qbbh_".$x."' id='qbbh_".$x."' title='0' />
                                                    <input type='text' class='g_input_txt branch'  id='qn_".$x."' name='qn_".$x."' style='width : 100%;' /></td>";
                                        echo "<td><input type='text' class='g_input_txt cheque_no' id='q1_".$x."' name='q1_".$x."' style='width : 100%;'  /></td>";
                                        echo "<td><input type='text' class='g_input_txt account_no' id='q2_".$x."' name='q2_".$x."' style='width : 100%;'  /></td>";
                                        echo "<td><input type='text' class='input_date_down_future' style='border : none; background-color : transparent;' readonly='readonly' id='q4_".$x."' name='q4_".$x."' title='' /></td>";
                                        echo "<td><input type='text' class='g_input_amo ttt' id='q3_".$x."' name='q3_".$x."' style='width : 100%;'  /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: transparent;">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Total</td>
                                <td><input type='text' class='g_input_amo' id='total' readonly="readonly" name='total' style="font-weight: bold;" title="0.00" /></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                       
                        <input type="button" id="btnDelete" title="Delete" />
                        
                        <!--<input type="button" id="btnPrint" title="Print" />-->
                        <input type="button"  id="btnSave" title='Save <F8>' />
                       
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
