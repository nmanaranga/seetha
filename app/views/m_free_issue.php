<?php if($this->user_permissions->is_view('m_free_issue')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/m_free_issue.js"></script>

<style type="text/css">

table td{
    border-bottom:1px ;
    padding: 4px;
}

[type=text] {        
    text-align: left;
}

.regulat_txt{
    text-align: left;
}

</style>


<div id="testLoad"></div>
<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Free Issue Bulk Purchase</h2>
<div class="dframe" id="mframe" style="padding-right:38px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/m_free_issue" id="form_">
        
        <table border=0 width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="100">Code</td>
                <td>
                    <input name="code" id="code" class="input_active" style="width:200px;text-transform: uppercase;">
                    <input name="c_name" id="c_name" class="input_active" style="width:275px;">
                    <input type="button" name="load_f" id="load_f" title="Find">
                </td>
                <td></td>
                <td width="100" align="right">No</td>
                <td width="100">
                <input type='hidden' id='hid' name='hid'/>
                <input name="no" id="no" title="<?=$maxno?>" class="input_active" style="width:100px;"></td>
                
               </tr>
            <tr>
                <td width="100">Date From</td>                                
                <td colspan=2><input name="date_from" id="date_from" class="input_date_down_future" readonly="readonly"> Date To <input name="date_to" id="date_to" class="input_date_down_future" readonly="readonly"></td>
                 <td width="100" align="right">Date</td>
                <td width="100">
                <?php if($this->user_permissions->is_view('m_free_issue')){ ?>
                    <input name="date" id="date" class="input_date_down_future" readonly="readonly" title="<?=date('Y-m-d')?>">
                <?php }else{ ?>
                    <input name="date" id="date" class="input_active" readonly="readonly" style="width:100%" title="<?=date('Y-m-d')?>">
                <?php } ?>
                </td>
            
            </tr>

        </table> <br>

         <table width="100%" cellpadding="0" cellspacing="0">
            <!-- <tr>
                <td colspan=4></td>                
                <td style="border-left:2px solid #cc0000; padding-left:10px;" colspan=4><b  style="font-size:20px">FOC</b></td>
            </tr> -->
            <tr>
                <td>
                    <table style="width:100%" id="tgrid" border="0">
                        <tr><b style="font-size:20px">Purches</b></tr>
                        <tr>
                            <th class="tb_head_th" style="width:175px;">Code</th>
                            <th class="tb_head_th" style="width:225px;">Name</th>
                            <th class="tb_head_th" style="width:100px;">Model</th>
                            <th class="tb_head_th" style="width:100px;">Qty</th>
                        </tr>
                         <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr class='cl1'>";                                       
                                        echo "<td class='g_col_fixed'><input type='text' class='g_input_txt  fo' readonly='readonly' id='0_".$x."' name='0_".$x."' style='width : 100%;'/>
                                              <input type='hidden' class='' id='h_".$x."' name='h_".$x."' style='width : 100%;'/>
                                                </td>";
                                        echo "<td class='g_col_fixed'><input type='text' readonly='readonly' class='g_input_txt ' id='1_".$x."' name='1_".$x."' style='width : 100%;'/></td>";
                                        echo "<td class='g_col_fixed'><input type='text' readonly='readonly' class='g_input_txt ' id='2_".$x."' name='2_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num' id='3_".$x."' name='3_".$x."' style='width : 100%;  text-align: right;'/></td>";
                                    echo "</tr>";
                                }
                            ?>
                    </table>
                </td>
                <td>
                    <table style="width:100%" id="tgrid2" border="0">
                        <tr><b style="font-size:20px">FOC</b></tr>
                        <tr>
                             <th class="tb_head_th" style="width:175px;">Code</th>
                            <th class="tb_head_th" style="width:225px;">Name</th>
                            <th class="tb_head_th" style="width:100px;">Model</th>
                            <th class="tb_head_th" style="width:100px;">Qty</th>
                        </tr>
                        <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr class='cl1'>";                                       
                                        echo "<td class='g_col_fixed'><input type='text' class='g_input_txt  foo' id='00_".$x."' name='00_".$x."' readonly='readonly' style='width : 100%;'/>
                                            <input type='hidden' class='hidd' id='hh_".$x."' name='hh_".$x."' style='width : 100%;'/></td>";
                                        echo "<td class='g_col_fixed'><input type='text' readonly='readonly' class='g_input_txt ' id='11_".$x."' name='11_".$x."' style='width : 100%;'/></td>";
                                        echo "<td class='g_col_fixed'><input type='text' readonly='readonly' class='g_input_txt ' id='22_".$x."' name='22_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_num' id='33_".$x."' name='33_".$x."' style='width : 100%; text-align: right;'/></td>";
                                    echo "</tr>";
                                }
                            ?>
                    </table>
                </td>
            </tr>
        </table>
        <br>


        <table>
            <tr>
                <td><input type="hidden" id="code_" name="code_" title="0" /></td>                
                <td><input type="button" id="btnExit" title='Exit' /></td> 
                <td><input type="button" id="btnReset" title='Reset'></td>        
                <?php if($this->user_permissions->is_delete('m_free_issue')){ ?><td><input type="button" id="btnDelete" title='Cancel' ></td><?php } ?>       
                <?php if($this->user_permissions->is_add('m_free_issue')){ ?><td><input type="button" id="btnSave" title='Save <F8>' ></td><?php } ?>
            </tr>
        </table>

    </form>
</div>
<?php } ?>

