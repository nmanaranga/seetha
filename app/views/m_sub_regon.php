<?php if($this->user_permissions->is_view('002')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_sub_regon.js'></script>
<h2>Sub Region</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 480px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_sub_regon" >
                <table>
                    <tr>
                        <td>Region Code</td>
                        <td><input type="text" class="input_txt" title='Code' name='code' id="code" maxlength="10" ></td>
                    </tr><tr>
                        <td>Region Description</td>
                        <td><input type="text" class="input_txt" title='Description' id="des" name="description" style="width: 250px;" maxlength="255"/></td>
                    </tr><tr>
                        <td>Main Region</td>
                        <td>
                            <span id="m_region"><?=$main_regon;?></span>
                            <input type="text" class="input_txt" title='Main Region' readonly="readonly" id="mr_des"  style="width: 150px;">
                        </td>
                    </tr><tr>
                        <td colspan="2" style="text-align: right;">
                        <input type="button" id="btnExit" title="Exit" />
                            <input type="hidden" id="code_" name="code_" />
                            <?php if($this->user_permissions->is_add('002')){ ?>
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