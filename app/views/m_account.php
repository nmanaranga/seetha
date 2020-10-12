<?php if($this->user_permissions->is_view('m_account')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/m_account.js'></script>
<h2>Account Setup</h2>

<table style="width:100%;" id="tbl1">
    <tr>
        <td valign="top" class="content" style="width:650px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_account" >
					<table style="width:100%;" id="tbl2">
						<tr>
							<td width="120" >Account Type</td>
							<td colspan="2"><?php echo $account_type; ?>
							<input type="text" id="account_type" style="width:280px;"  readonly="readonly" class="hid_value"/></td>

						</tr>

						<tr>
							<td>Code</td>
							<td colspan="2"><input type="text" id="code" title="" name="code" style="width:120px;text-transform: uppercase;" class="input_txt upper" />
								<input type="checkbox" name="is_control_acc" id="is_control_acc" title="1"/><span style="margin-right:5px;">Is Control Account</span>
								 <input type="checkbox" name="is_bank_acc" id="is_bank_acc" title="1"/>Is Bank Account

							</td>
						</tr>
						
						<tr>
							<td>Description</td>
							<td colspan="2"><input type="text" id="description" title="" name="description" style="width:403px;" class="input_txt" maxlength="50" /></td>
						</tr>

						<tr>
							<td>Con. acc of this acc</td>
							<td colspan="2"><input type="text" id="control_acc" title="" name="control_acc" style="width:150px;" class="input_txt_f upper"/>
							<input type="text" id="control"  style="width:280px;"  title="" readonly="readonly" class="hid_value"/></td>

						
						</tr>

						<tr>
							<td>Account Category</td>
							<td colspan="2"><?php echo $account_category; ?>
							<input type="text" id="account_category" style="width:280px;"  title=" " readonly="readonly" class="hid_value"/></td>

						
						</tr>

						<tr>
							<td>Order No</td>
							<td colspan="2"><input type="text" id="order_no" name="order_no" style="width:50px;" title="" class="input_txt"/>
							<=Order for reports.</td>
						</tr>

						<tr>
							<td>Display Text</td>
							<td colspan="2"><input type="text" id="display_text" title="" name="display_text" style="width:120px;" class="input_txt"/>
							</td>
						</tr>




					
						<tr>
							<td style="text-align:right" colspan="3">
								<input type="hidden" id="code_" name="code_" title="0" />
								<input type="button" id="btnExit" title='Exit' />
								<input type="button" id="btnReset" title='Reset' />
								<?php if($this->user_permissions->is_add('m_account')){ ?>
								<input type="button" id="btnSave1" title='Save <F8>' />
                                <?php } ?>
								<input type="button" id="btnAcc" title='Find Account Details' style="width:140px;" />	
								                       
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
<?php } ?>


