
<script type='text/javascript' src='<?=base_url()?>js/m_main_cat.js'></script>
<h2>Main Category</h2>
<table width="100%">
<tr>
    <td valign="top" class="content">
        <div class="form" id="form">
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_main_cat" >
            <table>
                <tr>
                    <td>Code</td>
                    <td><input type="text" class="input_txt" title='Code' name="code" id="code" maxlength="10"></td>
                </tr><tr>
                    <td>Description</td>
                    <td><input type="text" class="input_txt" title='Description' name="description" id="description"  maxlength="255"/></td>
                </tr><tr>
                    <td colspan="2" style="text-align: right;">
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
    <td class="content" valign="top">
        <div class="form">
            <div id="grid_body"><?=$table_data;?></div>
        </div>
    </td>
</tr>
</table>
