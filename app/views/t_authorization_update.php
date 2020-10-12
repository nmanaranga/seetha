<?php if($this->user_permissions->is_view('t_authorization_update')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_authorization_update.js"></script>

<h2 style="text-align: center;">User Authorization </h2>

<div class="dframe" id="mframe" style="width: 1024px;">
   
    <form method="post" action="<?=base_url()?>index.php/main/save/t_purchase" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td colspan="2"><span style="margin-right:10px;">User</span>
                
                    <input type="text" class="input_txt" id="user"  style="width: 350px;" />
                   </td>
                <td>User Role</td>
                <td>
                    <input type="text" class="input_txt" name="user_role" id="user_role"   style="width: 530px;" title="<?//=$max_no?>" />
                   
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <div style="height:400px;overflow:scroll;">
                    <table style="width: 100%;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 50px;">Cluster</th>
                                <th class="tb_head_th" style="width: 50px;">Branch</th>
                                <th class="tb_head_th" style="width: 60px;">Trans Type</th>
                                <th class="tb_head_th" style="width: 60px;">Trans No</th>
                                <th class="tb_head_th" style="width: 50px;">Form</th>
                                <th class="tb_head_th" style="width: 50px;">Level</th>
                                <th class="tb_head_th" style="width: 50px;">Amount</th>
                                <th class="tb_head_th" style="width: 50px;">Operator</th>
                                <th class="tb_head_th" style="width: 50px;">PT</th>
                                <th class="tb_head_th" style="width: 50px;">OK</th>
                                <th class="tb_head_th" style="width: 50px;">Reject</th>
                                <th class="tb_head_th" >Comment</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis_pre' id='4_".$x."' name='4_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo free_is' id='5_".$x."' name='5_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='6_".$x."' name='6_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' id='7_".$x."' name='7_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='checkbox' class='g_input_amo dis_pre' id='8_".$x."' name='8_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='checkbox' class='g_input_amo free_is' id='9_".$x."' name='9_".$x."' style='width : 100%;'/></td>";
                                        echo "<td id='t_".$x."' name='t_".$x."' style='text-align: right;background-color: #f9f9ec;' class='tf'>&nbsp;</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                          
                        </tfoot>
                        
                    </table>
                </div>
                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Cancel" />
                        <input type="button" id="btnDelete5" title="Delete" />
                       
                        <?php if($this->user_permissions->is_view('020')){ ?>
                        <input type="button"  id="btnSave5" title='Save <F8>' />
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php } ?>