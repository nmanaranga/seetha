<?php if($this->user_permissions->is_view('r_stock_report')){ ?>
  <h2 style="text-align: center;">Stock Report </h2>
  <link rel="stylesheet" href="<?=base_url()?>css/report.css" />
  <script type="text/javascript" src="<?=base_url()?>js/r_stock_report.js"></script>

  <div class="dframe" id="r_view2" style="width: 1100px;">
    <form id="print_pdf" class="printExcel" action="<?php echo site_url(); ?>" method="post" target="_blank">

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
          </tr>
          <input type="hidden" id="d_cl" title='<?php echo $d_cl ?>' name="d_cl"/>
          <input type="hidden" id="d_bc" title='<?php echo $d_bc ?>' name="d_bc"/>

          <tr>
            <td style="width:150px;">Department</td>
            <td><input type="text" class="input_txt" title="" id="department" name="department"/></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="department_des" name="department_des"  style="width: 250px;"></td>
            <td>Unit</td>
            <td><input type="text" class="input_txt" title="" id="unit" name="unit" /></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="unit_des"  style="width: 250px;"></td>
          </tr>

          <tr>
            <td>Main Category</td>
            <td><input type="text" class="input_txt" title="" id="main_category" name="main_category"/></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="main_category_des" name="main_category_des" style="width: 250px;"></td>

            <td>Brand</td>
            <td><input type="text" class="input_txt" title="" id="brand" name="brand" /></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="brand_des"  style="width: 250px;"></td>
          </tr>
          <tr>
            <td>Sub Category</td>
            <td><input type="text" class="input_txt" title="" id="sub_category" name="sub_category"/></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="sub_category_des" name="sub_category_des"  style="width: 250px;"></td>
            <td>Supplier</td>
            <td><input type="text" class="input_txt" title="" id="supplier" name="supplier"/></td>
            <td align="left" colspan=""><input  class="hid_value" id="supplier_des"  style="width: 250px;"  readonly="readonly" /></td>
          </tr>
          <tr>
            <td>Item</td>
            <td><input type="text" class="input_txt" title="" id="item" name="item" /></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="item_des" name="item_des"  style="width: 250px;"></td>
            <td>Sub Item</td>
            <td><input type="text" class="input_txt" title="" id="sub_item" name="sub_item" /></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="sub_item_des"  style="width: 250px;"></td>

          </tr>

          <tr>
            <td>Ref No</td>
            <td><input type="text" class="input_txt" title="" id="ref_no" name="ref_No" /></td>
            <td class="minus">Minus Items<input type="checkbox" class="input_txt" title="" id="minus" name="minus" value="1" /></td>
            <td colspan="2" >Non Serial Item<input type="checkbox" class="input_txt" title="" id="non_serial" name="non_serial" value="1" /></td>      
          </tr>
        </table>

        <table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px; padding-top:20px;">
          <br>
          <hr>
          <?php if($this->user_permissions->is_view('r_stock_in_hand')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_stock_in_hand' title="r_stock_in_hand" id="r_stock_in_hand" class="report" excel="true"/>Stock In Hand 
              </td>
            </tr>
          <?php } ?>

          <?php if($this->user_permissions->is_view('r_batch_in_hand')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_batch_in_hand' title="r_batch_in_hand" id="r_batch_in_hand" class="report" excel="true"/>Stock In Hand Batch Wise
              </td>
            </tr>
          <?php } ?>

          <?php if($this->user_permissions->is_view('r_serial_in_hand')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_serial_in_hand' title="r_serial_in_hand" id="r_serial_in_hand" class="report" excel="true"/>Stock In Hand Serial Wise
              </td>
            </tr>
          <?php } ?>
          <tr>
            <td>
              <input type='radio' name='by' value='r_6month_before' title="r_6month_before" id="r_6month_before" class="report"/>Available Stock Before 6 Month Purchasing (Only for Electronic Items)
            </td>
          </tr>
          <tr>
           <td>
            <input type='radio' name='by' value='r_stock_age' title="r_stock_age" id="r_stock_age" class="report"/> Stock Aging Report (Only for Furniture Items)
          </td>
        </tr>

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
        <?php if($this->user_permissions->is_view('r_serial_in_hand')){ ?>
          <tr>
            <td>
              <input type='radio' name='by' value='r_serial_in_hand_all_branch' title="r_serial_in_hand_all_branch" id="r_serial_in_hand_all_branch" class="report" excel="true"/>Serial In Hand All Branch
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
              <input type='radio' name='by' excel='true' value='item_lists' title="item_lists" id="item_lists" class="report"/>Item List
            </td>
          </tr>
        <?php } ?>

        <?php if($this->user_permissions->is_view('r_stock_details')){ ?>
          <tr>
            <td>
              <input type='radio' name='by' value='r_stock_details' title="r_stock_details" id="r_stock_details" class="report"/>Stock Movement
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

        <?php } ?>
        <?php // if($this->user_permissions->is_view('r_po_status')){ ?>
          <tr>
            <td>
              <input type='radio' name='by' value='r_open_stock' title="r_open_stock" id="r_open_stock" class="report"/>Opening Stock Report
            </td>
          </tr>
          <?php if($this->user_permissions->is_view('r_open_stock_branch')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_open_stock_branch' title="r_open_stock_branch" id="r_open_stock_branch" class="report"/>Opening Stock Report - Branch Wise
              </td>
            </tr>
          <?php } ?>
          <?php // } ?>
          <?php // if($this->user_permissions->is_view('r_po_status')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_sub_item' title="r_sub_item" id="r_sub_item" class="report"/>Sub Item Stock Report
              </td>
            </tr>
            <?php // } ?>

          </table>




        </div>
        <div style="text-align: right; padding-top: 7px;">


          <button id="btnExit">Exit</button>
          <!-- <button id="print">Print</button> -->
          <input type="button" id="print" title="Print"/>
          <input type="button" id="printExcel" title="Excel" disabled="true" />

        </div>
      </fieldset>




      <!-- <input type="hidden" id='by' name='by' value='' title="" class="report"> -->
      <input type="hidden" name='page' value='A4' title="A4" >
      <input type="hidden" name='orientation' value='P' title="P" >
      <input type="hidden" id='type' name='type' value='' title="" >
      <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >


    </form>
  </div>

  <?php } ?>