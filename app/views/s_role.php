<?php if($this->user_permissions->is_view('s_role')){ ?>
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
<script type="text/javascript" src="<?=base_url()?>js/s_role.js"></script>

<h2 style="text-align: center;">User Role</h2>
    <form  method="post" id="_form" action="<?=base_url()?>index.php/main/save/s_role" >
        <div class="dframe" id="mframe" style="text-align: center;width:960px;">
        <table width="100%">
            <tr>
                <td width="107">Code</td>
                <td width="1687"><input name="code" type="text" class="input_txt" id="code" style="width: 150px; text-transform:uppercase;" title="code" /></td>
                <td width="1"><input type="hidden" name="code_" id="code_" title="0" /></td><td width="0"></td>
            </tr><tr>
                <td>Description </td>
                <td><input name="des" id="des" type="text" class="input_txt" id="des" style="width: 150px;" title="Description" />
                <!--<input name="text" type="text" class="input_txt" id="sup_des"  style="width: 300px;" title='' readonly="readonly" /></td>-->
                <td>&nbsp;</td>
            </tr><tr>
                <td>Branch</td>
                <td> <?//=$branch;?></td>
                <td>&nbsp;</td>
            </tr><tr>
                <td colspan="2" valign="top" class="content" style="width: 180px;">
                <div class="form" id="form">    
            <?php if(isset($_GET['key'])){ echo "<span style='color: blue;' >".base64_decode($_GET['key'])."</span>"; } ?>
            <table style="width: 100%" id="tgrid">
                <thead>
                <tr>
                    <th class="tb_head_th" style='width : 70px' >Module Id</th>
                    <th class="tb_head_th" >Name</th> 
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_view' ".$t1." />View</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_add' ".$t1." />Add</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_edit' ".$t1." />Edit</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_delete' ".$t1." />Delete</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_print' ".$t1." />Print</th>
                    <th class="tb_head_th"  style='width : 70px'><input type='checkbox' id='all_r_print' ".$t1." />Re-Print</th>
                    <th class="tb_head_th"  style='width : 80px'><input type='checkbox' id='all_back_date' ".$t1." />Back Date</th>
                </tr>
                </thead>
                <?php
                    // foreach($table_data as $r){
                    //     echo "<tr>";
                    //         echo "<td><input type='text' readonly='readonly' name='m_code".$r->m_code."' class='g_input_num' id='m_code".$r->m_code."' style='width : 100%' title='".$r->m_code."'/></td>";
                    //         echo "<td><input type='text' readonly='readonly' name='m_description".$r->m_code."' class='g_input_txt' id='m_description".$r->m_code."' style='width : 100%' title='".$r->m_description."'/></td>";   
                    //         echo "<td style='text-align: center;'><input type='checkbox' name='is_view".$r->m_code."' id='is_view".$r->m_code."' class='ob_a' title='1' /></td>";
                    //         echo "<td style='text-align: center;'><input type='checkbox' name='is_add".$r->m_code."' id='is_add".$r->m_code."' class='ob_b' title='1'/></td>";
                    //         echo "<td style='text-align: center;'><input type='checkbox' name='is_edit".$r->m_code."' id='is_edit".$r->m_code."' class='ob_e' title='1'/></td>";
                    //         echo "<td style='text-align: center;'><input type='checkbox' name='is_delete".$r->m_code."' id='is_delete".$r->m_code."' class='ob_d' title='1'/></td>";
                    //         echo "<td style='text-align: center;'><input type='checkbox' name='is_print".$r->m_code."' id='is_print".$r->m_code."' class='ob_f' title='1'/></td>";
                    //         echo "<td style='text-align: center;'><input type='checkbox' name='is_re_print".$r->m_code."' id='is_re_print".$r->m_code."' class='ob_g' title='1'/></td>";
                    //         echo "<td style='text-align: center;'><input type='checkbox' name='is_back_date".$r->m_code."' id='is_back_date".$r->m_code."' class='ob_h' title='1'/></td>";
                           
                    //     echo "</tr>";
                   //}
                ?>
            </table>
            <div style="text-align: right; padding: 7px;">
                <input type="button" id="btnExit" title='Exit' />
                <input type="button" id="btnReset" title="Reset" />

                <input type="button" id="btnSave" title='Save <F8>' />
     
            </div>
                </div>
                </td>
                </tr>
        </table>
    </form>
</div>

<?php } ?>