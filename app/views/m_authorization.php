<?php if($this->user_permissions->is_view('m_authorization')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/m_authorization.js"></script>

<h2 style="text-align: center;">User Authorization Levels</h2>
<div class="dframe" id="mframe" style="width: 720px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_purchase" id="form_">
        <table style="width: 720px;" border="0">
            <tr>
                <td width="100">Form Name</td>
                <td width="100">
                    <select></select>
                </td>
                <td align="left" width="500">
                    <input type="text" class="hid_value" id="form_name" name="form_name" title="" style="width:470px;" maxlength="255" />
                </td>
                </tr>

                <tr>
                <td colspan="3" style="text-align: center;">
                    <div style="height:300px;overflow:scroll;">
                    <table style="width:100%" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 70px;">Level</th>
                                <th class="tb_head_th">User Role Id </th>
                                <th class="tb_head_th" style="width: 70px;">Description</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' style='width : 100%;'/></td>";
                                        
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        
                    </table>
                </div>
                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <input type="button" id="btnDelete5" title="Delete" />
                        
                       
                        <input type="button"  id="btnSave5" title='Save <F8>' />
                       
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php } ?>