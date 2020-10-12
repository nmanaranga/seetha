<?php  if (!$isSupAdmin) {return;} ?>


<style type="text/css">
	
	#serch_pop_prent_mod {
		position: absolute;
		width: 450px;
		display: none;
		z-index: 999999;
		background-color: #FFF;
		border: 1px dotted #CCC;
		padding: 7px;
		margin: auto;
		top: 100px;
	}

	#serch_pop_all_mod {
		position: absolute;
		width: 525px;
		display: none;
		z-index: 999999;
		background-color: #FFF;
		border: 1px dotted #CCC;
		padding: 7px;
		margin: auto;
		top: 100px;
	}


	#blocker_pop_mnu{
		width: 100%;
		height: 100%;
		z-index: 150;
		background-color: #000;
		position:fixed;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		margin: auto;
		opacity: 0.8 !important;
		display: none;
	}


	#sr_all_mod{
		height: 250px;
		overflow: auto;
	}

	#sr_prent_mod{
		height: 250px;
		overflow: auto;
	}
</style>

<div id="blocker_pop_mnu"></div>
<div id="serch_pop_prent_mod" style="width: 800px;">
	<input type="text" id="pop_search_prent_mod" title=""  style="width: 100%;" class="input_acitve"/><br />
	<div id="sr_prent_mod" style="height:400px;"></div>
	<div style="text-align: right; padding-top: 7px;"><button id="pop_close_prent_mod" >Close</button></div>
</div>

<div id="serch_pop_all_mod" style="width: 800px;">
	<input type="text" id="pop_search_all_mod" title=""  style="width: 100%;" /><br />
	<div id="sr_all_mod" style="height:400px;"></div>
	<div style="text-align: right; padding-top: 7px;">
		<button id="pop_close_all_mod" >Close</button>
	</div>
</div>

<script type='text/javascript' src='<?=base_url()?>js/s_menu_add.js'></script>
<link rel="stylesheet" href="<?=base_url()?>css/themify-icons/IcoList.css">

