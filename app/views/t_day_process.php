<?php if($this->user_permissions->is_view('t_day_process')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/t_day_process.js'></script>
<h2>Day Process</h2>
<div class="dframe" id="mframe">
   <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
      <table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
         <tr>
            <td style="width:100px;">No</td>
            <td><input type="text" class="input_active_num" readonly="readonly" style="width:150px;" name="id" id="id" title="<?=$max?>" />
               <!-- <input type="button" name="load_po" id="load_po" title="Load"/> -->
            </td>
         </tr>
         <tr>
            <td style="width:100px;">Date</td>
            <td><input type="text" class="input_active_num" readonly="readonly" style="width:150px;" name="date" id="date" title="<?=$max_date?>" />
               <!-- <input type="button" name="load_po" id="load_po" title="Load"/> -->
            </td>
         </tr>
         <tr>
            <td style="width:100px;"></td>
            <td></td>
         </tr>
         <tr>
            <td colspan="3" height="20">
               <hr class="hline"/>
            </td>
         </tr>
      </table>


       <div id="tabs" >
         <ul>
            <li><a href="#tabs-1" >Purchase Order</a></li>
            <li><a href="#tabs-2" >Intrest Amount Detail</a></li>
            <li><a href="#tabs-3" >Penalty Process</a></li>
            

         </ul>
         <div id="tabs-1">
            <table style="width:500px;" cellpadding="0" id="grid">
               <thead>
                  <tr>
                     <th  class="tb_head_th" style="width:120px;">Date</th>
                     <th  class="tb_head_th" style="width:120px;">PO No</th> 
                     <th  class="tb_head_th" style="width:120px;">Supplier</th>                 
                  </tr>
               </thead>
               <tbody id="searchType">
                  
               </tbody>
    
            </table>                               
         </div>
         <div id="tabs-2">
            <table style="width:300px;" cellpadding="0" id="grid">
               <thead>
                  <tr>
                     <th  class="tb_head_th" style="width:120px;">Date</th>
                     <th  class="tb_head_th" style="width:120px;">No</th> 
                     <th  class="tb_head_th" style="width:120px;">Amount</th>                 
                  </tr>
               </thead>
               <tbody id="searchType2">
                  
               </tbody>
    
            </table>                               
         </div>
         <div id="tabs-3">
            <table style="width:300px;" cellpadding="0" id="grid">
               <thead>
                  <tr>
                     <th  class="tb_head_th" style="width:120px;">Date</th>
                     <th  class="tb_head_th" style="width:120px;">No</th> 
                     <th  class="tb_head_th" style="width:120px;">Amount</th>                 
                  </tr>
               </thead>
               <tbody id="searchType3">
                  
               </tbody>
    
            </table>                               
         </div>
      </div>


      <table style="width:100%;">
         <input type="hidden" name='by' value='f_find_serial' title="f_find_serial" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='L' title="L" >
         <input type="hidden" name='type' value='f_find_serial' title="f_find_serial" >
         <tr>
               
            <td align="right">
               <input type="button" name="calculation" id="calculation" title="Calculate"/>
               <input type="button" id="btnExit" title="Exit" />
               <!-- <?php if($this->user_permissions->is_re_print('f_find_transaction_log_det')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?> -->
               <input type="hidden" name="code_" id="code_"/>   
               <input name="button" type="button" id="btnReset" title='Reset' />
            </td>
         </tr>
      </table>
   </form>
</div>
<?php } ?>