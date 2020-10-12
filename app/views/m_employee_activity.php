<?php if($this->user_permissions->is_view('m_employee_activity')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/m_employee_activity.js'></script>
<h2>Employee Activity</h2>
<div  style="width:525px;margin-left:50px;">
<table style="width:525px;" id="tbl1">
    <tr>
        <td valign="top" class="content" style="width:50%;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_employee_activity" >
                    <table style="width:520px;" id="tbl2">
                        <tr>   
                            <td colspan="2" style="text-align:right;">No <input type="text" style="margin-right:5px;margin-left:30px;" class="input_active_num" name="id" id="id" title="<?=$max_no?>" maxlength="10" style="width:150px;" readonly="readonly"/><input type="hidden" id="hid" name="hid" title="0" /></td>
                        </tr>
                        <tr>  
                            <td colspan="2" style="text-align:right;">Date <input type="text" style="margin-right:5px;margin-left:30px;text-align:right;"  class="input_date_down_future" readonly="readonly" style="width:150px;" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
                        </tr>
                        <tr>
                            <td>Employee</td>
                            <td>
                                <input type="text" class="input_active"  style="width:150px;" name="emp_id" id="emp_id"/>     
                                <input type="text" class="hid_value" id="emp_name" style="width:280px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>
                                <input type="text" class="input_active" style="width:150px;" name="category" id="category"/>     
                                <input type="text" class="hid_value" id="cat_name" style="width:280px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Designation</td>
                            <td>
                                <input type="text" class="input_active" name="designation" id="designation" style="width:150px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Note</td>
                            <td>
                                <input type="text" class="input_active" name="note" id="note" style="width:433px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Active</td>
                            <td>
                                <input type="checkbox" class="input_active" name="is_active" id="is_active" title="1" value="1" style="width:0px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right" colspan="2">
                                <input type="button" id="btnExit" title='Exit' />
                                <?php if($this->user_permissions->is_add('m_employee_activity')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?> 
                                <input type="button" id="btnReset" title='Reset'>                       
                            </td>
                        </tr>
                    </table><!--tbl2-->
                    <input type="hidden" id="code_" name="code_" title="0">
                </form><!--form_-->
            </div><!--form-->
      </td>
      <td valign="top" class="content" style="width:600px;">
            <div class="form" id="form" style="width:500px;" >
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
    <?php 
if($this->user_permissions->is_print('m_employee_activity')){ ?>
    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
<?php } ?> 
</table><!--tbl1-->
</div>

<?php } ?>

<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
    <input type="hidden" name='by' value='m_employee_activity' title="m_employee_activity" class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" name='header' value='false' title="false" >
    <input type="hidden" name='qno' value='' title="" id="qno" > 
    <input type="hidden" name='terminal_id_h' value='' title="" id="terminal_id_h" >          
</form>