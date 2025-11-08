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
    <?php echo $msg; //include('inc/errors.php'); ?>

    <?php $page = $_GET['page']; if(!empty($page) && $page == "binNumbers"){ ?>
    <section class="no-padding-top no-padding-bottom">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="block">
              <?php if(allData('useraccess',$my['office_position'],'bin_ctrl')){ ?>
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
                      <th>BIN NUMBERS</th>
                      <th>ACTIONS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php allBins(); ?>
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
            <form method="post" action="others_adds.php?page=binNumbers">
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
    <?php } elseif(!empty($page) && $page == "cargoKeys"){ ?>
    <section class="no-padding-top no-padding-bottom">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
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
                      <th>Vsl Qty</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php cargoKeys(); ?>
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
    <?php } elseif(!empty($page) && $page == "useraccess"){ ?>
    <section class="no-padding-top no-padding-bottom">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="block">

              <?php if(allData('useraccess',$my['office_position'],'access_ctrl')){ ?>
              <div class="title">
                <strong>Designations</strong>
                <!-- add vassel modal and btn -->
                <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addUseraccess">+ ADD</button>
                

                <!-- Modal -->
                <div class="modal fade" id="addUseraccess" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Insert Disegnation Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="post" action="others_adds.php?page=useraccess">
                        <div class="modal-body">
                          
                          <div class="form-group row">
                            <div class="col-sm-12">
                              <div class="row">
                                <label for="exampleInputPassword1">Disegnation</label>
                                <div class="col-md-12">
                                  <input type="text" name="useraccess" class="form-control" required placeholder="Exicutive">
                                </div>
                              </div><br/>

                            </div>
                          </div>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" name="addUseraccess" class="btn btn-primary">+ADD</button>
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
                      <th>Designation</th>
                      <th>DG Control</th>
                      <th>Bin</th>
                      <th>Vessel</th>
                      <th>Forwadings</th>
                      <th>3rd Party</th>
                      <th>Users</th>
                      <th>Others</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php useraccess(); ?>
                  </tbody>
                </table>
              </div>
              <?php }else{ ?>
              <div class="title">
                <strong>You don't have access to this</strong>
              </div>
              <?php } ?>

            </div>
          </div>
        </div>
      </div>
    </section>

    <?php }  elseif(!empty($page) && $page == "test"){ ?>
    <section class="no-padding-top no-padding-bottom">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="block">

              <div class="title">
                <strong>All Cargo Keys</strong>
                <button class="btn btn-primary" onclick="window.print()">Print this page</button>
              </div>

              <div class="table-responsive"> 
                <table id="example" class="table table-dark table-striped table-sm">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>BANK NAME</th>
                      <th>BIN NUMBERS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php binandimporter(); ?>
                  </tbody>
                </table>
              </div>


            </div>
          </div>
        </div>
      </div>
    </section>
    <?php } ?>

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