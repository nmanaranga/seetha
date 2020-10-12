
<script type='text/javascript' src='<?=base_url()?>js/m_department.js'></script>
<h2>Department</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 480px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_department" >
                <table>
				
                    <tr>
                        <td>Code</td>
                        <td><input type="text" class="input_txt" title='Code' id="code" name="code" maxlength="10"></td>
                    </tr>
					
					<tr>
                        <td>Description</td>
                        <td><input type="text" class="input_txt" title='Description' id="description" name="description"  maxlength="255"/></td>
                    </tr>
					
					<tr>
                        <td>Privilege Card Rate</td>
                        <td><input type="text" class="input_txt" title='privilege_card_rate' id="privilege_card_rate" name="privilege_card_rate"  maxlength="10"/></td>
                    </tr>
					
					<tr>
                        <td style="text-align:right" colspan="2">
                            <input type="hidden" id="code_" name="code_" title="0" />
							<input type="button" id="btnExit" title="Exit" />
                            <input type="button" id="btnSave1" title='Save <F8>' />
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
		<td class="content" valign="top">
            <div class="form">
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>
