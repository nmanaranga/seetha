<?php if($this->user_permissions->is_view('t_account_receipt')){ ?>
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
<script type="text/javascript" src="<?=base_url()?>js/t_account_receipt.js"></script>
<?php
if(isset($_GET['print'])){
    echo "<script type='text/javascript'>var pid = ".$_GET['print'].";</script>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        if(confirm("Do you need get print?")){
            window.open('index.php/prints/trance_forms/t_customer_receipt/?id='+pid, '_blank');
            window.open('?action=t_customer_receipt', '_self');
        }else{
            window.open('?action=t_customer_receipt', '_self');
        }
    });
</script>
<?php
}
if(! isset($sd['route'])){ $sd['route'] = 0; } if(! isset($sd['area'])){ $sd['area'] = 0; }
echo '<script type="text/javascript"> sroot = "'.$sd['route'].'"; sarea = "'.$sd['area'].'";</script>';
?>
<style>

.hid_chq_issue{ display:none;}
</style>
<h2 style="text-align: center;">Receipt</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_account_receipt" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Customer</td>
                <td><input name="text" type="text" class="input_txt" id="saccount" style="width: 150px;" title="" />
                  <input type="hidden" name="account" id="account" title="0" />
                  <input name="text" type="text" class="input_txt" id="account_des"  style="width: 300px;" title='' readonly="readonly"/>     </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" style="width: 100%;" name="id" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />  
                    <input type="hidden" id="payable_grid" name="payable_grid" title="0" />
                </td>
            </tr><tr>
                <td>Description</td>
                <td><input name="description" type="text" class="input_txt" id="description"  style="width: 450px;" title='' maxlength="50"/></td>
                <td style="width: 100px;">Date</td>
                <td style="width: 150px;">
                    <?php if($this->user_permissions->is_back_date('t_account_receipt')){ ?>
                        <input type="text" style="width: 150px; text-align:right;" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } else { ?>  
                        <input type="text" style="width: 150px; text-align:right;" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />  
                    <?php } ?>
                </td>
            </tr><tr>
                <td>Narration</td>
                <td><input name="narration" type="text" class="input_txt" id="narration"  style="width: 450px;" title='' maxlength="50" /></td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px; "><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;" maxlength="100"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">

                    <table width="945" id="tgrid" style="width: 875px;">
                        <thead>
                            <tr>
                              <th width="100" class="tb_head_th">Receivable Invoice No </th>
                                <th width="100" class="tb_head_th" style="width: 100px;">Last Paid Date </th>
                                <th width="100" class="tb_head_th">Paid Amount</th>
                                <th width="100" class="tb_head_th">Balance to pay </th>
                                <th width="100" class="tb_head_th">Paid</th>                                
                               
                            </tr>
                        </thead><tbody id="table_data">
                        </tbody>                        
                    </table>

                    <table style="width: 100%;">
                        <tr>
                            <td width="31"></td>
                            <td width="31">                            </td>
                            <td width="1001" style="text-align: right;">Pay Total<span style="width: 140px;">
                              <input type="text" class="g_input_amo" readonly="readonly" id="total" name="total" title="" style="width: 165px;margin-left:80" />
                          </span></td>
                            <td width="20" style="text-align: right;">&nbsp;</td>
                        </tr>
                    </table>
                    <div id="payment_methods">                    </div>
                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <?php if($this->user_permissions->is_delete('t_account_receipt')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_account_receipt')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                        <input type="button" id="btnPayments" title="Payments" />                        
                        <?php if($this->user_permissions->is_add('t_account_receipt')){ ?><input type="hidden"  id="btnSave" title='Save <F8>' /><?php } ?>
                
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
<?php } ?>