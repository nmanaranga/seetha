
<?php if($this->user_permissions->is_view('chq_print_scheme')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/chq_print_scheme.js'></script>
<h2>Cheque Print Scheme</h2>
<div>
<table width="100%" border="0">
    <tr>
        <td valign="top" class="content" style="width: 640px;">
            <div class="form" id="form">
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/chq_print_scheme" >
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Print Settings</a></li>
                        <!-- <li><a href="#tabs-2">Counter File</a></li> -->
                    </ul>
                    <div id="tabs-1">
                        
                        <fieldset>
                            <table border="0" style="width:100%;">
                                <legend></legend>
                                <tr>
                                    <td width="136">Code</td>
                                        <td>&nbsp;</td>
                                    <td>
                                        <input type="text" class="input_txt"  title='' id="code" name='code' maxlength="10" style="width:150px; border: 1px solid #003399;padding: 3px;font-weight: bold;font-size: 12px;font-family: Arial;">
                                        <input type="hidden" id="code_"  name="code_" title='0'>
                                        &nbsp;&nbsp;Inactive <input type="checkbox" name="inactive" id="inactive" title="1" value="1">
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>

                                <tr>
                                    <td>Scheme Name</td>
                                    <td>&nbsp;</td>
                                    <td colspan="3"><input type="text" class="input_txt"  title='' id="name" name='name'  style="width:430px;"></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </fieldset>

                        <fieldset> 
                            <table border="0" style="width:100%;">
                                <legend><b>Date Print On</b></legend>   
                                <tr>
                                    <td width="128">Full Date</td>
                                    <td><b>X</b></td>
                                    <td><input type="text" class="input_active g_input_num"  id="bdata_x" name='bdata_x'  style="width:150px;"></td>
                                    <td width="86" style="text-align:right;"><b>Y</b></td>
                                    <td><input type="text" class="input_active g_input_num"  id="bdate_y" name='bdate_y'  style="width:150px;"></td>
                                </tr>

                                <tr>
                                    <td>Day</td>
                                    <td><b>X</b></td>
                                    <td>
                                        <input type="text" class="input_active g_input_num"  id="bdate1_x" name='bdate1_x'  style="width:73px;">
                                        <input type="text" class="input_active g_input_num"  id="bdate2_x" name='bdate2_x'  style="width:73px;">
                                    DD
                                    </td>
                                </tr>

                                <tr>
                                    <td>Month</td>
                                    <td><b>X</b></td>
                                    <td>
                                        <input type="text" class="input_active g_input_num"  id="bmonth1_x" name='bmonth1_x'  style="width:73px;">
                                        <input type="text" class="input_active g_input_num"  id="bmonth2_x" name='bmonth2_x'  style="width:73px;">
                                    MM
                                    </td>
                                </tr>

                                <tr>
                                    <td>Year</td>
                                    <td><b>X</b></td>
                                    <td colspan="3">
                                        <input type="text" class="input_active g_input_num"  id="byear1_x" name='byear1_x'  style="width:73px;">
                                        <input type="text" class="input_active g_input_num"  id="byear2_x" name='byear2_x'  style="width:73px;">
                                        <input type="text" class="input_active g_input_num"  id="byear3_x" name='byear3_x'  style="width:73px;">
                                        <input type="text" class="input_active g_input_num"  id="byear4_x" name='byear4_x'  style="width:73px;">
                                    YYYY
                                    </td>
                                </tr>
                            </table>
                        </fieldset>    
                          
                        <fieldset> 
                            <table border="0" style="width:100%;">
                                <legend><b>Payee Print On</b></legend>   
                                    <tr>
                                        <td>Payee Name</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="payee_x" name='payee_x'  style="width:150px;"></td>
                                        <td><b >Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="payee_y" name='payee_y'  style="width:150px;"></td>
                                    </tr>

                                    <tr>
                                        <td>First Line Width</td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input type="text" class="input_active g_input_num"  id="paylength" name='paylength'  style="width:150px;">
                                            Enter No letters
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Gap Between Lines</td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input type="text" class="input_active g_input_num"  id="paygap" name='paygap'  style="width:150px;">
                                        </td>
                                    </tr>

                            </table>
                        </fieldset> 

                        <fieldset> 
                            <table border="0" style="width:100%;">
                                <legend><b>Cross Word Print On</b></legend>  
                                    <tr>
                                        <td width="128">A/C Payee Only</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="crossing_x" name='crossing_x'  style="width:150px;"></td>
                                        <td width="111" style="text-align:right;"><b>Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="crossing_y" name='crossing_y'  style="width:150px;"></td>
                                    </tr>

                                    <tr>
                                        <td>Cross</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="crossonly_x" name='crossonly_x'  style="width:150px;"></td>
                                        <td width="111" style="text-align:right;"><b>Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="crossonly_y" name='crossonly_y'  style="width:150px;"></td>
                                    </tr>
                            </table>
                        </fieldset>  

                        <fieldset> 
                            <table border="0" style="width:100%;">
                                <legend><b>Amount Print On</b></legend>  
                                    <tr>
                                        <td>Amount in word</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="amw_x" name='amw_x'  style="width:150px;"></td>
                                        <td><b >Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="amw_y" name='amw_y'  style="width:150px;"></td>
                                    </tr>

                                    <tr>
                                        <td>1st/2nd Line Width</td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input type="text" class="input_active g_input_num"  id="amwlength" name='amwlength'  style="width:150px;">
                                            Enter No letters
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Gap Between Lines</td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input type="text" class="input_active g_input_num"  id="amountgap" name='amountgap'  style="width:150px;">
                                            Star Lenght
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Second Line</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="second_line_x" name='second_line_x'  style="width:150px;"></td>
                                        <td>&nbsp;</td>
                                        <td><input type="text" class="input_active g_input_num"  id="second_line_y" name='second_line_y'  style="width:150px;"></td>
                                    </tr>

                                    <tr>
                                        <td>Amount</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="amt_x" name='amt_x'  style="width:150px;"></td>
                                        <td>&nbsp;</td>
                                        <td><input type="text" class="input_active g_input_num"  id="amt_y" name='amt_y'  style="width:150px;"></td>
                                    </tr>                       
                            </table>
                        </fieldset> 
                        <fieldset> 
                            <table border="0" style="width:100%;">
                                <legend><b>Stamp Print On</b></legend>  
                                    <tr>
                                        <td width="128">Name Print</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="stamp_x" name='stamp_x'  style="width:150px;"></td>
                                        <td width="112">&nbsp;</td>
                                        <td><input type="text" class="input_active g_input_num"  id="stamp_y" name='stamp_y'  style="width:150px;"></td>
                                    </tr> 
                            </table>
                        </fieldset> 
                    </div>
                  
                    <div id="tabs-2" style="display:none;">
                        <fieldset> 
                            <table border="0" style="width:100%;">
                                <legend><b>Counter File</b></legend> 

                                    <tr>
                                        <td width="128">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td width="20">&nbsp;</td>
                                        <td>Is Print Counter File &nbsp;&nbsp;<input type="checkbox"  id="iscfprint" name='iscfprint' title="1" value="1"></td>
                                    </tr> 

                                    <tr>
                                        <td width="128">Date</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfdate_x" name='cfdate_x'  style="width:150px;"></td>
                                        <td width="20" style="text-align:right;"><b>Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfdate_y" name='cfdate_y'  style="width:150px;"></td>
                                    </tr> 

                                    <tr>
                                        <td width="128">Reailiage Date</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfrealidate_x" name='cfrealidate_x'  style="width:150px;"></td>
                                        <td width="20" style="text-align:right;"><b>Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfrealidate_y" name='cfrealidate_y'  style="width:150px;"></td>
                                    </tr> 

                                    <tr>
                                        <td width="128">Pay To</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfpay_x" name='cfpay_x'  style="width:150px;"></td>
                                        <td width="20" style="text-align:right;"><b>Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfpay_y" name='cfpay_y'  style="width:150px;"></td>
                                    </tr> 

                                    <tr>
                                        <td width="128">Pay To Length</td>
                                        <td>&nbsp;</td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfpaylength" name='cfpaylength'  style="width:150px;"></td>
                                    </tr>

                                    <tr>
                                        <td width="128">Note</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfnote_x" name='cfnote_x'  style="width:150px;"></td>
                                        <td width="20" style="text-align:right;"><b>Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfnote_y" name='cfnote_y'  style="width:150px;"></td>
                                    </tr> 

                                    <tr>
                                        <td width="128">Note Length</td>
                                        <td>&nbsp;</td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfnotelength" name='cfnotelength'  style="width:150px;"></td>
                                    </tr>

                                    <tr>
                                        <td width="128">Amount</td>
                                        <td><b>X</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfamount_x" name='cfamount_x'  style="width:150px;"></td>
                                        <td width="20" style="text-align:right;"><b>Y</b></td>
                                        <td><input type="text" class="input_active g_input_num"  id="cfamount_y" name='cfamount_y'  style="width:150px;"></td>
                                    </tr> 
                            </table>
                        </fieldset>
                    </div>
            
                <div style="text-align: right; padding: 7px;">
                    <input type="button" id="btnExit" title="Exit" />
                    <input type="button" id="btnReset" title='Reset'>
                    <?php if($this->user_permissions->is_add('chq_print_scheme')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                    <!-- <input type="button" id="btnPrint" title='Print' value="Print" /> -->
                    
                </div>
                </div>
                </form>
            </div>
        </td>
        <td class="content" valign="top" style="width: 450px;">
            <div class="form" id="form" style="width: 450px;">
            <table>
            <tr>
            <td style="padding-right:64px;"><label>Search</label></td>
            <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
            </tr>
            </table> 
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>

<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='m_customer' title="m_customer" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='pdf_no' id="pdf_no" value='' title="" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >  
                 <input type="hidden" name='code_find' value='' title="" id="code_find" >                
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name='dt' value='' title="" id="dt" > 
        
</form>
</div>
<?php } ?>