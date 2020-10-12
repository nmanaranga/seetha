<?php if($this->user_permissions->is_view('t_internal_transfer')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_internal_transfer.js"></script>

<div id="fade" class="black_overlay"></div>

<?php 
    if($ds['use_serial_no_items'] ){
        $this->load->view('t_serial_out.php'); 
    }
?>

<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Internal Transfer</h2>
<div class="dframe" id="mframe" style="padding-right:25px; width: 900px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_internal_transfer" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td colspan="2">
                    <fieldset>
                        <legend>           
                           Transfer From                        
                        </legend>
                        <span>From Branch</span> 
                        <span style="padding-left:2px;"><input type="text" readonly="readonly" class="input_txt" name="f_branch" id="f_branch" style="width:150px; " title="<?=$from_branch?>" /></span>
                        
                        <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
                        <input type="text"  class="hid_value"  id="branch_hid" name="branch_hid"  title="<?=$from_branch_name?>" style="width: 251px;" /> 
                        <br>
                        <span>Store</span> 
                        <span style="padding-left:44px;"><?=$store?></span>
                        <input type="text"  class="hid_value"  id="store_hid" name="store_hid"  title="" style="width: 251px;" /> 

                    </fieldset>
                </td>

                <td width='150'>&nbsp;</td>
                <td  rowspan='3'  valign="top" align="right">
                    <table>
                        <tr>
                            <td>Type</td>
                            <td>

                                <select style="width:185px;" name="t_type" id="t_type">
                                    <option value='request'>Request</option> 
                                    <option value='main_store'>Main Store</option> 
                                    <option value='branch'>Branch</option>   
                                </select>
                                <input type="hidden" id="types" name="types" title="request" />

                            </td>
                        </tr>

                        <tr>
                        <td>Sub No</td>
                        <td>  
                            <input type="hidden" class="input_active_num" name="id" id="id" style="width:100%" title="<?=$id?>" />
                            <input type="hidden" id="hid" name="hid" title="0" />
                            <input type="hidden" id="sub_hid" name="sub_hid" title="0" />                    
                            <input type="text" class="input_active_num" name="sub_no" id="sub_no" style="width: 100%;" title="<?=$sub_max_no?>"/>

                        </td>
                        </tr>


                        <tr><td>Date</td>
                        <td>
                            <?php if($this->user_permissions->is_back_date('t_internal_transfer')){ ?>
                                <input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                            <?php } else{ ?>
                                <input type="text" class="" readonly="readonly" name="ddate" id="ddate" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                            <?php } ?>
                        </td>
                        </tr>

                        <tr><td>Ref. No</td>
                        <td><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;"/></td>
                        </tr>

                    </table>   
                </td>
                <td rowspan='3'></td>
            </tr>

            <tr>
                <td colspan="2">
                    <fieldset>
                        <legend>  
                            Transfer To  Location       
                           
                        </legend> 
                        <span>Store</span> 
                        <span  style="padding-left:33px;"><?=$location_store?></span>
                        <input type="text"  class="hid_value"  id="location_store_hid" name="location_store_hid"  title="" style="width: 251px;" />  

                        <br>
                        <span>Vehicle</span> 
                        <span style="padding-left:34px;">
                            <input type="text"  class="input_txt"  id="vehicle" name="vehicle"  title="" style="width: 150px;" /> 
                            <input type="hidden"  class="input_txt"  id="v_store2" name="v_store2"/>
                        </span>
                        <input type="text"  class="hid_value"  id="vehicle_des" name="vehicle_des"  title="" style="width: 251px;" />                         
                    

                    </fieldset>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <fieldset>
                        <legend>  
                            Transfer To         
                           
                        </legend> 
                        <span>Cluster</span> 
                        <span  style="padding-left:33px;"><?=$cluster?></span>
                        <input type="text"  class="hid_value"  id="to_cluster_hid" name="to_cluster_hid"  title="" style="width: 251px;" />  


                        <br>
                         <span>Branch</span> 
                        <span id="to_branch" style="padding-left:34px;"><select></select></span>
                        <input type="text"  class="hid_value"  id="to_branch_hid" name="to_branch_hid"  title="" style="width: 251px;" />                         
                    
                       

                    </fieldset>
                </td>
            </tr>

            <tr>
                <td style="padding-left:11px"><span>Order No</span>
                <input type="text" name="order_no" id="order_no" class="input_active g_input_num" style="width:150px; margin-left:24px;"></td>
            </tr>

                        
            <tr>
                <td colspan="4" style="text-align: center;">
                   <table style="width: 100%;" id="tgrid" >
                        <thead>
                            <tr>
   <!--                              <th class="tb_head_th" width="70">Code</th>
                                <th class="tb_head_th" >Name</th> 
                                <th class="tb_head_th" >Batch</th>                              
                                <th class="tb_head_th" >QTY</th>
                                <th class="tb_head_th" >Price</th>                              
                                <th class="tb_head_th" >Amount</th>


 -->


                                <th class="tb_head_th" style="width:80px;">Code</th>
                                <th class="tb_head_th" style="width:160px;">Name</th> 
                                <th class="tb_head_th" style="width:60px;">Model</th>
                                <th class="tb_head_th" style="width:60px;">Batch</th> 
                                <th class="tb_head_th" style="width:60px;">Cost</th>
                                <th class="tb_head_th" style="width:60px;">Min Price</th>   
                                <th class="tb_head_th" style="width:60px;">Max Price</th> 
                                <th class="tb_head_th" style="width:60px;">Cur Stock</th>                           
                                <th class="tb_head_th" style="width:60px;">QTY</th>                             
                                <th class="tb_head_th" >Amount</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                             <input type='hidden' id='transtype' title='INTERNAL TRANSFER' value='INTERNAL TRANSFER' name='transtype' />
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' /></td>
                                                <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                                <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
                                                <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                                <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width:100%;style='background-color: #f9f9ec;'/></td>";

                                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='m_".$x."' name='m_".$x."' maxlength='150' style='width:100%;style='background-color: #f9f9ec;'/></td>";

                                        echo "<td width='70' style='background-color: #f9f9ec;'><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                              <input type='text' readonly='readonly' class='g_input_txt btt_".$x."' id='1_".$x."' name='1_".$x."' style='background-color: #f9f9ec;width :40px; float: right; text-align:right;' /></td>";

                                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='c_".$x."' name='c_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='min_".$x."' name='min_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='3_".$x."' name='3_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='cur_".$x."' name='cur_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                                        echo "<td width='70' ><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                              <input type='text' class='g_input_num qun chk ky qtycl".$x."' id='2_".$x."' name='2_".$x."' style='width:40px; float:right;'/></td>";
                                      
                                        echo "
                                              <input type='hidden' class='g_input_amo dis' id='qtyh_".$x."' name='qtyh__".$x."' /></td>";
                                        
                                        echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' id='t_".$x."' name='t_".$x."' class='g_input_amo tf' readonly='readonly' style='text-align:right;'/>
                                              <input type='hidden' id='subcode_".$x."' title='0' name='subcode_".$x."' />
                                        </td>";

                                    echo "</tr>";
                                }
                            ?>
                        </tbody>


                    </table>
                    <table>
                    <tr >
                        <td><span>Note</span></td>
                        <td>
                            <textarea id="note" class="input_txt" style="width:485px;" name="note" cols="50" rows="2"></textarea>
                        </td>
                        <td style="padding-left:114px;"> 
                                    <b>Net Amount</b>
                                </td>
                                <td width="1w0">
                                    <input type='text'  id='net_amount' class="hid_value g_input_amounts" title="" name='net_amount' style="margin-left:33px; width:150px;" />
                                </td>
                        </tr>
					</table>
					
				
                                
					                  
                <input type="hidden" name="srls" id="srls"/>
                <input type='hidden' id='transCode' value='42' title='42'/>
                <div style="text-align: right; padding-top: 7px; margin-right:2px;">
                    <input type="button" id="" title="Exit" />
                    <input type="button" id="btnReset" title="Reset" />
                    <?php if($this->user_permissions->is_delete('t_internal_transfer')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                    <?php if($this->user_permissions->is_re_print('t_internal_transfer')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
			        <?php if($this->user_permissions->is_add('t_internal_transfer')){ ?><input type="button" title='Save <F8>' value="Save" id="btnSave" /><?php } ?>
                    <input type="button" title="Reject" value="REject" id="btnReject" />
                    <input type="hidden" name='save_chk' value="0" title="0" id="save_chk" > 
                   <?php 
                    if($this->user_permissions->is_print('t_internal_transfer')){ ?>
                        <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                    <?php } ?> 
                      
                    <input type="hidden" id="po" name="po" title="0" />
            		<input type="hidden" id="response" name="response" title="0" />
            		<input type="hidden" id="reject" name="reject" title="0" />
                </div>
                
                </td>
            </tr>
        </table>
    </form>

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
        <input type="hidden" name='by' value='t_internal_transfer' title="t_internal_transfer" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >
        <input type="hidden" name='type' value='t_internal_transfer' title="t_internal_transfer" >         
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
        <input type="hidden" name='qno' value='' title="" id="qno" >
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
     
    </form>

</div>
<?php } ?>