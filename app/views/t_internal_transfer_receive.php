<?php if($this->user_permissions->is_view('t_internal_transfer_receive')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_internal_transfer_receive.js?<?=rand();?>"></script>

<div id="fade" class="black_overlay"></div>

<?php 
if($ds['use_serial_no_items'] ){
    $this->load->view('t_serial_out.php'); 
}
?>

<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Internal Transfer Receive</h2>
<div class="dframe" id="mframe" style="padding-right:25px; width: 1040px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_internal_transfer_receive" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td colspan="3">
                    <fieldset>
                        <legend>Receive From </legend>
                       
                        <span>Mode</span> 
                        <span style="padding-left:38px;">
                            <select style="width:150px; " id="mode" name="mode">
                                <option value="0">Internal</option>
                                <option value="1">Direct</option>
                                <option value="2">Return</option>
                            </select>
                            <input type="hidden" name="tr_mode" id="tr_mode">
                            <input type="hidden" id="mode_hid" name="mode_hid" title="0" />                    
                            <input type="text" class="input_active_num" name="mode_no" id="mode_no" style="width: 20%;"/>

                        </span>
                        <br>
                       
                        <span>Request No</span> 
                        <span style="padding-left:2px;"><input type="text" readonly="readonly" class="input_txt" name="request_no" id="request_no" style="width:150px; " title="" /></span>
                        <input type="hidden"  id="max_nno" name="max_nno" /> 
                        <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
                        <input type="text"  class="hid_value"  id="request_hid" name="request_hid"  title="" style="width: 251px;" /> 
                        <br>
                        <span>Branch</span> 
                        <span style="padding-left:29px;"><input type="text" readonly="readonly" class="input_txt" name="cluster" id="cluster" style="width:150px; " title="" /></span>
                        <input type="text"  class="hid_value"  id="cluster_hid" name="cluster_hid"  title="" style="width: 251px;" /> 
                        <input type="hidden" id="hid_cl" name="hid_cl"  title="" />
                        <input type="hidden" id="hid_bc" name="hid_bc"  title="" />  
                        <input type="hidden" id="hid_nno" name="hid_nno"  title="" /> 
                        <input type="hidden" id="hid_nno2" name="hid_nno2"  title="" />  
                        <input type="hidden" id="hid_store" name="hid_store"  title="" /> 
                        <input type="hidden" id="customer" name="customer"  title="" /> 
                        <br>

                        <span>Store</span> 
                        <span style="padding-left:39px;">
                            <input type="text" readonly="readonly" class="input_txt" name="v_store" id="v_store" style="width:150px;" title="" /></span>
                            <input type="text"  class="hid_value"  id="location_des" name="location_des"  title="" style="width: 251px;" /> 
                            <br>

                            <span>Vehicle</span> 
                            <span style="padding-left:29px;">
                                <input type="text" readonly="readonly" class="input_txt" name="vehicle" id="vehicle" style="width:150px;" title="" /></span>
                                <input type="text"  class="hid_value"  id="v_des" name="v_des"  title="" style="width: 251px;" /> 



                            </fieldset>
                            <span>Store</span> 
                            <span style="padding-left:50px;"><?=$store?></span>
                            <input type="text"  class="hid_value store11"  id="store_hid" name="store_hid"  title="" style="width: 167px;" /> 
                            <input type="button" name="load_btn" id="load_btn" title="Load transfer issue" />
                            <?php if($this->user_permissions->is_approve('t_internal_transfer_receive')){  ?>
                            <span >&nbsp;&nbsp;&nbsp; <!-- <input type="checkbox" name="upWstock" id="upWstock">Update Without Stock -->
                                <input type="hidden" id="updateWstock" name="updateWstock" title="0" />
                            </span>
                            <?php }else{?>
                            <input type="hidden" id="updateWstock" name="updateWstock" title="0" />
                            <?php }?>
                        </td>


                        <td  rowspan='3'  valign="top" align="right" >
                            <table border=0>
                                <tr>
                                    <td style="width: 100px !important;;">No</td>
                                    <td style="width: 150px !important;">  
                                        <input type="text" class="hid_value" name="id" id="id" style="width:100%;text-align: right;" title="<?=$id?>" />
                                        <input type="hidden" id="hid" name="hid" title="0" /></td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>

                                            <select style="width:185px;" name="t_type" id="t_type">
                                                <option value='branch'>Branch</option>   
                                                <option value='request'>Request</option> 
                                                <option value='main_store'>Main Store</option> 
                                            </select>
                                            <input type="hidden" id="types" name="types" title="branch" />

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

                                    <tr>
                                        <td>Date</td>
                                        <td>
                                            <?php if($this->user_permissions->is_back_date('t_internal_transfer_receive')){ ?>
                                            <input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                                            <?php } else{ ?>
                                            <input type="text" class="" readonly="readonly" name="ddate" id="ddate" style="width:100%;text-align:right;" title="<?=date('Y-m-d')?>" />
                                            <?php } ?>
                                        </td>
                                    </tr>

                                    <tr><td>Ref. No</td>
                                        <td><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;"/></td>
                                    </tr>
                                    <tr><td>CRN No</td>
                                        <td><input type="text" class="input_active_num" id="crn_no" name="crn_no" title="<?php echo $crn_no; ?>" style="width: 100%;"  /></td>
                                    </tr>
                                </table>   
                            </td>
                            <td rowspan='3'></td>
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


                            <th class="tb_head_th" width="70">Code</th>
                            <th class="tb_head_th" >Name</th> 
                            <th class="tb_head_th" style="width:80px;">Model</th>
                            <th class="tb_head_th" style="width:60px;">Batch</th> 
                            <th class="tb_head_th" style="width:60px;">Cost</th>
                            <th class="tb_head_th" style="width:60px;">Min Price</th>   
                            <th class="tb_head_th" style="width:60px;">Max Price</th> 
                            <th class="tb_head_th" style="width:60px;">Cur Stock</th> 
                            <th class="tb_head_th" style="width:20px;">Issued Qty</th>                          
                            <th class="tb_head_th" style="width:40px;">Accept Qty</th>                             
                            <th class="tb_head_th" style="width:80px;">Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                     <input type='hidden' id='transtype' title='INTERNAL TRANSFER RECEIVE' value='INTERNAL TRANSFER RECEIVE' name='transtype' />
                     <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                     for($x=0; $x<25; $x++){
                        echo "<tr>";
                        echo "<td width='70'><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                        <input type='text' readonly='readonly' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' /></td>
                        <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                        <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                        <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
                        <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";
                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width:100%;style='background-color: #f9f9ec;'/></td>";

                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='m_".$x."' name='m_".$x."' maxlength='150' style='width:100%;style='background-color: #f9f9ec;'/></td>";

                        echo "<td width='70' style='background-color: #f9f9ec;'><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                        <input type='text' readonly='readonly' class='g_input_txt btt_".$x."' id='1_".$x."' name='1_".$x."' style='background-color: #f9f9ec;width :40px; float: right; text-align:right;' /></td>";

                        echo "<td><input type='text' class='g_input_txt'  id='c_".$x."' name='c_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                        echo "<td><input type='text' class='g_input_txt'  id='min_".$x."' name='min_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                        echo "<td><input type='text' class='g_input_txt'  id='3_".$x."' name='3_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='cur_".$x."' name='cur_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec; text-align:right;'/></td>";

                        echo "<td><input type='text' class='g_input_num g_col_fixed' id='qtyh_".$x."' readonly='readonly' name='qtyh_".$x."' /></td>";

                        echo "<td width='70'><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                        <input type='text' class='g_input_num qun chk ky qtycl".$x."' id='2_".$x."' name='2_".$x."' style='width:40px; float:right;'/></td>";

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
                        <textarea id="issue_note" class="input_txt" style="width:485px;" name="issue_note" cols="50" rows="2"></textarea>
                    </td>
                    <td style="padding-left:255px;"> 
                        <b>Net Amount</b>
                    </td>
                    <td width="1w0">
                        <input type='text'  id='net_amount' class="hid_value g_input_amounts" title="" name='net_amount' style="margin-left:33px; width:150px;" />
                    </td>
                </tr>

                <tr>
                    <td>Driver</td>
                    <td>
                        <input type='text' name='driver' readonly="readonly" id='driver' class="input_txt">
                        <input type='text' name='driver_des' readonly="readonly" id='driver_des' class="hid_value" style="width:333px;">
                    </td>
                </tr>

                <tr>
                    <td>Helper</td>
                    <td>
                        <input type='text' name='helper' readonly="readonly" id='helper' class="input_txt">
                        <input type='text' name='helper_des' readonly="readonly" id='helper_des' class="hid_value" style="width:333px;">
                    </td>
                </tr>
            </table>




            <input type="hidden" name="srls" id="srls"/>
            <input type='hidden' id='transCode' value='43' title='43'/>
            <div style="text-align: right; padding-top: 7px; margin-right:2px;">
                <input type="button" id="" title="Exit" />
                <input type="button" id="btnReset" title="Reset" />
                <?php if($this->user_permissions->is_delete('t_internal_transfer_receive')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                <?php if($this->user_permissions->is_re_print('t_internal_transfer_receive')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                <?php if($this->user_permissions->is_add('t_internal_transfer_receive')){ ?><input type="button" title='Save <F8>' value="Save" id="btnSave" /><?php } ?>
                    <input type="button" title='Save <F8>' value="Save" id="btnSaveee" style="display:none;"/>
                        <!-- <input type="button" title="Reject" value="REject" id="btnReject" /> -->
                        <input type="hidden" name='save_chk' value="0" title="0" id="save_chk" > 

                        <?php 
                        if($this->user_permissions->is_print('t_internal_transfer_receive')){ ?>
                        <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                        <?php } ?>  

                        <input type="hidden" id="po" name="po" title="0" />
                        <input type="hidden" id="response" name="response" title="0" />
                        <!-- <input type="hidden" id="reject" name="reject" title="0" /> -->
                    </div>

                </td>
            </tr>
        </table>
    </form>

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

        <input type="hidden" name='by' value='t_internal_transfer_receive' title="t_internal_transfer_receive" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >
        <input type="hidden" name='type' value='t_internal_transfer_receive' title="t_internal_transfer_receive" >         
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
        <input type="hidden" name='qno' value='' title="" id="qno" >
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
        <input type="hidden" name='issue_b' value='' title="" id="issue_b" > 
        <input type="hidden" name='issue_cl' value='' title="" id="issue_cl" >
        <input type="hidden" name='req_n' value='' title="" id="req_n" >
        <input type="hidden" name='st' value='' title="" id="st" >
        <input type="hidden" name='p_type' value='' title="" id="p_type" >
        <input type="hidden" name='sub_qno' value='' title="" id="sub_qno" >
        <input type="hidden" name='org_print' value='0' title="0" id="org_print">
        <input type="hidden" name='sub_qno2' value='' title="" id="sub_qno2" >

    </form>

</div>
<?php } ?>