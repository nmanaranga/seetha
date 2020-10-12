<?php if($this->user_permissions->is_view('m_bank')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_bank.js'></script>
<h2>Bank</h2>
<div class="dframe">
    <table border="0" style="width:100%">
    <tr>
        <td valign="top" class="content" style="width:500px;">
            <div class="form" id="form" >
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_bank" >
                <table>
                    <tr>

                        <td>Code</td>
                        <td><input type="text" class="input_txt" title='' id="code" name="code" maxlength="4" style="text-transform: uppercase;">
                        </td>
                    </tr>

                    <tr>
                        <td>Description</td>
                        <td><input type="text" class="input_txt" title='' id="description" name="description"  style="width:300px;" maxlength="255"/></td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="btnExit" title="Exit" />
                            <input type="hidden" id="code_" name="code_" />
                            <input type="button" id="btnReset" title='Reset'>
                            <?php if($this->user_permissions->is_add('m_bank')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                            
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td class="content" valign="top" style="width:700px;">
        <div class="form">
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
</div>
<?php } ?>
