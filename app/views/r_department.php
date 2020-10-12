<?php if($this->user_permissions->is_view('r_department')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_department.js'></script>
<h2>Department</h2>
<table width="100%" >
    <tr>
        <td valign="top" class="content" style="width: 450px;">
            <div class="form" id="form" style="width: 450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_department" >
                <table >
				
                    <tr>
                        <td>Code</td>
                        <td>
                            <input type="text" class="input_txt" title='' id="code" name="code" maxlength="4" style="width:150px; text-transform: uppercase;">
                            <input type="text" class="input_txt" title='' id="code_gen" name="code_gen" maxlength="2" style="width:50px; text-transform: uppercase;">
                        </td>
                    </tr>
					
					<tr>
                        <td>Description</td>
                        <td><input type="text" class="input_txt" title='' id="description" name="description"  style="width:350px;" maxlength="50"/></td>
                    </tr>
					
					<tr>
                        <td><span style="width:150px;">Privilege Card Rate</span></td>
                        <td><input type="text" class="input_txt" title='' id="privilege_card_rate" name="pv_card_rate"  /></td>
                    </tr>
					
					<tr>
                        <td style="text-align:right" colspan="2">
                            <input type="hidden" id="code_" name="code_" title="0" />
							<input type="button" id="btnExit" title="Exit" />
                            <?php if($this->user_permissions->is_add('r_department')){ ?><input type="button" id="btnSave" title='Save <F8>' /> <?php } ?>
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
		<td class="content" valign="top" style="width: 600px;"> 
            <div class="form" id="form" style="width: 600px;">
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