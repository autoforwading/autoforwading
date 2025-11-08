<?php include('inc/header.php'); if(isset($_GET['page'])){$page = $_GET['page'];} ?>
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
    <?php if(empty($page) || !isset($_GET['page'])){ ?>
    <section class="no-padding-top no-padding-bottom">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="block">

              <div class="title">
                <strong>All Bond Numbers</strong>
                <!-- add vassel modal and btn -->
                <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addPrizeBond">+ ADD</button>
                

                <!-- Modal -->
                <div class="modal fade" id="addPrizeBond" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Insert Bond Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="post" action="prizebond.php">
                        <div class="modal-body">
                          
                          <div class="form-group row">
                            <div class="col-sm-12">
                              <div class="row">
                                <label for="exampleInputPassword1">Prizebond Number</label>
                                <div class="col-md-12">
                                  <input type="text" name="bond_num" class="form-control" required>
                                </div>
                              </div><br/>
                            </div>
                          </div>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" name="addPrizeBond" class="btn btn-primary">+ADD</button>
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
                      <th>OWNER</th>
                      <th>Email</th>
                      <th>BOND NUMBERS</th>
                      <th>ACTIONS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php allBonds(); ?>
                  </tbody>
                </table>
              </div>


            </div>
          </div>
        </div>
      </div>
    </section>
    <?php } ?>
    
    <?php include('inc/footercredit.php'); ?>
  </div>
</div>


<!-- bin numbers edit -->
<?php
  $run = mysqli_query($db, "SELECT * FROM prizebond");
  while ($row = mysqli_fetch_assoc($run)) {
    $id = $row['id']; $owner = $row['owner']; $bond_num = $row['bond_num'];
?>
<div class="modal fade" id="<?php echo"editPrizeBond".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Insert Bond Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="prizebond.php">
        <div class="modal-body">
          
          <div class="form-group row">
            <div class="col-sm-12">
              <div class="row">
                <label for="exampleInputPassword1">Bond Number</label>
                <div class="col-md-12">
                  <input type="hidden" name="bondId" value="<?php echo $id; ?>">
                  <input type="text" name="bond_num" value="<?php echo $bond_num; ?>" class="form-control" required >
                </div><br>
              </div><br/>

            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="editPrizeBond" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>

<?php include('inc/footer.php'); ?>