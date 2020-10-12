<?php if($this->user_permissions->is_view('f_find_item_img')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/f_find_item_img.js'></script>
<h2>Find Item Images</h2>
<div class="dframe" id="mframe">
<form id="form_" method="post">
	<table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
		<tr>
			<?php foreach($branch as $row){ 
				$name= $row['name'];
				$bc=$row['bc'];
			} 
			?>
			<td style="width:100px;">Item</td>
			<td>
				<input type="text" id="item" name="item" class="input_txt" style="width:150px;" title=""/>
				<input type="text" id="item_des" name="item_des" class="hid_value" title="" style="width:350px;" title=""/>
			</td>			
		</tr>
		<tr>
			<td colspan="3" height="20"><hr class="hline"/></td>
		</tr>
	</table>
			
			
	<table style="width:100%;" cellpadding="0" id="grid">
		<thead>
            <tr>
               
                <th class="tb_head_th" style="text-align:center;">Images</th>
				
            </tr>
        </thead>
		
		<tbody id="item_ld">	
			<tr>
				<td width="300px;"style="text-align:center;"><img width='150px' border='0' hspace='9' vspace='9' height='150px' src='<?=base_url()?>images/non_img.jpg'/></td>
			</tr>						
		</tbody>
		<tfoot>
            <tr style="background-color: transparent;">
                <td style="text-align: right; font-weight: bold; font-size: 12px;"></td>
                <td>&nbsp;</td>
            </tr>
         </tfoot>
    </table>

	<table style="width:100%;">
		<tr><td height="20"><hr class="hline"/></td></tr>
	</table>

	<table style="width:100%;">
		<tr>
			<td align="right">
				<input type="button" id="btnExit" title="Exit" />
              	<input type="hidden" name="code_" id="code_"/>   
             	<input name="button" type="button" id="btnReset" title='Reset' />
             </td>
		</tr>
	</table>
</form>		
</div>			
<?php } ?>