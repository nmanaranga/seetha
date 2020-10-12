<?php if($this->user_permissions->is_view('m_supplier')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_supplier.js'></script>
<h2>Supplier</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 600px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_supplier" >
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Main Detail</a></li>
                            <li><a href="#tabs-2">Financial</a></li>
                            <li><a href="#tabs-3">Contact</a></li>
                            <li><a href="#tabs-4">Comment</a></li>
                            <li><a href="#tabs-5">Black List</a></li>
                        </ul>
                        <div id="tabs-1">
                            <fieldset>
                                <legend>Personal Details</legend>
                            <!-- <table border="0" width="100%">
                            	<td style="width:100%;">Code</td>-->
                               <table border="0" style="width:100%;">	
                                <tr>
                                	<td>Code</td>
                                    <td>
                                        <input type="text" class="hid_value" value='<?php echo $sup_code; ?>' title='<?php echo $sup_code; ?>' id="code" name="code" maxlength="10" style="width:130px;text-transform:uppercase;">
                                        <input type="text" class="input_txt" value='<?php echo $sup_code_gen; ?>' title='<?php echo $sup_code_gen; ?>' id="code_gen"  name="code_gen" maxlength="3" style="width:50px;text-transform:uppercase;">
                                    </td>
                                    <!--
                                    <td colspan="2"><input type="checkbox" title="1" id="inactive" name="inactive">Inactive
									<input type="checkbox" title="1" id="bl" name="bl">Black List</td>
                               -->
                           </tr><tr>
                            <td>Name</td>
                            <td><input type="text" class="input_txt" title='' id="name" name="name"  maxlength="100" style="width:350px;"/></td>
                        </tr><tr>
                            <td>Payee Name</td>
                            <td><input type="text" class="input_txt" title=' ' id="payee_name" name="payee_name" style="width:350px;" maxlength="100"/></td>
                        </tr><tr>
                            <td><span style="width:200px;">Contact Name</span></td>
                            <td><input type="text" class="input_txt" id="contact_name" title=" " name="contact_name" style="width:350px;"  maxlength="100"/></td>                       
                        </tr>
                        <tr>
                            <td>Address 01</td>
                            <td><input type="text" class="input_txt" id="address1" name="address1" title="" style="width: 200px;"  maxlength="100"/></td>                          
                        </tr>

                        <tr>
                            <td>Address 02</td>
                            <td><input type="text" class="input_txt" id="address2" name="address2" title="" style="width: 200px;"  maxlength="100"/></td>                          
                        </tr><tr>
                            <td>Address 03</td>
                            <td><input type="text" class="input_txt" id="address3" name="address3" title="" style="width: 200px;"  maxlength="100"/></td>                      
                        </tr><tr>
                            <td>Email</td>
                            <td>
                                <input type="text" class="input_txt" id="email" title="" style="width:200px;" name="email" /> 
                            </td>                        
                        </tr>
                        <tr>
                            <td>Postal Code</td>
                            <td>
                                <input type="text" class="input_txt" id="postal_code" title="" style="width:200px;" name="postal_code" /> 
                            </td>                        
                        </tr>
                        <tr>
                            <td>Date Of Join</td>
                            <td>
                                <input type="text" class="input_date_down_old" title="<?php echo date("Y-m-d"); ?>" id="doj" style="width: 100px;" name="doj" />
                            </td>
                        </tr>

                        <tr>
                            <td>Category</td>
                            <td><?php echo $category; ?>

                               <input style="width:180px;" type="text" class="hid_value" id="category_id" title=""/>
                           </td>
                           <tr>
                               <td><input type="checkbox" title="1" id="is_inactive" name="is_inactive">Inactive</td>                                    
                               <td></td>
                           </tr>
                       </tr>

                   </table>
               </fieldset>
           </div>
           <div id="tabs-2">
              <table style="width:100%" >
                 <tr>
                    <td colspan="3">
                        <fieldset>
                            <table>
                               <tr>
                                <td><span style="margin-left:0px;margin-right:30px;">Balance</span><input type="text" class="hid_value g_input_amo"  id="balance" style="width:150px;" title="" /></td>								
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                 <fieldset>
                    <legend>Credit</legend>
                    <table>
                       <tr>
                          <td>Credit Limit</td>
                          <td><input type="text"  name="credit_limit" id="credit_limit" title=" " class="input_txt_f"/></td>
                      </tr>
                      <tr>
                          <td>Credit Period</td>
                          <td><input type="text" name="credit_period" id="credit_period"  title=" " class="input_txt_f"/>
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
                          <td><input type="checkbox" name="is_tax" id="is_tax" title="1"></td>

                          <td><span style="margin-right:25px;">Tax Registered</span></td>

                          <td>Tax No</td> <td><input type="text" id="tax" title="" class="input_txt_f" name="tax_reg_no"></td>
                      </tr>
                  </table>

              </fieldset>
          </td>
      </tr>
  </table>
</div>

<div id="tabs-3">
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

        $s = "<select name='contact_".$x."' id='contact_".$x."' class='contact'>";
        $s .= "<option value='0'>---</option>";
        $s .= "<option value='OFFICE'>OFFICE</option>";
        $s .= "<option value='MOBILE'>MOBILE</option>";
        $s .= "<option value='FAX'>FAX</option>";
        $s .= "<option value='RESIDENT'>RESIDENT</option>";                                 
        $s .= "<option value='Other'>Other</option>";
        $s .= "</select>";

        echo "<tr>";
        echo "<td style='width : 150px'><input type ='text' id='type_".$x."' name='type_".$x."'class='input_txt' style='width: 100%; display:none;text-transform: uppercase;' value=''/> $s</td>";
        echo "<td style='width : 200px'><input type ='text' id='des_".$x."' name='des_".$x."'class='input_txt' style='width: 100%; text-transform: uppercase;' value=''/> </td>";
        echo "<td style='width : 150px'><input type ='text' id='tp_".$x."' name='tp_".$x."' title='' class='input_txt' style='width: 100%;'/></td>";
        echo "</tr>";
    }
    ?>
