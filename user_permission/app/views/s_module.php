<?php if($this->utility->is_SuperAdmin()){ ?>
<script type='text/javascript' src='<?=base_url()?>js/s_module.js'></script>
<h2>Module</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 500px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/s_module" >
                    <table border="0">

                    <tr>
                        <td width="">Main Module</td>
                        <td width="">
                            <?=$def_mod ?>
                            <!-- <select name="main_mod" id="main_mod">
                                        <option value="0">---</option>
                                        <option value="1">Seettu</option>
                                        <option value="2">HP</option>
                                        <option value="3">Service</option>
                                        <option value="4">Cheque</option>
                                        <option value="5">Gift Voucher</option>
                                        <option value="6">Common</option> 
                                    </select> -->
                            </td>

                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>
                             <?=$def_category ?>
                            <!-- <select name="package" id="package">
                                <option value="1">Master Form</option>
                                <option value="2">Transactions</option>
                                <option value="3">HP</option>
                                <option value="4">Reports</option>
                                <option value="5">Settings</option>
                                <option value="6">Find</option>
                                <option value="7">Settu</option>
                                <option value="8">Cheques</option>
                                <option value="9">User Permition</option>
                                <option value="10">Service</option>
                                <option value="11">Gift Voucher</option>
                                <option value="12">HP Reports</option>
                                <option value="13">Seettu Reports</option>
                                <option value="14">Service Reports</option>
                                <option value="15">Cheque Reports</option>
                                <option value="16">Gift Voucher Reports</option>
                            </select> -->
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Code</td>
                        <td><input type="text" readonly="readonly" class="input_txt"  name="m_code" style="text-transform:uppercase; width: 150px;" id="code" maxlength="10" title="<?php echo $max_no;?>"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Module Name</td>
                        <td><input type="text" class="input_txt"  name="module_name" style=" width: 150px;" id="module_name"></td>
                        <td style="width: 200px;">
                            <?php if ($this->utility->is_SuperAdmin()) {
                                echo "Only For Super Admin : <input type='checkbox' name='is_special' id='is_special'/>";
                            } ?>

                        </td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td colspan="2"><input type="text" class="input_txt" name="m_description"  id="des" style="width: 400px;" maxlength="255"/></td>

                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">
                            <input type="hidden" id="code_" name="code_"/>
                            <input type="button" id="btnExit" title='Exit' />                    
                            <?php if($this->user_permissions->is_add('s_module')){ ?><input type="button" id="btnSave" title='Save' /><?php } ?>                          
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td valign="top" class="content">
            <div class="form" id="form">
                <table>
                <tr>
                <td style="padding-right:60px;"><label>Search</label></td>
                <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:545px; marging-left:20px;"></td>
                </tr>
                </table> 
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>
<?php } ?>