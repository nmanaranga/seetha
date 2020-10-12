<?php if($this->user_permissions->is_view('r_credit_card_rate')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/r_credit_card_rate.js'></script>
<h2>Credit Card Rate Setup</h2>
<div  style="width:525px;margin-left:50px;">
    <table style="width:525px;" id="tbl1">
        <tr>
            <td valign="top" class="content" style="width:50%;">
                <div class="form" id="form">
                    <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_credit_card_rate" >
                        <table style="width:520px;" id="tbl2">
                            <tr>   
                                <td colspan="2" style="text-align:right;">No <input type="text" style="margin-right:5px;margin-left:30px;" class="input_active_num" name="id" id="id" title="<?=$max_no?>" maxlength="10" style="width:150px;" readonly="readonly"/><input type="hidden" id="hid" name="hid" title="0" /></td>
                            </tr>
                            <tr>  
                                <td colspan="2" style="text-align:right;">Date <input type="text" style="margin-right:5px;margin-left:30px;"  class="input_date_down_future" readonly="readonly" style="width:150px;" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:left;">Terminal ID <input type="text" class="input_active" style="margin-right:5px;margin-left:10px;text-transform:uppercase"   style="width:150px;" name="terminal_id" id="terminal_id" maxlength="20"/></td>     
                            </tr>
                            <tr>
                                <td colspan='2'>Bank &nbsp;<input type="text" class="input_active" id="bank_id" name="bank_id" style="width:150px;margin-left:42px;" title="" />

                                    <input type="text" class="hid_value" id="bank" title="" style="width:280px;"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table style="width: 650px;" id="tgrid">
                                        <thead>
                                            <tr>
                                                <th class="tb_head_th" style="width:70px;">Month</th>
                                                <th class="tb_head_th" style="width:70px;">Rate</th>
                                                <th class="tb_head_th" style="width:150px;">Merchant ID</th>
                                                <th class="tb_head_th" style="width:120px;">Acc Code</th>
                                                <th class="tb_head_th">Account Name</th>
                                                <th class="tb_head_th">Active</th>
                                            </tr>
                                        </thead><tbody>
                                            <?php
                                            for($x=0; $x<12; $x++){
                                                echo "<tr>";
                                                echo "<td ><input type='text' class='g_input_num'  id='month_".$x."' name='month_".$x."' style='width:100%;text-align:Right;'/></td>";
                                                echo "<td ><input type='text' class='g_input_amo'  id='rate_".$x."' name='rate_".$x."' style='width:100%;text-align:Right;'/></td>";
                                                echo "<td ><input type='text' class='g_input_txt'  id='merchant_id_".$x."' name='merchant_id_".$x."' style='width:100%; text-align:left;' maxlength='20'/></td>";
                                                echo "<td ><input type='text' class='g_input_amo'  id='acc_".$x."' name='acc_".$x."' style='width:100%;text-align:left;'/></td>";
                                                echo "<td ><input type='text' class='hid_value'  id='acc_name_".$x."' name='acc_name_".$x."' style='width:100%;text-align:left;'/></td>";
                                                echo "<td ><input type='checkbox' id='active_".$x."' name='active_".$x."' value='0' title='0' /></td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right" colspan="2">
                                    <input type="button" id="btnExit" title='Exit' />
                                    <input type="button" id="btnPrint" title="Print" />
                                    <?php if($this->user_permissions->is_add('r_credit_card_rate')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?> 
                                        <input type="button" id="btnReset" title='Reset'>                       
                                    </td>
                                </tr>
                            </table><!--tbl2-->
                            <input type="hidden" id="code_" name="code_" title="0">
                        </form><!--form_-->
                    </div><!--form-->
                </td>
                <td valign="top" class="content" style="width:600px;">
                    <div class="form" id="form" style="width:500px;" >
                        <table>
                            <tr>
                                <td style="padding-right:64px;"><label>Search</label></td>
                                <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
                            </tr>
                        </table>
                        <div id="grid_body"><?=$table_data;?></div>
                    </div>
                </td>
            </tr>
            <?php 
            if($this->user_permissions->is_print('r_credit_card_rate')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
            <?php } ?> 
        </table><!--tbl1-->
    </div>

    <?php } ?>

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

       <input type="hidden" name='by' value='r_credit_card_rate' title="r_credit_card_rate" class="report">
       <input type="hidden" name='page' value='A4' title="A4" >
       <input type="hidden" name='orientation' value='P' title="P" >
       <input type="hidden" name='type' value='purchase' title="purchase" >
       <input type="hidden" name='header' value='false' title="false" >
       <input type="hidden" name='qno' value='' title="" id="qno" >          
   </form>