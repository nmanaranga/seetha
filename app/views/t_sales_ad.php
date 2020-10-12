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
</style>
<script type="text/javascript" src="<?=base_url()?>js/t_sales_ad.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_sales_ad/?id='+pid, '_blank');
            window.open('?action=t_sales_ad', '_self');
        }else{
            window.open('?action=t_sales_ad', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
if(! isset($sd['sales_ref'])){ $sd['sales_ref'] = 0; } if(! isset($sd['stores'])){ $sd['stores'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'"; sales_ref = "'.$sd['sales_ref'].'"; storse = "'.$sd['stores'].'";</script>';
?>
<h2 style="text-align: center;">Sales Advance</h2>
<div id="permission">
    <div id="massage">
        You Have To Get Permission From <span id="perid"></span> User.
    </div>
    <div id="request_body">
        <fieldset style="height: 200px; overflow: auto;">
            <legend>Online Users</legend>
            <span id="online_users">Loding...</span>
        </fieldset>
    </div>
    <div style="text-align: right; padding-top: 7px;">
        <button id="btnRequest">Request</button>
        <button id="btnRefresh">Refresh</button>
        <button id="btnCancel">Cancel</button>
        <button id="btnCloseRequest">Close</button>
    </div>
</div>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_sales_ad" id="form_">
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
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr>
            <tr>
                <td>Ref. No</td>
                <td>
                    <input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 150px;"/>
                    
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr>
            <!--<tr>-->
            <!--    <td></td>-->
            <!--    <td>-->
            <!--    -->
            <!--        -->
            <!--    </td>-->
            <!--    <td style="width: 100px;">Ref. No</td>-->
            <!--    <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;"/></td>-->
            <!--</tr>-->
            
            <tr>
                <!--<div id="payment_methods">-->
                <!--            -->
                <!--        </div>-->
                <td colspan="4" style="text-align: center;">
                    
                    <!--<div style="text-align: right; padding-top: 7px;">-->
                    <!--    <input type="button" id="btnExit" title="Exit" />-->
                    <!--    <input type="button" id="btnReset" title="Cancel" />-->
                    <!--    <input type="button" id="btnDelete" title="Delete" />-->
                    <!--    <input type="button" id="btnPrint" title="Print" />-->
                    <!--    <input type="button" id="btnPayments" title="Payments"/>-->
                    <!--    <input type="button"  id="btnSave" title='Save <F8>' />-->
                    <!--</div>-->
                </td>
            </tr>
        </table>
        
        <!--<div id="pay_form" style="display: none;">-->
	    <fieldset style='background-color: #f9f9f9; padding: 7px;'>
		<legend>Payment Method</legend>
                Cash  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" name="cash" id="cash" title="0" class="input_amount" style="width: 150px;"/>
		&nbsp;&nbsp;
                Cheque  <input type="text" name="cheque" id="cheque" title="0" class="input_amount" readonly='readonly' style="width: 150px;"/>
		&nbsp;&nbsp;Total <input type="text" name="total" id="total" title="Total" class="input_amount" readonly='readonly' style="width: 150px;"/>
                
	    </fieldset>
	    <fieldset>
		<legend>Cheque Details</legend>
		<table style="width: 100%;" id="tgrid2">
                    <thead>
                        <tr>
                           <th class="tb_head_th">Bank</th>
                            <th class="tb_head_th" style="width: 180px;">Bank Branch</th>
                            <th class="tb_head_th" style="width: 80px;">Cheque No</th>
                            <th class="tb_head_th" style="width: 80px;">Acount No</th>
                            <th class="tb_head_th" style="width: 80px;">R. Date</th>
                            <th class="tb_head_th" style="width: 80px;">Amount</th>
                        </tr>
                    </thead><tbody>
                        <?php
                            //if will change this counter value of 10. then have to change edit model save function.
                            for($x=0; $x<10; $x++){
                                echo "<tr>";
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
		</table>
	    </fieldset>
	<!--    <div style="text-align: right; padding-top: 7px;">-->
	<!--	<input type="button" title="Close" id="btnClosePay" />-->
	<!--	<input type="button" title='Save <F8>' id="btnSavePay" />-->
	<!--    </div>-->
        
                <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <!--<input type="button" id="btnPayments" title="Payments"/>-->
                        <input type="button"  id="btnSave1" title='Save <F8>' />
                </div>
	<!--</div>-->
        
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