<h2>Add Menu Items</h2>
<table width="100%"> 

	<tr>
		<td valign="top" class="content" align="center" style="width:700px;">
			<div class="form" id="form" style="width:700px;">
				<form id="form_" method="post" action="<?=base_url()?>index.php/main/save/s_menu_add" >
					<table width="100%">

						<tr>
							<td width="5%">Main</td>
							<td width="3%"><input type="checkbox" class='chk' name="chk_0" id="chk_0" /></td>
							<td width="21%">
								<input type="text" class='sel input_txt' id="sel_0" readonly="readonly" />
							</td>
							<td width="21%"><input type="text" name="" class='hid_value txtmod' id="txtmod_0" readonly="readonly" /></td>
							<td width="21%"><input type="text" name="" class='input_txt' id="txtdes_0" /></td>
							<td width="8%">&nbsp;</td>
							<td width="8%" align="right">
								No: <input type="text" value="<?=$mnu_mx?>" title="<?=$mnu_mx?>" name="mnu_no" class='input_txt' id="mnu_no" style="width: 50px;" />
								<input type="hidden" value="<?=$mnu_mx?>" title="<?=$mnu_mx?>" name="mnu_no_hid" id="mnu_no_hid" />
							</td>
						</tr>
						<tr>
							<td>Sub1</td>
							<td><input type="checkbox" class='chk' name="chk_1" id="chk_1"/></td>
							<td width="21%">
								<input type="text" class='sel input_txt' id="sel_1" readonly="readonly" disabled="disabled"/>
							</td>
							<td width="21%"><input type="text" name="" class='hid_value txtmod' id="txtmod_1" readonly="readonly" /></td>
							<td width="21%"><input type="text" name="" class='input_txt' id="txtdes_1" /></td>
							<td width="8%">&nbsp;</td>

							<td width="21%" rowspan="3">
								<span name="btnSetIco" id="btnSetIco" class=""></span>
								<input type="hidden" name="txtSetIco" id="txtSetIco" />
							</td>		
						</tr>
						<tr>
							<td>Sub2</td>
							<td><input type="checkbox" class='chk' name="chk_2" id="chk_2" /></td>
							<td width="21%">
								<input type="text" class='sel input_txt' id="sel_2" readonly="readonly" disabled="disabled"/>
							</td>
							<td width="21%"><input type="text" name="" class='hid_value txtmod' id="txtmod_2" readonly="readonly" /></td>
							<td width="21%"><input type="text" name="" class='input_txt' id="txtdes_2" /></td>
							<td width="8%">&nbsp;</td>
						</tr>
						<tr>
							<td>Sub3</td>
							<td><input type="checkbox" class='chk' name="chk_3" id="chk_3" /></td>
							<td width="21%">
								<input type="text" class='sel input_txt' id="sel_3" readonly="readonly" disabled="disabled"/>
							</td>
							<td width="21%"><input type="text" name="" class='hid_value txtmod' id="txtmod_3" readonly="readonly" /></td>
							<td width="21%"><input type="text" name="" class='input_txt' id="txtdes_3" /></td>
							<td width="8%">&nbsp;</td>
							<!-- <td width="21%">&nbsp;</td> -->
						</tr>
						<tr>
							<td>Sub4</td>
							<td><input type="checkbox" class='xxxchk' name="xxxchk_4" id="chk_4" disabled="disabled" /></td>
							<td width="21%">
								<input type="text" class='sel input_txt' id="sel_4" readonly="readonly" disabled="disabled"/>
							</td>
							<td width="21%"><input type="text" name="" class='hid_value txtmod' id="txtmod_4" readonly="readonly" /></td>
							<td width="21%"><input type="text" name="" class='input_txt' id="txtdes_4" /></td>
							<td width="8%">&nbsp;</td>
							<td width="21%">&nbsp;</td>
						</tr>

						<tr>
							<td colspan="7" >&nbsp;</td>
						</tr>

						<tr>
							<td></td>
							<td colspan="2"><input type="checkbox" name="OpInNwTab" id="OpInNwTab" />Open In New Tab</td>
							<td colspan="2"><input type="checkbox" name="is_right" id="is_right" />Is In Right</td>
							<td></td>
						</tr>

						<tr>
							<td colspan="7" style="display: none;">
								<input type='text' name='model_id' id='model_id' /><br/>
								<input type='text' name='mod_0' id='mod_0' />
								<input type='text' name='des_0' id='des_0' />
								<input type='text' class='' readonly="readonly" name='no_0' id='no_0' min="1"/>
								<br/>
								<input type='text' name='mod_1' id='mod_1' />
								<input type='text' name='des_1' id='des_1' />
								<input type='text' class='' readonly="readonly" name='no_1' id='no_1' min="1" />
								<br/>
								<input type='text' name='mod_2' id='mod_2' />
								<input type='text' name='des_2' id='des_2' />
								<input type='text' class='' readonly="readonly" name='no_2' id='no_2' min="1" />
								<br/>
								<input type='text' name='mod_3' id='mod_3' />
								<input type='text' name='des_3' id='des_3' />
								<input type='text' class='' readonly="readonly" name='no_3' id='no_3' min="1" />
								<br/>
								<input type='text' name='mod_4' id='mod_4' />
								<input type='text' name='des_4' id='des_4' />
								<input type='text' class='' readonly="readonly" name='no_4' id='no_4' min="1" />
								<br/>
								<input type='text' id='minOdr' />
								<input type='text' id='maxOdr' />
							</td>
						</tr>
						<tr><td colspan="7">&nbsp;</td></tr>
						<tr><td colspan="7">Update Order No to 
							<input type="text" class="input_txt" id="txtUpOdr" style="width: 50px;" disabled="disabled"  value="<?=$mnu_mx?>" title="<?=$mnu_mx?>"/>
							<input type="button" id="btnUpOdr" title="Update Order" disabled="disabled" />
						</td></tr>
						<tr><td colspan="7">&nbsp;</td></tr>
						<tr>
							<td style="text-align:right" colspan="7">
								<input type="hidden" id="code_" name="code_" title="1"  value="1" />
								<input type="button" id="btnExit" title="Exit" />
								<input type="button" id="btnDelete_mnu" title='Delete' />
								<input type="button" id="btnSave_mnu" title='Save <F8>' />
								<input type="button" id="btnReset" title='Reset'>
							</td>
						</tr>
					</table>

				</form>
			</div>
		</td>
	</tr>
