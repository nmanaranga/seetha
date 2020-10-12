<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class s_menu_add extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->sd = $this->session->all_userdata();
		// $this->load->database($this->sd['db']);
	}

	public function base_details(){
		$a['menu']=$this->view_menu();
		$a['mnu_mx']=$this->mnu_mx();	
		$a['isSupAdmin']=($this->sd['oc']=='0' && $this->sd['name']=='SuperAdmin');	
		return $a;
	}


	public function mnu_mx(){
		$this->db->select_max('order_no');
		return ($this->db->get('def_menu')->first_row()->order_no+1);


	}

	public function save(){

		$cMnu = array(
			'model_id' 	=> trim($_POST['model_id']), 
			// 'order_no' 	=> trim($_POST['mnu_no_hid']), 			
			'm_no' 		=> trim($_POST['no_0']), 
			'main_mod' 	=> trim($_POST['mod_0']), 			
			'main_des' 	=> ucwords(strtolower(trim($_POST['des_0']))), 
			's1_no' 	=> trim($_POST['no_1']), 
			'sub1_mod' 	=> trim($_POST['mod_1']), 
			'sub1_des' 	=> ucwords(strtolower(trim($_POST['des_1']))), 
			's2_no' 	=> trim($_POST['no_2']), 
			'sub2_mod' 	=> trim($_POST['mod_2']), 
			'sub2_des' 	=> ucwords(strtolower(trim($_POST['des_2']))), 
			's3_no' 	=> trim($_POST['no_3']), 
			'sub3_mod' 	=> trim($_POST['mod_3']), 
			'sub3_des' 	=> ucwords(strtolower(trim($_POST['des_3']))), 
			's4_no' 	=> trim($_POST['no_4']), 
			'sub4_mod' 	=> trim($_POST['mod_4']), 
			'sub4_des' 	=> ucwords(strtolower(trim($_POST['des_4']))), 
			'icon' 		=> trim($_POST['txtSetIco']),
			'is_right' 	=> (isset($_POST['is_right'])?1:0),			
			);


		if ($this->is_save_mnu($_POST['mnu_no_hid'])) {
			$cMnu['order_no']=$this->mnu_mx();
			$this->db->insert('def_menu', $cMnu);
			echo "S";
		}else{
			$this->db->where('order_no', $_POST['mnu_no_hid']);
			$this->db->update('def_menu', $cMnu);
			echo "U";
		}
		

		// if ($this->chk_is_exst()) {
		// }

		


		$this->load->model('s_menu');
		$oc=(isset($this->sd['oc']))?$this->sd['oc']:'no';
		$menu= preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $this->s_menu->load_menu($oc)));;

		$session_data = array(
			"menu"=>$menu
			);

		$this->session->set_userdata($session_data);

		
		
	}

	public function is_save_mnu($mnNo){	
		$this->db->where('order_no', $mnNo);
		$qry=$this->db->get('def_menu');

		if ($qry->num_rows()>0) {
			return false;
		}else{
			return true;			
		}
	}

	public function load_mnu_itms(){	
		$this->db->where('order_no', $_POST['thid']);
		$qry=$this->db->get('def_menu');
		$qr=$qry->first_row();
		$a['qr']=$qr;
// var_dump($qr->m_no);exit();
		$wr=array();
		if (!empty($qr->m_no)) {
		}
		if (!empty($qr->s1_no)) {
			$wr['m_no']=$qr->m_no;
		}
		if (!empty($qr->s2_no)) {
			$wr['s1_no']=$qr->s1_no;			
		}
		if (!empty($qr->s3_no)) {
			$wr['s2_no']=$qr->s2_no;	
		}
		if (!empty($qr->s4_no)) {
			$wr['s3_no']=$qr->s3_no;	
			// $wr['s4_no']=$qr->s4_no;	
		}


		$this->db->where($wr);
		$this->db->select_max('order_no');
		$qrM=$this->db->get('def_menu');
		$a['max']=$qrM->first_row()->order_no;		

		$this->db->where($wr);
		$this->db->select_min('order_no');
		$qrM=$this->db->get('def_menu');
		$a['min']=$qrM->first_row()->order_no;	

		if ($qry->num_rows()>0) {
			echo json_encode($a);
		}else{
			echo "er";
		}
		
	}

	public function delete_mnu_itms(){	
		$this->db->where('order_no', $_POST['id']);
		$this->db->delete('def_menu');
		echo "D";
	}

	public function update_order(){	
//update menu order no to '0'
		$this->db->where('order_no', (intval($_POST['mnu'])));
		$this->db->set('order_no', '0', FALSE);
		$this->db->update('def_menu');	

//decrees order no 1 for get mising record no 
		$this->db->where('order_no >=', $_POST['mnu']);
		$this->db->set('order_no', 'order_no-1', FALSE);
		$this->db->update('def_menu');

//increes order no 1 for get blank spece to '0' to record
		$this->db->where('order_no >=', $_POST['id']);
		$this->db->set('order_no', 'order_no+1', FALSE);
		$this->db->update('def_menu');

//update '0' record as id no
		$this->db->where('order_no', '0');
		$this->db->set('order_no', $_POST['id'], FALSE);
		$this->db->update('def_menu');	
		
		echo "UO";
	}


	public function chk_is_exst(){
		$cMnu4 = array(
			'm_no' 		=> trim($_POST['no_0']), 
			's1_no' 	=> trim($_POST['no_1']), 
			's2_no' 	=> trim($_POST['no_2']), 
			's3_no' 	=> trim($_POST['no_3']), 
			's4_no' 	=> trim($_POST['no_4']), 
			);
		$cMnu3 = array(
			'm_no' 		=> trim($_POST['no_0']), 
			's1_no' 	=> trim($_POST['no_1']), 
			's2_no' 	=> trim($_POST['no_2']), 
			's3_no' 	=> trim($_POST['no_3']), 
			);
		$cMnu2 = array(
			'm_no' 		=> trim($_POST['no_0']), 
			's1_no' 	=> trim($_POST['no_1']), 
			's2_no' 	=> trim($_POST['no_2']), 
			);
		$cMnu1 = array(
			'm_no' 		=> trim($_POST['no_0']), 
			's1_no' 	=> trim($_POST['no_1']), 
			);
		$cMnu0 = array(
			'm_no' 		=> trim($_POST['no_0']), 
			);


		$this->db->where($cMnu4);	
		$this->db->where('s4_no !=', 0);		
		$this->db->group_by('main_des, sub1_des, sub2_des, sub3_des, sub4_des');
		$res4=$this->db->get('def_menu');

		$this->db->where($cMnu3);	
		$this->db->where('s3_no !=', 0);		
		$this->db->group_by('main_des, sub1_des, sub2_des, sub3_des');
		$res3=$this->db->get('def_menu');

		$this->db->where($cMnu2);	
		$this->db->where('s2_no !=', 0);		
		$this->db->group_by('main_des, sub1_des, sub2_des');
		$res2=$this->db->get('def_menu');			

		$this->db->where($cMnu1);	
		$this->db->where('s1_no !=', 0);		
		$this->db->group_by('main_des, sub1_des');
		$res1=$this->db->get('def_menu');

		$this->db->where($cMnu0);			
		$this->db->group_by('main_des');
		$res0=$this->db->get('def_menu');

// exit();
		if ($res4->num_rows()>0) {
			$this->db->where('s4_no >=', trim($_POST['no_4']));
			$this->db->set('s4_no', 's4_no+1', FALSE);
			$this->db->update('def_menu');
			echo "4";
			// $this->chk_is_exst();
		}else if ($res3->num_rows()>0) {
			$this->db->where('s3_no >=', trim($_POST['no_3']));
			$this->db->set('s3_no', 's3_no+1', FALSE);
			$this->db->update('def_menu');
			echo "3";
			// $this->chk_is_exst();
		}else if ($res2->num_rows()>0) {
			$this->db->where('s2_no >=', trim($_POST['no_2']));
			$this->db->set('s2_no', 's2_no+1', FALSE);
			$this->db->update('def_menu');
			echo "2";
			// $this->chk_is_exst();
		}else if ($res1->num_rows()>0) {
			$this->db->where('s1_no >=', trim($_POST['no_1']));
			$this->db->set('s1_no', 's1_no+1', FALSE);
			$this->db->update('def_menu');
			echo "1";
			// $this->chk_is_exst();
		}else if ($res0->num_rows()>0) {
			$this->db->where('m_no >=', trim($_POST['no_0']));
			$this->db->set('m_no', 'm_no+1', FALSE);
			$this->db->update('def_menu');	
			echo "0";
			// $this->chk_is_exst();		
		}
		
		return true;
		
		
	}

	public function f1_load_all_mod(){
		if (isset($_POST['search'])) {
			if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
			$match=$_POST['search'];	
		}else{
			$_POST['search'] = "";
		}


		// $this->db->select('m_code,m_description');
		$this->db->like('module_name', $match);
		$this->db->or_like('m_code', $match); 
		$this->db->or_like('m_description', $match); 
		$m_res=$this->db->get('u_modules');

		if ($_POST['no']==0) {
			$jenRes=$this->db->select_max('m_no')
			->get('def_menu')->first_row()->m_no+1;

		}else if ($_POST['no']==1) {
			$jenRes=$this->db->select_max('s1_no')
			->where("m_no",$_POST['no_0'])
			->get('def_menu')->first_row()->s1_no+1;

		}else if ($_POST['no']==2) {
			$jenRes=$this->db->select_max('s2_no')
			->where("m_no",$_POST['no_0'])
			->where("s1_no",$_POST['no_1'])
			->get('def_menu')->first_row()->s2_no+1;

		}else if ($_POST['no']==3) {
			$jenRes=$this->db->select_max('s3_no')
			->where("m_no",$_POST['no_0'])
			->where("s1_no",$_POST['no_1'])
			->where("s2_no",$_POST['no_2'])
			->get('def_menu')->first_row()->s3_no+1;

		}else if ($_POST['no']==4) {
			$jenRes=$this->db->select_max('s4_no')
			->where("m_no",$_POST['no_0'])
			->where("s1_no",$_POST['no_1'])
			->where("s2_no",$_POST['no_2'])
			->where("s3_no",$_POST['no_3'])
			->get('def_menu')->first_row()->s4_no+1;
		}


		if ($m_res->num_rows()>0) {
			$a  = "<table id='item_list' style='width : 100% ' >";
			$a .= "<thead><tr>";
			$a .= "<th class='tb_head_th'>Code</th>";
			$a .= "<th class='tb_head_th'>Module Id</th>";
			$a .= "<th class='tb_head_th'>Module Description</th>";
			$a .= "</tr></thead><tr class='cl' style='display: none;'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			foreach ($m_res->result() as $r) {
				$a .= "<tr class='cl'>";
				$a .= "<td>".$r->m_code."</td>";
				// $a .= "<td>".$r->module_name."</td>";
				$a .= "<td>".(isset($r->module_name)?$r->module_name:'Menu Name')."</td>";
				$a .= "<td>".$r->m_description."</td>";
				$a .= "<td style='display:none;'>".$jenRes."</td>";				
				$a .= "</tr>";
			}
			$a .= "</table>";
			echo $a;
		}
	}

	public function f1_load_prent_mod(){
		// if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
		// $match=$_POST['search'];

		if ($_POST['no']==0) {
			$this->db->select('m_no i_no,main_mod i_mod,main_des i_description');
			$this->db->where('main_mod', 'non');
			$this->db->group_by('m_no');
			$m_res=$this->db->get('def_menu');

		}else if ($_POST['no']==1) {
			$this->db->select('s1_no i_no,sub1_mod i_mod,sub1_des i_description');
			$this->db->where('m_no', $_POST['no_0']);
			$this->db->where('sub1_mod', 'non');
			$this->db->where('s1_no !=', 0);			
			$this->db->group_by('m_no, s1_no');
			$m_res=$this->db->get('def_menu');

		}else if ($_POST['no']==2) {
			$this->db->select('s2_no i_no,sub2_mod i_mod,sub2_des i_description');
			$this->db->where('m_no', $_POST['no_0']);
			$this->db->where('s1_no', $_POST['no_1']);
			$this->db->where('sub2_mod', 'non');
			$this->db->where('s2_no !=', 0);			
			$this->db->group_by('m_no, s1_no, s2_no');
			$m_res=$this->db->get('def_menu');

		}else if ($_POST['no']==3) {
			$this->db->select('s3_no i_no,sub3_mod i_mod,sub3_des i_description');
			$this->db->where('m_no', $_POST['no_0']);
			$this->db->where('s1_no', $_POST['no_1']);
			$this->db->where('s2_no', $_POST['no_2']);
			$this->db->where('sub3_mod', 'non');
			$this->db->where('s4_no !=', 0);			
			$this->db->group_by('m_no, s1_no, s2_no, s3_no');
			$m_res=$this->db->get('def_menu');
		}


		if ($_POST['no']==0) {
			$jenRes=$this->db->select_max('m_no')
			->get('def_menu')->first_row()->m_no+1;

		}else if ($_POST['no']==1) {
			$jenRes=$this->db->select_max('s1_no')
			->where("m_no",$_POST['no_0'])
			->get('def_menu')->first_row()->s1_no+1;

		}else if ($_POST['no']==2) {
			$jenRes=$this->db->select_max('s2_no')
			->where("m_no",$_POST['no_0'])
			->where("s1_no",$_POST['no_1'])
			->get('def_menu')->first_row()->s2_no+1;

		}else if ($_POST['no']==3) {
			$jenRes=$this->db->select_max('s3_no')
			->where("m_no",$_POST['no_0'])
			->where("s1_no",$_POST['no_1'])
			->where("s2_no",$_POST['no_2'])
			->get('def_menu')->first_row()->s3_no+1;

		}else if ($_POST['no']==4) {
			$jenRes=$this->db->select_max('s4_no')
			->where("m_no",$_POST['no_0'])
			->where("s1_no",$_POST['no_1'])
			->where("s2_no",$_POST['no_2'])
			->where("s3_no",$_POST['no_3'])
			->get('def_menu')->first_row()->s4_no+1;
		}




		$a  = "<table id='item_list' style='width : 100% ' >";
		$a .= "<thead><tr>";
		$a .= "<th class='tb_head_th'>Item No</th>";
		$a .= "<th class='tb_head_th'>Module Description</th>";
		$a .= "</tr></thead><tr class='cl' style='display: none;'><td>&nbsp;</td><td>&nbsp;</td></tr>";
		$a .= "<tr class='cl'>";
		$a .= "<td>".$jenRes."</td>";
		$a .= "<td>New Parent</td>";
		$a .= "</tr>";	
		if ($m_res->num_rows()>0) {					
			foreach ($m_res->result() as $r) {
				$a .= "<tr class='cl'>";
				$a .= "<td>".$r->i_no."</td>";
				$a .= "<td>".$r->i_description."</td>";
				$a .= "</tr>";
			}
		}			
		$a .= "</table>";
		echo $a;

		
	}




	public function view_menu()
	{
		$menu='<ol>';


		$this->db->select('m_no,main_mod,main_des,order_no');
		$this->db->group_by('m_no');
		$m_res=$this->db->get('def_menu');

		if ($m_res->num_rows()) {
			foreach ($m_res->result() as $m_key => $m_value) {

				if ($m_value->main_mod!="non") {
					$menu.= "<li><a href='?action=".$m_value->main_mod."'>".$m_value->main_des." (".$m_value->order_no.")</a></li>";
					continue;
				}

				$menu.= "<li>".$m_value->main_des."<ol>";


				$this->db->select('s1_no,sub1_mod,sub1_des,order_no');
				$this->db->where('m_no', $m_value->m_no);
				$this->db->where('s1_no !=', 0);
				$this->db->group_by('m_no,s1_no');
				$this->db->order_by('order_no', 'asc');
				$s1_res=$this->db->get('def_menu');

				if ($s1_res->num_rows()) {
					foreach ($s1_res->result() as $s1_key => $s1_value) {

						if ($s1_value->sub1_mod=="dvid") {
							$menu.= "<li></li>";
							continue;
						}

						if ($s1_value->sub1_mod!="non") {
							$menu.= "<li><a href='?action=".$s1_value->sub1_mod."'>".$s1_value->sub1_des." (".$s1_value->order_no.")</a></li>";
							continue;
						}

						$menu.= "<li><a tabindex='0'>".$s1_value->sub1_des."</a>
						<ol>";



							$this->db->select('s2_no,sub2_mod,sub2_des,order_no');
							$this->db->where('m_no', $m_value->m_no);
							$this->db->where('s1_no', $s1_value->s1_no);
							$this->db->where('s2_no !=', 0);
							$this->db->group_by('m_no,s1_no,s2_no');
							$this->db->order_by('order_no', 'asc');
							$s2_res=$this->db->get('def_menu');

							if ($s2_res->num_rows()) {
								foreach ($s2_res->result() as $s2_key => $s2_value) {

									if ($s2_value->sub2_mod=="dvid") {
										$menu.= "<li></li>";
										continue;
									}

									if ($s2_value->sub2_mod!="non") {
										$menu.= "<li><a href='?action=".$s2_value->sub2_mod."'>".$s2_value->sub2_des." (".$s2_value->order_no.")</a></li>";
										continue;
									}

									$menu.= "<li><a tabindex='0'>".$s2_value->sub2_des."</a>
									<ol>";

										$this->db->select('s3_no,sub3_mod,sub3_des,order_no');
										$this->db->where('m_no', $m_value->m_no);
										$this->db->where('s1_no', $s1_value->s1_no);
										$this->db->where('s2_no', $s2_value->s2_no);
										$this->db->where('s3_no !=', 0);
										$this->db->group_by('m_no,s1_no,s2_no,s3_no');
										$this->db->order_by('order_no', 'asc');
										$s3_res=$this->db->get('def_menu');

										if ($s3_res->num_rows()) {
											foreach ($s3_res->result() as $s3_key => $s3_value) {

												if ($s3_value->sub3_mod=="dvid") {
													$menu.= "<li></li>";
													continue;
												}

												if ($s3_value->sub3_mod!="non") {
													$menu.= "<li><a href='?action=".$s3_value->sub3_mod."'>".$s3_value->sub3_des." (".$s3_value->order_no.")</a></li>";
													continue;
												}

												$menu.= "<li><a tabindex='0'>".$s3_value->sub3_des."</a>
												<ol>";



													$this->db->select('s4_no,sub4_mod,sub4_des,order_no');
													$this->db->where('m_no', $m_value->m_no);
													$this->db->where('s1_no', $s1_value->s1_no);
													$this->db->where('s2_no', $s2_value->s2_no);
													$this->db->where('s3_no', $s3_value->s3_no);
													$this->db->where('s4_no !=', 0);
													$this->db->group_by('m_no,s1_no,s2_no,s3_no,s4_no');
													$s4_res=$this->db->get('def_menu');

													if ($s4_res->num_rows()) {
														foreach ($s4_res->result() as $s4_key => $s4_value) {

															if ($s4_value->sub4_mod=="dvid") {
																$menu.= "<li></li>";
																continue;
															}

															if ($s4_value->sub4_mod!="non") {
																$menu.= "<li><a href='?action=".$s4_value->sub4_mod."'>".$s4_value->sub4_des." (".$s4_value->order_no.")</a></li>";
																continue;
															}

																// $menu.= "<li><a tabindex='0'>".$s3_value->sub3_des."</a>
																// <ol>";


																// 	$menu.= "</ol>";
																// 	$menu.= "</li>";
														}

													}

													$menu.= "</ol>";
													$menu.= "</li>";
												}

											}

											$menu.= "</ol>";
											$menu.= "</li>";
										}

									}

									$menu.= "</ol>";
									$menu.= "</li>";
								}

							}

							$menu.= "</ol>";
							$menu.= "</li>";
						}

					}

					return $menu;
				}



			}



			/* End of file s_menu_add.php */
/* Location: .//D/xampp/htdocs/wtc/app/models/s_menu_add.php */