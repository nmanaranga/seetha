<?php if($this->user_permissions->is_view('m_customer')){ ?>
    <script type='text/javascript' src='<?=base_url()?>js/m_customer.js?<?= rand() ?>'></script>
    <h2>Customers</h2>
    <div>
        <table width="100%" border="0">
            <tr>
                <td valign="top" class="content" style="width: 640px;">
                    <div class="form" id="form">
                        <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_customer" >
                            <div id="tabs">
                                <ul>
                                    <li><a href="#tabs-1">Main</a></li>
                                    <li><a href="#tabs-2">Financial And Other</a></li>
                                    <li><a href="#tabs-3">Black List</a></li>
                                    <li><a href="#tabs-4">Events</a></li>
                                    <li><a href="#tabs-5">Contact</a></li>

                                </ul>
                                <div id="tabs-1">
                                    <fieldset>
                                        <legend>Personal Details</legend>
                                        <table border="0" style="width:100%;">
                                            <tr>
                                                <td>Code</td>
                                                <td><input type="text" class="input_active_hid" readonly title='<?php echo $get_next_code; ?>' id="code" name='code' maxlength="10" style="width:150px; border: 1px solid #003399;padding: 3px;font-weight: bold;font-size: 12px;font-family: Arial;"></td>

                                            </tr>


                                            <tr>
                                                <td>Type</td>
                                                <td colspan="3">  
                                                    <?php echo $type; ?>
                                                </td>
                                            </tr>    
                                            <tr>
                                                <td>NIC</td>
                                                <td><input type="text" class="input_txts" title='' id="nic" name="nic"  style="width:150px;" maxlength="12"/>
                                                    <input type="checkbox" title="1" id="inactive" name="inactive">Inactive</td>
                                                    <td><input type="checkbox" class='is_type' title="1" id="is_customer" name="is_customer">Is Customer
                                                        <!-- <td><input type="checkbox" class='is_type' title="1" id="is_guarantor" name="is_guarantor">Is Guarantor -->
                                                            <input type="hidden" class='is_type' title="1" id="is_guarantor" name="is_guarantor">

                                                        </tr>

                                                        <tr>
                                                            <td>Title</td>
                                                            <td colspan="3">
                                                                <select name='name_title' id='name_title' class='input_txt'>
                                                                    <option value="Mr">Mr</option>
                                                                    <option value="Master">Master</option>
                                                                    <option value="Mrs">Mrs</option>
                                                                    <option value="Ms">Ms</option>
                                                                    <option value="Miss">Miss</option>
                                                                    <option value="Rev">Rev</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Surname</td>
                                                            <td colspan="3"><input type="text" class="input_txts fn" title='' id="sur_name" name="sur_name"  style="width:430px;" maxlength="100"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Other Names</td>
                                                            <td colspan="3"><input type="text" class="input_txts fn" title='' id="other_name" name="other_name"  style="width:430px;" maxlength="100"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Initials</td>
                                                            <td colspan="3"><input type="text" class="input_txts fn" title='' id="initials" name="initials"  style="width:430px;" maxlength="100"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Full Name</td>
                                                            <td colspan="3"><input type="text" class="input_txts fn" title='' id="f_name" name="f_name"  style="width:430px;" maxlength="100"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Print Name</td>
                                                            <td colspan="3">
                                                                <input type="text" class="hid_value input_txts" title='' id="name" name="name"  style="width:430px;" maxlength="100"  />
                                                                <input type="checkbox" id='edit_name'> Edit Name
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Company Name</td>
                                                            <td colspan="3"><input type="text" class="input_txts" title='' id="company_name" name="company_name"  style="width:430px;" maxlength="100"/></td>
                                                        </tr><tr>
                                                            <td>Address 01</td>
                                                            <td colspan="3"><input type="text" class="input_txts" id="address1" title="" style="width: 200px;" name="address1"  maxlength="100"/></td>                       
                                                        </tr><tr>
                                                            <td>Address 02</td>
                                                            <td colspan="3"><input type="text" class="input_txts" id="address2" title="" style="width: 200px;"  name="address2" maxlength="100"/></td>                          
                                                        </tr><tr>
                                                            <td>Address 03</td>
                                                            <td colspan="3"><input type="text" class="input_txts" id="address3" title="" style="width: 200px;"  name="address3" maxlength="100"/></td>                      
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td colspan="3"><input type ="text" class="input_txts" id="email" title="" style="width:200px" name="email"  maxlength="50"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Postal Code</td>
                                                            <td colspan="3"><input type ="text" class="input_active_num" id="po_code" title="" style="width:200px" name="po_code"  maxlength="50"/></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Date Of Join</td>
                                                            <td colspan="3">
                                                                <input type="text" class="input_date_down_old" title="<?php echo date("Y-m-d"); ?>" id="doj" style="width: 150px;" name="doj" />
                                                            </td>  
                                                        </tr>  
                                                        <tr>
                                                            <td>Category</td>
                                                            <td colspan="3"> 
                                                                <input type="text" id="category_id" class="input_active" title="<?= $show_category->cus_cat?>" name="category_id" />   
                                                                <input type="text" class="hid_value" id="category" title="<?= $show_category->description?>" maxlength="255" style="width:280px;"/>
                                                                <input type="button"  id="btncategory" name="btncategory" title="..."/>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>Town</td>
                                                            <td colspan="3"> 
                                                                <input type="text" id="town_id" class="input_active" title="<?= $show_town->cus_town?>" name="town_id" />    
                                                                <input type="text" class="hid_value" id="town_name" title="<?= $show_town->description?>" maxlength="255" style="width:280px;"/>
                                                                <input type="button"  id="btntown" name="btntown" title="..."/>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>Area </td>
                                                            <td colspan="3"> 
                                                                <input type="text" id="area_id" class="input_active" title="<?= $show_area->cus_area?>" name="area_id" />
                                                                <input type="text" class="hid_value" id="area_name"  title="<?= $show_area->description?>" maxlength="255" style="width:280px;"/>
                                                                <input type="button"  id="btnarea" name="btnarea" title="..."/>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>Root</td>
                                                            <td colspan="3"> 
                                                               <input type="text" id="root_id" class="input_active" title="<?= $show_root->cus_route?>" name="root_id" /> 
                                                               <input type="text" class="hid_value" id="root_name" title="<?= $show_root->description?>" maxlength="255" style="width:280px;"/>
                                                               <input type="button"  id="btnroot" name="btnroot" title="..."/>
                                                           </td>
                                                       </tr>

                                                       <tr>
                                                        <td>Nationality</td>
                                                        <td colspan="3"><?php //echo $nation; ?>
                                                        <input type="text" id="nationality_id" class="input_active" title="<?= $show_nationality->cus_nationality?>" name="nationality_id" /> 
                                                        <input type="text" class="hid_value" id="nationality" title="<?= $show_nationality->description?>" style="width:280px;"/>
                                                        <input type="button"  id="btn_nationality" name="btn_nationality" title="..."/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Customer Status</td>
                                                    <td colspan="3"> 
                                                        <input type="text" id="cus_status" name="cus_status" class="input_active" title="<?= $show_status->cus_status?>">
                                                        <input type="text" class ="hid_value" id="hid_cus_status" name="hid_cus_status" style="width:280px;" title="<?= $show_status->description?>"/>
                                                        <input type="hidden" id="color_value" name="color_value" title="<?= $show_status->color?>">
                                                        <input type="button" id="btn_cus_status" name="btn_cus_status" title="..."/>
                                                    </td>
                                                </tr>

                                            </table>
                                        </fieldset>
                                    </div>
                                    <div id="tabs-2">
                                        <table style="width: 100%;" border="0" >
                                           <tr>



                                               <td align="left" colspan="2"><span style="margin-left:10px;margin-right:37px;">Balance</span><input type="text" class="g_input_amo hid_value" style="width:150px;" id="balance" Title=""  /></td>
                                           </td>&nbsp;</td>
                                       </tr>

                                       <tr>
                                          <td colspan="3">
                                             <fieldset>
                                                <legend>Credit</legend>
                                                <table>
                                                   <tr>
                                                       <td>Credit Limit</td>
                                                       <td><input type="text" class="input_txt" id="credit_limit" Title="" name="credit_limit"/></td>
                                                   </tr>

                                                   <tr>
                                                       <td>Credit Period</td>
                                                       <td><input type="text" class="input_txt" id="credit_period" Title="" name="credit_period"/></td>
                                                   </tr>



                                               </table>
                                           </fieldset>

                                       </td>					
                                   </tr>

                                   <tr>
                                      <td colspan="3">
                                         <fieldset>
                                            <legend>Tax</legend>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="is_tax" title="1" id="is_tax"/>
                                                    </td><td align="left" style="width:100px;">Tax Registered
                                                    </td>
                                                    <td align="right" >Tax No</td>
                                                    <td align="left"><input type="text" class="input_txt" name="tax_reg_no" id="tax_id" title="" /></td>

                                                </tr></table>
                                            </fieldset>	
                                        </td>

                                    </tr>
                                    <tr>
                                      <td>
                                         <fieldset>
                                            <legend>Other</legend>
                                            <table  border="0">
                                               <tr>
                                                  <td>Date Of Birth</td>
                                                  <td colspan="2"><input type="text" class="input_date_down_old" title="<?php echo date("Y-m-d"); ?>" id="dob" name="dob" /></td>

                                              </tr>
                                              <tr>
                                                  <td>Occupation</td>
                                                  <td colspan="2"><input type="text" class="input_txt" name="occupation" id="occupation" title="" /></td>

                                              </tr>

                                              <tr>
                                                  <td>Salary</td>
                                                  <td colspan="2"><input type="text" class="input_txt" name="salary" id="salary" title="" /></td>

                                              </tr>


                                          </table>
                                      </fieldset>	
                                  </td>
                              </tr>	
                              <tr>
                                <td>
                                    <fieldset>
                                        <legend>Contact Person Details</legend>
                                        <table  border="0">
                                            <tr>
                                                <td>Name</td>
                                                <td colspan="2"><input type="text" class="input_txt" title="" id="cont_name" name="cont_name" style="width:430px;"/></td>

                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td colspan="2"><input type="text" class="input_txt" name="cont_address" id="cont_address" title="" style="width:430px;"/></td>

                                            </tr>

                                            <tr>
                                                <td>Tel No</td>
                                                <td colspan="2"><input type="text" class="input_txt" name="cont_tel" id="cont_tel" title="" /></td>

                                            </tr>

                                            <tr>
                                                <td>Email</td>
                                                <td colspan="2"><input type="text" class="input_txt" name="cont_email" id="cont_email" title="" /></td>

                                            </tr>


                                        </table>
                                    </fieldset> 
                                </td>
                            </tr>   

                        </table>
                    </div>
                    <div id="tabs-3">
                        <table style="100%" border="0">
                            <tr>
                                <td>Black List</td>
                                <td><input type="checkbox" title="1" id="bl" name="bl"></td>
                            </tr>
                            <tr>
                                <td valign="top">Reason</td>
                                <td><textarea id="bl_reason" name="bl_reason" cols="75" rows="4"></textarea></td>
                            </tr><tr>
                                <td valign="top">Officer</td>
                                <td><textarea id="bl_officer" name="bl_officer" cols="75" rows="4"></textarea></td>
                            </tr><tr>
                                <td valign="top">Date</td>
                                <td><input type="text" class="input_date_down_old" title="<?php echo date("Y-m-d"); ?>" id="bl_date" name="bl_date" /></textarea></td>
                            </tr>
                        </table>
                    </div>
                    <div id="tabs-4">


                        <table style="width: 700px; border:1px solid #f9f9ec;" border="0" cellpadding="0" id="tgrid">
                         <thead>
                            <tr>
                                <th class="tb_head_th" style="width:135px;">Type</th>
                                <th class="tb_head_th" style="width:85px;">Date</th>
                                <th class="tb_head_th">Comment</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            //If change this counter of 25. Have to change module save function counter.
                            for($x=0; $x<15; $x++){
                                echo "<tr>";
                                echo "<td >
                                <input class='g_input_txt return_reason' type ='text' id='type_".$x."' name='type_".$x."' title='' style='background-color: #f9f9ec;border:solid 3px #f9f9ec;'/>
                                <input type='hidden' id='tcode_".$x."' name='tcode_".$x."' />
                                </td>";
                                echo '<td ><input  style="width:100px;background-color: #f9f9ec;border:dotted 1px #f9f9ec;" type="text" class="input_date_down_future" title="" id="ddate_'.$x.'" name="ddate_'.$x.'" /></td>';
                                echo "<td><input  class='g_input_txt' type='text' id='comment_".$x."' name='comment_".$x."' title='' style='background-color: #f9f9ec;border:solid 3px #f9f9ec;'/></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </tfoot></tfoot>
                </table>


            </div>
            <div id="tabs-5">
                <table style="width: 100%; align:center;" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="tb_head_th">Type</th>
                            <th class="tb_head_th">Description</th>
                            <th class="tb_head_th">TP No</th>
                        </tr>
                    </thead>

                    <?php
                            //If change this counter of 25. Have to change module save function counter.
                    for($x=0; $x<10; $x++){


                        $s = "<select name='contact1_".$x."' id='contact1_".$x."' class='contact'>";
                        $s .= "<option value='0'>---</option>";
                        $s .= "<option value='OFFICE'>OFFICE</option>";
                        $s .= "<option value='MOBILE'>MOBILE</option>";
                        $s .= "<option value='FAX'>FAX</option>";
                        $s .= "<option value='RESIDENT'>RESIDENT</option>";  
                        $s .= "<option value='Other'>Other</option>";                               
                        $s .= "</select>";

                        echo "<tr>";
                        echo "<td style='width : 150px'><input type ='text' id='type1_".$x."' name='type1_".$x."'class='input_txt' style='width: 100%; display:none;text-transform: uppercase;' /> $s</td>";
                        echo "<td style='width : 300px'><input type ='text' id='des_".$x."' name='des_".$x."'class='input_txt' style='width: 100%; text-transform: uppercase;' value=''/> </td>";
                        echo "<td style='width : 200px'><input type ='text' id='tp_".$x."' name='tp_".$x."' title='' class='input_txt' style='width: 100%;'/></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>

            </div>
            <div style="text-align: right; padding: 7px;">
                <input type="hidden" id="code_" name="code_" />
                <input type="button" id="btnExit" title="Exit" />
                <input type="button" id="btnPrint_1" title='Print' value="Print" />
                <?php if($this->user_permissions->is_add('m_customer')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>

                <input type="button" id="btnReset" title='Reset'>
            </div>
        </div>
    </form>
</div>
</td>
<td class="content" valign="top" style="width: 450px;">
    <div class="form" id="form" style="width: 450px;">
        <table>
            <tr>
                <td style="padding-right:64px;"><label>Search</label></td>
                <td>
                    <input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;">
                    <input type="button" id="cus_list" title="Customer List">
                </td>
            </tr>
        </table> 
        <div id="grid_body"><?=$table_data;?></div>
    </div>
</td>
</tr>
</table>

<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

   <input type="hidden" name='by' value='r_customer_list' title="r_customer_list" class="report">
   <input type="hidden" name='page' value='A4' title="A4" >
   <input type="hidden" name='pdf_no' id="pdf_no" value='' title="" >
   <input type="hidden" name='orientation' value='P' title="P" >
   <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
   <input type="hidden" name='qno' value='' title="" id="qno" >  
   <input type="hidden" name='code_find' value='' title="" id="code_find" >                
   <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
   <input type="hidden" name='dt' value='' title="" id="dt" > 

</form>

<form id="print_pdf2" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

   <input type="hidden" name='by' value='r_customer' title="r_customer" class="report">
   <input type="hidden" name='page' value='A4' title="A4" >
   <!-- <input type="hidden" name='pdf_no' id="pdf_no" value='' title="" > -->
   <input type="hidden" name='orientation' value='P' title="P" >
   <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
   <input type="hidden" name='cus_code' value='' title="" id="cus_code" >  
   <input type="hidden" name='code_find' value='' title="" id="code_find" >                
   <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
   <input type="hidden" name='dt' value='' title="" id="dt" > 

</form>
</div>
<?php } ?>