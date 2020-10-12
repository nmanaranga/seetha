<?php if($this->user_permissions->is_view('r_gift_voucher_report')){ ?>
<h2 style="text-align: center;">Gift Voucher Report </h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_gift_voucher_report.js"></script>

<div class="dframe" id="r_view2" style="width: 1100px;">
    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
   
    <fieldset>
        <legend>Date</legend>
        <table>
            <tr>
                <td><font size="2">From</font></td>
                <td><input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" /></td>
                <td style="padding-left:40px;"><font size="2">To</font></td>
                <td><input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  /></td>
            </tr>
        </table>
    </fieldset>    

    <fieldset>
        <legend>Category</legend>
        <div id="report_view" style="overflow: auto;">

           <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">
                        
                        <tr>
                            <td>Cluster</td>
                            <td><?php echo $cluster; ?></td>

                        </tr>

                        <tr>
                            <td>Branch</td>
                            <td>
                               <select name='branch' id='branch' >
                                <option value='0'>---</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Store</td>
                            <td>

                                 <select name='store' id='store' >
                                <option value='0'></option>
                                </select>


                              <!--  <?php echo $stores; ?></td> -->

                        </tr>

                        <input type="hidden" id="d_cl" title='<?php echo $d_cl ?>' name="d_cl"/>
                        <input type="hidden" id="d_bc" title='<?php echo $d_bc ?>' name="d_bc"/>

                     
                    
                        <tr>
                            <td>Item</td>
                           
                            <td colspan="2"><input type="text" class="input_txt" title="" id="item" name="item" />
                            <input type="text" class="hid_value"  readonly="readonly" id="item_des"  style="width: 250px;"></td>

                            
                        </tr>
                        

                    </table>

                    <table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px; padding-top:20px;">
                    <br>
                    <hr>
                    <?php if($this->user_permissions->is_view('r_gift_stock_in_hand')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_gift_stock_in_hand' title="r_gift_stock_in_hand" id="r_gift_stock_in_hand" class="report"/>Gift Voucher - Stock In Hand 
                    </td>
                    </tr>
                    <?php } ?>

                    

                   
                    <?php if($this->user_permissions->is_view('r_bin_card_gift_stock')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_bin_card_gift_stock' title="r_bin_card_gift_stock" id="r_bin_card_gift_stock" class="report"/>Bin Card
                    </td>
                    </tr>
                    <?php } ?>
                   
                    <!-- <tr>
                    <td>
                      <input type='radio' name='by' value='r_gift_voucher_details' title="r_gift_voucher_details" id="r_gift_voucher_details" class="report"/>Gift Voucher Details
                    </td>
                    </tr>

                    <tr>
                        <td>
                            <input type='radio' name='by' value='r_internal_transfer_order_list' title="r_internal_transfer_order_list" id="r_internal_transfer_order_list" class="report"/>Internal Transfer Order List
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='radio' name='by' value='r_internal_transfer_list' title="r_internal_transfer_list" id="r_internal_transfer_list" class="report"/>Internal Transfer List
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type='radio' name='by' value='r_internal_transfer_receive_list' title="r_internal_transfer_receive_list" id="r_internal_transfer_receive_list" class="report"/>Internal Transfer Received List
                        </td>
                    </tr>

                     <tr>
                        <td>
                            <input type='radio' name='by' value='r_purchase_details' title="r_purchase_details" id="r_purchase_details" class="report"/>Purchase Details
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type='radio' name='by' value='r_sales_details' title="r_sales_details" id="r_sales_details" class="report"/>Sales Details
                        </td>
                    </tr> -->
                </table>




        </div>
        <div style="text-align: right; padding-top: 7px;">
        

        <button id="btnExit">Exit</button>
        <button id="print">Print </button></div>
    </fieldset>
         

        

         <input type="hidden" id='by' name='by' value='' title="" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='P' title="P" >
         <input type="hidden" id='type' name='type' value='' title="" >
         <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >


</form>
</div>

<?php } ?>