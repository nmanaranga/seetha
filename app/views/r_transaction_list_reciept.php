<?php if($this->user_permissions->is_view('r_transaction_list_reciept')){ ?>
<h2 style="text-align: center;">Transaction Reports - Reciept</h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type='text/javascript' src='<?=base_url()?>js/r_transaction_list_reciept.js'></script>


<div class="dframe" id="r_view2" style="width: 1100px;">
    <form id="print_pdf" class="printExcel" action="<?php echo site_url(); ?>" method="post" target="_blank">

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
                        <tr id="account_c">
                            <td style="width:83px; padding-left:10px;">Account Code</td>

                            <td style="width:650px;"><input type="text" class="input_txt" title="" id="acc_code" name="acc_code" />
                                <input type="text" class="hid_value"  readonly="readonly" name='acc_code_des' id="acc_code_des"  style="width: 250px;">
                                <input type="checkbox" id="BraCuAcc">&nbsp;&nbsp;Branch Current Account
                            </td>

                        </tr>
                        <tr> <td colspan="5"><hr/><td> </tr>
                            <tr>
                                <!--<?php// if($this->user_permissions->is_view('r_advanced_payment_lists')){ ?>-->
                                <tr>
                                    <td colspan="2">
                                        <input type='radio' name='by' value='r_advanced_payment_lists' title="r_advanced_payment_lists" class="report" />Advanced Payment List
                                    </tr>
                                    <!--<?php //} ?>-->

                                    <!--<?php// if($this->user_permissions->is_view('r_advanced_payment_lists')){ ?>-->
                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' value='r_cancelled_advanced_payment_lists' title="r_cancelled_advanced_payment_lists" class="report" />Cancelled Advanced Payment List
                                        </tr>
                                        <!--<?php //} ?>-->
                                    </tr>
                                    <?php if($this->user_permissions->is_view('r_receipt_lists')){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' excel='true' value='r_receipt_lists' title="r_receipt_lists" class="report" />Receipt List (Customer Payment)
                                        </td>               
                                    </tr>
                                    <?php } ?>

                                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' value='r_general_recipt_summery' title="r_general_recipt_summery" class="report" />General Reciept List (Summery)
                                        </td>               
                                    </tr>
                                    <?php //} ?>

                                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' value='r_general_recipt_Details' title="r_general_recipt_Details" class="report" />General Reciept List (Details)
                                        </td>               
                                    </tr>
                                    <?php //} ?>

                                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' value='r_recievalble_invoice_summery' title="r_recievalble_invoice_summery" class="report" />Recievalble Invoice (Summery)
                                        </td>               
                                    </tr>
                                    <?php //} ?>

                                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' value='r_recievable_invoice_details' title="r_recievable_invoice_details" class="report" />Recievalble Invoice (Details)
                                        </td>               
                                    </tr>
                                    <?php //} ?>

                                    <?php //if($this->user_permissions->is_view('r_voucher_lists')){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' value='r_cheque_in_hand' title="r_cheque_in_hand" class="report" />Cheque in Hand
                                        </td>               
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <input type='radio' name='by' value='r_cheque_ack_list' title="r_cheque_ack_list" class="report" />Cheque Acknowledgement
                                        </td>               
                                    </tr>

                                    <?php //} ?>

                                    <tr> <td colspan="5"><hr/><td> </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type='radio' name='by' value='r_transfer_rece_outstanding' excel='true' title="r_transfer_rece_outstanding" class="report" />Transfer Receipt Outstanding
                                            </td>               
                                        </tr>
                                    </table>
                                </div>
                                <div style="text-align: right; padding-top: 7px;">


                                    <button id="btnReset">Reset</button> 
                                    <input type="hidden" name="type" id="type"  title=""/>
                                    <input type="button" title="Exit" id="btnExit" value="Exit">
                                    <button id="btnPrint">Print PDF</button>
                                    <input type="button" id="printExcel" title="Excel" disabled="true" />
                                </fieldset>




                                <!--   <input type="hidden" id='by' name='by' value='' title="" class="report"> -->
                                <input type="hidden" name='page' value='A4' title="A4" >
                                <input type="hidden" name='orientation' value='P' title="P" >
                                <input type="hidden" id='type' name='type' value='' title="" >
                                <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >


                            </form>
                        </div>

                        <?php } ?>