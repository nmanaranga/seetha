<?php if($this->user_permissions->is_view('004')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_root.js'></script>
<h2>Route</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_root" >
                <table>
                    <tr>
                        <td>Route Code</td>
                        <td><input type="text" class="input_txt" title='Code' id="code" name="code" maxlength="10"></td>
                    </tr><tr>
                        <td>Route Description</td>
                        <td><input type="text" class="input_txt" title='Description' id="des" name="description" maxlength="255" style="width: 300px;" /></td>
                    </tr><tr>
                        <td>Area</td>
                        <td>
                        <span id="area_"><?=$area;?></span>
                        <input type="text" class="input_txt" title='Area Name' id="area_des" readonly="readonly" style="width:250px"  />
                        </td>
                    </tr><tr>
                        <td colspan="2" style="text-align: right;">
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