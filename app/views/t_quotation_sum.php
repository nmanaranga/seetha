<?php if($this->user_permissions->is_view('t_quotation_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_quotation_sum.js"></script>

<h2 style="text-align: center;">Quotation</h2>
<div class="dframe" id="mframe" style="padding-right:25px; width:1150px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_quotation_sum" id="form_">
        <table width="100%" border="0">
            <tr>
                <td style="width: 100px;">Customer</td>
                <td><input type="text" id="customer" name="customer"class="input_active" title=""/>
                       <input type="text" class="input_active" id="customer_id" name="customer_id" title="" style="width: 300px;" />
                    </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" style="width:100%" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr><tr>
                <td>Address</td>
                <td>
                    <input type="text" class="input_active" name="address" id="address" title="" style="width:455px;"/>
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_print('t_quotation_sum')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="text-align:right;" />
                    <?php }else{ ?>   
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="text-align:right;"/>
                    <?php } ?> 
                </td>
            </tr><tr>
                <td>Memo</td>
                <td>
                   <input type="text" class="input_txt" name="memo" id="memo" title="" style="width:455px;"/>
                </td></td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title=" " style="width: 100%;" maxlength="25"/></td>
            </tr>
            <tr>
                <td>Validity Period</td>
                <td>
                   <input type="text" class="input_txt" name="validity_period" id="validity_period" title="" style="width:455px;"/>
                </td></td>
            </tr>    
            <tr>
                <td colspan="4" style="text-align: center;">
                
                    <table style="width: 100%;" id="tgrid" >
                        <thead>
                            <tr>
                                <th class="tb_head_th" width="120">Code</th>
                                <th class="tb_head_th" width="220">Item Description</th>
                                <th class="tb_head_th" width="102">Model</th>
                                <th class="tb_head_th" width="102">QTY</th>
                                <th class="tb_head_th" width="102">Price</th>
                                <th class="tb_head_th" width="102">Dis(%)</th>
                                <th class="tb_head_th" width="102">Discount</th>
                                <th class="tb_head_th" style="width: 71px;">Amount</th>
                                <th class="tb_head_th" >Description</th>
                               
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td ><input type='hidden'  name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='width:100%;'/></td>";
                                        echo "<td ><input type='text' class='g_input_txt '  id='n_".$x."' name='n_".$x."' maxlength='150' style='width:100%;'/></td>";
                                        echo "<td width='70'><input type='text' style='' class='g_input_txt' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td width='70' ><input type='text' class='g_input_num qun' id='2_".$x."' name='2_".$x."' s/></td>";
                                        echo "<td width='70' ><input type='text' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' /></td>";
                                        echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_amo dis_pre g_col_fixed' id='4_".$x."' name='4_".$x."' '/></td>";
                                        echo "<td width='70' ><input type='text' class='g_input_amo free_is' id='5_".$x."' name='5_".$x."' '/></td>";
                                        echo "<td width='70'id='t_".$x."' name='t_".$x."' class='tf' style='text-align:right;background-color: #f9f9ec;'>&nbsp;</td>";
                                        echo "<td><input type='text' class='g_input_txt' id='6_".$x."' name='6_".$x."' style='width:100%;'/></td>";
                                       
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
             
                        <table style="width:100%;" border="0">
                            <tr>
                                <td style="width:50px;">Officer</td>
                                <td colspan="4">
                                   <input type="text" class="input_txt" name="officer" id="officer" title="" style="width:150px;"/>
                                   <input type="text" class="hid_value" id="officer2" title="" readonly="readonly" style="width: 300px;" />
                                </td>
                                <td style="width: 100px;"></td>
                                <td style="width: 100px;"></td>            
                            </tr>
                            <tr style="background-color: transparent;">
                                
                                <td colspan="6" style="padding-left : 10px;">
                            		<input type="button" id="btnExit" title="Exit" />
									<input type="button" id="btnReset" title="Reset" />
									<input type="button" id="btnDelete" title="Cancel" />
									<?php if($this->user_permissions->is_re_print('t_quotation_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                                    <input type="button"  id="btnSavee" title='Save <F8>' />
									<?php if($this->user_permissions->is_add('t_quotation_sum')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>
                                </td>
                                <td align="left" style="margin-bottom :0px;"><b>Gross</b></td>
                                <td>
                                	<input type='text' id='total2' readonly="readonly" title="" name='gross' class="hid_value g_input_amounts" /></td>
                            </tr>
                            <tr style="background-color: transparent;">
                                <td style="padding-right : 7px;">&nbsp;</td>
                                <td colspan="5" style="padding-right : 10px;">&nbsp;</td>
                                <td align="left" ><b>Discount</b></td>
                                <td><input type='text'  id='discount' readonly="readonly" title="" name='discount' class="hid_value g_input_amounts" /></td>
                            </tr>
                            <tr style="background-color: transparent;">
                                <td style="padding-right : 7px;">&nbsp;</td>
                                <td colspan="5" style="padding-right : 10px;">&nbsp;</td>
                                <td align="left"><b> Net Amount <b/></td>
                                <td width="1w0"><input type='text'  id='net_amount' readonly="readonly" title="" name='net_amount' class="hid_value g_input_amounts"/></td>
                            </tr>
                        
                            
                            <?php 
                            if($this->user_permissions->is_print('t_quotation_sum')){ ?>
                                <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                            <?php } ?> 
                    </table>
                 
                </td>
            </tr>
        </table>
    


</form>


<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_quotation_sum' title="t_quotation_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='t_quotation_sum' title="t_quotation_sum" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
                 <input type="hidden" name='org_print' value='' title="" id="org_print">
       
</form>
    
</div>
<?php } ?>