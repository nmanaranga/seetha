
<script type='text/javascript' src='<?=base_url()?>js/t_sales_target.js'></script>
<?php  ?>
<h2>Monthly Budget</h2>
<table width="100%" >
    <tr>
        <td valign="top" class="content" style="width: 450px;">
            <div class="form" id="form" style="width: auto;">

                <table >

                    <tr>
                        <td>Cluster</td>
                        <td>
                            <input type="text" class="input_txt" readonly="" 
                            title="<?=$txtCluster;?>" value="<?=$txtCluster;?>" id="cluster"   style="width:150px;">

                        </td>
                        <!-- <td style="width: 200px">&nbsp;</td>  -->
                        <td>Month</td>    
                        <td>
                            <input  id="txtMonth" placeholder="Select Month ..." class="monthYearPicker" />
                        </td>    


                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td>
                            <input type="text" class="input_txt" readonly="" 
                            title="<?=$txtBranch;?>" value="<?=$txtBranch;?>" id="branch"  style="width:150px;">

                        </td>

                        <!--  <td style="width: 200px">&nbsp;</td>  -->
                        <td>&nbsp;</td>    
                        <td>
                            <input type="button" id="btnLoad" title="Load" />
                        </td> 
                    </tr>


                </table>
                <table>


                    <tr>
                        <td> <form id="form_" method="POST" action="<?=base_url()?>index.php/main/save/t_sales_target" >
                            <div style="width: 690px;margin-top: 23px;" id="#right" class="list_div"> </div>

                            <!-- hidden -->
                            <input type="hidden" id="txtDayC" name="txtDayC"   />
                            <input type="hidden" id="txtDate" name="txtDate"   />
                            <input type="hidden" id="txtStatus" name="txtStatus"   />
                            <input type="hidden" id="txtSumid" name="txtSumid"   />
                        </form>
                    </td>
                </tr>

                <tr>
                    <td> 
                        <label class="fontBold">Cumilative Target :</label>
                        <input type="text" readonly="" class="input_active" id="txtCumilativeTar"    />
                    </td>
                    </td>
                </tr>
                <tr>
                    <td> 
                        <label class="fontBold">Cumilative Archivement :</label>
                        <input type="text" readonly="" class="input_active" id="txtCumilativeArch"   /> 
                    </td>
                </tr>
                <tr>
                    <td> 
                        <label class="fontBold">Cumilative Variance : </label>
                        <input type="text" readonly="" class="input_active" id="txtCumVarince" />
                        <input type="text" readonly="" class="input_active" id="txtCumVarincePre" />
                    </td>
                </tr>

                <tr>
                    <td style="text-align: right;">
                        <input type="button" id="btnLoad" title="Exit" />
                        <input type="button" id="btnSave" title="Save" />
                        <input type="button" id="btnPrint" title="Print" />
                    </td>
                </tr>
            </table>

            <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
             <input type="hidden" name='by' value='t_sales_target' title="t_sales_target" class="report">
             <input type="hidden" name='page' value='A4' title="A4" >
             <input type="hidden" name='orientation' value='P' title="P" >
             <input type="hidden" name='header' value='false' title="false" >
             <input type="hidden" name='txtmMonth' value='' title="" id="txtmMonth" >
             <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
         </form>

     </td>

 </tr>
</table>


<?php  ?>


<style type="text/css">
    .fontBold{
        font-weight: bold;
    }
</style>

