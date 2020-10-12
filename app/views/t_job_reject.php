<?php if($this->user_permissions->is_view('t_job_reject')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_job_reject.js"></script>

<h2 style="text-align: center;">Job Reject</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_job_reject" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Supplier</td>
                <td>
                	<input type="text" class="input_txt" id="supplier" name="supplier"/>
                    <input type="text" class="hid_value" title='' name="sup_name" readonly="readonly" id="sup_name"  style="width: 300px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" style="width:100%" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr><tr>
                <td>Comment</td>
                <td>
                    <input type="text" class="input_txt" name="comment" id="comment" title="" style="width: 455px;"/></td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_job_reject')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } ?>    
                </td>

            </tr>
           
            <tr>
                <td> Search </td>
                <td> <input type="text" class="input_txt" name="search" id="search" style="width:455px;"> </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%;"/></td>
           
            </tr>
            
          <tr>
                <td colspan="4" style="text-align: center;">
                	<div style="height:300;overflow:scroll">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width:20px"> </th>
                                <th class="tb_head_th" style="width: 80px;">Job No</th>
                                <th class="tb_head_th" style="width: 80px;">Date</th>
                                <th class="tb_head_th" style="width: 80px;">Cus Code</th>
                                <th class="tb_head_th" style="width: 80px;">Cus Name</th>
                                <th class="tb_head_th" style="width: 80px;">Item</th>
                                <th class="tb_head_th">Reason</th>
                               
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='checkbox' class='chk' id = 'sel_".$x."' name='sel_".$x."'/>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                            <input type='text' class='g_input_txt ' id='0_".$x."' name='0_".$x."' readonly='readonly' style='background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150'  readonly='readonly' style='background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text' class='g_input_txt' id='1_".$x."' name='1_".$x."'  readonly='readonly' style='background-color: #f9f9ec;'></td>";
                                        echo "<td><input type='text' class='g_input_txt' id='2_".$x."' name='2_".$x."'  readonly='readonly'style='background-color: #f9f9ec; '/></td>";
                                  		echo "<td><input type='text' class='g_input_txt' id='3_".$x."' name='3_".$x."'  readonly='readonly' style='background-color: #f9f9ec;'></td>";
                                        echo "<td><input type='text' class='input_txt' id='4_".$x."' name='4_".$x."' style='width:100%' readonly='readonly'></td>";
                                  
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                                               
                    </table>
                </div>
			</td>
            </tr>
            <tr>
            	<td colspan="4">
					 		<div style="text-align: left; padding-top: 7px;">
		                        <input type="button" id="btnExit" title="Exit" />
		                        <input type="button" id="btnReset" title="Reset" />
		                        <?php if($this->user_permissions->is_delete('t_job_reject')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
		                        <?php if($this->user_permissions->is_re_print('t_job_reject')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
		                        <?php if($this->user_permissions->is_add('t_job_reject')){ ?>
		                        <input type="button"   title="Save" id="btnSave" name="btnSave"/>
		                        	
		                        <?php } ?>
					                        
                    		</div>
            	</td>
            </tr>
        </table>
    </form>
     <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
        <input type="hidden" name='by' value='t_job_reject' title='t_job_reject' class="report">
        <input type="hidden" name='orientation' value="L" title="L">
        <input type="hidden" name='header' value='false' title='false'>
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" >
        <input type="hidden" name='type' value='service_reject' title="service_reject" >
        <input type="hidden" name='page' value='A5' title="A5" >
        <input type="hidden" name='org_print' value='' title="" id="org_print">
        <input type="hidden" name='qno' value='' title="" id="qno" >
    </form>
</div>
<?php } ?>
