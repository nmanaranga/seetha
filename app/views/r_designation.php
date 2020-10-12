<?php if($this->user_permissions->is_view('r_designation')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_designation.js'></script>
<h2>Employee Designation</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width:35%">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_designation" >
                <table>
                    <tr>
                        <td>Code</td>
                        <td><input type="text" class="input_txt" title='' id="code" name="code" maxlength="4" style="align:right;width:130px; text-transform:uppercase;"></td>
                    </tr><tr>
                        <td>Description</td>
                        <td><input type="text" class="input_txt" title='' id="description" name="description" maxlength="50" style="width:350px;"/></td>
                    </tr>
					<tr>
                        <td colspan="2" style="text-align:right">
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
        <td valign="top" class="content" style="width:40%">
            <div class="form" id="form">
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