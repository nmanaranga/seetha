<?php if($this->user_permissions->is_view('s_permission_level_order')){ ?>
<script type="text/javascript" src="<?=base_url()?>js/s_permission_level_order.js" ></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />


<h2 style="text-align: center;">User Permission Level Order</h2>


<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 590px;">
        <div class="form" id="mframe" style="text-align: center; width:100%;">
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/s_permission_level_order" >
                <table>
                    <tr>
                        <td style="width: 100px;">Code</td>
                        <td>
                            <input type="text" name="code" id="code" title="" class="input_txt" style="width:150px;"/>
                            <input type="hidden" name="c_hidden" id="c_hidden" title="0" class="input_txt"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><input type="text" name="c_name" id="c_name"  class="input_txt" style="width:400px;"/></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><input type="text" name="description" id="description" class="input_txt" style="width:400px;"/></td>
                    </tr>
                    <tr>
                        <td>Check All Role</td>
                        <td><input type="checkbox" name="all_role" id="all_role" title="1" /></td>
                    </tr>
                    <tr>
                        <td>Active</td>
                        <td><input type="checkbox" name="is_active" id="is_active" title="1"/></td>
                    </tr>
                </table>

                <table style="width: 100%;" id="tgrid">
                    <tr>
                        <th class="tb_head_th" style='width : 150px;' >No</th>
                        <th class="tb_head_th" style='width : 200px;'>Roll Id</th>
                        <th class="tb_head_th" >Roll Name</th>
                     
                    </tr>

                    <?php
                    $t=1; 
                        for($x=0;$x<10;$x++){  

                            echo "<tr>";
                            echo "<td><input type='hidden' name='hid_".$x."' class='g_input_txt' readonly='readonly' id='hid_".$x."' />
                                  <input type='text' name='no_".$x."' class='g_input_txt' readonly='readonly' id='no_".$x."' title='".$t."' value='".$t."'/>
                                  </td>";
                            echo "<td><input type='text' name='rolid_".$x."' class='g_input_txt pop' id='rolid_".$x."' style='width:100%;'/></td>";
                            echo "<td><input type='text' name='rolname_".$x."' class='g_input_txt' readonly='readonly' id='rolname_".$x."' style='width:100%;'/></td>";
                           
                            echo "</tr>";
                       $t++;
                        }
                    ?>
                </table>

                <div style="text-align: right; padding: 7px;">
                    <input type="hidden" id="code_" name="code_"/>
                    <input type="button" id="btnExit" title='Exit' />
                    <input type="button" id="btnReset" title="Reset" />
                    <?php if($this->user_permissions->is_add('s_permission_level_order')){ ?><input type="button" id="btnSave" title="Save" /><?php } ?>
                </div>
            </form>
        </div>
    </td>
        <td valign="top" class="content">
            <div class="form" id="form" style='margin-top:10px;'>
                <table>
                <tr>
                <td style="padding-right:5px;"><label>Search</label></td>
                <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:453px; marging-left:20px;"></td>
                </tr>
                </table> 
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>    
<?php } ?>
