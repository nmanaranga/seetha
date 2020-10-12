<?php if($this->user_permissions->is_view('r_branch_c_acc')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_branch_c_acc.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<h2>Branch Current Account Setup</h2>
<div class="dframe" id="mframe" style="margin-top:10px; width:984px; padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/r_branch_c_acc" id="form_">
        <table style="width: 100%" border="0">
           <input type='hidden' name='hid_count' and id='hid_count'/>
            <tr>
            	<td colspan="4">
            			<table style="width:100%" id="tgrid" border="0">
							<thead>
								<tr>
									<th class="tb_head_th" style="width:50px">No</th>
                                    <th class="tb_head_th" style="width:100px">Cluster</th>
									<th class="tb_head_th" style="width:200px">Cluster Name</th>
									<th class="tb_head_th" style="width:60px">Branch</th>
                                    <th class="tb_head_th" style="width:200px">Branch Name</th>
									<th class="tb_head_th" style="width:100px">Account Code</th>
									<th class="tb_head_th" style="width:200px">Acount Name</th>
									
								</tr>
							</thead>
							<tbody id="load_g">
																	
							</tbody>
						</table>
            	</td>
            </tr>
            <tr>
            <td colspan="2" style="text-align: center;">
                <div style="text-align:left; padding-top: 7px;">
                	<input type="button" id="btnExit" title="Exit" />
                	<input type="button" id="btnReset" title="Reset" />
                     <?php //if($this->user_permissions->is_delete('r_branch_c_acc')){ ?> <!-- <input type="button" id="btnDelete" title="Delete" /> --> <?php } ?>
                    <?php if($this->user_permissions->is_re_print('r_branch_c_acc')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?> 
                    <?php if($this->user_permissions->is_add('r_branch_c_acc')){ ?><input type="button" id="btnSave" title="Save" /><?php } ?>
                </div>
            </td>
            
        </tr>
        </table>
         <?php 
    if($this->user_permissions->is_print('r_branch_c_acc')){ ?>
        <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
    <?php } ?> 
    </form>

      <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">      
        <input type="hidden" name='by' value='r_branch_c_acc' title="r_branch_c_acc" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >                 
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" > 
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
    </form>
</div>
<?php //} ?>