<?php if($this->user_permissions->is_view('t_po_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_po_sum.js"></script>


<h2 style="text-align: center;">Purchase Order</h2>
<div class="dframe" id="mframe" style="width:950px;">
  <form method="post" action="<?=base_url()?>index.php/main/save/t_po_sum" id="form_">
    <table style="width: 100%" border="0">

      <tr>
        <td style="width: 100px;">Supplier</td>
        <td>
          <input type="text" class="input_active" id="supplier_id" name="supplier_id" title="" />

          <input type="text" class="hid_value" id="supplier" title="" style="width:300px;" readonly='readonly'/>
        </td>
        <td style="width: 50px;">Type</td>
        <td>
          <?php if($this->utility->get_is_store_in_branch('1')) {
           echo "<select style='width:100%' name='type' id='type'>
           <option value='1'>Main Store</option>
           </select>";
         }else{
           echo "<select style='width:100%' name='type' id='type'>
           <option value='2'>Direct</option>
           </select>";
         }
         ?>
       </td>

     </tr>

     <tr>
      <td style="width: 100px;">Approve No</td>
      <td>
       <input type="text" class="input_txt" name="approve_no" id="approve_no" title="" style="width:150px;" maxlength="10" />
       <input type="button" title='Load' id="load_req">
       <input type="button" title='Load' id="load_req_duplecate" >
       <?php $this->sd = $this->session->all_userdata(); if($this->sd['branch']=='MS'){ ?>
       &nbsp;&nbsp;<input type="checkbox" name="dpo" id="dpo">&nbsp;&nbsp;Direct PO
       <?php } ?>

     </td>
     <td style="width: 50px;">No</td>
     <td>
      <input type="text" class="input_active_num" name="id" id="id" style="width:100%" title="<?=$max_no?>"/>
      <input type="hidden" id="hid" name="hid" title="0" />
    </td>

  </tr>

  <tr>
    <td>Memo</td>
    <td><input type="text" class="input_txt" name="memo" id="memo" title="" style="width: 453px;" maxlength="255" /></td>
    <td style="width: 100px;">Date</td>
    <td style="width: 100px;">
      <?php if($this->user_permissions->is_back_date('t_po_sum')){ ?>
      <input type="text" style="width:100%; text-align:right;" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
      <?php }else{ ?> 
      <input type="text" style="width:100%; text-align:right;" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
      <?php } ?>   
    </td>
  </tr>

  <tr>
    <td style="width: 100px;">Ship To</td>
    <td>
      <?php echo $branch; ?>
      Print Cost &nbsp;&nbsp;<input type='checkbox' id='cost_print'/>
      <span style="margin-left:40px"> Delivery Date
        <input type="text" class="input_date_down_future" readonly="readonly" name="deliver_date" id="deliver_date" title="<?=date('Y-m-d')?>" />

      </span>
    </td>
    <td style="width: 100px;">Ref. No</td>
    <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%; text-align:right;"/></td>
  </tr>
  <tr>

    <td style="width: 100px;"></td>
    <td style="width: 100px;"></td>
  </tr>

  <tr>
    <td colspan="4" style="text-align: center;">
      <table style="width: 920px;" id="tgrid">
        <thead>
          <tr>
            <th class="tb_head_th" style="width: 105px;">Item Code</th>
            <th class="tb_head_th" style="width: 250px;">Item Name</th>
            <th class="tb_head_th"  style="width: 80px;">Module</th>
            <th class="tb_head_th" style="width: 80px;">Current QTY</th>
            <th class="tb_head_th" style="width: 80px;">Approve QTY</th>
            <th class="tb_head_th" style="width: 80px;">Cost</th>
            <th class="tb_head_th" style="width: 80px;">Total Amount</th>
          </tr>
        </thead><tbody>
          <?php
                                //if will change this counter value of 25. then have to change edit model save function.
          for($x=0; $x<25; $x++){
            echo "<tr>";
            echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
            <input type='text' class='g_input_txt fo' readonly id='0_".$x."' name='0_".$x."'style='width:100%;pointer-events:none;'/></td>";
            echo "<td><input type='text' class='g_input_txt g_col_fixed' id='n_".$x."' name='n_".$x."' readonly='readonly' style='width:100%;'/></td>";
            echo "<td><input type='text' class='g_input_num g_col_fixed' id='1_".$x."' name='1_".$x."' readonly='readonly'style='width:100%;'/></td>";
            echo "<td><input type='text' class='g_input_num g_col_fixed' id='2_".$x."' name='2_".$x."' readonly='readonly'style='width:100%;'/></td>";
            echo "<td><input type='text' class='g_input_num g_col_fixed qty' id='3_".$x."' name='3_".$x."' readonly='readonly'style='width:100%;'/></td>";
            echo "<td style=''><input type='text' class='g_input_amo price g_col_fixed'  id='4_".$x."' name='4_".$x."'  readonly='readonly'style='width:100%;'/></td>";
            echo "<td><input type='text' readonly='readonly' class='g_input_amo g_col_fixed amount' id='5_".$x."' name='5_".$x."' style='width:100%;'/>
            <input type='hidden' name='nno_".$x."' id='nno_".$x."'  />
            <input type='hidden' name='bc_".$x."' id='bc_".$x."'  /> 
            <input type='hidden' name='cl_".$x."' id='cl_".$x."'  />   
            </td>";
            echo "</tr>";
          }
          ?>
        </tbody>
        <tfoot>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

            <td colspan='2' align="right"><b>Total</b> &nbsp; <input type='text' class='hid_value g_input_amounts' id='total2' readonly="readonly" name='total' style="margin-top:15px;" /></td>
          </tr>
        </tfoot>

      </table>
      <div style="text-align: left; padding-top: 7px;">
        <input type="button" id="btnExit" title="Exit" />
        <input type="button" id="btnReset" title="Reset" />
        <?php if($this->user_permissions->is_delete('t_po_sum')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
        <?php if($this->user_permissions->is_re_print('t_po_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>

        <?php if($this->user_permissions->is_add('t_po_sum')){ ?><input type="button"  id="btnSave" title='Save <F8>' />
          <input type="button"  id="btnSavee" title='Save <F8>' style='display:none;'/>

            <?php } ?>

          </div>
        </td>
      </tr>
      <tr>
        <td colspan="6"></td>
      </tr>
      <tr>
        <td colspan="6" style="color:red; font-size:12px;">** For View Item Details, Double Click Item Code</td>
      </tr>
    </table>
    <?php 
    if($this->user_permissions->is_print('t_po_sum')){ ?>
    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
    <?php } ?> 
  </form>


  <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
   <input type="hidden" name='by' value='t_po_sum' title="t_po_sum" class="report">
   <input type="hidden" name='page' value='A4' title="A4" >
   <input type="hidden" name='orientation' value='P' title="P" >
   <input type="hidden" name='type' value='purchase_quotation' title="purchase_quotation" >
   <input type="hidden" name='header' value='false' title="false" >
   <input type="hidden" name='qno' value='' title="" id="qno" >
   <input type="hidden" name='rep_sup' value='' title="" id="rep_sup" >
   <input type="hidden" name='rep_ship_bc' value='' title="" id="rep_ship_bc" >
   <input type="hidden" name='rep_date' value='' title="" id="rep_date" >
   <input type="hidden" name='cost_prnt' value='' title="" id="cost_prnt" >
   <input type="hidden" name='rep_deliver_date' value='' title="" id="rep_deliver_date" >
   <input type="hidden" name='is_duplicate' value='0' title="0" id="is_duplicate" >

 </form>

</div>
<?php } ?>