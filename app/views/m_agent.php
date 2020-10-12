<script type='text/javascript' src='<?=base_url()?>js/m_agent.js'></script>
<h2>Agent</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 390px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_agent" >
                <table>
                    <tr>
                        <td>Agent Code</td>
                        <td><input type="text" class="input_txt" title='Code' id="code" name="code" maxlength="10"></td>
                    </tr><tr>
                        <td>Agent Name</td>
                        <td><input type="text" class="input_txt" title='Agent Name' id="name" name="name"  maxlength="255"/></td>
                    </tr><tr>
                        <td>Full Name</td>
                        <td><input type="text" class="input_txt" title='Full Name' id="f_name" name="full_name"  maxlength="255"/></td>
                    </tr><tr>
                        <td>NIC No</td>
                        <td><input type="text" class="input_txt" title='National ID' id="nic" name="nic"  maxlength="10"/></td>
                    </tr><tr>
                        <td>Address 01</td>
                        <td><input type="text" class="input_txt" id="address_no" title="No" name="address01" style="width: 200px;"  maxlength="255"/></td>                       
                    </tr><tr>
                        <td>Address 02</td>
                        <td><input type="text" class="input_txt" id="address_street" name="address02" title="Street" style="width: 200px;"  maxlength="255"/></td>                          
                    </tr><tr>
                        <td>Address 03</td>
                        <td><input type="text" class="input_txt" id="address_city" name="address03" title="City" style="width: 200px;"  maxlength="255"/></td>                      
                    </tr><tr>
                        <td>Phone Numbers</td>
                        <td>
                            <input type="text" class="input_txt" id="p_mobile" title="Mobile" style="width: 85px;" maxlength="10" name="phone01" />
                            <input type="text" class="input_txt" id="p_office" title="Office" style="width: 85px;" maxlength="10" name="phone02" />
                            <input type="text" class="input_txt" id="p_fax" title="Fax" style="width: 85px;" maxlength="10" name="phone03" />
                        </td>                        
                    </tr><tr>
                        <td>Credit Limit</td>
                        <td><input type="text" class="input_amount" title='Credit Limit' id="credit_limit" name="credit_limit" /></td>
                    </tr><tr>
                        <td>Credit Days</td>
                        <td><input type="text" class="input_number" title='Credit Days' id="credit_days" name="credit_days" /></td>
                    </tr><tr>
                        <td>BR No</td>
                        <td><input type="text" class="input_txt" title='BR No' id="br_no" name="br_no" /></td>
                    </tr><tr>
                        <td>Bank Guarantee</td>
                        <td><input type="text" class="input_txt" title='Bank Guarantee' id="b_gar" name="bank_guarantee_code" /></td>
                    </tr><tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="hidden" id="code_" name="code_" />
                        <input type="button" id="btnExit" title="Exit" />
                            <input type="button" id="btnSave" title='Save <F8>' />
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td class="content" valign="top">
            <div class="form">
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>