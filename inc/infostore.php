<?php
	// forwading info
	$forwading = array(
		'export_vsl_details' => "SHIP Details",
		'mainfilecover' => "File Cover Format",
		'accfilecover' => "ACCOUNTS FILE COVER PAGE",
		'finalentryexport' => "13.FINAL ENTRY (4 COPY)",
		'pcforwadingexport' => "28.PC FORWARDING_NEW",
		'pcstampexport' => "28.Stamp_PC Undertaking to Customs_New",
		'inctaxforwading' => "29.INCOME TAX FORWARDING",
		'mmdforwading' => "MMD FORWADING",
		'inctaxstamp' => "29.Stamp_Income_TAX",
		'pcformet' => "PC-FORMAT",
		'prepartique' => "1.PREPARTIQUE PORT HEALTH 1 copy",
		'vsl_declearation' => "2.VSL DECLARATION TO DTM 2 copy",
		'portigm' => "3.PORT IGM WITH FORWARDING 2 copy",
		'plantq' => "4. PLANT QUARENTINE WITH (1 IGM+ANCHORE PER.) 2 copy",
		'po_booking' => "5.P.O BOOKING TO CUSTOMS 3 copy",
		'survey_booking' => "6.APPILICATION OF SURVEYOR BOOKING 1 copy",
		'survey_booking_bangla' => "6......NEW 8copy",
		'port_health' => "20.PORT HEALTH",
		'psc_submission' => "21.PHC SUBMISSION LETTER",
		'egm_forwading' => "22.EGM FOWARDING (NIL COPY-3)",
		'egm_format' => "23.EGM_format",
		'arrival_perticular' => "24.Arrival Particulars",
		'ship_required_docs' => "26.Ship Docs Required",
		'representative_letter' => "9.REPRESENTATIVE FORWARDING",
		'lightdues' => "15.LIGHT DUES",
		'lightdues2nd' => "15.LIGHT DUES-EXTENTION-2nd",
		'vataitchalan15' => "15% CHALAN",
		'vataitchalan10' => "10% CHALAN",
		'watchman_letter' => "10.WATCHMAN FORWARDING",
		'vendor_letter' => "8.SUPPLIER APPLICATION",
		'stevedorebooking' => "7.STEAVATOR BOOKING",
		'portbillcollect' => "PORT BILL COLLECTION FORWARDING",
		'cargo_declearation' => "MMD_CARGO_DECLARATION",
		'softemplet' => "SOF",
		'igmfullcargo' => "GENERAL SEGMENT"
	);

	// user info
	// logic should be "isset($_SESSION['id'])" so none could update profile except his
	if (isset($_GET['userid']) && !empty($_GET['userid'])) { 
		$id = $_GET['userid']; 
		$run = mysqli_query($db,"SELECT * FROM users WHERE id = '$id' ");
		if (mysqli_num_rows($run) > 0) {
			$viewuser = mysqli_fetch_assoc($run);
			$user = array(
				'id' => $id,
				'name' => $viewuser['name'],
				'office_position' => $viewuser['office_position'],
				'contact' => $viewuser['contact'],
				'email' => $viewuser['email'],
				'image' => $viewuser['image'],
				'balance' => $viewuser['balance'],
				'password' => $viewuser['password']
			);
		}
		else{$user = array( 'id' => '', 'name' => '', 'contact' => '', 'email' => '', 'image' => '', 'balance' => '', 'password' => '');}	
	}else{ $user = array( 'id' => '', 'name' => '', 'contact' => '', 'email' => '', 'image' => '', 'balance' => '', 'password' => ''); }

	// my info
	if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { 
		$id = $_SESSION['id']; 
		$rusr = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM users WHERE id = '$id' "));

		$my = array(
			'id' => $id,
			'companyid' => $rusr['companyid'],
			'name' => $rusr['name'],
			'office_position' => $rusr['office_position'],
			'contact' => $rusr['contact'],
			'email' => $rusr['email'],
			'image' => $rusr['image'],
			'balance' => $rusr['balance'],
			'password' => $rusr['password']
		);

		if ($my['companyid'] != 0) {
			// companydata
			$companyid = $rusr['companyid'];
			$rcom = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM companies WHERE id = '$companyid' "));
			$company = array(
				'id' => $companyid,
				'adminid' => $rcom['adminid'],
				'ain' => $rcom['companyain'],
				'companyname' => $rcom['companyname'],
				'companymoto' => $rcom['companymoto'],
				'email' => $rcom['email'],
				'telephone' => $rcom['telephone'],
				'address' => $rcom['address'],
				'port' => $rcom['port'],
				'templet' => $rcom['templet'],
				'package' => $rcom['package'],
				'timereset' => $rcom['timereset'],
				'birthday' => $rcom['birthday'],
				'status' => $rcom['status']
			);
		}else{
			$company = array( 'companyid' => '', 'companyadmin' => '', 'ain' => '', 'companyname' => '', 'companymoto' => '', 'email' => '', 'telephone' => '', 'address' => '', 'port' => '', 'templet' => '', 'package' => '', 'timereset' => '', 'birthday' => '', 'status' => ''); 
		}
	}else{ 
		$my = array( 'id' => '', 'name' => '', 'contact' => '', 'email' => '', 'image' => '', 'balance' => '', 'password' => '', 'companyid' => '', 'companyadmin' => '', 'companyname' => '', 'companymoto' => '', 'companytemplet' => '', 'companypackage' => '', 'companytimereset' => '', 'companybirthday' => '', 'companystatus' => ''); 

		$company = array( 'companyid' => '', 'companyadmin' => '', 'ain' => '', 'companyname' => '', 'companymoto' => '', 'email' => '', 'telephone' => '', 'address' => '', 'templet' => '', 'package' => '', 'timereset' => '', 'birthday' => '', 'status' => ''); 
	}

	// vessel info
	if(isset($_GET['vsl_num'])&& !empty($_GET['vsl_num'])||isset($_GET['edit'])&& !empty($_GET['edit'])||isset($_GET['forwadingpage'])&& !empty($_GET['forwadingpage'])||isset($_GET['ship_perticular'])&& !empty($_GET['ship_perticular'])||isset($_GET['igminputs'])&& !empty($_GET['igminputs'])){ 
		// set vsl_num
		if(isset($_GET['vsl_num']) && !empty($_GET['vsl_num'])){$vsl_num=$_GET['vsl_num']; }
		elseif(isset($_GET['forwadingpage']) && !empty($_GET['forwadingpage'])){$vsl_num=$_GET['forwadingpage']; }
		elseif(isset($_GET['ship_perticular']) && !empty($_GET['ship_perticular'])){$vsl_num=$_GET['ship_perticular']; }
		elseif(isset($_GET['igminputs']) && !empty($_GET['igminputs'])){$vsl_num=$_GET['igminputs']; }
		else{$vsl_num = $_GET['edit'];}
		// extract data
		$rvsl=mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM vessels WHERE id = '$vsl_num' "));
		$row = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' "));

		$rep_id = $rvsl['representative']; 
        $rep_goodname = allData('users', $rep_id, 'goodname');
        if (empty($rep_goodname)) {$rep_goodname = allData('users', $rep_id, 'name');}
	    $rep_contact = allData('users', $rep_id, 'contact');

	    if ($row['vsl_nationality']) {
	    	$vsl_nationality = allData('nationality', $row['vsl_nationality'], 'port_name');
	    	$vsl_nationalitycode = allData('nationality', $row['vsl_nationality'], 'port_code');
	    }else{$vsl_nationalitycode = $vsl_nationality = "";}
	    if ($row['vsl_registry']) {
	    	$vsl_registry = allData('nationality', $row['vsl_registry'], 'port_name');
	    	$vsl_registrycode = allData('nationality', $row['vsl_registry'], 'port_code');
	    }else{$vsl_registrycode = $vsl_registry = "";}
	    if ($row['last_port']) {
	    	$last_port = allData('loadport', $row['last_port'], 'port_name');
	    	$last_portcode = allData('loadport', $row['last_port'], 'port_code');
	    }else{$last_port = $last_portcode = "";}
	    

		$thisvessel = array(
			// 'id' => $rvsl['msl_num'],
			'id' => $vsl_num,
			'companyid' => $rvsl['companyid'],
			'msl_num' => $rvsl['msl_num'],
			'vessel_name' => $rvsl['vessel_name'],
			'rotation' => $rvsl['rotation'],
			'arrived' => $rvsl['arrived'],
			'rcv_date' => $rvsl['rcv_date'],
			'com_date' => $rvsl['com_date'],
			'sailing_date' => $rvsl['sailing_date'],
			'stevedoreid' => $rvsl['stevedore'],
			'stevedore' => str_replace("&", "&amp;", allData('stevedore', $rvsl['stevedore'], 'name')),
			'kutubdia_qty' => $rvsl['kutubdia_qty'],
			'outer_qty' => $rvsl['outer_qty'],
			'retention_qty' => $rvsl['retention_qty'],
			'seventyeight_qty' => $rvsl['seventyeight_qty'],
			'fender_off' => $rvsl['fender_off'],
			'received_by' => $rvsl['received_by'],
			'sailed_by' => $rvsl['sailed_by'],
			'anchor' => $rvsl['anchor'],
			'rep_id' => $rep_id,
			'rep_name' => $rep_goodname,
			'rep_contact' => $rep_contact,
			'survey_consignee' => $rvsl['survey_consignee'],
			'survey_custom' => $rvsl['survey_custom'],
			'survey_supplier' => $rvsl['survey_supplier'],
			'survey_pni' => $rvsl['survey_pni'],
			'survey_chattrer' => $rvsl['survey_chattrer'],
			'survey_owner' => $rvsl['survey_owner'],
			'vsl_opa' => $rvsl['vsl_opa'],
			'custom_visited' => $rvsl['custom_visited'],
			'qurentine_visited' => $rvsl['qurentine_visited'],
			'psc_visited' => $rvsl['psc_visited'],
			'multiple_lightdues' => $rvsl['multiple_lightdues'],
			'crew_change' => $rvsl['crew_change'],
			'has_grab' => $rvsl['has_grab'],
			'fender' => $rvsl['fender'],
			'fresh_water' => $rvsl['fresh_water'],
			'piloting' => $rvsl['piloting'],
			'remarks' => $rvsl['remarks'],
			'status' => $rvsl['status'],
			'payment' => $rvsl['payment'],
			'workstatus' => $rvsl['workstatus'],

			// vessel details
			'ship_perticularId' => $row['id'],
			'imo' => $row['vsl_imo'],
	        'callsign' => $row['vsl_call_sign'],
	        'mmsi_number' => $row['vsl_mmsi_number'],
	        'class' => $row['vsl_class'],
	        'nationalityid' => $row['vsl_nationality'],
	        'nationalitycode' => $vsl_nationalitycode,
	        'nationality' => $vsl_nationality,
	        'registryid' => $row['vsl_registry'],
	        'registrycode' => $vsl_registrycode,
	        'registry' => $vsl_registry,
	        'official_number' => $row['vsl_official_number'],
	        'grt' => formatIndianNumber($row['vsl_grt']),
	        'nrt' => formatIndianNumber($row['vsl_nrt']),
	        'dead_weight' => formatIndianNumber($row['vsl_dead_weight']),
	        'rawgrt' => $row['vsl_grt'],
	        'rawnrt' => $row['vsl_nrt'],
	        'rawdead_weight' => $row['vsl_dead_weight'],
	        'breth' => $row['vsl_breth'],
	        'depth' => $row['vsl_depth'],
	        'loa' => $row['vsl_loa'],
	        // 'vsl_pni' => str_replace("&", "&amp;", $row['vsl_pni']),
	        'pni' => mysqli_real_escape_string($db, str_replace("&", "&amp;", $row['vsl_pni'])),
	        'owner_name' => str_replace("&", "&amp;", $row['vsl_owner_name']),
	        'owner_address' => str_replace("&", "&amp;", $row['vsl_owner_address']),
	        'owner_email' => $row['vsl_owner_email'],
	        'operator_name' => str_replace("&", "&amp;", $row['vsl_operator_name']),
	        'operator_address' => str_replace("&", "&amp;", $row['vsl_operator_address']),
	        'year_of_built' => str_replace("&", "&amp;", $row['year_of_built']),
	        'number_of_hatches_cranes' => str_replace("&", "&amp;", $row['number_of_hatches_cranes']),
	        'nature' => $row['vsl_nature'],
	        'cargo' => $row['vsl_cargo'],
	        'cargo_name' => $row['vsl_cargo_name'],
	        'cargo_qty' => formatIndianNumberNew(ttlcargoqty($vsl_num)),
	        // 'shipper_name' => $row['shipper_name'],
	        'shipper_name' => str_replace("&", "&amp;", $row['shipper_name']),
	        'shipper_address' => str_replace("&", "&amp;", $row['shipper_address']),
	        'lastportid' => $row['last_port'],
	        'last_port' => $last_port,
	        'last_portcode' => $last_portcode,
	        'next_port' => $row['next_port'],
	        'with_retention' => $row['with_retention'],
	        'capt_name' => $row['capt_name'],
	        'number_of_crew' => $row['number_of_crew'],
	        'packages_codes' => $row['packages_codes']
		);
	}else{
		$thisvessel = array(
			'id' => '','companyid' => '','vessel_name' => '','rotation' => '','arrived' => '','rcv_date' => '','sailing_date' => '','stevedore' => '','kutubdia_qty' => '','outer_qty' => '','retention_qty' => '','seventyeight_qty' => '','com_date' => '','fender_off' => '','received_by' => '','sailed_by' => '','anchor' => '','representative' => '','survey_consignee' => '','survey_custom' => '','survey_supplier' => '','survey_pni' => '','survey_chattrer' => '','survey_owner' => '','vsl_opa' => '','custom_visited' => '','qurentine_visited' => '','psc_visited' => '','multiple_lightdues' => '','crew_change' => '','has_grab' => '','fender' => '','fresh_water' => '','piloting' => '','remarks' => '','status' => '','payment' => '','ship_perticularId' => '','imo' => '','callsign' => '','mmsi_number' => '','class' => '','nationalityid' => '','nationalitycode' => '','nationality' => '','registryid' => '','registrycode' => '','registry' => '','official_number' => '','grt' => '','nrt' => '','rawgrt' => '','rawnrt' => '','dead_weight' => '','breth' => '','depth' => '','loa' => '','pni' => '','owner_name' => '','owner_address' => '','owner_email' => '','operator_name' => '','operator_address' => '','year_of_built' => '','number_of_hatches_cranes' => '','nature' => '','cargo' => '','cargo_name' => '','cargo_qty' => '','shipper_name' => '','shipper_address' => '','lastportid' => '','last_port' => '','next_port' => '','with_retention' => '','capt_name' => '','number_of_crew' => '','rawnrt' => '','packages_codes' => ''
		);
	}
?>