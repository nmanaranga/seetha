<?php if($this->user_permissions->is_view('r_transaction_list_voucher')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_r.js'></script>

<h2>R</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 480px;">
            <div class="form" id="form">
                <form action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
		    <table>
		    <tr>
		    	<td>Date</td>
		    	<td>
		    		<input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" />
        			To <input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  />
        		</td>
        	</tr>
            <tr>			

			<tr>
			    <td colspan="2" style="text-align: right;">
                <input type="hidden" name="type" id="type"  title=""/>
				<input type="button" title="Exit" id="btnExit" value="Exit">
				
				 <button id="btnPrint">Print PDF</button>
			    </td>
			</tr>

		    </table>

         	<input type="hidden" name='page' value='A4' title="A4" >
         	<input type="hidden" name='orientation' value='L' title="L" >
         	<input type="hidden" name='type' value='19' title="19" >
         	<input type="hidden" name='header' value='false' title="false" >
         	<input type="hidden" name='qno' value='' title="" id="qno" >
         	<input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
         	<input type="hidden" name='dt' value='' title="" id="dt" >
         	<input type="hidden" value='r_r' title="r_r" id="by" name="by" >
        </form>
    </div>   
</table>
<?php } ?>