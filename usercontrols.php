<?php include('inc/header.php'); ?>
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include('inc/sidebar.php'); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">

        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">
              <span>Dashboard</span>
            </h2>
          </div>
        </div>
        <?php echo $msg; $page="";if(isset($_GET['page'])&&!empty($_GET['page'])){$page=$_GET['page'];} ?>
        <?php if ($page == "balance") { ?>
        <!-- add consignee & CNF -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Users</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addUsers">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Users info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="users.php">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Name</label>
                                  <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Designation</label>
                                  <select name="office_position" class="form-control mb-3 mb-3" required>
                                    <option value="">--SELECT--</option>
                                    <?php selectOptions('useraccess', 'designation'); ?>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Email</label>
                                  <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Contact</label>
                                  <input type="text" name="contact" class="form-control" placeholder="Contact">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addUsers" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">Img</th>
                          <th scope="col">Name</th>
                          <th scope="col">Balance</th>
                          <th scope="col">Designation</th>
                          <th scope="col">Email</th>
                          <!-- <th scope="col">Contact</th> -->
                          <th scope="col" style="text-align: center;">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php totalusers(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>


          <!-- edit designation -->
          <?php
            $companyid = $my['companyid'];
            $run3 = mysqli_query($db, "SELECT * FROM users WHERE activation != 'delete' AND companyid = '$companyid' ");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id']; $name = $row3['name']; $office_position = $row3['office_position'];
          ?>
          <!-- Consignee Edit Modal -->
          <div class="modal fade" id="<?php echo"edituserprofile".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Designation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="users.php">
                  <input type="hidden" name="userid" value="<?php echo $id; ?>">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Designation</label>
                        <select name="designation" class="form-control mb-3 mb-3">
                          <option value="<?php echo $office_position; ?>"><?php echo allData('useraccess', $office_position, 'designation'); ?></option>
                          <?php 
                            $run = mysqli_query($db, "SELECT * FROM useraccess WHERE companyid = '$companyid' ORDER BY designation ASC ");
                            while ($row = mysqli_fetch_assoc($run)) {
                              $degid = $row['id']; $designation = $row['designation'];
                              if ($degid == $office_position) { continue; }
                              echo "<option value=\"$degid\">$designation</option>";
                            }
                          ?>
                        </select>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="updatedesignation" class="btn btn-primary">Update</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>


          <!-- Add balance -->
          <?php
            $companyid = $my['companyid'];
            $run3 = mysqli_query($db, "SELECT * FROM users WHERE activation != 'delete' ");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id']; $name = $row3['name']; $balance = $row3['balance'];
          ?>
          <div class="modal fade" id="<?php echo"addbalance".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add Balance</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="usercontrols.php">
                  <input type="hidden" name="userid" value="<?php echo $id; ?>">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Balance</label>
                        <input type="number" step="any" class="form-control" name="balance" placeholder="Available: <?php echo $balance; ?> /-">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="addbalance" class="btn btn-primary">+ Add</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>
        <?php } elseif ($page == "binapproval") { ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <?php if($my['email'] == "skturan2405@gmail.com"){ ?>
                  <div class="title">
                    <strong>All Bank Bin Numbers</strong>
                    <!-- add vassel modal and btn -->
                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addBankBin">+ ADD</button>
                    

                    <!-- Modal -->
                    <div class="modal fade" id="addBankBin" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Insert Bin Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="others_adds.php?page=binNumbers">
                            <div class="modal-body">
                              
                              <div class="form-group row">
                                <div class="col-sm-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <label for="exampleInputPassword1">Name</label>
                                      <input type="text" name="bank_name" class="form-control" required placeholder="NAME">
                                    </div>

                                    <div class="col-md-6">
                                      <label for="exampleInputPassword1">Bin</label>
                                      <input type="text" name="bin_num" class="form-control" required placeholder="BIN NUMBER">
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-12">
                                      <label class="col-sm-12 form-control-label">SELECT TYPE</label>
                                      <select name="type" class="form-control mb-3 mb-3" required>
                                        <option value="">--SELECT--</option>
                                        <option value="BANK">Bank</option>
                                        <option value="IMPORTER">Importer</option>
                                      </select>
                                    </div>
                                  </div>

                                </div>
                              </div>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addbin" class="btn btn-primary">+ADD</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-dark table-striped table-sm">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>BANK NAME</th>
                          <th>STATUS</th>
                          <th>BIN NUMBERS</th>
                          <th>ACTIONS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php binapproval(); ?>
                      </tbody>
                    </table>
                  </div>
                  <?php } else{ ?>
                  <div class="title">
                    <strong>You don't have access to this</strong>
                  </div>
                  <?php  } ?>
                </div>
              </div>
            </div>
          </div>




          <!-- bin numbers edit -->
          <?php
            $run3 = mysqli_query($db, "SELECT * FROM bins");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id']; $type = $row3['type']; $name = $row3['name']; $bin = $row3['bin']; 
          ?>
          <div class="modal fade" id="<?php echo"editBankBin".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Insert Bin Info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="post" action="bin_numbers.php">
                  <input type="hidden" name="pre_bin" value="<?php echo $bin; ?>">
                  <?php if ($type == "IMPORTER") { $sttus = "disabled"; ?>
                    <input type="hidden" name="type" value="IMPORTER">
                  <?php }else{$sttus = "";} ?>
                  <div class="modal-body">
                    
                    <div class="form-group row">
                      <div class="col-sm-12">
                        <div class="row">
                          <label for="exampleInputPassword1">Bank Name & Bin Number</label>
                          <div class="col-md-6">
                            <input type="hidden" name="binId" value="<?php echo $id; ?>">
                            <input type="text" name="bank_name" value="<?php echo $name; ?>" class="form-control" required placeholder="BANK NAME">
                          </div>

                          <div class="col-md-6">
                            <input type="text" name="bin_num" value="<?php echo $bin; ?>" class="form-control" required placeholder="BIN NUMBER">
                          </div><br>

                          <div class="col-md-12">
                            <label class="col-sm-3 form-control-label">TYPE</label>
                            <select name="type" class="form-control mb-3 mb-3" <?php echo $sttus ?>>
                              <?php if($type == "BANK"){ ?>
                              <option value="BANK">Bank</option>
                              <option value="IMPORTER">Importer</option>
                              <?php }else{ ?>
                              <option value="IMPORTER">Importer</option>
                              <option value="BANK">Bank</option>
                              <?php } ?>
                            </select>
                          </div>
                        </div><br/>

                      </div>
                    </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editBankBin" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php } ?>
        </section>
        <?php } elseif(!empty($page) && $page == "cargokeyapproval"){ ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Cargo Keys</strong>
                    <!-- add vassel modal and btn -->
                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addCargoKey">+ ADD</button>
                    

                    <!-- Modal -->
                    <div class="modal fade" id="addCargoKey" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Insert Key Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="others_adds.php?page=cargoKeys">
                            <div class="modal-body">
                              
                              <div class="form-group row">
                                <div class="col-sm-12">
                                  <div class="row">
                                    <label for="exampleInputPassword1">Cargo Key</label>
                                    <div class="col-md-12">
                                      <input type="text" name="cargoKey" class="form-control" required placeholder="CARGO KEY">
                                    </div>
                                  </div><br/>

                                </div>
                              </div>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addCargoKey" class="btn btn-primary">+ADD</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table class="table table-dark table-striped table-sm">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Keys</th>
                          <th>Company</th>
                          <th>Status</th>
                          <th>Vsl Qty</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php cargokeyapproval(); ?>
                      </tbody>
                    </table>
                  </div>


                </div>
              </div>
            </div>
          </div>
          <!-- cargokey edit -->
          <?php
            $run4 = mysqli_query($db, "SELECT * FROM cargokeys");
            while ($row4 = mysqli_fetch_assoc($run4)) {
              $id = $row4['id']; $name = $row4['name'];
          ?>
          <div class="modal fade" id="<?php echo"editCargoKey".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Insert Key Info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="post" action="others_adds.php?page=cargoKeys">
                  <div class="modal-body">
                    
                    <div class="form-group row">
                      <div class="col-sm-12">
                        <div class="row">
                          <label for="exampleInputPassword1">Cargo Short name / Key</label>
                          <div class="col-md-12">
                            <input type="hidden" name="keyId" value="<?php echo $id; ?>">
                            <input type="text" name="cargoKey" value="<?php echo $name; ?>" class="form-control" required placeholder="Key Name">
                          </div>
                        </div><br/>

                      </div>
                    </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editCargoKey" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php } ?>
        </section>


        <!-- loadport -->
        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'loadportapproval'){ ?>
        <!-- loadport -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Load Ports</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addLoadport">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addLoadport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Loadport info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=loadport">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Loadport</label>
                                  <input type="text" name="port_name" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Country Code</label>
                                  <input type="text" name="port_code" class="form-control" required="">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addLoadport" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Port Name</th>
                          <th scope="col">Port Code</th>
                          <th scope="col">Approve</th>
                          <th scope="col">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php approveloadport(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM loadport");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $port_name = $row3['port_name'];
              $port_code = $row3['port_code'];
          ?>
          <!-- Loadport Edit Modal -->
          <div class="modal fade" id="<?php echo"editLoadport".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Loadport info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=loadport">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Name</label>
                        <input type="hidden" name="loadportId" value="<?php echo $id; ?>">
                        <input type="text" name="port_name" value="<?php echo $port_name; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Code</label>
                        <input type="text" name="port_code" value="<?php echo $port_code; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editLoadport" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>




        <!-- approve nationality -->
        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'nationalityapproval'){ ?>
        <!-- nationality -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Nationalities</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addNationality">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addNationality" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Nationality info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=nationality">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Nationality</label>
                                  <input type="text" name="port_name" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Country Code</label>
                                  <input type="text" name="port_code" class="form-control" required="">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addNationality" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Port Name</th>
                          <th scope="col">Port Code</th>
                          <th scope="col">Company</th>
                          <th scope="col">Status</th>
                          <th scope="col">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php approvenationality(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM nationality");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $port_name = $row3['port_name'];
              $port_code = $row3['port_code'];
          ?>
          <!-- Loadport Edit Modal -->
          <div class="modal fade" id="<?php echo"editNationality".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Nationality info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=nationality">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Name</label>
                        <input type="hidden" name="nationalityId" value="<?php echo $id; ?>">
                        <input type="text" name="port_name" value="<?php echo $port_name; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Code</label>
                        <input type="text" name="port_code" value="<?php echo $port_code; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editNationality" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>

        <!-- cnf approval -->
        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'cnfapproval'){ ?>
        <!-- stevedore -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All CNF</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addCnf">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addCnf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add CNF info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=cnf">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">CNF</label>
                                  <input type="text" name="cnfName" class="form-control" placeholder="CNF Name">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Email</label>
                                  <input type="email" name="cnfEmail" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addCnf" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Email</th>
                          <th scope="col">Status</th>
                          <th scope="col" style="text-align: center;">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php cnfapproval(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM cnf");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $name = $row3['name'];
              $email = $row3['email'];
          ?>
          <!-- CNF Edit Modal -->
          <div class="modal fade" id="<?php echo"editCnf".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit CNF info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=cnf">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">CNF</label>
                        <input type="hidden" name="cnfId" value="<?php echo $id; ?>">
                        <input type="text" name="cnfName" value="<?php echo $name; ?>" class="form-control" placeholder="CNF Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" name="cnfEmail" value="<?php echo $email; ?>" class="form-control" placeholder="Email">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editCnf" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>



        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'surveyorapproval'){ ?>
        <!-- stevedore -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>Surveyors</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addSurveyor">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addSurveyor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Surveyor info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=surveyors">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Name</label>
                                  <input type="text" name="surveyor_name" class="form-control" >
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Contact 1</label>
                                  <input type="text" name="contact_1" class="form-control" >
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Contact 2</label>
                                  <input type="text" name="contact_2" class="form-control" >
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addSurveyor" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Surveyor Name</th>
                          <th scope="col">Contact 1</th>
                          <th scope="col">Contact 2</th>
                          <th scope="col">Status</th>
                          <th scope="col" style="text-align: center;">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php surveyorapproval(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <?php
            $run = mysqli_query($db, "SELECT * FROM surveyors");
            while ($row = mysqli_fetch_assoc($run)) {
              $id = $row['id'];
              $surveyor_name = $row['surveyor_name'];
              $contact_1 = $row['contact_1'];
              $contact_2 = $row['contact_2'];
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editSurveyor".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Stevedore info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=surveyors">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Surveyor Name</label>
                        <input type="hidden" name="surveyorId" value="<?php echo $id; ?>">
                        <input type="text" name="surveyor_name" value="<?php echo $surveyor_name; ?>" class="form-control" >
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact 1</label>
                        <input type="text" name="contact_1" value="<?php echo $contact_1; ?>" class="form-control" >
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact 2</label>
                        <input type="text" name="contact_2" value="<?php echo $contact_2; ?>" class="form-control" >
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editSurveyor" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>



        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'surveycompanyapproval'){ ?>
        <!-- surveycompany -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Load Ports</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addSurveycompany">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addSurveycompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Survey Company Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=surveycompany">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Company Name</label>
                                  <input type="text" name="company_name" class="form-control" required="">
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="text" name="contact_person" class="form-control" required="">
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Office Num</label>
                                    <input type="text" name="contact_number" class="form-control" required="">
                                  </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addSurveycompany" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark table-responsive-sm">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Company Name</th>
                          <th scope="col">Status</th>
                          <th scope="col" style="width: 20%">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php surveycompanyapproval(); ?>
                      </tbody>]
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM surveycompany");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id']; $company_name = $row3['company_name']; 
              $contact_person = $row3['email']; $contact_number = $row3['officenum'];
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editSurveycompany".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Company info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=surveycompany">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Company Name</label>
                        <input type="hidden" name="surveycompanyId" value="<?php echo $id; ?>">
                        <input type="text" name="company_name" value="<?php echo $company_name; ?>" class="form-control" placeholder="Company Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" name="contact_person" value="<?php echo $contact_person; ?>" class="form-control" placeholder="Contact Person">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Office Number</label>
                        <input type="text" name="contact_number" value="<?php echo $contact_number; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editSurveycompany" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>




        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'stevedoreapproval'){ ?>
        <!-- stevedore -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Stevedore</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addStevedore">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addStevedore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Stevedore info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=stevedore">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Stevedore</label>
                                  <input type="text" name="stevedoreName" class="form-control" placeholder="Stevedore Name">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addStevedore" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Status</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php stevedoreapproval(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM stevedore");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $name = $row3['name'];
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editStevedore".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Stevedore info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=stevedore">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Stevedore</label>
                        <input type="hidden" name="stevedoreId" value="<?php echo $id; ?>">
                        <input type="text" name="stevedoreName" value="<?php echo $name; ?>" class="form-control" placeholder="Stevedore Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editStevedore" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>


        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'exportlogs'){ ?>
        <!-- stevedore -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Companies</strong>
                    <?php include('inc/errors.php'); ?>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Status</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php alltask(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM stevedore");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $name = $row3['name'];
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editStevedore".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Stevedore info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=stevedore">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Stevedore</label>
                        <input type="hidden" name="stevedoreId" value="<?php echo $id; ?>">
                        <input type="text" name="stevedoreName" value="<?php echo $name; ?>" class="form-control" placeholder="Stevedore Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editStevedore" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>




        <?php } ?>

        <?php include('inc/footercredit.php'); ?>
      </div>
    </div>
    <?php include('inc/footer.php'); ?>