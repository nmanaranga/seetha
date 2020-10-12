<?php if($this->user_permissions->is_view('m_gift_voucher')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_gift_voucher.js'></script>
<h2>Gift Voucher</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width:450px;">
            <div class="form" id="form" style="width:450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_gift_voucher" >
                <table>
                    <tr>
                        <td>Code</td>
                        <td colspan="2">
                            <input type="text" class="input_txt" title='' id="code" name="code" maxlength="15" style="width:150px; text-transform: uppercase;">
                            <input type="hidden" class="input_txt" id="code_" name="code_">
                        </td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td colspan="2"><input type="text" class="input_txt" title='' id="description" name="description"  maxlength="50" style="width:350px;"/></td>
                    </tr>
                    <tr>
                        <td>Cost</td>
                        <td><input type="text" class="input_txt g_input_amo" title='' id="cost" name="cost" style="width:100px;"/></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type="text" class="input_txt g_input_amo" title='' id="price" name="price"  style="width:100px;"/></td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td>
                            <input type="text" class="input_txt" title='' id="supplier" name="supplier"  maxlength="50" style="width:100px;"/>
                            <input type="text" class="hid_value" title='' id="supplier_des" name="supplier_des"  style="width:250px;"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
							<input type="button" id="btnExit" title="Exit" />
                            <?php if($this->user_permissions->is_add('m_gift_voucher')){ ?><input type="button" id="btnSave" title='Save <F8>' /> <?php } ?>
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td valign="top" class="content" style="width:600px;">
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