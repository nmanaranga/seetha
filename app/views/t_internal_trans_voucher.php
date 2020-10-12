<?php if($this->user_permissions->is_view('t_internal_trans_voucher')){ ?>
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
  <script type="text/javascript" src="<?=base_url()?>js/t_internal_trans_voucher.js"></script>
  <?php $this->load->view('t_payment_option.php'); ?> 
  <h2 style="text-align: center;">Internal Transfer Voucher</h2>
  <div style="margin:10px auto; width:972px;"> 
    <div class="dframe" id="mframe" style="padding-right:25px;">
      <table style="width: 100%" border="0" cellpadding="0">
        <tr>
          <td width="100">Account Code</td>
          <td width="100"><input type="text" class="input_txt" id="acc_code" title="" name="acc_code">
            <input type="hidden" class="input_txt" id="supplier_id" title="" name="supplier_id"></td>
            <td colspan="3"><input type="text" class="hid_value" id="acc_des" readonly="readonly" style="width:407px;"/></td>
            <td width="50">&nbsp;</td>    
            <td width="50">No</td>
            <td>
              <input type="text" class="input_active_num" name="id" id="id" title="<?php echo $max_no;?>" style="width:150px;"/>
              <input type="hidden" id="hid" name="hid" title="0" />
              <input type="hidden" id="type" name="type" title="131" value="131"/>
              <input type='hidden' name='save_status' id="save_status"/>
            </td>
          </tr>

          <tr>
            <td>Balance</td>
            <td align="left"><input type="text" class="hid_value" id="balance" name="balance" title="" readonly="readonly" style="text-align:right;width:150px;"/></td>
            <td width="200"></td>
            <td align="left"></td>
            <td align="left"></td>
            <td>&nbsp;</td>
            <td>Date</td>
            <td>
              <?php if($this->user_permissions->is_back_date('t_internal_trans_voucher')){ ?>
                <input type="text" class="input_date_down_future" style="width:150px; text-align:right;" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
              <?php }else{ ?>    
                <input type="text" class="input_txt" style="width:150px;" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
              <?php } ?> 
            </td>
          </tr>

          <tr>

            <td>Memo</td>
            <td colspan="5">
              <input type="text" class="input_txt" id="memo" name="memo" title="" style="width:560px;"/></td>

              <td>Ref No</td>
              <td><input type="text" class="input_number" name="ref_no" id="ref_no" title="" style="width:150px;"/></td> 
            </tr> 

            <tr>
              <td>Payment</td>
              <td><input type="text" class="g_input_amo" name="net" id="net" style="border:1px solid #039;padding:3px 2px;width:150px;"/></td>

              <td align="left" colspan='3'>

                <!-- <input type="checkbox" name="is_multi_branch" id="is_multi_branch" title="1" style="vertical-align:middle"/>Multi Branch -->
                <input type="checkbox" id="auto_fill" name="auto_fill" style="vertical-align:middle"/>Auto Fill
                <input type='button' title='Load Details' value='Load Details' id="load_details" style='margin-left:20px;'/>
              </td>
              <td>&nbsp;</td>
              <td colspan='2'></td>
            </tr>  
            <tr>

              <td colspan="8" style="text-align: center;">

                <!-- <table style="width: 100%;" id="tgrid" border="1"> -->
                  <table style="width:100%" id="tgrid" border="0" class="tbl">
                    <thead>
                      <tr>

                        <th class="tb_head_th" style="width:50px;" >Cluster</th>
                        <th class="tb_head_th" style="width:50px;">Branch</th>
                        <th class="tb_head_th" style="width:100px;">Trans Type</th>
                        <th class="tb_head_th" style="width:50px;">No</th>
                        <th class="tb_head_th" style="width:50px;">Rec No</th>
                        <th class="tb_head_th" style="width:50px;">Mode No</th>
                        <th class="tb_head_th" style="width:100px;">Date</th>
                        <th class="tb_head_th" style="width:200px;">Description</th>
                        <th class="tb_head_th" style="width:100px;">Amount</th>
                        <th class="tb_head_th" style="width:100px;">Balance</th>
                        <th class="tb_head_th" style='width:100px;' >Payment</th>

                      </tr>
                    </thead><tbody>
                      <?php

                      for($x=0; $x<100; $x++){
                        echo "<tr>";
                        echo "<td><input type='text' class='g_input_txt g_col_fixed' id='cl0_".$x."' name='cl0_".$x."' style='width : 100%;' /></td>";
                        echo "<td><input type='text' class='g_input_txt g_col_fixed' id='bc0_".$x."' name='bc0_".$x."' style='width : 100%;' /></td>";
                        echo "<td><input type='text' class='g_input_num2 g_col_fixed qun' id='1_".$x."' name='1_".$x."' style='width:100%; text-align:left;' />
                        <input type='hidden' name='trans_code".$x."' id='trans_code".$x."'/>
                        </td>";
                        echo "<td><input type='text' class='g_input_num2 g_col_fixed' id='2_".$x."' name='2_".$x."' style='width:100%;' /></td>";
                        echo "<td><input type='text' class='g_input_num2 g_col_fixed' id='2r_".$x."' name='2r_".$x."' style='width:100%;' /></td>";
                        echo "<td><input type='text' class='g_input_num2 g_col_fixed' id='2m_".$x."' name='2m_".$x."' style='width:100%;' /></td>";
                        echo "<td><input type='text' class='g_input_txt g_col_fixed' id='3_".$x."' name='3_".$x."' style='width:100%;' /></td>";
                        echo "<td><input type='text' class='g_input_txt g_col_fixed' id='descrip_".$x."' name='descrip_".$x."' style='width : 100%;' /></td>";
                        echo "<td><input type='text' class='g_input_amo g_col_fixed' id='4_".$x."' name='4_".$x."' style='width:100%;' /></td>";
                        echo "<td><input type='text' class='g_input_amo g_col_fixed fo' id='5_".$x."' name='5_".$x."' style='width:100%;' /></td>";
                        echo "<td  style=''><input type='text' class='g_input_amo pay' id='6_".$x."' name='6_".$x."' style='width: 100px;'/></td>";
                        echo "</tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </td>
              </tr>

              <tr style="background:#e6eeff">
                <td colspan="3"></td>
                <td colspan="5">
                  <b style="padding-left:37px;">Total</b>
                  <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_amount" name="total_amount" style="margin-left:50px; width:113px;background:#e6eeff;" />
                  <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_balance" name="total_balance" style="width:110px;background:#e6eeff;" />
                  <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_payment" name="total_payment" style=" width:102px; background:#e6eeff;" />
                </td>


              </tr>



              <tr>
                <td colspan="5" rowspan="2">

                  <input type="button" id="btnExit" title="Exit" />
                  <input type="button" id="btnResett" title="Reset" />
                  <?php if($this->user_permissions->is_delete('t_internal_trans_voucher')){ ?><input type="button" id="btnDelete5" title="Cancel" /><?php } ?>
                  <?php if($this->user_permissions->is_re_print('t_internal_trans_voucher')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                  <input type="button" title="Payments" id='showPayments'/>
                  <?php if($this->user_permissions->is_add('t_internal_trans_voucher')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>


                </td>
                <td colspan="2"><b>Settle Amount</b></td>
                <td style="padding-left:29px;"><input type="text" class="hid_value g_input_amounts" readonly="readonly" title="" id="net_val" name="net_val" /></td>
              </tr>
              <tr>

                <td colspan="2"><b>Balance</b></td>
                <td style="padding-left:29px;"><input type="text" class="hid_value g_input_amounts" readonly="readonly" title="" id="balance2" name="balance2" /></td>
              </tr>

            </table>
            <?php 
            if($this->user_permissions->is_print('t_internal_trans_voucher')){ ?>
              <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
            <?php } ?> 
          </form>
          <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
           <input type="hidden" name='by' value='t_internal_trans_voucher' title="t_internal_trans_voucher" class="report">
           <input type="hidden" name='page' value='A4' title="A4" >
           <input type="hidden" name='orientation' value='P' title="P" >
           <input type="hidden" name='type' value='t_internal_trans_voucher' title="t_internal_trans_voucher" >
           <input type="hidden" name='recivied' value='' title=""  id='recivied'>
           <input type="hidden" name='header' value='false' title="false" >
           <input type="hidden" name='qno' value='' title="" id="qno" >
           <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
           <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
           <input type="hidden" name='dt' value='' title="" id="dt" >
           <input type="hidden" name='acc_codes' value='' title="" id="acc_codes" > 
           <input type="hidden" name='is_duplicate' value='0' title="0" id="is_duplicate" >           
         </form>
       </div>
     </div>
     <?php } ?>