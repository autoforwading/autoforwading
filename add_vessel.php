<?php include('inc/header.php'); ?>
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include('inc/sidebar.php'); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content" style="overflow: hidden;/*this is for select option hide*/">

        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">
              <span>Dashboard</span>
            </h2>
          </div>
        </div>
        <?php echo $msg; /*include('inc/errors.php');*/ ?>

        
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title"><strong>Add Vessel</strong></div>
                  <div class="block-body">


                    <form method="post" action="add_vessel.php">
                      <!-- 1st -->
                      <div class="form-row">
                        <div class="form-group col-md-1">
                          <label for="inputEmail4">Msl Num</label>
                          <input type="text" class="form-control" name="msl_num" required placeholder="101">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Vessel Name</label>
                          <input type="text" class="form-control" name="vessel_name" required placeholder="SOMEHTING">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Arrived</label>
                          <input type="date" class="form-control" name="arrived">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Receivd (same) <input type="checkbox" name="sameRcv" value="sameRcv" checked=""></label>
                          <input type="date" class="form-control" name="rcv_date">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Completd</label>
                          <input type="date" class="form-control" name="com_date">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Sailed (same) <input type="checkbox" name="sameSail" value="sameSail" checked=""></label>
                          <input type="date" class="form-control" name="sailing_date">
                        </div>
                        <!-- <div class="form-group col-md-3">
                          <label for="inputPassword4">Cargo Short Name</label>
                          <input type="text" class="form-control" name="cargo_short_name" required placeholder="WHEAT">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Quantyti</label>
                          <input type="number" class="form-control" name="total_qty" required >
                        </div> -->
                      </div>

                      <!-- 2nd -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Kutubdia Quantity</label>
                          <input type="number" step="any" class="form-control" name="kutubdia_qty" >
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Outer Quantity</label>
                          <input type="number" step="any" class="form-control" name="outer_qty">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputPassword4">Retention Quantity</label>
                          <input type="number" step="any" class="form-control" name="retention_qty">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">78 Quantity</label>
                          <input type="number" step="any" class="form-control" name="seventyeight_qty">
                        </div>
                      </div>

                      <!-- 3rd -->
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputState">Impoter</label>
                          <select name="importer[]" class="form-control mb-3 mb-3 selectpicker" multiple style="background: transparent;" data-live-search="true">
                            <!-- <option value="0">SELECT CONSIGNEE</option> -->
                            <?php// selectOptions('consignee', 'name'); ?>
                            <?php
                            $getimporter = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                            while ($row = mysqli_fetch_assoc($getimporter)) {
                              $id = $row['id']; $value = $row['name'];
                              echo"<option value=\"$id\">$value</option>";
                            }
                            ?>
                          </select>
                        </div>


                        <div class="form-group col-md-6">
                          <label for="inputState">Stevedore</label>
                          <select id="inputState" class="form-control search" name="stevedore">
                            <option value="">--Select--</option>
                            <?php selectOptions('stevedore', 'name'); ?>
                            <!-- <option>...</option> -->
                          </select>
                        </div>
                        
                        <div class="form-group col-md-3">
                          <label for="inputState">Representative</label>
                          <select id="inputState" class="form-control search" name="representative">
                            <option value="">--Select--</option>
                            <?php
                              $run1 = mysqli_query($db, "SELECT * FROM users WHERE office_position = 'Representative' OR office_position = 'Junior Shipping Exicutive' ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $userId = $row1['id']; $representative = $row1['name'];
                                // if ($userId == $received_by) { continue; }
                                echo"<option value=\"$userId\">$representative</option>";
                              }
                            ?>
                            <!-- <option value="">...</option> -->
                          </select>
                        </div>
                      </div>

                      <!-- 4th -->
                      <div class="form-row">
                        <!-- <div class="form-group col-md-6">
                          <label for="inputPassword4">Cargo full name</label>
                          <input type="text" class="form-control" name="cargo_bl_name" required>
                        </div> -->
                        <!--div class="form-group col-md-3">
                          <label for="inputEmail4">Fender On</label>
                          <input type="text" class="form-control" name="fender_on">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Fender Off</label>
                          <input type="text" class="form-control" name="fender_off">
                        </div-->
                        <div class="form-group col-md-6">
                          <label for="inputState">Rotation</label>
                          <input type="text" class="form-control" name="rotation">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputState">Anchorage</label>
                          <select id="inputState" class="form-control" name="anchor">
                            <option value="">--Select--</option>
                            <option value="Outer">Outer</option>
                            <option value="Kutubdia">Kutubdia</option>
                          </select>
                        </div>
                      </div>

                      <!-- 5th -->
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="inputState">Custom Survey</label>
                          <select id="inputState" class="form-control search" name="survey_custom">
                            <option value="">--Select--</option>
                            <?php selectOptions('surveycompany', 'company_name'); ?>
                            <!-- <option value="">...</option> -->
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">Consignee Survey</label>
                          <select id="inputState" class="form-control search" name="survey_consignee">
                            <option value="">--Select--</option>
                            <?php selectOptions('surveycompany', 'company_name'); ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">Supplier Survey</label>
                          <select id="inputState" class="form-control search" name="survey_supplier">
                            <option value="">--Select--</option>
                            <?php selectOptions('surveycompany', 'company_name'); ?>
                          </select>
                        </div>
                      </div>

                      <!-- 6th -->
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="inputState">Owner Survey</label>
                          <select id="inputState" class="form-control search" name="survey_owner">
                            <option value="">--Select--</option>
                            <?php selectOptions('surveycompany', 'company_name'); ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">P&I Survey</label>
                          <select id="inputState" class="form-control search" name="survey_pni">
                            <option value="">--Select--</option>
                            <?php selectOptions('surveycompany', 'company_name'); ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputState">Chattrer Survey</label>
                          <select id="inputState" class="form-control search" name="survey_chattrer">
                            <option value="">--Select--</option>
                            <?php selectOptions('surveycompany', 'company_name'); ?>
                          </select>
                        </div>
                      </div>

                      <!-- 7th -->
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inputState">Received By</label>
                          <select id="inputState" class="form-control search" name="received_by">
                            <option value="">--Select--</option>
                            <?php
                              $run1 = mysqli_query($db, "SELECT * FROM users WHERE office_position != 'Representative' ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $userId = $row1['id']; $username = $row1['name'];
                                // if ($userId == $received_by) { continue; }
                                echo"<option value=\"$userId\">$username</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputState">Sailed By</label>
                          <select id="inputState" class="form-control search" name="sailed_by">
                            <option value="">--Select--</option>
                            <?php
                              $run1 = mysqli_query($db, "SELECT * FROM users WHERE office_position != 'Representative' ");
                              while ($row1 = mysqli_fetch_assoc($run1)) {
                                $userId = $row1['id']; $username = $row1['name'];
                                // if ($userId == $received_by) { continue; }
                                echo"<option value=\"$userId\">$username</option>";
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <!-- 8th -->
                      <div class="form-group">
                        <label for="exampleInputPassword1">Insert / Write Remarks</label>
                        <!-- <input type="text" name="bank_name" class="form-control" required placeholder="BANK NAME"> -->
                        <textarea name="remarks" class="form-control" rows="3"></textarea>
                        <!-- <label for="inputState">Remarks</label>
                        <select name="remarks[]" class="form-control mb-3 mb-3 selectpicker" multiple style="background: transparent;" data-live-search="true">
                            <?php// selectOptions('remarks', 'name'); ?>
                          </select> -->
                      </div>

                      <!-- 8th -->
                      <!-- <div class="form-group">
                        <label for="exampleFormControlTextarea1">Vessel Remarks</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                      </div> -->

                      <!-- <div class="form-group">
                        <label for="inputAddress">Address</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                      </div>
                      <div class="form-group">
                        <label for="inputAddress2">Address 2</label>
                        <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                      </div> -->

                      <!-- <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="gridCheck">
                          <label class="form-check-label" for="gridCheck">
                            Check me out
                          </label>
                        </div>
                      </div> -->

                      <button type="submit" class="btn btn-secondary" name="addVassel">Submit</button>
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
              
              <!-- Form Elements -->
              <div class="col-lg-12">
                <div class="block">
                  <div class="title"><strong>Add Cargo</strong></div>
                  <div class="block-body">
                    <form method="post" action="add_vessel.php">
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputState">Select Vessel</label>
                          <select id="inputState" name="vesselId" class="form-control search" required>
                            <option value="">--Select--</option>
                            <?php selectOptions('vessels', 'vessel_name'); ?>
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
                          <input type="number" class="form-control" name="quantity" required>
                        </div>
                      </div>
                      
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputState">Select Cargo</label>
                          <select id="inputState" name="cargokey" class="form-control search" required>
                            <option value="">--Select--</option>
                            <?php selectOptions('cargokeys', 'name'); ?>
                          </select>
                        </div>
                        <div class="form-group col-md-9">
                          <label for="inputState">Cargo Bl Name</label>
                          <input type="text" class="form-control" name="cargo_bl_name" required>
                        </div>
                      </div>

                      <button type="submit" name="addCargoConsigneewise" class="btn btn-secondary">
                        +ADD
                      </button>
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
              
              <!-- Form Elements -->
              <div class="col-lg-6">
                <div class="block">
                  <div class="title"><strong>Add C&F</strong></div>
                  <div class="block-body">
                    <form method="post" action="add_vessel.php">
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inputState">Select Vessel</label>
                          <select id="inputState" name="vesselId" class="form-control search">
                            <option value="">--Select--</option>
                            <?php selectOptions('vessels', 'vessel_name'); ?>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputState">Select Importer</label>
                          <select id="inputState" name="consigneeId" class="form-control search">
                            <option value="">--Select--</option>
                            <?php selectOptions('consignee', 'name'); ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="inputState">Select CNF</label>
                          <select id="inputState" name="cnfId" class="form-control search">
                            <option value="">--Select--</option>
                            <?php selectOptions('cnf', 'name'); ?>
                          </select>
                        </div>
                      </div>

                      <button type="submit" name="addVesselsCnf" class="btn btn-secondary">+ADD</button>
                    </form>
                  </div>
                </div>
              </div>



              <div class="col-lg-6">
                <div class="block">
                  <div class="title"><strong>Add Surviour</strong></div>
                  <div class="block-body">
                    <form method="post" action="add_vessel.php">
                      
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inputState">Vessel</label>
                          <select id="inputState" class="form-control search" name="vesselId">
                            <option value="">--Select--</option>
                            <?php selectOptions('vessels', 'vessel_name'); ?>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputState">Party</label>
                          <select id="inputState" class="form-control search" name="party">
                            <option value="">--Select--</option>
                            <option value="survey_custom">Custom</option>
                            <option value="survey_consignee">Consignee</option>
                            <option value="survey_owner">Owner</option>
                            <option value="survey_pni">PNI</option>
                            <option value="survey_chattrer">Chattrer</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inputState">Surviour</label>
                          <select id="inputState" class="form-control search" name="surveyorId">
                            <option value="">--Select--</option>
                            <?php selectOptions('surveyors', 'surveyor_name'); ?>
                          </select>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="inputState">Survey Purpose</label>
                          <select id="inputState" class="form-control search" name="survey_purpose">
                            <option value="">--Select--</option>
                            <option value="Load Draft">Load Draft</option>
                            <option value="Rob">Rob</option>
                            <option value="Light Draft">Light Draft</option>
                          </select>
                        </div>
                      </div>

                      <button type="submit" class="btn btn-secondary" name="addVesselsSurveyor">+ADD</button>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>


        <!-- Please do not remove the backlink to us unless you support us at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
        <!-- <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
              <p class="no-margin-bottom">2020 &copy; Multiport. Design by <a href="https://bootstrapious.com/p/bootstrap-4-dark-admin">Tafsin Sanjid Turan</a>.</p>
            </div>
          </div>
        </footer> -->
        <?php include('inc/footercredit.php'); ?>
      </div>
    </div>
    <?php include('inc/footer.php'); ?>