<?php if($this->user_permissions->is_view('m_items')){ ?>

<style>

#sc

{

width:500px;

height:50px;

overflow:scroll;

}
</style>

<script type='text/javascript' src='<?=base_url()?>js/m_items.js'></script>
<h2>Items</h2>
    <table border="0">
        <tr>
            <td valign="top"  class="content"  style="width: 580px;">
                <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_items" enctype="multipart/form-data">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">	
						<tr>
                            <td style="width:100px;">Department</td>                           
                            <td><input type="text" class="input_txt ld" title="" id="department" name="department" data='r_department'/></td>
							<td colspan="2">
								<input type="text" class="hid_value"  readonly="readonly" id="department_des"  style="width: 250px;">
								<input type="hidden" class="hid_value" id="dep_codegen">
								<input type="button" title="..." name="btndepartment" id="btndepartment" value="...">
							</td>
						</tr>			
						<tr>
                            <td>Main Category</td>                       
                            <td><input type="text" class="input_txt ld" title="" id="main_category" name="main_category" data='r_category'/></td>
							<td colspan="2">
								<input type="text" class="hid_value"  readonly="readonly" id="main_category_des" style="width: 250px;">
								<input type="hidden" class="hid_value" id="mcat_codegen">
								<input type="button" title="..." name="btncategory" id="btncategory" value="...">
							</td>
                        </tr>
						<tr>
                            <td>Sub Category</td>
							<td><input type="text" class="input_txt ld" title="" id="sub_category" name="sub_category" data='r_sub_category'/></td>
							<td colspan="2">
								<input type="text" class="hid_value"  readonly="readonly" id="sub_category_des"  style="width: 250px;">
								<input type="hidden" class="hid_value" id="scat_codegen">
								<input type="button" title="..." name="btnsub_category" id="btnsub_category" value="...">
							</td>
                     </tr>
						<tr>
						<td>Unit</td>                        
                        	<td><input type="text" class="input_txt ld" title="" id="unit" name="unit" data='r_unit'/></td>
							<td colspan="2">
								<input type="text" class="hid_value"  readonly="readonly" id="unit_des"  style="width: 250px;">
								<input type="hidden" class="hid_value" id="unit_codegen">
								<input type="button" title="..." name="btn_unit" id="btn_unit" value="...">
							</td>
                        </tr>									
						<tr>
                          <td>Supplier</td>
                          <td><input type="text" class="input_txt ld" title="" id="supplier" name="supplier" data='m_supplier'/></td>
						  <td align="left" colspan="2">
						  	<input  class="hid_value" id="supplier_des"  style="width: 250px;"  readonly="readonly" />
						  	<input type="hidden" class="hid_value" id="s_codegen">
						  	<input type="button" title="..." name="btn_supplier" id="btn_supplier" value="...">
						  </td>
                        </tr>
						<tr>
							<td colspan="4"><hr style="width:100%;height:2px;background-color:#ad8e98;border:none;"/></td>
						</tr>
                        <tr>
                            <td>Code</td>                           
                            <td colspan="3">
                            	<input type="text" class="input_active"  id="code" name="code" maxlength="15" style="width:180px; text-transform:uppercase;"> <input type="hidden" id="code_" name="code_" />
                            	<input type='button' id='item_gen' title='Generate' value='Generate'/>
                            </td>
                        </tr>					
						<tr>
                            <td>Description</td>                            
                            <td colspan="3"><input type="text" class="input_txt"  id="description" name="description" style="width:400px;" maxlength="100"/></td>
                        </tr>
						<tr>
							<td colspan="4"><hr style="width:100%;height:2px;background-color:#ad8e98;border:none;"/></td>
						</tr>
                        <tr>
						<td colspan="5" align="center"><input type="checkbox" name="inactive" id="inactive" title="1" /> In active
						<input type="checkbox" name="serial_no" id="serial_no" title="1" />Serial Number Item
						<input type="checkbox" name="batch_item" id="batch_item" title="1"   />Batch Item
						</td>
                        </tr>
                        <td colspan="4"><hr style="width:100%;height:2px;background-color:#ad8e98;border:none;"/></td>
                    </table>
					 <div id="tabs" >
									<ul>

										<li><a href="#tabs-1" >Details</a></li>

										<li><a href="#tabs-3" >Picture</a></li>

										<li><a href="#tabs-2" >Upload</a></li>

										<li><a href="#tabs-4" >Available Stock</a></li>

									</ul>

                    <div id="tabs-1">

								<fieldset>

									  <legend>Details</legend>

								<table border="0" cellpadding="0">

											<tr>

													<td style="width:100px;">Model</td>

													<td><input type="text" class="input_txt"   id="model"  name="model" style="width: 150px;" maxlength="20"></td>

													<td></td>

													<td style="width:200px">Bar Code</td>

													<td><input type="text" class="input_txt"  id="barcode" name="barcode" style="width: 150px;" maxlength="20"></td>

													<td></td>

											</tr>

							

											<tr>

												<td>Brand</td>

												<td><input type="text" class="input_txt" id="brand" name="brand"/></td>

												

												<td colspan="3" ><input type="text" class="input_txt"   id="brand_des"  style="width: 320px;" readonly="readonly"></td>

												<td><input type="button" title="..." id="btn_brand" name="btn_brand"/></td>

											</tr>

		

											<tr>

													<td>EOQ</td>

													<td><input type="text" class="input_txt g_input_amo"  id="rol" name="rol" style="width: 150px;"></td>

													<td style="width:50px;"></td>

													

													<td>ROQ</td>

													<td><input type="text" class="input_txt g_input_amo"   id="roq"  name="roq" style="width: 150px;"></td>

													<td></td>

											</tr>

								</table>



								</fieldset>



			<fieldset>

						<legend>Price</legend>

					<table border="0" cellpadding="0">

							<tr>

									<td>Purchase</td>

									<td colspan="3"><input type="text" class="input_txt g_input_amo" title="" id="purchase_price"  name="purchase_price" style="width: 150px;text-align:right;" maxlength="14" autocomplete='off'></td>

							</tr>



							<tr>

								<td>Min Sales Price</td>

								<td><input type="text" class="input_txt g_input_amo" title="" id="min_price"  name="min_price" style="width: 150px;text-align:right;" maxlength="14"></td>

								<td>Min S Price %</td>

								<td><input type="text" class="input_txt g_input_amo" title="" id="min_price_p"  name="min_price_p" style="width:75px;text-align:right;" maxlength="14"></td>

							</tr>



							<tr>

								<td>Max Sales Price</td>

								<td><input type="text" class="input_txt g_input_amo" title=""  id="max_price"  name="max_price" style="width: 150px;text-align:right;" maxlength="14"></td>

								<td>Max S Price %</td>

								<td><input type="text" class="g_input_amo input_txt " title=""  id="max_price_p"  name="max_price_p" style="width: 75px;text-align:right;" maxlength="14"></td>

							</tr>











							<tr>

								<td>Last modified date</td>	

								<td colspan="3"><input type"text" name="modified_date" class="hid_value" id="modified_date" style="width: 150px;" maxlength="12" readonly/></td>

								

							</tr>





					</table>

		

			</fieldset>

			

		<?php if($ds['use_sub_items']){ ?>

		<fieldset>

            <legend>Sub Items</legend>

		

		<table style="width:100%;" cellpadding="0">

				<thead>

                            <tr>

                                <th class="tb_head_th" style="width: 80px;">Code</th>

                                <th class="tb_head_th">Description</th>

                            </tr>

                </thead>

				

				<tbody>

							<?php

                                

                                for($x=0; $x<3; $x++){

                                    echo "<tr>";

                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />

                                        <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' /></td>";

                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' readonly='readonly' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150'/></td>";

                                    echo "</tr>";

                                }

                            ?>

				</tbody>

				<tfoot>

                            <tr style="background-color: transparent;">

                                <td style="text-align: right; font-weight: bold; font-size: 12px;"></td>

                                <td>&nbsp;</td>

                            </tr>

                 </tfoot>

        </table>

		</fieldset>	

		<?php } ?>											

	</div>



	<div id="tabs-2" >

			

		<div id="image_pannel" style="width:400px;">



			<input type="text" name="image_name1" value="" title="" class="input_txts"/><input type="file" name="userfile1" size="20" />

			<input type="text" name="image_name2" value="" title="" class="input_txts"/><input type="file" name="userfile2" size="20" />

			<input type="text" name="image_name3" value="" title="" class="input_txts"/><input type="file" name="userfile3" size="20" />

			<input type="text" name="image_name4" value="" title="" class="input_txts"/><input type="file" name="userfile4" size="20" />

			<input type="text" name="image_name5" value="" title="" class="input_txts"/><input type="file" name="userfile5" size="20" />	



		</div>

	</div><!--tabs-2-->



	



	<div id="tabs-3">

			<table style='width:200px;background:#FCFCFC ' border='1'>

			<thead>

                <tr>

                <th colspan="3" class="imgtblhead tb_head_th"><b>Picture</b></th>

               </tr>

           </thead>

           <tbody>

           		<tr>

           			<td colspan="2" class="imgtbl"><input type='button' id="pic_name0" class="pic_name0 picNameClass" /></td>

           			<td rowspan='5'>

           			<div id="pic_pic">

           				<img width='150px' height='150px' src='<?=base_url()?>images/no_image.jpg'/>

           			</div>



           			<div class="imgMsg">

           			</div>

           			</td>

           		</tr>



           		<tr>

           			<td colspan="2" class="imgtbl"><input type='button' class="picNameClass" id="pic_name1" /></td>

           			

           		</tr>



           		<tr>

           			<td colspan="2" class="imgtbl"><input type='button' class="picNameClass" id="pic_name2" /></td>

           			

           		</tr>



           		<tr>

           			<td colspan="2" class="imgtbl"><input type='button' class="picNameClass" id="pic_name3" /></td>

           			

           		</tr>



           		<tr>

           			<td colspan="2" class="imgtbl"><input type='button' class="picNameClass" id="pic_name4" class="pic_name4" /></td>

           			

           		</tr>



           </tbody>

           </table>



        	



        	<div  style="width:400px;">



				 



				 </div>

	</div><!--tabs-3-->





	<div id="tabs-4">

		<fieldset>

			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">

                        

                <tr>

                    <td>Cluster</td>

                    <td><?php echo $cluster; ?></td>

                </tr>



                <tr>

                    <td>Branch</td>

                    <td>

                       <select name='branch' id='branch' >

                        <option value='0'>---</option>

                        </select>

                    </td>

                </tr>



                <tr>

                    <td>Store</td>

                    <td style="width:160px;">

                        <select name='store' id='store' >

                        <option value='0'></option>

                        </select>

                    </td>

                    <td>

                    	<input type="button" title="View Available Quantity" id="avbl_qty" name="avbl_qty"/>

                    </td>

                </tr>

            </table>

            <br>

            <legend>Available Quantity</legend>

       

			<table cellpadding="0" id="sc">

				<thead>

                    <tr>

                        <th class="tb_head_th">Cluster</th>

                        <th class="tb_head_th">Branch</th>

                        <th class="tb_head_th">Store</th>

                        <th class="tb_head_th">Available Qty</th>

                    </tr>

                </thead>

			

				<tbody>

							<?php

                                

                                for($x=0; $x<25; $x++){

                                    echo "<tr>";

                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />

                                        <input type='text' class='g_input_txt' readonly id='a0_".$x."' name='a0_".$x."'  /></td>";

                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' readonly class='g_input_txt'  id='a1_".$x."' name='a1_".$x."' maxlength='150'/></td>";

                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' readonly class='g_input_txt'  id='a2_".$x."' name='a2_".$x."' maxlength='150'/></td>";

                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' readonly class='g_input_num'  id='a3_".$x."' name='a3_".$x."' maxlength='150'/></td>";

                                    echo "</tr>";

                                }

                            ?>

             

				</tbody>

				

				<tfoot>

                            <tr style="background-color: transparent;">

                                <td style="text-align: right; font-weight: bold; font-size: 12px;"></td>

                                <td>&nbsp;</td>

                                <td>Total Quantity</td>

                                <td><input type='text' style="width:134px; background-color: transparent;" readonly class='g_input_amo g_input_amounts'  id='tot_qty' name='tot_qty' maxlength='150'/></td>



                            </tr>

                 </tfoot>

        </table>

    

		</fieldset>

	</div>

		<table>

			<tr>

                     <td colspan="2" style="width:40%; padding-left:10px;" >                            

                     <input type="button" id="btnExit" title="Exit" />

                     <?php if($this->user_permissions->is_add('m_items')){ ?><input name="button2" type="button" id="btnSave" title='Save <F8>' /><?php } ?>

                     <input type="hidden" name="sum"/>     

                     <input name="button" type="button" id="btnReset" title='Reset' />



                 	 </td>

             </tr>

						

		</table>			

					

</div>



				

			

		

		



				   

			

                  </form>

                </div>



            </td>

			

             <td id="items_table" valign="top"  class="content">

                <div class="form" id="form">

                <table>

            <tr>

            <td style="padding-right:64px;"><label>Search</label></td>

            <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;">
            	<input type="button" id="item_list" title="Item List" style="width:100px;">
            </td>

            </tr>

            </table> 

                    <div id="grid_body"><?=$table_data;?></div>

                </div>

            </td>

            

        </tr>
        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                <input type="hidden" name='by' value='m_items' title="m_items" class="m_items">
                <input type="hidden" name='page' value='A4' title="A4" >
                <input type="hidden" name='orientation' value='P' title="P" >
                <input type="hidden" name='header' value='false' title="false" >
        </form>

    </table>

</div>

<?php } ?>

