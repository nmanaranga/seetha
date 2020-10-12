<?php if($this->user_permissions->is_view('t_damage_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_damage_sum.js"></script>

<div id="fade" class="black_overlay"></div>

<?php 
    if($ds['use_serial_no_items'] ){
        $this->load->view('t_serial_out.php'); 
    }
?>

<div id="fade" class="black_overlay"></div>

<h2 style="text-align: center;">Damages</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_damage_sum" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
                <td style="width: 80px;">From Store</td>
                <td>
                   <span><?=$store_from;?></span>
                   <input type="text"  class="hid_value"  id="store_from_id" name="store_from_id"  title="" style="width: 251px;" />
                </td>
                
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" style="width:100%" title="<?=$id?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr>

            <tr>
                <td style="width: 80px;">To Store</td>
                <td>
                   <span><?=$store;?></span>
                   <input type="text"  class="hid_value"  id="store_to_id" name="store_to_id"  title="" style="width: 251px;" />
                   
                    <!--<input type="button" title="Set Root/Area" id="btnSetRootArea" />-->
                </td>

                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_damage_sum')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" style="text-align:right;"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" style="text-align:right;"/>
                    <?php } ?>        
                
                </td>
            </tr>

            <tr>
                <td>Officer</td>
                <td>
                    <input type="text" class="input_active" id="officer"  name="officer" title="" style="width: 150px;" maxlength="25" />
                    <input type="text"  class="hid_value"  id="officer_id" name="officer_id"  title="" style="width: 251px;" />
                </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;"/></td>
            </tr>

            <tr>
                <td>Remark</td>
                <td>
                    <input type="text" class="input_txt" title=''  id="ref_des"  name="ref_des"  style="width: 404px;">
                </td>
            </tr>
                        
            <tr>
                <td colspan="4" style="text-align: center;">
                   <table style="width: 100%;" id="tgrid" >
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width:93px;">Code</th>
                                <th class="tb_head_th" style="width:170px;">Name</th> 
                                <th class="tb_head_th" >Batch</th>                              
                                <th class="tb_head_th" >QTY</th>
                                <th class="tb_head_th" >Price</th>                              
                                <th class="tb_head_th" >Amount</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                             <input type='hidden' id='transtype' title='DAMAGE NOTE' value='DAMAGE NOTE' name='transtype' />
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td ><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='width:100%;'/></td>
                                                <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                                <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
                                                <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                                <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' readonly='readonly' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec;'/></td>";

                                        echo "<td width='50' style='background-color: #f9f9ec;'><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                              <input type='text' readonly='readonly' class='g_input_txt btt_".$x."' id='1_".$x."' name='1_".$x."' style='background-color: #f9f9ec;width :40px; float: right;text-align:right;' /></td>";

                                        echo "<td width='70' ><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float: left; height:6px;width:6px;cursor:pointer'/>
                                              <input type='text' class='g_input_num qun chk ky qtycl".$x."' id='2_".$x."' name='2_".$x."' style='width:40px; float:right;'/></td>";
                                      
                                        echo "<td width='70' style='text-align:right;'><input type='text' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' />
                                              <input type='hidden' class='g_input_amo dis' id='qtyh_".$x."' name='qtyh__".$x."' /></td>";
                                        
                                        echo "<td width='70' style='background-color: #f9f9ec; text-align:right;'><input type='text' id='t_".$x."' name='t_".$x."' class='g_input_amo tf' readonly='readonly'/>
                                              <input type='hidden' id='subcode_".$x."' title='0' name='subcode_".$x."' />
                                        </td>";

                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
					
					
				<fieldset>
					<table>
						<legend>Account</legend>
							<tr>	
								<td>
                                    DR ACC
                                </td>
								<td></td>
								<td>
                                    <input type="text" class="input_txt" title=''  id="dr_acc" name="dr_acc" style="width: 150px;">
                                </td>
							    <td style="padding-right : 7px;">
                                    &nbsp;
                                </td>
                                <td colspan="5" style="padding-right : 440px;">
                                    &nbsp;
                                </td>
                                <td align="left"> 
                                    <b>Net Amount</b>
                                </td>
                                <td width="1w0">
                                    <input type='text'  id='net_amount' class="hid_value g_input_amounts" title="" name='net_amount' style="margin-left:20px;" />
                                </td>
                            </tr>
							
							<tr>	
								<td>
                                    CR ACC
                                </td>
								<td></td>
								<td>
                                    <input type="text" class="input_txt" title='' readonly="readonly" id="cr_acc" name="cr_acc"  style="width: 150px;">
                                </td>
							</tr>
                        </table>
					</fieldset>						                  
                <input type="hidden" name="srls" id="srls"/>
                <input type='hidden' id='transCode' value='14' title='14'/>
                <div style="text-align: right; padding-top: 7px; margin-right:2px;">
                    <input type="button" id="" title="Exit" />
                    <input type="button" id="btnReset" title="Reset" />
                    <?php if($this->user_permissions->is_delete('t_damage_sum')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                    <?php if($this->user_permissions->is_re_print('t_damage_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
			        <?php if($this->user_permissions->is_add('t_damage_sum')){ ?><input type="button" title='Save <F8>' value="Save" id="btnSave" /><?php } ?>
                    <input type="hidden" name='save_chk' value="0" title="0" id="save_chk" > 
                   
                      
                    <input type="hidden" id="po" name="po" title="0" />
            		<input type="hidden" id="response" name="response" title="0" />
            		<input type="hidden" id="reject" name="reject" title="0" />
                </div>
                
                </td>
                <?php 
                    if($this->user_permissions->is_print('t_damage_sum')){ ?>
                    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                <?php } ?> 
            </tr>
        </table>
    </form>

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
        <input type="hidden" name='by' value='t_damage_sum' title="t_damage_sum" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >
                 
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
        <input type="hidden" name='qno' value='' title="" id="qno" >
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
     
    </form>

</div>
<?php } ?>