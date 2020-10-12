<?php if($this->user_permissions->is_view('t_po_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_hp.js"></script>


<h2 style="text-align: center;">Hire Purchase</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_internal_transfer_order" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Scheme</td>
                <td>
                	<input type="text" class="input_txt ld" title="" id="department" name="department" data='r_department'/>
                    <input type="text" class="hid_value"  readonly="readonly" id="department_des"  style="width: 250px;">
                    <input type="hidden" class="hid_value" id="dep_codegen">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>"/>
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr><tr>
                <td>Category</td>
                <td>
                    <input type="text" class="input_txt ld" title="" id="department" name="department" data='r_department'/>
                    <input type="text" class="hid_value"  readonly="readonly" id="department_des"  style="width: 250px;">
                    <input type="hidden" class="hid_value" id="dep_codegen">
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr>

            <tr>
                <td style="width: 100px;">Agreement No</td>
                <td>
                  
                   <input type="text" class="input_txt">
                </td>
         
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%;"/></td>
           
            </tr>

             <tr>
                <td style="width: 100px;">Customer</td>
                <td>
                    <input type="text" id="customer" class="input_active" title="" name="customer" />
                    <input type="text" class="hid_value" id="customer_id"  title="" readonly="readonly" style="width:324px;" />
                </td>
         
                <td style="width: 100px;"></td>
                <td style="width: 100px;"></td>
           
            </tr>

            <table style="width:100%;margin-bottom:20px;margin-top:20px;">

                <tr>
                    <td colspan="2">
                        <div id="tabs" style="width:440px">
                            <ul>
                                <li><a href="#tabs-1" >Guarantor 1</a></li>
                                <li><a href="#tabs-2" >Guarantor 2</a></li>
                               
                            </ul>
                            <div id="tabs-1">
                                <input type="text" class="input_txt ld" title="" id="department" name="department" data='r_department'/>
                                <input type="text" class="hid_value"  readonly="readonly" id="department_des"  style="width: 250px;">
                                <input type="hidden" class="hid_value" id="dep_codegen">
                            </div>
                            <div id="tabs-2">
                                <input type="text" class="input_txt ld" title="" id="department" name="department" data='r_department'/>
                                <input type="text" class="hid_value"  readonly="readonly" id="department_des"  style="width: 250px;">
                                <input type="hidden" class="hid_value" id="dep_codegen">
                            </div>
                          
                           
                        </div>
                    </td>
                    <td colspan="2">
                        <div id="tabs2" style="width:440px">
                            <ul>
                                <li><a href="#tabs-3" >Store</a></li>
                               
                            </ul>
                            <div id="tabs-3">
                                <?php echo $stores; ?>
                                <input type="text"  id="store_id" style="width:250px;" class="hid_value" title="" readonly="readonly"/>
                            </div>
                           
                           
                        </div>
                        
                    </td>

                </tr>

            </table>

            

          <tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                
                                <th class="tb_head_th" style="width: 80px;">Item Code</th>
                                <th class="tb_head_th">Item Name</th>
                                <th class="tb_head_th"  style="width: 80px;">Module</th>
                                <th class="tb_head_th"  style="width: 80px;">Batch</th>
                                <th class="tb_head_th"  style="width: 40px;">Qty</th>
                                <th class="tb_head_th"  style="width: 40px;">FOC</th>
                                <th class="tb_head_th"  style="width: 80px;">Price</th>
                                <th class="tb_head_th"  style="width: 60px;">Discount (Value)</th>
                                <th class="tb_head_th"  style="width: 60px;">Discount (%)</th>
                                <th class="tb_head_th" style="width: 80px;">Amount</th>
                                <th class="tb_head_th" style="width: 40px;">Warrenty</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    $y = $x + 1;
                                    echo "<tr>";
                                        

                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                              <input type='text'     style='width:100%;' class='g_input_txt fo' id='0_".$x."' name='0_".$x."'/></td>";
                                        echo "<td '><input type='text' style='width:100%' class='g_input_txt g_col_fixed' id='n_".$x."' name='n_".$x."' readonly='readonly' style='width:100%;'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_num g_col_fixed' id='1_".$x."' name='1_".$x."' readonly='readonly'/></td>";
                                        echo "<td class='g_col_fixed'><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float:left;height:6px;width:6px;cursor:pointer'/><input type='text' style='width:70px;' class='g_input_num g_col_fixed' id='6_".$x."' name='7_".$x."' readonly='readonly'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_num qty' id='7_".$x."' name='7_".$x."' /></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_num ' id='8_".$x."' name='8_".$x."'/></td>";
               
                                        
                                        echo "<td><input type='text' style='width:100%;' class='g_input_num price g_col_fixed' id='2_".$x."' name='2_".$x."' readonly='readonly'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_num dis' id='3_".$x."' name='3_".$x."' /></td>";
                                        echo "<td><input type='text' style='width:100%;' class='g_input_num g_col_fixed qty' id='9_".$x."' name='9_".$x."' readonly='readonly'/></td>";
                                        echo "<td style='width:40px;'><input type='text' style='width:100%;' class='g_input_amo  amount  g_col_fixed' id='4_".$x."' name='4_".$x."' readonly='readonly'/></td>";
                                        echo "<td><input type='text' readonly='readonly' style='width:100%;' class='g_input_amo' id='5_".$x."' name='5_".$x."' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                               
                                <td colspan='2' align="right"><b>Total</b> &nbsp; <input type='text' class='hid_value g_input_amounts' id='total2' readonly="readonly" name='total' style="margin-top:15px;margin-right:70px;width:80px;" /></td>
                            </tr>
                        </tfoot>
                        
                    </table>
                    <div style="text-align: left; padding-top: 7px;">
                        <table>
                            <tr>
                                <td>Document Cheque</td>
                                <td><input type="text" class="input_txt"></td>
                            </tr>
                              <tr>
                                <td>Down Payment</td>
                                <td><input type="text" class="input_active_num"></td>
                            </tr>

                            <tr>
                                <td>Balance</td>
                                <td><input type="text" class="input_active_num"></td>
                            </tr>


                        </table>
                    </div>
                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                       
                        <input type="button"  id="btnSave" title='Save <F8>' />
                       
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
        
        </form>

</div>
<?php } ?>