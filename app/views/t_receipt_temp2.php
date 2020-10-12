<?php if($this->user_permissions->is_view('t_receipt_temp')){ ?>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_receipt_temp.js"></script>

<h2 style="text-align: center;">Cheque Acknowledgement</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_receipt_temp" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Customer</td>
                <td>
                	<input type="text" name="customer_id" id="customer_id" class="input_txt"/> 
                    <input type="text" class="hid_value" title='' readonly="readonly" id="customer"  style="width: 300px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$id?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr><tr>
                <td>Officer</td>
                <td>
                    <input type="text" name="officer_id" id="officer_id" class="input_txt"/> 
                    <input type="text" class="hid_value" name="officer" id="officer" title="" style="width: 300px;"/></td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                <?php if($this->user_permissions->is_back_date('t_receipt_temp')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                <?php } else { ?>
                    <input type="text" class="input_txt" readonly="readonly" name="date" id="date" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                <?php } ?> 
                </td>
            </tr>

            <tr>
                <td>Remark</td>
                <td><input type="text" class="input_txt" name="remark" id="remark" title="" style="width: 455px;"/></td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;"/></td>
           
            </tr>

          <tr>
                <td colspan="4" style="text-align: center;">
                	
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Bank</th>
                                <th class="tb_head_th">Bank Name</th>
                                <th class="tb_head_th" style="width: 80px;">Branch</th>
                                <th class="tb_head_th" style="width: 80px;">Branch Name</th>
                               <th class="tb_head_th" style="width: 80px;">Account</th>
                                <th class="tb_head_th" style="width: 80px;">Chque No</th>
                                <th class="tb_head_th" style="width: 80px;">Realize Date</th>
                                <th class="tb_head_th" style="width: 80px;">Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<1; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='hid_value fo' id='0_".$x."' name='0_".$x."'  /></td>";
                                        echo "<td><input type='text' class='hid_value'  id='n_".$x."' name='n_".$x."' style='width:100%' readonly='readonly' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_txt fo' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td><input type='text' class='hid_value ' id='2_".$x."' name='2_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_txt' id='3_".$x."' name='3_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_txt' id='4_".$x."' name='4_".$x."' /></td>";
                                        echo "<td><input type='text' readonly='readonly' class='input_date_down_future' id='5_".$x."' name='5_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo tot' id='6_".$x."' name='6_".$x."' /></td>";
                                        
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                                               
                    </table>
               

                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_receipt_temp')){ ?><input type="button" id="Delete" title="Delete" /><?php } ?>
                        <?php if($this->user_permissions->is_delete('t_receipt_temp')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_receipt_temp')){ ?><input type="button" id="btnPrint" title="Print" />  <?php } ?>                     
                        <?php if($this->user_permissions->is_add('t_receipt_temp')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>

                      
                        <span style="text-align:right;margin-left:350px;"><b>Total</b>
                    <input type="text" class="g_input_amounts" name="tot_dr" id="tot_dr" style="width:100px;"/>
                    
                    </span>
                    </div>
                
                <?php if($this->user_permissions->is_print('t_receipt_temp')){ ?>
                    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                <?php } ?> 
                </td>
            </tr>
        </table>
    </form>

      <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
        <input type="hidden" name='by' value='t_receipt_temp' title="t_receipt_temp" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >                 
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
        <input type="hidden" name='qno' value='' title="" id="qno" >
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
        <input type="hidden" name='cus_id' value='' title="" id="cus_id" > 
        <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" > 
     
    </form>

</div>
<?php } ?>
