<?php if($this->user_permissions->is_view('004')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_area.js'></script>
<h2>Area</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_area" >
                <table>
                    <tr>
                        <td>Area Code</td>
                        <td><input type="text" class="input_txt" title='Code' id="code" name="code" maxlength="10" style="align:right"></td>
                    </tr><tr>
                        <td>Area Description</td>
                        <td><input type="text" class="input_txt" title='Description' id="des" name="description" maxlength="255" /></td>
                    </tr><tr>
                        <td>Sub Region</td>
                        <td>
                            <span id="subregion"><?=$sub_regon?></span>
                            <input type="text" class="input_txt" title='Sub Region' id="sr_des" readonly="readonly" style="width: 250px;">
                        </td>
                    </tr><tr>
                        <td>Sales Ref</td>
                        <td>
                            <span id="subregion"><?=$sales_ref?></span>
                            <input type="text" class="input_txt" title='Sales Ref' id="sre_des" readonly="readonly" style="width: 250px;">
                        </td>
                    </tr><tr>
                        <td colspan="2" style="text-align:right">
                            <input type="hidden" id="code_" name="code_" />
                        <input type="button" id="btnExit" title="Exit" />
                            <?php if($this->user_permissions->is_view('004')){ ?>
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