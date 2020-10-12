<?php defined('BASEPATH') OR exit('No direct script access allowed');

class s_menu extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->sd = $this->session->all_userdata();
	}

	public function base_details(){

	}


	public function load_menu($oc){
		$menu='';
		$array[]='';


		$this->db->select('module_id');
		if (!empty($oc)) {
			$this->db->where('user_id', $oc);
		}
		$this->db->where('is_view !=', 0);	
		// $this->db->where('is_special', 0);				
		$isViwMod=$this->db->get('u_user_role_permition');




		foreach($isViwMod->result() as $row)
		{
			$array[] = $row->module_id; 
		}



		$this->db->select('m_no,main_mod,main_des,is_right');
		
		$this->db->where_in('model_id',$array);
		$this->db->group_by('m_no');
		$m_res=$this->db->get('def_menu');

		if ($m_res->num_rows()) {

			foreach ($m_res->result() as $m_key => $m_value) {
				$menu.="<ul class='nav navbar-nav".(($m_value->is_right)?" navbar-right":'')."'>";

				$hassub=($m_value->main_mod=='non')?"<span class='caret'></span>":"";
				$subAct=($m_value->main_mod!='non')?"href='?action=".$m_value->main_mod."'":"data-submenu='' data-toggle='dropdown' tabindex='0'";
				


				$menu.= "<li class='dropdown'>

				<a  ".$subAct." >".$m_value->main_des.$hassub."</a>";


				$menu.= "<ul class='dropdown-menu'>";//<li class='dropdown-submenu'>
						// if ($m_value->main_mod!="non") {
						// 	$menu.= "<li class='".((!empty($m_value->icon))?$m_value->icon:"")."'><a tabindex='0' href='?action=".$m_value->main_mod."'>".$m_value->main_des."</a></li>";
						// 	continue;
						// }

				$this->db->select('s1_no,sub1_mod,sub1_des,icon');
				$this->db->where('m_no', $m_value->m_no);
				$this->db->where('s1_no !=', 0);
				$this->db->where_in('model_id',$array);
				$this->db->group_by('m_no,s1_no');
				$s1_res=$this->db->get('def_menu');

				if ($s1_res->num_rows()) {
					foreach ($s1_res->result() as $s1_key => $s1_value) {

						if ($s1_value->sub1_mod=="dvid") {
							$menu.= "<li class='divider'></li>";
							continue;
						}

						if ($s1_value->sub1_mod!="non") {
							$menu.= "<li class='".((!empty($s1_value->icon))?$s1_value->icon:"")."'><a tabindex='0' href='?action=".$s1_value->sub1_mod."'>".$s1_value->sub1_des."</a></li>";
							continue;
						}

						$menu.= "<li class='dropdown-submenu ti-angle-double-right'><a tabindex='0' class=''>".$s1_value->sub1_des."</a>
						<ul class='dropdown-menu'>";



							$this->db->select('s2_no,sub2_mod,sub2_des,icon');
							$this->db->where('m_no', $m_value->m_no);
							$this->db->where('s1_no', $s1_value->s1_no);
							$this->db->where('s2_no !=', 0);
							$this->db->where_in('model_id',$array);
							$this->db->group_by('m_no,s1_no,s2_no');
							$s2_res=$this->db->get('def_menu');

							if ($s2_res->num_rows()) {
								foreach ($s2_res->result() as $s2_key => $s2_value) {

									if ($s2_value->sub2_mod=="dvid") {
										$menu.= "<li class='divider'></li>";
										continue;
									}

									if ($s2_value->sub2_mod!="non") {
										$menu.= "<li class='".((!empty($s2_value->icon))?$s2_value->icon:"")."'><a tabindex='0' href='?action=".$s2_value->sub2_mod."'>".$s2_value->sub2_des."</a></li>";
										continue;
									}

									$menu.= "<li class='dropdown-submenu ti-angle-double-right'><a tabindex='0'>".$s2_value->sub2_des."</a>
									<ul class='dropdown-menu'>";


										$this->db->select('s3_no,sub3_mod,sub3_des,icon');
										$this->db->where('m_no', $m_value->m_no);
										$this->db->where('s1_no', $s1_value->s1_no);
										$this->db->where('s2_no', $s2_value->s2_no);
										$this->db->where('s3_no !=', 0);
										$this->db->where_in('model_id',$array);
										$this->db->group_by('m_no,s1_no,s3_no');
										$s3_res=$this->db->get('def_menu');

										if ($s3_res->num_rows()) {
											foreach ($s3_res->result() as $s3_key => $s3_value) {

												if ($s3_value->sub3_mod=="dvid") {
													$menu.= "<li class='divider'></li>";
													continue;
												}

												if ($s3_value->sub3_mod!="non") {
													$menu.= "<li class='".((!empty($s3_value->icon))?$s3_value->icon:"")."'><a tabindex='0' href='?action=".$s3_value->sub3_mod."'>".$s3_value->sub3_des."</a></li>";
													continue;
												}

												$menu.= "<li class='dropdown-submenu ti-angle-double-right'><a tabindex='0'>".$s3_value->sub3_des."</a>
												<ul class='dropdown-menu'>";


													$this->db->select('s4_no,sub4_mod,sub4_des,icon');
													$this->db->where('m_no', $m_value->m_no);
													$this->db->where('s1_no', $s1_value->s1_no);
													$this->db->where('s2_no', $s2_value->s2_no);
													$this->db->where('s3_no', $s3_value->s3_no);
													$this->db->where('s4_no !=', 0);
													$this->db->where_in('model_id',$array);
													$this->db->group_by('m_no,s1_no,s4_no');
													$s4_res=$this->db->get('def_menu');

													if ($s4_res->num_rows()) {
														foreach ($s4_res->result() as $s4_key => $s4_value) {

															if ($s4_value->sub4_mod=="dvid") {
																$menu.= "<li class='divider'></li>";
																continue;
															}

															if ($s4_value->sub4_mod!="non") {
																$menu.= "<li class='".((!empty($s4_value->icon))?$s4_value->icon:"")."'><a tabindex='0' href='?action=".$s4_value->sub4_mod."'>".$s4_value->sub4_des."</a></li>";
																continue;
															}

																// $menu.= "<li class='dropdown-submenu'><a tabindex='0'>".$s3_value->sub3_des."</a>
																// <ul class='dropdown-menu'>";


																// 	$menu.= "</ul>";
																// 	$menu.= "</li>";
														}

													}

													$menu.= "</ul>";
													$menu.= "</li>";
												}

											}

											$menu.= "</ul>";
											$menu.= "</li>";
										}

									}

									$menu.= "</ul>";
									//$menu.= "</li>";
								}

							}

							$menu.= "</ul>";
							$menu.= "</li>";
							$menu.= "</ul>";
						}

					}
					return $menu;
				}


			}

			/* End of file menu.php */
/* Location: .//D/xampp/htdocs/wtc/app/models/menu.php */