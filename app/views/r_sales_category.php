<?php if($this->user_permissions->is_view('r_sales_category')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_sales_category.js'></script>
<h2>Sales Category</h2>

<table style="width:100%;">
    <tr>
        <td valign="top" class="content" style="width:450px;">
            <div class="form" id="form" style="width:450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_sales_category" >
					<table>
						<tr>
							<td>Code</td>
							<td><input type="text" id="code" name="code" style="width:150px; text-transform:uppercase" title="" class="input_txt"/></td>
						</tr>
						
						<tr>
							<td>Description</td>
							<td><input type="text" id="description" name="description" title="" style="width:350px;" class="input_txt"/></td>
						</tr>
					
						<tr>
							<td style="text-align:right" colspan="2">
								<input type="hidden" id="code_" name="code_" />
								<input type="button" id="btnExit" title='Exit' />
								<?php if($this->user_permissions->is_add('r_sales_category')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
								<input type="button" id="btnReset" title='Reset'>                        
							</td>
						</tr>
					</table><!--tbl2-->
                </form><!--form_-->
            </div><!--form-->
      </td>
			<td style="width:600px;" valign="top" class="content">
            <div class="form" id="form" style="width:600px;">
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
