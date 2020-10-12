<?php if($this->user_permissions->is_view('r_payment_option')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/r_payment_option.js'></script>
<h2>Payment Option Setup</h2>
<div  style="width:525px;margin:0 auto;">
<table style="width:450px;" id="tbl1">
    <tr>
        <td valign="top" class="content" style="width:35%">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_payment_option" >
					<table style="width:450px;" id="tbl2">
						<tr>
							<td>Type</td>
							<td>
							<?php echo $select; ?>
						</td>
						</tr>
						
						<tr>
							<td colspan="2">
								<fieldset>
									<legend>Payment Options</legend>
									<table>
										<tr>
											<td><input type="checkbox" name="cash" id="cash" title="1"/></td>
											<td>Cash</td>
										</tr>

										<tr>
											<td><input type="checkbox" name="chq_receive" id="chq_receive" title="1"/></td>
											<td>Recieve Cheque</td>
										</tr>

										<tr>
											<td><input type="checkbox" name="credit_card" id="credit_card" title="1"/></td>
											<td>Credit Card</td>
										</tr>

										<tr>
											<td><input type="checkbox" name="crn" id="crn" title="1"/></td>
											<td>Credit Note</td>
										</tr>

										<!-- <tr>
											<td><input type="checkbox" name="bank_deposit" id="bank_deposit" title="1"/></td>
											<td>Bank Deposit</td>
										</tr> -->

										<tr>
											<td><input type="checkbox" name="discount" id="discount" title="1"/></td>
											<td>Discount</td>
										</tr>

										<!-- <tr>
											<td><input type="checkbox" name="advance" id="advance" title="1"/></td>
											<td>Advance</td>
										</tr> -->

										<tr>
											<td><input type="checkbox" name="gift_voucher" id="gift_voucher" title="1"/></td>
											<td>Gift Voucher</td>
										</tr>

										<tr>
											<td><input type="checkbox" name="credit" id="credit" title="1"/></td>
											<td>Credit</td>
										</tr>

											<tr>
											<td><input type="checkbox" name="privilege_card" id="privilege_card" title="1"/></td>
											<td>Privilege Card</td>
										</tr>
										<tr>
											<td><input type="checkbox" name="drn" id="drn" title="1"/></td>
											<td>Debit Note</td>
										</tr>
										<tr>
											<td><input type="checkbox" name="chq_issue" id="chq_issue" title="1"/></td>
											<td>Issued Cheque</td>
										</tr>

										<tr>
											<td><input type="checkbox" name="installment" id="installment" title="1"/></td>
											<td>Installment</td>
										</tr>

										<tr>
											<td><input type="checkbox" name="post_dated_cheques" id="post_dated_cheques" title="1"/></td>
											<td>Post Dated Cheques</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
					
						<tr>
							<td style="text-align:center" colspan="2">
								<input type="hidden" id="code_" name="code_" title="0" />
								<input type="button" id="btnExit" title='Exit' />
								<?php if($this->user_permissions->is_add('r_payment_option')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
								<input type="button" id="btnReset" title='Reset'>                        
							</td>
						</tr>
					</table><!--tbl2-->
                </form><!--form_-->
            </div><!--form-->
      </td>	
    </tr>
</table><!--tbl1-->
</div>
<?php } ?>