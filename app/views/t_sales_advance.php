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
<script type="text/javascript" src="<?=base_url()?>js/t_sales_advance.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_sales_credit/?id='+pid, '_blank');
            window.open('?action=t_sales_credit', '_self');
        }else{
            window.open('?action=t_sales_credit', '_self');
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
    <form method="post" action="<?=base_url()?>index.php/main/save/t_sales_credit" id="form_">
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
            </tr><tr>
                <td>Amount</td>
                <td>
                    <input type="text" class="input_amount" id="advance" name="advance" title="Advance Amount" style="width: 150px;" maxlength="25" />
                    <!--<input type="text" class="input_number" id="so_no" name="so_no" title="Sales Order No" style="width: 100px;" maxlength="25" />-->
                    <!--&nbsp;&nbsp;&nbsp;-->
                    <!--Balance &nbsp;&nbsp;-->
                    <!--<input type="text" class="input_amount" id="balance" readonly="readonly" name="balance" title="Balance" style="width: 100px;" maxlength="25" />-->
                    <!--&nbsp;&nbsp;&nbsp;
                    RM.(%) &nbsp;&nbsp;<input type="text" class="input_amount" id="rm" readonly="readonly" name="rm" title="Request Mr." style="width: 80px;" maxlength="25" />
                    &nbsp;&nbsp;&nbsp;
                    CM.(%) &nbsp;&nbsp;<input type="text" class="input_amount" id="cm" readonly="readonly" name="cm" title="Current Mr." style="width: 80px;" maxlength="25" />-->
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr><tr>
                <td></td>
                <td>
                
                    <!--<input type="text" class="input_txt" title='Sales Ref' readonly="readonly" id="ref_des"  style="width: 300px;">-->
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Reference No" style="width: 100%;"/></td>
            </tr>
            <!--<tr>-->
            <!--    <td>Pay Method</td>-->
            <!--    <td colspan="3">-->
            <!--        <input type="radio" name="pay_method" title="1" /> Cash &nbsp;&nbsp;&nbsp;&nbsp;-->
            <!--        <input type="radio" name="pay_method" title="2" checked="checked" /> Credit &nbsp;&nbsp;&nbsp;&nbsp;-->
            <!--        <input type="radio" name="pay_method" title="3" /> Easy Payment-->
            <!--</td>-->
            <!--</tr>-->
            <tr>
                <div id="payment_methods">
                            
                        </div>
                <td colspan="4" style="text-align: center;">
                    
                    <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <input type="button" id="btnPayments" title="Payments"/>
                        <input type="button"  id="btnSave" title='Save <F8>' />
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