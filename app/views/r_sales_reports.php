<?php if($this->user_permissions->is_view('r_sales_reports')){ ?>
  <h2 style="text-align: center;">Sales Report </h2>
  <link rel="stylesheet" href="<?=base_url()?>css/report.css" />
  <script type="text/javascript" src="<?=base_url()?>js/r_sales_reports.js"></script>

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
          <table width="100%" border="0" cellpadding="2" cellspacing="0" style="font-size: 12px;">
            <tr>
              <td>Cluster</td>
              <td style="width: 155px;"><?php echo $cluster; ?></td>
            </tr>
            <tr>
              <td>Branch</td>
              <td>
               <select name='branch' id='branch' >
                <option value='0'>---</option>
              </select>
            </td>
          </tr>
          <input type="hidden" id="d_cl" title='<?php echo $d_cl ?>' name="d_cl"/>
          <input type="hidden" id="d_bc" title='<?php echo $d_bc ?>' name="d_bc"/>

          <tr>
            <td style="width:150px;">Department</td>
            <td><input type="text" class="input_txt" title="" id="department" name="department"/></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="department_des" name="department_des"  style="width: 250px;"></td>
          </tr>

          <tr>
            <td>Main Category</td>
            <td><input type="text" class="input_txt" title="" id="main_category" name="main_category"/></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="main_category_des" name="main_category_des" style="width: 250px;"></td>
          </tr>
          <tr>
            <td>Sub Category</td>
            <td><input type="text" class="input_txt" title="" id="sub_category" name="sub_category"/></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="sub_category_des" name="sub_category_des"  style="width: 250px;"></td>
          </tr>
          <tr>
            <td>Salesman</td>
            <td><input type="text" class="input_txt" title="" id="salesman" name="salesman" /></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="salesman_des" name="salesman_des"  style="width: 250px;"></td>
          </tr>
          <tr>
            <td>Supplier</td>
            <td><input type="text" class="input_txt" title="" id="supplier" name="supplier" /></td>
            <td colspan=""><input type="text" class="hid_value"  readonly="readonly" id="supplier_des" name="supplier_des"  style="width: 250px;"></td>
          </tr>
        </table>

        <table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px; padding-top:20px;">
          <br>
          <hr>
          <?php if($this->user_permissions->is_view('r_dep_wise_details')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_dep_wise_details' title="r_dep_wise_details" id="r_dep_wise_details" class="report" excel="true"/>Department wise Details Report
              </td>
            </tr>
          <?php } ?>
          <?php if($this->user_permissions->is_view('r_cat_wise_details')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_cat_wise_details' title="r_cat_wise_details" id="r_cat_wise_details" class="report" excel="true"/>Category wise Details Report
              </td>
            </tr>
          <?php } ?>
          <?php if($this->user_permissions->is_view('r_cat_wise_summary')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_cat_wise_summary' title="r_cat_wise_summary" id="r_cat_wise_summary" class="report" excel="true"/>Category wise Summary Report
              </td>
            </tr>
          <?php } ?>
          <?php if($this->user_permissions->is_view('r_sup_wise_sales_summary')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_sup_wise_sales_summary' title="r_sup_wise_sales_summary" id="r_sup_wise_sales_summary" class="report" excel="true"/>Supplier wise Sales Summary Report
              </td>
            </tr>
          <?php } ?> 
          <?php if($this->user_permissions->is_view('r_sm_wise_dep_summary')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_sm_wise_dep_summary' title="r_sm_wise_dep_summary" id="r_sm_wise_dep_summary" class="report" excel="true"/>Salesman wise Department Summary Report
              </td>
            </tr>
          <?php } ?>
          <?php if($this->user_permissions->is_view('r_sup_wise_sales_details')){ ?>
            <tr>
              <td>
                <input type='radio' name='by' value='r_sup_wise_sales_details' title="r_sup_wise_sales_details" id="r_sup_wise_sales_details" class="report" excel="true"/>Supplier wise Sales Details Report
              </td>
            </tr>
          <?php } ?>

        </table>
      </div>
      <div style="text-align: right; padding-top: 7px;">
        <button id="btnExit">Exit</button>
        <input type="button" id="print" title="Print"/>
        <input type="button" id="printExcel" title="Excel" disabled="true" />
      </div>
    </fieldset>

    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" id='type' name='type' value='' title="" >
    <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >


  </form>
</div>

<?php } ?>