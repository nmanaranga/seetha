<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    #permission{
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
    
   /* #root_area {
        color: #5270e9;
        width: 350px;
    }*/
</style>
<script type="text/javascript" src="<?=base_url()?>js/t_ad_refund.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<!--<script type="text/javascript">-->
<!--    $(document).ready(function(){-->
<!--        if(confirm("Do you need get print?")){-->
<!--            window.open('index.php/prints/trance_forms/t_ad_refund/?id='+pid, '_blank');-->
<!--            window.open('?action=t_sales', '_self');-->
<!--        }else{-->
<!--            window.open('?action=t_sales', '_self');-->
<!--        }-->
<!--    });-->
<!--</script>-->
<?php
}

?>
<h2 style="text-align: center;">Adavnce Refund</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_ad_refund" id="form_">
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
                <td>Advance No</td>
                <td>
                    <span id="advance"><select id="ad" name="advance"><option >---</option></select> </span>
                    <!--<input type="text" class="input_txt" title='Sales Ref' readonly="readonly" id="ref_des"  style="width: 404px;">-->
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr>
            <tr>
                <td>Advance</td>
                <td>
                    <input type="text" class="input_txt" id="amount" name="amount" title="Amount" style="width: 150px;" />
                    Advance
                    Balance
                    <input type="text" class="input_txt" id="ad_balance" name="ad_balance" title="Balance" style="width: 150px;" />
                </td>
                
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;"/></td>
            </tr>
            <tr>
			

			
			<table style="width: 100%;" id="tgrid2">
                    <thead>
					
										    <fieldset style='background-color: #f9f9f9; padding: 7px;'>
		<legend>Payment Method</legend>
                Cash  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" name="cash_amount" id="cash_amount" title="0" class="input_amount" style="width: 150px;"/>
		&nbsp;&nbsp;
                Cheque  <input type="text" name="cheque" id="cheque" title="0" class="input_amount" readonly='readonly' style="width: 150px;"/>
		&nbsp;&nbsp;Total <input type="text" name="total" id="total" title="Total" class="input_amount" readonly='readonly' style="width: 150px;"/>
                
	    </fieldset>
					
                        <tr>
                           
                            <th class="tb_head_th">Bank</th>
                            <th class="tb_head_th" style="width: 80px;">Cheque No</th>
                            <th class="tb_head_th" style="width: 80px;">Account No</th>
                            <th class="tb_head_th" style="width: 80px;">R. Date</th>
                            <th class="tb_head_th" style="width: 80px;">Amount</th>
                        </tr>
                    </thead><tbody>
                        <?php
                            //if will change this counter value of 10. then have to change edit model save function.
                            for($x=0; $x<10; $x++){
                                echo "<tr>";
                                    echo "<td><input type='hidden' name='qbbh1_".$x."' id='qbbh1_".$x."' title='0' />
						<input type='text' class='g_input_txt branch1'  id='qn1_".$x."' name='qn1_".$x."' style='width : 100%;' /></td>";
                                    echo "<td><input type='text' class='g_input_txt cheque_no' id='q11_".$x."' name='q11_".$x."' style='width : 100%;'  /></td>";
                                    echo "<td><input type='text' class='g_input_txt account_no' id='q21_".$x."' name='q21_".$x."' style='width : 100%;'  /></td>";
                                    echo "<td><input type='text' class='input_date_down_future' style='border : none; background-color : transparent;' readonly='readonly' id='q41_".$x."' name='q41_".$x."' title='' /></td>";
                                    echo "<td><input type='text' class='g_input_amo ttt' id='q31_".$x."' name='q31_".$x."' style='width : 100%;'  /></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
		</table>
			
			
			</tr>
            
            <tr>
                <td colspan="4" style="text-align: center;">
                   
				   
				   
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <!--<input type="button" id="btnPrint" title="Print" />-->
<!--			<input type="button" id="btnPrint2" title="Deliver Note" />-->
<!--                        <input type="button" id="btnPayments" title="Payments" />-->
                        <input type="button" id="btnSave" title='Save <F8>' />
                    </div>
                </td>
            </tr>
        </table>
    </form>
    
   
</div>