<?php if($this->user_permissions->is_view('r_stock_report')){ ?>
<h2 style="text-align: center;">Stock Report </h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_stock_report.js"></script>

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

                               <!-- <?php echo $branch; ?> -->
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


                        <!--
                        <tr>
                            <td style="width:150px;">Store</td>
                            
                            <td><input type="text" class="input_txt" title="" id="store" name="store"/></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="store_des"  style="width: 250px;"></td>
                        </tr>
                        -->
                        <tr>
                            <td style="width:150px;">Department</td>
                            
                            <td><input type="text" class="input_txt" title="" id="department" name="department"/></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="department_des"  style="width: 250px;"></td>
                        
                            <td>Unit</td>
                           
                            <td><input type="text" class="input_txt" title="" id="unit" name="unit" /></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="unit_des"  style="width: 250px;"></td>
                        </tr>
                        
                        <tr>
                            <td>Main Category</td>
                            
                            <td><input type="text" class="input_txt" title="" id="main_category" name="main_category"/></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="main_category_des" style="width: 250px;"></td>
                            
                            <td>Brand</td>
                           
                            <td><input type="text" class="input_txt" title="" id="brand" name="brand" /></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="brand_des"  style="width: 250px;"></td>
                            

                        </tr>

                        <tr>
                            <td>Sub Category</td>
                            
                            <td><input type="text" class="input_txt" title="" id="sub_category" name="sub_category"/></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="sub_category_des"  style="width: 250px;"></td>
                            
                            <td>Supplier</td>
                         
                            <td><input type="text" class="input_txt" title="" id="supplier" name="supplier"/></td>
                            <td align="left" colspan=""><input  class="hid_value" id="supplier_des"  style="width: 250px;"  readonly="readonly" /></td>
                        </tr>

                    
                        <tr>
                            <td>Item</td>
                           
                            <td><input type="text" class="input_txt" title="" id="item" name="item" /></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="item_des"  style="width: 250px;"></td>
                        </tr>
                        <tr>
                            <td>Ref No</td>
                            <td><input type="text" class="input_txt" title="" id="ref_no" name="ref_No" /></td>
                        </tr>
                        <!--
                        
                        <tr>

                        <td>Unit</td>
                           
                            <td><input type="text" class="input_txt" title="" id="unit" name="unit" /></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="unit_des"  style="width: 250px;"></td>
                        </tr>

                        <tr>
                        <td>Brand</td>
                           
                            <td><input type="text" class="input_txt" title="" id="brand" name="brand" /></td>
                            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="brand_des"  style="width: 250px;"></td>
                        </tr>
                                        
                        <tr>
                          <td>Supplier</td>
                         
                         <td><input type="text" class="input_txt" title="" id="supplier" name="supplier"/></td>
                          <td align="left" colspan=""><input  class="hid_value" id="supplier_des"  style="width: 250px;"  readonly="readonly" /></td>
                        </tr>
                    
                        -->
                      


                    </table>

                    <table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px; padding-top:20px;">
<br>
<hr>
                    <?php if($this->user_permissions->is_view('r_stock_in_hand')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_stock_in_hand' title="r_stock_in_hand" id="r_stock_in_hand" class="report" checked="checked"/>Stock In Hand
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_batch_in_hand')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_batch_in_hand' title="r_batch_in_hand" id="r_batch_in_hand" class="report"/>Stock In Hand Batch Wise
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_serial_in_hand')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_serial_in_hand' title="r_serial_in_hand" id="r_serial_in_hand" class="report"/>Stock In Hand Serial Wise
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_bin_card_stock')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_bin_card_stock' title="r_bin_card_stock" id="r_bin_card_stock" class="report"/>Bin Card
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_stock_detail')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_stock_detail' title="r_stock_detail" id="r_stock_detail" class="report"/>Stock Details
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_item_department')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_item_department' title="r_item_department" id="r_item_department" class="report"/>Department List
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_item_category')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_item_category' title="r_item_category" id="r_item_category" class="report"/>Item Category
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_sub_item_category')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_sub_item_category' title="r_sub_item_category" id="r_sub_item_category" class="report"/>Item Sub Category
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('item_lists')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='item_lists' title="item_lists" id="item_lists" class="report"/>Item List
                    </td>
                    </tr>
                    <?php } ?>

                    <?php if($this->user_permissions->is_view('r_stock_details')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_stock_details' title="r_stock_details" id="r_stock_details" class="report"/>Stock Detail
                    </td>
                    </tr>
                    <?php } ?>
                    <?php if($this->user_permissions->is_view('r_po_qty_received')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_po_qty_received' title="r_po_qty_received" id="r_po_qty_received" class="report"/>Purchase Order Quantity Received
                    </td>
                    </tr>
                    <?php } ?>
                    <?php if($this->user_permissions->is_view('r_po_status')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_po_status' title="r_po_status" id="r_po_status" class="report"/>Purchase Request Status
                    </td>
                    </tr>
                    <?php } ?>
                     <?php // if($this->user_permissions->is_view('r_po_status')){ ?>
                    <tr>
                    <td>
                      <input type='radio' name='by' value='r_open_stock' title="r_open_stock" id="r_open_stock" class="report"/>Open Stock Report
                    </td>
                    </tr>
                    <?php // } ?>
                </table>




        </div>
        <div style="text-align: right; padding-top: 7px;">
        

        <button id="btnExit">Exit</button>
        <button id="print">Print</button></div>
    </fieldset>
         

        

         <input type="hidden" id='by' name='by' value='' title="" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='P' title="P" >
         <input type="hidden" id='type' name='type' value='' title="" >
         <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >


</form>
</div>

<?php } ?>