<?php if($this->user_permissions->is_view('m_employee_category')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_employee_category.js'></script>
<h2>Employee Category</h2>
 
<table width="100%"> 

    <tr>
        <td valign="top" class="content" style="width:450px;">
            <div class="form" id="form" style="width:450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_employee_category" >
                
                <table>
						<tr>
							<td>Code</td>
							<td>
                               <!-- <input type="text" readonly class="input_hid" title='<?php echo $store_code ?>' id="pre_code" name="pre_code" style="width:50px; text-transform: uppercase; border: 1px solid #003399;padding: 3px;font-weight: bold;font-size: 12px;font-family: Arial;" maxlength="4">-->
                                <input type="text" class="input_hid"  id="code" name="code" style="width:130px; text-transform: uppercase;border: 1px solid #003399;padding: 3px;font-weight: bold;font-size: 12px;font-family: Arial;" maxlength="10">
                            </td>
						</tr>
						
						<tr>
							<td>Description</td>
							<td><input type="text" class="input_txt" title='' id="description" name="description" style="width:350px;" maxlength="100"/></td>
						</tr>							
						<tr>
							
							<td style="text-align:right" colspan="2">
                                <input type="hidden" id="code_" name="code_" title="0" />
    							<input type="button" id="btnExit" title="Exit" />
    							<?php if($this->user_permissions->is_add('m_employee_category')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                                <input type="button" id="btnReset" title='Reset'>
							</td>
                    </tr>
                </table>
                </form>
            </div>
        </td><td class="content" valign="top" style="width:600px;">
            <div class="form" id="form" style="width:600px;" >
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
</table>
<?php } ?>
