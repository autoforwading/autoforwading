<?php
	// include all functions!
	if(!isset($_SESSION)) { session_start(); }
	include_once('functions.php');
	$username = $email = $contact = $msg = ""; $errors = $success = array();
	// $msg =alertMsg('Warning Working!','danger');
     // if the register button is clicked
	if(isset($_POST['register'])) {
		$username = mysqli_real_escape_string($db, $_POST['registerUsername']);
		// $usergoodname = mysqli_real_escape_string($db, $_POST['registerUsergoodname']);
		$email = mysqli_real_escape_string($db, $_POST['registerEmail']);
		$contact = mysqli_real_escape_string($db, $_POST['registerContact']);
		// $officePosition = mysqli_real_escape_string($db, $_POST['registerPosition']);
		$password_1 = mysqli_real_escape_string($db, $_POST['registerPassword1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['registerPassword2']);

		// ensure that form fields are filled peoperly
		if(empty($username)){  $msg = alertMsg('Username is required!', 'danger'); }
		elseif(empty($email)) {  $msg = alertMsg('Email is required!', 'danger'); }
		elseif (empty($contact)) { array_push($errors,"Contact shouldn't be empty!"); }
		// elseif (empty($officePosition)) { $msg = alertMsg('Office Position Empty!', 'danger'); }
		elseif(empty($password_1)) { array_push($errors,"Password is required"); }
		elseif($password_1 != $password_2 || strlen($password_1) != strlen($password_2)) //to check if both given pass is same
		{  $msg = alertMsg('The two passwords do not match!', 'danger'); }

		//check if email already exist
		elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE email = '$email'"))>0){
			$msg =alertMsg('Email already registared!','danger');
		}
		else{
			// password check
			$p_len = 6; 
			// check email
			$e_len = 11; 
			if (strlen($password_1) < $p_len) {
				$msg = alertMsg('Password should be atleast 6 character long!', 'danger');
			}
			
			elseif(strlen($email) < $e_len || !preg_match("/@gmail.com/", $email) && !preg_match("/@email.com/", $email)  && !preg_match("/@yahoo.com/", $email)) {
				$msg = alertMsg('Invalid Email!', 'danger'); $email = "";
			} // if there are no errors, save user to database
			else { //encrypt password before storing
				$password = md5($password_1);
				$sql = "
					INSERT INTO users (companyid, name, goodname, image, email, password, contact, office_position, status, balance, activation, registration_date)

				    VALUES('0', '$username', '$username', 'user-1.jpg', '$email', '$password', '$contact', '', 'online', '0', 'on', NOW())
				";
				$data_input = mysqli_query($db, $sql);
				if ($data_input) {
					
					$id = lastData("users", "id");
					$companyid = allData('users', $id, 'companyid');
					$_SESSION['id'] = $id;
					$_SESSION['email'] = $email;
					// input raw password to another database
					mysqli_query($db, "INSERT INTO passwords(owner, password)VALUES('$id', '$password_1')");

					// insert useraccess/default designation {admin}
					$sql2 = "
						INSERT INTO useraccess(companyid, userid, designation, access_ctrl, bin_ctrl, vessel_ctrl, thirdparty_ctrl, user_ctrl, others_ctrl, status, timedate)
						VALUES(0, '$id', 'Admin', 1, 1, 1, 1, 1, 1, 1, 'active', NOW())
					"; mysqli_query($db, $sql2);
					$designationid = lastData('useraccess', 'id');

					// update user designation
					mysqli_query($db, "UPDATE users SET office_position = '$designationid' WHERE id = '$id' ");

					// insert useraccess/default designation {user}
					$sql2 = "
						INSERT INTO useraccess(companyid, userid, designation, access_ctrl, bin_ctrl, vessel_ctrl, thirdparty_ctrl, user_ctrl, others_ctrl, status, timedate)
						VALUES(0, '$id', 'User', 0, 0, 0, 0, 0, 0, 0, 'active', NOW())
					"; mysqli_query($db, $sql2);

					header("location: index.php"); //redirect to home page
				} else{ $msg = alertMsg("Couldn't insert data!", "danger"); }
			}
		}
	}



	//log user in from login page
	if (isset($_POST['login'])) {
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password = mysqli_real_escape_string($db, $_POST['password']);
		// ensure that form fields are filled peoperly
		if(empty($email)){array_push($errors,"email is required");} //add error to error array
		if(empty($password)){array_push($errors,"password is required");}

		else{	//check if email already exist
			$result_email = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
			if (mysqli_num_rows($result_email) > 0) {
				$password = md5($password); //encrypt password before comparing with database
				$rck = mysqli_fetch_assoc($result_email); $svpassword = $rck['password'];
				if ($svpassword == $password) {
					// $row = mysqli_fetch_assoc($result);
					// check if user is enabled or disabled by admin
					if ($rck['activation'] == "off") {
						$msg = alertMsg("User Disabled, Please contact Admin!", "danger");
					}
					elseif ($rck['activation'] == "delete") {
						$msg = alertMsg("Deleted, Please contact Admin if want to recover id!", "danger");
					}
					else{
				    	$_SESSION['id'] = $rck['id']; 
				    	$_SESSION['email'] = $email; 
				    	// update online status
				    	$sql_3 = "UPDATE users SET status = 'online' WHERE email = '$email' ";
						$run_3 = mysqli_query($db, $sql_3); header("location: index.php");
					}
				}else{ $msg = alertMsg("Wrong password!", "danger"); }
			} else{ $msg = alertMsg("Email dosen't exist, Please sign up!", "danger"); }
		}
	}

	//logout
	if (isset($_GET['logout'])) {
		$id = $_SESSION['id'];
		if (mysqli_query($db,"UPDATE users SET status = 'offline' WHERE id = '$id' ")) {
			session_destroy(); 
			unset($_SESSION['companyid']);
			unset($_SESSION['email']); 
			unset($_SESSION['id']);
			header('location: login.php');
		}
	}

	// ADD VESSEL VARIABLES
	// 1st
	$vsl_num = $msl_num = $vessel_name = $cargo_short_name = $total_qty = $kutubdia_qty = $outer_qty = $retention_qty = $seventyeight_qty = $loadport = $importer = $stevedore = $representative = $cargo_bl_name = $rotation = $anchor = $arrived = $rcv_date = $sailing_date = $com_date = $fender_off = $survey_custom = $survey_consignee = $survey_supplier = $survey_owner = $survey_pni = $survey_chattrer = $received_by = $sailed_by = $remarks = $rcvbynm = ""; $slbynm = ""; $binnumber = ""; $query = "";
	//add vessel
	if (isset($_POST['addVassel'])) {
		$companyid = $my['companyid'];
		$msl_num = mysqli_real_escape_string($db, $_POST['msl_num']); // 1
		$vessel_name = strtoupper(mysqli_real_escape_string($db, $_POST['vessel_name'])); 

		if (empty($msl_num)) {$msg = alertMsg("Msl number is empty!", "danger");}
		//check if msl_num is already exist
		elseif (mysqli_num_rows(mysqli_query($db, "SELECT * FROM vessels WHERE msl_num = '$msl_num' AND companyid = '$companyid' "))>0){$msg = alertMsg('This MSL number is already exist!','success');}
		else{
			$today = date('d-m-Y'); $daycount=dayCount($company['timereset'], $today);

			if ($company['package'] == "free" || $my['companyid'] == 1){$payment = "paid";}
			elseif($company['package'] == "monthly" && $daycount < 31){$payment = "paid";}
			else{$payment = "unpaid";}

			// if ($my['companyid'] == 1) { $payment = "paid"; }
			// else{$payment = "unpaid";}
			$sql = "
				INSERT INTO vessels(companyid, msl_num, vessel_name, rotation, arrived, rcv_date, sailing_date, stevedore, kutubdia_qty, outer_qty, retention_qty, seventyeight_qty, com_date, fender_off, received_by, sailed_by, anchor, representative, survey_consignee, survey_custom, survey_supplier, survey_pni, survey_chattrer, survey_owner, remarks, status, payment, workstatus, timedate) 

				VALUES('$companyid', '$msl_num', '$vessel_name', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$payment', 'done', NOW()) 
			";
			$run = mysqli_query($db, $sql);
			if ($run) {
				$vsl_num = lastData("vessels", "id");
				mysqli_query($db, "INSERT INTO vessel_details(vsl_num, with_retention) VALUES('$vsl_num', 'IN-BALLAST')");
				// header("location: vessel_details.php?vsl_num=$vsl_num");
			}
			else{ $msg = alertMsg("Sorry, Something went wrong inserting data!", "danger"); }

			// by default, add custom and consignee to add surveyor
			$addsurveycompanysql = "
				INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) 
				VALUES('$vsl_num', 'survey_custom', '$survey_custom', '', 'Load Draft'),
				('$vsl_num', 'survey_custom', '$survey_custom', '', 'Light Draft'),
				('$vsl_num', 'survey_consignee', '$survey_consignee', '', 'Load Draft'),
				('$vsl_num', 'survey_consignee', '$survey_consignee', '', 'Light Draft')
			";
			$runsurveycompanysql = mysqli_query($db, $addsurveycompanysql);

			// if exist survey_supplier, add to add surveyor
			if (!empty($survey_supplier)) {
				// by default, add custom and consignee to add surveyor
				$addsurvey_suppliersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_supplier', '$survey_supplier', '', 'Load Draft'),
					('$vsl_num', 'survey_supplier', '$survey_supplier', '', 'Light Draft')";
				$runsurvey_suppliersql = mysqli_query($db, $addsurvey_suppliersql);
			}// if exist survey_owner, add to add surveyor
			if (!empty($survey_owner)) {
				// by default, add custom and consignee to add surveyor
				$addsurvey_ownersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_owner', '$survey_owner', '', 'Load Draft'),
					('$vsl_num', 'survey_owner', '$survey_owner', '', 'Light Draft')";
				$runsurvey_ownersql = mysqli_query($db, $addsurvey_ownersql);
			}// if exist survey_pni, add to add surveyor
			if (!empty($survey_pni)) {
				// by default, add custom and consignee to add surveyor
				$addsurvey_pnisql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_pni', '$survey_pni', '', 'Load Draft'),
					('$vsl_num', 'survey_pni', '$survey_pni', '', 'Light Draft')";
				$runsurvey_pnisql = mysqli_query($db, $addsurvey_pnisql);
			}// if exist survey_chattrer, add to add surveyor
			if (!empty($survey_chattrer)) {
				// by default, add custom and consignee to add surveyor
				$addsurvey_chattrersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_chattrer', '$survey_chattrer', '', 'Load Draft'),
					('$vsl_num', 'survey_chattrer', '$survey_chattrer', '', 'Light Draft')";
				$runsurvey_chattrersql = mysqli_query($db, $addsurvey_chattrersql);
			}
		}
	}












	// update vessel
	if (isset($_POST['vslUpdate'])) {
		$vsl_num = mysqli_real_escape_string($db, $_POST['vsl_num']);//done
		$msl_num = mysqli_real_escape_string($db, $_POST['msl_num']);//done
		$vesselId = $vsl_num; 
		// $msl_num = allData('vessels', $vsl_num, 'msl_num');
		$vessel_name = strtoupper(mysqli_real_escape_string($db, $_POST['vessel_name']));//done
		$kutubdia_qty = mysqli_real_escape_string($db, $_POST['kutubdia_qty']);//done
		$outer_qty = mysqli_real_escape_string($db, $_POST['outer_qty']);//done
		$retention_qty = mysqli_real_escape_string($db, $_POST['retention_qty']);//done
		$seventyeight_qty = mysqli_real_escape_string($db, $_POST['seventyeight_qty']);//done
		// impoter done
		// loadport done
		$stevedore = mysqli_real_escape_string($db, $_POST['stevedore']);//done
		$representative = mysqli_real_escape_string($db, $_POST['representative']);//done
		$rotation = mysqli_real_escape_string($db, $_POST['rotation']);//done
		$anchor = mysqli_real_escape_string($db, $_POST['anchor']);//done

		$com_date = mysqli_real_escape_string($db, $_POST['com_date']); // 18
		$arrived = mysqli_real_escape_string($db, $_POST['arrived']); // 4
		$rcv_date = mysqli_real_escape_string($db, $_POST['rcv_date']); // 4
		$sailing_date = mysqli_real_escape_string($db, $_POST['sailing_date']); // 5
		if (!empty($arrived)) {$arrived = dbtimefotmat('d/m/Y', $arrived, 'd-m-Y');}
		if (!empty($rcv_date)) {$rcv_date = dbtimefotmat('d/m/Y', $rcv_date, 'd-m-Y');}
		if (!empty($com_date)) {$com_date = dbtimefotmat('d/m/Y', $com_date, 'd-m-Y');}
		if (!empty($sailing_date)) {$sailing_date = dbtimefotmat('d/m/Y', $sailing_date, 'd-m-Y');}

		if (isset($_POST['sameRcv'])) { 
			if (empty($_POST['arrived']) && empty($_POST['rcv_date'])){$arrived = $rcv_date = "";}
			elseif (isset($_POST['arrived']) && !empty($_POST['arrived'])) {$rcv_date = $arrived;}
			elseif (isset($_POST['rcv_date']) && !empty($_POST['rcv_date'])) {$arrived = $rcv_date;}
			else {$arrived = $rcv_date;} 
		}else{if(empty($_POST['rcv_date'])){$rcv_date="";}if(empty($_POST['arrived'])){$arrived="";}}


		if (isset($_POST['sameSail'])) { 
			if(empty($_POST['com_date'])&&empty($_POST['sailing_date'])){$sailing_date=$com_date="";}
			elseif(isset($_POST['com_date']) && !empty($_POST['com_date'])) {$sailing_date = $com_date;}
			elseif(isset($_POST['sailing_date'])&&!empty($_POST['sailing_date'])){$com_date=$sailing_date;}
			else {$com_date = $sailing_date;} 
		}else{if(empty($_POST['sailing_date'])){$sailing_date="";}if(empty($_POST['com_date'])){$com_date="";}}

		$survey_custom = mysqli_real_escape_string($db, $_POST['survey_custom']);//done
		$survey_consignee = mysqli_real_escape_string($db, $_POST['survey_consignee']);//done
		$survey_owner = mysqli_real_escape_string($db, $_POST['survey_owner']);//done
		$survey_supplier = mysqli_real_escape_string($db, $_POST['survey_supplier']);//done
		$survey_pni = mysqli_real_escape_string($db, $_POST['survey_pni']);//done
		$survey_chattrer = mysqli_real_escape_string($db, $_POST['survey_chattrer']);//done
		$vsl_opa = mysqli_real_escape_string($db, $_POST['vsl_opa']);
		$received_by = mysqli_real_escape_string($db, $_POST['received_by']);//done
		$sailed_by = mysqli_real_escape_string($db, $_POST['sailed_by']);//done
		$remarks = mysqli_real_escape_string($db, $_POST['remarks']);//done

		// checkbox values
		$custom_visited=$qurentine_visited=$psc_visited=$multiple_lightdues=$crew_change=$has_grab=$fender=$fresh_water=$piloting=$sscec=$egm=0;
		if(isset($_POST['custom_visited'])){$custom_visited = $_POST['custom_visited'];}
		if(isset($_POST['qurentine_visited'])){$qurentine_visited = $_POST['qurentine_visited'];}
		if(isset($_POST['psc_visited'])){$psc_visited = $_POST['psc_visited'];}
		if(isset($_POST['multiple_lightdues'])){$multiple_lightdues = $_POST['multiple_lightdues'];}
		if(isset($_POST['crew_change'])){$crew_change = $_POST['crew_change'];}
		if(isset($_POST['has_grab'])){$has_grab = $_POST['has_grab'];}
		if(isset($_POST['fender'])){$fender = $_POST['fender'];}
		if(isset($_POST['fresh_water'])){$fresh_water = $_POST['fresh_water'];}
		if(isset($_POST['piloting'])){$piloting = $_POST['piloting'];}
		if(isset($_POST['sscec'])){$sscec = $_POST['sscec'];}
		if(isset($_POST['egm'])){$egm = $_POST['egm'];}

		
		
		
		// // update vessels importer
		// if (isset($_POST['importer'])) {
		// 	$importer = $_POST['importer'];

		// 	// Convert the importer list to a comma-separated string for SQL query
		// 	$importerListString = "'" . implode("', '", $importer) . "'";
		// 	// SQL query to delete importers not in the importer list
		// 	$delsql = "DELETE FROM vessels_importer WHERE msl_num = '$msl_num' AND importer NOT IN ($importerListString)"; mysqli_query($db,$delsql);

		// 	// indest vessels consignee
		// 	foreach ($importer as $key =>  $importerId) {
		//     	$importer_name = allData('bins', $importerId, 'name');

		//     	// check if importer already sinked
		//     	$run1 = mysqli_query($db, "SELECT * FROM vessels_importer WHERE importer = '$importerId' AND msl_num = '$msl_num' ");
		//     	// skip if importer already exists
		//     	if (mysqli_num_rows($run1) > 0 || $importerId == 0 ) { continue; }
		//     	// now insert
		//     	$sql = "
		// 	    	INSERT INTO vessels_importer(msl_num, importer, cnf)
		// 	    	VALUES('$msl_num', '$importerId', '')
		//     	"; $run = mysqli_query($db, $sql);
		//     }
		// }else{mysqli_query($db, "DELETE FROM vessels_importer WHERE msl_num = '$msl_num' ");}

		// check if multiple survey_party choosen for more then one purpose
		if (
			isset($survey_custom) && $survey_custom != "" && $survey_custom == $survey_consignee && $survey_custom != 35 
			|| isset($survey_custom) && $survey_custom != "" && $survey_custom == $survey_owner && $survey_custom != 35 
			|| isset($survey_custom) && $survey_custom != "" && $survey_custom == $survey_pni && $survey_custom != 35 
			|| isset($survey_custom) && $survey_custom != "" && $survey_custom == $survey_chattrer && $survey_custom != 35 
			|| isset($survey_custom) && $survey_custom != "" && $survey_custom == $survey_supplier && $survey_custom != 35 


			|| isset($survey_consignee) && $survey_consignee != "" && $survey_consignee == $survey_custom && $survey_consignee != 35 
			|| isset($survey_consignee) && $survey_consignee != "" && $survey_consignee == $survey_owner && $survey_consignee != 35 
			|| isset($survey_consignee) && $survey_consignee != "" && $survey_consignee == $survey_pni && $survey_consignee != 35 
			|| isset($survey_consignee) && $survey_consignee != "" && $survey_consignee == $survey_chattrer && $survey_consignee != 35 
			|| isset($survey_consignee) && $survey_consignee != "" && $survey_consignee == $survey_supplier && $survey_consignee != 35 


			|| isset($survey_owner) && $survey_owner != "" && $survey_owner == $survey_consignee && $survey_owner != 35 
			|| isset($survey_owner) && $survey_owner != "" && $survey_owner == $survey_custom && $survey_owner != 35 
			|| isset($survey_owner) && $survey_owner != "" && $survey_owner == $survey_pni && $survey_owner != 35 
			|| isset($survey_owner) && $survey_owner != "" && $survey_owner == $survey_chattrer && $survey_owner != 35 
			|| isset($survey_owner) && $survey_owner != "" && $survey_owner == $survey_supplier && $survey_owner != 35 


			|| isset($survey_pni) && $survey_pni != "" && $survey_pni == $survey_consignee && $survey_pni != 35 
			|| isset($survey_pni) && $survey_pni != "" && $survey_pni == $survey_owner && $survey_pni != 35 
			|| isset($survey_pni) && $survey_pni != "" && $survey_pni == $survey_custom && $survey_pni != 35 
			|| isset($survey_pni) && $survey_pni != "" && $survey_pni == $survey_chattrer && $survey_pni != 35 
			|| isset($survey_pni) && $survey_pni != "" && $survey_pni == $survey_supplier && $survey_pni != 35 


			|| isset($survey_chattrer) && $survey_chattrer != "" && $survey_chattrer == $survey_consignee && $survey_chattrer != 35 
			|| isset($survey_chattrer) && $survey_chattrer != "" && $survey_chattrer == $survey_owner && $survey_chattrer != 35 
			|| isset($survey_chattrer) && $survey_chattrer != "" && $survey_chattrer == $survey_pni && $survey_chattrer != 35 
			|| isset($survey_chattrer) && $survey_chattrer != "" && $survey_chattrer == $survey_custom && $survey_chattrer != 35
			|| isset($survey_chattrer) && $survey_chattrer != "" && $survey_chattrer == $survey_supplier && $survey_chattrer != 35


			|| isset($survey_supplier) && $survey_supplier != "" && $survey_supplier == $survey_consignee && $survey_supplier != 35 
			|| isset($survey_supplier) && $survey_supplier != "" && $survey_supplier == $survey_owner && $survey_supplier != 35 
			|| isset($survey_supplier) && $survey_supplier != "" && $survey_supplier == $survey_pni && $survey_supplier != 35 
			|| isset($survey_supplier) && $survey_supplier != "" && $survey_supplier == $survey_custom && $survey_supplier != 35
			|| isset($survey_supplier) && $survey_supplier != "" && $survey_supplier == $survey_chattrer && $survey_supplier != 35
		) {
			$msg = alertMsg('One survey company can\'t do more then one survey at a time!','success');
		}
		else{
			$sql = "
				UPDATE vessels SET msl_num = '$msl_num', vessel_name = '$vessel_name', rotation = '$rotation', arrived = '$arrived', rcv_date = '$rcv_date', sailing_date = '$sailing_date', stevedore = '$stevedore', kutubdia_qty = '$kutubdia_qty', outer_qty = '$outer_qty', retention_qty = '$retention_qty', seventyeight_qty = '$seventyeight_qty', com_date = '$com_date', fender_off = '', received_by = '$received_by', sailed_by = '$sailed_by', anchor = '$anchor', representative = '$representative', survey_consignee = '$survey_consignee', survey_custom = '$survey_custom', survey_supplier = '$survey_supplier', survey_pni = '$survey_pni', survey_chattrer = '$survey_chattrer', survey_owner = '$survey_owner', vsl_opa = '$vsl_opa', custom_visited = '$custom_visited', qurentine_visited = '$qurentine_visited', psc_visited = '$psc_visited', multiple_lightdues = '$multiple_lightdues', crew_change = '$crew_change', has_grab = '$has_grab', fender = '$fender', fresh_water = '$fresh_water', piloting = '$piloting', sscec = '$sscec', egm = '$egm', remarks = '$remarks' WHERE id = '$vsl_num'
			";
			$run = mysqli_query($db, $sql); 
			if ($run) {
				$msg = alertMsg("Updated Successfully!", "success");

				// check and add to vessels_surveyor table if new survey company added
				if (!empty($survey_custom)) {
					// check if survey supplier not exists in vessels surviour table
					if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose = 'Load Draft' ")){
						$addsurvey_customsql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_custom', '$survey_custom', '', 'Load Draft')";
						mysqli_query($db, $addsurvey_customsql);
					}
					// check if survey supplier not exists in vessels surviour table
					if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose = 'Light Draft' OR vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose = 'Rob' ")){
						$addsurvey_customsql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_custom', '$survey_custom', '', 'Light Draft')";
						mysqli_query($db, $addsurvey_customsql);
					}
				}
				
				
				// check and add to vessels_urveyor table if new survey company added
				if (!empty($survey_consignee)) {
					// check if survey supplier not exists in vessels surviour table
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose = 'Load Draft' ")==0){
						$addsurvey_consigneesql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_consignee', '$survey_consignee', '', 'Load Draft')";
						mysqli_query($db, $addsurvey_consigneesql);
					}
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose = 'Light Draft' OR vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose = 'Rob' ")==0){
						$addsurvey_consigneesql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_consignee', '$survey_consignee', '', 'Light Draft')";
						mysqli_query($db, $addsurvey_consigneesql);
					}
				}
				

				
				// check and add to vessels_urveyor table if new survey company added
				if (!empty($survey_supplier)) {
					// check if survey supplier not exists in vessels surviour table
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND survey_purpose = 'Load Draft' ")==0){
						$addsurvey_suppliersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_supplier', '$survey_supplier', '', 'Load Draft')";
						mysqli_query($db, $addsurvey_suppliersql);
					}
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND survey_purpose = 'Light Draft' ")==0){
						$addsurvey_suppliersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_supplier', '$survey_supplier', '', 'Light Draft')";
						mysqli_query($db, $addsurvey_suppliersql);
					}
				}
				
					
				// check and add to vessels_urveyor table if new survey company added
				if (!empty($survey_owner)) {
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND survey_purpose = 'Load Draft' ")==0){
						$addsurvey_ownersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_owner', '$survey_owner', '', 'Load Draft')";
						mysqli_query($db, $addsurvey_ownersql);
					}
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND survey_purpose = 'Light Draft' ")==0){
						$addsurvey_ownersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_owner', '$survey_owner', '', 'Light Draft')";
						mysqli_query($db, $addsurvey_ownersql);
					}
				}

				// if exist survey_pni, add to add surveyor
				if (!empty($survey_pni)) {
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND survey_purpose = 'Load Draft' ")==0){
						// by default, add custom and consignee to add surveyor
						$addsurvey_pnisql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_pni', '$survey_pni', '', 'Load Draft')";
						mysqli_query($db, $addsurvey_pnisql);
					}
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND survey_purpose = 'Light Draft' ")==0){
						// by default, add custom and consignee to add surveyor
						$addsurvey_pnisql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_pni', '$survey_pni', '', 'Light Draft')";
						mysqli_query($db, $addsurvey_pnisql);
					}
				}
				// if exist survey_chattrer, add to add surveyor
				if (!empty($survey_chattrer)) {
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND survey_purpose = 'Load Draft' ")==0){
						// by default, add custom and consignee to add surveyor
						$addsurvey_chattrersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_chattrer', '$survey_chattrer', '', 'Load Draft')";
						mysqli_query($db, $addsurvey_chattrersql);
					}
					if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND survey_purpose = 'Light Draft' ")==0){
						// by default, add custom and consignee to add surveyor
						$addsurvey_chattrersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
							('$vsl_num', 'survey_chattrer', '$survey_chattrer', '', 'Light Draft')";
						mysqli_query($db, $addsurvey_chattrersql);
					}
				}

				// update vessels survey company
				// these values comes from infostore start
				$prevsurvey_custom = $thisvessel['survey_custom'];
				$prevsurvey_consignee = $thisvessel['survey_consignee'];
				$prevsurvey_supplier = $thisvessel['survey_supplier'];
				$prevsurvey_owner = $thisvessel['survey_owner'];
				$prevsurvey_pni = $thisvessel['survey_pni'];
				$prevsurvey_chattrer = $thisvessel['survey_chattrer'];
				// these values comes from infostore end

				$allparties = array("survey_custom","survey_consignee","survey_supplier","survey_owner","survey_pni","survey_chattrer");
				$prev = "prev"; 
				$prevmodifiedsurveycompanyId = array(); 
				$newmodifiedsurveycompanyId = array();
				$newmodifiedsurveyparty = array();

				// store the survey company if modified
				foreach ($allparties as $key => $survey_party) { //example: survey_custom
					// add prev word to all parties. example: prevsurvey_custom, prevsurvey_consignee etc.
					$previous_survey = "prev".$survey_party; //example: prevsurvey_custom
					// checks if prev survey party and submitted survey party is not name.
					if($$survey_party!=$$previous_survey){ //example: $survey_custom != $prevsurvey_custom
						// add prevsurvey_party id to this array
						// $prevmodifiedsurveycompanyId[]=allDataUpdated('vessels','msl_num',$msl_num,$survey_party);	
						$prevmodifiedsurveycompanyId[]=$thisvessel[$survey_party];
						// add submitted survey_party id to this array
						$newmodifiedsurveycompanyId[] = $$survey_party; // $custom_survey
						// add surmitted survey party
						$newmodifiedsurveyparty[] = $survey_party; //"custom_survey";
					}
				}



				// update survey company from vessels_surveyor table
				foreach ($prevmodifiedsurveycompanyId as $key => $companyId) {
					$newsurveycompanyId = $newmodifiedsurveycompanyId[$key];
					$modifiedsurveyparty = $newmodifiedsurveyparty[$key];

					$update = mysqli_query($db, "UPDATE vessels_surveyor SET survey_company = '$newsurveycompanyId' WHERE vsl_num = '$vsl_num' AND survey_company = '$companyId' AND survey_party = '$modifiedsurveyparty' ");
					if ($update) {
						$msg = alertMsg("Updated vessels_surveyor!", "success");
					}else{$msg = alertMsg("Something went wrong!", "danger");}
				}
			}
			else{$msg = alertMsg("Something went wrong, Couldn't update data!", "danger");}
		}
		// header("location: vessel_details.php?msl_num=$msl_num");
	}

	// delete vessel
	if (isset($_GET['del_msl_num'])) {
		$vsl_num = $_GET['del_msl_num'];

		// delete vessel
		mysqli_query($db, "DELETE FROM vessels WHERE id = '$vsl_num' ");//

		// delete all vessels related data
		mysqli_query($db, "DELETE FROM vessels_bl WHERE vsl_num = '$vsl_num' ");//
		mysqli_query($db, "DELETE FROM vessels_cargo WHERE vsl_num = '$vsl_num' ");//
		mysqli_query($db, "DELETE FROM vessels_importer WHERE vsl_num = '$vsl_num' ");//
		mysqli_query($db, "DELETE FROM vessels_surveyor WHERE vsl_num = '$vsl_num' ");//
		mysqli_query($db, "DELETE FROM vessel_details WHERE vsl_num = '$vsl_num' ");//

		// redirect to homepage
		header('location: index.php');
	}





	// complete percentage
	if (isset($_POST['percentagecomplete'])) {
		$vsl_num = mysqli_real_escape_string($db, $_POST['vsl_num']); // done
		$vesselId = $vsl_num; // done
		$msl_num = allData('vessels', $vsl_num, 'msl_num');
		$vessel_name = strtoupper(mysqli_real_escape_string($db, $_POST['vessel_name'])); 

		$kutubdia_qty = $thisvessel['kutubdia_qty']; 
		$retention_qty = $thisvessel['retention_qty'];
		
		if (isset($_POST['kutubdia_qty'])&&!empty($_POST['kutubdia_qty'])) { $kutubdia_qty = mysqli_real_escape_string($db, $_POST['kutubdia_qty']); }

		if (isset($_POST['retention_qty'])&&!empty($_POST['retention_qty'])) { $retention_qty = mysqli_real_escape_string($db, $_POST['retention_qty']); }

		$outer_qty = $thisvessel['outer_qty']; $stevedore = $thisvessel['stevedoreid'];
		$representative = $thisvessel['rep_id']; $rotation = $thisvessel['rotation'];
		$anchor = $thisvessel['anchor']; 


		$survey_custom = $thisvessel['survey_custom'];
		$survey_consignee = $thisvessel['survey_consignee']; $received_by = $thisvessel['received_by'];
		$sailed_by = $thisvessel['sailed_by'];

		if (isset($_POST['outer_qty'])) {
			if (!empty($_POST['outer_qty'])) {
				$outer_qty = mysqli_real_escape_string($db, $_POST['outer_qty']);
			}else{if(!empty($kutubdia_qty)){$outer_qty=ttlcargoqty($vsl_num)-$kutubdia_qty;}}
		}

		if (isset($_POST['stevedore'])) {
			$stevedore = mysqli_real_escape_string($db, $_POST['stevedore']);
		}if (isset($_POST['representative'])) {
			$representative = mysqli_real_escape_string($db, $_POST['representative']);
		}if (isset($_POST['rotation'])) {
			$rotation = mysqli_real_escape_string($db, $_POST['rotation']);
		}if (isset($_POST['anchor'])) {
			$anchor = mysqli_real_escape_string($db, $_POST['anchor']);
		}


		
		if (isset($_POST['arrived']) && !empty($_POST['arrived'])) {
			$arrived = mysqli_real_escape_string($db, $_POST['arrived']);
			$arrived = dbtimefotmat('d/m/Y', $arrived, 'd-m-Y');
			// $arrived = date('d-m-Y', strtotime($arrived));
		}elseif(isset($_POST['arrived']) && empty($_POST['arrived'])) {$arrived = "";}
		else{$arrived = $thisvessel['arrived'];}

		if (isset($_POST['rcv_date']) && !empty($_POST['rcv_date'])) {
			$rcv_date = mysqli_real_escape_string($db, $_POST['rcv_date']);
			$rcv_date = dbtimefotmat('d/m/Y', $rcv_date, 'd-m-Y');
			// $rcv_date = date('d-m-Y', strtotime($rcv_date));
		}elseif(isset($_POST['rcv_date']) && empty($_POST['rcv_date'])){$rcv_date = "";}
		else{$rcv_date = $thisvessel['rcv_date'];}

		if (isset($_POST['com_date']) && !empty($_POST['com_date'])) {
			$com_date = mysqli_real_escape_string($db, $_POST['com_date']);
			$com_date = dbtimefotmat('d/m/Y', $com_date, 'd-m-Y');
			// $com_date = date('d-m-Y', strtotime($com_date));
		}elseif(isset($_POST['com_date']) && empty($_POST['com_date'])){$com_date = "";}
		else{$com_date = $thisvessel['com_date'];}

		if (isset($_POST['sailing_date']) && !empty($_POST['sailing_date'])) {
			$sailing_date = mysqli_real_escape_string($db, $_POST['sailing_date']);
			$sailing_date = dbtimefotmat('d/m/Y', $sailing_date, 'd-m-Y');
			// $sailing_date = date('d-m-Y', strtotime($sailing_date));
		}elseif(isset($_POST['sailing_date']) && empty($_POST['sailing_date'])){$sailing_date = "";}
		else{$sailing_date = $thisvessel['sailing_date'];}

		if (isset($_POST['sameRcv'])) { 
			if(isset($_POST['arrived']) && !empty($_POST['arrived'])) {$rcv_date = $arrived;}
			else {$arrived = $rcv_date;} 
		}

		if (isset($_POST['sameSail'])) { 
			if (isset($_POST['com_date']) && !empty($_POST['com_date'])) {$sailing_date = $com_date;}
			else {$com_date = $sailing_date;} 
		}


		if (isset($_POST['survey_custom'])) {
			$survey_custom = mysqli_real_escape_string($db, $_POST['survey_custom']);
		}if (isset($_POST['survey_consignee'])) {
			$survey_consignee = mysqli_real_escape_string($db, $_POST['survey_consignee']);
		}if (isset($_POST['received_by'])) {
			$received_by = mysqli_real_escape_string($db, $_POST['received_by']);
		}if (isset($_POST['sailed_by'])) {
			$sailed_by = mysqli_real_escape_string($db, $_POST['sailed_by']);
		}

		$sql = "
			UPDATE vessels SET rotation = '$rotation', arrived = '$arrived', rcv_date = '$rcv_date', com_date = '$com_date', sailing_date = '$sailing_date', stevedore = '$stevedore', retention_qty = '$retention_qty', kutubdia_qty = '$kutubdia_qty', outer_qty = '$outer_qty', received_by = '$received_by', sailed_by = '$sailed_by', anchor = '$anchor', representative = '$representative', survey_consignee = '$survey_consignee', survey_custom = '$survey_custom' WHERE id = '$vsl_num'
		";
		$run = mysqli_query($db, $sql);


		// add cargo section
		if (isset($_POST['loadport']) || isset($_POST['quantity']) || isset($_POST['cargokey']) || isset($_POST['cargo_bl_name'])) {
			$loadport = mysqli_real_escape_string($db, $_POST['loadport']); // done
			$quantity = mysqli_real_escape_string($db, $_POST['quantity']); // done
			$cargokey = mysqli_real_escape_string($db, $_POST['cargokey']); // done
			$cargo_bl_name = mysqli_real_escape_string($db, $_POST['cargo_bl_name']); // done
			
	    	if (empty($loadport) || empty($quantity) || empty($cargokey) || empty($cargo_bl_name)) { $msg = alertMsg('Some field is empty in cargo section--!','danger'); }
	    	else{
	    		$sql = "INSERT INTO vessels_cargo(vsl_num, cargo_key, loadport, quantity, cargo_bl_name) VALUES('$vsl_num', '$cargokey', '$loadport', '$quantity', '$cargo_bl_name')";
				$run = mysqli_query($db, $sql);if($run){$msg = alertMsg('Added Successfully!','success');}
				else{$msg = alertMsg('Something went wrong, Couldn\'t incert data!','danger');}
				// header('location: 3rd_parties.php?page=stevedore');	
	    	}
		}

    	// add survey table for vessels_surveyor
		// check and add to vessels_surveyor table if new survey company added
		if (!empty($survey_custom)) {
			// check if survey supplier not exists in vessels surviour table {Load}
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose = 'Load Draft' ")){
				$addsurvey_customsql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_custom', '$survey_custom', '', 'Load Draft')";
				$runsurvey_customsql = mysqli_query($db, $addsurvey_customsql);
			}
			// check if survey supplier not exists in vessels surviour table {Light}
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose = 'Light Draft' ") && !exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose = 'Rob' ")){
				$addsurvey_customsql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_custom', '$survey_custom', '', 'Light Draft')";
				$runsurvey_customsql = mysqli_query($db, $addsurvey_customsql);
			}
		}
		
		
		// check and add to vessels_urveyor table if new survey company added
		if (!empty($survey_consignee)) {
			// check if survey supplier not exists in vessels surviour table
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose = 'Load Draft' ")){
				$addsurvey_consigneesql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_consignee', '$survey_consignee', '', 'Load Draft')";
				$runsurvey_consigneesql = mysqli_query($db, $addsurvey_consigneesql);
			}
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose = 'Light Draft' ") && !exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose = 'Rob' ")){
				$addsurvey_consigneesql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_consignee', '$survey_consignee', '', 'Light Draft')";
				$runsurvey_consigneesql = mysqli_query($db, $addsurvey_consigneesql);
			}
		}
		

		
		// check and add to vessels_urveyor table if new survey company added
		if (!empty($survey_supplier)) {
			// check if survey supplier not exists in vessels surviour table
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND survey_purpose = 'Load Draft' ")){
				$addsurvey_suppliersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_supplier', '$survey_supplier', '', 'Load Draft')";
				$runsurvey_suppliersql = mysqli_query($db, $addsurvey_suppliersql);
			}
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND survey_purpose = 'Light Draft' ")){
				$addsurvey_suppliersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_supplier', '$survey_supplier', '', 'Light Draft')";
				$runsurvey_suppliersql = mysqli_query($db, $addsurvey_suppliersql);
			}
		}
		
			
		// check and add to vessels_urveyor table if new survey company added
		if (!empty($survey_owner)) {
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND survey_purpose = 'Load Draft' ")){
				$addsurvey_ownersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_owner', '$survey_owner', '', 'Load Draft')";
				$runsurvey_ownersql = mysqli_query($db, $addsurvey_ownersql);
			}
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND survey_purpose = 'Light Draft' ")){
				$addsurvey_ownersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_owner', '$survey_owner', '', 'Light Draft')";
				$runsurvey_ownersql = mysqli_query($db, $addsurvey_ownersql);
			}
		}

		// if exist survey_pni, add to add surveyor
		if (!empty($survey_pni)) {
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND survey_purpose = 'Load Draft' ")){
				// by default, add custom and consignee to add surveyor
				$addsurvey_pnisql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_pni', '$survey_pni', '', 'Load Draft')";
				$runsurvey_pnisql = mysqli_query($db, $addsurvey_pnisql);
			}
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND survey_purpose = 'Light Draft' ")){
				// by default, add custom and consignee to add surveyor
				$addsurvey_pnisql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_pni', '$survey_pni', '', 'Light Draft')";
				$runsurvey_pnisql = mysqli_query($db, $addsurvey_pnisql);
			}
		}
		// if exist survey_chattrer, add to add surveyor
		if (!empty($survey_chattrer)) {
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND survey_purpose = 'Load Draft' ")){
				// by default, add custom and consignee to add surveyor
				$addsurvey_chattrersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_chattrer', '$survey_chattrer', '', 'Load Draft')";
				$runsurvey_chattrersql = mysqli_query($db, $addsurvey_chattrersql);
			}
			if(!exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND survey_purpose = 'Light Draft' ")){
				// by default, add custom and consignee to add surveyor
				$addsurvey_chattrersql = "INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) VALUES
					('$vsl_num', 'survey_chattrer', '$survey_chattrer', '', 'Light Draft')";
				$runsurvey_chattrersql = mysqli_query($db, $addsurvey_chattrersql);
			}
		}


		// add survey company
		// these values comes from infostore start
		$prevsurvey_custom = $thisvessel['survey_custom'];
		$prevsurvey_consignee = $thisvessel['survey_consignee'];
		$prevsurvey_supplier = $thisvessel['survey_supplier'];
		$prevsurvey_owner = $thisvessel['survey_owner'];
		$prevsurvey_pni = $thisvessel['survey_pni'];
		$prevsurvey_chattrer = $thisvessel['survey_chattrer'];
		// these values comes from infostore end

		$allparties = array("survey_custom","survey_consignee","survey_supplier","survey_owner","survey_pni","survey_chattrer");

		$prev = "prev"; 
		$prevmodifiedsurveycompanyId = array(); 
		$newmodifiedsurveycompanyId = array();
		$newmodifiedsurveyparty = array();

		// store the survey company if modified
		foreach ($allparties as $key => $survey_party) { //example: survey_custom
			// add prev word to all parties. example: prevsurvey_custom, prevsurvey_consignee etc.
			$previous_survey = "prev".$survey_party; //example: prevsurvey_custom
			// checks if prev survey party and submitted survey party is not name.
			if($$survey_party!=$$previous_survey){ //example: $survey_custom != $prevsurvey_custom
				// add prevsurvey_party id to this array
				// $prevmodifiedsurveycompanyId[]=allDataUpdated('vessels','msl_num',$msl_num,$survey_party);	
				$prevmodifiedsurveycompanyId[]=$thisvessel[$survey_party];
				// add submitted survey_party id to this array
				$newmodifiedsurveycompanyId[] = $$survey_party; // $custom_survey
				// add surmitted survey party
				$newmodifiedsurveyparty[] = $survey_party; //"custom_survey";
			}
		}



		// update survey company from vessels_surveyor table
		foreach ($prevmodifiedsurveycompanyId as $key => $companyId) {
			$newsurveycompanyId = $newmodifiedsurveycompanyId[$key];
			$modifiedsurveyparty = $newmodifiedsurveyparty[$key];

			$update = mysqli_query($db, "UPDATE vessels_surveyor SET survey_company = '$newsurveycompanyId' WHERE vsl_num = '$vsl_num' AND survey_company = '$companyId' AND survey_party = '$modifiedsurveyparty' ");
			if ($update) {
				$msg = alertMsg("Updated vessels_surveyor!", "success");
			}else{$msg = alertMsg("Something went wrong!", "danger");}
		}


		// add vessels surveyor
		if (isset($_POST['listsurveyor']) && $_POST['listsurveyor'] > 0) {
			$listsurveyor = mysqli_real_escape_string($db, $_POST['listsurveyor']);
			for ($s=1; $s <= $listsurveyor; $s++) { 
				$thisrowIdsurveyor = mysqli_real_escape_string($db, $_POST['thisrowIdsurveyor'.$s]);
				$prevSurveyor = allData('vessels_surveyor', $thisrowIdsurveyor, 'surveyor');
				$survey_partys = mysqli_real_escape_string($db, $_POST['party'.$s]);
				$surveyorId = mysqli_real_escape_string($db, $_POST['surveyorId'.$s]);
				$survey_company = $thisvessel[$survey_partys];
				$survey_purpose = mysqli_real_escape_string($db, $_POST['survey_purpose'.$s]);
				if (empty($thisrowIdsurveyor) || empty($survey_partys) || empty($surveyorId) || empty($survey_purpose)) {
					$msg = alertMsg('Some Fields are empty in surveyor section!','danger');
				}else{
					// check if same surveyor is working in another party on same load/light purpose
					// check: same surveyor, same purpose, same party
					$run_1 = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND surveyor = '$surveyorId' AND survey_purpose = '$survey_purpose' AND survey_party = '$survey_partys' ");

					// check if already added surveyor or survey purpose (load/light) in same company
					// check: same party, same purpose, different surveyor.
					$run = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND survey_party = '$survey_partys' AND survey_purpose = '$survey_purpose' AND id != '$thisrowIdsurveyor' ");
					
					if (mysqli_num_rows($run_1) > 0) {
						$delete = mysqli_query($db, "DELETE FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND surveyor = '$surveyorId' AND survey_purpose = '$survey_purpose' AND survey_party = '$survey_partys' ");

						if ($survey_purpose == "both") {
							$update = mysqli_query($db, "UPDATE vessels_surveyor SET surveyor = '$surveyorId' WHERE survey_party = '$survey_partys' AND vsl_num = '$vsl_num' ");
							$msg = alertMsg('Running both if', 'success');		
						}
						else{
							$update = mysqli_query($db, "UPDATE vessels_surveyor SET surveyor = '$surveyorId', survey_purpose = '$survey_purpose' WHERE id = '$thisrowIdsurveyor' ");
							$msg = alertMsg('Running if', 'success');
						}
					}
					elseif (mysqli_num_rows($run) > 0) {
						$delete = mysqli_query($db, "DELETE FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND survey_party = '$survey_partys' AND survey_purpose = '$survey_purpose' AND id != '$thisrowIdsurveyor' ");

						if ($survey_purpose == "both") {
							$update = mysqli_query($db, "UPDATE vessels_surveyor SET surveyor = '$surveyorId' WHERE survey_party = '$survey_partys' AND vsl_num = '$vsl_num' ");
							$msg = alertMsg('Running both elseif', 'success');		
						}
						else{
							$update = mysqli_query($db, "UPDATE vessels_surveyor SET surveyor = '$surveyorId', survey_purpose = '$survey_purpose' WHERE id = '$thisrowIdsurveyor' ");
							$msg = alertMsg('Running elseif', 'success');
						}
					}
					else{
						if ($survey_purpose == "both") {
							$update = mysqli_query($db, "UPDATE vessels_surveyor SET surveyor = '$surveyorId' WHERE survey_party = '$survey_partys' AND vsl_num = '$vsl_num' ");
							$msg = alertMsg('Running both end', 'success');		
						}
						else{
							$update = mysqli_query($db, "UPDATE vessels_surveyor SET surveyor = '$surveyorId', survey_purpose = '$survey_purpose' WHERE id = '$thisrowIdsurveyor' ");
							$msg = alertMsg('Running else', 'success');
						}
					}
					//if ($update) {$msg = alertMsg('Percentage Surveyor Updated!', 'success');}
					//else{$msg = alertMsg('Couldn\'t update percentage Surveyor!', 'danger');}
				}
			}
		}



	  //   if (isset($_POST['listimporter']) && $_POST['listimporter'] > 0) {
	  //   	// update cnf
	  //   	$vsl_num = mysqli_real_escape_string($db, $_POST['vsl_num']);
			// $listimporter = mysqli_real_escape_string($db, $_POST['listimporter']);
			// for ($i=0; $i <= $listimporter; $i++) { 
			// 	if (isset($_POST['thisrowIdcnf'.$i]) && isset($_POST['importerId'.$i])) {
			// 		$thisrowId = mysqli_real_escape_string($db, $_POST['thisrowIdcnf'.$i]);
			// 		$importerId = mysqli_real_escape_string($db, $_POST['importerId'.$i]);
			// 		$cnfId = mysqli_real_escape_string($db, $_POST['cnfId'.$i]);
			// 		if (empty($thisrowId) || empty($importerId) || empty($cnfId)){}
			// 		else{
			// 			$update = mysqli_query($db, "UPDATE vessels_importer SET cnf = '$cnfId' WHERE importer = '$importerId' AND vsl_num = '$vsl_num' ");
			// 			if ($update) { $msg = alertMsg('Cnf Updated Successfully!', 'success'); }
			// 			else{$msg = alertMsg('Couldn\'t update percentage cnf!', 'danger');}
			// 		}
			// 	}
			// }
	  //   }
	}






	if (isset($_POST['updateProfile'])) {
		$name = $email = $contact = $newpass = ""; $id = $_POST['updateProfile']; 
		$name = mysqli_real_escape_string($db, $_POST['name']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$contact = mysqli_real_escape_string($db, $_POST['contact']);
		$oldpass = mysqli_real_escape_string($db, $_POST['oldpass']);
		$newpass = mysqli_real_escape_string($db, $_POST['newpass']);
		$newpassraw = $newpass;

		// updating basics
		$up = mysqli_query($db,"UPDATE users SET name='$name',email='$email',contact='$contact' WHERE id='$id' "); if ($up) {
			$msg = alertMsg("Profile Updated Successfully!", "success");
			// updating password'
			if (isset($newpass) && !empty($newpass)) { 
				if (strlen($newpass) < 6) {
					$msg = alertMsg("Password should be minimum 6 character!", "danger");
				}
				else{
					$newpass = md5($newpass);
					if (!empty($oldpass)) { $oldpass = md5($oldpass);
						$useroldpass = allData('users', $id, 'password'); 
						if ($oldpass == $useroldpass) {
							$psup=mysqli_query($db, "UPDATE users SET password = '$newpass' WHERE id = '$id' ");
							if (!$psup) {$msg = alertMsg("Couldn't Update Password!", "danger");}
							else{
								$findoldpass = mysqli_query($db, "SELECT password FROM passwords WHERE owner = '$id' ");
								if (mysqli_num_rows($findoldpass) > 0) {
									mysqli_query($db, "UPDATE passwords SET password = '$newpassraw' WHERE owner = '$id' ");
								}else{mysqli_query($db, "INSERT INTO passwords(owner,password) VALUES('$id', '$newpassraw')");}
								$msg = alertMsg("Password Updated Successfully!", "success");
							}
						}else{$msg = alertMsg("Old password Dose not match!", "danger");}
					}else{$msg = alertMsg("Please Input Old password!", "danger");}
				}
			}

			// image update
			if (!empty($_FILES['pp']['name'])) {
				$img = "MSL".round(microtime(true)*10).basename($_FILES['pp']['name']);
	 			$target = "img/userimg/".$img; move_uploaded_file($_FILES['pp']['tmp_name'],$target);
	 			mysqli_query($db, "UPDATE users SET image = '$img' WHERE id = '$id' ");
			}
		}
		else{$msg = alertMsg("Couldn't Basic info!", "danger");}
	}


	//add cargokey
	if (isset($_POST['addCargoKey'])) {
		$userid = $my['id']; $companyid = $my['companyid'];
		$cargoKey = strtolower(mysqli_real_escape_string($db, $_POST['cargoKey']));
		$check = mysqli_query($db, "SELECT * FROM cargokeys WHERE name = '$cargoKey' ");
		if (mysqli_num_rows($check)>0) {$msg = alertMsg("Key Already Exists", "danger");}
		else{
			$sql = "INSERT INTO cargokeys(companyid, userid, name, status, timedate) VALUES('$companyid', '$userid', '$cargoKey', 'unapproved', NOW())";
			$run = mysqli_query($db, $sql); if($run){alertMsg("Key Added Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		}
	}

	//edit cargokey
	if (isset($_POST['editCargoKey'])) {
		$keyId = mysqli_real_escape_string($db, $_POST['keyId']);
		$cargoKey = strtolower(mysqli_real_escape_string($db, $_POST['cargoKey']));
		$check = mysqli_query($db, "SELECT * FROM cargokeys WHERE name = '$cargoKey' ");
		if (mysqli_num_rows($check)>0) {$msg = alertMsg("Key Already Exists", "danger");}
		else{
			$sql = "UPDATE cargokeys SET name = '$cargoKey' WHERE id = '$keyId' ";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Key Updated Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
			// header('location: 3rd_parties.php?page=consignee');	
		}
	}

	// delete cargokey
	if (isset($_GET['aprvcrgokey'])) {
		$keyId = $_GET['aprvcrgokey'];
		mysqli_query($db, "UPDATE cargokeys SET status = 'approved' WHERE id = '$keyId' ");
		header('location: usercontrols.php?page=cargokeyapproval');
	}

	// delete cargokey
	if (isset($_GET['del_CargoKey'])) {
		$keyId = $_GET['del_CargoKey'];
		// delete cargo keys
		mysqli_query($db, "DELETE FROM cargokeys WHERE id = $keyId ");
		// delete cargokey related vessels
		mysqli_query($db, "DELETE FROM vessels_cargo WHERE cargo_key = '$keyId' ");
		header('location: others_adds.php?page=cargoKeys');
	}


	//add useraccess
	if (isset($_POST['addUseraccess'])) {
		$userid = $my['id']; $companyid = $my['companyid'];
		$designation = mysqli_real_escape_string($db, $_POST['useraccess']);
		
		$sql = "INSERT INTO useraccess(companyid, userid, designation) VALUES('$companyid', '$userid', '$designation')";
		$run = mysqli_query($db, $sql); if($run){alertMsg("Designation Added Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	}

	// delete useraccess
	if (isset($_GET['del_useraccess'])) {
		$keyId = $_GET['del_useraccess'];
		// get all users from this designation
		$run = mysqli_query($db, "SELECT office_position FROM users WHERE office_position = '$keyId' ");
		if (mysqli_num_rows($run)>0) {
			$msg = alertMsg("There are users in this designation, change their designation first.", "danger");
		}else{
			// delete useraccess
			mysqli_query($db, "DELETE FROM useraccess WHERE id = $keyId ");
			header('location: others_adds.php?page=useraccess');
		}
	}
	// update user access
	if (isset($_POST['access_ctrl'])) {
		$id = $_POST['access_ctrl']; $btnstatus = allData('useraccess', $id, 'access_ctrl');
		if($btnstatus){mysqli_query($db,"UPDATE useraccess SET access_ctrl=0 WHERE id='$id' ");}
		else{mysqli_query($db,"UPDATE useraccess SET access_ctrl=1 WHERE id='$id' ");}
		header("location: others_adds.php?page=useraccess");
	}if (isset($_POST['bin_ctrl'])) {
		$id = $_POST['bin_ctrl']; $btnstatus = allData('useraccess', $id, 'bin_ctrl');
		if($btnstatus){mysqli_query($db,"UPDATE useraccess SET bin_ctrl=0 WHERE id='$id' ");}
		else{mysqli_query($db,"UPDATE useraccess SET bin_ctrl=1 WHERE id='$id' ");}
		header("location: others_adds.php?page=useraccess");
	}if (isset($_POST['vessel_ctrl'])) {
		$id = $_POST['vessel_ctrl']; $btnstatus = allData('useraccess', $id, 'vessel_ctrl');
		if($btnstatus){mysqli_query($db,"UPDATE useraccess SET vessel_ctrl=0 WHERE id='$id' ");}
		else{mysqli_query($db,"UPDATE useraccess SET vessel_ctrl=1 WHERE id='$id' ");}
		header("location: others_adds.php?page=useraccess");
	}if (isset($_POST['forwading_ctrl'])) {
		$id = $_POST['forwading_ctrl']; $btnstatus = allData('useraccess', $id, 'forwading_ctrl');
		if($btnstatus){mysqli_query($db,"UPDATE useraccess SET forwading_ctrl=0 WHERE id='$id' ");}
		else{mysqli_query($db,"UPDATE useraccess SET forwading_ctrl=1 WHERE id='$id' ");}
		header("location: others_adds.php?page=useraccess");
	}if (isset($_POST['thirdparty_ctrl'])) {
		$id = $_POST['thirdparty_ctrl']; $btnstatus = allData('useraccess', $id, 'thirdparty_ctrl');
		if($btnstatus){mysqli_query($db,"UPDATE useraccess SET thirdparty_ctrl=0 WHERE id='$id' ");}
		else{mysqli_query($db,"UPDATE useraccess SET thirdparty_ctrl=1 WHERE id='$id' ");}
		header("location: others_adds.php?page=useraccess");
	}if (isset($_POST['user_ctrl'])) {
		$id = $_POST['user_ctrl']; $btnstatus = allData('useraccess', $id, 'user_ctrl');
		if($btnstatus){mysqli_query($db,"UPDATE useraccess SET user_ctrl=0 WHERE id='$id' ");}
		else{mysqli_query($db,"UPDATE useraccess SET user_ctrl=1 WHERE id='$id' ");}
		header("location: others_adds.php?page=useraccess");
	}if (isset($_POST['others_ctrl'])) {
		$id = $_POST['others_ctrl']; $btnstatus = allData('useraccess', $id, 'others_ctrl');
		if($btnstatus){mysqli_query($db,"UPDATE useraccess SET others_ctrl=0 WHERE id='$id' ");}
		else{mysqli_query($db,"UPDATE useraccess SET others_ctrl=1 WHERE id='$id' ");}
		header("location: others_adds.php?page=useraccess");
	}

	// update designation
	if (isset($_POST['updatedesignation'])) {
		$designation = $_POST['designation']; $id = $_POST['userid'];
		$run = mysqli_query($db, "UPDATE users SET office_position = '$designation' WHERE id = '$id' ");
		if ($run) {$msg = alertMsg("Designation Updated Successfully.", "success");}
		else{$msg = alertMsg("Couldn't Update Designation.", "danger");}
	}

	// add balance
	if (isset($_POST['addbalance'])) {
		$balance = $_POST['balance']; $id = $_POST['userid'];
		$available = $balance + allData('users', $id, 'balance');
		$run = mysqli_query($db, "UPDATE users SET balance = '$available' WHERE id = '$id' ");
		if ($run) {$msg = alertMsg("Balance Added Successfully.", "success");}
		else{$msg = alertMsg("Couldn't Add Balance.", "danger");}
	}

	// makevesselpayment
	if (isset($_GET['payment']) && $_GET['payment'] == "yes") {
		$id = $my['id']; $balance = $my['balance']; $vsl_num = $_GET['forwadingpage'];
		if ($balance < 4000) {
			$msg = alertMsg("Not enough balance, please recharge!", "danger");
		}else{
			$payment = $balance - 4000;
			// update user balance
			mysqli_query($db, "UPDATE users SET balance = '$payment' WHERE id = '$id' ");
			// make payment
			mysqli_query($db, "UPDATE vessels SET payment = 'paid' WHERE id = '$vsl_num' ");
			header("location: vessel_details.php?forwadingpage=".$vsl_num);
		}
		// $available = $balance + allData('users', $id, 'balance');
		// $run = mysqli_query($db, "UPDATE users SET balance = '$available' WHERE id = '$id' ");
		// if ($run) {$msg = alertMsg("Balance Added Successfully.", "success");}
		// else{$msg = alertMsg("Couldn't Add Balance.", "danger");}
	}


	// create company
	//add users
	if (isset($_POST['createcompany'])) {
		$companyname = strtoupper(mysqli_real_escape_string($db, $_POST['companyname']));
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$telephone = mysqli_real_escape_string($db, $_POST['telephone']);
		$address = mysqli_real_escape_string($db, $_POST['address']);
		$port = mysqli_real_escape_string($db, $_POST['port']);
		$package = mysqli_real_escape_string($db, $_POST['package']);
		$adminid = $my['id']; $today = date('d-m-Y');

		$sql = "
			INSERT INTO companies(adminid, companyname, companymoto, email, telephone, address, port, templet, package, timereset, birthday, status) VALUES('$adminid', '$companyname', '', '$email', '$telephone', '$address', '$port', 'default', '$package', '$today',  NOW(), 'active')
		";

		$run=mysqli_query($db,$sql);if($run){$msg=alertMsg("Company Created Successfully!","success");}
		if ($run) {
			$companyid = lastData('companies', 'id');
			$userup = mysqli_query($db, "UPDATE users SET companyid = '$companyid' WHERE id = '$adminid' ");
			$compu = mysqli_query($db, "UPDATE useraccess SET companyid = '$companyid' WHERE userid = '$adminid' ");

			if (!$userup) {$msg = alertMsg("Couldn't Update users", "danger"); echo "Somethins Wrong user";}
			if (!$compu) {$msg = alertMsg("Couldn't Update useraccess", "danger"); echo "Somethins Wrong com";}

			// Create company path for forwadings
			$path = "forwadings/auto_forwardings/templets/".$companyid."/";
			// Create folder if not exist, then save the file to that path
			createpath($path);
			header('location: index.php');
		} else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
	}
	// updatecompany
	if (isset($_POST['companyupdate'])) {
		$companyid = $my['companyid']; $userid = $my['id'];
		$companyain = strtoupper(mysqli_real_escape_string($db, $_POST['companyain']));
		$companyname = strtoupper(mysqli_real_escape_string($db, $_POST['companyname']));
		$address = strtoupper(mysqli_real_escape_string($db, $_POST['address']));
		$port = strtoupper(mysqli_real_escape_string($db, $_POST['port']));
		$sql = "UPDATE companies SET companyain = '$companyain', companyname = '$companyname', address = '$address', port = '$port' WHERE id = '$companyid' ";
		$run=mysqli_query($db,$sql);if($run){header('location: profile.php?userid='.$userid);}
	}


	//add users
	if (isset($_POST['addUsers'])) {
		$contact = "";
		$name = mysqli_real_escape_string($db, $_POST['name']);
		$office_position = mysqli_real_escape_string($db, $_POST['office_position']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$contact = mysqli_real_escape_string($db, $_POST['contact']);
		$password_1 = "000000"; $password = md5($password_1);
		$companyid = $my['companyid'];
		$sql = "INSERT INTO users(companyid, name, image, email, password, contact, office_position, status, balance, activation, registration_date) VALUES('$companyid','$name', 'user-1.jpg', '$email', '$password', '$contact', '$office_position', 'offline', '0', 'off', NOW())";
		$run=mysqli_query($db,$sql);if($run){$msg=alertMsg("User Added Successfully!","success");}
		else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
	}

	// user action || user acitvation
	if (isset($_GET['useraction'])) {
		$action = $_GET['useraction']; $id = $_GET['userid'];

		if($action=="on"){
			mysqli_query($db,"UPDATE users SET activation='on',status='online' WHERE id='$id' ");
			header('location: profile.php');
		}elseif($action=="off"){
			mysqli_query($db,"UPDATE users SET activation='off',status='offline' WHERE id='$id' ");
			header('location: profile.php');
		}elseif($action=="delete"){
			mysqli_query($db,"UPDATE users SET activation='delete',status='offline' WHERE id='$id' ");
			header('location: profile.php');
		}elseif($action=="deleteuser"){
			$id = $_SESSION['id']; $cid = $_SESSION['companyid']; 
			// $status = allData('users', $id, 'power');
			mysqli_query($db,"UPDATE users SET activation='delete',status='offline' WHERE id='$id' ");
			// if ($power == "admin") {
			// 	$sql = "UPDATE company SET status='delete' WHERE id = '$id' AND companyid = '$cid' ";
			// 	mysqli_query($db, "UPDATE users SET activation = 'delete',status='offline' WHERE companyid = '$cid' ");
			// 	mysqli_query($db,$sql);
			// }
		}
	}
	

	//add consignee
	if (isset($_POST['addConsignee'])) {
		$userid = $my['id']; $companyid = $my['companyid'];
		$consigneeName = mysqli_real_escape_string($db, $_POST['consigneeName']);
		$binnumber = mysqli_real_escape_string($db, $_POST['binnumber']);
		$checkbin = mysqli_query($db, "SELECT bin FROM bins WHERE bin = '$binnumber' ");
		if(mysqli_num_rows($checkbin)>0){$msg=alertMsg("Sorry, Bin Number Already Exist!","danger");}
		else{
			$sql="INSERT INTO bins(companyid, userid, name,type,bin, status, timedate)VALUES('$companyid', '$userid', '$consigneeName','IMPORTER','$binnumber', 'unapproved', NOW())";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Consignee Added Successfully!", "danger");}
			else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
		}	
	}

	//edit consignee
	if (isset($_POST['editConsignee'])) {
		$consigneeId = mysqli_real_escape_string($db, $_POST['consigneeId']);
		$consigneeName = mysqli_real_escape_string($db, $_POST['consigneeName']);
		$binnumber = mysqli_real_escape_string($db, $_POST['binnumber']);
		$sql="UPDATE bins SET name='$consigneeName',bin='$binnumber' WHERE id='$consigneeId' ";
		$run = mysqli_query($db, $sql); 
		if($run){ $msg = alertMsg("Consignee Updated Successfully!", "success"); } 
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	}

	// delete consignee
	if (isset($_GET['delConsignee'])) {
		$delConsignee = $_GET['delConsignee'];
		$sql = "DELETE FROM bins WHERE id = $delConsignee ";
		if(mysqli_query($db,$sql)){$msg=alertMsg("Consignee Deleted Successfully!","success");}
	}

	//add cnf
	if (isset($_POST['addCnf'])) {
		$companyid = $my['companyid']; $userid = $my['id'];
		$cnfName = mysqli_real_escape_string($db, $_POST['cnfName']);
		$cnfEmail = mysqli_real_escape_string($db, $_POST['cnfEmail']);
		$sql = "INSERT INTO cnf(companyid, userid, name, email, status, timedate) VALUES('$companyid', '$userid', '$cnfName','$cnfEmail', 'unapproved', NOW())";
		$run=mysqli_query($db,$sql);if($run){$msg=alertMsg("Cnf Added Successfully!","success");}
		else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
		// header('location: 3rd_parties.php?page=cnf');
	}

	//edit cnf
	if (isset($_POST['editCnf'])) {
		$cnfId = mysqli_real_escape_string($db, $_POST['cnfId']);
		$cnfName = mysqli_real_escape_string($db, $_POST['cnfName']);
		$cnfEmail = mysqli_real_escape_string($db, $_POST['cnfEmail']);
		$sql = "UPDATE cnf SET name = '$cnfName', email = '$cnfEmail' WHERE id = '$cnfId' ";
		if(mysqli_query($db, $sql)){ $msg = alertMsg("CNF Updated Successfully!", "success");}
		else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	}
	// approve cnf
	if (isset($_GET['cnfaprvid'])) {
		$cnfaprvid = $_GET['cnfaprvid'];
		$sql = "UPDATE cnf SET status = 'approved' WHERE id = '$cnfaprvid' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("CNF Approved Successfully!", "success");}
		header('location: usercontrols.php?page=cnfapproval');
	}
	// delete cnf
	if (isset($_GET['delCnf'])) {
		$delCnf = $_GET['delCnf'];
		$sql = "DELETE FROM cnf WHERE id = $delCnf ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("CNF Deleted Successfully!", "success");}
		header('location: 3rd_parties.php?page=cnf');
	}

	//add stevedore
	if (isset($_POST['addStevedore'])) {
		$companyid = $company['id']; $userid = $my['id'];
		$stevedoreName = strtoupper(mysqli_real_escape_string($db, $_POST['stevedoreName']));
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$sql = "INSERT INTO stevedore(companyid, userid, name, email, status, timedate) VALUES('$companyid', '$userid', '$stevedoreName', '$email', 'pending', NOW())";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Stevedore Added Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	//edit stevedore
	if (isset($_POST['editStevedore'])) {
		$stevedoreId = mysqli_real_escape_string($db, $_POST['stevedoreId']);
		$stevedoreName = strtoupper(mysqli_real_escape_string($db, $_POST['stevedoreName']));
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$sql = "UPDATE stevedore SET name = '$stevedoreName', email = '$email' WHERE id = '$stevedoreId' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Stevedore Updated Successfully: ".$email, "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	// approve stevedore
	if (isset($_GET['aprvstevedore'])) {
		$aprvstevedore = $_GET['aprvstevedore'];
		$sql = "UPDATE stevedore SET status = 'approved' WHERE id = '$aprvstevedore' ";
		if(mysqli_query($db, $sql)){$msg=alertMsg("Deleted Successfully!", "success");}
		header('location: usercontrols.php?page=stevedoreapproval');
	}

	// delete stevedore
	if (isset($_GET['delStevedore'])) {
		$delStevedore = $_GET['delStevedore'];
		$sql = "DELETE FROM stevedore WHERE id = $delStevedore ";
		if(mysqli_query($db, $sql)){$msg=alertMsg("Deleted Successfully!", "success");}
		header('location: 3rd_parties.php?page=stevedore');
	}




	//add agent
	if (isset($_POST['addAgent'])) {
		$c_name = strtoupper(mysqli_real_escape_string($db, $_POST['company_name']));
		$contact_person = strtoupper(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_1 = mysqli_real_escape_string($db, $_POST['contact_1']);
		$contact_2 = mysqli_real_escape_string($db, $_POST['contact_2']);

		$check = mysqli_query($db, "SELECT * FROM agent WHERE company_name = '$c_name' ");

		if(!empty($c_name)&& mysqli_num_rows($check)>0){$msg=alertMsg('Already Exist!','danger');}
		else{
			$sql = "INSERT INTO agent(company_name, contact_person, contact_1, contact_2) 
			VALUES('$c_name','$contact_person', '$contact_1', '$contact_2')";
			if(mysqli_query($db, $sql)){ $msg = alertMsg("Agent Added Successfully!", "success"); }
			else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
		}
	}

	//edit agent
	if (isset($_POST['editAgent'])) {
		$agentId = mysqli_real_escape_string($db, $_POST['agentId']);
		$company_name = strtoupper(mysqli_real_escape_string($db, $_POST['company_name']));
		$contact_person = strtoupper(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_1 = mysqli_real_escape_string($db, $_POST['contact_1']);
		$contact_2 = mysqli_real_escape_string($db, $_POST['contact_2']);
		$sql = "UPDATE agent SET company_name = '$company_name', contact_person = '$contact_person', contact_1 = '$contact_1', contact_2 = '$contact_2' WHERE id = '$agentId' ";
		if(mysqli_query($db,$sql)){$msg=alertMsg("Agent Updated Successfully!","success");}
		else{$msg=alertMsg("Something went wrong, Couldn't incert data!","danger");}
	}

	// delete agent
	if (isset($_GET['delAgent'])) {
		$delAgent = $_GET['delAgent'];
		$sql = "DELETE FROM agent WHERE id = $delAgent ";
		if(mysqli_query($db, $sql)){$msg=alertMsg("Agent Deleted Successfully!","success");}
		header('location: 3rd_parties.php?page=agents');
	}


	//add surveyor
	if (isset($_POST['addSurveyor'])) {
		$companyid = $my['companyid']; $userid = $my['userid'];
		$surveyor_name = mysqli_real_escape_string($db, $_POST['surveyor_name']);
		$contact_1 = mysqli_real_escape_string($db, $_POST['contact_1']);
		$contact_2 = mysqli_real_escape_string($db, $_POST['contact_2']);
		$sql = "INSERT INTO surveyors(companyid, userid, surveyor_name, contact_1, contact_2, status, timedate) VALUES('$companyid', '$userid', '$surveyor_name', '$contact_1', '$contact_2', 'unapproved', NOW())"; if(mysqli_query($db, $sql)){
			$msg = alertMsg("Surveyor Added Successfully!", "success");
		} else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	}

	//edit surveyor
	if (isset($_POST['editSurveyor'])) {
		$surveyorId = mysqli_real_escape_string($db, $_POST['surveyorId']);
		$surveyor_name = mysqli_real_escape_string($db, $_POST['surveyor_name']);
		$contact_1 = mysqli_real_escape_string($db, $_POST['contact_1']);
		$contact_2 = mysqli_real_escape_string($db, $_POST['contact_2']);
		$sql = "UPDATE surveyors SET surveyor_name = '$surveyor_name', contact_1 = '$contact_1', contact_2 = '$contact_2' WHERE id = '$surveyorId' ";if(mysqli_query($db, $sql)){
			$msg = alertMsg("Surveyor Updated Successfully!", "success");
		} else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
	}

	// approve surveyor
	if (isset($_GET['aprvserveyor'])) {
		$aprvserveyor = $_GET['aprvserveyor'];
		$sql = "UPDATE surveyors SET status = 'approved' WHERE id = '$aprvserveyor' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Surveyor Deleted Successfully!", "danger");}
		header('location: usercontrols.php?page=surveyorapproval');
	}

	// delete surveyor
	if (isset($_GET['delSurveyor'])) {
		$delSurveyor = $_GET['delSurveyor'];
		$sql = "DELETE FROM surveyors WHERE id = $delSurveyor ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Surveyor Deleted Successfully!", "danger");}
		header('location: 3rd_parties.php?page=surveyors');
	}



	//add cnf contact
	if (isset($_POST['addCnfContacts'])) {
		$company = mysqli_real_escape_string($db, $_POST['cnfcompanyId']);
		$contact_person = strtolower(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_2 = strtolower(mysqli_real_escape_string($db, $_POST['contact_2']));
		$contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
		$check = mysqli_query($db, "SELECT * FROM cnf_contacts WHERE name = '$contact_person' ");
		if (mysqli_num_rows($check) > 0) { $msg = alertMsg("Exist Already!", "danger"); }
		else{
			$sql = "INSERT INTO cnf_contacts(company, name, contact_2, contact, status) VALUES('$company', '$contact_person', '$contact_number', '', '')";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Added Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}// header('location: 3rd_parties.php?page=stevedore');	
		}
	}

	//edit cnf contact
	if (isset($_POST['editCnfContact'])) {
		$rowId = mysqli_real_escape_string($db, $_POST['rowId']);
		$contact_person = strtolower(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_2 = strtolower(mysqli_real_escape_string($db, $_POST['contact_2']));
		$contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
		$check = mysqli_query($db, "SELECT * FROM cnf_contacts WHERE name = '$contact_person' AND id != '$rowId' ");
		if (mysqli_num_rows($check) > 0) { $msg = alertMsg("Exist Already!", "danger"); }
		else{
			$sql = "UPDATE cnf_contacts SET name = '$contact_person', contact_2 = '$contact_2', contact = '$contact_number' WHERE id = '$rowId' ";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Updated Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't Update data!", "danger");}
			// header('location: 3rd_parties.php?page=stevedore');	
		}
	}

	// delete cnf contact
	if (isset($_GET['delCnfContact'])) {
		$delCnfContact = $_GET['delCnfContact']; $cnfview = $_GET['cnfview'];
		mysqli_query($db, "DELETE FROM cnf_contacts WHERE id = $delCnfContact ");
		header("location: 3rd_parties.php?cnfview=$cnfview");
	}


	//add consignee contact
	if (isset($_POST['addConsigneeContacts'])) {
		$company = mysqli_real_escape_string($db, $_POST['consigneecompanyId']);
		$contact_person = strtolower(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
		$check=mysqli_query($db,"SELECT * FROM consignee_contacts WHERE name = '$contact_person' ");
		if (mysqli_num_rows($check) > 0) { $msg = alertMsg("Exist Already!", "danger"); }
		else{
			$sql = "INSERT INTO consignee_contacts(company, name, contact, status) VALUES('$company', '$contact_person', '$contact_number', '')";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Added Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
			// header('location: 3rd_parties.php?page=stevedore');	
		}
	}

	//edit consignee contact
	if (isset($_POST['editConsigneeContact'])) {
		$rowId = mysqli_real_escape_string($db, $_POST['rowId']);
		$contact_person = strtolower(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
		$check=mysqli_query($db, "SELECT * FROM consignee_contacts WHERE name='$contact_person' ");
		if (mysqli_num_rows($check) > 0) { $msg = alertMsg("Exist Already!", "danger"); }
		else{
			$sql = "UPDATE consignee_contacts SET name = '$contact_person', contact = '$contact_number' WHERE id = '$rowId' ";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Updated Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't Update data!", "danger");}
			// header('location: 3rd_parties.php?page=stevedore');	
		}
	}

	// delete consignee contact
	if (isset($_GET['delConsigneeContact'])) {
		$delConsigneeContact = $_GET['delConsigneeContact']; $consigneeview = $_GET['consigneeview'];
		mysqli_query($db, "DELETE FROM consignee_contacts WHERE id = $delConsigneeContact ");
		header("location: 3rd_parties.php?consigneeview=$consigneeview");
	}


	//add stevedore contact
	if (isset($_POST['addStevedoreContacts'])) {
		$company = mysqli_real_escape_string($db, $_POST['stevedorecompanyId']);
		$contact_person = strtolower(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
		$check=mysqli_query($db,"SELECT * FROM stevedore_contacts WHERE name = '$contact_person' ");
		if (mysqli_num_rows($check) > 0) { $msg = alertMsg("Exist Already!", "danger"); }
		else{
			$sql = "INSERT INTO stevedore_contacts(company, name, contact, status) VALUES('$company', '$contact_person', '$contact_number', '')";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Added Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}// header('location: 3rd_parties.php?page=stevedore');	
		}
	}

	//edit stevedore contact
	if (isset($_POST['editStevedoreContact'])) {
		$rowId = mysqli_real_escape_string($db, $_POST['rowId']);
		$contact_person = strtolower(mysqli_real_escape_string($db, $_POST['contact_person']));
		$contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
		$check=mysqli_query($db, "SELECT * FROM stevedore_contacts WHERE name='$contact_person' ");
		if (mysqli_num_rows($check) > 0) { $msg = alertMsg("Exist Already!", "danger"); }
		else{
			$sql = "UPDATE stevedore_contacts SET name = '$contact_person', contact = '$contact_number' WHERE id = '$rowId' ";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Updated Successfully!", "success");}
			else{$msg = alertMsg("Something went wrong, Couldn't Update data!", "danger");}
			// header('location: 3rd_parties.php?page=stevedore');	
		}
	}

	// delete stevedore contact
	if (isset($_GET['delStevedoreContact'])) {
		$delStevedoreContact = $_GET['delStevedoreContact']; $stevedoreview = $_GET['stevedoreview'];
		mysqli_query($db, "DELETE FROM stevedore_contacts WHERE id = $delStevedoreContact ");
		header("location: 3rd_parties.php?stevedoreview=$stevedoreview");
	}


	//add loadport
	if (isset($_POST['addLoadport'])) {
		$userid = $my['id']; $companyid = $my['companyid'];
		$port_name = mysqli_real_escape_string($db, $_POST['port_name']);
		$port_code = mysqli_real_escape_string($db, $_POST['port_code']);
		$sql = "INSERT INTO loadport(companyid, userid, port_name, port_code, status, timedate) VALUES('$companyid', '$userid', '$port_name', '$port_code', 'unapproved', NOW())";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Port Added Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	//edit loadport
	if (isset($_POST['editLoadport'])) {
		$loadportId = mysqli_real_escape_string($db, $_POST['loadportId']);
		$port_name = mysqli_real_escape_string($db, $_POST['port_name']);
		$port_code = mysqli_real_escape_string($db, $_POST['port_code']);
		$sql = "UPDATE loadport SET port_name = '$port_name', port_code = '$port_code' WHERE id = '$loadportId' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Port Updated Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	// delete loadport
	if (isset($_GET['aprvldprt'])) {
		$aprvldprt = $_GET['aprvldprt'];
		$sql = "UPDATE loadport SET status = 'approved' WHERE id = '$aprvldprt' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Loadport Updated Successfully!", "success");}
		header('location: usercontrols.php?page=loadportapproval');
	}

	// delete loadport
	if (isset($_GET['delLoadport'])) {
		$delLoadport = $_GET['delLoadport'];
		$sql = "DELETE FROM loadport WHERE id = $delLoadport ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Port Deleted Successfully!", "success");}
		header('location: 3rd_parties.php?page=loadport');
	}




	//add nationality
	if (isset($_POST['addNationality'])) {
		$userid = $my['id']; $companyid = $my['companyid'];
		$port_name = mysqli_real_escape_string($db, $_POST['port_name']);
		$port_code = mysqli_real_escape_string($db, $_POST['port_code']);
		$sql = "INSERT INTO nationality(companyid, userid, port_name, port_code, status, timedate) VALUES('$companyid', '$userid', '$port_name', '$port_code', 'unapproved', NOW())";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Port Added Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	//edit nationality
	if (isset($_POST['editNationality'])) {
		$nationalityId = mysqli_real_escape_string($db, $_POST['nationalityId']);
		$port_name = mysqli_real_escape_string($db, $_POST['port_name']);
		$port_code = mysqli_real_escape_string($db, $_POST['port_code']);
		$sql = "UPDATE nationality SET port_name = '$port_name', port_code = '$port_code' WHERE id = '$nationalityId' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Port Updated Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	// approve nationality
	if (isset($_GET['aprvationality'])) {
		$aprvationality = $_GET['aprvationality'];
		$sql = "UPDATE nationality SET status = 'approved' WHERE id = '$aprvationality' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Nationality Approved Successfully!", "success");}
		header('location: usercontrols.php?page=nationalityapproval');
	}

	// delete nationality
	if (isset($_GET['delNationality'])) {
		$delNationality = $_GET['delNationality'];
		$sql = "DELETE FROM nationality WHERE id = $delNationality ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Port Deleted Successfully!", "success");}
		header('location: 3rd_parties.php?page=nationality');
	}

	//add surveycompany
	if (isset($_POST['addSurveycompany'])) {
		$companyid = $my['companyid']; $userid = $my['id'];
		$company_name = mysqli_real_escape_string($db, $_POST['company_name']);
		$email = mysqli_real_escape_string($db, $_POST['contact_person']);
		$officenum = mysqli_real_escape_string($db, $_POST['contact_number']);
		$sql = "INSERT INTO surveycompany(companyid, userid, company_name, email, officenum, status, timedate) VALUES('$companyid', '$userid', '$company_name', '$email', '$officenum', 'unapproved', NOW())";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Company Added Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}// header('location: 3rd_parties.php?page=stevedore');	
	}

	//edit surveycompany
	if (isset($_POST['editSurveycompany'])) {
		$companyid = $my['companyid']; $userid = $my['userid'];
		$surveycompanyId = mysqli_real_escape_string($db, $_POST['surveycompanyId']);
		$company_name = mysqli_real_escape_string($db, $_POST['company_name']);
		$contact_person = mysqli_real_escape_string($db, $_POST['contact_person']);
		$contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
		$sql = "UPDATE surveycompany SET company_name = '$company_name', email = '$contact_person', officenum = '$contact_number' WHERE id = '$surveycompanyId' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Company Updated Successfully!", "success");}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	// approve surveycompany
	if (isset($_GET['aprvsurveycompany'])) {
		$aprvsurveycompany = $_GET['aprvsurveycompany'];
		$sql = "UPDATE surveycompany SET status = 'approved' WHERE id = '$aprvsurveycompany' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Company Deleted Successfully!", "success");}
		header('location: usercontrols.php?page=surveycompanyapproval');
	}

	// delete surveycompany
	if (isset($_GET['delSurveycompany'])) {
		$delSurveycompany = $_GET['delSurveycompany'];
		$sql = "DELETE FROM surveycompany WHERE id = $delSurveycompany ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Company Deleted Successfully!", "success");}
		header('location: 3rd_parties.php?page=surveycompany');
	}



	// //add consignee to vessel
	// if (isset($_POST['addConsigneetovessel'])) {
	// 	// $msl_num = $_GET['vesselId']; $cnf = $_GET['addCnftovessel'];
	// 	$msl_num = mysqli_real_escape_string($db, $_POST['vesselId']);
	// 	$consignee = mysqli_real_escape_string($db, $_POST['consigneeId']);
	// 	$sql = "INSERT INTO vessels_consignee(msl_num, consignee) VALUES('$msl_num', '$consignee')";
	// 	if(mysqli_query($db, $sql)){$msg = alertMsg("Consignee Added Successfully!", "success");}
	// 	else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	// 	// header('location: vessel_details.php?msl_num=$msl_num');	
	// }

	// //delete consignee to vessel
	// if (isset($_POST['delConsigneetovessel'])) {
	// 	$id = mysqli_real_escape_string($db, $_POST['consigneeId']);
	// 	$sql = "DELETE FROM vessels_consignee WHERE id = '$id' ";
	// 	if(mysqli_query($db, $sql)){$msg = alertMsg("Consignee Added Successfully!", "success");}
	// 	else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	// 	// header('location: vessel_details.php?msl_num=$msl_num');	
	// }


	// if (isset($_POST['addVesselsCnf'])) {
	// 	$msl_num = mysqli_real_escape_string($db, $_POST['msl_num']);
	// 	$importer = mysqli_real_escape_string($db, $_POST['importer']);
	// 	$cnf = mysqli_real_escape_string($db, $_POST['cnfId']);
	// 	$check = mysqli_query($db, "
	// 		SELECT * FROM vessels_cnf WHERE msl_num = '$msl_num' AND importer = '$importer' 
	// 	");
	// 	if (mysqli_num_rows($check)>0) { $msg = alertMsg("Cnf already Exist here!", "danger");}
	// 	else{
	// 		$sql = "INSERT INTO vessels_cnf(msl_num, importer, cnf) VALUES('$msl_num', '$importer', '$cnf')";
	// 		if(mysqli_query($db, $sql)){ $msg = alertMsg("CNF Added Successfully!", "success"); }
	// 		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
	// 		// header('location: vessel_details.php?msl_num=$msl_num');	
	// 	}
	// }

	// update vessels_cnf
	if (isset($_POST['update_vessels_cnf'])) {
		$vsl_num = mysqli_real_escape_string($db, $_POST['vsl_num']);
		$thisrowId = mysqli_real_escape_string($db, $_POST['thisrowId']);
		$cnfId = mysqli_real_escape_string($db, $_POST['cnfId']);

		if (isset($_POST['importers'])) {
			$importers = $_POST['importers'];
			// Convert the importer list to a comma-separated string for SQL query
			$importerListString = "'" . implode("', '", $importers) . "'";
			// SQL query to delete importers not in the importer list
			$delsql = "UPDATE vessels_importer SET cnf = 0 WHERE vsl_num = '$vsl_num' AND cnf = '$cnfId' AND importer NOT IN ($importerListString)"; mysqli_query($db,$delsql);

			// indest vessels consignee
			foreach ($importers as $key =>  $importerId) {
		    	$update = mysqli_query($db, "UPDATE vessels_importer SET cnf = '$cnfId' WHERE importer = '$importerId' AND vsl_num = '$vsl_num' ");
		    }
		}$msg = alertMsg('Cnf Updated Successfully!', 'success');
	}

	// //delete cnf to vessel ** not completed yet
	// if (isset($_GET['delVesselsCnf'])) {
	// 	$id = $_GET['delVesselsCnf']; $msl_num = allData('vessels_cnf', $id, 'msl_num');
	// 	$delete = mysqli_query($db, "DELETE FROM vessels_cnf WHERE id = '$id' "); 
	// 	if($delete){$msg = alertMsg("CNF removed Successfully!", "success");}
	// 	else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	// 	header("location: vessel_details.php?edit=$msl_num");
	// }


	if (isset($_POST['addVesselsSurveyor'])) {
		$vsl_num = mysqli_real_escape_string($db, $_POST['vesselId']);
		$party = mysqli_real_escape_string($db, $_POST['party']);
		$survey_company = allData('vessels', $vsl_num, $party);
		$surveyorId = mysqli_real_escape_string($db, $_POST['surveyorId']);
		$survey_purpose = mysqli_real_escape_string($db, $_POST['survey_purpose']);

		// check if same surveyor is working in another company on same load/light purpose
		// check same surveyor, same purpose (load/light), different company
		$run_1 = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND surveyor = '$surveyorId' AND survey_purpose = '$survey_purpose' AND survey_company != '$survey_company' ");

		// check if already added surveyor or survey purpose (load/light) in same company
		// check: same company, same purpose(load/light), whatever surveyor
		$run_2 = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND survey_company = '$survey_company' AND survey_purpose = '$survey_purpose' AND surveyor != 0 ");
		if (mysqli_num_rows($run_1) > 0) {
			// $exist_surveyor = allDataUpdated('vessels_surveyor');
			$msg = alertMsg("Surveyor already exist in other party! can't add as $party.", "danger");
		}
		elseif (mysqli_num_rows($run_2) > 0) {
			$msg = alertMsg("Surveyor already exist! can't add new.", "danger");
		}
		else{
			$sql = "
				INSERT INTO vessels_surveyor(vsl_num, survey_party, survey_company, surveyor, survey_purpose) 
				VALUES('$vsl_num', '$party', '$survey_company', '$surveyorId', '$survey_purpose')
			";
			if(mysqli_query($db,$sql)){$msg=alertMsg("Surveyor Added Successfully!","success");}
			else{ $msg = alertMsg("Something went wrong, Couldn't incert data!", "danger"); }
		} // header('location: vessel_details.php?msl_num=$msl_num');	
	}


	// update vessels_surviour
	if (isset($_POST['update_vessels_surveyor'])) {
		$vsl_num = mysqli_real_escape_string($db, $_POST['vsl_num']);
		$thisrowId = mysqli_real_escape_string($db, $_POST['thisrowId']);
		$prevSurveyor = allData('vessels_surveyor', $thisrowId, 'surveyor');
		$survey_party = mysqli_real_escape_string($db, $_POST['party']);
		$surveyorId = mysqli_real_escape_string($db, $_POST['surveyorId']);
		$survey_company = allData('vessels', $vsl_num, $survey_party);
		$survey_purpose = mysqli_real_escape_string($db, $_POST['survey_purpose']);

		// check if same surveyor is working in another party on same load/light purpose
		// check: same surveyor, same purpose, same party
		$run_1 = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND surveyor = '$surveyorId' AND survey_purpose = '$survey_purpose' AND survey_party = '$survey_party' ");

		// check if already added surveyor or survey purpose (load/light) in same company
		// check: same party, same purpose, different surveyor.
		$run = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND survey_party = '$survey_party' AND survey_purpose = '$survey_purpose' AND id != '$thisrowId' ");
		if (mysqli_num_rows($run_1) > 0) {
			$delete = mysqli_query($db, "DELETE FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND surveyor = '$surveyorId' AND survey_purpose = '$survey_purpose' AND survey_party = '$survey_party' ");

			$update = mysqli_query($db, "UPDATE vessels_surveyor SET survey_party = '$survey_party', survey_company = '$survey_company', surveyor = '$surveyorId', survey_purpose = '$survey_purpose' WHERE id = '$thisrowId' ");
		}
		elseif (mysqli_num_rows($run) > 0) {
			$delete = mysqli_query($db, "DELETE FROM vessels_surveyor WHERE vsl_num = '$vsl_num' AND survey_party = '$survey_party' AND survey_purpose = '$survey_purpose' AND id != '$thisrowId' ");
			$update = mysqli_query($db, "UPDATE vessels_surveyor SET survey_party = '$survey_party', survey_company = '$survey_company', surveyor = '$surveyorId', survey_purpose = '$survey_purpose' WHERE id = '$thisrowId' ");
		}
		else{
			$update = mysqli_query($db, "UPDATE vessels_surveyor SET survey_party = '$survey_party', survey_company = '$survey_company', surveyor = '$surveyorId', survey_purpose = '$survey_purpose' WHERE id = '$thisrowId' ");
		}
	}
	// delete vessels surveyor
	if (isset($_GET['delVesselSurveyors'])) {
		$delVesselSurveyors = $_GET['delVesselSurveyors'];
		$run=mysqli_query($db,"DELETE FROM vessels_surveyor WHERE id=$delVesselSurveyors");
		if($run){$msg = alertMsg("Consignee Deleted Successfully!", "success"); }
		// header('location: vessel_details.php?edit=$delVesselSurveyors');
	}



	//add bins
	if (isset($_POST['addbin'])) {
		$userid = $my['userid']; $companyid = $my['companyid'];
		$bank_name = mysqli_real_escape_string($db, $_POST['bank_name']);
		$bank_type = mysqli_real_escape_string($db, $_POST['type']);
		$bin_num = mysqli_real_escape_string($db, $_POST['bin_num']);
		// check if member already sinked
    	$run1 = mysqli_query($db, "SELECT * FROM bins WHERE bin = '$bin_num' ");
    	if(empty($bank_type)){$msg = alertMsg("Please select bin type!", "danger");}
    	elseif(mysqli_num_rows($run1)>0){$msg=alertMsg("Bin Number Already Exist!", "danger");}
    	else{
    		$sql = "INSERT INTO bins(companyid, userid, name,type,bin, status, timedate) VALUES('$companyid', '$userid', '$bank_name', '$bank_type', '$bin_num', 'unapproved', NOW())";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Bin added successfully!", "success");}
			else{$msg = alertMsg("Please select bin type!", "danger");}
			// header('location: 3rd_parties.php?page=stevedore');	
    	}
	}

	//edit bins
	if (isset($_POST['editBankBin'])) {
		$binId = mysqli_real_escape_string($db, $_POST['binId']);
		$bank_name = mysqli_real_escape_string($db, $_POST['bank_name']);
		$bank_type = mysqli_real_escape_string($db, $_POST['type']);
		$bin_num = mysqli_real_escape_string($db, $_POST['bin_num']);
		$sql = "UPDATE bins SET name = '$bank_name', bin = '$bin_num' WHERE id = '$binId' ";
		if(mysqli_query($db, $sql)){$msg = alertMsg("Updated Successfully!", "success");}
		else{$msg = alertMsg("Couldn't update data!", "danger");}
		// header('location: 3rd_parties.php?page=stevedore');	
	}

	// approve bins
	if (isset($_GET['approvebin'])) {
		$approvebin = $_GET['approvebin'];
		$sql = "UPDATE bins SET status = 'approved' WHERE id = '$approvebin' ";
		if(mysqli_query($db, $sql)){$msg=alertMsg("Bin Approved!","success");} 
		header('location: usercontrols.php?page=binapproval');
	}

	// delete bins
	if (isset($_GET['del_bin'])) {
		$del_bin = $_GET['del_bin'];
		$sql = "DELETE FROM bins WHERE id = $del_bin ";
		if(mysqli_query($db, $sql)){$msg=alertMsg("Deleted Successfully!","success");} 
		header('location: bin_numbers.php');
	}


	// //add cargo
	// if (isset($_POST['addCargoConsigneewise'])) {
	// 	$vesselId = mysqli_real_escape_string($db, $_POST['vesselId']);
	// 	$msl_num = allData("vessels", $vesselId, "msl_num");
	// 	$loadport = mysqli_real_escape_string($db, $_POST['loadport']);
	// 	$quantity = mysqli_real_escape_string($db, $_POST['quantity']);
	// 	$cargokey = mysqli_real_escape_string($db, $_POST['cargokey']);
	// 	$cargo_bl_name = mysqli_real_escape_string($db, $_POST['cargo_bl_name']);
		
 //    	if(empty($vesselId)||empty($loadport)||empty($quantity)||empty($cargokey)||empty($cargo_bl_name)){$msg=alertMsg("Some field is empty!","danger");}
 //    	else{
 //    		$sql = "INSERT INTO vessels_cargo(msl_num, cargo_key, loadport, quantity, cargo_bl_name) VALUES('$msl_num', '$cargokey', '$loadport', '$quantity', '$cargo_bl_name')";
	// 		if(mysqli_query($db, $sql)){$msg=alertMsg("Added Successfully!","success");}
	// 		else{$msg=alertMsg("Couldn't incert data!","danger");}
	// 		// header('location: 3rd_parties.php?page=stevedore');	
 //    	}
	// }

	// //edit 
	// if (isset($_POST['updateCargoConsigneewise'])) {
	// 	$id = mysqli_real_escape_string($db, $_POST['id']);
	// 	$msl_num = mysqli_real_escape_string($db, $_POST['msl_num']);
	// 	$loadport = mysqli_real_escape_string($db, $_POST['loadport']);
	// 	$quantity = mysqli_real_escape_string($db, $_POST['quantity']);
	// 	$cargokey = mysqli_real_escape_string($db, $_POST['cargokey']);
	// 	$cargo_bl_name = mysqli_real_escape_string($db, $_POST['cargo_bl_name']);
	// 	$sql = "UPDATE vessels_cargo SET cargo_key = '$cargokey', loadport = '$loadport', quantity = '$quantity', cargo_bl_name = '$cargo_bl_name' WHERE id = '$id' ";
	// 	if(mysqli_query($db, $sql)){$msg=alertMsg("Updated successfully!","success");}
	// 	else{$msg=alertMsg("Couldn't update data!","danger");}
	// 	// header('location: 3rd_parties.php?page=stevedore');	
	// }

	// // delete 
	// if (isset($_GET['delVesselCargoCon'])) {
	// 	$del = $_GET['delVesselCargoCon']; $msl_num = $_GET['edit'];
	// 	$run = mysqli_query($db, "DELETE FROM vessels_cargo WHERE id = $del ");
	// 	if($run){$msg=alertMsg("Deleted successfully!","success");} 
	// 	header("location: vessel_details.php?edit=$msl_num");
	// }



	// // destroy all data
	// if (isset($_GET['destroy']) && $_GET['destroy'] == 'destroy') {
	// 	// get a backup of database
	// 	$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=multiport', 'root', '');
	// 	$file = 'databasebackup'.date("Y-m-d-H-i-s").'.sql';
	// 	$dump->start('inc/db_backups/'.$file);
	// 	$sql = "INSERT INTO backups(file,date)VALUES('$file', NOW())";
	// 	$run = mysqli_query($db, $sql);

	// 	if ($run) {
	// 		// Delete process
	// 		$sql = "
	// 			TRUNCATE TABLE `agent`;
	// 			/*TRUNCATE TABLE `bins`; */
	// 			TRUNCATE TABLE `cargokeys`;  
	// 			TRUNCATE TABLE `cnf`;  
	// 			TRUNCATE TABLE `cnf_contacts`; 
	// 			TRUNCATE TABLE `consignee_contacts`; 
	// 			TRUNCATE TABLE `loadport`; 
	// 			TRUNCATE TABLE `stevedore`; 
	// 			TRUNCATE TABLE `stevedore_contacts`; 
	// 			TRUNCATE TABLE `surveycompany`; 
	// 			TRUNCATE TABLE `surveyors`; 
	// 			/*TRUNCATE TABLE `users`; */
	// 			TRUNCATE TABLE `vendor_contacts`; 
	// 			TRUNCATE TABLE `vessels`; 
	// 			TRUNCATE TABLE `vessels_bl`; 
	// 			TRUNCATE TABLE `vessels_cargo`;  
	// 			TRUNCATE TABLE `vessels_importer`; 
	// 			TRUNCATE TABLE `vessels_surveyor`; 
	// 			TRUNCATE TABLE `vessel_details`; 
	// 		";
	// 		if (mysqli_multi_query($db,$sql)) {
	// 			$msg=alertMsg("Data Destroyed!","success");
	// 			header('location: index.php');
	// 		}else{$msg=alertMsg("Couldn't destroy any data!","danger");}
	// 	} else{$msg=alertMsg("Couldn't Backup data!","danger");}
	// }


	// Backup database
	if (isset($_POST['backup_database'])) {
		// get a backup of database
		$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=multiport', 'root', '');
		$file = 'databasebackup'.date("Y-m-d-H-i-s").'.sql';
		$dump->start('inc/db_backups/'.$file);
		// INSERT SQL FILE NAME TO DATABASE
		$sql = "INSERT INTO backups(file,date)VALUES('$file', NOW())";
		$run = mysqli_query($db, $sql);
	}

	// Restore Database
	if (isset($_POST['restore_database'])) {
		// Delete all table before restore database
		$sql = "
			TRUNCATE TABLE `agent`;
			TRUNCATE TABLE `bins`;
			TRUNCATE TABLE `cargokeys`;  
			TRUNCATE TABLE `cnf`;  
			TRUNCATE TABLE `cnf_contacts`; 
			TRUNCATE TABLE `consignee_contacts`; 
			TRUNCATE TABLE `loadport`;
			TRUNCATE TABLE `passwords`;
			TRUNCATE TABLE `stevedore`; 
			TRUNCATE TABLE `stevedore_contacts`; 
			TRUNCATE TABLE `surveycompany`; 
			TRUNCATE TABLE `surveyors`; 
			TRUNCATE TABLE `users`;
			TRUNCATE TABLE `vessels`; 
			TRUNCATE TABLE `vessels_cargo`;
			TRUNCATE TABLE `vessels_importer`;  
			TRUNCATE TABLE `vessels_surveyor`; 
			TRUNCATE TABLE `vessel_details`; 
		";
		if ($db->multi_query($sql) === TRUE) {
			// $file = "databasebackup2023-01-21-13-16-00.sql";
			$file = lastData('backups', 'file');
			$filePath = "inc/db_backups/$file";
			$response = restoreMysqlDB($filePath);
		}else{$msg=alertMsg("Couldn't Destroy database before restore!","danger");}
	}

	if (isset($_GET['restore_database'])) {
		// Delete all table before restore database
		// List of tables to drop
		$tables = [
		    'agent', 'bins', 'cargokeys', 'cnf', 'cnf_contacts', 'consignee_contacts',
		    'loadport', 'passwords', 'stevedore', 'stevedore_contacts', 'surveycompany',
		    'surveyors', 'users', 'vessels', 'vessels_cargo', 'vessels_importer',
		    'vessels_surveyor', 'vessel_details'
		];

		$error = '';

		// Drop each table
		foreach ($tables as $table) {
		    $dropQuery = "DROP TABLE IF EXISTS `$table`";
		    if (!mysqli_query($db, $dropQuery)) {
		        $error .= "Error dropping table `$table`: " . mysqli_error($db) . "\n";
		    }
		}

		// Check if there were errors in dropping tables
		if ($error) {
		    $response = array("type" => "error", "message" => $error);
		} else {
			// $file = "databasebackup2024-07-24-17-30-25.sql";
			$id = $_GET['restore_database'];
		    $file = allData("backups",$id,"file");
		    $filePath = "inc/db_backups/$file";
		    if (restoreMysqlDB($filePath)) {
		    	$msg = alertMsg("Restored Successfully", "success");
		    }
		}
		// Output response
		// echo json_encode($response);

	}
	// Delete Backups
	if (isset($_GET['delbackups'])) {
		$id = $_GET['delbackups'];
		$file = allData('backups', $id, 'file'); $filePath = "inc/db_backups/$file";
		if (unlink($filePath) && mysqli_query($db, "DELETE FROM backups WHERE id = '$id' ")) {
			$msg = alertMsg("Data Deleted Successfully!", "success");
		}
	}


	// filter process
	if (isset($_POST['filtervsl'])) {
		// set all the variables to empty
		$fltrfrom = $fltrto = $fltrrepresentative = $fltrimporter = $fltrbank = $fltrcargo = $fltrstevedore = $dates = $fltrportcode = $fltrsurveyor = $fltrsurveycompany = $fltrseventyeight = $fltrkutubdia = $fltrouter = $fltrcustom = $fltrqurentine = $fltrlightdues = $fltrcrew = $fltrgrab = $fltrfender = $fltrwater = $fltrpiloting = $fltrsscec = $fltregm = $fltrpsc = $fltropa = "";

		// get all the variables from form
		$fltrrepresentative = mysqli_real_escape_string($db, $_POST['representative']);
		if (isset($_POST['importer'])) {$fltrimporter = $_POST['importer'];}
		if (isset($_POST['bank'])) {$fltrbank = $_POST['bank'];}
		if (isset($_POST['loadport'])) {$fltrportcode = $_POST['loadport'];}
		if (isset($_POST['cargo'])) {$fltrcargo = $_POST['cargo'];}
		if (isset($_POST['seventyeight'])) {$fltrseventyeight = $_POST['seventyeight'];}

		// checkbox start
		if (isset($_POST['kutubdia'])) {$fltrkutubdia = $_POST['kutubdia'];}
		if (isset($_POST['outer'])) {$fltrouter = $_POST['outer'];}
		if (isset($_POST['custom_visited'])) {$fltrcustom = $_POST['custom_visited'];}
		if (isset($_POST['qurentine_visited'])) {$fltrqurentine = $_POST['qurentine_visited'];}
		if (isset($_POST['psc_visited'])) {$fltrpsc = $_POST['psc_visited'];}
		if (isset($_POST['multiple_lightdues'])) {$fltrlightdues = $_POST['multiple_lightdues'];}
		if (isset($_POST['crew_change'])) {$fltrcrew = $_POST['crew_change'];}
		if (isset($_POST['has_grab'])) {$fltrgrab = $_POST['has_grab'];}
		if (isset($_POST['fender'])) {$fltrfender = $_POST['fender'];}
		if (isset($_POST['fresh_water'])) {$fltrwater = $_POST['fresh_water'];}
		if (isset($_POST['piloting'])) {$fltrpiloting = $_POST['piloting'];}
		if (isset($_POST['sscec'])) {$fltrsscec = $_POST['sscec'];}
		if (isset($_POST['egm'])) {$fltregm = $_POST['egm'];}
		// checkbox end

		$fltrstevedore = mysqli_real_escape_string($db, $_POST['stevedore']);
		$fltropa = mysqli_real_escape_string($db, $_POST['vsl_opa']);
		$fltrcnf = mysqli_real_escape_string($db, $_POST['cnf']);
		$fltrsurveyor = mysqli_real_escape_string($db, $_POST['surveyors']);
		$fltrsurveycompany = mysqli_real_escape_string($db, $_POST['surveycompanies']);
		if (isset($_POST['frm_date']) && isset($_POST['to_date'])) {
			$frm_date = mysqli_real_escape_string($db, $_POST['frm_date']);
			$to_date = mysqli_real_escape_string($db, $_POST['to_date']);
			$dates =  explode(",", $frm_date.",".$to_date);
			// print_r($dates);
		}

		// set the array tor filter
		$query = array(
			'fltrrepresentative' => $fltrrepresentative,
			'fltrimporter' => $fltrimporter,
			'fltrbank' => $fltrbank,
			'fltrportcode' => $fltrportcode,
			'fltrcargo' => $fltrcargo,
			'fltrseventyeight' => $fltrseventyeight,

			'fltrkutubdia' => $fltrkutubdia,
			'fltrouter' => $fltrouter,
			'fltrcustom' => $fltrcustom,
			'fltrqurentine' => $fltrqurentine,
			'fltrpsc' => $fltrpsc,
			'fltrlightdues' => $fltrlightdues,
			'fltrcrew' => $fltrcrew,
			'fltrgrab' => $fltrgrab,
			'fltrfender' => $fltrfender,
			'fltrwater' => $fltrwater,
			'fltrpiloting' => $fltrpiloting,
			'fltrsscec' => $fltrsscec,
			'fltregm' => $fltregm,

			'fltrstevedore' => $fltrstevedore,
			'fltropa' => $fltropa,
			'fltrcnf' => $fltrcnf,
			'fltrsurveyor' => $fltrsurveyor,
			'fltrsurveycompany' => $fltrsurveycompany,
			'dates' => $dates
		); // now the queries passes through "allvessels($key, $query)" function in index page
	}

	// filter process
	if (isset($_POST['switchvsl'])) {
		// set all the variables to empty
		$firstvsl = $secondvsl = "";
		
		// after id 219, vessels bl input system added, can't swap below 219
		if (isset($_POST['firstvsl']) && isset($_POST['secondvsl']) && $_POST['firstvsl'] != $_POST['secondvsl'] && $_POST['firstvsl'] > 219 && $_POST['secondvsl'] > 219) {
			// get all the variables from form
			$id1st = mysqli_real_escape_string($db, $_POST['firstvsl']);
			$id2nd = mysqli_real_escape_string($db, $_POST['secondvsl']);
			$container = 'swapit';

			// $firstvsl = allData('vessels', $id1st, 'msl_num');
			$firstvsl = $id1st;
			$nm1st = allData('vessels', $id1st, 'vessel_name');
			// $secondvsl = allData('vessels', $id2nd, 'msl_num');
			$secondvsl = $id2nd;
			$nm2nd = allData('vessels', $id2nd, 'vessel_name');

			// 232 = swapit, 233 = 232, swapit = 233;
			// first switch the vessel_details
			$sql1 = "UPDATE vessel_details SET vsl_num = 'swapit' WHERE vsl_num = '$firstvsl' ";
			$sql2 = "UPDATE vessel_details SET vsl_num = '$firstvsl' WHERE vsl_num='$secondvsl' ";
			$sql3 = "UPDATE vessel_details SET vsl_num = '$secondvsl' WHERE vsl_num = 'swapit' ";
			mysqli_query($db, $sql1); mysqli_query($db, $sql2); mysqli_query($db, $sql3);

			// second switch the vessels_surveyor
			$sql4 = "UPDATE vessels_surveyor SET vsl_num = 'swapit' WHERE vsl_num = '$firstvsl' ";
			$sql5 = "UPDATE vessels_surveyor SET vsl_num='$firstvsl' WHERE vsl_num='$secondvsl' ";
			$sql6 = "UPDATE vessels_surveyor SET vsl_num ='$secondvsl' WHERE vsl_num = 'swapit' ";
			mysqli_query($db, $sql4); mysqli_query($db,$sql5); mysqli_query($db, $sql6);

			// third switch the vessels_bl
			$sql7 = "UPDATE vessels_bl SET $vsl_num = 'swapit' WHERE $vsl_num = '$firstvsl' ";
			$sql8 = "UPDATE vessels_bl SET $vsl_num = '$firstvsl' WHERE $vsl_num = '$secondvsl' ";
			$sql9 = "UPDATE vessels_bl SET $vsl_num = '$secondvsl' WHERE $vsl_num = 'swapit' ";
			mysqli_query($db, $sql7); mysqli_query($db, $sql8); mysqli_query($db, $sql9);

			// forth switch the vessels
			$sql10 = "UPDATE vessels SET vessel_name = '$nm2nd' WHERE id = '$id1st' ";
			$sql11 = "UPDATE vessels SET vessel_name = '$nm1st' WHERE id = '$id2nd' ";
			mysqli_query($db, $sql10); mysqli_query($db, $sql11);
		}else{$msg = alertMsg("Select Both Vessel", "danger");}
	}


	//update vessel_details || ship_perticular
	if (isset($_POST['ship_perticular_update'])) {

		// $vsl_imo = $vsl_call_sign = $vsl_mmsi_number = $vsl_class = $vsl_nationality = 
		// $vsl_registry = $vsl_official_number = $vsl_nrt = $vsl_grt = $vsl_dead_weight = 
		// $vsl_breth = $vsl_depth = $vsl_loa = $vsl_pni = $vsl_owner_name = $vsl_owner_address = 
		// $vsl_owner_email = $vsl_operator_name = $vsl_operator_address = $year_of_built = 
		// $number_of_hatches_cranes = $vsl_cargo_name = $vsl_cargo = $shipper_name = $shipper_address = 
		// $last_port = $capt_name = $number_of_crew = $packages_codes = $next_port = ""; 
		$vsl_imo = $thisvessel['imo'];
		$vsl_call_sign = $thisvessel['callsign'];
		$vsl_mmsi_number = $thisvessel['mmsi_number'];
		$vsl_class = $thisvessel['class'];
		$vsl_nationality = $thisvessel['nationalityid'];
		$vsl_registry = $thisvessel['registryid'];
		$vsl_official_number = $thisvessel['official_number'];
		$vsl_nrt = $thisvessel['rawnrt'];
		$vsl_grt = $thisvessel['rawgrt'];
		$vsl_dead_weight = $thisvessel['rawdead_weight'];
		$vsl_breth = $thisvessel['breth'];
		$vsl_depth = $thisvessel['depth'];
		$vsl_loa = $thisvessel['loa'];
		$vsl_pni = $thisvessel['pni'];
		$vsl_owner_name = $thisvessel['owner_name'];
		$vsl_owner_address = $thisvessel['owner_address'];
		$vsl_owner_email = $thisvessel['owner_email'];
		$vsl_operator_name = $thisvessel['operator_name'];
		$vsl_operator_address = $thisvessel['operator_address'];
		$year_of_built = $thisvessel['year_of_built'];
		$number_of_hatches_cranes = $thisvessel['number_of_hatches_cranes'];
		$vsl_cargo_name = $thisvessel['cargo_name'];
		$vsl_cargo = $thisvessel['cargo'];
		$shipper_name = $thisvessel['shipper_name'];
		$shipper_address = $thisvessel['shipper_address'];
		$last_port = $thisvessel['lastportid'];
		$capt_name = $thisvessel['capt_name'];
		$number_of_crew = $thisvessel['number_of_crew'];
		$packages_codes = $thisvessel['packages_codes'];
		$next_port = $thisvessel['next_port'];

		$ship_perticularId = mysqli_real_escape_string($db, $_POST['ship_perticularId']);
		$vsl_num = mysqli_real_escape_string($db, $_POST['vsl_num']);
		$msl_num = allData('vessels', $vsl_num, 'msl_num');

		if(isset($_POST['vsl_imo'])){
			$vsl_imo = mysqli_real_escape_string($db, $_POST['vsl_imo']);
		}
        if(isset($_POST['vsl_call_sign'])){
        	$vsl_call_sign = mysqli_real_escape_string($db, $_POST['vsl_call_sign']);
        }
        if(isset($_POST['vsl_mmsi_number'])){
        	$vsl_mmsi_number = mysqli_real_escape_string($db, $_POST['vsl_mmsi_number']);
        }
        if(isset($_POST['vsl_class'])){
        	$vsl_class = mysqli_real_escape_string($db, $_POST['vsl_class']);
        }
        if(isset($_POST['vsl_nationality'])){
        	$vsl_nationality = mysqli_real_escape_string($db, $_POST['vsl_nationality']);
        }
        if(isset($_POST['vsl_registry'])){
        	$vsl_registry = mysqli_real_escape_string($db, $_POST['vsl_registry']);
        }
        if(isset($_POST['vsl_official_number'])){
        	$vsl_official_number = mysqli_real_escape_string($db, $_POST['vsl_official_number']);
        }
        if(isset($_POST['vsl_nrt'])){
        	$vsl_nrt = mysqli_real_escape_string($db, $_POST['vsl_nrt']);
        }
        if(isset($_POST['vsl_grt'])){
        	$vsl_grt = mysqli_real_escape_string($db, $_POST['vsl_grt']);
        }
        if(isset($_POST['vsl_dead_weight'])){
        	$vsl_dead_weight = mysqli_real_escape_string($db, $_POST['vsl_dead_weight']);
        }
        if(isset($_POST['vsl_breth'])){
        	$vsl_breth = mysqli_real_escape_string($db, $_POST['vsl_breth']);
        }
        if(isset($_POST['vsl_depth'])){
        	$vsl_depth = mysqli_real_escape_string($db, $_POST['vsl_depth']);
        }
        if(isset($_POST['vsl_loa'])){
        	$vsl_loa = mysqli_real_escape_string($db, $_POST['vsl_loa']);
        }
        if(isset($_POST['vsl_pni'])){
        	$vsl_pni = mysqli_real_escape_string($db, $_POST['vsl_pni']);
        }
        if(isset($_POST['vsl_owner_name'])){
        	$vsl_owner_name = mysqli_real_escape_string($db, $_POST['vsl_owner_name']);
        }
        if(isset($_POST['vsl_owner_address'])){
        	$vsl_owner_address = mysqli_real_escape_string($db, $_POST['vsl_owner_address']);
        }
        if(isset($_POST['vsl_owner_email'])){
        	$vsl_owner_email = mysqli_real_escape_string($db, $_POST['vsl_owner_email']);
        }
        if(isset($_POST['vsl_operator_name'])){
        	$vsl_operator_name = mysqli_real_escape_string($db, $_POST['vsl_operator_name']);
        }
        if(isset($_POST['vsl_operator_address'])){
        	$vsl_operator_address = mysqli_real_escape_string($db, $_POST['vsl_operator_address']);
        }
        if(isset($_POST['year_of_built'])){
        	$year_of_built = mysqli_real_escape_string($db, $_POST['year_of_built']);
        }
        if(isset($_POST['number_of_hatches_cranes'])){
        	$number_of_hatches_cranes = mysqli_real_escape_string($db, $_POST['number_of_hatches_cranes']);
        }
        if(isset($_POST['vsl_nature'])){
        	$vsl_nature = mysqli_real_escape_string($db, $_POST['vsl_nature']);
        }
        if(isset($_POST['vsl_cargo'])){
        	$vsl_cargo = mysqli_real_escape_string($db, $_POST['vsl_cargo']);
        }
        if(isset($_POST['vsl_cargo_name'])){
        	$vsl_cargo_name = mysqli_real_escape_string($db, $_POST['vsl_cargo_name']);
        }
        if(isset($_POST['shipper_name'])){
        	$shipper_name = mysqli_real_escape_string($db, $_POST['shipper_name']);
        }
        if(isset($_POST['shipper_address'])){
        	$shipper_address = mysqli_real_escape_string($db, $_POST['shipper_address']);
        }
        if(isset($_POST['last_port'])){
        	$last_port = mysqli_real_escape_string($db, $_POST['last_port']);
        }
        if(isset($_POST['next_port'])){
        	$next_port = mysqli_real_escape_string($db, $_POST['next_port']);
        }
        if(isset($_POST['with_retention'])){
        	$with_retention = mysqli_real_escape_string($db, $_POST['with_retention']);
        }
        if(isset($_POST['capt_name'])){
        	$capt_name = mysqli_real_escape_string($db, $_POST['capt_name']);
        }
        if(isset($_POST['number_of_crew'])){
        	$number_of_crew = mysqli_real_escape_string($db, $_POST['number_of_crew']);
        }
        

        if (empty($_POST['packages_codes'])) {$packages_codes = "VR";}
        else{$packages_codes = mysqli_real_escape_string($db, $_POST['packages_codes']);}

        $workstatus = $thisvessel['workstatus'];
        if ($my['email'] == "shukurs920@gmail.com" || $my['email'] == "skturan2405@gmail.com") {
        	$workstatus = "done";
        }

        if (empty($with_retention)) { $with_retention = "IN-BALLAST"; }
        if (empty($vsl_nature)) { $vsl_nature = "BULK"; }

        $bl_cargo = $bl_cargokey = $bl_shippername = $bl_shipperaddress = $bl_loadport = $bl_issuedate = 0;
        


		$sql = "UPDATE vessel_details SET vsl_imo = '$vsl_imo', vsl_call_sign = '$vsl_call_sign', vsl_mmsi_number = '$vsl_mmsi_number', vsl_class = '$vsl_class', vsl_nationality = '$vsl_nationality', vsl_registry = '$vsl_registry', vsl_official_number = '$vsl_official_number', vsl_nrt = '$vsl_nrt', vsl_grt = '$vsl_grt', vsl_dead_weight = '$vsl_dead_weight', vsl_breth = '$vsl_breth', vsl_depth = '$vsl_depth', vsl_loa = '$vsl_loa', vsl_pni = '$vsl_pni', vsl_owner_name = '$vsl_owner_name', vsl_owner_address = '$vsl_owner_address', vsl_owner_email = '$vsl_owner_email', vsl_operator_name = '$vsl_operator_name', vsl_operator_address = '$vsl_operator_address', year_of_built = '$year_of_built', number_of_hatches_cranes = '$number_of_hatches_cranes', vsl_nature = '$vsl_nature', vsl_cargo = '$vsl_cargo', vsl_cargo_name = '$vsl_cargo_name', shipper_name = '$shipper_name', shipper_address = '$shipper_address', last_port = '$last_port', next_port = '$next_port', with_retention = '$with_retention', capt_name = '$capt_name', number_of_crew = '$number_of_crew', bl_cargo = '$bl_cargo', bl_cargokey = '$bl_cargokey', bl_shippername = '$bl_shippername', bl_shipperaddress = '$bl_shipperaddress', bl_loadport = '$bl_loadport', bl_issuedate = '$bl_issuedate', packages_codes = '$packages_codes' WHERE id = '$ship_perticularId' ";
		if(mysqli_query($db, $sql)){
			$msg = alertMsg("ship_perticular Successfully!", "success");
			mysqli_query($db, "UPDATE vessels SET workstatus = '$workstatus' WHERE id = '$vsl_num' ");
		}
		else{$msg = alertMsg("Something went wrong, Couldn't incert data!", "danger");}
	}


	// blinput
	//add bl
	if (isset($_POST['blinput'])) {
		$vsl_num = $_POST['vsl_num']; 
		$line_num = mysqli_real_escape_string($db, $_POST['line_num']);
		$bl_num = mysqli_real_escape_string($db, $_POST['bl_num']);
		$cargo_qty = mysqli_real_escape_string($db, $_POST['cargo_qty']);
		$cargokeyId = mysqli_real_escape_string($db, $_POST['cargokey']);
		$cargo_name = mysqli_real_escape_string($db, $_POST['cargo_name']);
		$shipper_name = mysqli_real_escape_string($db, $_POST['shipper_name']);
		$shipper_address = mysqli_real_escape_string($db, $_POST['shipper_address']);
		$receiver_id = mysqli_real_escape_string($db, $_POST['receiver_name']);
		$bank_id = mysqli_real_escape_string($db, $_POST['bank_name']);
		$load_port = mysqli_real_escape_string($db, $_POST['load_port']);
		$desc_port = mysqli_real_escape_string($db, $_POST['desc_port']);
		$issue_date = mysqli_real_escape_string($db, dbtimefotmat('d/m/Y', $_POST['issue_date'], 'Y-m-d'));
		// $arrived = dbtimefotmat('d/m/Y', $arrived, 'd-m-Y');

		// checkbox values
		$bl_cargo=$bl_cargokey=$bl_shippername=$bl_shipperaddress=$bl_loadport=$bl_issuedate=0;
		if(isset($_POST['bl_cargo'])){$bl_cargo = $_POST['bl_cargo'];}
		if(isset($_POST['bl_cargokey'])){$bl_cargokey = $_POST['bl_cargokey'];}
		if(isset($_POST['bl_shippername'])){$bl_shippername = $_POST['bl_shippername'];}
		if(isset($_POST['bl_shipperaddress'])){$bl_shipperaddress = $_POST['bl_shipperaddress'];}
		if(isset($_POST['bl_loadport'])){$bl_loadport = $_POST['bl_loadport'];}
		if(isset($_POST['bl_issuedate'])){$bl_issuedate = $_POST['bl_issuedate'];}

		// check if member already sinked
    	$run=mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND bl_num = '$bl_num' ");
    	if(empty($bl_num)){$msg = alertMsg("Enter Bl Number!", "danger");}
    	elseif(mysqli_num_rows($run)>0){$msg=alertMsg("Bl Number Already Exist!", "danger");}
    	else{
    		$sql = "INSERT INTO vessels_bl(vsl_num, line_num, bl_num, shipper_name, shipper_address, receiver_name, bank_name, load_port, desc_port, cargo_name, cargokeyId, cargo_qty, issue_date) VALUES('$vsl_num', '$line_num', '$bl_num', '$shipper_name', '$shipper_address', '$receiver_id', '$bank_id', '$load_port', '$desc_port', '$cargo_name', '$cargokeyId', '$cargo_qty', '$issue_date')";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Bl added successfully!", "success");}
			else{$msg = alertMsg("Please select bin type!", "danger");}
			// header('location: 3rd_parties.php?page=stevedore');	
    	}
    	// update vessel_details with bl_data
    	mysqli_query($db, "UPDATE vessel_details SET bl_cargo = '$bl_cargo', bl_cargokey = '$bl_cargokey', bl_shippername = '$bl_shippername', bl_shipperaddress = '$bl_shipperaddress', bl_loadport = '$bl_loadport', bl_issuedate = '$bl_issuedate' WHERE vsl_num = '$vsl_num' ");
	}


	//edit bl
	if (isset($_POST['blupdate'])) {
		$rawid = $_POST['blinputid']; $vsl_num = $_POST['vsl_num']; 
		$line_num = mysqli_real_escape_string($db, $_POST['line_num']);
		$bl_num = mysqli_real_escape_string($db, $_POST['bl_num']);
		$cargo_qty = mysqli_real_escape_string($db, $_POST['cargo_qty']);
		$cargo_name = mysqli_real_escape_string($db, $_POST['cargo_name']);
		$cargokeyId = mysqli_real_escape_string($db, $_POST['cargokey']);
		$shipper_name = mysqli_real_escape_string($db, $_POST['shipper_name']);
		$shipper_address = mysqli_real_escape_string($db, $_POST['shipper_address']);
		$receiver_id = mysqli_real_escape_string($db, $_POST['receiver_name']);
		$bank_id = mysqli_real_escape_string($db, $_POST['bank_name']);
		$load_port = mysqli_real_escape_string($db, $_POST['load_port']);
		$desc_port = mysqli_real_escape_string($db, $_POST['desc_port']);
		// $issue_date = mysqli_real_escape_string($db, $_POST['issue_date']);
		$issue_date = mysqli_real_escape_string($db, dbtimefotmat('d/m/Y', $_POST['issue_date'], 'Y-m-d'));
		if(!isset($issue_date)||empty($issue_date)){$issue_date=allData('vessels_bl',$rawid,'issue_date');}

		// checkbox values
		$bl_cargo=$bl_cargokey=$bl_shippername=$bl_shipperaddress=$bl_loadport=$bl_issuedate=0;
		if(isset($_POST['bl_cargo'])){$bl_cargo = $_POST['bl_cargo'];}
		if(isset($_POST['bl_cargokey'])){$bl_cargokey = $_POST['bl_cargokey'];}
		if(isset($_POST['bl_shippername'])){$bl_shippername = $_POST['bl_shippername'];}
		if(isset($_POST['bl_shipperaddress'])){$bl_shipperaddress = $_POST['bl_shipperaddress'];}
		if(isset($_POST['bl_loadport'])){$bl_loadport = $_POST['bl_loadport'];}
		if(isset($_POST['bl_issuedate'])){$bl_issuedate = $_POST['bl_issuedate'];}

		// check if member already sinked
    	$run=mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND bl_num = '$bl_num' AND id != '$rawid' ");
    	if(empty($bl_num)){$msg = alertMsg("Enter Bl Number!", "danger");}
    	elseif(mysqli_num_rows($run)>0){$msg=alertMsg("Bl Number Already Exist!", "danger");}
    	else{
    		// $sql = "INSERT INTO vessels_bl(msl_num, line_num, bl_num, shipper_name, shipper_address, receiver_name, bank_name, load_port, cargo_name, cargo_qty) VALUES('$msl_num', '$line_num', '$bl_num', '$shipper_name', '$shipper_address', '$receiver_id', '$bank_id', '$load_port', '$cargo_name', '$cargo_qty')";
    		$sql = "UPDATE vessels_bl SET bl_num = '$bl_num', shipper_name = '$shipper_name', shipper_address = '$shipper_address', receiver_name = '$receiver_id', bank_name = '$bank_id', load_port = '$load_port', desc_port = '$desc_port', cargo_name = '$cargo_name', cargokeyId = '$cargokeyId', cargo_qty = '$cargo_qty', issue_date = '$issue_date' WHERE id = '$rawid' ";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Bl Updated successfully!", "success");}
			else{$msg = alertMsg("Please select bin type!", "danger");}
			// header('location: 3rd_parties.php?page=stevedore');	
    	} 
    	// update vessel_details with bl_data
    	mysqli_query($db, "UPDATE vessel_details SET bl_cargo = '$bl_cargo', bl_cargokey = '$bl_cargokey', bl_shippername = '$bl_shippername', bl_shipperaddress = '$bl_shipperaddress', bl_loadport = '$bl_loadport', bl_issuedate = '$bl_issuedate' WHERE vsl_num = '$vsl_num' ");
	}






	//edit bl
	if (isset($_POST['doupdate'])) {
		$cnfId = "";
		$rawid = $_POST['doinputid']; $vsl_num = $_POST['vsl_num']; 
		$c_num = mysqli_real_escape_string($db, $_POST['c_num']);
		// $c_date = mysqli_real_escape_string($db, $_POST['c_date']);
		$c_date = mysqli_real_escape_string($db, dbtimefotmat('d/m/Y', $_POST['c_date'], 'Y-m-d'));
		$c_cargoname = mysqli_real_escape_string($db, $_POST['c_cargoname']);
		$cnfId = mysqli_real_escape_string($db, $_POST['cnfId']);
		$c_consignee = mysqli_real_escape_string($db, $_POST['c_consignee']);

		// check if member already sinked
    	$run=mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND c_num = '$c_num' AND id != '$rawid' ");
    	if(mysqli_num_rows($run)>0){$msg=alertMsg("C Number Already Exist!", "danger");}
    	elseif($cnfId == 0 || empty($cnfId)){$msg=alertMsg("CNF Missing! cnf: $cnfId", "danger");}
    	else{
    		$sql = "UPDATE vessels_bl SET c_num = '$c_num', c_date = '$c_date', c_cargoname = '$c_cargoname', cnf_name = '$cnfId', c_consignee = '$c_consignee' WHERE id = '$rawid' ";
			if(mysqli_query($db, $sql)){$msg = alertMsg("Do Updated successfully!", "success");}
			else{$msg = alertMsg("Something Went Wrong!", "danger");}
    	}
	}

	if (isset($_GET['bldelete'])) {
		$delid = $_GET['bldelete']; $vsl_num = $_GET['blinputs'];
		if (mysqli_query($db, "DELETE FROM vessels_bl WHERE id = '$delid' ")) {
			header("location: vessel_details.php?blinputs=$vsl_num");
		}else{$msg = alertMsg("Couldn't Delete Bl, Something Wrong!", "danger");}
	}


	//export forwadings
	if (isset($_POST['export_vsl_forwadings']) || isset($_GET['exportdo'])) {
		$year = date("Y"); $month = date("m"); $day = date("d");

		// these conditions are to get vsl_num to get vessel info to export forwadings
		if (isset($_POST['export_vsl_forwadings'])) {
			$btnVal = $_POST['export_vsl_forwadings']; $filename = "";
			$ship_perticularId = mysqli_real_escape_string($db, $_POST['ship_perticularId']);
			$vsl_num = mysqli_real_escape_string($db, $_POST['vsl_num']);
		}elseif (isset($_GET['exportdo'])) {
			$btnVal = ""; $filename = ""; $ship_perticularId = "";
			$vsl_num = $_GET['doinputs'];

			// do infos
	        $doId = $_GET['exportdo'];
			$run2 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE id = '$doId' ");
			$row2 = mysqli_fetch_assoc($run2); $c_cnfId = $row2['cnf_name'];
			$c_num = $row2['c_num']; $c_date = $row2['c_date']; $c_cargoname = $row2['c_cargoname'];
			$do_cargoname = $row2['cargo_name'];$do_cargoqty = $row2['cargo_qty'];
		}else{
			$btnVal = ""; $filename = ""; $ship_perticularId = "";
			$vsl_num = 124;
		}

		$run = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
        $row = mysqli_fetch_assoc($run);
        $ship_perticularId = $row['id']; 
        $vsl_imo = $row['vsl_imo'];
        $vsl_call_sign = $row['vsl_call_sign'];
        $vsl_mmsi_number = $row['vsl_mmsi_number'];
        $vsl_class = $row['vsl_class'];
        $vsl_nationality = $row['vsl_nationality'];
        $vsl_registry = $row['vsl_registry'];
        $vsl_official_number = $row['vsl_official_number'];
        $vsl_nrt = $row['vsl_nrt'];
        $vsl_grt = $row['vsl_grt'];
        $vsl_dead_weight = $row['vsl_dead_weight'];
        $vsl_breth = $row['vsl_breth'];
        $vsl_depth = $row['vsl_depth'];
        $vsl_loa = $row['vsl_loa'];
        $vsl_pni = $row['vsl_pni'];
        $vsl_owner_name = $row['vsl_owner_name'];
        $vsl_owner_address = $row['vsl_owner_address'];
        $vsl_owner_email = $row['vsl_owner_email'];
        $vsl_operator_name = $row['vsl_operator_name'];
        $vsl_operator_address = $row['vsl_operator_address'];
        $year_of_built = $row['year_of_built'];
        $number_of_hatches_cranes = $row['number_of_hatches_cranes'];
        $vsl_nature = $row['vsl_nature'];
        $vsl_cargo = $row['vsl_cargo'];
        $vsl_cargo_name = $row['vsl_cargo_name'];
        $shipper_name = $row['shipper_name'];
        $shipper_address = $row['shipper_address'];
        if (!empty($row['last_port'])) {$last_port = $row['last_port'];}
        else{$last_port = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'loadport');}
        $next_port = $row['next_port'];
        $with_retention = $row['with_retention'];
        $capt_name = $row['capt_name'];
        $number_of_crew = $row['number_of_crew'];
        $packages_codes = $row['packages_codes'];



        $run1 = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
        $row1 = mysqli_fetch_assoc($run1); $vessel = $row1['vessel_name']; 
        $rep_id = $row1['representative']; $stevedore_id = $row1['stevedore'];
        $arr_date = $row1['arrived'];
        $stevedore_name = allData('stevedore', $stevedore_id, 'name');

        if(!empty($arr_date)){$arrived = date('d.m.Y', strtotime($arr_date));}
        else{$arrived = "";}
        if(!empty($row1['sailing_date'])){$sailing_date=date('d.m.Y',strtotime($row1['sailing_date']));}
        else{$sailing_date = "";}

        $rotation = $row1['rotation'];
        $rotation_2 = substr($rotation,7)." / ".substr($rotation,0,-7);
        if (empty($rotation)) { $rotation = date("Y")." / "; $rotation_2 = " -_____/".date("Y"); }

        $lstdaynextmonth = date('t',strtotime('next month'));
        $nextmonth = date('m', strtotime('+1 month', strtotime($row1['arrived'])));
        $inctxsaildate = $lstdaynextmonth.".".$nextmonth.".".$year;


        // export log update
		if(isset($forwading[$btnVal])){exportlogs($vsl_num, $forwading[$btnVal]);} 
		else{exportlogs($vsl_num, $btnVal);}


        // export forwadings
		// file covers
		if($btnVal == "mainfilecover"){ export_forwading($vsl_num, $btnVal); }
		elseif($btnVal == "accfilecover"){ export_forwading($vsl_num, $btnVal); }
		elseif($btnVal=="file_covers"){
			export_forwading($vsl_num,"mainfilecover");
			export_forwading($vsl_num,"accfilecover");
		}

		// export vessel details
		elseif($btnVal=="export_vsl_details"){
			if(empty($vessel)){$msg=alertMsg("Vessel Name Is Missing", "danger");}
	        else{export_forwading($vsl_num, $btnVal); }
		}

		// before arrive
		// 1.vsl_declearation
		elseif($btnVal=="prepartique"){
			if(empty($vsl_nationality)){$msg=alertMsg("Vessel Nationality Missing", "danger");}
	    	elseif(empty($last_port)){$msg=alertMsg("Lastport Missing","danger");}
	    	elseif(empty($vsl_cargo)){$msg=alertMsg("Cargo qty Missing","danger");}
	    	elseif(empty($vsl_cargo_name)){$msg=alertMsg("Cargo name Missing","danger");}
	        else{export_forwading($vsl_num, $btnVal);}
		}
		// 2.vessel_declearation
		elseif($btnVal=="vsl_declearation"){
			if(empty($vsl_cargo)){$msg=alertMsg("Cargo qty Missing","danger");}
	    	elseif(empty($vsl_cargo_name)){$msg=alertMsg("Cargo name Missing","danger");}
	    	elseif(empty($vsl_imo)){$msg=alertMsg("Imo Number Missing","danger");}
	    	elseif(empty($vsl_grt)){$msg=alertMsg("Grt Missing","danger");}
	    	elseif(empty($vsl_nrt)){$msg=alertMsg("Nrt Missing","danger");}
	    	elseif(empty($vsl_loa)){$msg=alertMsg("Loa Missing","danger");}
	    	elseif(empty($vsl_owner_name)){$msg=alertMsg("Owner Name Missing","danger");}
	    	elseif(empty($shipper_name)){$msg=alertMsg("Shipper name","danger");}
	    	elseif(empty($last_port)){$msg=alertMsg("Last port/Load port Missing","danger");}
	        else{export_forwading($vsl_num, $btnVal);}
		}
		// 3.portigm
		elseif($btnVal=="portigm"){
			if(empty($vsl_cargo)){$msg=alertMsg("Cargo qty Missing","danger");}
	    	elseif(empty($vsl_cargo_name)){$msg=alertMsg("Cargo name Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// 4.plantq
		elseif($btnVal=="plantq"){
			if(empty($vsl_cargo)){$msg=alertMsg("Cargo Description Missing","danger");}
	    	elseif(empty($vsl_cargo_name)){$msg=alertMsg("Cargo name Missing","danger");}
	    	elseif(empty($last_port)){$msg=alertMsg("Last port/Load port Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// 5.po_booking
		elseif($btnVal=="po_booking"){
			if(empty($vsl_cargo)){$msg=alertMsg("Cargo Description Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// 6.SURVEYOR BOOKING
		elseif($btnVal=="survey_booking"){
			if(empty($vsl_cargo)){$msg=alertMsg("Cargo Description Missing","danger");}
	    	elseif(empty($vsl_cargo_name)){$msg=alertMsg("Cargo name Missing","danger");}
	        else{ 
	        	export_forwading($vsl_num, $btnVal);
	        	export_forwading($vsl_num, "survey_booking_bangla");
	        }
		}
		// export all before arrive
        elseif($btnVal == "before_arrive"){ 
        	if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	    	elseif(empty($vsl_cargo)){$msg=alertMsg("Vessel Cargo Description Missing","danger");}
	    	elseif(empty($vsl_cargo_name)){$msg=alertMsg("Vessel Name Missing","danger");}
	    	elseif(empty($last_port)){$msg=alertMsg("Loadport Missing!","danger");}
	    	else{
	    		export_forwading($vsl_num, "prepartique"); 
	    		export_forwading($vsl_num, "vsl_declearation"); 
	    		export_forwading($vsl_num, "portigm"); 
	    		export_forwading($vsl_num, "plantq"); 
	    		export_forwading($vsl_num, "po_booking"); 
	    		export_forwading($vsl_num, "survey_booking");
	    		export_forwading($vsl_num, "survey_booking_bangla");
	    	}
        }

		// after arrive
		// final entry
		elseif($btnVal == "finalentryexport"){ export_forwading($vsl_num, $btnVal); }
		// pc_forwading
		elseif ($btnVal == "pcforwadingexport") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	    	elseif(empty($vsl_nationality)){$msg=alertMsg("Vessel Flag Missing","danger");}
	    	elseif(empty($vsl_nrt)){$msg=alertMsg("Vessel Nrt Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// pc_stamp
		elseif ($btnVal == "pcstampexport") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	    	elseif(empty($vsl_nationality)){$msg=alertMsg("Vessel Flag Missing","danger");}
	    	elseif(empty($vsl_nrt)){$msg=alertMsg("Vessel Nrt Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// inc tax forwading
		elseif ($btnVal == "inctaxforwading") {
			if(empty($arrived)){$msg=alertMsg("Vessel Not Yet Received!","danger");}
    		else{export_forwading($vsl_num, $btnVal);}
		}
		// mmd forwading
		elseif ($btnVal == "mmdforwading") {
			if(empty($arrived)){$msg=alertMsg("Vessel Not Yet Received!","danger");}
    		else{export_forwading($vsl_num, $btnVal);}
		}
		// inc tax stamp
		elseif($btnVal == "inctaxstamp"){
        	if(empty($arrived)){$msg=alertMsg("Vessel Not Yet Received!","danger");}
        	else{ export_forwading($vsl_num, $btnVal); }
        }
        // pcformet
		elseif($btnVal == "pcformet"){
        	if(empty($rotation)){$msg=alertMsg("Rotation Missing for pc!","danger");}
        	elseif(empty($capt_name)){$msg=alertMsg("Capt name is missing!","danger");}
        	elseif(empty($vsl_nrt)){$msg=alertMsg("Nrt Missing!","danger");}
        	elseif(empty($vsl_nationality)){$msg=alertMsg("Nationality Missing!","danger");}
        	elseif(empty($next_port)){$msg=alertMsg("Next Port Missing!","danger");}
        	else{ export_forwading($vsl_num, $btnVal); }
        }
        // export all
        elseif($btnVal == "after_arrive"){ 
        	if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	    	elseif(empty($vsl_nationality)){$msg=alertMsg("Vessel Flag Missing","danger");}
	    	elseif(empty($vsl_nrt)){$msg=alertMsg("Vessel Nrt Missing","danger");}
	    	elseif(empty($arrived)){$msg=alertMsg("Vessel Not Yet Received!","danger");}
	    	else{
	    		export_forwading($vsl_num, "finalentryexport"); 
	    		export_forwading($vsl_num, "pcforwadingexport");;
	    		export_forwading($vsl_num, "pcstampexport"); 
	    		export_forwading($vsl_num, "inctaxforwading");
	    		export_forwading($vsl_num, "mmdforwading"); 
	    		export_forwading($vsl_num, "inctaxstamp");
	    		export_forwading($vsl_num, "pcformet");
	    	}
        }

        // after sail
        elseif ($btnVal == "port_health") { 
	    	if(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
	    	elseif(empty($capt_name)){$msg=alertMsg("Capt Name Missing","danger");}
	    	elseif(empty($vsl_nrt)){$msg=alertMsg("GRT Missing","danger");}
	    	elseif(empty($vsl_grt)){$msg=alertMsg("NRT Name Missing","danger");}
	    	elseif(empty($vsl_nationality)){$msg=alertMsg("Nationality/Flag Missing","danger");}
	    	elseif(empty($vsl_registry)){$msg=alertMsg("Port of Registry Missing","danger");}
	    	elseif(empty($vsl_imo)){$msg=alertMsg("IMO Number Missing","danger");}
	    	elseif(empty($last_port)){$msg=alertMsg("Load Port Missing","danger");}
	    	// elseif(empty($next_port)){$msg=alertMsg("Next Port Missing","danger");}
	    	elseif(empty($arrived)){$msg=alertMsg("Vessel Not Received Yet","danger");}
	    	elseif(empty($sailing_date)){$msg=alertMsg("Vessel Not Sailed Yet","danger");}
	    	elseif(empty($vsl_dead_weight)){$msg=alertMsg("Dead Weight Missing","danger");}
	    	elseif(empty($number_of_crew)){$msg=alertMsg("Number of Crew Missing","danger");}
	    	// elseif(empty($with_retention)){$msg=alertMsg("With Retention field empty","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}

		// PSC_SUBMISSION
        elseif ($btnVal == "psc_submission") { 
			if(empty($sailing_date)){$msg=alertMsg("Vessel Not Sailed Yet", "danger");}
	    	elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// EGM_Forwading
        elseif ($btnVal == "egm_forwading") { 
			if(empty($sailing_date)){$msg=alertMsg("Sailing Date Missing", "danger");}
	    	elseif(empty($arrived)){$msg=alertMsg("Receving Date Missing","danger");}
	    	elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
	    	elseif(empty($with_retention)){$msg=alertMsg("With Retention Data Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// EGM_Format
        elseif ($btnVal == "egm_format") { 
			if(empty($vsl_grt)){$msg=alertMsg("GRT Missing", "danger");}
	    	elseif(empty($vsl_nrt)){$msg=alertMsg("NRT Missing","danger");}
	    	elseif(empty($vsl_nationality)){$msg=alertMsg("Vessel Nationality Missing","danger");}
	    	elseif(empty($capt_name)){$msg=alertMsg("Capt Name Missing","danger");}
	    	elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
	    	elseif(empty($with_retention)){$msg=alertMsg("With Retention Data Missing","danger");}
	    	elseif(empty($next_port)){$msg=alertMsg("Next Port Missing","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		// EGM_Format
        elseif ($btnVal == "after_sail") { 
			if(empty($vsl_grt)){$msg=alertMsg("GRT Missing", "danger");}
			elseif(empty($vsl_imo)){$msg=alertMsg("IMO Number Missing","danger");}
	    	elseif(empty($vsl_nrt)){$msg=alertMsg("NRT Missing","danger");}
	    	elseif(empty($vsl_nationality)){$msg=alertMsg("Vessel Nationality Missing","danger");}
	    	elseif(empty($vsl_registry)){$msg=alertMsg("Port of Registry Missing","danger");}
	    	elseif(empty($capt_name)){$msg=alertMsg("Capt Name Missing","danger");}
	    	elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
	    	elseif(empty($with_retention)){$msg=alertMsg("With Retention Data Missing","danger");}
	    	elseif(empty($next_port)){$msg=alertMsg("Next Port Missing","danger");}
	    	elseif(empty($sailing_date)){$msg=alertMsg("Sailing Date Missing", "danger");}
	    	elseif(empty($arrived)){$msg=alertMsg("Receving Date Missing","danger");}
	    	elseif(empty($last_port)){$msg=alertMsg("Load Port Missing","danger");}
	    	elseif(empty($vsl_dead_weight)){$msg=alertMsg("Dead Weight Missing","danger");}
	    	elseif(empty($number_of_crew)){$msg=alertMsg("Number of Crew Missing","danger");}
	        else{ 
	        	export_forwading($vsl_num, "port_health"); 
	        	export_forwading($vsl_num, "psc_submission");
	        	export_forwading($vsl_num, "egm_forwading"); 
	        	export_forwading($vsl_num, "egm_format");
	        }
		}

		// Arrival Perticular
        elseif ($btnVal == "arrival_perticular") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		elseif ($btnVal == "ship_required_docs") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		elseif ($btnVal == "representative_letter") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(empty($rep_id)){$msg=alertMsg("Representative Not assigned yet!","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		elseif ($btnVal == "lightdues" || $btnVal == "lightdues2nd") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
			elseif(empty($capt_name)){$msg=alertMsg("Capt Name Missing","danger");}
			elseif(empty($vsl_nrt)){$msg=alertMsg("NRT Missing","danger");}
			elseif(empty($last_port)){$msg=alertMsg("Load Port Missing","danger");}
	        else{ 
	        	if ($btnVal == "lightdues") {export_forwading($vsl_num,$btnVal);}
	        	else{export_forwading($vsl_num,$btnVal);}
	        }
		}
		elseif ($btnVal == "watchman_letter") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		elseif ($btnVal == "vendor_letter") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		elseif ($btnVal == "export_rcv_docs") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(empty($rep_id)){$msg=alertMsg("Representative Not assigned yet!","danger");}
			elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
			elseif(empty($capt_name)){$msg=alertMsg("Capt Name Missing","danger");}
			elseif(empty($vsl_nrt)){$msg=alertMsg("NRT Missing","danger");}
			elseif(empty($last_port)){$msg=alertMsg("Load Port Missing","danger");}
	        else{ 
	        	export_forwading($vsl_num, "arrival_perticular"); 
	        	export_forwading($vsl_num, "ship_required_docs");
	        	export_forwading($vsl_num, "representative_letter"); 
	        	export_forwading($vsl_num, "lightdues");
	        	export_forwading($vsl_num, "watchman_letter"); 
	        	export_forwading($vsl_num, "vendor_letter"); 
	        }
		}elseif ($btnVal == "igmformat") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(!exist("vessels_bl", "vsl_num = ".$vsl_num)){$msg=alertMsg("BL Missing","danger");}
	        else{ igm_format($vsl_num); }
		}elseif ($btnVal == "igmxml") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(!exist("vessels_bl", "vsl_num = ".$vsl_num)){$msg=alertMsg("BL Missing","danger");}
	        else{ igm_xml($vsl_num); }
		}elseif ($btnVal == "igmfullcargo") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(!exist("vessels_bl", "vsl_num = ".$vsl_num)){$msg=alertMsg("BL Missing!","danger");}
			elseif(empty($vsl_imo)){$msg=alertMsg("IMO Missing!","danger");}
			elseif(empty($vsl_nationality)){$msg=alertMsg("Vessel Nationality Missing!","danger");}
			elseif(empty($vsl_registry)){$msg=alertMsg("Vessel Registry Missing!","danger");}
			elseif(empty($vsl_grt)){$msg=alertMsg("Vessel GRT Missing!","danger");}
			elseif(empty($vsl_nrt)){$msg=alertMsg("Vessel NRT Missing!","danger");}
			elseif(empty($packages_codes)){$msg=alertMsg("Package Code Missing!","danger");}
			elseif(empty($capt_name)){$msg=alertMsg("Name of Captain Missing!","danger");}
	        else{ igmfullcargo($vsl_num); export_forwading($vsl_num, $btnVal); }
		}elseif ($btnVal == "stevedorebooking") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
			elseif(empty($vsl_cargo)){$msg=alertMsg("Cargo qty Missing","danger");}
			elseif(empty($stevedore_name)){$msg=alertMsg("Stevedore Missing!","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}elseif (isset($_GET['exportdo'])) { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(empty($arr_date)){$msg=alertMsg("Arrival Date Missing","danger");}
			elseif(empty($do_cargoname)){$msg=alertMsg("Cargo name from Do Missing","danger");}
			elseif(empty($do_cargoqty)){$msg=alertMsg("Cargo Qty from Do Missing","danger");}
			elseif(empty($c_num)){$msg=alertMsg("C Number Missing","danger");}
			elseif(empty($c_date)){$msg=alertMsg("C Date Missing","danger");}
			elseif(empty($c_cnfId)){$msg=alertMsg("C Cnf Missing","danger");}
	        else{ do_format($doId); }
		}elseif ($btnVal == "portbillcollect") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif (empty($arrived)) {$msg=alertMsg("Vessel Not Received Yet!","danger");}
			elseif (empty($sailing_date)) {$msg=alertMsg("Vessel Not Sailed Yet!","danger");}
			elseif(empty($rotation)){$msg=alertMsg("Rotation Number Missing","danger");}
			elseif(empty($vsl_grt)){$msg=alertMsg("Grt Missing","danger");}
			elseif(empty($vsl_nrt)){$msg=alertMsg("Nrt Missing!","danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		elseif ($btnVal == "cargo_declearation") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	        else{ export_forwading($vsl_num, $btnVal); }
		}
		elseif ($btnVal == "vataitchalan") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
			elseif(empty($vsl_nrt)){$msg=alertMsg("NRT Missing","danger");}
	        else{ 
	        	export_forwading($vsl_num,"vataitchalan15");
	        	export_forwading($vsl_num,"vataitchalan10"); }
		}elseif ($btnVal == "softemplet") { 
			if(empty($vessel)){$msg=alertMsg("Vessel Name Missing", "danger");}
	        else{ export_forwading($vsl_num, "softemplet"); }
		}
		else{$msg=alertMsg("None above");}
	}

	if (isset($_POST['multipledoexport'])) {
		$multipledo = $_POST['multipledo'] ;
		foreach ($multipledo as $do) {
			// do infos
			$run2 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE id = '$do' ");
			$row2 = mysqli_fetch_assoc($run2); $c_cnfId = $row2['cnf_name'];
			$c_num = $row2['c_num']; $c_date = $row2['c_date']; $c_cargoname = $row2['c_cargoname'];
			$do_cargoname = $row2['cargo_name'];$do_cargoqty = $row2['cargo_qty'];

			// check c number and date, skip export if missing
			if (empty($c_num) || empty($c_date)) {continue;}
			do_format($do);
		}
	}

	if (isset($_GET['exportblxml'])) {
		$id = $_GET['exportblxml']; exportblxml($id);   
	}

	if (isset($_POST['multiplexmlexport'])) {
		$multiplexml = $_POST['multiplexml'] ;
		foreach ($multiplexml as $xml) { exportblxml($xml); }
	}

	//download forwadings
	if (isset($_POST['downloadfile'])) {
		$vsl_num = $_POST['vsl_num'];
		$msl_num = allData('vessels', $vsl_num, 'msl_num');
		$vessel=allData('vessels',$vsl_num,'vessel_name');
		$btnVal = $_POST['downloadfile'];
		$path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/";
		$save = $path.$btnVal; downloadfile($save);
		if (downloadfile($save)) {
			header("location: vessel_details.php?forwadingpage=$vsl_num");
		}else{echo "</br>".$save;}
		
	}

	if (isset($_GET['msl_num_to_vsl_num'])) {
		$companyid = $my['companyid']; $totalvslid = $totalmslid = array();
		$run = mysqli_query($db, "SELECT * FROM vessels WHERE companyid = '$companyid' ");
		// echo "<h2>Vessels Before</h2> </br>";
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $msl_num = $row['msl_num']; 
			$totalvslid[] = $row['id']; $totalmslid[] = $row['msl_num'];
			// echo "Id: ".$id; echo "&nbsp; &nbsp; MV. ". $row['vessel_name']; echo "&nbsp; &nbsp; Msl: ".$msl_num."</br>";
		}
		echo "<h2>Vessels Cargo Before</h2> </br>";
		// $reset = reset($totalmslid); 
		$totalmsl = implode(',', $totalmslid);
		// echo "$totalmsl"; 
		// print_r($totalmslid);echo"</br>";
		$vsl_cargo = mysqli_query($db, "SELECT * FROM vessel_details WHERE id != 0 AND vsl_num IN ($totalmsl) ");
		// echo "Total Cargo Vsl: ".mysqli_num_rows($vsl_cargo)."</br>";
		while ($row_cargo = mysqli_fetch_assoc($vsl_cargo)) {
			$id = $row_cargo['id']; $vsl_num = $row_cargo['vsl_num'];
			echo "Id: ".$id; echo "&nbsp; &nbsp; Vsl: ".$vsl_num.".MV.".allDataUpdated('vessels', 'msl_num', $vsl_num, 'vessel_name')." ";
			$msl_num = $vsl_num;
			$original_vslid = allDataUpdated('vessels', 'msl_num', $msl_num, 'id');
			echo "ID: ".$original_vslid."</br>";
			// mysqli_query($db, "UPDATE vessel_details SET vsl_num = '$original_vslid' WHERE id = '$id' ");
		}
	}

	if (isset($_GET['df'])) {
		if (!empty($_GET['df'])) {
			$vsl_num = $_GET['df']; 
			$msl_num = allData('vessels', $vsl_num, 'msl_num');
			$vessel_name = allData('vessels', $vsl_num, 'vessel_name');
			$path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel_name."/"; 
			// Create Zip
			createzip($path);
			header("location: vessel_details.php?forwadingpage=$vsl_num&&dn=$vsl_num");
		}else{alertMsg("Empty Vessel Number!");}
	}if (isset($_GET['dn'])) {
		if (!empty($_GET['dn'])) {
			$vsl_num = $_GET['dn']; 
			$msl_num = allData('vessels', $vsl_num, 'msl_num');
			$vessel_name = allData('vessels', $vsl_num, 'vessel_name');
			$path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel_name."/";
			// download
			$save = $path."zip_downloaded.zip"; downloadfile($save);
		}
	}


	// upload ship perticular files from vessel details
	if (isset($_POST['uploadshipperticular'])) {
		
		$vessel_id = $thisvessel['id'];
		// Set the directory where files will be uploaded
		$uploadDir = "forwadings/auto_forwardings/".$thisvessel['msl_num'].".MV. ".$thisvessel['vessel_name']."/";

		// Create the uploads folder if it doesn't exist
		if (!is_dir($uploadDir)) {
		    mkdir($uploadDir, 0755, true); // 0755 gives read & execute permissions, true allows recursive creation
		}

		// Define allowed MIME types and corresponding file extensions
		$allowedTypes = [
		    'application/pdf' => 'pdf',
		    'application/msword' => 'doc',
		    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
		    'application/vnd.ms-excel' => 'xls',
		    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
		    'application/zip' => 'zip',
		    'image/jpeg' => 'jpg',
		    'image/png' => 'png',
		];

		// // List of input names to process (matches the form fields)
		// $fileFields = ['ship_perticular', 'crewlist', 'pnicer'];
		// Map input fields to desired filenames (without extensions)
		$filenameMap = [
		    'shipperticular' => 'ship_perticular',
		    'crewlist' => 'crew_list',
		    'pnicer' => 'pni_cer',
		];

		// Loop through each file input
		foreach ($filenameMap as $field => $fixedName) {
		    // Check if this input is set in $_FILES
		    if (!isset($_FILES[$field])) {
		        // echo "File input '$field' is missing.<br>";
		        continue; // Skip to next input
		    }

		    // Grab the temporary path, original name, MIME type, and error code for the uploaded file
		    $fileTmpPath = $_FILES[$field]['tmp_name'];
		    $fileName = $_FILES[$field]['name'];
		    $fileType = $_FILES[$field]['type'];
		    $fileError = $_FILES[$field]['error'];

		    // If no file was uploaded in this field, skip it
		    if ($fileError === UPLOAD_ERR_NO_FILE) {
		        // echo "No file uploaded for '$field'.<br>";
		        continue;
		    }

		    // If there was another error during upload, display it and skip
		    if ($fileError !== UPLOAD_ERR_OK) {
		        // echo "Error uploading '$fileName'.<br>";
		        continue;
		    }

		    // Check if the uploaded file's MIME type is in the allowed list
		    if (!array_key_exists($fileType, $allowedTypes)) {
		    	$msg = alertMsg("File type ".$fileType." not allowed for file ".$fileName.".<br>", "danger");
		        // echo "File type '$fileType' not allowed for file '$fileName'.<br>";
		        continue;
		    }

		    // Get the extension from our allowedTypes array
		    $extension = $allowedTypes[$fileType];

		    // Create a safe and unique filename to prevent overwriting or unsafe file names
		    // $safeName = uniqid() . '-' . basename($fileName);

		    // Full path where the file will be stored
		    // $destination = $uploadDir . $safeName;
		    $destination = $uploadDir . $fixedName . '.' . $extension;

		    // Move the uploaded file from temp folder to the desired destination
		    if (move_uploaded_file($fileTmpPath, $destination)) {
		        // echo "Uploaded successfully: $fileName<br>";
		        $msg = alertMsg("Uploaded successfully: ".$fileName, "success");
		        mysqli_query($db, "UPDATE vessels SET workstatus = 'notdone' WHERE id = '$vessel_id' ");
		    } else {
		        // echo "Failed to move uploaded file: $fileName<br>";
		        $msg = alertMsg("Failed to move uploaded file: ".$fileName, "danger");
		    }
		}
	}
?>