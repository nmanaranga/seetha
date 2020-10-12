<?php if($this->user_permissions->is_view('t_cheque_issue_trans')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_cheque_issue_trans.js"></script>
<h2 style="text-align: center;">Cheque Withdraw</h2>

<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_cheque_issue_trans" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Bank Account</td>
                <td>
                    <input type="text" name="banck_acc" id="banck_acc" class="input_txt"/> 
                    <input type="text" class="hid_value" title='' readonly="readonly" id="acc"  style="width: 300px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$id?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr>
            <tr>
                <td>Cheque No</td>
                <td>
                    <input type="text" class="input_txt"  id="cheque_no"/>    
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                <?php if($this->user_permissions->is_back_date('t_cheque_issue_trans')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                <?php } else { ?>
                    <input type="text" class="input_txt" readonly="readonly" name="date" id="date" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                <?php } ?> 
                </td>
            </tr>
            <tr>
                <td>Load Cheque</td>
                <td>
                    <input type="text" class="input_date_down_future input_txt" readonly="readonly" name="cheque_date" id="cheque_date" title="<?=date('Y-m-d')?>" />    
                    <input type="button" name="load_cheque" id="load_cheque" title="Load" style="width:100px;"/>
                </td>
                <td style="width: 100px;"></td>
                <td style="width: 100px;"></td>
            </tr>

           
          <tr>
                <td colspan="4" style="text-align: center;">
                    
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 60px;">Account No</th>
                                <th class="tb_head_th" style="width: 200px;">Account Name</th>
                                <th class="tb_head_th" style="width: 150px;">To Account Code</th>
                                <th class="tb_head_th" style="width: 200px;">To Account Name</th>
                                <th class="tb_head_th" style="width: 150px;">Cheque No</th>
                                <th class="tb_head_th" >Date</th>
                                <th class="tb_head_th" style="width: 100px;">Amount</th>
                                <th class="tb_head_th" style="width: 20px;">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<50; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                  <input type='text' class='hid_value ' id='0_".$x."' name='0_".$x."'  style='width:100%'/></td>";
                                        echo "<td><input type='text' class='hid_value'  id='n_".$x."' name='n_".$x."' style='width:100%' readonly='readonly' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='hid_value fo' id='1_".$x."' name='1_".$x."' style='width:100%;' /></td>";
                                        echo "<td><input type='text' class='hid_value fo' id='1_name_".$x."' name='1_name_".$x."' style='width:100%;' /> </td>";
                                        echo "<td><input type='text' class='hid_value ' id='2_".$x."' name='2_".$x."' style='width:100%;'/></td>";
                                        echo "<td><input type='text' class='hid_value' id='3_".$x."' name='3_".$x."' style='width:100%'/></td>";
                                        echo "<td><input type='text' class='hid_value g_input_amo bl' readonly='readonly' id='4_".$x."' name='4_".$x."' style='width:100%'/></td>";
                                        echo "<td><input type='checkbox' class='chk' id='5_".$x."' name='5_".$x."' value='1' />
                                                  <input type='hidden' id='t_code_".$x."' name='t_code_".$x."' />
                                                  <input type='hidden' id='t_no_".$x."' name='t_no_".$x."' />
                                              </td>";
                                       
                                        
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                 <td style="width: 150px;">&nbsp;</td>                                  
                                 <td></td>
                                 <td></td>
                                 <td ></td>

                                 <td style="width: 100px; ">Total</td>
                                 <td style="width: 132px; ">
                                    <input type="text" class="g_input_amounts" name="total" id="total" style="width:100px;border: 1px solid #003399; margin-left:8px;"/>
                                </td>
                            </tr>
                            <tr>
                                 <td style="width: 150px;">&nbsp;</td> 
                                 <td></td>                                   
                                 <td></td>
                                 <td></td>
                                 <td style="width: 100px;">Total Settle</td>
                                 <td style="width: 132px;">
                                    <input type="text" class="g_input_amounts" name="tot_settle" id="tot_settle" style="width:100px;border: 1px solid #003399; margin-left:8px;"/>
                                </td>
                            </tr>
                            <tr>
                                 <td style="width: 150px;">&nbsp;</td>  
                                 <td></td>                                   
                                 <td></td>
                                 <td></td>
                                 <td style="width: 100px;">Balance</td>
                                 <td style="width: 132px;">
                                    <input type="text" class="g_input_amounts" name="tot_balance" id="tot_balance" style="width:100px;border: 1px solid #003399; margin-left:8px;"/>
                                </td>
                            </tr>
                        </tfoot>
                        
                                               
                    </table>
               

                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_delete('t_cheque_issue_trans')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                        <?php if($this->user_permissions->is_re_print('t_cheque_issue_trans')){ ?><input type="button" id="btnPrint" title="Print" />  <?php } ?>                     
                        <?php if($this->user_permissions->is_add('t_cheque_issue_trans')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>

                    </div>
                
                <?php if($this->user_permissions->is_print('t_cheque_issue_trans')){ ?>
                    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                <?php } ?> 
                </td>
            </tr>
        </table>
    </form>

      <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
        <input type="hidden" name='by' value='t_cheque_issue_trans' title="t_cheque_issue_trans" class="report">
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