</table>

</div>
<div id="tabs-4">
 <table style="width: 100%;" cellpadding="0">
  <thead>
     <tr>
        <th class="tb_head_th">Date</th>
        <th class="tb_head_th">Comments</th>
    </tr>
</thead>
<tbody>
    <?php
                            //If change this counter of 25. Have to change module save function counter.
    for($x=0; $x<10; $x++){
        echo "<tr>";
        echo "<td style='width : 150px'><input type ='text' id='date_".$x."' name='date_".$x."' class='input_date_down_future' style='width: 100%;' /></td>";
        echo "<td><input type ='text' id='comment_".$x."' name='comment_".$x."' title='' class='input_txt' style='width: 100%;'/></td>";
        echo "</tr>";
    }
    ?>
</tbody>
</table>
</div>

<div id="tabs-5">
    <table  border="0" style="width: 100%!important;">
        <tr>
            <td style="width: 50px!important;">Black List</td>
            <td style="width: 400px!important;"><input type="checkbox" title="1" id="is_blacklist" name="is_blacklist" ></td>
        </tr>
        <tr>
            <td valign="top">Reason</td>
            <td><textarea id="bl_reason" name="bl_reason" style="width:100%!important;"></textarea></td>
        </tr><tr>
            <td valign="top">Officer</td>
            <td><textarea id="bl_officer" name="bl_officer" style="width:100%!important;"></textarea></td>
        </tr><tr>
            <td valign="top">Date</td>
            <td><input type="text" class="input_date_down_old" title="<?php echo date("Y-m-d"); ?>" id="bl_date" name="bl_date" /></textarea></td>
        </tr> 
    </table>
</div>




</div>

<div style="text-align: right; padding-top: 10px;">
    <input type="hidden" id="code_" name="code_" />
    <input type="button" id="btnExit" title="Exit" />
    <?php if($this->user_permissions->is_add('m_supplier')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
        <input type="button" id="btnReset" title='Reset'>
    </div>
</form>
</td>
<td class="content" valign="top">
    <div class="form" class="form" id="form">
        <table>
            <tr>
                <td style="padding-right:64px;">
                    <label>Search</label>
                </td>
                <td>
                    <input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;">
                    <input type="button" id="sup_list" title="Supplier List">
                </td>
            </tr>
        </table> 
        <div id="grid_body"><?=$table_data;?></div>
    </div>
</td>
</tr>
<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
    <input type="hidden" name='by' value='t_grn_sum' title="r_supplier_2" class="r_supplier_2">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <!-- <input type="hidden" name='type' value='purchase' title="purchase" > -->
    <input type="hidden" name='header' value='false' title="false" >
</form>
</table>
<?php } ?>
