<?php if($this->user_permissions->is_view('r_subitem')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_subitem.js'></script>
<h2>Sub Item</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width:450px;">
            <div class="form" id="form" style="width:450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_subitem" >
                <table>
                    <tr>
                        <td>Code</td>
                        <td><input type="text" style="width:150px; text-transform:uppercase" class="hid_value" readonly="readonly" title='<?=$max_no?>' id="code" name="code"></td>
                    </tr><tr>
                        <td>Description</td>
                        <td><input type="text" style="width:350px;" class="input_txt" title='' id="description" name="description"  maxlength="50"/></td>
                    </tr><tr>
                        <td>Quantity</td>
                        <td><input type="text" class="g_input_num " title='' id="qty" name="qty"  maxlength="50" style='font-weight: bold; width:150px; padding:3px; border: 1px solid #003399 !important;'/></td>
                    </tr><tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="hidden" id="code_" name="code_" />
                            <input type="hidden" id="max_no" name="max_no" />
							<input type="button" id="btnExit" title="Exit" />
                            <?php if($this->user_permissions->is_add('r_subitem')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
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