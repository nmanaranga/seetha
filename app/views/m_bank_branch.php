<?php if($this->user_permissions->is_view('m_bank_branch')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/m_bank_branch.js'></script>
<h2>Bank Branch</h2>
<div>
<table style="width:100%;" id="tbl1">
    <tr>
        <td valign="top" class="content" width="650" >
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_bank_branch" >
					<table style="width:100%;" id="tbl2">
						<tr>
							<td width="100">Bank</td>
							<!--<td colspan="2"><?php echo $bank; ?>
								<input type="text" id="bank_id" class="input_txt" style="width:300px;" readonly="readonly" />
							-->
				            <td colspan="2"><input type="text" class="input_txt" id="sbank" name="sbank" title="" maxlength="50" style="width: 150px;" />
				                <input type="hidden" name="bank" id="bank" title="0" />
				                <input type="text" class="hid_value" title='' readonly="readonly" id="bank_des"  style="width: 300px;"/>
				            </td>

							</td>
						</tr>

						<tr>
							<td>Branch Code</td>
							<td colspan="2">
								<input type="text" title="" id="code" name="code" class="input_txt" maxlength="4" style="width:150px; text-transform: uppercase;" />
								<input type="text" class="hid_value" title='' readonly="readonly" id="branch_code"  name="branch_code" style="width: 147px; text-transform: uppercase;"/>
								<input type="hidden" name="b_branch_code" id="b_branch_code" title="" />
							</td>
						</tr>
						
						<tr>
							<td>Description</td>
							<td colspan="2">
								<input type="text" title="" id="description" class="input_txt" name="description" maxlength="100" style="width:300px;" />

							</td>
						</tr>
					
						<tr>
							<td style="text-align:right" colspan="2">
								<input type="hidden" id="code_" name="code_" title="0" />
								
								<input type="button" id="btnExit" title='Exit' />
								<?php if($this->user_permissions->is_add('m_bank_branch')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
								<input type="button" id="btnReset" title='Reset'>                        
							</td>
						</tr>
					</table><!--tbl2-->
                </form><!--form_-->
            </div><!--form-->
      </td>

      <td valign="top" class="content">
            <div class="form" >
            <table>
            <tr>
            <td style="padding-right:64px;"><label>Search</label></td>
            <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
            </tr>
            </table>
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table><!--tbl1-->
</div>
<?php } ?>
