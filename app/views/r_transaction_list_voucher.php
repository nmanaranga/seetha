<?php if($this->user_permissions->is_view('r_transaction_list_voucher')){ ?>
<h2 style="text-align: center;">Transaction Reports - Voucher </h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type='text/javascript' src='<?=base_url()?>js/r_transaction_list_voucher.js'></script>

<div class="dframe" id="r_view2" style="width: 1100px;">
    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

        <fieldset>

            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">
                <tr>
                    <td style="width:180px; padding-left:10px; padding-right:10px;">From</td>
                    <td style="width:110px;">
                        <input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" />

                    </td>
                    <td style="width:40px;"> To </td>
                    <td>
                        <input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  /> &nbsp; &nbsp;
                        <input type="checkbox" name="chkdate1" id="chkdate1" value="1" checked>
                    </td>
                </tr>

                <tr> 
                    <td style="padding-left:10px; padding-right:10px;width:180px;">Trans Number Range</td>
                    <td>
                        <input type="text" class="g_input_num input_txt_f" id="t_range_from" name="t_range_from" style="width:80px;" disabled/> 
                        
                    </td>
                    <td> To </td>
                    <td> 
                        <input type="text" class="g_input_num input_txt_f" id="t_range_to" name="t_range_to" style="width:81px;" disabled/>&nbsp; &nbsp;
                        <input type="checkbox" name="chknumRange" id="chknumRange" value="1" > 
                    </td>

                </tr>
            </table>
        </fieldset>    

        <fieldset>

            <div id="report_view" style="overflow: auto;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">

                    <tr>
                        <td style="padding-left:10px; padding-right:10px; width:83px;">Cluster</td>
                        <td><?php echo $cluster; ?></td>
                    </tr>
                    <tr>
                        <td style="padding-left:10px; padding-right:10px; width:83px;">Branch</td>
                        <td> <?php echo $branch; ?> </td>

                    </tr>
                    <input type="hidden" id="d_cl" title='<?php echo $d_cl ?>' name="d_cl"/>
                    <input type="hidden" id="d_bc" title='<?php echo $d_bc ?>' name="d_bc"/>

                    <tr>    
                        <td style="width:83px; padding-left:10px; padding-right:10px;">Account Code</td>

                        <td style="width:450px;"><input type="text" class="input_txt" title="" id="acc_code" name="acc_code" />
                            <input type="text" class="hid_value"  readonly="readonly" id="acc_code_des" name="acc_code_des" style="width: 250px;">
                        </td>
                    </tr>
                    <tr>    
                        <td style="width:83px; padding-left:10px; padding-right:10px;">Debit Note</td>
                        <td style="width:450px;"><input type="text" class="input_txt" title="" id="debit_note" name="debit_note" /></td>
                    </tr>
                    <tr><td colspan="3"><br/><hr> </td></tr>

                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_voucher_lists' title="r_voucher_lists" class="report" />Voucher List 01 (supplier Payment)
                        </td>               
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_voucher_lists_2' title="r_voucher_lists_2" class="report" />Voucher List 02 (supplier Payment)
                        </td>               
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_cancelled_voucher_lists' title="r_cancelled_voucher_lists" class="report" />Cancelled Voucher List (supplier Payment)
                        </td>               
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_cancelled_voucher_lists_2' title="r_cancelled_voucher_lists_2" class="report" />Cancelled Voucher List 02(supplier Payment)
                        </td>               
                    </tr>
                    <?php //} ?>
                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_petty_cash' title="r_petty_cash" class="report" />Petty Cash Summery
                        </td>               
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_cancelled_petty_cash' title="r_cancelled_petty_cash" class="report" />Cancelled Petty Cash Summery
                        </td>               
                    </tr>
                    <?php //} ?>
                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_petty_cash_details' title="r_petty_cash_details" class="report" />Petty Cash Details
                        </td>               
                    </tr>
                    <?php //} ?>

                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_general_voucher_summery' title="r_general_voucher_summery" class="report" />General Voucher List (Summery)
                        </td>               
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_cancelled_general_voucher_summery' title="r_cancelled_general_voucher_summery" class="report" />Cancelled General Voucher List (Summery)
                        </td>               
                    </tr>
                    <?php //} ?>

                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_general_voucher_details' title="r_general_voucher_details" class="report" />General Voucher List (Details)
                        </td>               
                    </tr>
                    <?php //} ?>


                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_payable_invoice_summery' title="r_payable_invoice_summery" class="report" />Payable Invoice (Summery)
                        </td>               
                    </tr>
                    <?php //} ?>

                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_payable_invoice_details' title="r_payable_invoice_details" class="report" />Payable Invoice (Details)
                        </td>               
                    </tr>
                    <?php //} ?>

                    <tr>
                        <td colspan="2">
                            <input type='radio' name='by' value='r_sup_settlement_bal' title="r_sup_settlement_bal" class="report" id="settlement_bal" />Supplier Settlement Balance Details
                        </td>               
                    </tr>


                </table>
            </div>
            <div style="text-align: right; padding-top: 7px;">


                <button id="btnReset">Reset</button> 
                <input type="hidden" name="type" id="type"  title=""/>
                <input type="button" title="Exit" id="btnExit" value="Exit">
                <button id="btnPrint">Print PDF</button>

            </fieldset>

            <!-- <input type="hidden" id='by' name='by' value='' title="" class="report"> -->
            <input type="hidden" name='page' value='A4' title="A4" >
            <input type="hidden" name='orientation' value='P' title="P" >
            <input type="hidden" id='type' name='type' value='' title="" >
            <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >


        </form>
    </div>

    <?php } ?>