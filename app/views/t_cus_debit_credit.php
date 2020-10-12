<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    .posting_div{
        background-color: #FFF;
        border: 1px dotted #CCC;
        padding: 7px;
        padding-buttom: 0px;
    }
    
    .heading {
        background-color: #aee8c8;
        margin: 5px;
        border: 2px solid #FFF;
        box-shadow: 0px 0px 5px #AAA;
        text-align: left;
        padding: 7px;
    }
</style>
<script type="text/javascript" src="<?=base_url()?>js/t_cus_debit_credit.js"></script>
<h2 style="text-align: center;">Debit / Credit Note</h2>
<div class="dframe" id="mframe" style="text-align: center; width: 650px;">
    <form action="index.php/main/save/t_cus_debit_credit" method="post" id="_form">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 80px;">Customer</td>
                <td>
                    <input type="text" class="input_txt" id="scustomers" title="Customer Search" style="width: 110px;" />
                    <input type="hidden" name="customer" id="customer" title="0" />
                    <input type="text" class="input_txt" title='Customer Name' readonly="readonly" id="cus_des"  style="width: 202px;">
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
                    <input type="text" class="input_amount" name="amount" id="amount" title="Amount" maxlength="255" />
                    &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="radio" name="type" title="1" checked="checked" /> Debit  &nbsp;&nbsp; &nbsp; &nbsp;
                    <input type="radio" name="type" title="2" /> Credit                  
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr>
        </table>
        <div style="text-align: right; padding: 7px;">
            <input type="button" id="btnExit" title="Exit" />
            <input type="button" id="btnReset" title="Reset" />
            <input type="button" id="btnDelete" title="Delete" />
            <input type="button" id="btnSave" title='Save <F8>' />
            <input type="button" id="btnPrint" title="Print" />
        </div>
    </form>
</div>