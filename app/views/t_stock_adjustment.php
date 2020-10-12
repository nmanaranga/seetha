<?php if($this->user_permissions->is_view('036')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_stock_adjustment.js"></script>
<?php
if(isset($_GET['print'])){
        
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
   
?>
<script type="text/javascript">
    $(document).ready(function(){
        
   
        
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_stock_adjustment/?id='+pid, '_blank');
            window.open('?action=t_stock_adjustment', '_self');
        }else{
            window.open('?action=t_stock_adjustment', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Stock Adjustment</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_stock_adjustment" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td>Stores</td>
                <td>
                    <?=$stores;?>
                    <input type="text" class="input_txt" id="sto_des" title="Stores Name" style="width: 300px;" readonly="readonly" />
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
                <td colspan="2">&nbsp;</td>
                <!--<td><input type="text" class="input_number" name="pid" id="pid" title="Memo" style="width: 100%;" /></td>-->
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;" maxlength="25"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Item Code</th>
                                <th class="tb_head_th">Item Name</th>
                                <th class="tb_head_th" style="width: 80px;">System Qun.</th>
                                <th class="tb_head_th" style="width: 80px;">Actual Qun.</th>
                                                               <th class="tb_head_th" style="width: 80px;">Balance</th>
                                <th class="tb_head_th" style="width: 80px;">Purchase Price</th>
                                <th class="tb_head_th" style="width: 80px;">Value</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt'  readonly='readonly'  id='n_".$x."' name='n_".$x."' maxlength='150' /></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' readonly='readonly' /></td>";
                                        echo "<td><input type='text' class='g_input_amo acu' id='2_".$x."' name='2_".$x."' /></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' disabled='disabled' class='g_input_amo dis' id='3_".$x."' name='3_".$x."'  readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text'  class='g_input_amo dis' id='4_".$x."' name='4_".$x."' readonly='readonly'  /></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text'  class='g_input_amo dis' id='5_".$x."' name='5_".$x."' readonly='readonly'  /></td>";
                                        //echo "<td id='t_".$x."' style='text-align: right;' class='tf'>&nbsp;</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <?php if($this->user_permissions->is_view('036')){ ?>
                        <input type="button"  id="btnSave" title='Save <F8>' />
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php } ?>