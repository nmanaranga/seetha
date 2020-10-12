<?php if($this->user_permissions->is_view('027')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_sales_return.js"></script>

<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_sales_return/?id='+pid, '_blank');
            window.open('?action=t_sales_return', '_self');
        }else{
            window.open('?action=t_sales_return', '_self');
        }
    });
</script>
<?php
}

if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
if(! isset($sd['sales_ref'])){ $sd['sales_ref'] = 0; } if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'"; sales_ref = "'.$sd['sales_ref'].'"; storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Sales Return</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_sales_return" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Customer</td>
                <td>
                    <input type="text" class="input_txt" id="scustomers" title="Customer Search" style="width: 150px;" />
                    <input type="hidden" name="customer" id="customer" title="0" />
                    <input type="text" class="input_txt" title='Customer Name' readonly="readonly" id="cus_des"  style="width: 300px;">
                    <!--<input type="text" class="input_txt" title='Customer Name' readonly="readonly" id="sup_des"  style="width: 300px;">-->                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />                </td>
            </tr>
            <tr>
              <td>Sales</td>
              <td><select name="sale" id="sale">
                <option value="0">Cash sales</option>
                <option value="1">Credit Sales</option>
              </select></td>
              <td style="width: 100px;">Date</td>
              <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr>
            <tr>
                <td>Invoice No</td>
                <td>
                    <span id="invoice_no"><select id="invoice_no"><option >---</option></select> </span>
                    <!--<input type="text" class="input_number" id="invoice_no" name="invoice_no" title="Invoice No" style="width: 100px;" maxlength="25" />
                    <input type="hidden" name="hinv_no" id="hinv_no" title="0" />-->
                    <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Balance &nbsp;&nbsp;<input type="text" class="input_amount" id="balance" readonly="readonly" name="balance" title="Balance" style="width: 100px;" maxlength="25" />-->
                    <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CRN No &nbsp;&nbsp;<input type="text" class="input_number" id="crn_no" name="crn_no" title="CRN No" style="width: 101px;" maxlength="25" />-->                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;"/></td>
            </tr><tr>
                <td>Sales Ref</td>
                <td>
                    <?=$ref;?>
                    <input type="text" class="input_txt" title='Sales Ref' readonly="readonly" id="ref_des"  style="width: 300px;">                </td>
                <td style="width: 100px;">&nbsp;</td>
                <td style="width: 100px;">&nbsp;</td>
            </tr>
            <tr>
                <td>Stores</td>
                <td>
                    <?=$stores;?>
                    <input type="text" class="input_txt" id="sto_des" title="Stores Name" readonly="readonly" style="width: 300px;">                </td>
            </tr>
            
            
            <tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" >Item Code</th>
                                <th class="tb_head_th">Item Name</th>
                                <th class="tb_head_th" >Quantity</th>
                                <th class="tb_head_th" >Amount</th>
                                <!--<th class="tb_head_th" style="width: 80px;">Discount(%)</th>-->
                                <th class="tb_head_th" >Total Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<40; $x++){
                                    //echo "<tr>";
                                    //    echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                    //            <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";
                                    //    echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150'/></td>";
                                    //    echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' /></td>";
                                    //    echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' /></td>";
                                    //    //echo "<td><input type='text' disabled='disabled' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' /></td>";
                                    //    echo "<td id='t_".$x."' style='text-align: right;background-color: #f9f9ec;' class='tf'>&nbsp;</td>";
                                    //echo "</tr>";
                                    
                                    echo "<tr>";
                                         echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                    <input type='text' class='g_input_txt' id='c0_".$x."' name='c0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo' id='c1_".$x."' readonly='readonly' style='width : 300px;'/></td>";
                                        //echo "<td><input type='text' class='g_input_amo' id='c2_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo' id='c3_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo' id='c4_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo pay_amo' id='c5_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <!--<tfoot>
                            <tr style="background-color: transparent;">
                                <td style="padding-left : 7px;">Memo</td>
                                <td colspan="2" style="padding-left : 10px;"><input type="text" class="input_txt" name="memo" id="memo" title="Memo" style="width: 450px; border: 1px dotted #111;" maxlength="255" /></td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Net Amount</td>
                                <td><input type='text' class='g_input_amo' id='total2' readonly="readonly" name='total' style="margin-right: 15px; margin-left: 5px; font-weight: bold;" /></td>
                            </tr><tr style="background-color: transparent;">
                                <td style="padding-left : 7px;">Stores</td>
                                <td colspan="2" style="padding-left : 10px;">
                                    <?=$stores;?>
                                    <input type="text" class="input_txt" id="sto_des" title="Stores Name" style="width: 297px; border: 1px dotted #111;" readonly="readonly" />
                                </td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;"></td>
                                <td></td>
                            </tr><tr style="background-color: transparent;">
                                <td style="padding-left : 7px;"></td>
                                <td colspan="2" style="padding-left : 10px;"></td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;"></td>
                                <td></td>
                            </tr>
                        </tfoot>-->
                    </table>
                    
                     <br>
                    
                                <table style="width: 875px;" id="tgrid1">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" >Item Code</th>
                                            <th class="tb_head_th">Item Name</th>
                                            <th class="tb_head_th">Quantiy</th>
                                            <th class="tb_head_th">Amount</th>
                                            <th class="tb_head_th" style="width: 120px;">Total Amount</th>
                                        </tr>
                                    </thead><tbody>
                                        <?php
                                            //if will change this counter value of 25. then have to change edit model save function.
                                            for($x=0; $x<40; $x++){
                                                echo "<tr>";
                                                    echo "<td><input type='hidden' class='item_h' name='h2_".$x."' id='h2_".$x."' title='0' />
						    <input type='text' class='g_input_txt' id='d0_".$x."' name='d0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d1_".$x."' name='d1_".$x."' readonly='readonly' style='width : 300px;'/></td>";
                                                    //echo "<td><input type='text' class='g_input_amo' id='d2_".$x."' name='d2_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo amo' id='d3_".$x."' name='d3_".$x."'  style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d4_".$x."' name='d4_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='tf' id='d5_".$x."' name='d5_".$x."' style='width : 100%;' />
						    <input type='hidden' id='hc_".$x."' title='0' /></td>";
                                                echo "</tr>";
                                            }
                                        ?>
				
										
                                    </tbody>                        
                                </table>
        </table>
                    <table> <tbody>      
											                            <tr style="background-color: transparent;">
                                <td width="7" style="padding-left : 7px;">&nbsp;</td>
                                <td colspan="4" style="padding-left : 10px;">&nbsp;</td>
                                <td width="828" style="text-align: right; font-weight: bold; font-size: 12px;"><span style="padding-left : 10px;">Total</span></td>
                                <td width="198"><span style="text-align: right; font-weight: bold; font-size: 12px;">
                                  <input type='text' class='g_input_amo' id='total2' readonly="readonly" name='total' style="margin-right: 15px; margin-left: 5px; font-weight: bold;" />
                                </span></td>
                            </tr>
		
		
		 </tbody>      
		</table> 
                    
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnClear" title="Clear" />
                        <input type="button" id="btnReset" title="Reset" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <?php if($this->user_permissions->is_view('027')){ ?>
                        <input type="button"  id="btnSave" title='Save <F8>' />
                        <?php } ?>
       
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php } ?>