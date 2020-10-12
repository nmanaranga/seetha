<h2 style="text-align: center;">Account Report </h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_sales_target_report.js"></script>

<div class="dframe" id="r_view2" style="width: 1000px;">
    <form id="print_pdf" class="printExcel" action="<?php echo site_url(); ?>" method="post" target="_blank">

        <fieldset>
            <legend>Date</legend>
            <table>
                <tr>
                    <td><font size="2">Month</font></td>
                    <td><input type="text" class="monthYearPicker" id="txtmMonth" name="txtmMonth" title="<?=date('Y-m')?>" style="width: 80px; text-align:right;" /></td>
                </tr>
            </table>
        </fieldset>    
        <fieldset >
            <legend >Category</legend>
            <div id="report_view" style="overflow: auto;">
            <table border="0" cellpadding="0" cellspacing="0" id="cl_bc" style="font-size: 12px;">        
                   <tr>
                    <td style="width:100px;"><lable id="lblCluster">Cluster<lable></td>
                    <td><?php echo $cluster; ?></td>
                </tr>

                <tr>
                    <td><lable id="lblBranch">Branch<lable></td>
                    <td>
                        <?php echo $branch; ?> 
                    </td>
                </tr> 
                 <tr>
                    <td><lable id="lblEmployee">Employee<lable></td>
                    <td>
                        <?php echo $employee; ?> 
                    </td>
                </tr>
            </table>


                <table border="0" cellpadding="0" cellspacing="0" id="acc_table" style="font-size: 12px;">          
                    <tr> <td colspan="3"><hr/><td>
                    </tr>

                </table>
                <?php if(false){ ?> 
                <table style="width:960px"> <tr><td colspan="5"><hr/></td></tr> </table>
                <table style="width:960px" id="tgrid" border="0" class="tbl">
                    <thead>
                        <tr>
                            <th class="tb_head_th" style="width: 50px;">&nbsp;</th>
                            <th class="tb_head_th" style="width: 100px;">No</th>
                            <th class="tb_head_th" style='width: 150px;'>Account Code</th>
                            <th class="tb_head_th" style="width: 200px;">Account Name</th>
                            <th class="tb_head_th" style="width: 100px;">Type</th>
                            <th class="tb_head_th" >Headings</th>
                        </tr>
                    </thead><tbody id="tbl_body">

                    <?php
                    for($x=0; $x<1; $x++){
                        echo "<tr>";
                        echo "<td class='check' style='background:#F9F9EC'><input type='checkbox' class='g_input_txt g_col_fixed Checkbox'  id='n_".$x."' name='n_".$x."' /></td>";
                        echo "<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='1_".$x."' name='1_".$x."' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        echo "<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='2_".$x."' name='2_".$x."' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        echo "<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='3_".$x."' name='3_".$x."' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        echo "<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='4_".$x."' name='4_".$x."' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        echo "<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='5_".$x."' name='5_".$x."' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php } ?>

        </div>
        <?php if(false){ ?> 
        <input type='button' id="btnCheckAll" title="Check All" value="Check All" />
        <input type='button' id="btnUncheckAll" title="Uncheck All" value="Check All" />      <?php } ?>

        <table border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">

            <tr>
                <td><input type="radio" id="budgetAchiForBranch" name="acc"/>Budget Achievement for Branch & Cluster</td>
            </tr>
            <tr>
                <td><input type="radio" id="budgetAchiForCluster" name="acc"/>Budget Achievement for Cluster</td>
            </tr>
            <tr>
            <td><input type="radio" id="budgetAchiForSupervisor" name="acc"/>Budget Achievement  for Supervisor</td>
            </tr>
            <tr>
                <td><input type="radio" id="budgetAchiForGroup" name="acc"/>Budget Achievement for Group</td>
            </tr>


        </table>

        <div style="text-align: right; margin-top:10px; padding-top: 7px;">
            <input type="button" id="btnReset" title="Reset" />    
            <input type="button" id="btnExit" title="Exit" />
            <input type="button" id="print" title="Print" />
        </div>
    </fieldset>
    <input type="hidden" id='by' name='by'  class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" id='type' name='type' value='' title="" >
    <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
    <input type="hidden" name='row_count' title="row_count" id="row_count">
    <input type="hidden" name='clusters' title="" id="clusters" >
    <input type="hidden" name='branchs' title="" id="branchs">
    <input type="hidden" name='supervisors' title="" id="supervisors">
</form>
</div>

