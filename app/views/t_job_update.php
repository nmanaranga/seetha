<?php if($this->user_permissions->is_view('t_job_update')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_job_update.js"></script>

<h2 style="text-align: center;">Send to Supplier</h2>
<div class="dframe" id="mframe" style="width: 1200px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_job_update" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Supplier</td>
                <td>
                	<input type="text" class="input_text input_active" title='' name="sup_id" readonly="readonly" id="sup_id"  style="width: 120px;">
                    <input type="text" class="hid_value" title='' name="supplier" readonly="readonly" id="supplier"  style="width: 330px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" style="width:100%" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr><tr>
                <td>Comment</td>
                <td>
                    <input type="text" class="input_txt" name="comment" id="comment" title="" style="width: 455px;"/></td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_job_update')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } ?>    
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%;"/></td>
           
            </tr>

          <tr>
                <td colspan="4" style="text-align: center;">
                	<div style="height:360;overflow:scroll">
                    <table style="width: 100%;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 3%"></th>
                                <th class="tb_head_th" style="width: 4%">Job No</th>
                                <th class="tb_head_th" style="width: 8%;">Date</th>
                                <th class="tb_head_th" style="width: 20%;">Customer</th>
                                <th class="tb_head_th" style="width: 22%;">Item</th>
                                <th class="tb_head_th" style="width: 25%;">Fault</th>
                                <th class="tb_head_th" style="width: 8%;">Serial No</th>
                                <th class="tb_head_th" style="width: 3%;">Guarantee</th>
                                <th class="tb_head_th" style="width: 8%;">Card No</th>
                               
                            </tr>
                        </thead><tbody>
                           <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td style='text-align:center;'><input type='checkbox' id='sel_".$x."' name='sel_".$x."' value='1'></td>";
                                        echo "<td><input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150'readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_txt ' id='1_".$x."' name='1_".$x."' style='width:100%'readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_txt' id='2_".$x."' name='2_".$x."' style='width:100%' readonly='readonly'/></td>";
                                  		echo "<td><input type='text' class='g_input_txt' id='3_".$x."' name='3_".$x."' style='width:100%' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_txt' id='4_".$x."' name='4_".$x."' style='width:100%' readonly='readonly'/></td>";
                                        echo "<td style='text-align:center;'><input type='checkbox' id='gur_".$x."' name='gur_".$x."' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_txt' id='5_".$x."' name='5_".$x."' style='width:100%' readonly='readonly'/></td>";
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
		                        <?php if($this->user_permissions->is_delete('t_job_update')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
		                        <?php if($this->user_permissions->is_re_print('t_job_update')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
		                        <?php if($this->user_permissions->is_add('t_job_update')){ ?><input type="button"  id="btnSave" title='Save <F8>' /><?php } ?>            
                    		</div>
                </td>
            </tr>
        </table>
    </form>
     <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

        <input type="hidden" name='by' value='t_job_update' title='t_job_update' class="report">
        <input type="hidden" name='orientation' value="L" title="L">
        <input type="hidden" name='header' value='false' title='false'>
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" >
        <input type="hidden" name='type' value='service' title="service" >
        <input type="hidden" name='page' value='A5' title="A5" >
        <input type="hidden" name='org_print' value='' title="" id="org_print">
        <input type="hidden" name='qno' value='' title="" id="qno" >

    </form>
</div>
<?php } ?>
