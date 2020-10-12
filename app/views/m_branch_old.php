<?php if($this->user_permissions->is_view('m_branch')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_branch.js'></script>
<h2>Branches</h2>

<table width="100%">
    <tr>
    <td valign="top" class="content" style="width: 800px;">
            <div class="form" id="form">
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_branch" >
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">General</a></li>
                        <li><a href="#tabs-2">Other</a></li>                        
                    </ul>

                     <div id="tabs-1"> <!-- Tab 1-->
                        <fieldset>
                            <legend>General</legend>
                            <table border="0" style="width:100%;">
                            <tr>
                                <td>Cluster</td>
                                <td><?php echo $cluster; ?>
                                    <input type="text" class="hid_value" title='' id="cluster_id"  maxlength="255" style="width: 270px;">   
                                </td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td><input type="text" class="input_txt" title='' id="bc" style="text-transform:uppercase;" name="bc" maxlength="3">
                                </td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>
                                    <input type="text" class="input_txt" title='' id="name" name="name"  style="width:375px;" maxlength="255"/></td>
                            </tr>
                             <tr>
                                <td>Address</td>
                                <td><input type="text" class="input_txt" id="address" title="" name="address" style="width: 375px;"  maxlength="255"/></td>                       
                            </tr>
                            <tr>
                                <td colspan="2">TP
                                    <span style="margin-left:37px;">
                                        <input type="text" class="input_txt" id="tp" title="" style="width:120px;" maxlength="10" name="tp" />
                                    </span>
                                 <span style="margin-left:110px;">   
                                Fax

                                    <input type="text" class="input_txt" id="fax" title="" style="width:120px;" maxlength="10" name="fax" />
                                </span>
                                </td>                        
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><input type="text" class="input_txt" title='' id="email" name="email"  style="width:375px;"/></td>
                            </tr>

                            </table>
                        </fieldset>
                    </div>

                    <div id="tabs-2"> <!-- Tab 2-->
  
                            <table border="0" style="width:100%;">

                             <tr>
                                <td width="126">Account</td>
                                <td>
                                <input type="text" class="input_txt ld" title="" id="current_acc" name="current_acc" data='r_unit'/>                  
                                <input type="text" class="hid_value" title='' id="acc_dtls"  name="acc_dtls" maxlength="255" style="width: 194px;">   
                                </td>
                            </tr>
                            </table>
                            <fieldset>
                             <table border="0" style="width:100%;">
                             <legend>Cash Sales</legend>

                            <tr>
                                <td>Customer Sale Limit</td>
                                <td><input type="text" name="cash_customer_limit" id="cash_customer_limit" class="input_active g_input_amo" /></td>
                            </tr>

                            <tr>
                                <td>Default Customer</td>
                                <td>
                                    <input type="text" name="def_cash_customer" id="def_cash_customer" class="input_txt"/>
                                    <input type="text" name="def_customer_des" id="def_customer_des" class="hid_value" style="width:194px;"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Default Loan Customer</td>
                                <td>
                                    <input type="text" name="def_loan_customer" id="def_loan_customer" class="input_txt"/>
                                    <input type="text" name="def_loan_customer_des" id="def_loan_customer_des" class="hid_value" style="width:194px;"/>
                                </td>
                            </tr>
                            </table>
                            </fieldset>
                            <br/>
                             <fieldset>
                             <table border="0" style="width:100%;">
                             <legend>Default Sales Value</legend>
                             <tr>
                                <td width="114">Store</td>
                                <td>
                                    <input type="text" class="input_txt ld" title="" id="def_sales_store" name="def_sales_store" data='r_unit'/>                
                                    <input type="text" class="hid_value" title='' id="store_id" name="store_id" maxlength="180" style="width: 199px;">   
                                </td>

                             </tr>
                             <tr>
                                <td>Sales Category</td>
                                <td>
                                    <input type="text" class="input_txt ld" title="" id="def_sales_category" name="def_sales_category" data='r_unit'/>                
                                    <input type="text" class="hid_value" title='' id="category_id"  maxlength="180" style="width: 199px;">   
                                </td>
                             </tr>
                             <tr>
                                <td>Group</td>
                                <td>                              
                                    <input type="text" class="input_txt ld" title="" id="def_sales_group" name="def_sales_group" data='r_unit'/>                
                                    <input type="text" class="hid_value" title='' id="group_dtls"  maxlength="180" style="width: 199px;">   
                                </td>
                             </tr>
                             </table>
                             </fieldset>            
                    </div>
                </div>
                <table>                    
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="btnExit" title="Exit" />
                            <input type="hidden" id="code_" name="code_" />
                            <?php if($this->user_permissions->is_add('m_branch')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td class="content" valign="top">
            <div class="form">
           <!-- <table>
            <tr>
            <td style="padding-right:64px;"><label>Search</label></td>
            <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
            </tr>
            </table> -->
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>
<?php } ?>


