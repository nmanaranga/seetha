<?php if($this->user_permissions->is_view('m_account_category')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_account_category.js'></script>
<h2>Account Category</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width:600px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_account_category" >
                <table>
                    <tr>
                        <td>Code</td>
                        <td>
                    <input type="text" class="input_txt upper" title='' id="code" name="code" maxlength="4" style="align:right; width:130px; text-transform: uppercase;"></td>
                    </tr><tr>
                        <td>Description</td>
                        <td><input type="text" class="input_txt" title='' id="description" name="description" maxlength="50" style="width:350px;"/></td>
                    </tr>
					<tr>
                        <td colspan="2" style="text-align:right">
                            <input type="hidden" id="code_" name="code_" />
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title='Reset'>
                        <?php if($this->user_permissions->is_add('m_account_category')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td valign="top" class="content" >
            <div class="form" id="form" >
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
