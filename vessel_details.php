<?php 
  include('inc/header.php'); 
?>

    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include('inc/sidebar.php'); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">

        <div class="page-header">
          <div class="container-fluid">

            <h2 class="h5 no-margin-bottom" style="text-align: center;">
              <!-- <span style="float: left;">Dashboard</span> -->
              <!-- one line if else statement -->
              <?php //$msl_num = isset($_GET['edit']) ? $_GET['edit'] : $_GET['view']; ?>
              <?php 
                // useraccess for vessel_ctrl
                if (allData('useraccess',$my['office_position'],'vessel_ctrl')) { $btnstatus = ""; }
                else{$btnstatus = "disabled";}

                // useraccess for vessel_ctrl
                if (allData("useraccess",$my['office_position'],"forwading_ctrl") && isset($_GET['forwadingpage']) && !empty($_GET['forwadingpage']) && allData("vessels", $_GET['forwadingpage'], "payment") == "paid") { $fbtnstatus = ""; }
                else{$fbtnstatus = "disabled";}

                // initial page setup
                if(isset($_GET['vsl_num']) && !empty($_GET['vsl_num'])){$vsl_num = $_GET['vsl_num'];}
                elseif(isset($_GET['edit']) && !empty($_GET['edit'])){
                  $vsl_num = $_GET['edit'];
                  $editpage = "active"; $shipperpage = $forwadingpage = $vesselblpage = $vesseligmpage = $doinputpage = "";
                } 
                elseif(isset($_GET['blinputs']) && !empty($_GET['blinputs'])){
                  $vsl_num = $_GET['blinputs'];
                  $vesselblpage = "active"; $shipperpage = $vesseligmpage = $forwadingpage = $editpage = $doinputpage = "";
                }
                elseif(isset($_GET['igminputs']) && !empty($_GET['igminputs'])){
                  $vsl_num = $_GET['igminputs'];
                  $vesseligmpage = "active"; $shipperpage = $vesselblpage = $forwadingpage = $editpage = $doinputpage = "";
                } 
                elseif(isset($_GET['doinputs']) && !empty($_GET['doinputs'])){
                  $vsl_num = $_GET['doinputs'];
                  $doinputpage = "active"; $shipperpage = $forwadingpage = $editpage = $vesselblpage = $vesseligmpage = "";
                } 
                elseif(isset($_GET['forwadingpage']) && !empty($_GET['forwadingpage'])){
                  $vsl_num = $_GET['forwadingpage'];
                  $forwadingpage = "active"; $shipperpage = $doinputpage = $editpage = $vesselblpage = $vesseligmpage = "";
                } 
                elseif(isset($_GET['ship_perticular']) && !empty($_GET['ship_perticular'])){
                  $vsl_num = $_GET['ship_perticular'];
                  $shipperpage = "active"; $forwadingpage = $doinputpage = $editpage = $vesselblpage = $vesseligmpage = "";
                }
                else{$vsl_num = "";}

                $run0 = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
                if (mysqli_num_rows($run0) > 0) {
                  $row0 = mysqli_fetch_assoc($run0); $vessel = $row0['vessel_name']; 
                } else{$vessel = "";}
                if($vessel != ""){ $msl_num = allData('vessels', $vsl_num, 'msl_num'); }

                // deletes folger if folder is empty
                $folder = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/";
                deleteIfEmpty($folder);
                
              ?>
              <!-- <a href="vessel_details.php?ship_perticular=<?php echo $vsl_num; ?>">
                FORWADINGS
              </a> -->

              <!-- <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link disabled" href="#">Dashboard</a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="vessel_details.php?edit=<?php echo $vsl_num; ?>">Edit Vsl</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="vessel_details.php?ship_perticular=<?php echo $vsl_num; ?>">Ship Perticular</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="vessel_details.php?port_bill=<?php echo $vsl_num; ?>">Port Bill</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="vessel_details.php?blinputs=<?php echo $vsl_num; ?>">BL</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="vessel_details.php?doinputs=<?php echo $vsl_num; ?>">DO</a>
                </li>
              </ul> -->
              <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <!-- <a class="navbar-brand" href="#">Navbar</a> -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?php echo $editpage; ?>">
                      <a class="nav-link" href="vessel_details.php?edit=<?php echo $vsl_num; ?>">Edit Vsl</a>
                    </li>
                    <li class="nav-item <?php echo $shipperpage; ?>">
                      <a class="nav-link" href="vessel_details.php?ship_perticular=<?php echo $vsl_num; ?>">Ship Perticular</a>
                    </li>
                    <li class="nav-item <?php echo $vesselblpage; ?>">
                      <a class="nav-link" href="vessel_details.php?blinputs=<?php echo $vsl_num; ?>">BL</a>
                    </li>
                    <li class="nav-item <?php echo $vesseligmpage; ?>">
                      <a class="nav-link" href="vessel_details.php?igminputs=<?php echo $vsl_num; ?>">IGM</a>
                    </li>
                    <li class="nav-item <?php echo $doinputpage; ?>">
                      <a class="nav-link" href="vessel_details.php?doinputs=<?php echo $vsl_num; ?>">DO</a>
                    </li>
                    <li class="nav-item <?php echo $forwadingpage; ?>">
                      <a class="nav-link" href="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>">Forwadings</a>
                    </li>
                  </ul>
                </div>
              </nav>
            </h2>
          </div>
        </div>
        <?php echo $msg; /*include('inc/errors.php');*/ ?>

        <?php if(isset($_GET['vsl_num'])){ $vsl_num = $_GET['vsl_num'];$percen = percentage($vsl_num); ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>Vassel Details</strong>
                    <a href="vessel_details.php?edit=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right;">
                      <i class="icon-ink"></i> Edit
                    </a>

                  </div>
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $percen; ?>%;" aria-valuenow="<?php echo $percen; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percen; ?>%</div>
                  </div>
                  <!-- <div class="progress">
                    <?php //percentage($vsl_num); ?>
                  </div> -->
                  <div class="table-responsive"> 
                    <table class="table table-dark table-sm table-custom">
                      <thead>
                        <tr style="color: white; border: 1px solid white;">
                          <th>Msl</th>
                          <th colspan="2">Vessel</th>
                          <th>Rotaion</th>
                          <th>Rcv</th>
                          <th>Sail</th>
                          <!-- <th>Stevedore</th> -->
                          <th>Cargo</th>
                          <th>Qty</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php vesselDetailsNew($vsl_num); ?>
                      </tbody>
                    </table>
                  </div>


                </div>
              </div>
            </div>
          </div>
        </section>





        <!--section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Load Draft Surveyors</strong>
                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addSurveyorLoad">+ Add Surveyor</button>
                

                    <div class="modal fade" id="addSurveyorLoad" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">
                              Insert Vessels Surveyors Info
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                            <input type="hidden" name="vesselId" value="<?php echo $vsl_num; ?>">
                            <div class="modal-body">
                              
                              <input type="hidden" name="msl_num" value="<?php echo $msl_num; ?>">
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <label for="inputState">Party</label>
                                  <select id="inputState" class="form-control search" name="party">
                                    <option value="">--Select--</option>
                                    <?php
                                    $run = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
                                    $row = mysqli_fetch_assoc($run); 
                                    $custom = $row['survey_custom'];
                                    $consignee = $row['survey_consignee'];
                                    $supplier = $row['survey_supplier'];
                                    $pni = $row['survey_pni'];
                                    $chattrer = $row['survey_chattrer'];
                                    $owner = $row['survey_owner'];
                                    if ($custom != 0) {
                                      echo "<option value=\"survey_custom\">Custom</option>";
                                    }if ($consignee != 0) {
                                      echo "<option value=\"survey_consignee\">Consignee</option>";
                                    }if ($supplier != 0) {
                                      echo "<option value=\"survey_supplier\">Supplier</option>";
                                    }if ($owner != 0) {
                                      echo "<option value=\"survey_owner\">Owner</option>";
                                    }if ($pni != 0) {
                                      echo "<option value=\"survey_pni\">PNI</option>";
                                    }if ($chattrer != 0) {
                                      echo "<option value=\"survey_chattrer\">Chattrer</option>";
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="inputState">Surviour</label>
                                  <select id="inputState" class="form-control search" name="surveyorId" required>
                                    <option value="">--Select--</option>
                                    <?php selectOptions('surveyors', 'surveyor_name'); ?>
                                  </select>
                                </div>

                              </div>
                              <input type="hidden" name="survey_purpose" value="Load Draft">
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addVesselsSurveyor" class="btn btn-success">
                                +ADD
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="block-body">
                    <table class="table table-dark">
                      <thead>
                        <tr>
                          <th class="col-2" scope="col">Party</th>
                          <th class="col-3" scope="col">Company</th>
                          <th class="col-2" scope="col">Purpose</th>
                          <th class="col-3" scope="col">Surveyor</th>
                          <th class="col-2" scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php// vesselSurveyors($vsl_num, "load"); ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section-->


        <!--section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Light Draft Surveyors</strong>
                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addSurveyorLight">+ Add Surveyor</button>
                

                    
                    <div class="modal fade" id="addSurveyorLight" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Insert Bin Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                            <input type="hidden" name="vesselId" value="<?php echo $vsl_num; ?>">
                            <div class="modal-body">
                              
                              <input type="hidden" name="msl_num" value="<?php echo $msl_num; ?>">
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <label for="inputState">Party</label>
                                  <select id="inputState" class="form-control search" name="party">
                                    <option value="">--Select--</option>
                                    <?php
                                    $run = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
                                    $row = mysqli_fetch_assoc($run); 
                                    $custom = $row['survey_custom'];
                                    $consignee = $row['survey_consignee'];
                                    $supplier = $row['survey_supplier'];
                                    $pni = $row['survey_pni'];
                                    $chattrer = $row['survey_chattrer'];
                                    $owner = $row['survey_owner'];
                                    if ($custom != 0) {
                                      echo "<option value=\"survey_custom\">Custom</option>";
                                    }if ($consignee != 0) {
                                      echo "<option value=\"survey_consignee\">Consignee</option>";
                                    }if ($supplier != 0) {
                                      echo "<option value=\"survey_supplier\">Supplier</option>";
                                    }if ($owner != 0) {
                                      echo "<option value=\"survey_owner\">Owner</option>";
                                    }if ($pni != 0) {
                                      echo "<option value=\"survey_pni\">PNI</option>";
                                    }if ($chattrer != 0) {
                                      echo "<option value=\"survey_chattrer\">Chattrer</option>";
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="inputState">Surviour</label>
                                  <select id="inputState" class="form-control search" name="surveyorId" required>
                                    <option value="">--Select--</option>
                                    <?php// selectOptions('surveyors', 'surveyor_name'); ?>
                                  </select>
                                </div>

                              </div>
                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputState">Survey Purpose</label>
                                  <select id="inputState" class="form-control search" name="survey_purpose" required>
                                    <option value="">--Select--</option>
                                    <option value="Rob">Rob</option>
                                    <option value="Light Draft">Light Draft</option>
                                  </select>
                                </div>
                              </div>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addVesselsSurveyor" class="btn btn-success">
                                +ADD
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="block-body">
                    <table class="table table-dark">
                      <thead>
                        <tr>
                          <th class="col-2" scope="col">Party</th>
                          <th class="col-3" scope="col">Company</th>
                          <th class="col-2" scope="col">Purpose</th>
                          <th class="col-3" scope="col">Surveyor</th>
                          <th class="col-2" scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php// vesselSurveyors($vsl_num, "light"); ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section-->


        <!-- <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>Vassel Details</strong>
                    <a href="vessel_details.php?edit=<?php echo $msl_num; ?>" class="btn btn-secondary btn-sm" style="float: right;">
                      <i class="icon-ink"></i> Edit
                    </a>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-dark table-striped table-sm">
                      <tbody>
                        <?php //vesselDetails($msl_num); ?>
                      </tbody>
                    </table>
                  </div>


                </div>
              </div>
            </div>
          </div>
        </section> -->


        <!-- add consignee & CNF -->
        <!-- <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-6">
                <div class="block">

                  <div class="title">
                    <strong>Add Consignee To This Vessel</strong>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-dark table-striped table-sm">
                      <tbody>
                        <?php //allConsignee("addBtn"); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

              <div class="col-lg-6">
                <div class="block">

                  <div class="title">
                    <strong>Add CNF To This Vessel</strong>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-dark table-striped table-sm">
                      <tbody>
                        <?php //allCnf("addBtn"); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </section> -->


        <?php } elseif(isset($_GET['edit']) && !empty($_GET['edit'])){ ?>


        <!-- edit vessel -->
        <?php
            $vsl_num = $_GET['edit']; 
            $run2 = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
            $row2 = mysqli_fetch_assoc($run2);

            $vesselId = $row2['id']; 
            $companyid = $my['companyid'];
            $vsl_companyid = $row2['companyid'];
            $msl_num = $row2['msl_num'];
            $vessel_name = $row2['vessel_name'];
            $received_by = $row2['received_by'];
            
            $stevedore = $row2['stevedore'];
            $kutubdia_qty = $row2['kutubdia_qty'];
            $outer_qty = $row2['outer_qty'];
            $retention_qty = $row2['retention_qty'];
            $seventyeight_qty = $row2['seventyeight_qty'];
            $sailed_by = $row2['sailed_by'];
            $repId = $row2['representative'];

            if (empty($row2['rotation'])) {
              $rotation = "2025 / ";
            }else{$rotation = $row2['rotation'];}

            if (empty($row2['arrived'])) {$arrived = "";}
            else{
              // $arrived = date('Y-m-d', strtotime($row2['arrived']));
              $arrived = date('d/m/Y', strtotime($row2['arrived']));
            }

            if (empty($row2['com_date'])) {$com_date = "";}
            else{$com_date = date('d/m/Y', strtotime($row2['com_date']));}
            
            if (empty($row2['rcv_date'])) {$rcv_date = "";}
            else{
              // $rcv_date = date('Y-m-d', strtotime($row2['rcv_date']));
              $rcv_date = date('d/m/Y', strtotime($row2['rcv_date']));
            }
            
            if (empty($row2['sailing_date'])) {$sailing_date = "";}
            else{$sailing_date = date('d/m/Y', strtotime($row2['sailing_date']));}

            if (!empty($arrived)) {
              if (!empty($rcv_date)&&$arrived!=$rcv_date){$ckstatusRcv = "";}
              elseif (!empty($arrived) && empty($rcv_date)){$ckstatusRcv = "";}
              else{$ckstatusRcv = "checked";}
            }else{if(!empty($rcv_date)){$ckstatusRcv="";}else{$ckstatusRcv="checked";}}

            // arrived = com_date, rcv_date = sailing_date
            if (!empty($com_date)) {
              if (!empty($sailing_date)&&$com_date!=$sailing_date){$ckstatusSail = "";}
              elseif (!empty($com_date) && empty($sailing_date)){$ckstatusSail = "";}
              else{$ckstatusSail = "checked";}
            }else{if(!empty($sailing_date)){$ckstatusSail="";}else{$ckstatusSail="checked";}}

            $anchor = $row2['anchor'];
            $survey_custom = $row2['survey_custom'];
            $survey_consignee = $row2['survey_consignee'];
            $survey_supplier = $row2['survey_supplier'];
            $survey_pni = $row2['survey_pni'];
            $survey_chattrer = $row2['survey_chattrer'];
            $survey_owner = $row2['survey_owner'];

            // not count in percentage start
            $vsl_opa = $row2['vsl_opa'];

            $custom_visited = $row2['custom_visited'];
            $qurentine_visited = $row2['qurentine_visited'];
            $psc_visited = $row2['psc_visited'];
            $multiple_lightdues = $row2['multiple_lightdues'];
            $crew_change = $row2['crew_change'];
            $has_grab = $row2['has_grab'];
            $fender = $row2['fender'];
            $fresh_water = $row2['fresh_water'];
            $piloting = $row2['piloting'];
            $sscec = $row2['sscec'];
            $egm = $row2['egm'];

            $custom_v = $qurentine_v = $psc_v = $multiple_l = $crew_c = $has_g = $fender_us = $fresh_w = $piloting_us = $obl_h = $obl_i = $sscec_ck = $egm_ck = "";
            if($custom_visited == 1){$custom_v = "checked";}
            if($qurentine_visited == 1){$qurentine_v = "checked";}
            if($psc_visited == 1){$psc_v = "checked";}
            if($multiple_lightdues == 1){$multiple_l = "checked";}
            if($crew_change == 1){$crew_c = "checked";}
            if($has_grab == 1){$has_g = "checked";}
            if($fender == 1){$fender_us = "checked";}
            if($fresh_water == 1){$fresh_w = "checked";}
            if($piloting == 1){$piloting_us = "checked";}
            if($sscec == 1){$sscec_ck = "checked";}
            if($egm == 1){$egm_ck = "checked";}
            // not count in percentage end

            $ttlqtyplused = floatval($outer_qty) + floatval($kutubdia_qty) + floatval($retention_qty);
            $ttlctgqty = floatval($outer_qty) + floatval($kutubdia_qty);

            // stevedore select data
            // $stvdrnm = allData('stevedore', $stevedore, 'name');
            // received by select data
            
            if($received_by != 0){$rcvbynm = allData('users', $received_by, 'name');}
            // sailed by select data
            if($sailed_by != 0){$slbynm = allData('users', $sailed_by, 'name');}
            
            // representative select data
            $representative_name = allData('users', $repId, 'name');

            $cargo_srt_nm = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_key');
            if($cargo_srt_nm != 0){$cargo_short_name = allData('cargokeys', $cargo_srt_nm, 'name');}

            $cargo_bl_name = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_bl_name');
            // $total_qty = gettotal('vessels_cargo', 'msl_num', $msl_num, 'quantity');
            $total_qty = ttlcargoqty($vsl_num);

        ?>
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Update Vessel Info </strong>
                    <?php if (allData('useraccess',$my['office_position'],'vessel_ctrl')) { ?>
                    <a 
                      onClick="javascript: return confirm('Please confirm deletion');" 
                      href="index.php?del_msl_num=<?php echo $vsl_num; ?>" 
                      class="btn btn-danger btn-sm"
                       style="float: right;"
                    ><i class="bi bi-trash"></i></a>
                    <?php } ?>
                    <a href="vessel_details.php?vsl_num=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right; margin-right: 10px;">
                      <i class="icon-ink"></i> View
                    </a>
                  </div>

                  <?php if (percentage($vsl_num) < 100) { ?>
                    <button type="submit" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#completepercentage" <?php echo $btnstatus; ?>>Complete</button>
                  <?php } ?>

                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo percentage($vsl_num); ?>%;" aria-valuenow="<?php echo percentage($vsl_num); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo percentage($vsl_num); ?>%</div>
                  </div>

                  <div class="block-body">

                    <form method="post" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                      <!-- 1st -->
                      <div class="form-row">
                        <div class="form-group col-md-1">
                          <label for="inputEmail4">VOY No.</label>
                          <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                          <input type="text" class="form-control" name="msl_num" value="<?php echo $msl_num; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label>Vessel Name</label>
                          <input type="text" class="form-control" name="vessel_name" required value="<?php echo $vessel_name ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Arrived</label>
                          <!-- <input type="date" class="form-control" name="arrived" value="<?php echo $arrived; ?>" <?php echo $btnstatus; ?>> -->
                          <input type="text" id="datepicker" class="form-control" name="arrived" value="<?php echo $arrived; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Received (same) <input type="checkbox" name="sameRcv" value="sameRcv" <?php echo $ckstatusRcv; ?> <?php echo $btnstatus; ?>></label>
                          <!-- <input type="date" class="form-control" name="rcv_date" value="<?php echo $rcv_date; ?>" <?php echo $btnstatus; ?>> -->
                          <input type="text" id="datepicker" class="form-control" name="rcv_date" value="<?php echo $rcv_date; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Discharge Completd</label>
                          <!-- <label>(same</label>
                          <input type="checkbox" name=""> -->
                          <input type="text" id="datepicker" class="form-control" name="com_date" value="<?php echo $com_date; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Sailed (same) <input type="checkbox" name="sameSail" value="sameSail" <?php echo $ckstatusSail; ?> <?php echo $btnstatus; ?>></label>
                          <input type="text" id="datepicker" class="form-control" name="sailing_date" value="<?php echo $sailing_date; ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 2nd -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Kutubdia Discharge Qty</label>
                          <input type="number" step="any" class="form-control" name="kutubdia_qty" value="<?php echo $kutubdia_qty ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Outer Discharge Qty</label>
                          <input type="number" step="any" class="form-control" name="outer_qty" value="<?php echo $outer_qty ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Retention Qty</label>
                          <input type="number" step="any" class="form-control" name="retention_qty" value="<?php echo $retention_qty ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">78 Quantity</label>
                          <input type="number" step="any" class="form-control" name="seventyeight_qty" value="<?php echo $seventyeight_qty ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 3rd -->
                      <div class="form-row">
                        <!-- <div class="form-group col-md-3">
                          <label for="inputState">Load Port</label>
                           <select name="loadport[]" class="form-control mb-3 mb-3 selectpicker" multiple style="background: transparent;" data-live-search="true">
                            <?php
                              // $run = mysqli_query($db, "SELECT * FROM loadport ");
                              // while ($row = mysqli_fetch_assoc($run)) {
                              //   $id = $row['id']; $value = $row['port_name'];
                              //   $getLoadPort = mysqli_query($db, "SELECT * FROM vessels_loadport WHERE loadport = '$id' AND msl_num = '$msl_num' ");
                              //   if (mysqli_num_rows($getLoadPort) > 0) { $selected = "selected"; }
                              //   else{$selected = "";}
                              //   echo"<option value=\"$id\" $selected>$value</option>";
                              // }
                            ?>
                          </select>
                        </div> -->

                        <!-- <div class="form-group col-md-3">
                          <label for="inputState">Impoter</label>
                          <select name="importer[]" class="form-control mb-3 mb-3 selectpicker" multiple style="background: transparent;" data-live-search="true">
                            <?php
                              $run = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                              while ($row = mysqli_fetch_assoc($run)) {
                                $id = $row['id']; $value = $row['name'];
                                $getImporter = mysqli_query($db, "SELECT * FROM vessels_importer WHERE importer = '$id' AND vsl_num = '$vsl_num' ");
                                if (mysqli_num_rows($getImporter) > 0) { $selected = "selected"; }
                                else{$selected = "";}
                                echo"<option value=\"$id\" $selected>$value</option>";
                              }
                            ?>
                          </select>
                        </div> -->


                        <div class="form-group col-md-9">
                          <label for="inputState">Stevedore</label>
                          <select id="inputState" class="form-control search" name="stevedore" <?php echo $btnstatus; ?>>
                            <!-- <option value="<?php echo $stevedore ?>"><?php echo alldata('stevedore', $stevedore, 'name'); ?></option> -->
                            <?php
                              if (empty($stevedore)) {echo "<option></option>";}
                              else{echo "<option value=\"$stevedore\">".alldata('stevedore',$stevedore,'name')."</option>";}
                              $run = mysqli_query($db, "SELECT * FROM stevedore ");
                              while ($row = mysqli_fetch_assoc($run)) {
                                $id = $row['id']; $value = $row['name'];
                                if ($id == $stevedore) { continue; }
                                echo"<option value=\"$id\">$value</option>";
                              }
                            ?>
                          </select>
                        </div>
                        
                        <div class="form-group col-md-3">
                          <label for="inputState">Representative</label>
                          <select id="inputState" class="form-control search" name="representative" <?php echo $btnstatus; ?>>
                            <??>
                            <!-- <option value="<?php echo $repId ?>"><?php echo $representative_name; ?></option> -->
                            <?php
                              if (empty($repId)) {echo "<option></option>";}
                              else{echo "<option value=\"$repId\">$representative_name</option>";}
                              $run1 = mysqli_query($db, "SELECT * FROM users WHERE companyid = '$companyid' ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $rep_name = $row1['name'];
                                if ($repId == $id) { continue; }
                                echo"<option value=\"$id\">$rep_name</option>";
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <!-- 4th -->
                      <div class="form-row">
                        <!-- <div class="form-group col-md-6">
                          <label for="inputPassword4">Cargo full name</label>
                          <input type="text" class="form-control" name="cargo_bl_name" value="<?php echo $cargo_bl_name ?>" required>
                        </div> -->
                        <!--div class="form-group col-md-3">
                          <label for="inputEmail4">Fender On</label>
                          <input type="text" class="form-control" name="fender_on" value="<?php echo $fender_on; ?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Fender Off</label>
                          <input type="text" class="form-control" name="fender_off" value="<?php echo $fender_off; ?>">
                        </div-->
                        <div class="form-group col-md-3">
                          <label for="inputState">Reg No.</label>
                          <input type="text" class="form-control" name="rotation" value="<?php echo $rotation ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">Anchorage</label>
                          <select id="inputState" class="form-control" name="anchor" <?php echo $btnstatus; ?>>
                            <?php
                              if ($anchor == "Outer") {
                                echo"
                                  <option value=\"\">--Select----</option>
                                  <option value=\"Outer\" selected>Outer</option>
                                  <option value=\"Kutubdia\">Kutubdia</option>
                                ";
                              }
                              elseif ($anchor == "Kutubdia") {
                                echo"
                                  <option value=\"\">--Select----</option>
                                  <option value=\"Outer\">Outer</option>
                                  <option value=\"Kutubdia\" selected>Kutubdia</option>
                                ";
                              }
                              else{
                                echo"
                                  <option value=\"\">--Select----</option>
                                  <option value=\"Outer\">Outer</option>
                                  <option value=\"Kutubdia\">Kutubdia</option>
                                ";
                              }
                            ?>
                          </select>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="inputState">OPA</label>
                          <select id="inputState" class="form-control search" name="vsl_opa" <?php echo $btnstatus; ?>>
                            <?php
                              $company_name = allData('agent', $vsl_opa, 'company_name');
                              if ($company_name == "") {
                                echo"<option value=\"\">--Select--</option>";
                              }else{
                            ?>
                            <option value="<?php echo $vsl_opa; ?>"><?php echo $company_name; ?></option>
                            <?php }
                              $run1 = mysqli_query($db, "SELECT * FROM agent ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $company_name = $row1['company_name'];
                                if ($id == $vsl_opa) { continue; }
                                echo"<option value=\"$id\">$company_name</option>";
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <!-- 5th -->
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="inputState">Custom Survey</label>
                          <select id="inputState" class="form-control search" name="survey_custom" <?php echo $btnstatus; ?>>
                            <?php
                              $company_name = allData('surveycompany', $survey_custom, 'company_name');
                              if ($company_name == "") {
                                echo"<option value=\"\">--Select--</option>";
                              }else{
                            ?>
                            <option value="<?php echo $survey_custom; ?>"><?php echo $company_name; ?></option>
                            <?php }
                              $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $company_name = $row1['company_name'];
                                if ($id == $survey_custom) { continue; }
                                echo"<option value=\"$id\">$company_name</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">Consignee Survey</label>
                          <select id="inputState" class="form-control search" name="survey_consignee" <?php echo $btnstatus; ?>>
                            <?php
                              $company_name = allData('surveycompany', $survey_consignee, 'company_name');
                              if ($company_name == "") {
                                echo"<option value=\"\">--Select--</option>";
                              }else{
                            ?>
                            <option value="<?php echo $survey_consignee; ?>"><?php echo $company_name ?></option>
                            <?php }
                              $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $company_name = $row1['company_name'];
                                if ($id == $survey_consignee) { continue; }
                                echo"<option value=\"$id\">$company_name</option>";
                              }//echo"<option value=\"\">--Select--</option>";
                            ?>
                          </select>
                        </div>

                        <div class="form-group col-md-4">
                          <label for="inputState">Supplier Survey</label>
                          <select id="inputState" class="form-control search" name="survey_supplier" <?php echo $btnstatus; ?>>
                            <?php
                              $company_name = allData('surveycompany', $survey_supplier, 'company_name');
                              if ($company_name == "") {
                                echo"<option value=\"\">--Select--</option>";
                              }else{
                            ?>
                            <option value="<?php echo $survey_supplier; ?>"><?php echo $company_name ?></option>
                            <?php }
                              $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $company_name = $row1['company_name'];
                                if ($id == $survey_supplier) { continue; }
                                echo"<option value=\"$id\">$company_name</option>";
                              }//echo"<option value=\"\">--Select--</option>";
                            ?>
                          </select>
                        </div>
                      </div>

                      <!-- 6th -->
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="inputState">Owner Survey</label>
                          <select id="inputState" class="form-control search" name="survey_owner" <?php echo $btnstatus; ?>>
                            <?php
                              $company_name = allData('surveycompany', $survey_owner, 'company_name');
                              if ($company_name == "") {
                                echo"<option value=\"\">--Select--</option>";
                              }else{
                            ?>
                            <option value="<?php echo $survey_owner ?>"><?php echo $company_name; ?></option>
                            <?php }
                              $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $company_name = $row1['company_name'];
                                if ($id == $survey_owner) { continue; }
                                echo"<option value=\"$id\">$company_name</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">P&I Survey</label>
                          <select id="inputState" class="form-control search" name="survey_pni" <?php echo $btnstatus; ?>>
                            <?php
                              $company_name = allData('surveycompany', $survey_pni, 'company_name');
                              if ($company_name == "") {
                                echo"<option value=\"\">--Select--</option>";
                              }else{
                            ?>
                            <option value="<?php echo $survey_pni ?>"><?php echo $company_name; ?></option>
                            <?php }
                              $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $company_name = $row1['company_name'];
                                if ($id == $survey_pni) { continue; }
                                echo"<option value=\"$id\">$company_name</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">Chattrer Survey</label>
                          <select id="inputState" class="form-control search" name="survey_chattrer" <?php echo $btnstatus; ?>>
                            <?php
                              $company_name = allData('surveycompany', $survey_chattrer, 'company_name');
                              if ($company_name == "") {
                                echo"<option value=\"\">--Select--</option>";
                              }else{
                            ?>
                            <option value="<?php echo $survey_chattrer ?>"><?php echo $company_name; ?></option>
                            <?php }
                              $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $company_name = $row1['company_name'];
                                if ($id == $survey_chattrer) { continue; }
                                echo"<option value=\"$id\">$company_name</option>";
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <!-- 7th -->
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inputState">Received By</label>
                          <select id="inputState" class="form-control search" name="received_by" <?php echo $btnstatus; ?>>
                            
                            <?php
                              if (empty($received_by)) {echo "<option></option>";}
                              else{echo "<option value=\"$received_by\">$rcvbynm</option>";}
                              $run1 = mysqli_query($db, "SELECT * FROM users WHERE companyid = '$companyid' ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $name = $row1['name'];
                                if ($received_by == $id) { continue; }
                                echo"<option value=\"$id\">$name</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputState">Sailed By</label>
                          <select id="inputState" class="form-control search" name="sailed_by" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $sailed_by ?>"><?php echo $slbynm; ?></option>
                            <?php
                              if (empty($sailed_by)) {echo "<option></option>";}
                              else{echo "<option value=\"$sailed_by\">$slbynm</option>";}
                              $run1 = mysqli_query($db, "SELECT * FROM users WHERE companyid = '$companyid' ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $id = $row1['id']; $name = $row1['name'];
                                if ($sailed_by == $id) { continue; }
                                echo"<option value=\"$id\">$name</option>";
                              }
                            ?>
                          </select>
                        </div>
                      </div>












                      <!-- 8th -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="custom_visited" value="1" <?php echo $custom_v; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Custom visited
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="qurentine_visited" value="1" <?php echo $qurentine_v; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Qurentine visited
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="psc_visited" value="1" <?php echo $psc_v; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Psc Visited
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="fender" value="1" <?php echo $fender_us; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Fender
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew_change" value="1" <?php echo $crew_c; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Crew Change
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_grab" value="1" <?php echo $has_g; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Has Grab
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="fresh_water" value="1" <?php echo $fresh_w; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Fresh Water
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="piloting" value="1" <?php echo $piloting_us; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Piloting
                            </label>
                          </div>
                        </div>
                      </div>


                      <div class="form-row">

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="multiple_lightdues" value="1" <?php echo $multiple_l; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              Multiple Lightdues
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sscec" value="1" <?php echo $sscec_ck; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              SSCEC
                            </label>
                          </div>
                        </div>

                        <div class="form-group col-md-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="egm" value="1" <?php echo $egm_ck; ?> <?php echo $btnstatus; ?> >
                            <label class="form-check-label" for="flexCheckDefault">
                              EGM
                            </label>
                          </div>
                        </div>

                      </div>
















                      <!-- 9th -->
                      <div class="form-group">
                        <label for="exampleInputPassword1">Insert / Write Remarks</label>
                        <!-- <input type="text" name="bank_name" class="form-control" required placeholder="BANK NAME"> -->
                        <textarea name="remarks" class="form-control" rows="3" <?php echo $btnstatus; ?>><?php echo allData('vessels', $vesselId, 'remarks'); ?></textarea>
                      </div>

                      <button type="submit" name="vslUpdate" class="btn btn-success" <?php echo $btnstatus; ?>>Update</button>
                      <!-- <a href="vessel_details.php?vsl_num=<?php echo $vsl_num; ?>" class="btn btn-primary">Cancel</a> -->
                    </form>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>


        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Vessels Cargo </strong>
                    <!-- <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addCargo">+ Add Cargo</button> -->
                    <?php $id = $vsl_num; ?>

                    <!-- Modal -->
                    <!-- <div class="modal fade" id="addCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Insert Cargo Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" style="padding-left: 15px; padding-right: 15px;" action="vessel_details.php?edit=<?php echo $msl_num; ?>">
                            <div class="modal-body">
                              <input type="hidden" name="vesselId" value="<?php echo $id; ?>">
                              <input type="hidden" name="msl_num" value="<?php echo $msl_num; ?>">
                              <div class="form-row">
                                <div class="form-group col-md-3">
                                  <label for="inputState">Select Cargo</label>
                                  <select id="inputState" name="cargokey" class="form-control search" required>
                                    <option value="">--Select--</option>
                                    <?php selectOptions('cargokeys', 'name'); ?>
                                  </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="inputState">Select Loadport</label>
                                  <select id="inputState" name="loadport" class="form-control search" required>
                                    <option value="">--Select--</option>
                                    <?php selectOptions('loadport', 'port_name'); ?>
                                  </select>
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="inputState">Quantity</label>
                                  <input type="number" step="any" class="form-control" name="quantity" required>
                                </div>
                              </div>
                              
                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputState">Cargo Bl Name</label>
                                  <input type="text" class="form-control" name="cargo_bl_name" required>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addCargoConsigneewise" class="btn btn-success">
                                +ADD
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div> -->
                  </div>
                  <div class="block-body">
                    <table class="table table-dark">
                      <thead>
                        <tr>
                          <th class="col-1" scope="col">Cargo</th>
                          <th class="col-2" scope="col">Port</th>
                          <th class="col-2" scope="col">Qty</th>
                          <th class="col-5" scope="col">Cargo Name</th>
                          <th class="col-2" scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php vesselCargo($vsl_num); ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>


        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Surveyors</strong>
                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addSurveyor" <?php echo $btnstatus; ?>>+ Add Surveyor</button>
                

                    <!-- Modal -->
                    <div class="modal fade" id="addSurveyor" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Insert Bin Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                            <input type="hidden" name="vesselId" value="<?php echo $vsl_num; ?>">
                            <div class="modal-body">
                              
                              <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <!-- <label for="inputState">Survey Company</label>
                                  <select id="inputState" class="form-control search" name="survey_company" required>
                                    <option value="">--Select--</option>
                                    <?php // selectOptions('surveycompany', 'company_name'); ?>
                                  </select> -->
                                  <label for="inputState">Party</label>
                                  <select id="inputState" class="form-control search" name="party" <?php echo $btnstatus; ?>>
                                    <option value="">--Select--</option>
                                    <?php
                                    $run = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
                                    $row = mysqli_fetch_assoc($run); 
                                    $custom = $row['survey_custom'];
                                    $consignee = $row['survey_consignee'];
                                    $supplier = $row['survey_supplier'];
                                    $pni = $row['survey_pni'];
                                    $chattrer = $row['survey_chattrer'];
                                    $owner = $row['survey_owner'];
                                    if ($custom != 0) {
                                      echo "<option value=\"survey_custom\">Custom</option>";
                                    }if ($consignee != 0) {
                                      echo "<option value=\"survey_consignee\">Consignee</option>";
                                    }if ($supplier != 0) {
                                      echo "<option value=\"survey_supplier\">Supplier</option>";
                                    }if ($owner != 0) {
                                      echo "<option value=\"survey_owner\">Owner</option>";
                                    }if ($pni != 0) {
                                      echo "<option value=\"survey_pni\">PNI</option>";
                                    }if ($chattrer != 0) {
                                      echo "<option value=\"survey_chattrer\">Chattrer</option>";
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="inputState">Surviour</label>
                                  <select id="inputState" class="form-control search" name="surveyorId" required <?php echo $btnstatus; ?>>
                                    <option value="">--Select--</option>
                                    <?php selectOptions('surveyors', 'surveyor_name'); ?>
                                  </select>
                                </div>

                              </div>
                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputState">Survey Purpose</label>
                                  <select id="inputState" class="form-control search" name="survey_purpose" required <?php echo $btnstatus; ?>>
                                    <option value="">--Select--</option>
                                    <option value="Load Draft">Load Draft</option>
                                    <option value="Rob">Rob</option>
                                    <option value="Light Draft">Light Draft</option>
                                  </select>
                                </div>
                              </div>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="addVesselsSurveyor" class="btn btn-success" <?php echo $btnstatus; ?>>
                                +ADD
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="block-body">
                    <table class="table table-dark">
                      <thead>
                        <tr>
                          <th class="col-2" scope="col">Survey Party</th>
                          <th class="col-3" scope="col">Survey Company</th>
                          <th class="col-2" scope="col">Purpose</th>
                          <th class="col-3" scope="col">Surveyor</th>
                          <th class="col-2" scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php vesselSurveyors($vsl_num, "all"); ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>

        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Add C&F</strong>
                    <!-- <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addCNF">+ Add C&F</button>
                

                    
                    <div class="modal fade" id="addCNF" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Insert Cnf Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="vessel_details.php?edit=<?php echo $msl_num; ?>">
                            <div class="modal-body">
                              <input type="hidden" name="msl_num" value="<?php echo $msl_num; ?>">
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <label for="inputState">Select Importer</label>
                                  <select id="inputState" name="importer" class="form-control search">
                                    <option value="">--Select--</option>
                                    <?php 
                                      //selectOptions('cnf', 'name'); 
                                      $run = mysqli_query($db, "SELECT * FROM vessels_importer WHERE msl_num = '$msl_num' ");
                                      while ($row = mysqli_fetch_assoc($run)) {
                                        $impId = $row['importer']; $impName = allData('bins', $impId, 'name');
                                        echo "<option value=\"$impId\">$impName</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="inputState">Select CNF</label>
                                  <select id="inputState" name="cnfId" class="form-control search">
                                    <option value="">--Select--</option>
                                    <?php selectOptions('cnf', 'name'); ?>
                                  </select>
                                </div>
                              </div>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addVesselsCnf" class="btn btn-success">
                                +ADD
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div> -->
                  </div>
                  <div class="block-body">
                    <table class="table table-dark">
                      <thead>
                        <tr>
                          <th class="col-5" scope="col">Importer</th>
                          <th class="col-5" scope="col">Cnf</th>
                          <th class="col-2" scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php vesselsCnf($vsl_num); ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
        

        <?php
          // edit vessels_surveyor
          $run = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num'");
          while ($row = mysqli_fetch_assoc($run)) {
            $id = $row['id']; $party = $row['survey_party'];
            $surveyor = $row['surveyor']; $survey_purpose = $row['survey_purpose'];
            $surveyor_name = allData('surveyors', $surveyor, 'surveyor_name');
        ?>
        <!-- Consignee Edit Modal -->
        <div class="modal fade" id="<?php echo"editVesselSurveyors".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Insert Surveyor Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                <div class="modal-body">
                  
                  <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                  <input type="hidden" name="thisrowId" value="<?php echo $id; ?>">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <!-- <label for="inputState">Survey Company</label>
                      <select id="inputState" class="form-control search" name="survey_company" required>
                        <option value="">--Select--</option>
                        <?php // selectOptions('surveycompany', 'company_name'); ?>
                      </select> -->
                      <label for="inputState">Party</label>
                      <input type="hidden" name="party" value="<?php echo $party ?>">
                      <select id="inputState" class="form-control search" name="party" disabled>
                        <option value="<?php echo $party ?>"><?php echo $party ?></option>
                        <option value="survey_custom">Custom</option>
                        <option value="survey_consignee">Consignee</option>
                        <option value="survey_owner">Owner</option>
                        <option value="survey_pni">PNI</option>
                        <option value="survey_chattrer">Chattrer</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputState">Surviour</label>
                      <select id="inputState" class="form-control search" name="surveyorId" <?php echo $btnstatus; ?>>
                        <option value="<?php echo $surveyor ?>"><?php echo $surveyor_name; ?></option>
                        <?php selectOptions('surveyors', 'surveyor_name'); ?>
                      </select>
                    </div>

                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="inputState">Survey Purpose</label>
                      <select id="inputState" class="form-control search" name="survey_purpose" required <?php echo $btnstatus; ?>>
                        <option value="<?php echo $survey_purpose; ?>"><?php echo $survey_purpose; ?></option>
                        <option value="Load Draft">Load Draft</option>
                        <option value="Rob">Rob</option>
                        <option value="Light Draft">Light Draft</option>
                      </select>
                    </div>
                  </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="submit" name="update_vessels_surveyor" class="btn btn-success" <?php echo $btnstatus; ?>>
                    +Update
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php } ?>


        <?php
          // edit vessel_cargo
          $run = mysqli_query($db, "SELECT * FROM vessels_cargo WHERE vsl_num = '$vsl_num'");
          while ($row = mysqli_fetch_assoc($run)) {
            if(isset($_GET['vsl_num'])){$vsl_num=$_GET['vsl_num'];}else{$vsl_num=$_GET['edit'];}
            $vessel=allData("vessels",$vsl_num,"vessel_name");
            $id = $row['id']; $cargo_key = $row['cargo_key']; $loadport = $row['loadport']; 
            $quantity = $row['quantity']; $cargo_bl_name = $row['cargo_bl_name']; 
            $cargo = allData('cargokeys', $cargo_key, 'name');
            $loadportnm = allData('loadport', $loadport, 'port_name');
        ?>
        <!-- Consignee Edit Modal -->
        <div class="modal fade" id="<?php echo"editVesselCargo".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Insert Cargo Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" style="padding-left: 15px; padding-right: 15px;" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="inputState">Select Cargo</label>
                    <select id="inputState" name="cargokey" class="form-control search" required>
                      <option value="<?php echo $cargo_key ?>"><?php echo $cargo; ?></option>
                      <?php selectOptions('cargokeys', 'name'); ?>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputState">Select Loadport</label>
                    <select id="inputState" name="loadport" class="form-control search" required>
                      <option value="<?php echo $loadport; ?>"><?php echo $loadportnm; ?></option>
                      <?php selectOptions('loadport', 'port_name'); ?>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="inputState">Quantity</label>
                    <input type="number" step="any" class="form-control" value="<?php echo $quantity; ?>" name="quantity" required>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="inputState">Cargo Bl Name</label>
                    <input type="text" class="form-control" name="cargo_bl_name" value="<?php echo $cargo_bl_name; ?>" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" name="updateCargoConsigneewise" class="btn btn-success">
                    +Update
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php } ?>


        <?php
          // edit vessels_cnf
          $run = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num'");
          while ($row = mysqli_fetch_assoc($run)) {
            $id = $row['id']; 
            if(isset($_GET['vsl_num'])){$vsl_num=$_GET['vsl_num'];}else{$vsl_num=$_GET['edit'];} 
            $importerId = $row['importer']; 
            $cnfId = $row['cnf']; 
            $cnfName = allData('cnf', $cnfId, 'name');
            $importerName = allData('bins', $importerId, 'name');
        ?>
        <!-- Consignee Edit Modal -->
        <div class="modal fade" id="<?php echo"editVesselsCnf".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Insert CNF Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                <div class="modal-body">
                  
                  <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                  <input type="hidden" name="thisrowId" value="<?php echo $id; ?>">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputState">Importer</label>
                      <select id="inputState" class="form-control selectpicker" multiple name="importers[]" data-live-search="true" required <?php echo $btnstatus; ?>>
                        <!-- <option value="<?php echo $importerId; ?>"><?php echo $importerName; ?></option> -->
                        <?php 
                          $run5 = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
                          while ($row5 = mysqli_fetch_assoc($run5)) {
                            $thisid = $row5['id']; $imid = $row5['importer']; $cn = $row5['cnf'];

                            // select importer
                            if($cn==$cnfId && $cnfId != 0){$selected="selected";}
                            else{$selected = "";}
                            if($imid==$importerId && $importerId != 0){$selected="selected";}
                            
                            $impId = $row5['importer']; $impName = allData('bins', $impId, 'name');
                            echo "<option value=\"$impId\" $selected>$impName</option>";
                          }
                        ?>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputState">Cnf</label>
                      <select id="inputState" class="form-control search" name="cnfId" required <?php echo $btnstatus; ?>>
                        <option value="<?php echo $cnfId ?>"><?php echo $cnfName; ?></option>
                        <?php selectOptions('cnf', 'name'); ?>
                      </select>
                    </div>
                  </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="submit" name="update_vessels_cnf" class="btn btn-success" <?php echo $btnstatus; ?>>
                    +Update
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php } ?>

        <!-- end elseif ship_perticular -->
        <?php }elseif(isset($_GET['ship_perticular'])){ 
          $ship_perticularId = $vsl_imo = $vsl_call_sign = $vsl_mmsi_number = $vsl_class = $vsl_nationality = $vsl_registry = $vsl_official_number = $vsl_nrt = $vsl_grt = $vsl_dead_weight = $vsl_breth = $vsl_depth = $vsl_loa = $vsl_pni = $vsl_owner_name = $vsl_owner_address = $vsl_owner_email = $vsl_operator_name = $vsl_operator_address = $year_of_built = $number_of_hatches_cranes = $vsl_nature = $vsl_cargo = $vsl_cargo_name = $shipper_name = $shipper_address = $last_port = $next_port = $with_retention = $capt_name = $number_of_crew = $crgoname = $crgoqtyname = "";
          $vsl_num = $_GET['ship_perticular'];
          $companyid = $my['companyid']; 
          $vesselsCompany = allData('vessels', $vsl_num, 'companyid');
          $msl_num = allData('vessels', $vsl_num, 'msl_num');


          $run3 = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
          if ($companyid == $vesselsCompany && mysqli_num_rows($run3) > 0) {
            $row3 = mysqli_fetch_assoc($run3);
            $ship_perticularId = $row3['id']; 
            $vsl_imo = $row3['vsl_imo'];
            $vsl_call_sign = $row3['vsl_call_sign'];
            $vsl_mmsi_number = $row3['vsl_mmsi_number'];
            $vsl_class = $row3['vsl_class'];
            $vsl_official_number = $row3['vsl_official_number'];
            $vsl_nrt = $row3['vsl_nrt'];
            $vsl_grt = $row3['vsl_grt'];
            $vsl_dead_weight = $row3['vsl_dead_weight'];
            $vsl_breth = $row3['vsl_breth'];
            $vsl_depth = $row3['vsl_depth'];
            $vsl_loa = $row3['vsl_loa'];
            $vsl_pni = $row3['vsl_pni'];
            $vsl_owner_name = $row3['vsl_owner_name'];
            $vsl_owner_address = $row3['vsl_owner_address'];
            $vsl_owner_email = $row3['vsl_owner_email'];
            $vsl_operator_name = $row3['vsl_operator_name'];
            $vsl_operator_address = $row3['vsl_operator_address'];

            $year_of_built = $row3['year_of_built'];
            if (empty($row3['number_of_hatches_cranes'])) {
              $number_of_hatches_cranes = "05 HATCHES / 04 CRANES";
            }else{$number_of_hatches_cranes = $row3['number_of_hatches_cranes'];}

            $vsl_nature = $row3['vsl_nature'];
            $packages_codes = $row3['packages_codes'];
            
            if (empty($row3['packages_codes'])) {$packages_codes = "VR"; }
            else{$packages_codes = $row3['packages_codes'];}
            // $vsl_cargo = $row3['vsl_cargo'];
            // $vsl_cargo_name = $row3['vsl_cargo_name'];

            if (!empty($row3['vsl_cargo'])) {$vsl_cargo = $row3['vsl_cargo'];}
            else{$vsl_cargo =  forwadingcrgodesc($vsl_num);}
            if (!empty($row3['vsl_cargo_name'])) {$vsl_cargo_name = $row3['vsl_cargo_name'];}
            else{$vsl_cargo_name = forwadingcrgodesc($vsl_num, "onlyname");}

            // $vsl_registry = $row3['vsl_registry'];

            if (!empty($row3['vsl_nationality'])) {
              $nport_id = $row3['vsl_nationality'];
              $nport_name = allData('nationality', $nport_id, 'port_name');
            }else{$nport_id = $nport_name = "";}

            if (!empty($row3['vsl_registry'])) {
              $rport_id = $row3['vsl_registry'];
              $rport_name = allData('nationality', $rport_id, 'port_name');
            }else{$rport_id = $rport_name = "";}

            if (!empty($row3['shipper_name'])) {
              $shipper_name = $row3['shipper_name'];
            }else{
              $shipper_name = allDataUpdated('vessels_bl', 'vsl_num', $vsl_num, 'shipper_name');
            }
            if (!empty($row3['shipper_address'])) {
              $shipper_address = $row3['shipper_address'];
            }else{
              $shipper_address = allDataUpdated('vessels_bl', 'vsl_num', $vsl_num, 'shipper_address');
            }
            
            if (empty($row3['last_port'])) {
                
              if (mysqli_num_rows(mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ")) > 0) {
                $row4=mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num' );"));
                $dep_date=$row4['issue_date'];
                $load_port=$row4['load_port'];
                $cargo_name=$row4['cargo_name'];
                $dep_day = dbtime($dep_date, "d"); 
                $dep_month = dbtime($dep_date, "m"); 
                $dep_year = dbtime($dep_date, "Y"); 
                $port_name = allData('loadport', $load_port, 'port_name');
                $port_code = allData('loadport', $load_port, 'port_code');

                $lastportid = $load_port; 
                $last_port = allData('loadport', $load_port, 'port_name');
              }else{
                if (exist("vessels_cargo", "vsl_num = $vsl_num")) {
                  $lastportid = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'loadport');
                  $last_port = allData('loadport', $lastportid, 'port_name');
                }
              }
            }else{
              $lastportid = $row3['last_port'];
              $last_port = allData('loadport', $lastportid, 'port_name');
            }
            
            $next_port = $row3['next_port'];
            $with_retention = $row3['with_retention'];
            $capt_name = $row3['capt_name'];
            $number_of_crew = $row3['number_of_crew'];
          
        ?>
          <style type="text/css">
            .table-custom td, .table-custom th{
              border: none;
            }
          </style>
          <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Ship Perticular Of MV. <?php echo $vessel; ?> </strong>
                    <!-- <a 
                      onClick="javascript: return confirm('Please confirm deletion');" 
                      href="index.php?del_msl_num=<?php echo $msl_num; ?>" 
                      class="btn btn-danger btn-sm"
                       style="float: right;"
                    ><i class="bi bi-trash"></i></a> -->
                    <!-- <a href="vessel_details.php?edit=<?php echo $vsl_num; ?>" class="btn btn-success btn-sm" style="float: right; margin-right: 10px;">
                      <i class="fas fa-file-upload"></i> Upload
                    </a> -->
                    <?php
                      $skipList = ['ship_perticular', 'pni_cer', 'crew_list'];
                      $folder = "forwadings/auto_forwardings/".$thisvessel['msl_num'].".MV. ".$thisvessel['vessel_name']."/";
                      if(!checkfileexist($folder, $skipList)){ 
                    ?>
                    <button class="btn btn-success btn-sm" style="float: right; margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#uploadshipperticular">
                      <i class="fas fa-file-upload"></i> Upload
                    </button>
                    <?php } ?>

                    <!-- Modal Filter -->
                    <div class="modal fade" id="uploadshipperticular" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Please Upload Ship perticular, Crew list and P&I Certificate</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form method="post" action="<?php echo pagename().pageurl(); ?>" enctype="multipart/form-data">
                            <div class="modal-body">
                              <?php if(!checkfileexist($folder, ['ship_perticular'])){ ?>
                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputEmail4">Ship Perticular</label>
                                  <input type="file" class="form-control" name="shipperticular">
                                </div>
                              </div>
                              <?php } ?>

                              <?php if(!checkfileexist($folder, ['crew_list'])){ ?>
                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputEmail4">Crew List</label>
                                  <input type="file" class="form-control" name="crewlist">
                                </div>
                              </div>
                              <?php } ?>

                              <?php if(!checkfileexist($folder, ['pni_cer'])){ ?>
                              <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputEmail4">P&I Certificate</label>
                                  <input type="file" class="form-control" name="pnicer">
                                </div>
                              </div>
                              <?php } ?>
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" name="uploadshipperticular" class="btn btn-success"><i class="fas fa-file-upload"></i> &nbsp; Upload</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="block-body">

                    <form method="post" action="vessel_details.php?ship_perticular=<?php echo $vsl_num; ?>">
                      <!-- 1st -->
                      <div class="form-row">
                        <div class="form-group col-md-1">
                          <label for="inputEmail4">Voyage</label>
                          <input type="hidden" name="ship_perticularId" value="<?php echo $ship_perticularId; ?>">
                          <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                          <input type="text" class="form-control" name="vsl_num" disabled value="<?php echo $msl_num; ?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Class </label>
                          <input type="text" class="form-control" name="vsl_class" value="<?php echo $vsl_class; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label>IMO No.</label>
                          <input type="text" class="form-control" name="vsl_imo" required value="<?php echo $vsl_imo ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Call Sign</label>
                          <input type="text" class="form-control" name="vsl_call_sign" value="<?php echo $vsl_call_sign; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">MMSI Number</label>
                          <!-- <label>(same</label>
                          <input type="checkbox" name=""> -->
                          <input type="text" class="form-control" name="vsl_mmsi_number" value="<?php echo $vsl_mmsi_number; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputPassword4">Official No</label>
                          <input type="text" step="any" class="form-control" name="vsl_official_number" value="<?php echo $vsl_official_number ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 2nd -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Vessel Nationality / Flag</label>
                          <!-- <input type="text" step="any" class="form-control" name="vsl_nationality" value="<?php echo $vsl_nationality ?>"> -->
                          <select id="inputState" name="vsl_nationality" class="form-control search" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $nport_id; ?>"><?php echo $nport_name; ?></option>
                            <?php 
                              $run4 = mysqli_query($db, "SELECT * FROM nationality ");
                              while ($row4 = mysqli_fetch_assoc($run4)) {
                                $id = $row4['id']; $vsl_nationality = $row4['port_name'];
                                echo"<option value=\"$id\">$vsl_nationality</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputPassword4">Port Of Registry</label>
                          <!-- <input type="text" step="any" class="form-control" name="vsl_registry" value="<?php echo $vsl_registry ?>"> -->
                          <select id="inputState" name="vsl_registry" class="form-control search" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $rport_id; ?>"><?php echo $rport_name; ?></option>
                            <?php 
                              $run4 = mysqli_query($db, "SELECT * FROM nationality ");
                              while ($row4 = mysqli_fetch_assoc($run4)) {
                                $id = $row4['id']; $vsl_registry = $row4['port_name'];
                                echo"<option value=\"$id\">$vsl_registry</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputState">GRT</label>
                          <input type="number" step="any" class="form-control" name="vsl_grt" value="<?php echo $vsl_grt ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputState">NRT</label>
                          <input type="number" step="any" class="form-control" name="vsl_nrt" value="<?php echo $vsl_nrt ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">Dead Weight</label>
                          <input type="text" step="any" class="form-control" name="vsl_dead_weight" value="<?php echo $vsl_dead_weight ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Breath</label>
                          <input type="text" step="any" class="form-control" name="vsl_breth" value="<?php echo $vsl_breth ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Depth</label>
                          <input type="text" step="any" class="form-control" name="vsl_depth" value="<?php echo $vsl_depth ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">LOA</label>
                          <input type="text" step="any" class="form-control" name="vsl_loa" value="<?php echo $vsl_loa ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">P&I</label>
                          <input type="text" step="any" class="form-control" name="vsl_pni" value="<?php echo $vsl_pni ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-2">
                          <label for="inputPassword4">YEAR OF BUILT</label>
                          <input type="text" step="any" class="form-control" name="year_of_built" value="<?php echo $year_of_built ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">NUMBER OF HATCHES / CRANES</label>
                          <input type="text" step="any" class="form-control" name="number_of_hatches_cranes" value="<?php echo $number_of_hatches_cranes ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputState">Owner Name</label>
                          <input type="text" step="any" class="form-control" name="vsl_owner_name" value="<?php echo $vsl_owner_name ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 3rd -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Owner Email</label>
                          <input type="text" step="any" class="form-control" name="vsl_owner_email" value="<?php echo $vsl_owner_email ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-9">
                          <label for="inputState">Owner Address</label>
                          <input type="text" step="any" class="form-control" name="vsl_owner_address" value="<?php echo $vsl_owner_address ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 4th -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Operator Name</label>
                          <input type="text" step="any" class="form-control" name="vsl_operator_name" value="<?php echo $vsl_operator_name ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-9">
                          <label for="inputState">Operator Address</label>
                          <input type="text" step="any" class="form-control" name="vsl_operator_address" value="<?php echo $vsl_operator_address ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 5th -->
                      <div class="form-row">
                        <div class="form-group col-md-9">
                          <label for="inputEmail4">Vessel Cargo (Qty + Name)</label>
                          <input type="text" step="any" class="form-control" name="vsl_cargo" value="<?php echo $vsl_cargo; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Next Port</label>
                          <!-- <input type="text" step="any" class="form-control" name="next_port" value="<?php echo $next_port ?>">

                          <label for="inputState">Select Loadport</label> -->
                          <select id="inputState" name="next_port" class="form-control search" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $next_port; ?>"><?php echo $next_port; ?></option>
                            <?php 
                              $run4 = mysqli_query($db, "SELECT * FROM loadport ");
                              while ($row4 = mysqli_fetch_assoc($run4)) {
                                $id = $row4['id']; $next_port = $row4['port_name'];
                                echo"<option value=\"$next_port\">$next_port</option>";
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-9">
                          <label for="inputPassword4">Cargo Name</label>
                          <input type="text" step="any" class="form-control" name="vsl_cargo_name" value="<?php echo $vsl_cargo_name; ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">With Retention</label>
                          <input type="text" step="any" class="form-control" name="with_retention" value="<?php echo $with_retention ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="inputEmail4">Nature</label>
                          <input type="text" step="any" class="form-control" placeholder="BULK" name="vsl_nature" value="<?php echo $vsl_nature ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-8">
                          <label for="inputState">Shipper Name</label>
                          <input type="text" step="any" class="form-control" name="shipper_name" value="<?php echo $shipper_name ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 6th -->
                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="inputEmail4">Shipper Address</label>
                          <input type="text" step="any" class="form-control" name="shipper_address" value="<?php echo $shipper_address ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>
                      
                      <!-- 7th -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Last Port</label>
                          <!-- <input type="text" step="any" class="form-control" name="last_port" value="<?php echo $last_port ?>"> -->

                          <select id="inputState" name="last_port" class="form-control search" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $lastportid; ?>"><?php echo $last_port; ?></option>
                            <?php 
                              $run4 = mysqli_query($db, "SELECT * FROM loadport ");
                              while ($row4 = mysqli_fetch_assoc($run4)) {
                                $thislp_id = $row4['id']; $this_lp = $row4['port_name'];
                                echo"<option value=\"$thislp_id\">$this_lp</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Packages codes</label>
                          <input type="text" step="any" class="form-control" name="packages_codes" value="<?php echo $packages_codes ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-5">
                          <label for="inputEmail4">Name of Captain</label>
                          <input type="text" step="any" class="form-control" placeholder="Without 'CAPT. ' word " name="capt_name" value="<?php echo $capt_name ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Number of Crew</label>
                          <input type="text" step="any" class="form-control" name="number_of_crew" value="<?php echo $number_of_crew ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <button type="submit" name="ship_perticular_update" class="btn btn-success" <?php echo $btnstatus; ?>>Update</button>
                      <!-- <a href="vessel_details.php?ship_perticular=<?php echo $vsl_num; ?>" class="btn btn-primary">Cancel</a> -->
                    </form>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>




        <?php if($my['email'] == "shukurs920@gmail.com" || $my['email'] == "skturan2405@gmail.com"){ ?>
        <!-- download files section -->
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Download Files Of MV. <?php echo $vessel; ?></strong>
                    <?php
                      $folder = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/"; // Folder location

                      // Check if the folder exists
                      if (is_dir($folder)) {
                        $files = scandir($folder); // Get all entries in the folder
                        $files = array_filter($files, function($file) use ($folder) {
                            // Include only actual files and exclude temporary files starting with "~$"
                            return is_file($folder . $file) && strpos($file, '~$') !== 0;
                        });

                        if (empty($files)) {
                            echo "
                            <a href=\"vessel_details.php?forwadingpage=$vsl_num\" class=\"btn btn-secondary btn-sm\" style=\"float: right; margin-right: 10px;\">
                                <i class=\"icon-ink\"></i> Refresh
                            </a>
                            ";
                        }else { ?>
                          <?php if(allData('useraccess',$my['office_position'],'forwading_ctrl')){  ?>
                          <a href="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>&&df=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right; margin-right: 10px;">
                              <i class="icon-ink"></i> Download All
                          </a>
                          <?php } ?>
                        <?php }
                      } else {/*echo "The folder does not exist.";*/}
                      ?>
                    

                  </div>

                  <div class="block-body">

                    <div class="table-responsive"> 
                      <?php
                        $folder = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/"; // Folder location

                        // Check if the folder exists
                        if (is_dir($folder)) {
                          $files = scandir($folder); // Get all entries in the folder
                          $files = array_filter($files, function($file) use ($folder) {
                              // Include only actual files and exclude temporary files starting with "~$"
                              return is_file($folder . $file) && strpos($file, '~$') !== 0;
                          });

                          // check to se if downloadable file exist
                          $skipList = ['ship_perticular', 'pni_cer', 'crew_list'];

                          // Flag to check if there’s anything left to download
                          $hasDownloadableFiles = false; 
                          foreach ($files as $file) {
                            // Extract filename without extension
                            $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
                            // Skip specific base filenames
                            if (!in_array($nameWithoutExt, $skipList)){continue;}
                            // We have at least one downloadable file
                            $hasDownloadableFiles = true; 
                          }

                          if (empty($files)) {echo "No files available for download. $folder";}
                          // If nothing valid to download was found
                          elseif(!$hasDownloadableFiles){echo "No files available for download. $folder";}
                          else { ?>
                            <table class="table table-dark table-sm table-custom" id="downloads">
                              <thead>
                                <tr>
                                  <th colspan="8"><?php echo "<h3>Files in '$folder'</h3>"; ?></th>
                                </tr>
                              </thead>
                              <tbody>
                                <form method="post" action="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>">
                                  <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                                  <?php
                                  if (!allData("useraccess",$my['office_position'],"forwading_ctrl")){
                                    $tskbtnstatus = "disabled";
                                  }else{$tskbtnstatus = "enabled";}
                                  $skipList = ['ship_perticular', 'pni_cer', 'crew_list'];
                                  // Flag to check if there’s anything left to download
                                  $hasDownloadableFiles = false; 
                                  foreach ($files as $file) {
                                    // Extract filename without extension
                                    $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);

                                    // Skip specific base filenames
                                    if (!in_array($nameWithoutExt, $skipList)){continue;}
                                    // We have at least one downloadable file
                                    $hasDownloadableFiles = true;
                                    $filePath = $folder . $file; 
                                  ?>
                                    <tr style="border: 1px solid white;">
                                      <td colspan="6">
                                        <input type='hidden' name="<?php echo "$file"; ?>" value="<?php echo' . htmlspecialchars($file) . ' ?>">
                                        <?php echo "$file"; ?>
                                      </td>
                                      <td colspan="2">
                                        <button type="submit" class="form-control btn btn-success btn-sm" name="downloadfile" value="<?php echo "$file" ?>" style="color: white" <?php echo $tskbtnstatus; ?>>
                                          Download
                                        </button>
                                      </td>
                                    </tr> 
                                    <?php 
                                  } 
                                  ?>
                                </form> 
                              </tbody>
                            </table>
                          <?php }
                        } else {echo "No files available for download. $folder";}
                        ?>

                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- END download file section -->
        <?php } ?>




        <?php } else{ ?>
          <section class="no-padding-top">
            <div class="container-fluid">
              <div class="row">
                
                <!-- Form Elements -->
                <div class="col-lg-12">
                  <div class="block">
                    <div class="title">
                      <strong>Sorry, No Vessel Found! </strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        <?php } ?>


        <!-- blinputs -->
        <?php } elseif(isset($_GET['blinputs'])){ ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <?php// echo $msg; ?>
                    <strong>BL Informations of MV. <?php echo allData('vessels', $vsl_num, 'vessel_name'); ?></strong>

                    <div id="toolbar" class="select" style="width: 30%; margin-left: 120px; margin-top: -35px; display: none;">
                      <select class="form-control">
                        <option value="">Export Basic</option>
                        <option value="all">Export All</option>
                        <option value="selected">Export Selected</option>
                      </select>
                    </div>

                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addBl" <?php echo $btnstatus; ?>>+ Add BL Info</button>
                  </div>

                  <div class="table-responsive"> 
                    <table 
                      class="table table-dark table-striped table-sm"
                    >

                      <thead>
                        <tr role="row">
                          <th>Line</th>
                          <th>BL</th>
                          <th>Cargo</th>
                          <th>Loadport</th>
                          <th>QTY</th>
                          <?php if (allData('useraccess',$my['office_position'],'vessel_ctrl')){ ?>
                          <th style="text-align: center;">Actions</th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          vsl_bl($vsl_num); 
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php
            $vsl_num = $_GET['blinputs']; 
            $sql = "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ";
            $line_num = mysqli_num_rows(mysqli_query($db, $sql)) + 1;
            if (mysqli_num_rows(mysqli_query($db, $sql)) > 0) {
              $row6 = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ORDER BY id DESC LIMIT 1 "));
              $cargo_qty = $row6['cargo_qty']; $cargo_name = $row6['cargo_name'];
              $shipper_name = $row6['shipper_name']; $shipper_address = $row6['shipper_address']; 
              // $issue_date = $row6['issue_date']; 
              $issue_date = dbtimefotmat('Y-m-d', $row6['issue_date'], 'd/m/Y');
              $loadPortId = $row6['load_port'];
              $desc_portId = $row6['desc_port']; $desc_port = allData('loadport', $desc_portId, 'port_name');
              $receiverId = $row6['receiver_name']; $bankId = $row6['bank_name'];
              $cargokeyId = $row6['cargokeyId']; $cargokey = allData('cargokeys', $cargokeyId, 'name');
              $receiver_name = allData('bins', $receiverId, 'name');
              $receiver_bin = allData('bins', $receiverId, 'bin');
              $bank_name = allData('bins', $bankId, 'name');
              $bank_bin = allData('bins', $bankId, 'bin');
              $load_port = allData('loadport', $loadPortId, 'port_name');
            }else{
              $cargo_qty = $cargo_name = $shipper_name = $shipper_address = $issue_date = $receiverId = $bankId = $loadPortId = $cargokey = $cargokeyId = "";
              $receiver_name = $bank_name = $load_port = "--SELECT--";
            }
           
            $cargo_qty = $receiverId = $bankId = ""; 
            $receiver_name = $bank_name = "--SELECT--";

            $getvsldtls = mysqli_query($db, "SELECT bl_cargo, bl_cargokey, bl_shippername, bl_shipperaddress, bl_loadport, bl_issuedate FROM vessel_details WHERE vsl_num = '$vsl_num' ");
            $rowvsl = mysqli_fetch_assoc($getvsldtls);
            
            if($rowvsl['bl_cargo']==1){$ckbl_cargo="checked";} 
            else{$ckbl_cargo = $cargo_name = "";}
            if($rowvsl['bl_cargokey']==1){$ckbl_cargokey="checked";} 
            else{$ckbl_cargokey = $cargokey = $cargokeyId = "";}
            if($rowvsl['bl_shippername']==1){$ckbl_shippername="checked";} 
            else{$ckbl_shippername = $shipper_name = "";}
            if($rowvsl['bl_shipperaddress']==1){$ckbl_shipperaddress="checked";} 
            else{$ckbl_shipperaddress = $shipper_address = "";}
            if($rowvsl['bl_loadport']==1){$ckbl_loadport="checked";} 
            else{$ckbl_loadport = $loadPortId = $load_port = "";}
            if($rowvsl['bl_issuedate']==1){$ckbl_issuedate="checked";} 
            else{$ckbl_issuedate = $issue_date = "";}
          ?>
          <div class="modal fade" id="addBl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add BL info for IGM</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="post" action="vessel_details.php?blinputs=<?php echo $vsl_num ?>">
                  <input type="hidden" class="form-control" name="vsl_num" required value="<?php echo "$vsl_num"; ?>">
                  <div class="modal-body">
                    
                    <div class="form-row">
                      <div class="form-group col-md-1">
                        <label for="inputState">Line No</label>
                        <input type="hidden" class="form-control" name="line_num" required value="<?php echo "$line_num"; ?>">
                        <input type="text" class="form-control" disabled value="<?php echo "$line_num"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Bl No</label>
                        <input type="text" class="form-control" name="bl_num" required value="<?php //echo "$line_num"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Qty</label>
                        <input type="text" class="form-control" name="cargo_qty" required value="<?php echo "$cargo_qty"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-7">
                        <label for="inputState">Cargo Name <input type="checkbox" name="bl_cargo" value="1" <?php echo $ckbl_cargo; ?> <?php echo $btnstatus; ?>> (all same) </label>
                        <input type="text" class="form-control" name="cargo_name" required value="<?php echo "$cargo_name"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label for="inputState">Cargo Key <input type="checkbox" name="bl_cargokey" value="1" <?php echo $ckbl_cargokey; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <select class="form-control search" name="cargokey" required <?php echo $btnstatus; ?>>
                          <option value="<?php echo $cargokeyId ?>"><?php echo $cargokey; ?></option>
                          <?php selectOptions("cargokeys","name", "all"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-9">
                        <label for="inputState">Shipper Name <input type="checkbox" name="bl_shippername" value="1" <?php echo $ckbl_shippername; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" class="form-control" name="shipper_name" required value="<?php echo "$shipper_name"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="inputState">Shipper Address <input type="checkbox" name="bl_shipperaddress" value="1" <?php echo $ckbl_shipperaddress; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <textarea type="text" class="form-control" name="shipper_address" value="" <?php echo $btnstatus; ?>><?php echo "$shipper_address" ?></textarea>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inputState">Receiver</label>
                        <select id="inputState" class="form-control search" name="receiver_name" required <?php echo $btnstatus; ?>>
                          <option value="<?php echo $receiverId ?>"><?php echo $receiver_name; //echo" || ".$receiver_bin; ?></option>
                          <?php
                            $run1 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                            while ($row1 = mysqli_fetch_assoc($run1)) {
                              $id = $row1['id']; $receiver_name = $row1['name']; $binnum = $row1['bin'];
                              // echo"<option value=\"$id\">$receiver_name || <span>$binnum</span></option>";
                              echo"<option value=\"$id\"><span>$binnum</span></option>";
                            }
                          ?>
                        </select>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="inputState">Bank</label>
                        <select id="inputState" class="form-control search" name="bank_name" <?php echo $btnstatus; ?>>
                          <option value="<?php echo $bankId ?>"><?php echo $bank_name; //echo" || ".$bank_bin; ?></option>
                          <?php
                            $run2 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'BANK' ");
                            while ($row2 = mysqli_fetch_assoc($run2)) {
                              $idImporter = $row2['id']; $bank_name = $row2['name'];$bank_bin = $row2['bin'];
                              // echo"<option value=\"$idImporter\">$bank_name || <span>$bank_bin</span></option>";
                              echo"<option value=\"$idImporter\"><span>$bank_bin</span></option>";
                            }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputState">Load Port <input type="checkbox" name="bl_loadport" value="1" <?php echo $ckbl_loadport; ?> <?php echo $btnstatus; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <select class="form-control search" name="load_port" required <?php echo $btnstatus; ?>>
                          <option value="<?php echo $loadPortId ?>"><?php echo $load_port; ?></option>
                          <?php selectOptions("loadport","port_name", "all"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputEmail4">Issue Date <input type="checkbox" name="bl_issuedate" value="1" <?php echo $ckbl_issuedate; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" id="datepicker" class="form-control" name="issue_date" value="<?php echo $issue_date; ?>" required <?php echo $btnstatus; ?>>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Discharge Port</label>
                        <select class="form-control search" name="desc_port" required <?php echo $btnstatus; ?>>
                          <option value="65">CHITTAGONG, BANGLADESH</option>
                          <?php selectOptions("loadport","port_name", "all"); ?>
                        </select>
                      </div>
                    </div>

                      

                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="blinput" class="btn btn-primary" <?php echo $btnstatus; ?>>+ ADD</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  </div>
                </form>
              </div>
            </div>
          </div>


        <?php
          // checkbox status
          $vsl_num = $_GET['blinputs']; 
            $getvsldtls = mysqli_query($db, "SELECT bl_cargo, bl_cargokey, bl_shippername, bl_shipperaddress, bl_loadport, bl_issuedate FROM vessel_details WHERE vsl_num = '$vsl_num' ");
            $rowvsl = mysqli_fetch_assoc($getvsldtls);
            // $bl_cargo = $rowvsl['bl_cargo']; $bl_cargokey = $rowvsl['bl_cargokey'];
            // $bl_shippername = $rowvsl['bl_shippername']; $bl_shipperaddress = $rowvsl['bl_shipperaddress'];
            // $bl_loadport = $rowvsl['bl_loadport']; $bl_issuedate = $rowvsl['bl_issuedate'];
            if($rowvsl['bl_cargo']==1){$ckbl_cargo="checked";} else{$ckbl_cargo="";}
            if($rowvsl['bl_cargokey']==1){$ckbl_cargokey="checked";} else{$ckbl_cargokey="";}
            if($rowvsl['bl_shippername']==1){$ckbl_shippername="checked";} else{$ckbl_shippername="";}
            if($rowvsl['bl_shipperaddress']==1){$ckbl_shipperaddress="checked";} else{$ckbl_shipperaddress="";}
            if($rowvsl['bl_loadport']==1){$ckbl_loadport="checked";} else{$ckbl_loadport="";}
            if($rowvsl['bl_issuedate']==1){$ckbl_issuedate="checked";} else{$ckbl_issuedate="";}

          $total = 0;
          $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num'");
          while ($row = mysqli_fetch_assoc($run)) {
            $id = $row['id']; //
            $line_num = $row['line_num']; //
            $bl_num = $row['bl_num']; //
            $cargo_name = $row['cargo_name']; //
            $cargokeyId = $row['cargokeyId']; //
            $cargokey = allData('cargokeys', $cargokeyId, 'name');
            $cargo_qty = $row['cargo_qty']; //
            $loadPortId = $row['load_port'];
            $desc_portId = $row['desc_port'];
            $receiverId = $row['receiver_name'];
            $bankId = $row['bank_name'];
            $shipper_name = $row['shipper_name'];
            $shipper_address = $row['shipper_address'];
            // $issue_date = $row['issue_date'];
            $issue_date = dbtimefotmat('Y-m-d', $row['issue_date'], 'd/m/Y');
            $load_port = allData('loadport', $loadPortId, 'port_name');
            $desc_port = allData('loadport', $desc_portId, 'port_name');
            $port_code = allData('loadport', $loadPortId, 'port_code');
            $receiver_name = allData('bins', $receiverId, 'name');
            $rcvr_bin = allData('bins', $receiverId, 'bin');
            $bank_name = allData('bins', $bankId, 'name');
            $bnk_bin = allData('bins', $bankId, 'bin');
            $total = $total+$cargo_qty;
        ?>
        <div class="modal fade" id="<?php echo"editBlInput".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit BL Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="vessel_details.php?blinputs=<?php echo $vsl_num ?>">
                  <input type="hidden" class="form-control" name="vsl_num" required value="<?php echo "$vsl_num"; ?>">
                  <input type="hidden" class="form-control" name="blinputid" required value="<?php echo "$id"; ?>">
                  <input type="hidden" class="form-control" name="line_num" required value="<?php echo "$line_num"; ?>">
                  <div class="modal-body">
                    
                    <div class="form-row">
                      <div class="form-group col-md-1">
                        <label for="inputState">Line No</label>
                        <input type="text" class="form-control" name="line_num" required value="<?php echo "$line_num"; ?>" disabled>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Bl No</label>
                        <input type="text" class="form-control" name="bl_num" required value="<?php echo "$bl_num" ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Qty</label>
                        <input type="text" class="form-control" name="cargo_qty" required value="<?php echo "$cargo_qty" ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-7">
                        <label for="inputState">Cargo Name <input type="checkbox" name="bl_cargo" value="1" <?php echo $ckbl_cargo; ?> <?php echo $btnstatus; ?>> (all same) </label>
                        <input type="text" class="form-control" name="cargo_name" required value="<?php echo "$cargo_name" ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label for="inputState">Cargo key <input type="checkbox" name="bl_cargokey" value="1" <?php echo $ckbl_cargokey; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <select class="form-control search" name="cargokey" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($cargokeyId)){echo"<option></option>";}
                          else{echo "<option value=\"$cargokeyId\">$cargokey</option>";}
                          ?>
                          <?php selectOptions("cargokeys","name"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-9">
                        <label for="inputState">Shipper Name <input type="checkbox" name="bl_shippername" value="1" <?php echo $ckbl_shippername; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" class="form-control" name="shipper_name" required value="<?php echo "$shipper_name" ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="inputState">Shipper Address <input type="checkbox" name="bl_shipperaddress" value="1" <?php echo $ckbl_shipperaddress; ?>> (all same)</label>
                        <textarea type="text" class="form-control" name="shipper_address" value="" <?php echo $btnstatus; ?>><?php echo "$shipper_address" ?></textarea>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inputState">Receiver</label>
                        <select id="inputState" class="form-control search" name="receiver_name" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($receiverId)){echo"<option></option>";}
                          else{echo"<option value=\"$receiverId\">$receiver_name || $rcvr_bin</option>";}
                          ?>
                          <?php
                            $run1 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                            while ($row1 = mysqli_fetch_assoc($run1)) {
                              $id = $row1['id']; $receiver_name = $row1['name']; $binnum = $row1['bin'];
                              // echo"<option value=\"$id\">$receiver_name || <span>$binnum</span></option>";
                              echo"<option value=\"$id\"><span>$binnum</span></option>";
                            }
                          ?>
                        </select>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="inputState">Bank</label>
                        <select id="inputState" class="form-control search" name="bank_name" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($bankId)){echo"<option></option>";}
                          else{echo "<option value=\"$bankId\">$bank_name || $bnk_bin</option>";}
                          ?>
                          <?php
                            $run2 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'BANK' ");
                            while ($row2 = mysqli_fetch_assoc($run2)) {
                              $idImporter = $row2['id']; $bank_name = $row2['name'];$bank_bin = $row2['bin'];
                              echo"<option value=\"$idImporter\">$bank_name || <span>$bank_bin</span></option>";
                            }
                          ?>
                        </select>
                      </div>
                    </div> 

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputState">Load Port <input type="checkbox" name="bl_loadport" value="1" <?php echo $ckbl_loadport; ?>> (all same)</label>
                        <select class="form-control search" name="load_port" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($loadPortId)){echo"<option></option>";}
                          else{echo "<option value=\"$loadPortId\">$load_port</option>";}
                          ?>
                          <?php selectOptions("loadport","port_name"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-3">
                        <label for="inputEmail4">Issue Date <input type="checkbox" name="bl_issuedate" value="1" <?php echo $ckbl_issuedate; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" id="datepicker" class="form-control" name="issue_date" value="<?php echo $issue_date; ?>" <?php echo $btnstatus; ?>>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Discharge Port</label>
                        <select class="form-control search" name="desc_port" <?php echo $btnstatus; ?>>
                          <option value="<?php echo $desc_portId ?>"><?php echo $desc_port; ?></option>
                          <?php selectOptions("loadport","port_name"); ?>
                        </select>
                      </div>
                    </div>

                      

                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="blupdate" class="btn btn-primary" <?php echo $btnstatus; ?>>+ Update</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  </div>
                </form>
            </div>
          </div>
        </div>
      </section>
      <?php } ?>
      <!-- end blinputs -->


















      <!-- igminputs -->
        <?php } elseif(isset($_GET['igminputs'])){ ?>
        <?php
          $ship_perticularId = $vsl_imo = $vsl_call_sign = $vsl_mmsi_number = $vsl_class = $vsl_nationality = $vsl_registry = $vsl_official_number = $vsl_nrt = $vsl_grt = $vsl_dead_weight = $vsl_breth = $vsl_depth = $vsl_loa = $vsl_pni = $vsl_owner_name = $vsl_owner_address = $vsl_owner_email = $vsl_operator_name = $vsl_operator_address = $year_of_built = $number_of_hatches_cranes = $vsl_nature = $vsl_cargo = $vsl_cargo_name = $shipper_name = $shipper_address = $last_port = $next_port = $with_retention = $capt_name = $number_of_crew = $crgoname = $crgoqtyname = "";

          // $ship_perticularId = $thisvessel['ship_perticularId'];
          $vsl_num = $_GET['igminputs'];
          $companyid = $my['companyid']; 
          $vesselsCompany = $thisvessel['companyid'];

          // $vsl_imo = $thisvessel['imo'];
          // $nport_id = $thisvessel['nationalityid'];
          // $nport_name = $thisvessel['nationality'];
          // $rport_id = $thisvessel['registryid'];
          // $rport_name = $thisvessel['registry'];
          // $vsl_grt = $thisvessel['rawgrt'];
          // $vsl_nrt = $thisvessel['rawnrt'];
          // $lastportid = $thisvessel['lastportid'];
          // $last_port = $thisvessel['last_port'];
          // $packages_codes = $thisvessel['packages_codes'];
          // $capt_name = $thisvessel['capt_name'];

          $run3 = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
          if ($companyid == $vesselsCompany && mysqli_num_rows($run3) > 0) {
            $row3 = mysqli_fetch_assoc($run3);
            $ship_perticularId = $row3['id']; //
            $vsl_imo = $row3['vsl_imo'];//
            $vsl_nrt = $row3['vsl_nrt'];//
            $vsl_grt = $row3['vsl_grt'];//

            $vsl_nature = $row3['vsl_nature'];

            // 
            if (empty($row3['packages_codes'])) {$packages_codes = "VR"; }
            else{$packages_codes = $row3['packages_codes'];}

            // $vsl_registry = $row3['vsl_registry'];

            // 
            if (!empty($row3['vsl_nationality'])) {
              $nport_id = $row3['vsl_nationality'];
              $nport_name = allData('nationality', $nport_id, 'port_name');
            }else{$nport_id = $nport_name = "";}

            // 
            if (!empty($row3['vsl_registry'])) {
              $rport_id = $row3['vsl_registry'];
              $rport_name = allData('nationality', $rport_id, 'port_name');
            }else{$rport_id = $rport_name = "";}
            
            // 
            if (empty($row3['last_port'])) {
              if (mysqli_num_rows(mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ")) > 0) {
                $row4=mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num' );"));
                $dep_date=$row4['issue_date'];
                $load_port=$row4['load_port'];
                $cargo_name=$row4['cargo_name'];
                $dep_day = dbtime($dep_date, "d"); 
                $dep_month = dbtime($dep_date, "m"); 
                $dep_year = dbtime($dep_date, "Y"); 
                $port_name = allData('loadport', $load_port, 'port_name');
                $port_code = allData('loadport', $load_port, 'port_code');

                $lastportid = $load_port; 
                $last_port = allData('loadport', $load_port, 'port_name');
              }else{
                if (exist("vessels_cargo", "vsl_num = $vsl_num")) {
                  $lastportid = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'loadport');
                  $last_port = allData('loadport', $lastportid, 'port_name');
                }
              }
            }else{
              $lastportid = $row3['last_port'];
              $last_port = allData('loadport', $lastportid, 'port_name');
            }
            
            $with_retention = $row3['with_retention'];
            $capt_name = $row3['capt_name'];//
          }
        ?>
        <section class="no-padding-top">
            <div class="container-fluid">
              <div class="row">
                
                <!-- Form Elements -->
                <div class="col-lg-12">
                  <div class="block">
                    <div class="title">
                      <strong>Info For GENERAL SEGMENT of MV. <?php echo $thisvessel['vessel_name']; ?></strong>
                      <!-- <a 
                        onClick="javascript: return confirm('Please confirm deletion');" 
                        href="index.php?del_msl_num=<?php echo $msl_num; ?>" 
                        class="btn btn-danger btn-sm"
                         style="float: right;"
                      ><i class="bi bi-trash"></i></a> -->
                      <a href="vessel_details.php?edit=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right; margin-right: 10px;">
                        <i class="icon-ink"></i> <-Back
                      </a>
                    </div>

                    <div class="block-body">
                      <form method="post" action="vessel_details.php?igminputs=<?php echo $vsl_num; ?>">
                      <!-- 1st -->
                      <div class="form-row">
                        <div class="form-group col-md-1">
                          <label for="inputEmail4">Voyage</label>
                          <input type="hidden" name="ship_perticularId" value="<?php echo $ship_perticularId; ?>">
                          <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                          <input type="text" class="form-control" name="vsl_num" disabled value="<?php echo $msl_num; ?>">
                        </div>
                        <div class="form-group col-md-2">
                          <label>IMO No.</label>
                          <input type="text" class="form-control" name="vsl_imo" required value="<?php echo $vsl_imo ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Vessel Nationality / Flag</label>
                          <!-- <input type="text" step="any" class="form-control" name="vsl_nationality" value="<?php echo $vsl_nationality ?>"> -->
                          <select id="inputState" name="vsl_nationality" class="form-control search" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $nport_id; ?>"><?php echo $nport_name; ?></option>
                            <?php 
                              $run4 = mysqli_query($db, "SELECT * FROM nationality ");
                              while ($row4 = mysqli_fetch_assoc($run4)) {
                                $id = $row4['id']; $vsl_nationality = $row4['port_name'];
                                echo"<option value=\"$id\">$vsl_nationality</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputPassword4">Vessel Port of Registry</label>
                          <!-- <input type="text" step="any" class="form-control" name="vsl_registry" value="<?php echo $vsl_registry ?>"> -->
                          <select id="inputState" name="vsl_registry" class="form-control search" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $rport_id; ?>"><?php echo $rport_name; ?></option>
                            <?php 
                              $run4 = mysqli_query($db, "SELECT * FROM nationality ");
                              while ($row4 = mysqli_fetch_assoc($run4)) {
                                $id = $row4['id']; $vsl_registry = $row4['port_name'];
                                echo"<option value=\"$id\">$vsl_registry</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputState">GRT</label>
                          <input type="number" step="any" class="form-control" name="vsl_grt" value="<?php echo $vsl_grt ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputState">NRT</label>
                          <input type="number" step="any" class="form-control" name="vsl_nrt" value="<?php echo $vsl_nrt ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <!-- 2nd -->
                      <div class="form-row">
                        
                      </div>
                      
                      <!-- 7th -->
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="inputEmail4">Last Port</label>
                          <!-- <input type="text" step="any" class="form-control" name="last_port" value="<?php echo $last_port ?>"> -->

                          <select id="inputState" name="last_port" class="form-control search" <?php echo $btnstatus; ?>>
                            <option value="<?php echo $lastportid; ?>"><?php echo $last_port; ?></option>
                            <?php 
                              $run4 = mysqli_query($db, "SELECT * FROM loadport ");
                              while ($row4 = mysqli_fetch_assoc($run4)) {
                                $thislp_id = $row4['id']; $this_lp = $row4['port_name'];
                                echo"<option value=\"$thislp_id\">$this_lp</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Packages codes</label>
                          <input type="text" step="any" class="form-control" name="packages_codes" value="<?php echo $packages_codes ?>" <?php echo $btnstatus; ?>>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputEmail4">Name of Captain</label>
                          <input type="text" step="any" class="form-control" placeholder="Without 'CAPT. ' word " name="capt_name" value="<?php echo $capt_name ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <button type="submit" name="ship_perticular_update" class="btn btn-success" <?php echo $btnstatus; ?>>Update</button>
                      <!-- <a href="vessel_details.php?ship_perticular=<?php echo $vsl_num; ?>" class="btn btn-primary">Cancel</a> -->
                    </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <?php// echo $msg; ?>
                    <strong>BL Informations of MV. <?php echo allData('vessels', $vsl_num, 'vessel_name'); ?></strong>

                    <div id="toolbar" class="select" style="width: 30%; margin-left: 120px; margin-top: -35px; display: none;">
                      <select class="form-control">
                        <option value="">Export Basic</option>
                        <option value="all">Export All</option>
                        <option value="selected">Export Selected</option>
                      </select>
                    </div>

                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addBl" <?php echo $btnstatus; ?>>+ Add BL Info</button>
                  </div>

                  <div class="table-responsive"> 
                    <table 
                      class="table table-dark table-striped table-sm"
                    >

                      <thead>
                        <tr role="row">
                          <th>Line</th>
                          <th>BL</th>
                          <th>Cargo</th>
                          <th>Loadport</th>
                          <th>QTY</th>
                          <th>All <input type="checkbox" id="selectIgm"></th>
                          <?php if (allData('useraccess',$my['office_position'],'vessel_ctrl')){ ?>
                          <th style="text-align: center;">Actions</th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $sql = "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ";
                          // show dynamicsql
                          $run = mysqli_query($db, $sql); $total = 0;
                          while ($row = mysqli_fetch_assoc($run)) {
                            $id = $row['id']; //
                            $line_num = $row['line_num']; //
                            $bl_num = $row['bl_num']; //
                            $cargo_name = $row['cargo_name']; //
                            if (empty($row['c_cargoname'])) {$c_cargoname = $cargo_name;}
                                   else{$c_cargoname = $row['c_cargoname'];}
                            $cargo_qty = $row['cargo_qty']; //
                            $c_num = $row['c_num']; //
                            $loadPortId = $row['load_port'];
                            $load_port = allData('loadport', $loadPortId, 'port_name');
                            $port_code = allData('loadport', $loadPortId, 'port_code');
                            $total = $total+$cargo_qty;
                            

                            echo "
                            <form method=\"post\" action=\"".pagename().pageurl()."\">
                              <tr>
                                <th scope=\"row\">$line_num</th>
                                <td>
                                  <a 
                                    href=\"#\" 
                                    style=\"text-decoration: none; padding: 5px;\"
                                    data-toggle=\"modal\" data-target=\"#editDoInput$id\"
                                  >
                                    $bl_num
                                  </a>
                                </td>
                                <td>$c_cargoname</td>
                                
                                <td style=\"text-align:left;\">
                                  $load_port
                                </td>
                                <td>$cargo_qty MT</td>
                                <td style=\"text-align: center;\"><input type=\"checkbox\" name=\"multiplexml[]\" value=\"$id\" /></td>
                                ";
                                // useraccess for vessel_ctrl
                                    if (allData('useraccess',$my['office_position'],'vessel_ctrl')){
                                echo"<td scope=\"col\">
                                  <a 
                                    href=\"#\" 
                                    style=\"text-decoration: none; padding: 5px;\"
                                    data-toggle=\"modal\" data-target=\"#editBlInput$id\"
                                  >
                                    <span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i> </span>
                                  </a>
                                  |
                                  <a 
                                    onClick=\"javascript: return confirm('Export XML of bl no: ".$bl_num." ?');\"
                                    href=\"vessel_details.php?doinputs=$vsl_num&&exportblxml=$id\" 
                                    style=\"text-decoration: none; padding: 5px;\"
                                  >
                                    <span style=\"padding: 5px;\"><i class=\"icon icon-log-out-1\"></i> </span>
                                  </a>
                                </td>";
                                }
                                      echo"</tr>
                            ";
                          }
                          echo "
                            <tr>
                              <th scope=\"row\" colspan=\"4\" style=\"text-align:right;\">Total: &nbsp;</th>
                              <td colspan=\"1\">
                                <a href=\"vessel_details.php?edit=$vsl_num\">
                                  ".formatIndianNumber($total)." MT
                                </a>
                              </td>
                              <td colspan=\"2\">
                                <button type=\"submit\" name=\"multiplexmlexport\" class=\"btn btn-sm btn-secondary\">
                                  <i class=\"icon icon-log-out-1\"></i> Export Selected
                                </button>
                              </td>
                                  </tr>
                                  </form>
                          "; 
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php
            $vsl_num = $_GET['igminputs']; 
            $sql = "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ";
            $line_num = mysqli_num_rows(mysqli_query($db, $sql)) + 1;
            if (mysqli_num_rows(mysqli_query($db, $sql)) > 0) {
              $row6 = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ORDER BY id DESC LIMIT 1 "));
              $cargo_qty = $row6['cargo_qty']; $cargo_name = $row6['cargo_name'];
              $shipper_name = $row6['shipper_name']; $shipper_address = $row6['shipper_address']; 
              // $issue_date = $row6['issue_date']; 
              $issue_date = dbtimefotmat('Y-m-d', $row6['issue_date'], 'd/m/Y');
              $loadPortId = $row6['load_port'];
              $desc_portId = $row6['desc_port']; $desc_port = allData('loadport', $desc_portId, 'port_name');
              $receiverId = $row6['receiver_name']; $bankId = $row6['bank_name'];
              $cargokeyId = $row6['cargokeyId']; $cargokey = allData('cargokeys', $cargokeyId, 'name');
              $receiver_name = allData('bins', $receiverId, 'name');
              $receiver_bin = allData('bins', $receiverId, 'bin');
              $bank_name = allData('bins', $bankId, 'name');
              $bank_bin = allData('bins', $bankId, 'bin');
              $load_port = allData('loadport', $loadPortId, 'port_name');
            }else{
              $cargo_qty = $cargo_name = $shipper_name = $shipper_address = $issue_date = $receiverId = $bankId = $loadPortId = $cargokey = $cargokeyId = "";
              $receiver_name = $bank_name = $load_port = "--SELECT--";
            }
           
            $cargo_qty = $receiverId = $bankId = ""; 
            $receiver_name = $bank_name = "--SELECT--";

            $getvsldtls = mysqli_query($db, "SELECT bl_cargo, bl_cargokey, bl_shippername, bl_shipperaddress, bl_loadport, bl_issuedate FROM vessel_details WHERE vsl_num = '$vsl_num' ");
            $rowvsl = mysqli_fetch_assoc($getvsldtls);
            
            if($rowvsl['bl_cargo']==1){$ckbl_cargo="checked";} 
            else{$ckbl_cargo = $cargo_name = "";}
            if($rowvsl['bl_cargokey']==1){$ckbl_cargokey="checked";} 
            else{$ckbl_cargokey = $cargokey = $cargokeyId = "";}
            if($rowvsl['bl_shippername']==1){$ckbl_shippername="checked";} 
            else{$ckbl_shippername = $shipper_name = "";}
            if($rowvsl['bl_shipperaddress']==1){$ckbl_shipperaddress="checked";} 
            else{$ckbl_shipperaddress = $shipper_address = "";}
            if($rowvsl['bl_loadport']==1){$ckbl_loadport="checked";} 
            else{$ckbl_loadport = $loadPortId = $load_port = "";}
            if($rowvsl['bl_issuedate']==1){$ckbl_issuedate="checked";} 
            else{$ckbl_issuedate = $issue_date = "";}
          ?>
          <div class="modal fade" id="addBl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add BL info for IGM</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="post" action="vessel_details.php?igminputs=<?php echo $vsl_num ?>">
                  <input type="hidden" class="form-control" name="vsl_num" required value="<?php echo "$vsl_num"; ?>">
                  <div class="modal-body">
                    
                    <div class="form-row">
                      <div class="form-group col-md-1">
                        <label for="inputState">Line No</label>
                        <input type="hidden" class="form-control" name="line_num" required value="<?php echo "$line_num"; ?>">
                        <input type="text" class="form-control" disabled value="<?php echo "$line_num"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Bl No</label>
                        <input type="text" class="form-control" name="bl_num" required value="<?php //echo "$line_num"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Qty</label>
                        <input type="text" class="form-control" name="cargo_qty" required value="<?php echo "$cargo_qty"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-7">
                        <label for="inputState">Cargo Name <input type="checkbox" id="bl_cargo_checkbox" name="bl_cargo" value="1" <?php echo $ckbl_cargo; ?> <?php echo $btnstatus; ?>> (all same) </label>
                        <input type="text" class="form-control" id="cargo_name_field" name="cargo_name" required value="<?php echo "$cargo_name"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label for="inputState">Cargo Key <input type="checkbox" name="bl_cargokey" value="1" <?php echo $ckbl_cargokey; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <select class="form-control search" name="cargokey" required <?php echo $btnstatus; ?>>
                          <option value="<?php echo $cargokeyId ?>"><?php echo $cargokey; ?></option>
                          <?php selectOptions("cargokeys","name", "all"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-9">
                        <label for="inputState">Shipper Name <input type="checkbox" id="bl_shippername_checkbox" name="bl_shippername" value="1" <?php echo $ckbl_shippername; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" id="shipper_name_field" class="form-control" name="shipper_name" required value="<?php echo "$shipper_name"; ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="inputState">Shipper Address <input type="checkbox" id="bl_shipperaddress_checkbox" name="bl_shipperaddress" value="1" <?php echo $ckbl_shipperaddress; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <textarea type="text" id="shipper_address_field" class="form-control" name="shipper_address" value="" <?php echo $btnstatus; ?>><?php echo "$shipper_address" ?></textarea>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inputState">Receiver</label>
                        <select id="inputState" class="form-control search" name="receiver_name" required <?php echo $btnstatus; ?>>
                          <option value="<?php echo $receiverId ?>"><?php echo $receiver_name; //echo" || ".$receiver_bin; ?></option>
                          <?php
                            $run1 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                            while ($row1 = mysqli_fetch_assoc($run1)) {
                              $id = $row1['id']; $receiver_name = $row1['name']; $binnum = $row1['bin'];
                              // echo"<option value=\"$id\">$receiver_name || <span>$binnum</span></option>";
                              echo"<option value=\"$id\"><span>$binnum</span></option>";
                            }
                          ?>
                        </select>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="inputState">Bank</label>
                        <select id="inputState" class="form-control search" name="bank_name" <?php echo $btnstatus; ?>>
                          <option value="<?php echo $bankId ?>"><?php echo $bank_name; //echo" || ".$bank_bin; ?></option>
                          <?php
                            $run2 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'BANK' ");
                            while ($row2 = mysqli_fetch_assoc($run2)) {
                              $idImporter = $row2['id']; $bank_name = $row2['name'];$bank_bin = $row2['bin'];
                              // echo"<option value=\"$idImporter\">$bank_name || <span>$bank_bin</span></option>";
                              echo"<option value=\"$idImporter\"><span>$bank_bin</span></option>";
                            }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputState">Load Port <input type="checkbox" name="bl_loadport" value="1" <?php echo $ckbl_loadport; ?> <?php echo $btnstatus; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <select class="form-control search" name="load_port" required <?php echo $btnstatus; ?>>
                          <option value="<?php echo $loadPortId ?>"><?php echo $load_port; ?></option>
                          <?php selectOptions("loadport","port_name", "all"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputEmail4">Issue Date <input type="checkbox" name="bl_issuedate" value="1" <?php echo $ckbl_issuedate; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" id="datepicker" class="form-control" name="issue_date" value="<?php echo $issue_date; ?>" required <?php echo $btnstatus; ?>>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Discharge Port</label>
                        <select class="form-control search" name="desc_port" required <?php echo $btnstatus; ?>>
                          <option value="65">CHITTAGONG, BANGLADESH</option>
                          <?php selectOptions("loadport","port_name", "all"); ?>
                        </select>
                      </div>
                    </div>

                      

                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="blinput" class="btn btn-primary" <?php echo $btnstatus; ?>>+ ADD</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  </div>
                </form>
              </div>
            </div>
          </div>


        <?php
          // checkbox status
          $vsl_num = $_GET['igminputs']; 
            $getvsldtls = mysqli_query($db, "SELECT bl_cargo, bl_cargokey, bl_shippername, bl_shipperaddress, bl_loadport, bl_issuedate FROM vessel_details WHERE vsl_num = '$vsl_num' ");
            $rowvsl = mysqli_fetch_assoc($getvsldtls);
            // $bl_cargo = $rowvsl['bl_cargo']; $bl_cargokey = $rowvsl['bl_cargokey'];
            // $bl_shippername = $rowvsl['bl_shippername']; $bl_shipperaddress = $rowvsl['bl_shipperaddress'];
            // $bl_loadport = $rowvsl['bl_loadport']; $bl_issuedate = $rowvsl['bl_issuedate'];
            if($rowvsl['bl_cargo']==1){$ckbl_cargo="checked";} else{$ckbl_cargo="";}
            if($rowvsl['bl_cargokey']==1){$ckbl_cargokey="checked";} else{$ckbl_cargokey="";}
            if($rowvsl['bl_shippername']==1){$ckbl_shippername="checked";} else{$ckbl_shippername="";}
            if($rowvsl['bl_shipperaddress']==1){$ckbl_shipperaddress="checked";} else{$ckbl_shipperaddress="";}
            if($rowvsl['bl_loadport']==1){$ckbl_loadport="checked";} else{$ckbl_loadport="";}
            if($rowvsl['bl_issuedate']==1){$ckbl_issuedate="checked";} else{$ckbl_issuedate="";}

          $total = 0;
          $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num'");
          while ($row = mysqli_fetch_assoc($run)) {
            $id = $row['id']; //
            $line_num = $row['line_num']; //
            $bl_num = $row['bl_num']; //
            $cargo_name = $row['cargo_name']; //
            $cargokeyId = $row['cargokeyId']; //
            $cargokey = allData('cargokeys', $cargokeyId, 'name');
            $cargo_qty = $row['cargo_qty']; //
            $loadPortId = $row['load_port'];
            $desc_portId = $row['desc_port'];
            $receiverId = $row['receiver_name'];
            $bankId = $row['bank_name'];
            $shipper_name = $row['shipper_name'];
            $shipper_address = $row['shipper_address'];
            // $issue_date = $row['issue_date'];
            $issue_date = dbtimefotmat('Y-m-d', $row['issue_date'], 'd/m/Y');
            $load_port = allData('loadport', $loadPortId, 'port_name');
            $desc_port = allData('loadport', $desc_portId, 'port_name');
            $port_code = allData('loadport', $loadPortId, 'port_code');
            $receiver_name = allData('bins', $receiverId, 'name');
            $rcvr_bin = allData('bins', $receiverId, 'bin');
            $bank_name = allData('bins', $bankId, 'name');
            $bnk_bin = allData('bins', $bankId, 'bin');
            $total = $total+$cargo_qty;
        ?>
        <div class="modal fade" id="<?php echo"editBlInput".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit BL Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="vessel_details.php?igminputs=<?php echo $vsl_num ?>">
                  <input type="hidden" class="form-control" name="vsl_num" required value="<?php echo "$vsl_num"; ?>">
                  <input type="hidden" class="form-control" name="blinputid" required value="<?php echo "$id"; ?>">
                  <input type="hidden" class="form-control" name="line_num" required value="<?php echo "$line_num"; ?>">
                  <div class="modal-body">
                    
                    <div class="form-row">
                      <div class="form-group col-md-1">
                        <label for="inputState">Line No</label>
                        <input type="text" class="form-control" name="line_num" required value="<?php echo "$line_num"; ?>" disabled>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Bl No</label>
                        <input type="text" class="form-control" name="bl_num" required value="<?php echo "$bl_num" ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="inputState">Qty</label>
                        <input type="text" class="form-control" name="cargo_qty" required value="<?php echo "$cargo_qty" ?>" <?php echo $btnstatus; ?>>
                      </div>
                      <div class="form-group col-md-7">
                        <label for="inputState">Cargo Name <input type="checkbox" name="bl_cargo" value="1" <?php echo $ckbl_cargo; ?> <?php echo $btnstatus; ?>> (all same) </label>
                        <input type="text" class="form-control" name="cargo_name" required value="<?php echo "$cargo_name" ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label for="inputState">Cargo key <input type="checkbox" name="bl_cargokey" value="1" <?php echo $ckbl_cargokey; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <select class="form-control search" name="cargokey" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($cargokeyId)){echo"<option></option>";}
                          else{echo "<option value=\"$cargokeyId\">$cargokey</option>";}
                          ?>
                          <?php selectOptions("cargokeys","name"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-9">
                        <label for="inputState">Shipper Name <input type="checkbox" name="bl_shippername" value="1" <?php echo $ckbl_shippername; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" class="form-control" name="shipper_name" required value="<?php echo "$shipper_name" ?>" <?php echo $btnstatus; ?>>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="inputState">Shipper Address <input type="checkbox" name="bl_shipperaddress" value="1" <?php echo $ckbl_shipperaddress; ?>> (all same)</label>
                        <textarea type="text" class="form-control" name="shipper_address" value="" <?php echo $btnstatus; ?>><?php echo "$shipper_address" ?></textarea>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inputState">Receiver</label>
                        <select id="inputState" class="form-control search" name="receiver_name" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($receiverId)){echo"<option></option>";}
                          else{echo"<option value=\"$receiverId\">$receiver_name || $rcvr_bin</option>";}
                          ?>
                          <?php
                            $run1 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                            while ($row1 = mysqli_fetch_assoc($run1)) {
                              $id = $row1['id']; $receiver_name = $row1['name']; $binnum = $row1['bin'];
                              echo"<option value=\"$id\">$receiver_name || <span>$binnum</span></option>";
                            }
                          ?>
                        </select>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="inputState">Bank</label>
                        <select id="inputState" class="form-control search" name="bank_name" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($bankId)){echo"<option></option>";}
                          else{echo "<option value=\"$bankId\">$bank_name || $bnk_bin</option>";}
                          ?>
                          <?php
                            $run2 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'BANK' ");
                            while ($row2 = mysqli_fetch_assoc($run2)) {
                              $idImporter = $row2['id']; $bank_name = $row2['name'];$bank_bin = $row2['bin'];
                              echo"<option value=\"$idImporter\">$bank_name || <span>$bank_bin</span></option>";
                            }
                          ?>
                        </select>
                      </div>
                    </div> 

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputState">Load Port <input type="checkbox" name="bl_loadport" value="1" <?php echo $ckbl_loadport; ?>> (all same)</label>
                        <select class="form-control search" name="load_port" <?php echo $btnstatus; ?>>
                          <?php
                          if (empty($loadPortId)){echo"<option></option>";}
                          else{echo "<option value=\"$loadPortId\">$load_port</option>";}
                          ?>
                          <?php selectOptions("loadport","port_name"); ?>
                        </select>
                      </div>

                      <div class="form-group col-md-3">
                        <label for="inputEmail4">Issue Date <input type="checkbox" name="bl_issuedate" value="1" <?php echo $ckbl_issuedate; ?> <?php echo $btnstatus; ?>> (all same)</label>
                        <input type="text" id="datepicker" class="form-control" name="issue_date" value="<?php echo $issue_date; ?>" <?php echo $btnstatus; ?>>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Discharge Port</label>
                        <select class="form-control search" name="desc_port" <?php echo $btnstatus; ?>>
                          <option value="<?php echo $desc_portId ?>"><?php echo $desc_port; ?></option>
                          <?php selectOptions("loadport","port_name"); ?>
                        </select>
                      </div>
                    </div>

                      

                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="blupdate" class="btn btn-primary" <?php echo $btnstatus; ?>>+ Update</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  </div>
                </form>
            </div>
          </div>
        </div>
      </section>
      <?php } ?>
      <!-- end igminputs -->

















        <!-- doinputs -->
        <?php } elseif(isset($_GET['doinputs'])){ ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <?php //echo $msg; //include('inc/errors.php'); ?>
                  <div class="title">
                    <?php //echo $msg; //include('inc/errors.php'); ?>
                    <strong>Do List of MV. <?php echo allData('vessels', $vsl_num, 'vessel_name'); ?></strong>

                    <div id="toolbar" class="select" style="width: 30%; margin-left: 120px; margin-top: -35px; display: none;">
                      <select class="form-control">
                        <option value="">Export Basic</option>
                        <option value="all">Export All</option>
                        <option value="selected">Export Selected</option>
                      </select>
                    </div>

                    <!-- add vassel modal and btn -->
                    <!-- <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addDo">+ DO</button> -->
                  </div>

                  <div class="table-responsive"> 
                    <table 
                      class="table table-dark table-striped table-sm"
                    >
                    

                    <!-- <div id="bar" class="select" style="border: 1px solid white; display: none;">
                      <select></select>
                    </div> -->

                      <thead>
                        <tr role="row">
                          <th>Line</th>
                          <th>BL</th>
                          <th>Cargo</th>
                          <th>C-Num</th>
                          <th>QTY</th>
                          <th>All <input type="checkbox" id="selectAll"></th>
                          <?php if (allData('useraccess',$my['office_position'],'vessel_ctrl')){ ?>
                          <th style="text-align: center;">Edit</th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          vsl_do($vsl_num); 
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END IGMINPUTS -->


          <?php
            $total = 0;
            // edit blinput
            $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num'");
            while ($row = mysqli_fetch_assoc($run)) {
              $id = $row['id']; //
              $line_num = $row['line_num']; //
              $bl_num = $row['bl_num']; //
              $cargo_name = $row['cargo_name']; //
              $cargo_qty = $row['cargo_qty']; //
              $loadPortId = $row['load_port'];
              $receiverId = $row['receiver_name'];
              $cnfId = $row['cnf_name'];

              if (empty($row['c_cnf'])) {
                if (empty($row['cnf_name'])) {$cnf_name = "";}
                else{$cnf_name = allData('cnf', $cnfId, 'name');}
              }else{$cnf_name = $row['c_cnf']; }
              // $receiverId = $row['receiver_name'];
              if (empty($row['c_consignee'])) {
                if (empty($row['receiver_name'])) {$receiver_name = "";}
                else{$receiver_name = $receiver_name = allData('bins', $receiverId, 'name');}
              }else{$receiver_name = $row['c_consignee']; }
                
              $shipper_name = $row['shipper_name'];
              $shipper_address = $row['shipper_address'];
              $issue_date = $row['issue_date'];
              $c_num = $row['c_num'];
              // $c_date = $row['c_date'];
              if (empty($row['c_date'])) {$c_date = "";}
              else{$c_date = dbtimefotmat('Y-m-d', $row['c_date'], 'd/m/Y');}
              if (empty($row['c_cargoname'])) {$c_cargoname = $cargo_name;}
              else{$c_cargoname = $row['c_cargoname'];}
              $load_port = allData('loadport', $loadPortId, 'port_name');
              $port_code = allData('loadport', $loadPortId, 'port_code');
              $total = $total+$cargo_qty;
          ?>
          <!-- Consignee Edit Modal -->
          <div class="modal fade" id="<?php echo"editDoInput".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Edit Do Info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="post" action="vessel_details.php?doinputs=<?php echo $vsl_num ?>">
                    <input type="hidden" class="form-control" name="vsl_num" required value="<?php echo "$vsl_num"; ?>">
                    <input type="hidden" class="form-control" name="doinputid" required value="<?php echo "$id"; ?>">
                    <div class="modal-body">
                      
                      <div class="form-row">
                        <div class="form-group col-md-1">
                          <label for="inputState">Line No</label>
                          <input type="text" class="form-control" name="line_num" required value="<?php echo "$line_num"; ?>" disabled>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputState">Bl No</label>
                          <input type="text" class="form-control" name="bl_num" required value="<?php echo "$bl_num" ?>" disabled>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">Qty</label>
                          <input type="text" class="form-control" name="cargo_qty" required value="<?php echo "$cargo_qty" ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputState">Importer</label>
                          <input type="text" class="form-control" name="c_consignee" value="<?php echo "$receiver_name" ?>">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="inputState">Cargo Name</label>
                          <input type="text" class="form-control" name="c_cargoname" value="<?php echo "$c_cargoname" ?>" <?php echo $btnstatus; ?>>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputState">C Number</label>
                          <input type="text" class="form-control" name="c_num" required value="<?php echo "$c_num" ?>" <?php echo $btnstatus; ?>>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="inputEmail4">C Number Date</label>
                          <input type="text" id="datepicker" class="form-control" name="c_date" required value="<?php echo $c_date; ?>" <?php echo $btnstatus; ?>>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="inputState">CNF</label>
                          <select id="inputState" class="form-control search" name="cnfId" required <?php echo $btnstatus; ?>>
                            <?php
                              if (empty($cnfId)) { echo "<option></option>"; }
                              else{ echo "<option value=\"$cnfId\">$cnf_name</option>"; }
                              $run2 = mysqli_query($db, "SELECT * FROM cnf ");
                              while ($row2 = mysqli_fetch_assoc($run2)) {
                                $cnfIdthis = $row2['id']; $cnf_namethis = $row2['name'];
                                echo"<option value=\"$cnfIdthis\">$cnf_namethis</option>";
                              }
                            ?>
                          </select>
                        </div>

                      </div> 

                        

                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="doupdate" class="btn btn-primary" <?php echo $btnstatus; ?>>+ Update</button>
                      <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </section>
        <?php } ?>
        <!-- end editdoinput -->



















        <!-- FORWADINGS -->
        <?php } elseif(isset($_GET['forwadingpage'])){ ?>
          <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <?php if($thisvessel['payment'] != "paid"){ ?>
                    <strong>Pay <a href="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>&&payment=yes" class="btn btn-warning btn-sm" style="color: #2D3035;">
                      <i class="icon-cloud"></i> 4000/- Tk
                    </a> To Unlock 
                    <?php } ?>
                    Export Forwadings Of: <b><?php echo $msl_num."."; ?>MV. <?php echo $vessel; ?></b></strong>
                    <!-- <a 
                      onClick="javascript: return confirm('Please confirm deletion');" 
                      href="index.php?del_msl_num=<?php echo $msl_num; ?>" 
                      class="btn btn-danger btn-sm"
                       style="float: right;"
                    ><i class="bi bi-trash"></i></a> -->
                    <a href="vessel_details.php?edit=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right; margin-right: 10px;">
                      <i class="icon-ink"></i> <-Back
                    </a>
                  </div>

                  <div class="block-body">

                    <div class="table-responsive"> 
                      <table class="table table-dark table-sm table-custom">
                        <thead>
                          <tr>
                            <th colspan="8">Vessel Details</th>
                          </tr>
                        </thead>
                        <tbody>
                          <form method="post" action="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>">
                            <input type="hidden" name="ship_perticularId" value="<?php echo $ship_perticularId; ?>">
                            <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                            <!-- 1st -->
                            <tr style="border-bottom: 1px solid white;">
                              <td colspan="8">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "export_vsl_details" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Export Vessel Details
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <th colspan="8"> Before Arrive</th>
                            </tr>
                            <tr style="border-bottom: 1px solid white;">
                              <td>
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "prepartique" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  1.Prepartique
                                </button>
                              </td>

                              <td>
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "vsl_declearation" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  2.VSL DESC
                                </button>
                              </td>


                              <td>
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "portigm" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  3.Port IGM
                                </button>
                              </td>


                              <td>
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "plantq" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  4.Plant.Q
                                </button>
                              </td>


                              <td>
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "po_booking" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  5.P.O Booking
                                </button>
                              </td>


                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "survey_booking" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  6.Survey Booking
                                </button>
                              </td>


                              <td>
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-info btn-sm" name="export_vsl_forwadings" value="<?php echo "before_arrive" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Export All
                                </button>
                              </td>
                            </tr>




                            <tr>
                              <th colspan="8"> After Arrive</th>
                            </tr>

                            <tr>
                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "finalentryexport" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  13.Final Entry
                                </button>
                              </td>


                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "pcforwadingexport" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  28.PC Forwading
                                </button>
                              </td>


                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "pcstampexport" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  28.Stamp_PC
                                </button>
                              </td>


                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "inctaxforwading" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  29.Inc Tax Forwading
                                </button>
                              </td>
                            </tr>
                            <tr style="border-bottom: 1px solid white;">
                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "inctaxstamp" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  29.Inc Tax Stamp
                                </button>
                              </td>

                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="mmdforwading" style="color: white" <?php echo $fbtnstatus; ?>>
                                  MMD Forwading
                                </button>
                              </td>

                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="pcformet" style="color: white" <?php echo $fbtnstatus; ?>>
                                  PC Formet
                                </button>
                              </td>


                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-info btn-sm" name="export_vsl_forwadings" value="<?php echo "after_arrive" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Export All
                                </button>
                              </td>
                            </tr>




                            <tr>
                              <th colspan="8"> After Sail</th>
                            </tr>
                            <tr style="border-bottom: 1px solid white;">
                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "port_health" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  20.Port Helth
                                </button>
                              </td>


                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "psc_submission" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  21.PHC Submission
                                </button>
                              </td>


                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "egm_forwading" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  22.EGM Forwading
                                </button>
                              </td>


                              <td colspan="1">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "egm_format" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  23.EGM Format
                                </button>
                              </td>


                              <td colspan="1">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-info btn-sm" name="export_vsl_forwadings" value="<?php echo "after_sail" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Export All
                                </button>
                              </td>
                            </tr>

                            <?php if ($my['companyid'] == 1) { ?>
                            <!-- file cover section -->
                            <tr>
                              <th colspan="8"> Only Multiport</th>
                            </tr>

                            <tr style="border-bottom: 1px solid white;">
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "vataitchalan" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  15% VAT & 10% AIT
                                </button>
                              </td>

                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "mainfilecover" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Main File Cover
                                </button>
                              </td>

                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "accfilecover" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Accounts File Cover
                                </button>
                              </td>
                              <td colspan="2">
                                <!-- <label for="inputEmail4">Msl Num</label> -->
                                <button type="submit" class="form-control btn btn-info btn-sm" name="export_vsl_forwadings" value="<?php echo "file_covers" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Export All
                                </button>
                              </td>
                            </tr>
                            <?php } ?>


                            <tr>
                              <th colspan="8">Receving Doc's</th>
                            </tr>
                            <tr>
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "arrival_perticular" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Arrival Perticular & Transport
                                </button>
                              </td>

                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "ship_required_docs" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Ship Required Doc's
                                </button>
                              </td>

                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "representative_letter" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Representative Letter
                                </button>
                              </td>
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "qurentine" ?>" style="color: white" <?php echo $fbtnstatus; ?> disabled>
                                  Qurentine
                                </button>
                              </td>
                            </tr>
                            <?php 
                              if ($my['companyid'] == 1) {$lightclspn = 2;}
                              else{$lightclspn = 4;}
                            ?>
                            <tr style="border-bottom: 1px solid white;">
                              <td colspan="<?php echo $lightclspn; ?>">
                                <div class="btn-group" role="group" aria-label="Basic example" style="width: 100%;">
                                  <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "lightdues" ?>" style="color: white" <?php echo $fbtnstatus; ?>>Lightdues</button>


                                  <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "lightdues2nd" ?>" style="color: white" <?php echo $fbtnstatus; ?>>Extension</button>
                                </div>
                                
                              </td>
                              <?php if($my['companyid'] == 1){ ?>
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "watchman_letter" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Watchman Letter
                                </button>
                              </td>

                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success" name="export_vsl_forwadings" value="<?php echo "vendor_letter" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Vendor Letter
                                </button>
                              </td>
                              <?php } ?>

                              <td colspan="<?php echo $lightclspn; ?>">
                                <button type="submit" class="form-control btn btn-info btn-sm" name="export_vsl_forwadings" value="<?php echo "export_rcv_docs" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Export All
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <th colspan="8">Templets</th>
                            </tr>

                            <tr style="border-left: 1px solid white; border-right: 1px solid white; border-bottom: 1px solid white;">
                              <td colspan="3">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "igmformat" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                    Igm Format
                                  </button>
                              </td>
                              <td colspan="3">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "igmxml" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                    Igm Xml
                                  </button>
                              </td>
                              <td colspan="3">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "igmfullcargo" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                    Full Cargo
                                  </button>
                              </td>
                            </tr>

                            <tr>
                              <th colspan="8">Forwadings</th>
                            </tr>

                            <tr style="border-left: 1px solid white; border-right: 1px solid white; border-bottom: 1px solid white;">
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "softemplet" ?>" style="color: white" <?php echo $fbtnstatus; ?> >
                                  Sof Format
                                </button>
                              </td>
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "stevedorebooking" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Stevedore Booking
                                </button>
                              </td>
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "portbillcollect" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Port bill Collect
                                </button>
                              </td>
                              <td colspan="2">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "cargo_declearation" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  Cargo Declearation
                                </button>
                              </td>
                            </tr>

                            <!-- <tr>
                              <th colspan="8">Vat Chalan</th>
                            </tr>

                            <tr style="border-left: 1px solid white; border-right: 1px solid white; border-bottom: 1px solid white;">
                              <td colspan="4">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "vatfifteen" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  15% VAT ON LIGHT DUES
                                </button>
                              </td>
                              <td colspan="4">
                                <button type="submit" class="form-control btn btn-success btn-sm" name="export_vsl_forwadings" value="<?php echo "vatten" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                  10% AIT ON LIGHT DUES
                                </button>
                              </td>
                            </tr> -->
                          </form>
                        </tbody>
                      </table>
                    </div>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- END FORWADINGS -->

        <!-- download files section -->
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <strong>Download Files Of MV. <?php echo $vessel; ?></strong>
                    <!-- <a 
                      onClick="javascript: return confirm('Please confirm deletion');" 
                      href="index.php?del_msl_num=<?php echo $msl_num; ?>" 
                      class="btn btn-danger btn-sm"
                       style="float: right;"
                    ><i class="bi bi-trash"></i></a> -->
                    <!-- <a href="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right; margin-right: 10px;">
                        <i class="icon-ink"></i> Download All
                    </a> -->
                    <?php
                      $folder = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/"; // Folder location

                      // Check if the folder exists
                      if (is_dir($folder)) {
                        $files = scandir($folder); // Get all entries in the folder
                        $files = array_filter($files, function($file) use ($folder) {
                            // Include only actual files and exclude temporary files starting with "~$"
                            return is_file($folder . $file) && strpos($file, '~$') !== 0;
                        });

                        if (empty($files)) {
                            echo "
                            <a href=\"vessel_details.php?forwadingpage=$vsl_num\" class=\"btn btn-secondary btn-sm\" style=\"float: right; margin-right: 10px;\">
                                <i class=\"icon-ink\"></i> Refresh
                            </a>
                            ";
                        }else { ?>
                          <?php if(allData('useraccess',$my['office_position'],'forwading_ctrl')){  ?>
                          <a href="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>&&df=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right; margin-right: 10px;">
                              <i class="icon-ink"></i> Download All
                          </a>
                          <?php } ?>
                        <?php }
                      } else {/*echo "The folder does not exist.";*/}
                      ?>
                    

                  </div>

                  <div class="block-body">

                    <div class="table-responsive"> 
                      <?php
                        $folder = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/"; // Folder location

                        // Check if the folder exists
                        if (is_dir($folder)) {
                          $files = scandir($folder); // Get all entries in the folder
                          $files = array_filter($files, function($file) use ($folder) {
                              // Include only actual files and exclude temporary files starting with "~$"
                              return is_file($folder . $file) && strpos($file, '~$') !== 0;
                          });

                          // check to se if downloadable file exist
                          $skipList = ['ship_perticular', 'pni_cer', 'crew_list'];

                          // Flag to check if there’s anything left to download
                          $hasDownloadableFiles = false; 
                          foreach ($files as $file) {
                            // Extract filename without extension
                            $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
                            // Skip specific base filenames
                            if (in_array($nameWithoutExt, $skipList)){continue;}
                            // We have at least one downloadable file
                            $hasDownloadableFiles = true; 
                          }

                          if (empty($files)) {echo "No files available for download. $folder";}
                          // If nothing valid to download was found
                          elseif(!$hasDownloadableFiles){echo "No files available for download. $folder";}
                          else { ?>
                            <table class="table table-dark table-sm table-custom" id="downloads">
                              <thead>
                                <tr>
                                  <th colspan="8"><?php echo "<h3>Files in '$folder'</h3>"; ?></th>
                                </tr>
                              </thead>
                              <tbody>
                                <form method="post" action="vessel_details.php?forwadingpage=<?php echo $vsl_num; ?>">
                                  <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                                  <?php
                                  $skipList = ['ship_perticular', 'pni_cer', 'crew_list'];
                                  // Flag to check if there’s anything left to download
                                  $hasDownloadableFiles = false; 
                                  foreach ($files as $file) {
                                    // Extract filename without extension
                                    $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);

                                    // Skip specific base filenames
                                    if (in_array($nameWithoutExt, $skipList)){continue;}
                                    // We have at least one downloadable file
                                    $hasDownloadableFiles = true;
                                    $filePath = $folder . $file; ?>
                                    <tr style="border: 1px solid white;">
                                      <td colspan="6">
                                        <input type='hidden' name="<?php echo "$file"; ?>" value="<?php echo' . htmlspecialchars($file) . ' ?>">
                                        <?php echo "$file"; ?>
                                      </td>
                                      <td colspan="2">
                                        <button type="submit" class="form-control btn btn-success btn-sm" name="downloadfile" value="<?php echo "$file" ?>" style="color: white" <?php echo $fbtnstatus; ?>>
                                          Download
                                        </button>
                                      </td>
                                    </tr> 
                                    <?php 
                                  } 
                                  ?>
                                </form> 
                              </tbody>
                            </table>
                          <?php }
                        } else {echo "No files available for download. $folder";}
                        ?>

                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- END SHIP PERTICULAR -->

        <?php } else{ ?>
          <section class="no-padding-top">
            <div class="container-fluid">
              <div class="row">
                
                <!-- Form Elements -->
                <div class="col-lg-12">
                  <div class="block">
                    <div class="title">
                      <strong>Blank Page</strong>
                      <!-- <a 
                        onClick="javascript: return confirm('Please confirm deletion');" 
                        href="index.php?del_msl_num=<?php echo $msl_num; ?>" 
                        class="btn btn-danger btn-sm"
                         style="float: right;"
                      ><i class="bi bi-trash"></i></a> -->
                      <a href="vessel_details.php?edit=<?php echo $vsl_num; ?>" class="btn btn-secondary btn-sm" style="float: right; margin-right: 10px;">
                        <i class="icon-ink"></i> <-Back
                      </a>
                    </div>

                    <div class="block-body">
                      Blank content
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        <?php } ?>






        <!-- percentage complete -->
        <div class="modal fade" id="completepercentage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo "MV. ".$vessel_name; ?> <?php echo $rcv_date; ?></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="vessel_details.php?edit=<?php echo $vsl_num; ?>">
                <input type="hidden" name="vsl_num" value="<?php echo $vsl_num; ?>">
                <input type="hidden" name="vesselId" value="<?php echo $vesselId; ?>">
                <input type="hidden" name="vessel_name" value="<?php $vessel_name; ?>">
                <div class="modal-body">
                  <!-- 1st -->
                  <div class="form-row">
                    <?php if(empty($arrived) || empty($rcv_date)){ ?>
                    <div class="form-group col-md-2">
                      <label for="inputEmail4">Arrived</label>
                      <input type="text" id="datepicker" class="form-control" name="arrived" value="<?php echo $arrived; ?>">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputEmail4">Received(sm) <input type="checkbox" name="sameRcv" value="sameRcv" <?php echo $ckstatusRcv; ?>></label>
                      <input type="text" id="datepicker" class="form-control" name="rcv_date" value="<?php echo $rcv_date; ?>">
                    </div>
                    <?php } ?>
                    <?php if(empty($com_date)||empty($sailing_date)){ ?>
                    <div class="form-group col-md-2">
                      <label for="inputEmail4">Complete Date</label>
                      <input type="text" id="datepicker" class="form-control" name="com_date" value="<?php echo $com_date; ?>">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputEmail4">Sailed(same) <input type="checkbox" name="sameSail" value="sameSail" <?php echo $ckstatusSail; ?>></label>
                      <input type="text" id="datepicker" class="form-control" name="sailing_date" value="<?php echo $sailing_date; ?>">
                    </div>
                    <?php } ?>
                    <?php if(strval($ttlqtyplused)!=strval(ttlcargoqty($vsl_num))){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputPassword4">Outer Quantity </label>
                      <input type="number" step="any" class="form-control" name="outer_qty" value="<?php echo $outer_qty ?>">
                    </div>
                    <?php } ?>

                    <?php if(strval($ttlqtyplused)!=strval(ttlcargoqty($vsl_num))){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputEmail4">Kutubdia Quantity</label>
                      <input type="number" step="any" class="form-control" name="kutubdia_qty" value="<?php echo $kutubdia_qty; ?>">
                    </div>
                    <?php } ?>
                    
                    <?php if(strval($ttlqtyplused)!=strval(ttlcargoqty($vsl_num))){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputEmail4">Retention Quantity</label>
                      <input type="number" step="any" class="form-control" name="retention_qty" value="<?php echo $retention_qty; ?>">
                    </div>
                    <?php } ?>

                    <!-- <?php// echo $msl_num.exist('vessels_importer','msl_num',$msl_num); ?> -->
                    <!-- <?php if(exist("vessels_importer","vsl_num = ".$vsl_num." ")==0){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputState">Impoter</label>
                      <select name="importer[]" class="form-control mb-3 mb-3 selectpicker" multiple style="background: transparent;" data-live-search="true">
                        <?php
                          // $run = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                          // while ($row = mysqli_fetch_assoc($run)) {
                          //   $id = $row['id']; $value = $row['name'];
                          //   $getImporter = mysqli_query($db, "SELECT * FROM vessels_importer WHERE importer = '$id' AND vsl_num = '$vsl_num' ");
                          //   if (mysqli_num_rows($getImporter) > 0) { $selected = "selected"; }
                          //   else{$selected = "";}
                          //   echo"<option value=\"$id\" $selected>$value</option>";
                          // }
                        ?>
                      </select>
                    </div>
                    <?php } ?> -->

                    <?php if(empty($stevedore) || $stevedore == 0){ ?>
                    <div class="form-group col-md-6">
                      <label for="inputState">Stevedore</label>
                        <select id="inputState" class="form-control search" name="stevedore" <?php echo $btnstatus; ?>>
                          <?php
                            if (empty($stevedore)) {echo "<option></option>";}
                            else{echo "<option value=\"$stevedore\">".alldata('stevedore',$stevedore,'name')."</option>";}
                            $run = mysqli_query($db, "SELECT * FROM stevedore ");
                            while ($row = mysqli_fetch_assoc($run)) {
                              $id = $row['id']; $value = $row['name'];
                              if ($id == $stevedore) { continue; }
                              echo"<option value=\"$id\">$value</option>";
                            }
                          ?>
                        </select>
                    </div>
                    <?php } ?>
                    
                    <?php if(empty($row2['representative']) || $row2['representative'] == 0){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputState">Representative</label>
                      <select id="inputState" class="form-control search" name="representative" <?php echo $btnstatus; ?>>
                        <??>
                        <!-- <option value="<?php echo $repId ?>"><?php echo $representative_name; ?></option> -->
                        <?php
                          if (empty($repId)) {echo "<option></option>";}
                          else{echo "<option value=\"$repId\">$representative_name</option>";}
                          $run1 = mysqli_query($db, "SELECT * FROM users WHERE companyid = '$companyid' ");
                          while ($row1 = mysqli_fetch_assoc($run1)) {
                            $id = $row1['id']; $rep_name = $row1['name'];
                            if ($repId == $id) { continue; }
                            echo"<option value=\"$id\">$rep_name</option>";
                          }
                        ?>
                      </select>
                    </div>
                    <?php } ?>

                    <?php if(empty($row2['rotation'])){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputState">Rotation</label>
                      <input type="text" class="form-control" name="rotation" value="<?php echo $rotation ?>">
                    </div>
                    <?php } ?>

                    <?php if(empty($row2['anchor'])){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputState">Anchorage</label>
                      <select id="inputState" class="form-control" name="anchor">
                        <?php
                          if ($anchor == "Outer") {
                            echo"
                              <option value=\"\">--Select----</option>
                              <option value=\"Outer\" selected>Outer</option>
                              <option value=\"Kutubdia\">Kutubdia</option>
                            ";
                          }
                          elseif ($anchor == "Kutubdia") {
                            echo"
                              <option value=\"\">--Select----</option>
                              <option value=\"Outer\">Outer</option>
                              <option value=\"Kutubdia\" selected>Kutubdia</option>
                            ";
                          }
                          else{
                            echo"
                              <option value=\"\">--Select----</option>
                              <option value=\"Outer\">Outer</option>
                              <option value=\"Kutubdia\">Kutubdia</option>
                            ";
                          }
                        ?>
                      </select>
                    </div>
                    <?php } ?>


                    <?php if($row2['survey_custom']==0){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputState">Custom Survey</label>
                      <select id="inputState" class="form-control search" name="survey_custom">
                        <?php
                          $company_name = allData('surveycompany', $survey_custom, 'company_name');
                          if ($company_name == "") {
                            echo"<option value=\"\">--Select--</option>";
                          }else{
                        ?>
                        <option value="<?php echo $survey_custom; ?>"><?php echo $company_name; ?></option>
                        <?php }
                          $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                          while ($row1 = mysqli_fetch_assoc($run1)) {
                            $id = $row1['id']; $company_name = $row1['company_name'];
                            if ($id == $survey_custom) { continue; }
                            echo"<option value=\"$id\">$company_name</option>";
                          }
                        ?>
                      </select>
                    </div>
                    <?php } ?>

                    <?php if($row2['survey_consignee']==0){ ?>
                    <div class="form-group col-md-3">
                      <label for="inputState">Consignee Survey</label>
                      <select id="inputState" class="form-control search" name="survey_consignee">
                        <?php
                          $company_name = allData('surveycompany', $survey_consignee, 'company_name');
                          if ($company_name == "") {
                            echo"<option value=\"\">--Select--</option>";
                          }else{
                        ?>
                        <option value="<?php echo $survey_consignee; ?>"><?php echo $company_name ?></option>
                        <?php }
                          $run1 = mysqli_query($db, "SELECT * FROM surveycompany ");
                          while ($row1 = mysqli_fetch_assoc($run1)) {
                            $id = $row1['id']; $company_name = $row1['company_name'];
                            if ($id == $survey_consignee) { continue; }
                            echo"<option value=\"$id\">$company_name</option>";
                          }//echo"<option value=\"\">--Select--</option>";
                        ?>
                      </select>
                    </div>
                    <?php } ?>

                    <?php if($row2['received_by']==0){ ?>
                    <div class="form-group col-md-6">
                      <label for="inputState">Received By</label>
                      <select id="inputState" class="form-control search" name="received_by">
                        <option value="<?php echo $received_by ?>"><?php echo $rcvbynm; ?></option>
                        <?php
                          $run1 = mysqli_query($db, "SELECT * FROM users WHERE office_position != 'Representative' ");
                          while ($row1 = mysqli_fetch_assoc($run1)) {
                            $id = $row1['id']; $name = $row1['name'];
                            if ($received_by == $id) { continue; }
                            echo"<option value=\"$id\">$name</option>";
                          }
                        ?>
                      </select>
                    </div>
                    <?php } ?>

                    <?php if($row2['sailed_by']==0){ ?>
                    <div class="form-group col-md-6">
                      <label for="inputState">Sailed By</label>
                      <select id="inputState" class="form-control search" name="sailed_by">
                        <option value="<?php echo $sailed_by ?>"><?php echo $slbynm; ?></option>
                        <?php
                          $run1 = mysqli_query($db, "SELECT * FROM users WHERE office_position != 'Representative' ");
                          while ($row1 = mysqli_fetch_assoc($run1)) {
                            $id = $row1['id']; $name = $row1['name'];
                            if ($sailed_by == $id) { continue; }
                            echo"<option value=\"$id\">$name</option>";
                          }
                        ?>
                      </select>
                    </div>
                    <?php } ?>
                  </div>


                  <?php// if(exist("vessels_cargo","vsl_num = ".$vsl_num." ")==0){ ?>
                  <!-- <hr>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      Cargo Section
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-3">
                      <label for="inputState">Select Cargo</label>
                      <select id="inputState" name="cargokey" class="form-control search">
                        <option value="">--Select--</option>
                        <?php selectOptions('cargokeys', 'name'); ?>
                      </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label for="inputState">Select Loadport</label>
                      <select id="inputState" name="loadport" class="form-control search">
                        <option value="">--Select--</option>
                        <?php selectOptions('loadport', 'port_name'); ?>
                      </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputState">Quantity</label>
                      <input type="number" step="any" class="form-control" name="quantity">
                    </div>
                  </div>
                  
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="inputState">Cargo Bl Name</label>
                      <input type="text" class="form-control" name="cargo_bl_name">
                    </div>
                  </div> -->
                  <?php// } ?>











                  <!-- vessels surveyors -->
                  <?php $listsurveyor = 0; ?>
                  <?php if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND surveyor = 0 ") || exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND surveyor = 0 ")){ ?>
                    <hr>
                    <!-- survey custom -->
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        Surveyor Section
                      </div>
                    </div>
                    <!-- <input type="hidden" name="msl_num" value="<?php echo $msl_num; ?>"> -->

                    <?php if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND surveyor = 0 ")){ ?>
                    <div class="form-row">
                      <?php
                        $listsurveyor++;
                        $id = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND surveyor = 0 ", "id"); $party = "survey_custom";
                      ?>
                      <input type="hidden" name="listsurveyor" value="<?php echo $listsurveyor; ?>">
                      <input type="hidden" name="thisrowIdsurveyor<?php echo $listsurveyor; ?>" value="<?php echo $id; ?>">
                      <div class="form-group col-md-4">
                        <label for="inputState">Party</label>
                        <input type="hidden" name="party<?php echo $listsurveyor ?>" value="<?php echo $party ?>">
                        <select id="inputState" class="form-control search" name="party" disabled>
                          <option value="<?php echo $party ?>"><?php echo $party ?></option>
                          <option value="survey_custom">Custom</option>
                          <option value="survey_consignee">Consignee</option>
                          <option value="survey_owner">Owner</option>
                          <option value="survey_pni">PNI</option>
                          <option value="survey_chattrer">Chattrer</option>
                        </select>
                      </div>

                      <?php
                        $survey_purpose = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND surveyor = 0 ", "survey_purpose");
                      ?>
                      <div class="form-group col-md-4">
                        <label for="inputState">Survey Purpose</label>
                        <select id="inputState" class="form-control search" name="survey_purpose<?php echo $listsurveyor ?>">
                          <option value="<?php echo $survey_purpose; ?>"><?php echo $survey_purpose; ?></option>
                          <option value="both">Load & Light</option>
                          <option value="Load Draft">Load Draft</option>
                          <option value="Rob">Rob</option>
                          <option value="Light Draft">Light Draft</option>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Surviour</label>
                        <select id="inputState" class="form-control search" name="surveyorId<?php echo $listsurveyor ?>">
                          <option value="">--Select--</option>
                          <!-- <option value="<?php echo $surveyor ?>"><?php echo $surveyor_name; ?></option> -->
                          <?php selectOptions('surveyors', 'surveyor_name'); ?>
                        </select>
                      </div>
                    </div>
                    <?php } ?>






                    <?php if(exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND surveyor = 0 ")==1){ ?>
                    <!-- survey consignee -->
                    <div class="form-row">
                      <?php
                        $listsurveyor++;
                        $id = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND surveyor = 0 ", "id"); $party = "survey_consignee";
                      ?>
                      <input type="hidden" name="listsurveyor" value="<?php echo $listsurveyor; ?>">
                      <input type="hidden" name="thisrowIdsurveyor<?php echo $listsurveyor ?>" value="<?php echo $id; ?>">
                      <div class="form-group col-md-4">
                        <label for="inputState">Party</label>
                        <input type="hidden" name="party<?php echo $listsurveyor ?>" value="<?php echo $party ?>">
                        <select id="inputState" class="form-control search" name="party" disabled>
                          <option value="<?php echo $party ?>"><?php echo $party ?></option>
                          <option value="survey_custom">Custom</option>
                          <option value="survey_consignee">Consignee</option>
                          <option value="survey_owner">Owner</option>
                          <option value="survey_pni">PNI</option>
                          <option value="survey_chattrer">Chattrer</option>
                        </select>
                      </div>

                      <?php
                        $survey_purpose = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND surveyor = 0 ", "survey_purpose");
                      ?>
                      <div class="form-group col-md-4">
                        <label for="inputState">Survey Purpose</label>
                        <select id="inputState" class="form-control search" name="survey_purpose<?php echo $listsurveyor ?>">
                          <option value="<?php echo $survey_purpose; ?>"><?php echo $survey_purpose; ?></option>
                          <option value="both">Load & Light</option>
                          <option value="Load Draft">Load Draft</option>
                          <option value="Rob">Rob</option>
                          <option value="Light Draft">Light Draft</option>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Surviour</label>
                        <select id="inputState" class="form-control search" name="surveyorId<?php echo $listsurveyor ?>">
                          <option value="">--Select--</option>
                          <!-- <option value="<?php echo $surveyor ?>"><?php echo $surveyor_name; ?></option> -->
                          <?php selectOptions('surveyors', 'surveyor_name'); ?>
                        </select>
                      </div>
                    </div>
                    <?php } ?>

                  <?php } ?>


                  <!-- survey supplier -->
                  <?php if($row2['survey_supplier'] != 0 && exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND surveyor = 0 ")==1){ ?>
                    <!-- survey consignee -->
                    <div class="form-row">
                      <?php
                        $listsurveyor++;
                        $id = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND surveyor = 0 ", "id"); $party = "survey_supplier";
                      ?>
                      <input type="hidden" name="listsurveyor" value="<?php echo $listsurveyor; ?>">
                      <input type="hidden" name="thisrowIdsurveyor<?php echo $listsurveyor ?>" value="<?php echo $id; ?>">
                      <div class="form-group col-md-4">
                        <label for="inputState">Party</label>
                        <input type="hidden" name="party<?php echo $listsurveyor ?>" value="<?php echo $party ?>">
                        <select id="inputState" class="form-control search" name="party" disabled>
                          <option value="<?php echo $party ?>"><?php echo $party ?></option>
                          <option value="survey_custom">Custom</option>
                          <option value="survey_consignee">Consignee</option>
                          <option value="survey_owner">Owner</option>
                          <option value="survey_pni">PNI</option>
                          <option value="survey_chattrer">Chattrer</option>
                        </select>
                      </div>

                      <?php
                        $survey_purpose = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND surveyor = 0 ", "survey_purpose");
                      ?>
                      <div class="form-group col-md-4">
                        <label for="inputState">Survey Purpose</label>
                        <select id="inputState" class="form-control search" name="survey_purpose<?php echo $listsurveyor ?>">
                          <option value="<?php echo $survey_purpose; ?>"><?php echo $survey_purpose; ?></option>
                          <option value="both">Load & Light</option>
                          <option value="Load Draft">Load Draft</option>
                          <option value="Rob">Rob</option>
                          <option value="Light Draft">Light Draft</option>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Surviour</label>
                        <select id="inputState" class="form-control search" name="surveyorId<?php echo $listsurveyor ?>">
                          <option value="">--Select--</option>
                          <!-- <option value="<?php echo $surveyor ?>"><?php echo $surveyor_name; ?></option> -->
                          <?php selectOptions('surveyors', 'surveyor_name'); ?>
                        </select>
                      </div>
                    </div>
                  <?php } ?>


                  <!-- survey owner -->
                  <?php if($row2['survey_owner'] != 0 && exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND surveyor = 0 ")==1){ ?>
                    <!-- survey consignee -->
                    <div class="form-row">
                      <?php
                        $listsurveyor++;
                        $id = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND surveyor = 0 ", "id"); $party = "survey_owner";
                      ?>
                      <input type="hidden" name="listsurveyor" value="<?php echo $listsurveyor; ?>">
                      <input type="hidden" name="thisrowIdsurveyor<?php echo $listsurveyor ?>" value="<?php echo $id; ?>">
                      <div class="form-group col-md-4">
                        <label for="inputState">Party</label>
                        <input type="hidden" name="party<?php echo $listsurveyor ?>" value="<?php echo $party ?>">
                        <select id="inputState" class="form-control search" name="party" disabled>
                          <option value="<?php echo $party ?>"><?php echo $party ?></option>
                          <option value="survey_custom">Custom</option>
                          <option value="survey_consignee">Consignee</option>
                          <option value="survey_owner">Owner</option>
                          <option value="survey_pni">PNI</option>
                          <option value="survey_chattrer">Chattrer</option>
                        </select>
                      </div>

                      <?php
                        $survey_purpose = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND surveyor = 0 ", "survey_purpose");
                      ?>
                      <div class="form-group col-md-4">
                        <label for="inputState">Survey Purpose</label>
                        <select id="inputState" class="form-control search" name="survey_purpose<?php echo $listsurveyor ?>">
                          <option value="<?php echo $survey_purpose; ?>"><?php echo $survey_purpose; ?></option>
                          <option value="both">Load & Light</option>
                          <option value="Load Draft">Load Draft</option>
                          <option value="Rob">Rob</option>
                          <option value="Light Draft">Light Draft</option>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Surviour</label>
                        <select id="inputState" class="form-control search" name="surveyorId<?php echo $listsurveyor ?>">
                          <option value="">--Select--</option>
                          <!-- <option value="<?php echo $surveyor ?>"><?php echo $surveyor_name; ?></option> -->
                          <?php selectOptions('surveyors', 'surveyor_name'); ?>
                        </select>
                      </div>
                    </div>
                  <?php } ?>


                  <!-- survey pni -->
                  <?php if($row2['survey_pni'] != 0 && exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND surveyor = 0 ")==1){ ?>
                    <!-- survey consignee -->
                    <div class="form-row">
                      <?php
                        $listsurveyor++;
                        $id = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND surveyor = 0 ", "id"); $party = "survey_pni";
                      ?>
                      <input type="hidden" name="listsurveyor" value="<?php echo $listsurveyor; ?>">
                      <input type="hidden" name="thisrowIdsurveyor<?php echo $listsurveyor ?>" value="<?php echo $id; ?>">
                      <div class="form-group col-md-4">
                        <label for="inputState">Party</label>
                        <input type="hidden" name="party<?php echo $listsurveyor ?>" value="<?php echo $party ?>">
                        <select id="inputState" class="form-control search" name="party" disabled>
                          <option value="<?php echo $party ?>"><?php echo $party ?></option>
                          <option value="survey_custom">Custom</option>
                          <option value="survey_consignee">Consignee</option>
                          <option value="survey_owner">Owner</option>
                          <option value="survey_pni">PNI</option>
                          <option value="survey_chattrer">Chattrer</option>
                        </select>
                      </div>

                      <?php
                        $survey_purpose = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND surveyor = 0 ", "survey_purpose");
                      ?>
                      <div class="form-group col-md-4">
                        <label for="inputState">Survey Purpose</label>
                        <select id="inputState" class="form-control search" name="survey_purpose<?php echo $listsurveyor ?>">
                          <option value="<?php echo $survey_purpose; ?>"><?php echo $survey_purpose; ?></option>
                          <option value="both">Load & Light</option>
                          <option value="Load Draft">Load Draft</option>
                          <option value="Rob">Rob</option>
                          <option value="Light Draft">Light Draft</option>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Surviour</label>
                        <select id="inputState" class="form-control search" name="surveyorId<?php echo $listsurveyor ?>">
                          <option value="">--Select--</option>
                          <!-- <option value="<?php echo $surveyor ?>"><?php echo $surveyor_name; ?></option> -->
                          <?php selectOptions('surveyors', 'surveyor_name'); ?>
                        </select>
                      </div>
                    </div>
                  <?php } ?>


                  <!-- survey chattrer -->
                  <?php if($row2['survey_chattrer'] != 0 && exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND surveyor = 0 ")==1){ ?>
                    <!-- survey consignee -->
                    <div class="form-row">
                      <?php
                        $listsurveyor++;
                        $id = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND surveyor = 0 ", "id"); $party = "survey_chattrer";
                      ?>
                      <input type="hidden" name="listsurveyor" value="<?php echo $listsurveyor; ?>">
                      <input type="hidden" name="thisrowIdsurveyor<?php echo $listsurveyor ?>" value="<?php echo $id; ?>">
                      <div class="form-group col-md-4">
                        <label for="inputState">Party</label>
                        <input type="hidden" name="party<?php echo $listsurveyor ?>" value="<?php echo $party ?>">
                        <select id="inputState" class="form-control search" name="party" disabled>
                          <option value="<?php echo $party ?>"><?php echo $party ?></option>
                          <option value="survey_custom">Custom</option>
                          <option value="survey_consignee">Consignee</option>
                          <option value="survey_owner">Owner</option>
                          <option value="survey_pni">PNI</option>
                          <option value="survey_chattrer">Chattrer</option>
                        </select>
                      </div>

                      <?php
                        $survey_purpose = getdata("vessels_surveyor", "vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND surveyor = 0 ", "survey_purpose");
                      ?>
                      <div class="form-group col-md-4">
                        <label for="inputState">Survey Purpose</label>
                        <select id="inputState" class="form-control search" name="survey_purpose<?php echo $listsurveyor ?>">
                          <option value="<?php echo $survey_purpose; ?>"><?php echo $survey_purpose; ?></option>
                          <option value="both">Load & Light</option>
                          <option value="Load Draft">Load Draft</option>
                          <option value="Rob">Rob</option>
                          <option value="Light Draft">Light Draft</option>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="inputState">Surviour</label>
                        <select id="inputState" class="form-control search" name="surveyorId<?php echo $listsurveyor ?>">
                          <option value="">--Select--</option>
                          <!-- <option value="<?php echo $surveyor ?>"><?php echo $surveyor_name; ?></option> -->
                          <?php selectOptions('surveyors', 'surveyor_name'); ?>
                        </select>
                      </div>
                    </div>
                    <input type="hidden" name="listsurveyor" value="<?php echo $listsurveyor ?>">
                  <?php } ?>

































                  <!-- vessels cnfs -->
                  <?php// if(exist("vessels_importer","vsl_num = ".$vsl_num." AND importer != 0 AND cnf = 0 ")){ ?>
                    <!-- <hr>
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        C&F Section
                      </div>
                    </div> -->

                    <?php 
                      // $listimporter = 0;
                      // $sql1 = "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' AND importer != 0 AND cnf = 0 "; $run3 = mysqli_query($db, $sql1); while ($row3 = mysqli_fetch_assoc($run3)) { 
                      //   $importerId=$row3['importer'];$cnfId=$row3['cnf'];$idthis=$row3['id'];
                      //   $listimporter++;
                    ?>
                    <!-- <input type="hidden" name="thisrowIdcnf<?php echo $listimporter ?>" value="<?php echo $idthis; ?>">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inputState">Importer</label>
                        <input type="hidden" name="listimporter" value="<?php echo $listimporter; ?>">
                        <input type="hidden" name="importerId<?php echo $listimporter ?>" value="<?php echo $importerId ?>">
                        <select id="inputState" class="form-control selectpicker" name="importers<?php echo $listimporter; ?>" disabled>
                          <?php 
                            $run5 = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
                            while ($row5 = mysqli_fetch_assoc($run5)) {
                              $thisid = $row5['id']; $imid = $row5['importer']; $cn = $row5['cnf'];

                              // select importer
                              if($cn==$cnfId && $cnfId != 0){$selected="selected";}
                              else{$selected = "";}
                              if($imid==$importerId && $importerId != 0){$selected="selected";}
                              
                              $impId = $row5['importer']; $impName = allData('bins', $impId, 'name');
                              echo "<option value=\"$impId\" $selected>$impName</option>";
                            }
                          ?>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputState">Cnf</label>
                        <select id="inputState" class="form-control search" name="cnfId<?php echo $listimporter; ?>">
                          <option value="">--Select--</option>
                          <?php selectOptions('cnf', 'name'); ?>
                        </select>
                      </div>
                    </div> -->
                    <?php// } ?>
                  <?php// } ?>




                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" name="percentagecomplete" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
















        <!-- Please do not remove the backlink to us unless you support us at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
        <!-- <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
              <p class="no-margin-bottom">2020 &copy; Multiport. Design by <a href="https://bootstrapious.com/p/bootstrap-4-dark-admin">Bootstrapious</a>.</p>
            </div>
          </div>
        </footer> -->
        <?php //include('inc/footercredit.php'); ?>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script>
      $(document).ready(function () {
          $('#bl_cargo_checkbox').change(function () {
              if ($(this).is(':checked')) {
                  // Checkbox checked – fetch cargo name via AJAX
                  $.ajax({
                      url: 'inc/get_cargo_name.php',
                      method: 'POST',
                      data: {
                          vsl_num: $('input[name="vsl_num"]').val()
                      },
                      success: function (response) {
                          $('#cargo_name_field').val(response);
                      },
                      error: function (xhr, status, error) {
                          console.error('AJAX Error:', status, error);
                          console.log('Response Text:', xhr.responseText);
                          alert('Something went wrong! Check console.');
                      }
                  });
              } else {
                  // Checkbox unchecked – clear the cargo name input
                  $('#cargo_name_field').val('');
              }
          });
        });
    </script> -->
    <script>
      $(document).ready(function () {
          const vslNum = $('input[name="vsl_num"]').val();

          function fetchFieldValue(field, targetInputId) {
              $.ajax({
                  url: 'inc/get_bl_field_value.php',
                  method: 'POST',
                  data: {
                      vsl_num: vslNum,
                      field: field
                  },
                  success: function (response) {
                      try {
                          const data = JSON.parse(response);
                          if (data[field] !== undefined) {
                              $('#' + targetInputId).val(data[field]);
                          }
                      } catch (e) {
                          console.error("JSON parse error", e, response);
                      }
                  },
                  error: function (xhr, status, error) {
                      console.error('AJAX Error:', status, error);
                      alert('Error loading field: ' + field);
                  }
              });
          }

          // Cargo Name
          $('#bl_cargo_checkbox').change(function () {
              if ($(this).is(':checked')) {
                  fetchFieldValue('cargo_name', 'cargo_name_field');
              } else {
                  $('#cargo_name_field').val('');
              }
          });

          // Shipper Name
          $('#bl_shippername_checkbox').change(function () {
              if ($(this).is(':checked')) {
                  fetchFieldValue('shipper_name', 'shipper_name_field');
              } else {
                  $('#shipper_name_field').val('');
              }
          });

          // Shipper Address
          $('#bl_shipperaddress_checkbox').change(function () {
              if ($(this).is(':checked')) {
                  fetchFieldValue('shipper_address', 'shipper_address_field');
              } else {
                  $('#shipper_address_field').val('');
              }
          });
      });
    </script>



    <?php include('inc/footer.php'); ?>