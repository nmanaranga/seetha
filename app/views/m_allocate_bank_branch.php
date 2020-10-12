<?php if($this->user_permissions->is_view('m_allocate_bank_branch')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/m_allocate_bank_branch.js"></script>


<h2 style="text-align: center;">Bank Allocate To Branch</h2>
<div class="dframe" id="mframe">
  <form method="post" action="<?=base_url()?>index.php/main/save/m_allocate_bank_branch" id="form_">
    <table style="width: 100%" border="0">
      <tr>
        <td style="width: 100px;">Bank Account</td>
        <td>
         <input type="text" class="input_active_num"  name="bank_acc" style="width:150px" id="bank_acc" />
         <input type="text" class="hid_value" name="bank_des" id="bank_des" style="width:300px"/>
       </td>
       <td style="width: 100px;">Date</td>
       <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
     </tr>
     <input type="hidden" class="input_active_num" name="id" id="id" title="<?=$max_no?>"/>
     <input type="hidden" id="hid" name="hid" title="0" />                    
     
     <tr>
      <td style="width: 100px;">Cluster</td>
      <td>

        <input type="text" class="input_active_num"  style="width:150px" name="u_cluster" id="u_cluster" />
        <input type="text" class="hid_value" name="cluster_des" id="cluster_des" style="width:300px"/>
        <input type="button" title="Load branches" id="load_bc" />
        <input type="hidden" id="hid_tot" name="hid_tot"/>
        <input type="hidden" id="hid_user" name="hid_user" title="0"/>
      </td>
      <td style="width: 100px;"></td>
      <td style="width: 100px;"></td>

    </tr>
    <tr>
      <td colspan="4" style="text-align: center;">


       <table style="width: 875px;" id="tgrid">
        <thead>
          <tr>
            <th class="tb_head_th" style="width: 20px;">Cluster</th>
            <th class="tb_head_th" style="width: 140px;" >Cluster Name</th>
            <th class="tb_head_th" style="width: 20px;">Branch</th>
            <th class="tb_head_th" style="width: 120px;" >Branch Name</th>
            <th class="tb_head_th"  style="width: 60px;">Active</th>
          </tr>
        </thead>

        <tbody id='t_branch'>

        </tbody>


      </table> 
      <div style="text-align: left; padding-top: 7px;">

      </div>
      <div style="text-align: left; padding-top: 7px;">
        <input type="button" id="btnExit" title="Exit" />
        <input type="button" id="btnReset" title="Reset" />
        <?php if($this->user_permissions->is_add('m_allocate_bank_branch')){ ?><input type="button"  id="btnSave" title="Save" /><?php } ?>

      </div>
    </td>
  </tr>
</table>
</form>
</div>
<?php } ?>