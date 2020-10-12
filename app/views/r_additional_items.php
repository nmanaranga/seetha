<?php if($this->user_permissions->is_view('r_additional_items')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_additional_items.js'></script>
<h2>Additional Items</h2>

<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 450px;">
            <div class="form" id="form" style="width: 450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_additional_items" >
                <table>

                    <tr>
                        <td>Type</td>
                        <td>
                            <select name="type" id="type">
                                <option value='1'>Purchase</option>
                                <option value='2'>Sales</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Code</td>
                        <td><input type="text" class="input_txt" style="text-transform:uppercase" title='' name="code" id="code" maxlength="5"></td>
                    </tr>
					<tr>
                        <td>Description</td>
                        <td><input type="text" class="input_txt" name="description" title='' id="description" style="width: 350px;" maxlength="50"/></td>
                    </tr>
					  <tr>
                        <td>Rate</td>
                        <td><input type="text" class="input_txt" title='' name="rate" id="rate" maxlength="10" >
                            <span style="margin-left:10px;">Add</span> <input type="checkbox" id="is_add" name="is_add" title="1" style="vertical-align:middle;"/>
                            <span style="margin-left:10px;">Is Reverse Entry</span><input type="checkbox" id="is_reverse" name="is_reverse" title="1" style="vertical-align:middle;"/>
                        </td>
                    </tr>

                    <tr>
                        <td>Account</td>
                        <td><input type="text" class="input_txt" name="account_id" id="account_id" style="width:100px;"/>
                        <input type="text" class="hid_value" name="account" id="account" style="width:250px;" readonly="readonly"/></td>
                    </tr>

                    
                    <!--
                    <td style="width: 100px;">Account</td>
                    
                        <td>
                            <input type="text" class="input_txt" id="aacount" title="account" style="width: 150px;" />
                            <input type="hidden" name="account" id="aacount" title="0" />
                            <input type="text" class="input_txt" title='Account' readonly="readonly" id="acc_des"  style="width: 350px;">
                        </td>

                    </td>
                    -->
                    


					<tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="hidden" id="code_" name="code_"/>
                            <input type="button" id="btnExit" title='Exit' />
                            <?php if($this->user_permissions->is_add('r_additional_items')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td valign="top" class="content" style="width: 600px;">
            <div class="form" id="form" style="width:600px;" >
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
<?php } ?>