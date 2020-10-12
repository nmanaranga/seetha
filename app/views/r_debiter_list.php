<?php if($this->user_permissions->is_view('r_debiter_list')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_debiter_list.js'></script>
<script type='text/javascript' src='<?=base_url()?>js/m_client_application.js'></script>
<h2>Debitor Reports</h2>
<table width="100%">
  <tr>
    <td valign="top" class="content" style="width: 480px;">
      <div class="form" id="form">
       <form id="print_pdf" class="printExcel" action="<?php echo site_url(); ?>" method="post" target="_blank">
        <table>
          <tr><td>Date </td><td><input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" />
            To <input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  /></td></tr>
            


            <tr>
              <td style="width:83px;">Cluster</td>
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
              <td style="width: 100px;">Customer</td>
              <td>
                <input type="text" class="input_active" id="cus_id" name="cus_id"title="" />
                
                <input type="text" class="hid_value" id="customer" title="" style="width:300px;" readonly='readonly'/>
              </td>
            </tr>
            <tr>
              <td style="width: 100px;">Area</td>
              <td>
                <input type="text" class="input_active" id="area_code" name="area_code"title="" />
                
                <input type="text" class="hid_value" id="area" title="" style="width:300px;" readonly='readonly'/>
              </td>
            </tr>
            <tr>
              <input type="hidden" id="d_cl" title='<?php echo $d_cl ?>' name="d_cl"/>
              <input type="hidden" id="d_bc" title='<?php echo $d_bc ?>' name="d_bc"/>
              
              <?php if($this->user_permissions->is_view('r_customer_list')){ ?> 	
              <tr>
               <td colspan="2">
                <input type='radio' name='by' value='r_customer_list' title="r_customer_list" class="report"/>Category wise Customer</td>
                
              </tr>
              <?php } ?>

              <?php if($this->user_permissions->is_view('r_customer_area_list')){ ?> 
              <tr>
               <td colspan="2">
                 <input type='radio' name='by' value='r_customer_area_list' title="r_customer_area_list" class="report"/>Customer Area List</td>
                 
               </tr>
               <?php } ?>

               <?php if($this->user_permissions->is_view('r_customer_town_list')){ ?> 
               <tr>
                 <td colspan="2">
                   <input type='radio' name='by' value='r_customer_town_list' title="r_customer_town_list" class="report"/>Customer Town List</td>
                   
                 </tr>
                 <?php } ?>

                 <?php if($this->user_permissions->is_view('r_customer_balances')){ ?> 
                 <tr>
                   <td colspan="2">
                     <input type='radio' name='by' value='r_customer_balances' title="r_customer_balances" class="report" excel="true"/>Customer Balance</td>
                     
                   </tr>
                   <?php } ?>

                   <?php if($this->user_permissions->is_view('r_customer_analysis')){ ?> 
                   <tr>
                    <td colspan="2">
                      <input type='radio' name='by' value='r_customer_analysis' title="r_customer_analysis" class="report" excel="true"/>Customer Age Analysis Report</td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <input type='radio' name='by' value='r_customer_analysis_det' title="r_customer_analysis_det" class="report"/>Customer Age Analysis Detail Report</td>
                      </tr>
                      <?php } ?>
                      <?php if($this->user_permissions->is_view('r_cus_ledger_card')){ ?> 
                      <tr>
                        <td colspan="2">
                          <input type='radio' name='by' value='r_cus_ledger_card' title="r_cus_ledger_card" class="report" excel="true" />Customer Ledger Card</td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <input type='radio' name='by' value='r_cus_ledger_card_inv' title="r_cus_ledger_card_inv" class="report" />Invoice Wise Customer Ledger Card</td>
                          </tr>
                          <?php } ?>

                          <tr>
                            <td colspan="2">
                              <input type='radio' name='by' value='r_cus_balance_details' title="r_cus_balance_details" class="report" excel="true" id="cus_bal"/>Customer Balance Details</td>
                            </tr>

                            <tr>
                             <td colspan="3" style="text-align: right;">
                              <input type="hidden" name="type" id="type"  title=""/>
                              <input type="button" title="Exit" id="btnExit" value="Exit">
                              <input type="button" id="btnPrint" title="Print"/>
                              <input type="button" id="printExcel" title="Excel" disabled="true" />
                            </td>
                          </tr>
                        </table>

                        <input type="hidden" name='page' value='A4' title="A4" >
                        <input type="hidden" name='orientation' value='P' title="P" >
                        <input type="hidden" name='type' value='19' title="19" >
                        <input type="hidden" name='header' value='false' title="false" >
                        <input type="hidden" name='qno' value='' title="" id="qno" >
                        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                        <input type="hidden" name='dt' value='' title="" id="dt" >
                        <input type="hidden" name='cu_id' value='' title="" id="cu_id" >
                        <input type="hidden" name='are_id' value='' title="" id="are_id" >
                      </form>
                    </div>
                    
                  </table>
                  <?php } ?>