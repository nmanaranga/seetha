<?php if($this->user_permissions->is_view('t_adjustment_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_adjustment_sum.js"></script>

<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Stock Adjustment</h2>
<div class="dframe" id="mframe" style="padding-right:25px; width:1030px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_adjustment_sum" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Store</td>
                <td>
                  <span><?=$store;?></span>                    
                    <input type="text" class="hid_value" title='' readonly="readonly" id="store"  style="width: 368px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="max_no" id="max_no" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr>

            <tr>
                <td>Memo</td>
                <td><input type="text" class="input_txt" id="memo" name="memo" title="" style="width: 520px;" maxlength="25" />
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_adjustment_sum')){ ?> 
                        <input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" />
                    <?php }else{ ?>
                         <input type="text" class="input_active_num" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" />
                    <?php } ?>  
                </td>      
            </tr>
            <tr>
                <td colspan="2">
<!--                <fieldset>
                        <legend>Select Item</legend>
                        <table>
                            <tr>
                                <td>
                                <input type="radio" name="item" value="item_all" title="item_all" id="item_all" />All Item 
                                <input type="radio" name="item" value="item_cat" title="item_cat" id="item_cat" />Category
                                    <td><input type="text" class="input_txt" title="" id="main_category" name="main_category"/></td>
                            <td colspan="2"><input type="text" class="hid_value"  readonly="readonly" id="main_category_des" style="width: 320px;"></td>
                                </td>   
                            </tr>
                        </table>
                    </fieldset> -->
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%;" maxlength="25"/></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 100%;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Code</th>
                                <th class="tb_head_th" style="width: 150px;">Name</th>
                                <th class="tb_head_th" style="width: 50px;">Batch No</th>
                                <th class="tb_head_th" style="width: 50px;">Cur QTY</th>
                                <th class="tb_head_th" style="width: 50px;">1st Count</th>
                                <th class="tb_head_th" style="width: 50px;">2nd Count</th>
                                <th class="tb_head_th" style="width: 50px;">3rd Count</th>
                                <th class="tb_head_th" style="width: 50px;">Difference</th>
                                <th class="tb_head_th" style="width: 70px;">Cost</th>
                                <th class="tb_head_th" style="width: 70px;">Amount</th>

                                <th class="tb_head_th" style="width: 50px;">Is Serial</th>
                                <th class="tb_head_th" style="width: 50px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr class='cv'>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt' readonly='readonly' id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;'/></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_num qun' readonly='readonly' id='1_".$x."' name='1_".$x."' style='width : 100%;'/></td>";
                                        echo "<td  style='background-color: #f9f9ec;'><input type='text' class='g_input_num amo' readonly='readonly' id='2_".$x."' name='2_".$x."' style='width : 100%;'/></td>";
                                        
                                        echo "<td><input type='text' class='g_input_num ss' id='1count_".$x."' name='1count_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num ss' id='2count_".$x."' name='2count_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num ss' id='3count_".$x."' name='3count_".$x."' style='width : 100%;'/></td>";
                                        
                                        echo "<td  style='background-color: #f9f9ec;'><input type='text' class='g_input_txt dis_pre qunn' readonly='readonly' id='4_".$x."' name='4_".$x."' readonly='readonly' style='width : 100%;text-align:right;'/></td>
                                                <input type='hidden' class='g_input_num dis_pre' id='44_".$x."' name='44_".$x."'/>
                                                <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                                <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                                <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";
                                        echo "<td><input type='text' class='g_input_amo dis' readonly='readonly' id='5_".$x."' name='5_".$x."' style='width : 100%; background-color: #f9f9ec;'/></td>";
                                       
                                        echo "<td> <input type='text' class='g_input_amo tf' readonly='readonly' id='t_".$x."' name='t_".$x."' style='width : 100%;'/></td>";
                                               
                                        echo "<td>
                                                <input type='checkbox' id='serial_".$x."' name='serial_".$x."' style='width : 100%;' class='sh'/>
                                                <input type='hidden' id='serialhid_".$x."' name='serialhid_".$x."'/>
                                              </td>";
                                        echo "<td id='sbar_".$x."'>
                                                <input type='checkbox' id='status_".$x."' name='status_".$x."' style='width : 100%;' class='st'/>
                                                <input type='hidden' class='stcl' id='statushid_".$x."' name='statushid_".$x."'/>
                                            </td>";
                                       
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <table style="width:100%">
                        <tr>
                            <td colspan="5">
                                <div style="text-align:left; padding-top: 7px;">
                                    <input type="button" id="btnExit" title="Exit" />
                                    <input type="button" id="btnReset" title="Reset" />
                                    <?php if($this->user_permissions->is_delete('t_adjustment_sum')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
                                    <?php if($this->user_permissions->is_re_print('t_adjustment_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                                    <input type="hidden" name="srls" id="srls"/>  
                                    <?php if($this->user_permissions->is_add('t_adjustment_sum')){ ?><input type="button" id="btnSave_f" title="Save" /><?php } ?>
                                    <?php if($this->user_permissions->is_add('t_adjustment_sum')){ ?><input type="button"  id="btnSave" title='Update Stock' /><?php } ?>
                                </div>
                            </td>
                            <td><b>Net Amount<b></td>
                            <td>
                                <input type='text' class='hid_value g_input_amounts' id='net_amount' readonly="readonly" name='net_amount' style="margin-right:0px;" />
                            </td>
                        </tr>
                        <?php 
                        if($this->user_permissions->is_print('t_adjustment_sum')){ ?>
                            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                        <?php } ?> 
                    </table>
                </table>
            </td>
        </tr>
    </table>
</form>

 <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                 <input type="hidden" name='by' value='t_adjustment_sum' title="t_adjustment_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='adjustment_sum' title="adjustment_sum" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='mem' value='' title="" id="mem" >
                 <input type="hidden" name='str' value='' title="" id="str" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
             
</form>

</div>
<?php } ?>