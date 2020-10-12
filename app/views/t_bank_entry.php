<?php if($this->user_permissions->is_view('t_bank_entry')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_bank_entry.js"></script>
<h2 style="text-align: center;">Bank Entry</h2>
<div class="msgBox">
    <div class="msgInner">Saving Success</div>
</div>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_bank_entry" id="form_">
    <table style="width: 100%" border="0">
        <tr><td style="width: 50px;"></td>
            <td style="width: 150px;"></td>
            <td ></td>
            <td style="width: 100px;">No</td>
            <td style="width: 100px;">
                <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                <input type="hidden" id="hid" name="hid" title="0" />                </td>
        </tr>

        <tr><td></td>
            <td></td>
            <td></td>
            <td style="width: 100px;">Date</td>
            <td style="width: 100px;">
                <?php if($this->user_permissions->is_back_date('t_bank_entry')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                <?php } else { ?>
                    <input type="text" class="input_txt" readonly="readonly" style="width:100%; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
                <?php } ?> 
                </td>
        </tr>

        <tr><td></td>
            <td><input name="optConfirm" type="radio" value="CashEntry" id="R1" title="CashEntry" >
                Cash Deposit 
            </td>
            <td></td>
            <td style="width: 100px;">Ref. No</td>
            <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" style="width: 100%; text-align:right;" maxlength="10"/></td>
        </tr>
        <tr><td></td>
            <td><input name="optConfirm" type="radio" value="OtherBankEntry" id="R2" title="OtherBankEntry">
                Other Bank Entry 
            </td>
            <td></td>
            <td style="width: 100px;">Sub No</td>
            <td style="width: 100px;"><input type="text" class="input_txt g_input_amo" name="sub_no" id="sub_no" title="" style="width: 100%;" maxlength="10"/></td>
        </tr>
        <tr><td></td>
            <td></td><td></td>
            <td style="width: 100px;">Entry Code</td>
            <td style="width: 100px;">
                <select id="entry_code" name="entry_code" style="width: 100%;" maxlength="10"><option>  <option></select>
            </td>
        </tr>
        <tr><td></td>
            <td>Credit Acc</td>
            <td><input type="text" class="input_txt" id="scredit_acc" name="scredit_acc" title="" style="width: 150px;" />
                <input type="hidden" name="credit_acc" id="credit_acc" title="0" />
                <input type="text" class="input_txt" title='' readonly="readonly" id="credit_acc_des"  style="width: 300px;"/>
                <!-- input type="text" class="input_txt" id="sjournal_type" title="" style="width: 150px;" />
                <input type="hidden" name="journal_type" id="journal_type" title="0" />
                <input type="text" class="input_txt" title='' readonly="readonly" id="journal_type_des"  style="width: 300px;"/>-->
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr><td></td>
            <td>Debit Acc</td>
            <td><input type="text" class="input_txt" id="sdebit_acc" name="sdebit_acc" title="" style="width: 150px;" />
                <input type="hidden" name="debit_acc" id="debit_acc" title="0" />
                <input type="text" class="input_txt" title='' readonly="readonly" id="debit_acc_des"  style="width: 300px;"/>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr><td></td>
            <td>Description</td>
            <td><input name="description" type="text" class="input_txt" id="description"  style="width: 450px;" title='' maxlength="50" /></td>
            <td></td>
            <td></td>
        </tr>
        <tr><td></td>
            <td>Narration</td>
            <td><input name="narration" type="text" class="input_txt" id="narration"  style="width: 450px;" title='' maxlength="150" /></td>
            <td></td>
            <td></td>
        </tr>
        <tr><td></td>
            <td>Batch</td>
            <td><input name="batch" type="text" class="input_txt" id="batch"  style="width: 140;" title='' maxlength="10" /></td>
            <td></td>
            <td></td>
        </tr>
        <tr><td></td>
            <td>Amount</td>
            <td><input name="amount" type="text" class="input_txt g_input_amo" id="amount"  style="width: 140px;" title='' maxlength="50" /></td>
            <td></td>
            <td></td>
        </tr>

            <td></td>
            <td style="text-align:rignt" colspan="4">
                <input type="hidden" id="code_" name="code_" title="0" />
                <input type="button" id="btnExit" title='Exit' />
                <input type="button" id="btnReset" title='Reset'> 
                <?php if($this->user_permissions->is_delete('t_bank_entry')){ ?><input type="button" id="btnDelete1" title="Delete" /><?php } ?> 
                <?php if($this->user_permissions->is_re_print('t_bank_entry')){ ?><input type="button" id="btnPrint" title='Print' /> <?php } ?>
                <?php if($this->user_permissions->is_add('t_bank_entry')){ ?><input type="button" id="btnSave1" title='Save <F8>' /> <?php } ?>
            </td>
        </tr>
    </table>
    <?php 
    if($this->user_permissions->is_print('t_bank_entry')){ ?>
        <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
    <?php } ?> 
    </form>

    
    <form method="post" action="<?=base_url()?>index.php/reports/generate/t_bank_entry" id="print_pdf" target="_blank"> <!-- target="_blank">
    <!--<form name="print_pdf" id="print_pdf" action="<?=base_url()?>index.php/main/save1/t_bank_entry" method="post" target="_blank">-->
            <input type="hidden" name='by' value='t_bank_entry' title="t_bank_entry" class="report">
            <input type="hidden" name='page' value='A4half' title="A4half" >
            <input type="hidden" name='orientation' value='P' title="p" >
            <input type="hidden" name='type' value='0' title="0" >
            <input type="hidden" name='header' value='false' title="false" >
            <input type="hidden" name='qno' value='' title=5 id="qno" >
            <input type="hidden" name='dd' title="<?= date('Y-m-d') ?>" id="dd" >

    </form>

</div>

<?php } ?>