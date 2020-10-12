
<script type='text/javascript' src='<?=base_url()?>js/m_branch_supervisor.js'></script>

<h2>Branch Assigned To Supervisor</h2>
<table width="100%" >
    <tr>
        <td valign="top" class="content" style="width: 450px;">
            <div class="form" id="form" style="width: auto;">
             <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_branch_supervisor" >
                <table >

                    <tr>
                        <td style="width:100px;">Branch :</td>
                        <td><?php echo $lbranch; ?></td>
                    </tr>
                    <tr>
                        <td style="width:100px;">Supervisor :</td>
                        <td><?php echo $lemployee; ?></td>
                    </tr> 



                    <tr>
                        <td style="text-align: right;" >
                            <input type="button" id="btnLoad" title="Exit" /> 
                        </td>
                        <td style="text-align: left;" >
                            <input type="button" id="btnSave" title="Save" />
                        </td>


                    </tr> 
                </table>
            </form>
        </div>







    </td>
    <td class="content" valign="top" style="width:700px;">
        <div class="form">
            <table>
                <tr>
                    <!-- <td style="padding-right:64px;"><label>Search</label></td> -->
                    <!-- <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td> -->
                </tr>
            </table>
            <div id="grid_body"><?=$table_data;?></div>
        </div>
    </td>

</tr>
</table>





