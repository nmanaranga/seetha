<?php if($this->user_permissions->is_view('r_total_sale')){ ?>
    <script type='text/javascript' src='<?=base_url()?>js/r_total_sale.js'></script>
    <h2>Total Sale Reports</h2>
    <table width="100%">
        <tr>
            <td valign="top" class="content" style="width: 480px;">
                <div class="form" id="form">
                    <form id="print_pdf" class="printExcel" action="<?php echo site_url(); ?>" method="post" target="_blank">
                      <table>
                          <tr><td>Date </td><td ><input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" />
                            To <input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  /></td></tr>

                            <tr>
                                <td>Cluster</td>
                                <td><?php echo $cluster; ?></td>
                            </tr>

                            <tr>
                                <td>Branch</td>
                                <td>
                                   <select name='branch' id='branch' style='width:179px;'>
                                    <option value='0'>---</option>
                                </select>
                            </td>
                        </tr>
                        <input type="hidden" id="d_cl" title='<?php echo $d_cl ?>' name="d_cl"/>
                        <input type="hidden" id="d_bc" title='<?php echo $d_bc ?>' name="d_bc"/>
                        <tr>
                            <td>Employee</td>
                            <td><input type="text" class="input_txt" title="" id="emp" name="emp" style="width: 180px;"/></td>
                            <td><input type="text" class="hid_value"  readonly="readonly" id="emp_des" name="emp_des" style="width: 250px;"></td>
                        </tr>
                        <tr>
                            <?php if($this->user_permissions->is_view('r_total_sale')){ ?>
                                <tr>
                                    <td>
                                       <input type='radio' name='by'  value='r_total_sale' title="r_total_sale" class="report" excel="true"/>Total Sales Report
                                   </td>			    
                               </tr>
                           <?php } ?>
                           <?php if($this->user_permissions->is_view('r_total_sale_return')){ ?>
                               <tr>
                                <td>
                                    <input type='radio' name='by'  value='r_total_sale_return' title="r_total_sale_return" class="report" />Total Sales Return
                                </td>               
                            </tr>
                        <?php } ?>

                        <?php if($this->user_permissions->is_view('r_total_sale_emp')){ ?>
                            <tr>
                                <td>
                                    <input type='radio' name='by'  value='r_total_sale_emp' title="r_total_sale_emp" class="report" excel="true"/>Employee Total Sales Report
                                </td>               
                            </tr>
                        <?php } ?>
                        <?php if($this->user_permissions->is_view('r_total_sale_gross_profit2')){ ?>
                            <tr>
                                <td>
                                    <input type='radio' name='by'  value='r_total_sale_gross_profit2' title="r_total_sale_gross_profit2" class="report" excel="true"/>Item Wise Total Sales Report               
                                </td>               
                            </tr>
                        <?php } ?>
                        <?php if($this->user_permissions->is_view('r_total_sale_inv_wise')){ ?>
                            <tr>
                                <td>
                                    <input type='radio' name='by'  value='r_total_sale_inv_wise' title="r_total_sale_inv_wise" class="report" excel="true"/>Invoice Wise Total SalesReport               
                                </td>               
                            </tr>
                        <?php } ?>
                        <?php if($this->user_permissions->is_view('r_employee_item_commission')){ ?>
                            <tr>
                                <td>
                                    <input type='radio' name='by'  value='r_employee_item_commission' title="r_employee_item_commission" class="report" excel="true"/>Employee Wise Item Commission              
                                </td>               
                            </tr>
                        <?php } ?>
                        <?php if($this->user_permissions->is_view('r_total_sale_gross_profit3')){ ?>
                            <tr>
                                <td>
                                    <input type='radio' name='by'  value='r_total_sale_gross_profit3' title="r_total_sale_gross_profit3" class="report" excel="true"/>Item Wise Total Sales Report (New)               
                                </td>               
                            </tr>
                        <?php } ?>
                        <tr>
                           <tr>
                               <td colspan="2" style="text-align: right;">
                                <input type="hidden" name="type" id="type"  title=""/>
                                <input type="button" title="Exit" id="btnExit" value="Exit">
                                <input type="button" title="Process" id="btnprocess" value="Process">
                                <input type="button" title="Print PDF" value="Print PDF" id="btnPrint"/>
                                <input type="button" id="printExcel" title="Excel" disabled="true" />
                            </td>
                        </tr>
                    </table>

                    <input type="hidden" name='page' value='A4' title="A4" >
                    <input type="hidden" name='orientation' value='P' title="P" >
                    <!-- <input type="hidden" name='type' value='19' title="19" > -->
                    <input type="hidden" name='header' value='false' title="false" >
                    <input type="hidden" name='qno' value='' title="" id="qno" >
                    <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                    <input type="hidden" name='dt' value='' title="" id="dt" >
                </form>
            </div>

        </table>
        <?php } ?>