</table>
<?=$menu?>




<div id="addIconsMain"style="width: 800px; height: 500px;">
	<div id="addIcons">
		<div class="icon-container">
			<span class=""></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-down"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrows-vertical"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrows-horizontal"></span>
		</div>
		<div class="icon-container">
			<span class="ti-angle-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-angle-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-angle-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-angle-down"></span>
		</div>	
		<div class="icon-container">
			<span class="ti-angle-double-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-angle-double-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-angle-double-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-angle-double-down"></span>
		</div>					
		<div class="icon-container">
			<span class="ti-move"></span>
		</div>
		<div class="icon-container">
			<span class="ti-fullscreen"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-top-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-top-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-circle-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-circle-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-circle-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrow-circle-down"></span>
		</div>
		<div class="icon-container">
			<span class="ti-arrows-corner"></span>
		</div>
		<div class="icon-container">
			<span class="ti-split-v"></span>
		</div>

		<div class="icon-container">
			<span class="ti-split-v-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-split-h"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hand-point-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hand-point-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hand-point-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hand-point-down"></span>
		</div>
		<div class="icon-container">
			<span class="ti-back-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-back-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-exchange-vertical"></span>
		</div>

		<div class="icon-container">
			<span class="ti-wand"></span>
		</div>
		<div class="icon-container">
			<span class="ti-save"></span>
		</div>
		<div class="icon-container">
			<span class="ti-save-alt"></span>
		</div>

		<div class="icon-container">
			<span class="ti-direction"></span>
		</div>
		<div class="icon-container">
			<span class="ti-direction-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-user"></span>
		</div>
		<div class="icon-container">
			<span class="ti-link"></span>
		</div>
		<div class="icon-container">
			<span class="ti-unlink"></span>
		</div>
		<div class="icon-container">
			<span class="ti-trash"></span>
		</div>
		<div class="icon-container">
			<span class="ti-target"></span>
		</div>
		<div class="icon-container">
			<span class="ti-tag"></span>
		</div>
		<div class="icon-container">
			<span class="ti-desktop"></span>
		</div>
		<div class="icon-container">
			<span class="ti-tablet"></span>
		</div>
		<div class="icon-container">
			<span class="ti-mobile"></span>
		</div>
		<div class="icon-container">
			<span class="ti-email"></span>
		</div>	
		<div class="icon-container">
			<span class="ti-star"></span>
		</div>
		<div class="icon-container">
			<span class="ti-spray"></span>
		</div>
		<div class="icon-container">
			<span class="ti-signal"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shopping-cart"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shopping-cart-full"></span>
		</div>
		<div class="icon-container">
			<span class="ti-settings"></span>
		</div>
		<div class="icon-container">
			<span class="ti-search"></span>
		</div>
		<div class="icon-container">
			<span class="ti-zoom-in"></span>
		</div>
		<div class="icon-container">
			<span class="ti-zoom-out"></span>
		</div>
		<div class="icon-container">
			<span class="ti-cut"></span>
		</div>
		<div class="icon-container">
			<span class="ti-ruler"></span>
		</div>
		<div class="icon-container">
			<span class="ti-ruler-alt-2"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-ruler-pencil"></span>
		</div>
		<div class="icon-container">
			<span class="ti-ruler-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bookmark"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bookmark-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-reload"></span>
		</div>
		<div class="icon-container">
			<span class="ti-plus"></span>
		</div>
		<div class="icon-container">
			<span class="ti-minus"></span>
		</div>
		<div class="icon-container">
			<span class="ti-close"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-pin"></span>
		</div>
		<div class="icon-container">
			<span class="ti-pencil"></span>
		</div>

		<div class="icon-container">
			<span class="ti-pencil-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-paint-roller"></span>
		</div>
		<div class="icon-container">
			<span class="ti-paint-bucket"></span>
		</div>
		<div class="icon-container">
			<span class="ti-na"></span>
		</div>
		<div class="icon-container">
			<span class="ti-medall"></span>
		</div>
		<div class="icon-container">
			<span class="ti-medall-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-marker"></span>
		</div>
		<div class="icon-container">
			<span class="ti-marker-alt"></span>
		</div>

		<div class="icon-container">
			<span class="ti-lock"></span>
		</div>
		<div class="icon-container">
			<span class="ti-unlock"></span>
		</div>
		<div class="icon-container">
			<span class="ti-location-arrow"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layers"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layers-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-key"></span>
		</div>
		<div class="icon-container">
			<span class="ti-image"></span>
		</div>
		<div class="icon-container">
			<span class="ti-heart"></span>
		</div>
		<div class="icon-container">
			<span class="ti-heart-broken"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hand-stop"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hand-open"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hand-drag"></span>
		</div>
		<div class="icon-container">
			<span class="ti-flag"></span>
		</div>
		<div class="icon-container">
			<span class="ti-flag-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-flag-alt-2"></span>
		</div>
		<div class="icon-container">
			<span class="ti-eye"></span>
		</div>
		<div class="icon-container">
			<span class="ti-import"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-export"></span>
		</div>
		<div class="icon-container">
			<span class="ti-cup"></span>
		</div>
		<div class="icon-container">
			<span class="ti-crown"></span>
		</div>
		<div class="icon-container">
			<span class="ti-comments"></span>
		</div>
		<div class="icon-container">
			<span class="ti-comment"></span>
		</div>
		<div class="icon-container">
			<span class="ti-comment-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-thought"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-clip"></span>
		</div>

		<div class="icon-container">
			<span class="ti-check"></span>
		</div>
		<div class="icon-container">
			<span class="ti-check-box"></span>
		</div>
		<div class="icon-container">
			<span class="ti-camera"></span>
		</div>
		<div class="icon-container">
			<span class="ti-announcement"></span>
		</div>
		<div class="icon-container">
			<span class="ti-brush"></span>
		</div>
		<div class="icon-container">
			<span class="ti-brush-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-palette"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-briefcase"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bolt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bolt-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-blackboard"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bag"></span>
		</div>
		<div class="icon-container">
			<span class="ti-world"></span>
		</div>
		<div class="icon-container">
			<span class="ti-wheelchair"></span>
		</div>
		<div class="icon-container">
			<span class="ti-car"></span>
		</div>
		<div class="icon-container">
			<span class="ti-truck"></span>
		</div>
		<div class="icon-container">
			<span class="ti-timer"></span>
		</div>
		<div class="icon-container">
			<span class="ti-ticket"></span>
		</div>
		<div class="icon-container">
			<span class="ti-thumb-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-thumb-down"></span>
		</div>

		<div class="icon-container">
			<span class="ti-stats-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-stats-down"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shine"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shift-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shift-left"></span>
		</div>

		<div class="icon-container">
			<span class="ti-shift-right-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shift-left-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shield"></span>
		</div>
		<div class="icon-container">
			<span class="ti-notepad"></span>
		</div>
		<div class="icon-container">
			<span class="ti-server"></span>
		</div>

		<div class="icon-container">
			<span class="ti-pulse"></span>
		</div>
		<div class="icon-container">
			<span class="ti-printer"></span>
		</div>
		<div class="icon-container">
			<span class="ti-power-off"></span>
		</div>
		<div class="icon-container">
			<span class="ti-plug"></span>
		</div>
		<div class="icon-container">
			<span class="ti-pie-chart"></span>
		</div>

		<div class="icon-container">
			<span class="ti-panel"></span>
		</div>
		<div class="icon-container">
			<span class="ti-package"></span>
		</div>
		<div class="icon-container">
			<span class="ti-music"></span>
		</div>
		<div class="icon-container">
			<span class="ti-music-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-mouse"></span>
		</div>
		<div class="icon-container">
			<span class="ti-mouse-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-money"></span>
		</div>
		<div class="icon-container">
			<span class="ti-microphone"></span>
		</div>
		<div class="icon-container">
			<span class="ti-menu"></span>
		</div>
		<div class="icon-container">
			<span class="ti-menu-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-map"></span>
		</div>
		<div class="icon-container">
			<span class="ti-map-alt"></span>
		</div>

		<div class="icon-container">
			<span class="ti-location-pin"></span>
		</div>

		<div class="icon-container">
			<span class="ti-light-bulb"></span>
		</div>
		<div class="icon-container">
			<span class="ti-info"></span>
		</div>
		<div class="icon-container">
			<span class="ti-infinite"></span>
		</div>
		<div class="icon-container">
			<span class="ti-id-badge"></span>
		</div>
		<div class="icon-container">
			<span class="ti-hummer"></span>
		</div>
		<div class="icon-container">
			<span class="ti-home"></span>
		</div>
		<div class="icon-container">
			<span class="ti-help"></span>
		</div>
		<div class="icon-container">
			<span class="ti-headphone"></span>
		</div>
		<div class="icon-container">
			<span class="ti-harddrives"></span>
		</div>
		<div class="icon-container">
			<span class="ti-harddrive"></span>
		</div>
		<div class="icon-container">
			<span class="ti-gift"></span>
		</div>
		<div class="icon-container">
			<span class="ti-game"></span>
		</div>
		<div class="icon-container">
			<span class="ti-filter"></span>
		</div>
		<div class="icon-container">
			<span class="ti-files"></span>
		</div>
		<div class="icon-container">
			<span class="ti-file"></span>
		</div>
		<div class="icon-container">
			<span class="ti-zip"></span>
		</div>
		<div class="icon-container">
			<span class="ti-folder"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-envelope"></span>
		</div>


		<div class="icon-container">
			<span class="ti-dashboard"></span>
		</div>
		<div class="icon-container">
			<span class="ti-cloud"></span>
		</div>
		<div class="icon-container">
			<span class="ti-cloud-up"></span>
		</div>
		<div class="icon-container">
			<span class="ti-cloud-down"></span>
		</div>
		<div class="icon-container">
			<span class="ti-clipboard"></span>
		</div>
		<div class="icon-container">
			<span class="ti-calendar"></span>
		</div>
		<div class="icon-container">
			<span class="ti-book"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bell"></span>
		</div>
		<div class="icon-container">
			<span class="ti-basketball"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bar-chart"></span>
		</div>
		<div class="icon-container">
			<span class="ti-bar-chart-alt"></span>
		</div>


		<div class="icon-container">
			<span class="ti-archive"></span>
		</div>
		<div class="icon-container">
			<span class="ti-anchor"></span>
		</div>

		<div class="icon-container">
			<span class="ti-alert"></span>
		</div>
		<div class="icon-container">
			<span class="ti-alarm-clock"></span>
		</div>
		<div class="icon-container">
			<span class="ti-agenda"></span>
		</div>
		<div class="icon-container">
			<span class="ti-write"></span>
		</div>

		<div class="icon-container">
			<span class="ti-wallet"></span>
		</div>
		<div class="icon-container">
			<span class="ti-video-clapper"></span>
		</div>
		<div class="icon-container">
			<span class="ti-video-camera"></span>
		</div>
		<div class="icon-container">
			<span class="ti-vector"></span>
		</div>

		<div class="icon-container">
			<span class="ti-support"></span>
		</div>
		<div class="icon-container">
			<span class="ti-stamp"></span>
		</div>
		<div class="icon-container">
			<span class="ti-slice"></span>
		</div>
		<div class="icon-container">
			<span class="ti-shortcode"></span>
		</div>
		<div class="icon-container">
			<span class="ti-receipt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-pin2"></span>
		</div>
		<div class="icon-container">
			<span class="ti-pin-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-pencil-alt2"></span>
		</div>
		<div class="icon-container">
			<span class="ti-eraser"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-more"></span>
		</div>
		<div class="icon-container">
			<span class="ti-more-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-microphone-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-magnet"></span>
		</div>
		<div class="icon-container">
			<span class="ti-line-double"></span>
		</div>
		<div class="icon-container">
			<span class="ti-line-dotted"></span>
		</div>
		<div class="icon-container">
			<span class="ti-line-dashed"></span>
		</div>

		<div class="icon-container">
			<span class="ti-ink-pen"></span>
		</div>
		<div class="icon-container">
			<span class="ti-info-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-help-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-headphone-alt"></span>
		</div>

		<div class="icon-container">
			<span class="ti-gallery"></span>
		</div>
		<div class="icon-container">
			<span class="ti-face-smile"></span>
		</div>
		<div class="icon-container">
			<span class="ti-face-sad"></span>
		</div>
		<div class="icon-container">
			<span class="ti-credit-card"></span>
		</div>
		<div class="icon-container">
			<span class="ti-comments-smiley"></span>
		</div>
		<div class="icon-container">
			<span class="ti-time"></span>
		</div>
		<div class="icon-container">
			<span class="ti-share"></span>
		</div>
		<div class="icon-container">
			<span class="ti-share-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-rocket"></span>
		</div>

		<div class="icon-container">
			<span class="ti-new-window"></span>
		</div>

		<div class="icon-container">
			<span class="ti-rss"></span>
		</div>

		<div class="icon-container">
			<span class="ti-rss-alt"></span>
		</div>






		<div class="icon-container">
			<span class="ti-control-stop"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-shuffle"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-play"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-pause"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-forward"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-backward"></span>
		</div>	
		<div class="icon-container">
			<span class="ti-volume"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-skip-forward"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-skip-backward"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-record"></span>
		</div>
		<div class="icon-container">
			<span class="ti-control-eject"></span>
		</div>			






		<div class="icon-container">
			<span class="ti-paragraph"></span>
		</div>
		<div class="icon-container">
			<span class="ti-uppercase"></span>
		</div>

		<div class="icon-container">
			<span class="ti-underline"></span>
		</div>
		<div class="icon-container">
			<span class="ti-text"></span>
		</div>
		<div class="icon-container">
			<span class="ti-Italic"></span>
		</div>
		<div class="icon-container">
			<span class="ti-smallcap"></span>
		</div>
		<div class="icon-container">
			<span class="ti-list"></span>
		</div>
		<div class="icon-container">
			<span class="ti-list-ol"></span>
		</div>
		<div class="icon-container">
			<span class="ti-align-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-align-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-align-justify"></span>
		</div>
		<div class="icon-container">
			<span class="ti-align-center"></span>
		</div>
		<div class="icon-container">
			<span class="ti-quote-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-quote-left"></span>
		</div>







		<div class="icon-container">
			<span class="ti-layout-width-full"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-width-default"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-width-default-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-tab"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-tab-window"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-tab-v"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-tab-min"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-slider"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-slider-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-sidebar-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-sidebar-none"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-sidebar-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-placeholder"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-menu"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-menu-v"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-menu-separated"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-menu-full"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-right-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-overlay"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-overlay-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-overlay-alt-2"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-left-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-center"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-media-center-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-list-thumb"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-list-thumb-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-list-post"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-list-large-image"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-line-solid"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-grid4"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-grid3"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-grid2"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-grid2-thumb"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-cta-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-cta-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-cta-center"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-cta-btn-right"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-cta-btn-left"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-column4"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-column3"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-column2"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-accordion-separated"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-accordion-merged"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-accordion-list"></span>
		</div>
		<div class="icon-container">
			<span class="ti-widgetized"></span>
		</div>
		<div class="icon-container">
			<span class="ti-widget"></span>
		</div>
		<div class="icon-container">
			<span class="ti-widget-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-view-list"></span>
		</div>
		<div class="icon-container">
			<span class="ti-view-list-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-view-grid"></span>
		</div>
		<div class="icon-container">
			<span class="ti-upload"></span>
		</div>
		<div class="icon-container">
			<span class="ti-download"></span>
		</div>	
		<div class="icon-container">
			<span class="ti-loop"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-sidebar-2"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-grid4-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-grid3-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-grid2-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-column4-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-column3-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-layout-column2-alt"></span>
		</div>		






		<div class="icon-container">
			<span class="ti-flickr"></span>
		</div>
		<div class="icon-container">
			<span class="ti-flickr-alt"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-instagram"></span>
		</div>
		<div class="icon-container">
			<span class="ti-google"></span>
		</div>
		<div class="icon-container">
			<span class="ti-github"></span>
		</div>

		<div class="icon-container">
			<span class="ti-facebook"></span>
		</div>
		<div class="icon-container">
			<span class="ti-dropbox"></span>
		</div>
		<div class="icon-container">
			<span class="ti-dropbox-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-dribbble"></span>
		</div>
		<div class="icon-container">
			<span class="ti-apple"></span>
		</div>
		<div class="icon-container">
			<span class="ti-android"></span>
		</div>
		<div class="icon-container">
			<span class="ti-yahoo"></span>
		</div>
		<div class="icon-container">
			<span class="ti-trello"></span>
		</div>
		<div class="icon-container">
			<span class="ti-stack-overflow"></span>
		</div>
		<div class="icon-container">
			<span class="ti-soundcloud"></span>
		</div>
		<div class="icon-container">
			<span class="ti-sharethis"></span>
		</div>
		<div class="icon-container">
			<span class="ti-sharethis-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-reddit"></span>
		</div>

		<div class="icon-container">
			<span class="ti-microsoft"></span>
		</div>
		<div class="icon-container">
			<span class="ti-microsoft-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-linux"></span>
		</div>
		<div class="icon-container">
			<span class="ti-jsfiddle"></span>
		</div>
		<div class="icon-container">
			<span class="ti-joomla"></span>
		</div>
		<div class="icon-container">
			<span class="ti-html5"></span>
		</div>
		<div class="icon-container">
			<span class="ti-css3"></span>
		</div>	
		<div class="icon-container">
			<span class="ti-drupal"></span>
		</div>
		<div class="icon-container">
			<span class="ti-wordpress"></span>
		</div>		
		<div class="icon-container">
			<span class="ti-tumblr"></span>
		</div>
		<div class="icon-container">
			<span class="ti-tumblr-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-skype"></span>
		</div>
		<div class="icon-container">
			<span class="ti-youtube"></span>
		</div>
		<div class="icon-container">
			<span class="ti-vimeo"></span>
		</div>
		<div class="icon-container">
			<span class="ti-vimeo-alt"></span>
		</div>			
		<div class="icon-container">
			<span class="ti-twitter"></span>
		</div>
		<div class="icon-container">
			<span class="ti-twitter-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-linkedin"></span>
		</div>
		<div class="icon-container">
			<span class="ti-pinterest"></span>
		</div>

		<div class="icon-container">
			<span class="ti-pinterest-alt"></span>
		</div>
		<div class="icon-container">
			<span class="ti-themify-logo"></span>
		</div>
		<div class="icon-container">
			<span class="ti-themify-favicon"></span>
		</div>
		<div class="icon-container">
			<span class="ti-themify-favicon-alt"></span>
		</div>

	</div>
	<div style="text-align: right; padding-top: 7px;">
		<button id="pop_close_mnu_ico" >Close</button>
	</div>
</div>





