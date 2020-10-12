<?php if($this->user_permissions->is_view('024')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    #permission, #root_area{
        z-index: 35;
        position: absolute;
        width: 500px;
        background-color: #FFF;
        padding: 7px;
        top: 180px;
        display: none;
        border: 1px dotted #CCC;
    }
    
    #massage, #massage2{
        font-size: 18px;
        text-align: center;
        font-family: Times;
        color: #da1033;
        font-weight: bold;
        padding-bottom: 7px;
    }
    
    #root_area {
        color: #5270e9;
        width: 350px;
    }
    
    .hid_chq_issue{ display:none;}
</style>

<script type="text/javascript" src="<?=base_url()?>js/t_sales_conform.js"></script>

<?php
if(isset($_GET['id'])){
    echo "<script type='text/javascript'>
        $('document').ready(function(){

           load_data(".$_GET['id'].");

        });
    </script>";
} 
?>  

<?php

?>

<?php

if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
if(! isset($sd['sales_ref'])){ $sd['sales_ref'] = 0; } if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'"; sales_ref = "'.$sd['sales_ref'].'"; storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Cash Sales Conform</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_sales_conform" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 80px;">Customer</td>
                <td>
                    <input type="text" class="input_txt" id="scustomers" title="Customer Search" style="width: 150px;" />
                    <input type="hidden" name="customer" id="customer" title="0" />
                    <input type="text" class="input_txt" title='Customer Name' readonly="readonly" id="cus_des"  style="width: 404px;">
                    <!--<input type="button" title="Set Root/Area" id="btnSetRootArea" />-->
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                    <input type="text" id="hid" name="hid" title="0" />
                    <input type="hidden" id="tab_id" name="tab_id" title="0" />
                </td>
            </tr><tr>
                <td>Balance</td>
                <td>
                    <!--<input type="text" class="input_number" id="so_no" name="so_no" title="000" style="width: 100px;" maxlength="25" />-->
                    <input type="text" class="input_amount" id="balance" readonly="readonly" name="balance" title="Balance" style="width: 150px;" maxlength="25" />
                    &nbsp;&nbsp;&nbsp;
                    <!--RM.(%)--> &nbsp;&nbsp;<input type="hidden" class="input_amount" id="rm" readonly="readonly" name="rm" title="Request Mr." style="width: 80px;" maxlength="25" />
                    &nbsp;&nbsp;&nbsp;
                    <!--CM.(%)--> &nbsp;&nbsp;<input type="hidden" class="input_amount" id="cm" readonly="readonly" name="cm" title="Current Mr." style="width: 80px;" maxlength="25" />
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr><tr>
                <td>Sales Ref</td>
                <td>
                    <?=$ref;?>
                    <input type="text" class="input_txt" title='Sales Ref' readonly="readonly" id="ref_des"  style="width: 404px;">
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;"/></td>
            </tr>
            <tr>
                
                <td>Stores</td>
                <td>  <?=$stores;?>  
                    <input type="text" class="input_txt" id="sto_des" title="Stores Name" style="width: 404px; border: 1px dotted #111;" readonly="readonly" />
                </td>
                
            </tr>
            <tr>
                <td>Pay Method</td>
                <td colspan="3">
                    <input type="radio" name="pay_method" title="1" checked="checked" /> Cash &nbsp;&nbsp;&nbsp;&nbsp;
                    <!--<input type="radio" name="pay_method" title="2" disabled="disabled" /> Credit &nbsp;&nbsp;&nbsp;&nbsp;-->
                    <input type="radio" name="pay_method" title="3" /> Easy Payment  &nbsp;&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 70px;">Item Code</th>
                                <th class="tb_head_th">Item Name</th>
                                <th class="tb_head_th" style="width: 70px;">Quantity</th>
                                <th class="tb_head_th" style="width: 70px;">Amount</th>
                                <th class="tb_head_th" style="width: 70px;">Discount</th>
                                <th class="tb_head_th" style="width: 70px;">Discount(%)</th>
                                <th class="tb_head_th" style="width: 70px;">Free Issue</th>
                                <th class="tb_head_th" style="width: 70px; ">Total Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' class='item_h' name='h_".$x."' id='h_".$x."' title='0' />
                                            <input type='hidden' name='is_ser_".$x."' id='is_ser_".$x."' title='0' readonly='readonly' />
                                                <input type='hidden' name='is_ser_upt_".$x."' id='is_ser_upt_".$x."' title='0' />
                                        <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt' readonly='readonly' id='n_".$x."' name='n_".$x."' style='width: 100%' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' autocomplete='off' readonly='readonly' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' autocomplete='off' readonly='readonly'/>
                                        <input type='hidden' id='hc_".$x."' title='0' /></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' style='text-align: right;' id='3_".$x."' name='3_".$x."' autocomplete='off' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis_pre' style='text-align: right;' id='4_".$x."' name='4_".$x."' autocomplete='off' readonly='readonly'/>
<input type='text' class='g_input_amo q' style='text-align: right;' id='44_".$x."' name='44_".$x."' autocomplete='off' readonly='readonly'/>										
										
										</td>";
                                        echo "<td><input type='text' class='g_input_amo dis_pre' style='text-align: right;' id='5_".$x."' name='5_".$x."' autocomplete='off' readonly='readonly'/></td>";
                                        echo "<td id='t_".$x."' style='text-align: right;background-color: #f9f9ec;' class='tf' readonly='readonly'>&nbsp;</td>";
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
                                <td colspan="4" style="padding-left : 10px;">
                                    
                                    <!--<input type="text" class="input_txt" id="sto_des" title="Stores Name" style="width: 297px; border: 1px dotted #111;" readonly="readonly" />-->
                                </td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Discount</td>
                                <td><input type='text' class='g_input_amo' id='discount' name='discount' style="margin-right: 15px; margin-left: 5px; font-weight: bold; border: 1px dotted #111;" /></td>
                            </tr><tr style="background-color: transparent;">
                                <td style="padding-left : 7px;"></td>
                                <td colspan="4" style="padding-left : 10px;"></td>
                                <td style="text-align: right; font-weight: bold; font-size: 12px;">Net Amount</td>
                                <td><input type='text' class='g_input_amo' id='net_amount' readonly="readonly" name='net_amount' style="margin-right: 15px; margin-left: 5px; font-weight: bold;" /></td>
                            </tr>
                        </tfoot>
                        <div id="payment_methods">
                        
                        </div>
                    </table>
                    
                    
<!--                  grid new-->
                    
                    
<!--                  end of grid  -->



                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <?php if($this->user_permissions->is_view('024')){ ?>
			<input type="button" id="btnPayments" title="Payments" />

                        <input type="button"  id="btnreject" title="Reject" />
                        <input type="button"  id="btnapprove" title="Approve" />
                       
                        <!--<input type="button"  id="btnSave" title='Save <F8>' />-->
                        <?php } ?>
                        
                        <input type="hidden" id="po" name="po" title="0" />
			<input type="hidden" id="response" name="response" title="0" />
			<input type="hidden" id="reject" name="reject" title="0" />
                        
                        
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