<?php if($this->user_permissions->is_view('f_post_dated_chq_reg')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/f_post_dated_chq_reg.js'></script>
<h2>Post Dated Cheques Registry</h2>
<div class="dframe" id="mframe2">
   <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
      <table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
        <tr>
            <td style="width:100px;">Cluster</td>
            <td style="width:605px;">
              <input type="text" id="cluster" name="cluster" class="input_txt" style="width:150px;"/>
              <input type="text" id="cluster_des" name="cluster_des" class="hid_value" title="" style="width:250px;" >
            </td>
            <td style="width:65px;">Date From</td>
            <td style="width:300px;">
              <input type="text" class="input_date_down_future" readonly="readonly" name="f_date" id="f_date" title="<?=date('Y-m-d')?>"  style="text-align:right;"/>
              To
              <input type="text" class="input_date_down_future" readonly="readonly" name="t_date" id="t_date" title="<?=date('Y-m-d')?>"  style="text-align:right;"/>
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
            <td style="width:65px;">Status</td>
            <td>
              <select style="width:100px;" name="status" id="status">
                <option value="A">All</option>
                <option value="P">Pending</option>
                <option value="R">Recepit</option>
                <option value="D">Deposit</option>
              </select>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type="button" id="find" style="width:103px;" value="FIND" title="FIND">
            </td>
        </tr>
        <tr><td>Customer</td>
            <td>   <input type="text" id="t_search" name="t_search" class="input_txt" title="" style="width:150px;">
                   <input type="text" id="cus_name" name="cus_name" class="hid_value" title="" style="width:250px;"></td>
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
               <th  class="tb_head_th" style="width:80px;">Date</th>
               <th  class="tb_head_th" style="width:80px;">Realize Date</th>
               <th  class="tb_head_th" style="width:150px;">Customer</th>
               <th  class="tb_head_th" style="width:100px;">CHQ No</th>
               <th  class="tb_head_th" style="width:100px;">Amount</th>
               <th  class="tb_head_th" style="width:100px;">Account</th>
               <th  class="tb_head_th" style="width:150px;">Bank</th>
               <th  class="tb_head_th" style="width:150px;">Branch</th>
               <th  class="tb_head_th" style="width:40px;">A.No</th>
               <th  class="tb_head_th" style="width:80px;">Receipt Date</th>
               <th  class="tb_head_th" style="width:80px;">Receipt No</th>
               <th  class="tb_head_th" style="width:80px;">Bank Date</th>
               <th  class="tb_head_th" style="width:50px;">Remarks</th>
            </tr>
         </thead>
         <tbody id="item_ld">
            <?php
               for($x=0; $x<50; $x++){
                    echo "<tr>";
                       echo "<td><input type='text' class='g_input_txt' id='dt_".$x."' name='dt_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='rldt_".$x."' name='rldt_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
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
                              <input type='text' class='g_input_txt' id='bb_".$x."' name='bb_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/>
                              <input type='hidden' id='bbcode_".$x."' name='bbcode_".$x."'/>
                            </td>";
                       echo "<td><input type='text' class='g_input_num' id='ack_".$x."' name='ack_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='rd_".$x."' name='rd_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_num' id='rn_".$x."' name='rn_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td><input type='text' class='g_input_txt' id='bd_".$x."' name='bd_".$x."' style='border:dotted 1px #ccc;background-color:#f9f9ec;' readonly='readonly'/></td>";
                       echo "<td>
                                <input type='button' class='rmks' title='...' id='r_".$x."' disabled style='width:50px;' />
                                <input type='hidden' id='rtnno_".$x."' name='rtnno_".$x."'/>
                                <input type='hidden' id='rtndate_".$x."' name='rtndate_".$x."'/>
                                <input type='hidden' id='rtnreason_".$x."' name='rtnreason_".$x."'/>
                                <input type='hidden' id='status_".$x."' name='status_".$x."'/>
                             </td>";
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
         <input type="hidden" name='by' value='f_post_dated_chq_reg' title="f_post_dated_chq_reg" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='L' title="L" >
         <input type="hidden" name='type' value='f_post_dated_chq_reg' title="f_post_dated_chq_reg" >
         <tr>
            <td align="right"><input type="button" id="btnExit" title="Exit" />
               <input type="hidden" name="code_" id="code_"/>   
               <input name="button" type="button" id="btnReset" title='Reset' />
               <?php if($this->user_permissions->is_re_print('f_post_dated_chq_reg')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
            </td>
         </tr>
      </table>
   </form>
</div>
<?php } ?>