<?php if($this->user_permissions->is_view('t_internal_transfer_order')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_internal_transfer_order.js"></script>


<h2 style="text-align: center;">Internal Transfer Order</h2>
<div class="dframe" id="mframe" style="width: 980px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_internal_transfer_order" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Type</td>
                <td>
                	<select style="width:185px;" name="t_type" id="t_type">
                        <option value='branch'>Branch</option>   
                        <option value='request'>Request</option> 
                        <option value='main_store'>Main Store</option> 
                    </select>
                    <input type="hidden" id="types" name="types" title="branch" />
                </td>
                <td style="width: 50px;">Sub No</td>
                <td>
                    <input type="hidden" class="input_active_num" name="id" id="id" title="<?=$max_no?>"/>
                    <input type="hidden" id="hid" name="hid" title="0" />
                    <input type="hidden" id="sub_hid" name="sub_hid" title="0" />
                    
                    <input type="text" class="input_active_num" name="sub_no" id="sub_no" title="<?=$sub_max_no?>"/>

                    

                </td>

            </tr><tr>
                <td>Cluster</td>
                <td>

                   <?=$cluster;?>
                    
                    

                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">

                    <?php if($this->user_permissions->is_back_date('t_internal_transfer_order')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width: 100%; text-align:right;"/></td>
                    <?php }else{?>    
                        <input type="text" class="" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width: 100%; text-align:right;"/></td>
                    <?php } ?>

                   
                </td>
            </tr>

            <tr>
                <td style="width: 100px;">Branch</td>
                <td>
                  <select style="width:185px;" id="branch" name="to_bc"></select>
                   
                </td>
         
                <td style="width: 100px;">Ref no</td>
                <td style="width: 100px;">
                    <input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;"/>
                </td>
           
            </tr>


          <tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 960px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 30px;">No</th>
                                <th class="tb_head_th" style="width: 110px;">Item Code</th>
                                <th class="tb_head_th">Item Name</th>
                                <th class="tb_head_th"  style="width: 80px;">Module</th>
                                <th class="tb_head_th"  style="width: 80px;">Cost</th>
                                <th class="tb_head_th"  style="width: 80px;">Min Price</th>
                                <th class="tb_head_th"  style="width: 80px;">Max Price</th>
                                <th class="tb_head_th"  style="width: 80px;">Current Stock</th>
                                <th class="tb_head_th" style="width: 40px;">QTY</th>
                                <th class="tb_head_th" style="width: 80px;">Total Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    $y = $x + 1;
                                    echo "<tr>";
                                        echo "<td style='width:20px;'><input type='text' style='width:100%;' class='g_input_num g_col_fixed' id='6_".$x."' name='6_".$x."' title='$y' readonly='readonly'/></td>";

                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                              <input type='text'     style='width:100%;' class='g_input_txt fo' id='0_".$x."' name='0_".$x."'/></td>";
                                        echo "<td '><input type='text' style='width:100%' class='g_input_txt g_col_fixed' id='n_".$x."' name='n_".$x."' readonly='readonly' style='width:100%;'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_amo g_col_fixed' id='1_".$x."' name='1_".$x."' readonly='readonly'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_amo g_col_fixed' id='7_".$x."' name='7_".$x."' readonly='readonly'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_amo g_col_fixed' id='8_".$x."' name='8_".$x."' readonly='readonly'/></td>";
               
                                        
                                        echo "<td><input type='text' style='width:100%;' class='g_input_amo g_col_fixed' id='2_".$x."' name='2_".$x."' readonly='readonly'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_num g_col_fixed qty' id='3_".$x."' name='3_".$x."' readonly='readonly'/></td>";
                                        echo "<td style='width:40px;'><input type='text' style='width:100%;' class='g_input_num price' id='4_".$x."' name='4_".$x."' /></td>";
                                        echo "<td><input type='text' readonly='readonly' style='width:100%;' class='g_input_amo g_col_fixed amount' id='5_".$x."' name='5_".$x."' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                               
                                <td colspan='2' align="right"><b>Total</b> &nbsp; <input type='text' class='hid_value g_input_amounts' id='total2' readonly="readonly" name='total' style="margin-top:15px;" /></td>
                            </tr>
                        </tfoot>
                        
                    </table>
                    <div style="text-align: left; padding-top: 7px;">
                        Note <input type="text" style="width:600px;border:1px solid black;" name="note" id="note"/>
                    </div>
                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_internal_transfer_order')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_internal_transfer_order')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>                     
                        <?php if($this->user_permissions->is_add('t_internal_transfer_order')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>
                       
                    </div>
                </td>
            </tr>
        </table>
    </form>


     <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_internal_transfer_order' title="t_internal_transfer_order" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='t_internal_transfer_order' title="t_internal_transfer_order" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='rep_sup' value='' title="" id="rep_sup" >
                 <input type="hidden" name='rep_ship_bc' value='' title="" id="rep_ship_bc" >
                 <input type="hidden" name='rep_date' value='' title="" id="rep_date" >
                 <input type="hidden" name='rep_deliver_date' value='' title="" id="rep_deliver_date" >
                 <input type="hidden" name='sub_qno' value='' title="" id="sub_qno">
        
        </form>

</div>
<?php } ?>