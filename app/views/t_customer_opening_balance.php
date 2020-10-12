<?php if($this->user_permissions->is_view('024')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_customer_opening_balance.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_customer_opening_balance/?id='+pid, '_blank');
            window.open('?action=t_customer_opening_balance', '_self');
        }else{
            window.open('?action=t_customer_opening_balance', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Customer Opening Balance</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_customer_opening_balance" id="form_">
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
                                <th class="tb_head_th" style="width: 80px;">Customer Code</th>
                                <th class="tb_head_th">Customer Name</th>
                                <th class="tb_head_th" style="width: 80px;">Dr</th>
                                <th class="tb_head_th" style="width: 80px;">Cr</th>
                                <!--<th class="tb_head_th" style="width: 80px;">Discount(%)</th>-->
                                <!--<th class="tb_head_th" style="width: 80px;">Total Amount</th>-->
                            </tr>
                        </thead><tbody>
                            <?php
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_".$x."' readonly='readonly' name='n_".$x."' style='width: 100%;'  maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dr' id='1_".$x."' name='1_".$x."'  /></td>";
                                        echo "<td><input type='text' class='g_input_amo cr' id='2_".$x."' name='2_".$x."' /></td>";
                                        //echo "<td><input type='text' disabled='disabled' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' /></td>";
                                        //echo "<td id='t_".$x."' style='text-align: right;background-color: #f9f9ec;' class='tf'>&nbsp;</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: transparent;">
                                <td>&nbsp;</td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Total</td>
                                <td><input type='text' class='g_input_amo' id='dr_total' readonly="readonly" name='dr_total' title="0.00" style="font-weight: bold;" /></td>
                                <td><input type='text' class='g_input_amo' id='cr_total' readonly="readonly" name='cr_total' title="0.00" style="font-weight: bold;" /></td>
                            </tr>
                        </tfoot>
                        
                    </table>
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                         <?php if($this->user_permissions->is_delete('024')){ ?>
                        <input type="button" id="btnDelete" title="Delete" />
                        <?php } ?>    
                        <!--<input type="button" id="btnPrint" title="Print" />-->
                        <?php if($this->user_permissions->is_add('024')){ ?>
                        <input type="button"  id="btnSave" title='Save <F8>' />
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php } ?>