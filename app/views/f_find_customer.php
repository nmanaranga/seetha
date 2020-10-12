<?php if($this->user_permissions->is_view('f_find_customer')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/f_find_customer.js'></script>
<h2>Find Customer Balance</h2>
<div class="dframe" id="mframe2">
   <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
      <table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
        <tr>
            <td style="width:100px;">Search All</td>
            <td><input type="text" id="pop_searchs" name="search" class="input_txt" title="" style="width:160px;" title=""/></td>
            <td style="width:50px;">As At Date</td>
            <td style="width:450px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>"  style="text-align:right;"/>
              <input type="button" id="find_date" name="find_date" value="FIND" title="FIND"></td>
            <td style="width:100px;">&nbsp;</td>
         </tr>
        <tr>
            <td style="width:100px;">Search by Area  </td>
            <td style="width:100px;"><input type="text" id="pop_search_area" name="search" class="input_txt" title="" style="width:160px;" title=""/></td>
            <td style="width:100px;">&nbsp;</td>
            <td style="width:100px;">&nbsp;</td>
            <td style="width:100px;">&nbsp;</td>
         </tr>
         <tr>
            <td colspan="6" height="20">
               <hr class="hline"/>
            </td>

         </tr>
      </table>
      <table style="width:100%;" cellpadding="0" id="grid">
         <thead>
            <tr>
               <th  class="tb_head_th" style="width:80px;">Code</th>
               <th  class="tb_head_th" style="width:100px;">NIC</th>
               <th  class="tb_head_th" style="width:150px;">Name</th>
               <th  class="tb_head_th" style="width:250px;">Address</th>
               <th  class="tb_head_th" style="width:80px;">Balance</th>
               <th  class="tb_head_th" style="width:80px;">PD Value</th>
               <th  class="tb_head_th" style="width:80px;">Rtn Chq Value</th>
               <th  class="tb_head_th" style="width:80px;">Area</th>
               <th  class="tb_head_th" style="width:80px;">Tel No</th>
            </tr>
         </thead>
         <tbody id="item_ld">
            <?php
               for($x=0; $x<50; $x++){
                    echo "<tr>";
                       echo "<td><input type='text' class='g_input_txt' id='0_".$x."' name='0_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='n_".$x."' name='n_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='1_".$x."' name='1_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='2_".$x."' name='2_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='3_".$x."' name='3_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='4_".$x."' name='4_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='5_".$x."' name='5_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='6_".$x."' name='6_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='7_".$x."' name='7_".$x."' style='border:dotted 1px #ccc;background:transparent;' readonly='readonly'/></td>";
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
         <input type="hidden" name='by' value='f_find_customer' title="f_find_customer" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='L' title="L" >
         <input type="hidden" name='type' value='f_find_customer' title="f_find_customer" >
         <tr>
            <td align="right"><input type="button" id="btnExit" title="Exit" />
               <?php if($this->user_permissions->is_re_print('f_find_customer')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
               <input type="hidden" name="code_" id="code_"/>   
               <input name="button" type="button" id="btnReset" title='Reset' />
            </td>
         </tr>
      </table>
   </form>
</div>
<?php } ?>