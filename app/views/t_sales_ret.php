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
<script type="text/javascript" src="<?=base_url()?>js/t_sales_ret.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_sales_ret/?id='+pid, '_blank');
            window.open('?action=t_sales_ret', '_self');
        }else{
            window.open('?action=t_sales_ret', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'";</script>';
?>
<h2 style="text-align: center;">Sales Return</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_sales_ret" id="form_">
        <table style="width: 100%" border="0">
            <tr>
              <td style="width: 100px;">Sales</td>
              <td><select name="sale" id="sale">
                <option value="0">Cash sales</option>
                <option value="1">Credit Sales</option>
              </select>
             Invoice No <input name="inv_no" type="text" class="input_txt" id="inv_no" style="width: 150px; text-align:right" title="Invoice Number" /></td>
              <td style="width: 50px;">No</td>
              <td><input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                <input type="hidden" id="hid" name="hid" title="0" /></td>
            </tr>
            <tr>
              <td style="width: 100px;">Customer</td>
              <td><input name="text2" type="text" class="input_txt" id="scustomers" style="width: 150px;" title="Customer Search" />
                <input type="hidden" name="customer" id="customer" title="0" />
                <input name="text2" type="text" class="input_txt" id="cus_des"  style="width: 300px;" title='Customer Name' readonly="readonly" /></td>
              <td style="width: 50px;"><span style="width: 100px;">Date</span></td>
              <td><span style="width: 100px;">
                <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
              </span></td>
            </tr>
            <tr>
              <td style="width: 100px;">Address</td>
              <td><input type="text" class="input_txt" name="Address" id="address" title="Memo" style="width: 453px;" maxlength="255" /></td>
              <td style="width: 50px;"><span style="width: 100px;">Ref. no </span></td>
              <td><input type="text" class="input_active_num" name="ref_no" id="ref_no" title="ref_no" /></td>
            </tr>
            <tr>
              <td style="width: 100px;">Reason</td>
              <td><input type="text" class="input_txt" name="memo2" id="reason" title="Reason" style="width: 453px;" maxlength="255" /></td>
              <td style="width: 50px;">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 100px;">Store</td>
                <td>              <?=$stores;?>
                    <input type="text" class="input_txt" id="sto_des" title="Stores Name" readonly="readonly" style="width: 300px;"></td>
                <td style="width: 50px;">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
<tr>
                <td colspan="4" style="text-align: center;">
        
                     
                                     <table style="width: 98%;" id="tgrid">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width: 70px;">Code</th>
                                            <th class="tb_head_th">Name</th>
                                            <th class="tb_head_th">foc</th>
                                            <th class="tb_head_th">Qty</th>
                                            <th class="tb_head_th">Rate</th>
                                            <th class="tb_head_th">Amount</th>
                                        </tr>
                                    </thead><tbody>
                                       <?php
                                            //if will change this counter value of 25. then have to change edit model save function.
                                            for($x=0; $x<25; $x++){
                                                echo "<tr>";
                                                    echo "<td><input type='text' class='g_input_txt fo' id='c0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c1_".$x."' readonly='readonly' style='width : 300px;'/></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c2_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c3_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='c4_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo pay_amo' id='c5_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>                        
                                </table>
                    
                    <br>
                    
                                <table style="width: 100%;" id="tgrid1">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width: 70px;">Code</th>
                                            <th class="tb_head_th">Name</th>
                                            <th class="tb_head_th">foc</th>
                                            <th class="tb_head_th">Qty</th>
                                            <th class="tb_head_th">Rate</th>
                                            <th class="tb_head_th">Amount</th>
                                        </tr>
                                    </thead><tbody>
                                        <?php
                                            //if will change this counter value of 25. then have to change edit model save function.
                                            for($x=0; $x<25; $x++){
                                                echo "<tr>";
                                                    echo "<td><input type='text' class='g_input_txt fo' id='d0_".$x."' name='d0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d1_".$x."' name='d1_".$x."' readonly='readonly' style='width : 300px;'/></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d2_".$x."' name='d2_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d3_".$x."' name='d3_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo' id='d4_".$x."' name='d4_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                    echo "<td><input type='text' class='g_input_amo set_amo' id='d5_".$x."' name='d5_".$x."' style='width : 100%;' /></td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>                        
                                </table>
                    </table>
                    
                   <!-- <div id="payment_methods">
                        
                    </div>-->
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
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <!--<input type="button" id="btnPrint" title="Print" />-->
                        <!--<input type="button" id="btnPayments" title="Payments" />-->
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