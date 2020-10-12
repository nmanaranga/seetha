<?php if($this->user_permissions->is_view('001')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_main_regon.js'></script>
<h2>Main Region</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 480px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_main_regon" >
                <table>
                    <tr>
                        <td>Region Code</td>
                        <td><input type="text" class="input_txt" title='Code' name="code" id="code" maxlength="10"></td>
                    </tr><tr>
                        <td>Region Description</td>
                        <td><input type="text" class="input_txt" name="description" title='Description' id="des" style="width: 250px;" maxlength="255"/></td>
                    </tr><tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="hidden" id="code_" name="code_"/>
                        <input type="button" id="btnExit" title="Exit" />
                        <?php if($this->user_permissions->is_add('001')){ ?>
                            <input type="button" id="btnSave1" title='Save <F8>' />
                        <?php } ?>    
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td valign="top" class="content">
            <div class="form" >
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>
<?php } ?>
