<?php if($this->user_permissions->is_view('chq_print')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/chq_print.js"></script>
<h2 style="text-align: center;">Cheque Writing</h2>
<div class="dframe" id="mframe">
     <form method="post" action="<?=base_url()?>index.php/main/save/chq_print" id="form_"> 
       <fieldset>
           <table style="width: 100%" border="0">
             <tr>
               <td colspan="3"> </td>
               <td style="width: 50px;">No</td>
               <td>
                   <input type="text" style="width:100px;" class="input_active_num input_txt" name="id" id="id" title="<?=$max_no?>" />
                   <input type="hidden" id="hid" name="hid" title="0" />
               </td>
             </tr>
             <tr>
                <td colspan="3"> </td>
                <td style="width: 50px;">Voucher No</td>
                <td>
                   <input type="text" style="width:100px;" class="input_active_num hid_value input_txt" name="voucher_id" id="voucher_id" title="<?//=$max_no?>" />
                   <input type="hidden" id="h_vocher_id" name="h_vocher_id" title="0" />
                </td>
             </tr>
             <tr>
                <td colspan="3"> </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('chq_print')){ ?>
                       <input type="text" class="input_date_down_future input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="text-align:right;width:100%"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="text-align:right; width:100%"/>
                    <?php } ?>    
                </td>
             </tr>
             <tr>
                 <td style="width: 100px;">A/C No</td>
                 <td>
                     <input type="text" class="input_txt hid_value" title='' name="acc_No" id="acc_No"  style="width: 150px;" readonly="readonly">
                     <input type="text" class="hid_value" title='' name="acc_No_des" readonly="readonly" id="acc_No_des"  style="width: 300px;">
                 </td>
             </tr>
             <tr>
                <td style="width: 100px;">Cheque No</td>
                <td><input type="text"  class="hid_value input_txt" style="width:150px;" name="cheque_id" id="cheque_id" title="" readonly="readonly" /></td>        
             </tr>   
        </table>
    </fieldset>
    <br/>
    <fieldset>
        <table>
            <tr>
                <td style="width: 100px;"> Cross Cheq. </td>
                <td>
                    <input type="checkbox" id="cross_cheq_id" name="cross_cheq_id" title="1"> 
                     <td>
                    <select style="width: 100px;" id ="cross_word" name ="cross_word">
                        <option value="A/C Payee Only">A/C Payee Only</option>
                        <option value="Not Negotiable">Not Negotiable </option>
                        <option value="Cross Only">Cross Only</option>
                    </select>
                </td>
                <td></td>
            </tr>
        </table>
    </fieldset>
    <br/>
    <fieldset>
        <table>
            <tr>
                <td style="width: 100px;"> Bank Date</td>
                <td  style="width: 663px;">
                    <input type="checkbox" id="bank_date_id" name="bank_date_id" title="1"> 
                    <input type="text" class="input_txt" readonly="readonly" name="bank_date" id="bank_date" title="<?=date('Y-m-d')?>" style="text-align:right; width:127px;"/>
                      
                </td>
                <td style="width: 100px;">
                    Cash Cheque
                    <input type="checkbox" id="cash_cheque_id" name="cash_cheque_id" title="1">
                </td>
            </tr>
           
            <tr>
                <td> Payee</td>
                <td>
                     <input type="text" class="input_txt hid_value" title='' name="Payee_No" id="Payee_No"  style="width: 150px;">
                     <input type="text" class="input_txt" title='' name="Payee_No_des" id="Payee_No_des"  style="width: 300px;">
                 </td>

            </tr>
             <tr style='display:none;'>
                <td> Counter File Name</td>
                <td>
                     <input type="text" class="input_txt" title='' name="CF_Name_des"  id="CF_Name_des"  style="width: 453px;">
                     <input type="hidden"  name="payee_hid"  id="payee_hid" >
                     <input type="hidden"  name="cf_hid"  id="cf_hid" >
                 </td>

            </tr>
            <tr>
                <td> Description</td>
                <td>
                     <input type="text"  name="description" id="description" class='input_txt' style="width: 453px; ">
                 </td>

            </tr>
            <tr>
                <td> Amount Rs.</td>
                <td>
                    <input type="text"  class="hid_value input_num input_txt" name="amount" id="amount"  style="width: 150px;text-align:right;">
                </td>

            </tr>
            <tr>
            <td > </td>            
            <td style="width:400px; align:right;">
                <input type="button" id="btnprint" title="Print" />
                <input type="button" id="btnReset" title='Reset'> 
                <input type="button" id="btnPrint" title='Print'> 
                <?php if($this->user_permissions->is_delete('chq_print')){ ?><input type="button" id="btndelete" title='Delete' /><?php } ?>           
                <?php if($this->user_permissions->is_add('chq_print')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
            </td>
            </tr>

        </table>
    </fieldset><br/>

</form> 

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
         <input type="hidden" name='by' value='chq_print' title="chq_print" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='P' title="P" >
         <input type="hidden" name='type' value='chq_print' title="chq_print" >
         <input type="hidden" name='header' value='false' title="false" >
         <input type="hidden" name='qno' value='' title="" id="qno" >
         <input type="hidden" name='acc_code' id="acc_code" >
         <input type="hidden" name='p_amount' id="p_amount" >
         <input type="hidden" name='date' title="<?=date('Y-m-d')?>" id="date" >
    </form>

</div>
<?php } ?>