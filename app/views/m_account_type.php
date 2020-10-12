<?php if($this->user_permissions->is_view('m_account_type')){ ?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/t_forms.css" />
<script type='text/javascript' src='<?= base_url() ?>js/m_account_type.js'></script>
<h2>Account Setup</h2>
<div>
    <table style="width:100%;" id="tbl1">
        <tr>
            <td valign="top" class="content" style="width:500px;">
                <div class="form" id="form">
                    <form id="form_" method="post" action="<?= base_url() ?>index.php/main/save/m_account_type" >
                        <table style="width:450px;" id="tbl2">
                            <tr>
                                <td>Category</td>
                                <td colspan="2"><input type="text" id="control_type" name="control_category" style="width:100px; text-transform: uppercase;" title="" class="input_txt upper" maxlength="10"/>
                                <input type="text" id="account_type" style="width:250px;"  readonly="readonly" class="hid_value"/></td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td colspan="2"><input type="text" id="code" name="code" style="width:100px; text-transform: uppercase;" title="<?php echo $max_no;?>" class="input_txt upper" maxlength="10"/></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td colspan="2"><input class="input_txt" type="text" id="description" title="" name="heading" maxlength="50" style="width:353px;" /></td>
                            </tr>
                            <tr>
                                <td>Report</td>
                                <td colspan="2">
                                    <select name="report" id="report">
                                        <option value="0">--</option>
                                        <option value="1">Balance Sheet</option>
                                        <option value="2">Profit and Loss</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td colspan="2">
                                    <select name="rtype" id="rtype">
                                        <option value="0">--</option>
                                        <option value="1">Income</option>
                                        <option value="2">Expence</option>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td>Is Control</td>

                                <td colspan='2'><input class="input_txt" type="checkbox" id="is_con" title="1" name="is_control_category" style='margin-left:-68px;'/>
                                <span style='margin-left:55px;'>Next Code Format </span><input type="text" id='ncformat' name='ncformat' class='input_txt' style='width:108px;' maxlength="8" /></td>
                            </tr>

                            <tr>
                                <td>Is Ledger Account</td>
                                <td colspan="2"><input class="input_txt" type="checkbox" id="is_ledger_acc" title="1" name="is_ledger_acc" style='margin-left:-68px;'/>
                                </td>
                            </tr>

                            <tr>
                            <td colspan='3'><hr/></td>                                
                            </tr>

                            <tr class='is_show'>
                                <td>Acc Type</td>
                                <td colspan="2"><input type="text" id="control_type2" name="control_type2" style="width:100px; text-transform: uppercase;" title="" class="input_txt upper" maxlength="10"/>
                                <input type="text" id="account_type2" style="width:250px;"  readonly="readonly" class="hid_value"/></td>
                            </tr>

                            <tr class='is_show'>
                                <td>Code</td>
                                <td colspan="2"><input type="text" id="code_samp" name="code_samp" style="width:100px; text-transform: uppercase;" title="<?php echo $max_no;?>" class="hid_value upper" maxlength="10"/></td>
                            </tr>

                            <tr class='is_show'>
                                <td>Description</td>
                                <td colspan="2"><input class="hid_value" type="text" id="description_samp" name="description_samp" title="" maxlength="50" style="width:353px;" /></td>
                            </tr>

                            <tr class='is_show'>
                                <td>Is Bank Account</td>
                                <td colspan="2"><input class="input_txt" type="checkbox" id="is_bank_acc" title="1" name="is_bank_acc" style='margin-left:-68px;'/></td>
                            </tr>

                            <tr class='is_show'>
                                <td>Is Control Account</td>
                                <td colspan="2"><input class="input_txt" type="checkbox" id="is_control_acc" title="1" name="is_control_acc" style='margin-left:-68px;'/></td>
                            </tr>

                            <tr class='is_show'>
                            <td>Con. acc of this acc</td>
                            <td colspan="2"><input type="text" id="control_acc" title="" name="control_acc" style="width:100px;" class="input_txt_f upper"/>
                            <input type="text" id="control"  style="width:250px;"  title="" readonly="readonly" class="hid_value"/></td>
                            </tr> 

                            <tr class='is_show'>
                                <td>Order No</td>
                                <td colspan="2"><input class="g_input_num input_txt" type="text" id="order_no" name="order_no" style='width:100px;'/></td>
                            </tr>


                            <tr class='is_show'>
                                <td>Display Text</td>
                                <td colspan="2"><input class="input_txt" type="text" id="dis_text" name="dis_text" style='width:353px;'/></td>
                            </tr>        

                            <tr>
                                <td>&nbsp;</td>
                                <td style="text-align:right" colspan="2">
                                    <input type="hidden" id="code_" name="code_" title="0" />
                                    <input type="button" id="btnExit" title='Exit' />
                                    <input type="button" id="btnReset" title='Reset'>  
                                    <?php if($this->user_permissions->is_add('m_account_type')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </td>

            <td valign="top" class="content">
                <div class="form" >
                    <table>
                        <tr>
                            <td style="padding-right:64px;"><label>Search</label></td>
                            <td><input type="text" class="input_txt" title='' id="type_search" name="srch" style="width:230px; marging-left:20px;">
                                <input type='checkbox' id='is_ledg' /> Ledger Account    
                            </td>
                        </tr>
                    </table>
                    <div id="grid_body"><?= $table_data; ?></div>
                </div>
            </td>

        </tr>
    </table>
</div>
<?php } ?>



