<?php if($this->user_permissions->is_view('t_quotation_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_check_print_sign.js"></script>

<h2 style="text-align: center;">Check Print And Signature</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_check_print_sign" id="form_">
        <table style="width: 100%" border="0">
            
            <tr>
                <td style="width: 100px;">Type</td>
                <td>
                        <select name='t_type' id='t_type'>
                            <option value='1'>Voucher_Authorise</option>
                            <option value='2'>Signature_01</option>
                            <option value='3'>Signature_02</option>
                            <option value='3'>Issue_Cheque</option>
                        </select>
                </td>
                
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_print('t_quotation_sum')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php }else{ ?>   
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } ?> 
                </td>

            </tr>

            </tr>
                <td style="width: 100px;">Account</td>
                <td colspan="2">
                    <input id="acc" class="input_txt ac_input" type="text" style="width: 150px;" maxlength="50" title="" name="acc_id" autocomplete="off">
                    <input id="acc_id" type="hidden" title="0" name="acc_id" value="0">
                    <input id="acc_des" name="acc_des" class="hid_value" type="text" style="width: 300px;" readonly="readonly" title="">
                </td>
            </tr>

            <tr>
                <td colspan="4" style="text-align: center;">
                
                    <table style="width: 100%;" id="tgrid" >
                        <thead>
                            <tr>
                                <th class="tb_head_th" width="35">CL</th>
                                <th class="tb_head_th" width="35">BR</th>
                                <th class="tb_head_th" >Vou No</th>
                                <th class="tb_head_th" >Date</th>
                                
                                <th class="tb_head_th" >Supp ID</th>
                                <th class="tb_head_th"  width="200" >Supplier Name</th>
                                <th class="tb_head_th"  width="150">Amount</th>
                                <th class="tb_head_th"  width"150">Cheque No</th>
                                <th class="tb_head_th" >OK</th>
                               
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td width='35'><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt' id='0_".$x."' name='0_".$x."' /></td>";
                                        echo "<td ><input type='text' class='g_input_txt '  id='n_".$x."' name='n_".$x."' maxlength='150' style='width:100%;'/></td>";
                                        echo "<td width='70'><input type='text' style='' class='g_input_txt' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td width='70' ><input type='text' class='g_input_num qun' id='2_".$x."' name='2_".$x."' s/></td>";
                                        echo "<td width='70' ><input type='text' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' /></td>";
                                        echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_amo dis_pre g_col_fixed' id='4_".$x."' name='4_".$x."' '/></td>";
                                        echo "<td width='70' ><input type='text' class='g_input_amo free_is' id='5_".$x."' name='5_".$x."' '/></td>";
                                        echo "<td width='70' ><input type='text' class='g_input_amo free_is' id='5_".$x."' name='5_".$x."' '/></td>";
                                        echo "<td width='70'id='t_".$x."' name='t_".$x."' class='tf' style='text-align:right;background-color: #f9f9ec;'>&nbsp;</td>";

                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
             
                        <table style="width:100%;" border="0">
                            <tr>
                                    <td colspan="8">&nbsp;</td>
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
                                <td></td>
                                <td></td>
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
       
</form>
    
</div>
<?php } ?>