<?php if($this->user_permissions->is_view('g_t_sales_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/g_t_sales_sum.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_sales_sum.css" />

<div id="fade" class="black_overlay"></div>
<?php 
if($ds['use_serial_no_items'] ){
  $this->load->view('g_t_serial_out.php'); 
}
?>
<div id="fade" class="black_overlay"></div>
<div id="det_box">
  <img id="close_det_box" src="<?=base_url(); ?>/images/close_button.png"/>
  <div id="det_box_inner"><?php if(isset($det_box)){ echo $det_box;} ?>
  </div>
</div>
<?php $this->load->view('t_payment_option.php'); ?>
<h2>Gift Voucher Sales</h2>

<div class="dframe" id="mframe" style="padding-right:25px;">
  <table style="width:100%;" id="tbl1" border="0">
    <tr>        
     <td width="65">Customer</td>
     <td width="100">
      <input type="text" id="customer" class="input_active" title="" name="customer" />            
    </td>    	
    <td colspan="3">
      <input type="text" class="hid_value main_cus" id="customer_id"  title="" readonly="readonly" style="width:356px;" />
      <input type="text" placeholder="Customer Name" id="bill_cuss_name" class="input_active bill_to_cus" title="" style="display:none; width:356px;" name="bill_cuss_name" />
    </td>        
    <td class="" width="118">
      <input type='button' value="..." title="..." id="cutomer_create"/>
      &nbsp; &nbsp; &nbsp;Bill To<input type="checkbox" style="align:right;" name="is_main" id="is_main">   
    </td>
    <td width="50">No</td>
    <td width="150"><input type="text" class="input_active_num" name="id" id="id" maxlength="10" title="<?=$max_no?>" style="width:150px;"/>
     <input type="hidden" id="hid" name="hid" title="0" />
     <input type="hidden" id="base_url" title="<?php echo base_url();?>" value="<?php echo base_url();?>" />
   </td>  	
   <input type="hidden" id="cl" name="cl" title="<?=$cl?>" />
   <input type="hidden" id="bc" name="bc" title="<?=$bc?>" />	
 </tr>

 <tr>
  <input type="hidden" name="df_is_serial" id="df_is_serial" value="<?php echo $ds['use_serial_no_items'] ?>" title="<?php echo $ds['use_serial_no_items'] ?>"/>
  <td>Address</td>
  <td colspan="4">
    <input type="text" class="hid_value main_cus" id="address" title="" readonly="readonly" style="width:510px;"/>
    <input type="text" class="input_active bill_to_cus" id="cus_address" name="cus_address" title="" style="width:510px; display:none;"/>
  </td>
  <td class="" width="118">
    <b style="margin-left:35px;">Cash Bill</b><input type="checkbox" style="align:right;" name="is_cash_bill" id="is_cash_bill">

  </td>
  <td>Date</td>
  <td>
    <?php if($this->user_permissions->is_back_date('g_t_sales_sum')){ ?>    
    <input type="text" class="input_date_down_future " readonly="readonly" style="width:150px; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
    <?php }else{?>   
    <input type="text" class="" readonly="readonly" style="width:150px; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
    <?php }?> 
  </td>
</tr>

<tr>
 <td>Balance</td>
 <td><input type="text" id="balance" name="balance" class="hid_value g_input_amo" title=""/></td>
 <td width="147">&nbsp;</td>
 <td><span class="ct">Category</span></td>
 <td>
  <span class="ct"><?php echo $sales_category;?></span>
  <input type="hidden" id="sales_category1" name="sales_category1" />
</td>
<td>&nbsp;</td>
<input type="hidden" name="type" id="type" title="63" value="63"/>
<td>Ref No</td>
<td><input type="text" name="ref_no" id="ref_no" class="input_txt" title="" maxlength="10"/></td>
</tr>

<tr>
 <td>SO No</td>
 <td><input type="text" id="serial_no" name="serial_no" class="input_txt" title="" /></td> 
 <td>&nbsp;</td>
 <td><span class="gr">Group</span></td>
 <td><span class="gr"><?php echo $groups;?></span>
  <input type="hidden" id="groups1" name="groups" /></td>
  <td>&nbsp;</td>
  <td>Sub No</td>
        <!-- <td>CRN No</td>
        <td><input type="text" class="input_active_num" name="crn_no" id="crn_no" title="<?=$crn_no?>" style="width:150px;"/>
          <input type="hidden" class="input_active_num" name="crn_no_hid" id="crn_no_hid" value='' style="width:150px;"/></td> -->
          <td>
            <input type="text" class="input_active_num" readonly="readonly" name="sub_no" id="sub_no" title="0" style="width:150px;"/>
            <!-- <input type="text" name="ref_no" id="ref_no" class="input_txt" title="" maxlength="10"/> -->
          </td>
        </tr>

        <tr>
         <td>Store</td>
         <td><?php echo $stores; ?></td>
         <td colspan="3"><input type="text"  id="store_id" style="width:355px;" class="hid_value" title="" readonly="readonly"/></td>
         <td>&nbsp;</td>
    	<!-- <td>Sub No</td>
    	<td>
            <input type="text" class="input_active_num" readonly="readonly" name="sub_no" id="sub_no" title="0" style="width:150px;"/>
            <input type="text" name="ref_no" id="ref_no" class="input_txt" title="" maxlength="10"/> 
          </td> -->
        </tr>

 <!--    <tr>
        <td>Quotation</td>
        <td><input type="text" class="input_txt" title="" id="quotation" name="quotation"/></td>
        <td>
            <input type="button" name="b_foc" id="b_foc" title="Bulk FOC" />
            <input type="hidden" name="is_foc" id="is_foc" title="0" />
        </td>
      </tr> -->

      <tr>
       <td colspan="8">
        <table style="width:100%" id="tgrid" border="0" class="tbl">
          <thead>
            <tr>
              <th class="tb_head_th" style="width: 80px;">Code</th>
              <th class="tb_head_th" style="width: 250px;">Description</th>
              <th class="tb_head_th" style="width: 10px;">QTY</th>
              <th class="tb_head_th" style="width: 65px;">Price</th>
              <th class="tb_head_th" style="width: 65px;">Amount</th>
              <th class="tb_head_th" style="width: 20px;">Is Free</th>
            </tr>
          </thead><tbody>
          <input type='hidden' id='transtype' title='GIFT VOUCHER SALE' value='GIFT VOUCHER SALE' name='transtype' />
          <input type='hidden' name='all_foc_amount' id='all_foc_amount' value='0' title='0'/>
          <?php
          for($x=0; $x<25; $x++){
            echo "<tr>";
            echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
            <input type='text' class='g_input_txt fo items_".$x."' id='0_".$x."' name='0_".$x."' style='width:100%;' />
            <input type='hidden' name='bqty_'".$x."' id='bqty_".$x." title='0' style='display:block;visibility:hidden;'/>
          </td>
          <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
          <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
          <input type='hidden' id='all_serial_".$x."' title='0' name='all_serial_".$x."' />
          <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";

          echo "<td style='width:100px;' class='g_col_fixed'><input type='text' class='g_input_txt' readonly   id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/>
        </td>";
                                        /*echo "<td><input type='text' class='g_input_txt g_col_fixed model' id='2_".$x."' name='2_".$x."' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td class='g_col_fixed'><input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float:left;height:6px;width:6px;cursor:pointer'/>
                                        <input type='text' class='g_input_txt g_col_fixed batch btt_".$x."' id='1_".$x."' name='1_".$x."' style='margin:0;padding:0;width :56px; float: right; text-align:right;' readonly='readonly'/></td>";*/
                                        echo "<td><input type='button'  class='quns' id='btn_".$x."' style='margin:0;padding:5px;float:left; height:6px;width:6px;cursor:pointer'/>
                                        <input type='text' class='g_input_num qty qun qtycl".$x."' id='5_".$x."' name='5_".$x."' style='margin:0;padding:0;width :70px;float: right;'/></td>";
                                       /* echo "<td><input type='text' class='g_input_num foc' id='4_".$x."' name='4_".$x."' style='width : 100%;'/>
                                               <input type='hidden' class='tot_foc' id='tot_foc_".$x."'  name='tot_foc_".$x."' value='0'/>
                                               
                                             </td>";*/
                                             echo "<td><input type='text' class='g_input_amo price' id='3_".$x."' name='3_".$x."' style='width : 100%;'/>
                                             <input type='hidden' id='cost_".$x."'  name='cost_".$x."' value='0'/> 
                                           </td>";
                                           /*echo "<td class='g_col_fixed'><input type='text' class='g_input_amo dis_pre' id='6_".$x."' name='6_".$x."' readonly='readonly' style='width : 100%;'/></td>";*/
                                  /*      echo "<td><input type='text' class='g_input_amo dis' id='7_".$x."' name='7_".$x."' style='width : 100%;'/>
                                                  <input type='hidden' class='tot_discount' id='tot_dis_".$x."'  name='tot_dis_".$x."' value='0'/>  
                                                </td>";*/
                                                echo "<td><input type='text' class='g_input_amo amount g_col_fixed' id='8_".$x."' name='8_".$x."' style='width : 100%;text-align:right;' readonly='readonly'/>

                                                <input type='hidden' id='f_".$x."' name='f_".$x."' style='width : 100%;'/>
                                                <input type='hidden' id='bal_free_".$x."' name='bal_free_".$x."' style='width : 100%;'/>
                                                <input type='hidden' id='bal_tot_".$x."' name='bal_tot_".$x."' style='width : 100%;'/>
                                                <input type='hidden' id='free_price_".$x."' name='free_price_".$x."' style='width : 100%;'/>
                                                <input type='hidden' id='issue_qty_".$x."' name='issue_qty_".$x."' style='width : 100%;'/>
                                                <input type='hidden' id='item_min_price_".$x."' name='item_min_price_".$x."'/>
                                                <input type='hidden' id='subcode_".$x."' title='0' name='subcode_".$x."' />
                                              </td>";
                                              echo "<td style=''><input type='checkbox' id='free_".$x."' class='freitm' name='free_".$x."' style='width:100%;'/></td>";      
                                              echo "</tr>";
                                            }
                                            ?>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>

                                  <table border="0" style="width:100%;">
                                    <tr>
                                     <td>Customer Name</td>
                                     <td colspan="5" style="width:703px;"><input type="text" class="input_txt" title="" id="memo" name="memo" style="width:423px; text-align:right;" maxlength="100"/></td>
                                     <td style="margin-left:39px;"><b>Net Amount</b>
                                      <input type="text" id="net" name="net" class="hid_value g_input_amounts" style="width:60%;" readonly='readonly'/></td>


                                    </tr>

                                    <tr>
                                     <td>Sales Officer</td>
                                     <td colspan="5"><input type="text" name="sales_rep" id="sales_rep" style="width:120px;" class="input_active" title=""/>
                                      <input type="text" id="sales_rep2" style="width:265px;" class="hid_value" readonly="readonly" title=""/>
                                      <input type="button" id="sales_rep_create" title="..." value="...">
                                    </td>
                                    <td rowspan="3" colspan="3">


                                      <input type='hidden' name='additional_amount' id="additional_amount" />
                                      <input type='hidden' name='save_status' id="save_status"/>
                                      <input type='hidden' name='total_discount' id="total_discount" />
                                      <input type="hidden" name="total_amount" id="total_amount"/>

                                    </td>
                                  </tr>

                                  <tr>
                                   <td colspan="6"></td>
                                 </tr>

                                 <tr>
                                   <td colspan="7">
                                    <input type="button" id="btnExit" title="Exit" />
                                    <input type="button" id="btnResett" title="Reset" />
                                    <?php if($this->user_permissions->is_delete('g_t_sales_sum')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                                    <?php if($this->user_permissions->is_re_print('g_t_sales_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                                    <input type="hidden" name="srls" id="srls"/>
                                    <input type='hidden' id='transCode' value='63' title='63'/>
                                    <input type="button" title="Payments" id='showPayments'/>
                                    <input type="button" title='Save <F8>' id="btnSavee"/>
                                    <?php if($this->user_permissions->is_add('g_t_sales_sum')){ ?><input type="button" title='Save <F8>' id="btnSave"/><?php } ?>
                                    <?php if($this->user_permissions->is_approve('t_sales_return_sum')){ ?><input type="button"  id="btnApprove" title='Approve' /><?php } ?>
                                    <input type='hidden' id='app_status' name='approve' title='1' value='1'/>


                                  </td>
                                  <td >
                                  </td>
                                </tr>

                                <?php 
                                if($this->user_permissions->is_print('g_t_sales_sum')){ ?>
                                <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                                <?php } ?> 

                              </table><!--tbl1-->

                            </form>
                            <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                             <input type="hidden" name='by' value='g_t_sales_sum' title="g_t_sales_sum" class="report">
                             <input type="hidden" name='page' value='A4' title="A4" >
                             <input type="hidden" name='orientation' value='P' title="P" >
                             <input type="hidden" name='type' value='gift_sales' title="gift_sales" >
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