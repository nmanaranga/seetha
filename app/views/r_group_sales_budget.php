<?php if($this->user_permissions->is_view('r_group_sales_budget')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/r_group_sales_budget.js'></script>
<h2>Group Sales Budget</h2>
<div class="dframe" id="mframe">  
<table width="60%" border="0" cellpadding="0" align="center" id="r_group_sb">
    <tr>
        <td valign="top" class="content" style="width:600px;">
               <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_group_sales_budget" >
              <fieldset>
                    <legend><b>Group Sales</b></legend>
                    <table border="0" style="width:800px;">
                    <tr>
                        <td style="width:100px;">Code</td>
                        <td colspan="2"><input type="text" class="input_active" id="code" name="code"  title="" style="width:150px;" maxlength="" />
                        <input type="text" class="hid_value" readonly="readonly" title='' id="des"   style="width:328px;" maxlength="255" readonly='readonly'/></td>
                        <td style="text-align:right; width:100px;">Date</td>
                        <td><input type="text" id="ddate" name="ddate" class="input_date_down_future" title="<?php  echo date('Y-m-d')?>" style="text-align:right;"/></td> 
                    </tr>
                    <tr>
                        <td>Date From</td>
                        <td><input type="text" id="fdate" name="fdate" class="input_active" title="" style="width:150px;" readonly='readonly'/></td>
                        <td>To  
                        <input type="text" id="tdate" name="tdate" class="input_active" title="" style="width:150px; float:right;" readonly='readonly' /></td>
                        <td style="text-align:right;">No</td>
                        <td><input type="text" class="input_active_num" name="no" id="no" title="<?=$max_no?>" />
                        <input type="hidden" id="hid" name="hid" title="0" /></td>
                        
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td colspan="2"><input type="text" class="input_active" id="catogory" name="catogory"  title="" style="width:150px;" maxlength="" readonly='readonly'/>
                        <input type="text" class="hid_value" readonly="readonly" title='' id="cat"   style="width:328px;" maxlength="255"/></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </fieldset>
        </td>
    </tr>
    <tr> 
        <td> 
            <fieldset>
                <legend><b>Budget</b></legend>  
            <div class="gridDiv">
                <table style="width:100%;" cellpadding="0" class="grid" border="0">
            <thead>
            <tr>
               <th  class="tb_head_th" style="width:100px;">Code</th>
               <th  class="tb_head_th" style="width:250px;">Description</th>
               <th  class="tb_head_th" style="width:100px;">DR</th>
               <th  class="tb_head_th" style="width:100px;">CR</th>
            </tr>
            </thead>
            <tbody id="item_ld">
                <?php
                    for($x=0; $x<25; $x++){
                    echo "<tr>";
                       echo "<td><input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='n_".$x."' name='n_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_amo amount dr_amount' id='1_".$x."' name='1_".$x."' style='border:dotted 1px #ccc;background-color:#ffffff; text-align:right;' /></td>";
                       echo "<td><input type='text' class='g_input_amo amount cr_amount' id='2_".$x."' name='2_".$x."' style='border:dotted 1px #ccc;background-color:#ffffff; text-align:right; ' /></td>";
                       echo "</tr>";
                    }
                ?>
            </tbody>
                <tr style="background-color: transparent;">
                   <td> </td>
                   <td style="text-align: right; font-weight: bold; font-size: 12px;">Total</td>
                   <td><input type='text' class='g_input_txt ' id='dr_tot' name='dr_tot' style='border:dotted 1px #ccc;background-color:#f9f9ec; width:100%; text-align:right;' /></td>
                   <td><input type='text' class='g_input_txt ' id='cr_tot' name='cr_tot' style='border:dotted 1px #ccc;background-color:#f9f9ec; width:100%; text-align:right;' /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
      </table>
  </div>
  <br>
            <table border="0" width="100%">
                 <tr>
                    <td style="width:100px;">Note</td>
                    <td colspan="3"><input type='text' class='input_active' style="width:100%;" id="note" name="note"> </td>
                </tr>
            </table> 
      <br> 
          <div style="text-align: right; padding-top: 7px;">
             <input type="button" id="btnExit" title="Exit" />  
             <?php if($this->user_permissions->is_delete('r_group_sales_budget')){ ?><input type="button" id="btnCancel" title="Cancel" /><?php } ?> 
            <?php if($this->user_permissions->is_delete('r_group_sales_budget')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
            <?php if($this->user_permissions->is_re_print('r_group_sales_budget')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>                     
            <?php if($this->user_permissions->is_add('r_group_sales_budget')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>             
         </div>
    </fieldset>
    </td> 
</tr>
</table>
  </form>
</div>
    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='r_group_sales_budget' title="r_group_sales_budget" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P">
                 <input type="hidden" name='type' value='purchase' title="purchase">
                 <input type="hidden" name='header' value='false' title="false">
                 <input type="hidden" name='nno' value='' title="" id="nno">
        </form>
<?php } ?>