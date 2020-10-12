<?php if($this->user_permissions->is_view('s_role')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />

<style type="text/css">
    .posting_div{
        background-color: #FFF;
        border: 1px dotted #CCC;
        padding: 7px;
        padding-buttom: 0px;
    }

    .heading {
        background-color: #aee8c8;
        margin: 5px;
        border: 2px solid #FFF;
        box-shadow: 0px 0px 5px #AAA;
        text-align: left;
        padding: 7px;
    }

    #tgrid tr:hover{
        background-color: #EEE;
    }

</style>

<script type="text/javascript" src="<?=base_url()?>js/s_role.js"></script>

<h2 style="text-align: center;">User Role</h2>
    <form  method="post" id="_form" action="<?=base_url()?>index.php/main/save/s_role" >
        <div class="dframe" id="mfram" style="text-align: center;">
                <div class="form" id="form">    
                <table width="100%" border='0'>
                 
                <tr>
                <td width="107">Category</td>
                <td width="1687">
                          <?=$def_mod ?>
                    <!-- <select name="package" id="package">
                                <option value="0">---</option>
                                <option value="1">Master Form</option>
                                <option value="2">Transactions</option>
                                <option value="3">HP</option>
                                <option value="4">Reports</option>
                                <option value="5">Settings</option>
                                <option value="6">Find</option>
                                <option value="7">Settu</option>
                                <option value="8">Cheques</option>
                                <option value="9">User Permition</option>
                                <option value="10">Service</option>
                                <option value="11">Gift Voucher</option>
                                <option value="12">HP Reports</option>
                                <option value="13">Seettu Reports</option>
                                <option value="14">Service Reports</option>
                                <option value="15">Cheque Reports</option>
                                <option value="16">Gift Voucher Reports</option>
                            </select> -->
                    </td>

            </tr>

                <tr>
                <td width="107">Code</td>
                <td width="1687"><input name="code" type="text" class="input_txt" id="code" style="width: 150px;" title="" maxlength="4" />
                <input type="hidden" name="code_" id="code_" title="0" /> Is Active
                <input type="checkbox" name="is_active" id="is_active" title="1" />
                </td>

            </tr>
            <tr>
                <td>Description </td>
                <td><input name="des" id="des" type="text" class="input_txt" id="des" style="width: 350px;" title="" />
            </tr>

            <tr>
                <td colspan="2" valign="top" class="content" style="width: 1800px;">
                <?php if(isset($_GET['key'])){ echo "<span style='color: blue;' >".base64_decode($_GET['key'])."</span>"; } ?>
            <table style="width: 100%" id="tgrid">
                <thead>
                <tr>
                    <th class="tb_head_th" style='width : 70px' >Module Id</th>
                    <th class="tb_head_th" >Name</th> 
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_view' ".$t1." /><br/>View</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_add' ".$t1." /><br/>Add</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_edit' ".$t1." /><br/>Edit</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_delete' ".$t1." /><br/>Delete</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_approve' ".$t1." /><br/>Approve</th>
                    <th class="tb_head_th"  style='width : 60px'><input type='checkbox' id='all_print' ".$t1." /><br/>Print</th>
                    <th class="tb_head_th"  style='width : 70px'><input type='checkbox' id='all_r_print' ".$t1." /><br/>Re-Print</th>
                    <th class="tb_head_th"  style='width : 80px'><input type='checkbox' id='all_back_date' ".$t1." /><br/>Back Date</th>
                </tr>

                </thead>
                <tbody id="grid_det">
                </tbody>
            </table>

            <div style="text-align: right; padding: 7px;">
                <input type="button" id="btnExit" title='Exit' />
                <input type="button" id="btnReset" title="Reset" />
                <?php if($this->user_permissions->is_add('s_role')){ ?><input type="button" id="btnSave" title="Save" /><?php } ?>
            </div>
                </td>
                </tr>
        </table>

    </div>
    </form>

</div>
<?php } ?>
