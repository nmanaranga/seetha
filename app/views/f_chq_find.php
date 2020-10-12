<?php if($this->user_permissions->is_view('f_chq_find')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/f_chq_find.js'></script>
<h2>Cheques Registry</h2>
<div class="dframe" id="mframe2">
 <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
  <table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
    <tr>
      <td style="width:100px;">Cluster</td>
      <td style="width:605px;">
        <input type="text" id="cluster" name="cluster" class="input_txt" style="width:150px;"/>
        <input type="text" id="cluster_des" name="cluster_des" class="hid_value" title="" style="width:250px;" >
      </td>
      <td style="width:95px;">Type</td>
      <td style="width:300px;">
        <select name="chq_type" id="chq_type">
          <option value="1">Received Cheques</option>
          <option value="2">Issued Cheques</option>
          <option value="3">Cheques Acknowledgment </option>
        </select>

        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" id="find" style="width:103px;" value="FIND" title="FIND">
        <input type="hidden" id="p_type" name="p_type" title="customer">
      </td>

      <td></td>
      <td style="width:100px;">&nbsp;</td>
    </tr>
    <tr>
      <td style="width:100px;">Branch</td>
      <td style="width:605px;">
        <input type="text" id="branch" name="branch" class="input_txt" style="width:150px;"/>
        <input type="text" id="branch_des" name="branch_des" class="hid_value" title="" style="width:250px;" >

      </td>
      <td style="width:65px;"></td>
      <td style="width:37%;"></td>
    </tr>
    <tr>
      <td>Find</td>
      <td><input type="text" id="find_txt" name="find_txt" class="input_txt" style="width:403px"/>
    </tr>
    
   </table>

   <table>
     <tr>
       <td style="width:87px;">&nbsp;</td>
       <td>Date</td>
       <td><input type="checkbox" id='f_date' class='num_find'></td>
       <td style="width:10px;"></td>
       <td class="heading_top">Received Acc</td>
       <td><input type="checkbox" id='f_racc' class='num_find'></td>
       <td style="width:10px;"></td>
       <td>Cheque No</td>
       <td><input type="checkbox" id='f_chq' class='num_find'></td>
       <td style="width:10px;"></td>
       <td>Amount</td>
       <td><input type="checkbox" id='f_amount' class='num_find'></td>
       <td style="width:10px;"></td>
       <td class="heading_date">Realize Date</td>
       <td><input type="checkbox" id='f_rdate' class='num_find'></td>
     </tr>

     <tr>
       <td style="width:87px;">&nbsp;</td>
       <td>Account</td>
       <td><input type="checkbox" id='f_acc' class='num_find'></td>
       <td style="width:10px;"></td>
       <td>Bank</td>
       <td><input type="checkbox" id='f_bank' class='num_find'></td>
       <td style="width:10px;"></td>
       <td>Bank Branch</td>
       <td><input type="checkbox" id='f_branch' class='num_find'></td>
       <td style="width:10px;"></td>
       <td>Status</td>
       <td><input type="checkbox" id='f_status' class='num_find'></td>
       
     </tr>
   </table>

   <table>
     <tr>
      <td colspan="6" height="20">
         <hr class="hline"/>
      </td>
    </tr>
   </table>


   <table style="width:100%;" cellpadding="0" id="grid">
     <thead>
      <tr>
        <th  class="tb_head_th" style="width:40px;">Cluster</th>
        <th  class="tb_head_th" style="width:40px;">Branch</th>
        <th  class="tb_head_th" style="width:80px;">Date</th>
        <th  class="tb_head_th heading_top" style="width:150px;">  Received Acc  </th> 
        <th  class="tb_head_th" style="width:50px;">CHQ No</th>
        <th  class="tb_head_th" style="width:70px;">Amount</th>
        <th  class="tb_head_th" style="width:100px;">Account</th>
        <th  class="tb_head_th" style="width:120px;">Bank</th>
        <th  class="tb_head_th" style="width:120px;">Branch</th>
        <th  class="tb_head_th" style="width:140px;">Transaction</th>
        <th  class="tb_head_th" style="width:30px;">Transaction No</th>
        <th  class="tb_head_th" style="width:80px;">Status</th>
        <th  class="tb_head_th heading_date" style="width:80px;">Realize Date</th>
      </tr>
    </thead>
    <tbody id="item_ld">
      <?php
      for($x=0; $x<50; $x++){
        echo "<tr>";
        echo "<td><input type='text' class='g_input_txt' id='cl_".$x."' name='cl_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
        echo "<td><input type='text' class='g_input_txt' id='bc_".$x."' name='bc_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
        echo "<td><input type='text' class='g_input_txt' id='dt_".$x."' name='dt_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
        echo "<td>
        <input type='text' class='g_input_txt' id='c_".$x."' name='c_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/>
        <input type='hidden' id='ccode_".$x."' name='ccode_".$x."'/>
      </td>";
      echo "<td><input type='text' class='g_input_num' id='chqn_".$x."' name='chqn_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
      echo "<td><input type='text' class='g_input_amo' id='amnt_".$x."' name='amnt_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;'' readonly='readonly'/></td>";
      echo "<td><input type='text' class='g_input_txt' id='acc_".$x."' name='acc_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
      echo "<td>
      <input type='text' class='g_input_txt' id='b_".$x."' name='b_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/>
      <input type='hidden' id='bcode_".$x."' name='bcode_".$x."'/>
    </td>";
    echo "<td>
    <input type='text' class='g_input_txt' id='br_".$x."' name='br_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/>
    <input type='hidden' id='brcode_".$x."' name='brcode_".$x."'/>
  </td>";
  echo "<td><input type='text' class='g_input_txt' id='tr_".$x."' name='tr_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
  echo "<td><input type='text' class='g_input_num' id='trn_".$x."' name='trn_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
  echo "<td><input type='text' class='g_input_txt' id='status_".$x."' name='status_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
  echo "<td><input type='text' class='g_input_txt' id='rdate_".$x."' name='rdate_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
  echo "</tr>";
}
?>
</tbody>
<tfoot>
  <tr style="background-color: transparent;">
   <td style="text-align: right; font-weight: bold; font-size: 12px;"></td>
   <td>&nbsp;</td>
 </tr>
</tfoot>
</table>
<table style="width:100%;">
 <input type="hidden" name='by' value='f_chq_find' title="f_chq_find" class="report">
 <input type="hidden" name='page' value='A4' title="A4" >
 <input type="hidden" name='orientation' value='L' title="L" >
 <input type="hidden" name='type' value='f_chq_find' title="f_chq_find" >
 <tr>
  <td align="right"><input type="button" id="btnExit" title="Exit" />
   <input type="hidden" name="code_" id="code_"/>   
   <input name="button" type="button" id="btnReset" title='Reset' />
   <!-- <?php if($this->user_permissions->is_re_print('f_chq_find')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?> -->
 </td>
</tr>
</table>
</form>
</div>
<?php } ?>