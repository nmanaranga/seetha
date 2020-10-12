 var fcid, scid;

 $(document).keydown(function(e){
 	if(e.keyCode ==27){
 		$("#pop_close_all_mod").click();
 		$("#pop_close_prent_mod").click();
 		$("#pop_close_mnu_ico").click();
 	}
 });

 $(document).ready(function() {

 	$("#OpInNwTab").click(function(event) {
 		$(".txtmod").each(function(index, el) {
 			if ($("#OpInNwTab").is(':checked')) {
 				if ($(this).val()!="") {
 					if ($(this).val()!="non") {
 						set_cid($(this).attr('id'));
 						$("#mod_"+scid).val($("#txtmod_"+scid).val()+"' target='_blank");
 					}
 				}
 			}else{
 				set_cid($(this).attr('id'));
 				$("#mod_"+scid).val($("#txtmod_"+scid).val());
 			}
 		});
 	});

 	$("#txtUpOdr").bind('keydown', function(event) {

 	});

 	$("#btnSave_mnu").click(function(){
 		if(validate()){
 			save();
 		}
 	});

 	$("#btnDelete_mnu").click(function(){
 		delete_mnu_itms();
 	});
 	$("#btnUpOdr").click(function(){
 		var minOdr=parseInt($('#minOdr').val()); 
 		var maxOdr=parseInt($('#maxOdr').val());
 		var nNo= parseInt($("#txtUpOdr").val());

 		if (nNo>maxOdr) {
 			$("#txtUpOdr").val(maxOdr);
 		}
 		if (nNo<minOdr) {
 			$("#txtUpOdr").val(minOdr);
 		}			
 		if (isNaN(nNo)) {
 			$("#txtUpOdr").val(minOdr);
 		}
 		if (!isNaN(parseInt($("#txtUpOdr").val()))) {
 			update_order();
 		}
 	});

 	$("#pop_close_all_mod").click(function(){
 		$("#serch_pop_all_mod").css("display", "none");
 		$("#blocker_pop_mnu").css("display", "none");
 	});

 	$("#pop_close_prent_mod").click(function(){
 		$("#serch_pop_prent_mod").css("display", "none");
 		$("#blocker_pop_mnu").css("display", "none");
 	});

 	$("#pop_close_mnu_ico").click(function(){
 		$("#addIconsMain").css("display", "none");
 		$("#blocker_pop_mnu").css("display", "none");
 	});

 	$("#btnSetIco").click(function(event) {
 		$("#addIconsMain").center_mnu_pop();
 		$("#blocker_pop_mnu").css("display", "block");
 	});

 	$("#addIcons .icon-container").click(function(event) {
 		$("#txtSetIco").val($(this).find('span').attr('class'));
 		$("#btnSetIco").removeAttr('class');
 		$("#btnSetIco").addClass($(this).find('span').attr('class'));
 		$("#pop_close_mnu_ico").click();
 	});

 	$(".chk").attr('disabled', 'disabled');
 	$("#chk_0").removeAttr('disabled');

 	$("#txtdes_0").bind('blur keyup keydown keydown',function(event) {
 		$("#des_0").val($(this).val());
 	});

 	$("#txtdes_1").bind('blur keyup keydown keydown',function(event) {
 		$("#des_1").val($(this).val());
 	});

 	$("#txtdes_2").bind('blur keyup keydown keydown',function(event) {
 		$("#des_2").val($(this).val());
 	});

 	$("#txtdes_3").bind('blur keyup keydown keydown',function(event) {
 		$("#des_3").val($(this).val());
 	});

 	$("#txtdes_4").bind('blur keyup keydown keydown',function(event) {
 		$("#des_4").val($(this).val());
 	});


 	$(".chk").click(function() {
 		var ti=parseInt($(this).attr('id').split('_')[1]);
 		clear_af_me(ti);
 	});

 	$("#mnu_no").keydown(function(e){
 		if(e.keyCode == 13){
				// $("#mnu_no_hid").val($("#mnu_no").val());
				load_mnu_itms($("#mnu_no").val());
				
			}
		});

 	$(".sel").keydown(function(e){
 		set_cid($(this).attr('id'));

 		if(e.keyCode == 112){

 			if ($("#chk_"+scid).is(':checked')) {
				// $("#pop_search_prent_mod").val($(this).val());
				load_prent_mod();
				$("#serch_pop_prent_mod").center_mnu_pop();
				$("#blocker_pop_mnu").css("display", "block");
				setTimeout("$('#pop_search_prent_mod').focus()", 100);	
			}else{
				// $("#pop_search_all_mod").val($(this).val());
				load_all_mod();
				$("#serch_pop_all_mod").center_mnu_pop();
				$("#blocker_pop_mnu").css("display", "block");
				setTimeout("$('#pop_search_all_mod').focus()", 100);				
			}


		}
		$("#pop_search_all_mod").keyup(function(e){
			if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
				if ($("#chk_"+scid).is(':checked')) {
					load_prent_mod();
				}else{
					load_all_mod();
				}	
			}
		}); 
		if(e.keyCode == 46){
			clear_af_me(scid);
		}
	});


 });

 function set_cid(id){
 	id = id.split('_');
 	fcid = id[0];
 	scid = id[1];
 }

 $.fn.center_mnu_pop = function(){
 	var h = this.height();
 	var w = this.width();

 	h = (h - $(window).height())/2;
 	w = (w - $(window).width())/2;

 	if(h<0){ h = h*-1; }
 	if(w<0){ w = w*-1; }

 	this.css("top", h);
 	this.css("left", w);
 	this.css("display", "block");
 }



 function clear_af_me(thid){

 	for (var i =thid ; i < $(".chk").length; i++) {
 		var x=i+1;
 		$("#model_id").val('');
 		$("#sel_"+i).val('');			
 		$("#sel_"+x).val('').attr('disabled', 'disabled');			
 		$("#txtmod_"+i).val('');			
 		$("#txtmod_"+x).val('').attr('disabled', 'disabled');			
 		$("#txtdes_"+i).val('');			
 		$("#txtdes_"+x).val('').attr('disabled', 'disabled');			
 		$("#no_"+i).val('');			
 		$("#mod_"+i).val('');			
 		$("#des_"+i).val('');	
 		$("#chk_"+x).removeAttr('checked');
 		$("#chk_"+x).attr('disabled', 'disabled');

 	}

 	if ($("#chk_"+thid).is(':checked')) {
 		thid++;
 		$("#chk_"+thid).removeAttr('checked');
 		$("#sel_"+thid).val('').removeAttr('disabled');			
 		$("#txtmod_"+thid).val('').removeAttr('disabled');			
 		$("#txtdes_"+thid).val('').removeAttr('disabled');	
 		if (thid!=4) {
 			$("#chk_"+thid).removeAttr('disabled');
 		}
 	}

 }

 function load_all_mod(){
 	$.post("index.php/main/load_data/s_menu_add/f1_load_all_mod", {
 		search : $("#pop_search_all_mod").val(),
 		no_0:$("#no_0").val(),
 		no_1:$("#no_1").val(),
 		no_2:$("#no_2").val(),
 		no_3:$("#no_3").val(),
 		no_4:$("#no_4").val(),
 		no:scid
 	}, 
 	function(r){
 		$("#sr_all_mod").html(r);
 		settings_all_mod();            
 	}, "text");
 }

 function settings_all_mod(){
 	$("#item_list .cl").click(function(){        
 		$("#sel_"+scid).val($(this).children().eq(0).html());
 		$("#no_"+scid).val($(this).children().eq(3).html());
 		$("#model_id").val($(this).children().eq(0).html());		
 		$("#txtmod_"+scid).val($(this).children().eq(1).html());
 		$("#mod_"+scid).val($(this).children().eq(1).html());
 		$("#txtdes_"+scid).val($(this).children().eq(2).html());
 		$("#des_"+scid).val($(this).children().eq(2).html());
 		$("#pop_close_all_mod").click();                
 	})    

 }


 function load_prent_mod(){
 	$.post("index.php/main/load_data/s_menu_add/f1_load_prent_mod", {
 		search : $("#pop_search_prent_mod").val() ,
 		no_0:$("#no_0").val(),
 		no_1:$("#no_1").val(),
 		no_2:$("#no_2").val(),
 		no_3:$("#no_3").val(),
 		no_4:$("#no_4").val(),
 		no:scid
 	}, 
 	function(r){
 		$("#sr_prent_mod").html(r);
 		settings_prent_mod();            
 	}, "text");
 }

 function settings_prent_mod(){
 	$("#item_list .cl").click(function(){        
 		$("#sel_"+scid).val($(this).children().eq(0).html());
 		$("#no_"+scid).val($(this).children().eq(0).html());
 		$("#txtmod_"+scid).val("non");
 		$("#mod_"+scid).val("non");
 		$("#txtdes_"+scid).val($(this).children().eq(1).html());
 		$("#des_"+scid).val($(this).children().eq(1).html());
 		$("#pop_close_prent_mod").click();                
 	})    

 }

 function validate(){
 	if ($("#model_id").val()=="") {alert("Please end menu item");return false;}
 	return true;

 }
 function save(){
 	var frm = $('#form_');
 	loding();
 	$.ajax({
 		type: frm.attr('method'),
 		url: frm.attr('action'),
 		data: frm.serialize(),
 		success: function (pid){
 			if(pid == 'S'){ 
 				alert("Successfully Saved")
 				location.href="";
 			}else if(pid == "U"){
 				alert("Successfully Updated")
 				location.href="";
 			}else{
 				alert("Error No : "+pid);
 				location.href="";
 			}

 		}
 	});
 }

 function load_mnu_itms(thid){  
 	$.post("index.php/main/load_data/s_menu_add/load_mnu_itms", {
 		thid : thid
 	}, 
 	function(r){

 		if (r=='er') { 
 			alert("No Data");
 			return;
 		}


 		if (r.qr['s1_no']!='0') {
 			$('#chk_0').attr('checked', 'checked');
 			clear_af_me(0);
 		}
 		if (r.qr['s2_no']!='0') {
 			$('#chk_1').attr('checked', 'checked');
 			clear_af_me(1);
 		}    	
 		if (r.qr['s3_no']!='0') {
 			$('#chk_2').attr('checked', 'checked');
 			clear_af_me(2);
 		} 
 		if (r.qr['s4_no']!='0') {
 			$('#chk_3').attr('checked', 'checked');
 			clear_af_me(3);
 		} 
 		if (r.qr['m_no']!='0') {
 			$('#no_0').val(r.qr['m_no']); 
 			$('#sel_0').val(r.qr['m_no']); 
 		}
 		if (r.qr['s1_no']!='0') {
 			$('#no_1').val(r.qr['s1_no']); 
 			$('#sel_1').val(r.qr['s1_no']); 
 		}
 		if (r.qr['s2_no']!='0') {
 			$('#no_2').val(r.qr['s2_no']); 
 			$('#sel_2').val(r.qr['s2_no']); 
 		}    	
 		if (r.qr['s3_no']!='0') {
 			$('#no_3').val(r.qr['s3_no']); 
 			$('#sel_3').val(r.qr['s3_no']); 
 		} 
 		if (r.qr['s4_no']!='0') {
 			$('#no_4').val(r.qr['s4_no']); 
 			$('#sel_4').val(r.qr['s4_no']); 
 		} 

 		$('#model_id').val(r.qr['model_id']); 
 		$('#mnu_no_hid').val(r.qr['order_no']); 			
 		$('#mod_0').val(r.qr['main_mod']); 			
 		$('#des_0').val(r.qr['main_des']); 

 		$('#mod_1').val(r.qr['sub1_mod']); 
 		$('#des_1').val(r.qr['sub1_des']); 

 		$('#mod_2').val(r.qr['sub2_mod']); 
 		$('#des_2').val(r.qr['sub2_des']); 

 		$('#mod_3').val(r.qr['sub3_mod']); 
 		$('#des_3').val(r.qr['sub3_des']); 

 		$('#mod_4').val(r.qr['sub4_mod']); 
 		$('#des_4').val(r.qr['sub4_des']); 
 		$('#txtSetIco').val(r.qr['icon']);

 		$("#btnSetIco").removeAttr('class');		
 		$("#btnSetIco").addClass(r.qr['icon']);


 		$('#txtmod_0').val(r.qr['main_mod'].replace("' target='_blank","")); 		
 		$('#txtmod_1').val(r.qr['sub1_mod'].replace("' target='_blank","")); 
 		$('#txtmod_2').val(r.qr['sub2_mod'].replace("' target='_blank","")); 
 		$('#txtmod_3').val(r.qr['sub3_mod'].replace("' target='_blank","")); 
 		$('#txtmod_4').val(r.qr['sub4_mod'].replace("' target='_blank","")); 

 		$('#txtdes_0').val(r.qr['main_des']); 
 		$('#txtdes_1').val(r.qr['sub1_des']); 
 		$('#txtdes_2').val(r.qr['sub2_des']); 
 		$('#txtdes_3').val(r.qr['sub3_des']); 
 		$('#txtdes_4').val(r.qr['sub4_des']); 

 		$("#mnu_no").attr('readonly', 'readonly');
 		$("#txtUpOdr").removeAttr('disabled');
 		$("#btnUpOdr").removeAttr('disabled');

 		$('#minOdr').val(r.min); 
 		$('#maxOdr').val(r.max); 
 		$('#txtUpOdr').val(r.qr['order_no']); 				

 		$("#btnSave_mnu").val("Update <F8>");


 		$("#is_right").removeAttr('checked')      
 		if (r.qr['is_right']==1) {
 			$("#is_right").attr('checked', 'checked');     
 		}

 		$('#mod_0').val(); 			
 		$('#des_0').val(r.qr['main_des']); 

 		$('#mod_1').val(r.qr['sub1_mod']); 


 		$("#OpInNwTab").removeAttr('checked')      
 		var str = r.qr['main_mod']+" "+r.qr['sub1_mod']+" "+r.qr['sub2_mod']+" "+r.qr['sub3_mod']+" "+r.qr['sub4_mod'];
 		if(str.indexOf("' target='_blank") != -1){
 			$("#OpInNwTab").attr('checked', 'checked');     

 		}
	

 	}, "json");
 }  


 function delete_mnu_itms(){  
 	loding();
 	$.post("index.php/main/load_data/s_menu_add/delete_mnu_itms", {
 		id : $("#mnu_no_hid").val()
 	}, 
 	function(r){
 		alert("Successfully Deleted");
 		location.href="";

 	}, "text");
 } 

 function update_order(){  
 	loding();
 	$.post("index.php/main/load_data/s_menu_add/update_order", {
 		id : $("#txtUpOdr").val(),
 		mnu: $("#mnu_no_hid").val()
 	}, 
 	function(r){
 		alert("Successfully Update Order");
 		location.href="";

 	}, "text");
 } 
