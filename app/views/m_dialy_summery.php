<?php if($this->user_permissions->is_view('m_dialy_summery')){ ?>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
    <script type="text/javascript" src="<?=base_url()?>js/m_dialy_summery.js?<?= rand() ?>"></script>

    <style type="text/css">

    table td{
        border-bottom:1px dotted #666;
    }

    [type=text] {        
        text-align: right;
        margin:2px;
    }

    .regulat_txt{
        text-align: left;
    }

</style>


<div id="testLoad"></div>
<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Daily Summery</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/m_dialy_summery" id="form_">

        <table border=0 width="100%" cellpadding="0" cellspacing="0">
            <tr>                
                <td>&nbsp;</td>
                <td width="50">No</td>
                <td width="50"><input type="text" name="id" id="id" class="input_active g_input_num" title="<?=$id?>">
                    <input type="hidden" id="hid" name="hid" title="0" /></td>
                </tr>
                <tr>                
                    <td>&nbsp;</td>
                    <td width="50">Date</td>
                    <?php if($this->user_permissions->is_back_date('m_dialy_summery')){ ?>
                        <td width="50"><input type="text" class="input_active" readonly="readonly" name="ddate" id="ddate" title="<?=$max_date?>" /></td>
                    <?php } else { ?>
                        <td width="50"><input type="text" class="input_active" readonly="readonly" name="ddate" id="ddate" title="<?=$max_date?>" /></td>
                    <?php } ?> 

                </tr>
            </table>

            <table border=0 width="100%">
                <tr>                
                    <td width="100">Cluster Name</td>
                    <td class=""><input type="text" name="cl_name"  readonly="readonly" style="width:300px; text-align:left;" id="cl_name" title="<?=$bc_name?>" class="input_txt g_col_fixed"></td>                
                </tr>
                <tr>                
                    <td width="100">Branch Name</td>
                    <td><input type="text" name="bc_name" readonly="readonly" id="bc_name" style="width:300px; text-align:left;" title="<?=$cl_name?>" class="input_txt g_col_fixed"></td>                
                </tr>
                <tr>                
                    <td width="100">Cash in Hand Account</td>
                    <td><input type="text" name="acc_code" id="acc_code" readonly="readonly" class="input_txt g_col_fixed" title="<?=$acc_code?>"></td>                
                </tr>
            </table> <br>

            <table border=0 width="100%" cellpadding="0" cellspacing="0">
                <tr>                
                    <td width="15">a)</td>
                    <td colspan="4">Cash in Hand Opening Balance as per the Ledger</td>                
                    <td><input type="text" class="input_active g_input_amo g_col_fixed" readonly="readonly" name="opb" id="opb" title="<?=$opb?>"></td>
                </tr>


                <tr>                
                    <td valign="top"></td>
                    <td valign="top"></td>
                    <td colspan="3" width="80">



                    </td>
                    <td valign="bottom"></td>                
                </tr>


                <tr>                
                    <td>b)</td>
                    <td width="80">Less</td>
                    <td width="150">Cash In Hand Float</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type="text" name="cash_f" id="cash_f"  title="<?=$cash_f?>" readonly="readonly" class="input_active g_input_amo g_col_fixed"></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type="text" name="opt_bal" id="opt_bal" title="" readonly="readonly" class="input_active g_input_amo g_col_fixed"></td>
                </tr>

                <tr>                
                    <td>c)</td>
                    <td width="80">Less</td>
                    <td width="150">Bank Entries</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>                
                    <td>&nbsp;</td>
                    <td colspan="4" width="80" style="padding-left:79px;">

                        <table border="0"  cellpadding="0" cellspacing="0">
                            <tr>                      
                                <td>Bank Entry No</td>
                                <td>Bank A/c No</td>
                                <td>Amount</td>
                            </tr>
                            <tbody id="bank_entries"></tbody>                        
                        </table>

                    </td>
                    <td valign="bottom"><input type="text" name="bank_tot" id="bank_tot" title="" readonly="readonly" class="input_active g_input_amo g_col_fixed"></td>                
                </tr>

                <tr>                
                    <td>d)</td>
                    <td width="80">Add</td>
                    <td width="150">Sales</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td width="80"></td>
                    <td width="150">Cash Sales</td>
                    <td>&nbsp;</td>
                    <td align="right"><input type="text" name="c_sales" id="c_sales" readonly="readonly" title="<?=$cash_only?>" class="input_active g_input_amo g_col_fixed"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td width="80"></td>
                    <td width="150">Gift Voucher Sales</td>
                    <td>&nbsp;</td>
                    <td align="right"><input type="text" name="g_sales" id="g_sales" readonly="readonly" title="<?=$gift_only?>" class="input_active g_input_amo g_col_fixed"></td>
                    <td></td>
                </tr>


                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">System</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type="text" class="input_active g_input_num g_col_fixed" readonly="readonly" name="cash" id="cash" title="<?=$cash?>"></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Manual</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type="text" name="cash_m" id="cash_m" class="input_active g_input_amo " title="0.00"></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type="text" name="cash_tot" id="cash_tot" title="" readonly="readonly" class="input_active g_input_amo g_col_fixed" ></td>
                </tr>




                <tr>                
                    <td>e)</td>
                    <td width="80">Add</td>
                    <td width="150">Receipts</td>
                    <td>&nbsp;</td>
                    <td align="right"><input type="text" name="receipt" id="receipt" readonly="readonly" title="<?=$receipt?>" class="input_active g_input_amo g_col_fixed"></td>
                    <td></td>
                </tr>


                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Transport</td>
                    <td>&nbsp;</td>                
                    <td align="right"><input type="text" name="receipt_t" id="receipt_t" title="0.00" readonly="readonly" class="input_active g_input_amo g_col_fixed"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Advances</td>
                    <td>&nbsp;</td>                
                    <td align="right"><input type="text" name="receipt_a" id="receipt_a" title="<?=$advance_amount?>" readonly="readonly" class="input_active g_input_amo g_col_fixed"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Others</td>
                    <td>&nbsp;</td>                
                    <td align="right"><input type="text" name="receipt_o" id="receipt_o" title="0.00" class="input_active g_input_amo "></td>

                    <td><input type="text" name="receipt_tot" id="receipt_tot" title="<?=$receipt?>" readonly="readonly" class="input_active g_input_amo g_col_fixed"></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">&nbsp;</td>
                    <td>&nbsp;</td>                
                    <td align="right">&nbsp;</td>

                    <td><input type="text" name="receipt_f_tot" id="receipt_f_tot" title="0.00" readonly="readonly" class="input_active g_input_amo g_col_fixed"></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><!--< input type="text" name="" id="" title="27,340.00"> --></td>
                </tr>


                <tr>                
                    <td>f)</td>
                    <td width="80">Less</td>
                    <td width="150">Payments and Reversals</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Receipt Cancellation</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="receipt_cancel" id="receipt_cancel" class="input_active g_input_amo g_col_fixed" title="<?=$receipt_cancel?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Debit Note</td>
                    <td></td>                
                    <td align="right"><input type="text" name="cash_voucher" readonly="readonly" id="cash_voucher" class="input_active g_input_amo g_col_fixed" title="<?=$cash_voucher?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">System Receipts raised against the Manual Receipts</td>
                    <td></td>                
                    <td valign="bottom"  align="right"><input type="text" name="receipt_manual" id="receipt_manual" title="0.00" class="input_active g_input_amo "></td>

                    <td valign="bottom"><input type="text" name="payment_tot" id="payment_tot" readonly="readonly" title="<? echo $receipt_cancel+$cash_voucher ?>" class="input_active g_input_amo g_col_fixed"></td>
                </tr>         


                <tr>                
                    <td width="15">g)</td>
                    <td colspan="4">Cash in Hand Closing Balance as per the Ledger</td>                
                    <td><input type="text" name="close_bal" id="close_bal" class="input_active g_input_amo g_col_fixed" title="0.00"></td>
                </tr>





                <tr>                
                    <td valign="top">h)</td>
                    <td valign="top"></td>
                    <td colspan="3" width="80">

                        <table border="0"  cellpadding="0" cellspacing="0" width="300">
                            <tr>
                                <td>5000</td>
                                <td> <input type="text" name="5000_" id="5000_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="5000_tot" id="5000_tot" readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>2000</td>
                                <td> <input type="text" name="2000_" id="2000_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="2000_tot" id="2000_tot" readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>1000</td>
                                <td> <input type="text" name="1000_" id="1000_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="1000_tot" id="1000_tot" readonly="readonly"   class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>500</td>
                                <td> <input type="text" name="500_" id="500_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="500_tot" id="500_tot" readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>100</td>
                                <td> <input type="text" name="100_" id="100_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="100_tot" id="100_tot" readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>50</td>
                                <td> <input type="text" name="50_" id="50_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="50_tot" id="50_tot"  readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td> <input type="text" name="20_" id="20_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="20_tot" id="20_tot" readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td> <input type="text" name="10_" id="10_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="10_tot" id="10_tot" readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>
                            <tr>
                                <td>Coins</td>
                                <td> <input type="text" name="coints_" id="coints_"  class="input_active g_input_num"  title="0"></td>
                                <td></td>
                                <td><input type="text" name="coints_tot" id="coints_tot" readonly="readonly"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                            </tr>                        
                        </table>

                    </td>
                    <td valign="bottom">&nbsp;</td>                
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan=3>Total</td>   
                    <td><input type="text" name="h_" id="h_"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>             

                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan=3>Excess / Shortage</td>                
                    <td><input type="text" name="ttt" id="ttt"  class="input_active g_input_amo g_col_fixed"  title="0.00"></td>
                </tr>

                <tr>                
                    <td>i)</td>
                    <td width="80">Sales</td>
                    <td width="150">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>


                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Cash Sale</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="i_cash_sale" id="i_cash_sale" class="input_active g_input_amo g_col_fixed" title="<?=$cash?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Credit Sale</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="i_credit_sale" id="i_credit_sale" class="input_active g_input_amo g_col_fixed" title="<?=$credit?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Finance Companies</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="finance" id="finance" class="input_active g_input_amo g_col_fixed" title="<?=$finance?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Credit Card</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="ccard_sales" id="ccard_sales" class="input_active g_input_amo g_col_fixed" title="<?=$ccard_sales?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Sales Return</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="s_return" id="s_return" class="input_active g_input_amo g_col_fixed" title="<?=$s_return?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Net Sales</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="n_sale" id="n_sale" class="input_active g_input_amo g_col_fixed" title="<?=number_format(($cash+$credit+$ccard_sales)-$s_return,2)?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td>j)</td>
                    <td width="80">Receipts</td>
                    <td width="150">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Cash</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="j_cash" id="j_cash" class="input_active g_input_amo g_col_fixed" title="<?=number_format($receipt+$advance_amount,2)?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Credit Card</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="j_credit" id="j_credit" class="input_active g_input_amo g_col_fixed" title="<?=$j_section['credit']?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Cheques</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="j_cheque" id="j_cheque" class="input_active g_input_amo g_col_fixed" title="<?=$j_section['cheque']?>"></td>
                    <td></td>
                </tr>

                <tr>                
                    <td></td>
                    <td></td>
                    <td width="150">Total</td>
                    <td></td>                
                    <td align="right"><input type="text" readonly="readonly" name="j_tot" id="j_tot" class="input_active g_input_amo g_col_fixed" title="<?=number_format($j_section['cheque']+$j_section['credit']+$j_section['cash'],2)?>"></td>
                    <td></td>
                </tr>

               
                <tr>                
                    <td>k)</td>
                    <td width="80">Note</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>                
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>                
                    <td></td>
                    <td width="80"></td>
                    <td colspan="3"><input type="text" name="note" id="note"  class="input_active "  title="" style="width: 100%;text-align: left;"></td>
                    <td></td>
                </tr>    

                <tr>                
                    <td></td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>                
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>             
            </table>
            <input id="btnExit" type="button" title="Exit" value="Exit">
            <input type="button" title='Reset' value="Save" id="btnReset" />
            <?php if($this->user_permissions->is_delete('m_dialy_summery')){ ?><input id="btnDelete" type="button" title="Cancel" value="Cancel"/><?php } ?>
            <?php if($this->user_permissions->is_re_print('m_dialy_summery')){ ?><input id="btnPrint" type="button" title="Print" value="Print"/><?php } ?>
            <?php if($this->user_permissions->is_add('m_dialy_summery')){ ?><input type="button" title='Save <F8>' value="Save" id="btnSave" /><?php } ?>



        </form>
    </div>

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

       <input type="hidden" name='by' value='m_dialy_summery' title="m_dialy_summery" class="report">
       <input type="hidden" name='page' value='A4' title="A4" >
       <input type="hidden" name='orientation' value='P' title="P" >
       <input type="hidden" name='type' value='m_dialy_summery' title="m_dialy_summery" >
       <input type="hidden" name='header' value='false' title="false" >
       <input type="hidden" name='qno' value='' title="" id="qno" >
       <input type="hidden" name='rep_sup' value='' title="" id="rep_sup" >


   </form>
<?php } ?>
