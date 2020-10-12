
<script type='text/javascript' src='<?=base_url()?>js/m_hp_payment_scheme.js'></script>
<h2>Hire Purchase Payment Scheme</h2>
<table width="100%">
<tr>
    <td valign="top" class="content" style="width:480px;" >
        <div class="form" id="form" style="width:470px;" >
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_hp_payment_scheme" >
            <table>

                <tr>
                    <td>Code</td>
                    <td>
                        <input type="text" class="input_txt" title='' name="code" id="code" maxlength="4" style="width:150px; text-transform: uppercase;">
                      
                    </td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td><input type="text" class="input_txt" title='' name="description" id="description"  style="width:350px;" maxlength="50"/></td>
                </tr>
                <tr>
                    <td>Interest Rate</td>
                    <td>
                        <input type="text" class="g_input_num input_active" title='' name="interest_rate" id="interest_rate" style="width:150px;"> %
                        <input type="radio" title='1' name="interest_type" id="monthly"> Monthly
                        <input type="radio" title='2' name="interest_type" id="yearly"> Yearly
                      
                    </td>
                </tr>

                <tr>
                    <td>Period</td>
                    <td>
                        <input type="text" class="g_input_num input_active" title='' name="period" id="period"style="width:150px;">
                      
                    </td>
                </tr>

                <tr>
                    <td>Payment Type</td>
                    <td>
                        <select name="payment_type" id="payment_type">
                            <option value="">...</option>
                            <option value="1">Daily</option>
                            <option value="2">Monthly</option>
                            <option value="3">Yearly</option>
                        </select>
                       
                        Difference <input type="text" class="g_input_num input_active" title='' name="payment_gap" id="payment_gap" style="width:135px;">
                      
                    </td>
                </tr>
                <tr>
                    <td style="min-width:120px !important;">Document Charges</td>
                    <td>
                        <input type="text" class="g_input_amo input_active" title='' name="document_charges" id="document_charges" maxlength="4" style="width:150px; text-transform: uppercase;">
                      
                    </td>
                </tr>


                <tr>
                    <td>Down Payment</td>
                    <td>
                        <table style="border:1px solid black;">
                            <tr>
                                <td><input type="radio" title='1' name="down_pay_type" id="down_pay_type_val"> Value</td> 
                                <td><input type="text" class="g_input_amo input_active" title='' name="down_pay_val" id="down_pay_val" style="width:150px; margin-left:10px;"></td>
                            </tr>
                            <tr>
                                <td><input type="radio" title='2' name="down_pay_type" id="down_pay_type_pre">%</td> 
                                <td><input type="text" class="g_input_amo input_active" title='' name="down_pay_pre" id="down_pay_pre" style="width:150px; margin-left:10px;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>Loan Amount</td>
                    <td>
                        <table style="border:1px solid black;">
                            <tr>
                                <td>Minimum</td> 
                                <td><input type="text" class="g_input_amo input_active" title='' name="loan_min_amount" id="loan_min_amount" style="width:150px;margin-left:10px;"></td>
                            </tr>
                            <tr>
                                <td>Maximum</td> 
                                <td><input type="text" class="g_input_amo input_active" title='' name="loan_max_amount" id="loan_max_amount" style="width:150px;margin-left:10px;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                <tr>
                    <td>Editable</td>
                    <td>
                        <input type="checkbox" title='1' name="is_editable" id="is_editable" maxlength="4">
                      
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: right;">
                        <input type="hidden" id="code_" name="code_" />
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnSave1" title='Save <F8>' />
                        <input type="button" id="btnReset" title='Reset'>
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </td>
    <td class="content" valign="top" style="width:600px;">
        <div class="form" id="form" style="width:600px;">

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
