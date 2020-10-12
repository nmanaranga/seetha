<?php if($this->user_permissions->is_view('t_loyalty_card_request_approve')){ ?>
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
  <script type="text/javascript" src="<?=base_url()?>js/t_loyalty_card_request_approve.js"></script>

  <h2 style="text-align: center;">Loyalty Card Request Approve</h2>
  <div class="dframe" id="mframe" style="width: 1050px;">
    <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_loyalty_card_request_approve" >
      <table style="width: 100%" border="0">
        <tr>
          <td style="width:100px;">Cluster</td>
          <td style="width:120px;"><?=$cluster;?></td>                   
          <td><input type="hidden" class="input_date_down_future " readonly="readonly"  style="width:100%;text-align:right;" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" /></td>                   
          <td><input type="hidden" class="input_active_num" name="id" id="id" maxlength="10" title="<?=$max_no?>" style="width:150px;"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Branch</td>
          <td><select style="width:185px;" id="branch" name="to_bc"></select></td>                   
          <td>&nbsp;</td>               
          <td><input type="button"  id="load" title='Search' /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5"> <hr></td>
        </tr>
     
        <tr>
          <td colspan="5"><div id="tbl_tbdy"></div></td>
        </tr>
        <tr>
          <td colspan="5">
            <div style="text-align: right; padding-top: 7px;">
             <?php if($this->user_permissions->is_add('t_loyalty_card_request_approve')){ ?><input type="button"  id="btnSave" title='Save <F8>' />		<?php } ?>
             <input type="button" id="btnPrint" title="Print" />
             <input type="button" id="btnReset" title="Reset" />
             <input type="button" id="btnExit" title="Exit" />
           </div>
         </td>
       </tr>
     </table>
   </form>

   <div id="light33" class="white_content2" style='width:1020px; height: 575px; overflow: hidden;'>
    <div style='margin:-10px 10px 5px 5px;padding:5px 0px;'>
      <h3 id='pop_heading' style='width:100%;font-family:calibri;background:#283d66;color:#fff;text-transform:uppercase;'>Customer History Details</h3>
      <div id='item_det33'></div>
      <hr style="width:100%"/>
    </div>   
    <input type='button' value='close' title='Close' id='popclose33' style="position:relative; bottom:5px;right:5px; float: right;"/>
  </div>
  <div id="fade33" class="black_overlay"></div>

  <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
    <input type="hidden" name='by' value='t_loyalty_card_request_approve' title="t_loyalty_card_request_approve" class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" name='header' value='false' title="false" >
    <input type="hidden" name='qno' value='' title="" id="qno" >
    <input type="hidden" name='bal_date' value='' title="" id="bal_date" >
    <input type="hidden" name='bal_area' value='' title="" id="bal_area" >
  </form>


</div>
<?php } ?>