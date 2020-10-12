<?php if($this->user_permissions->is_view('s_add_role')){ ?>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    .posting_div{
        background-color: #FFF;
        border: 1px dotted #CCC;
        padding: 7px;
        padding-buttom: 0px;
    }
    
    .heading {
        background-color: #aee8c8;
        margin: 5px;
        border: 2px solid #FFF;
        box-shadow: 0px 0px 5px #AAA;
        text-align: left;
        padding: 7px;
    }
</style>
<script type="text/javascript" src="<?=base_url()?>js/s_add_role.js" ></script>

<h2 style="text-align: center;">Add Role</h2>
<div class="dframe" id="mframe" style="text-align: center;">
    
    
        <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/s_add_role" >
            
            <table>
                <tr>
                    <td style="width: 100px;">User</td>
                    <td>
                        <?//=$user;?>
                    </td>
                </tr>
            </table>
        <table style="width: 100%" id="tgrid">
            <tr>
                <th class="tb_head_th" style='width : 100px;' >Code</th>
                <th class="tb_head_th" >Description</th>
                <th class="tb_head_th"  style='width : 50px;'>Is Active</th>
                <th class="tb_head_th"  style='width : 100px;'>From Date</th>
                <th class="tb_head_th"  style='width : 100px;'>To Date</th>
            </tr>
            <?php
                // foreach($table_data as $r){                    
                //     echo "<tr>";
                //         echo "<td><input type='text' name='role_id".$r->role_id."' class='g_input_txt' readonly='readonly' id='role_id".$r->role_id."' title='".$r->role_id."'/></td>";
                //         echo "<td><input type='text' name='description".$r->role_id."' class='g_input_txt' readonly='readonly' id='description".$r->role_id."' title='".$r->description."' style='width:100%'/></td>";   
                //         echo "<td style='text-align: center;'><input type='checkbox' name='is_active".$r->role_id."' id='check".$r->role_id."' class='ob_a' title='1' /></td>";  
                //         echo "<td><input type='text' class='input_date_down_future' readonly='readonly' name='date_to".$r->role_id."' id='date_to".$r->role_id."' title='0000-00-00' /></td>";   
                //         echo "<td><input type='text' class='input_date_down_future' readonly='readonly' name='date_from".$r->role_id."' id='date_from".$r->role_id."' title='0000-00-00' /></td>";   
                //     echo "</tr>";
                //}
            ?>
        </table>
        <div style="text-align: right; padding: 7px;">
            <input type="hidden" id="code_" name="code_"/>
            <input type="button" id="btnExit" title='Exit' />
            <input type="button" id="btnReset" title="Reset" />
            
            <input type="submit" id="btnSave" title='Save <F8>' />
          
        </div>
    </form>
</div>
<?php } ?>