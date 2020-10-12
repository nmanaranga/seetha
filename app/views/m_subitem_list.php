<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/m_subitem_list.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_open_stock/?id='+pid, '_blank');
            window.open('?action=t_open_stock', '_self');
        }else{
            window.open('?action=t_open_stock', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Sub Item List</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/m_subitem_list" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td>Main Item </td>
                <td>
                    <?=$stores;?>
                    <input type="text" class="input_txt" id="sto_des" title="Stores Name" style="width: 300px;" readonly="readonly" />                </td>
                <td style="width: 50px;"><div align="right">Date</div></td>
                <td><input type="hidden" id="hid" name="hid" title="0" />
				<input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
				
              </td>
            </tr><tr>
                <td colspan="2">&nbsp;</td>
                <!--<td><input type="text" class="input_number" name="pid" id="pid" title="Memo" style="width: 100%;" /></td>-->
                <td style="width: 100px;">&nbsp;</td>
                <td style="width: 100px;">&nbsp;</td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                    <table width="704" id="tgrid" style="width: 875px;">
                        <thead>
                            <tr>
                                <th width="196" class="tb_head_th" style="width: 80px;">Item Code</th>
                                <th width="327" class="tb_head_th">Item Name</th>
                                <th width="80" class="tb_head_th" style="width: 80px;">Quantity</th>
                                <th width="80" class="tb_head_th" style="width: 80px;">Quantity in Carton</th>
                                <!--<th class="tb_head_th" style="width: 80px;">Discount(%)</th>-->
                                <th width="168" class="tb_head_th" style="width: 80px;">Free of Charge</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                        <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' /></td>";
                                        echo "<td><center><input type='checkbox' name='is_active".$x."' id='check".$x."' class='ob_a' title='1' /></center></td>";    
                                        //echo "<td id='t_".$x."' style='text-align: right;background-color: #f9f9ec;' class='tf'>&nbsp;</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: transparent;">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;"></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tfoot>
                  </table>
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <!--<input type="button" id="btnPrint" title="Print" />-->
                        <input type="button"  id="btnSave" title='Save <F8>' />
                    </div>                </td>
            </tr>
        </table>
    </form>
</div>