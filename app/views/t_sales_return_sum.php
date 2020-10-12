<?php if($this->user_permissions->is_view('t_sales_return_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_sales_return_sum.js"></script>
<div id="testLoad"></div>
<div id="fade" class="black_overlay"></div>

<?php 
    if($ds['use_serial_no_items'] ){
        $this->load->view('t_serial_out.php'); 
    }
?>


<h2 style="text-align: center;">Sales Return With Invoice</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_sales_return_sum" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Customer</td>
                <td>
                    <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
                    <input type="text" class="input_txt" title=''  id="customer"  name="customer" style="width: 150px;">
                    
                    <input type="text" class="hid_value" title='' readonly="readonly" id="customer_id"  style="width: 368px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" style="width:100%;" title="<?=$nno;?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr><tr>
                <td>Type</td>
                <td><select name="type" id="types"> 
                        <option value="4">Cash</option>
                        <option value="5">Credit</option>
                       <!--  <option value="6">HP</option>
                        <option value="7">Internal</option> -->
                    </select>

                    
                    Invoice No 
                    <span id="get_invoice">
                    <input type="text" class="input_txt" name="inv_no" id="inv_no"  title="" />
                   </span>
                    
                    CRN No &nbsp;&nbsp;<input type="text" class="input_active_num" id="crn_no" name="crn_no" title="<?php echo $crn_no; ?>" style="width: 100px;" maxlength="25" />
                </td>
                <td style="width: 100px !important;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_sales_return_sum')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php }else{ ?>
                        <input type="text" class="" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } ?>   
                    <input type="hidden" name="buy_date" id="buy_date"/> 
                </td>
            </tr><tr>
                <td>Sales Officer</td>
                <td>
                   <input type="text" class="input_txt" id="sales_rep" title="" style="width: 150px;" name="sales_rep"/>
                   <input type="text" class="hid_value" id="sales_rep2" title=" " style="width: 368px;" readonly="readonly" />
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%;" maxlength="25"/></td>
            </tr><tr>
                <td colspan="4" style="text-align: center;">
               

                	    <table style="width: 100% ;" id="tgrid2" >
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 120px;">Item Code</th>
                                <th class="tb_head_th">Name</th>
                                <th class="tb_head_th" style="width: 60px;">Return Qty</th>
                                <th class="tb_head_th" style="width: 60px;">Batch</th>
                                <th class="tb_head_th" style="width: 70px;">QTY</th>
                                <th class="tb_head_th" style="width: 70px;">Price</th>
                                <th class="tb_head_th" style="width: 70px;">Dis%</th>
                                <th class="tb_head_th" style="width: 70px;">Discount</th>
                                
                                <th class="tb_head_th" style="width: 70px;">Amount</th>
                            </tr>
                        </thead><tbody>
                        <input type='hidden' id='transtype' title='SALES RETURN' value='SALES RETUEN' name='transtype' />
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr class='cl1'>";
                                        echo "<td style=''><input type='hidden' name='h1_".$x."' id='h1_".$x."' title='0' />
                                                <input type='text' readonly='readonly' class='g_input_txt g_col_fixed' id='01_".$x."' name='01_".$x."' style='width : 100%;' /></td>
                                                <input type='hidden' id='setserial1_".$x."' title='0' name='setserial1_".$x."' />
                                                <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
                                                <input type='hidden' id='numofserial1_".$x."' title='' name='numofserial1_".$x."' />
                                                <input type='hidden' id='itemcode1_".$x."' title='0' name='itemcode1_".$x."' />  ";

                                        echo "<td><input type='text' readonly='readonly' class='g_input_txt g_col_fixed'  id='n1_".$x."' name='n1_".$x."' maxlength='150' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' readonly='readonly' class='g_input_num2  g_col_fixed' id='rq_".$x."' name='rq_".$x."' style='width : 100%;'/></td>";
                                         echo "<td><input type='text' readonly='readonly' class='g_input_num2 qty qun g_col_fixed' id='bt1_".$x."' name='bt1_".$x."' style='width : 100%;'/></td>";
                                        
                                        echo "<td style=''><input type='text' readonly='readonly' class='g_input_num2 qty qun g_col_fixed fe' id='11_".$x."' name='11_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' readonly='readonly' class='g_input_amo price g_col_fixed' id='21_".$x."' name='21_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' readonly='readonly' class='g_input_amo dis_pre g_col_fixed' id='31_".$x."' name='31_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' readonly='readonly' class='g_input_amo dis g_col_fixed' id='41_".$x."' name='41_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_amo dis' id='rmax1_".$x."' name='rmax1_".$x."' /></td>";                                      
                                        echo "<td ><input type='text' readonly='readonly' class='g_input_amo amount g_col_fixed' id='51_".$x."' name='51_".$x."' style='width : 100%;'/>
                                        <input type='hidden' id='is_frees_".$x."' title='0' name='is_frees_".$x."' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        </table>
              




                    <table style="width: 100%;" id="tgrid1">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 120px;">Item Code</th>
                                <th class="tb_head_th">Name</th>
                                
                                <th class="tb_head_th" style="width: 50px;">Batch</th>
                                <th class="tb_head_th" style="width: 60px;">QTY</th>
                                <th class="tb_head_th" style="width: 70px;">Price</th>
                                <th class="tb_head_th" style="width: 60px;">Dis%</th>
                                <th class="tb_head_th" style="width: 70px;">Discount</th>
                                <th class="tb_head_th" style="width: 70px;">Amount</th>
                                <th class="tb_head_th" style="width: 100px;">Reason</th>
                            </tr>
                        </thead><tbody >
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td style=''><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt g_col_fixed del_item' id='0_".$x."' name='0_".$x."' style='width : 100%;' /></td>
                                                <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                                <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                                <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />  ";

                                        echo "<td  ><input type='text' class='g_input_txt g_col_fixed'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;'/></td>";
                                         echo "<td><input type='text' readonly='readonly' class='g_input_num2 qty qun btt_".$x."' id='bt_".$x."' name='bt_".$x."' style='width : 100%;'/></td>";
                                        
                                        echo "<td style=''><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                        <input type='text' class='g_input_num2 qty qun vali qtycl qtycl".$x." g_col_fixed".$x."' id='1_".$x."' name='1_".$x."' style='width : 40px;float: right;'/></td>";
                                       
                                        echo "<td style=''><input type='text' class='g_input_amo price vali' id='2_".$x."' name='2_".$x."' style='width : 100%;'/></td>";
                                        echo "<td class='g_col_fixed'><input type='text' class='g_input_amo dis_pre' id='3_".$x."' name='3_".$x."' readonly='readonly' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_amo dis g_col_fixed' id='4_".$x."' name='4_".$x."' style='width : 100%;'/>
                                       		 <input type='hidden' class='g_input_amo dis' id='rmax_".$x."' name='rmax_".$x."'/></td>";                                      
                                        echo "<td ><input type='text' readonly='readonly' class='g_input_amo amount g_col_fixed' id='5_".$x."' name='5_".$x."' style='width : 100%;'/>
                                        <input type='hidden' class='g_input_amo dis' id='21h_".$x."' name='21h_".$x."'/></td>";
                                        echo "<td><input type='text' class='g_input_txt return_reason' id='6_".$x."' name='6_".$x."' style='width : 100%;' maxlength='4'/>
                                        <input type='hidden' class='g_input_txt' id='ret_".$x."' name='ret_".$x."'/>
                                        <input type='hidden' id='subcode_".$x."' title='0' name='subcode_".$x."' />
                                        <input type='hidden' id='is_free_".$x."' title='0' value='0' name='is_free_".$x."' />
                                        </td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        </table>



                        <table style="width:100%;" border="0">
                        <tr>
                            <td colspan='2' rowspan='6' style="width:50%;">
                                <fieldset style="background:transparent;">
                                        <legend>Other Amount</legend>
                                            
                                            <table id="tgrid2" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th class="tb_head_th" style="width: 100px;">Type</th>
                                                        <th class="tb_head_th" style="width: 300px;">Description</th>
                                                        <th class="tb_head_th" style="width: 100px;">Rate%</th>
                                                        <th class="tb_head_th" style="width: 100px;">Amount</th>
                                                    </tr>   
                                                </thead>
                                                    <tbody>
                                                        <?php
                                                            for($x=0; $x<5; $x++){
                                                                echo "<tr>";
                                                                echo "<td style=''><input type='hidden' name='hh_".$x."' id='hh_".$x."' title='0' />
                                                                <input type='hidden' name='hhh_".$x."' id='hhh_".$x."' title='0' />
                                                                        <input type='text' class='g_input_txt foo' id='000_".$x."' name='000_".$x."' readonly='readonly' style='width : 100%;' /></td>";
                                                                echo "<td ><input type='text' class='g_input_txt g_col_fixed'  id='nnn_".$x."' name='nnn_".$x."' maxlength='150' style='width : 100%;'/></td>";
                                                                echo "<td style=''><input type='text' class='g_input_amo g_col_fixed rate' id='111_".$x."' name='111_".$x."' style='width : 100%;'/></td>";
                                                                echo "<td style=''><input type='text' class='g_input_amo aa' id='222_".$x."' name='222_".$x."' style='width : 100%;'/></td>";
                                                                echo "</tr>";
                                                            }
                                                        ?>
                                                    </tbody>
                                                <tr>
                                                </tr>

                                            </table>
                                           
                                        </fieldset>
                            </td>
                            <td style="width:10%;">&nbsp;</td>
                            <td style="width:5%;">&nbsp;</td>
                            <td style="width:5%;">&nbsp;</td>
                            <td style="width:15%;"><b>Gross</b></td>
                            <td style="width:30%;"><input type='text' class='hid_value g_input_amounts' id='total2' readonly="readonly" name='total' title="" style="width:100%;"/></td> 
                        </tr>  
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="width:15%;"><b>Discount</b></td>
                            <td style="width:15%;"><input type='text' class='hid_value g_input_amounts' id='discount' readonly="readonly" title="" name='discount' style="width:100%;"/></td> 
                        </tr> 
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="width:15%;"><b>Additional Amount</b></td>
                            <td style="width:15%;"><input type='text' class='hid_value g_input_amounts' title="" id='addi_amount' readonly="readonly" name='addi_amount' style="width:100%;"/></td> 
                        </tr>   
                        <tr>
                            <input type='hidden' name='additional_add' id="additional_add" />
                            <input type='hidden' name='additional_deduct' id="additional_deduct" />
                                                
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="width:15%;"><b>Net Amount</b></td>
                            <td style="width:15%;"><input type='text' class='hid_value g_input_amounts' title="" id='net' readonly="readonly" name='net_amount' style="width:100%;"/></td> 
                        </tr>   
                        <tr>
                            <td colspan='5'>&nbsp;</td> 
                        </tr> 
                        <tr>
                            <td colspan='5'>&nbsp;</td> 
                        </tr>  

                        <tr>
                            <td style="width:15%;"><b>Store</b></td>
                            <td style="width:15%;" colspan='6'><?php echo $stores;?></td> 
                        <tr>
                            <td style="width:15%;"><b>Memo</b></td>
                            <td ><input type="text" class="input_txt" name="memo" id="memo" title="" maxlength="255" style="width:100%;"/></td> 
                            <td colspan='5'>
                                <div style="text-align:right;">
                                            <input type="button" id="btnExit" title="Exit" />
                                            <input type="button" id="btnReset" title="Reset" />
                                            <?php if($this->user_permissions->is_delete('t_sales_return_sum')){ ?><input type="button" id="btnDelete5" title="Cancel" /><?php } ?>
                                            <?php if($this->user_permissions->is_re_print('t_sales_return_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                                            <input type="button"  id="btnSavee" title='Save <F8>' />
                                            <?php if($this->user_permissions->is_add('t_sales_return_sum')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>
                                            <input type="hidden" name="srls" id="srls"/> 
                                            <input type='hidden' id='transCode' value='8' title='8'/>
                                            <?php if($this->user_permissions->is_approve('t_sales_return_sum')){ ?><input type="button"  id="btnApprove" title='Approve' /><?php } ?>
                                            <input type='hidden' id='app_status' name='approve' title='1' value='1'/>
                                        </div>
                            </td>
                        </tr> 
                    </table>
                    
                </td>
            </tr>
        </table>
        <?php 
        if($this->user_permissions->is_print('t_sales_return_sum')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
        <?php } ?> 
    </form>



    	<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_sales_return_sum' title="t_sales_return_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='sales_return' title="sales_return" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='is_duplicate' value='0' title="0" id="is_duplicate" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
        
        </form>


</div>
<?php } ?>