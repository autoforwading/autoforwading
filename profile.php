<?php 
  include('inc/header.php'); 
  // my info
  // logic should be "isset($_SESSION['id'])" so none could update profile except his
  if (isset($_GET['userid']) && !empty($_GET['userid'])) { 
    $id = $_GET['userid']; 
    $run = mysqli_query($db,"SELECT * FROM users WHERE id = '$id' ");
    if (mysqli_num_rows($run) > 0) {
      $viewuser = mysqli_fetch_assoc($run);
      $user = array(
        'id' => $id,
        'companyid' => $viewuser['companyid'],
        'name' => $viewuser['name'],
        'contact' => $viewuser['contact'],
        'email' => $viewuser['email'],
        'image' => $viewuser['image'],
        'password' => $viewuser['password']
      );
    }
    else{$user = array( 'id' => '', 'companyid' => '', 'name' => '', 'contact' => '', 'email' => '', 'image' => '', 'password' => '');}  
  }else{ $user = array( 'id' => '', 'companyid' => '', 'name' => '', 'contact' => '', 'email' => '', 'image' => '', 'password' => ''); }

  // check if user is seeing own id or others
  $mainuser = $_SESSION['id']; $mainusercompanyid = allData('users', $mainuser, 'companyid');
  $viewuser = $user['id']; $viewusercompanyid = $user['companyid'];
  if ($mainuser == $viewuser) { $value = "enabled"; }
  else{$value = "disabled";}
?>
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include('inc/sidebar.php'); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">

        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">
              <span>Dashboard</span>
              <?php if($mainuser == $viewuser){ ?>
              <!-- <a 
                onClick="javascript: return confirm(' Are You Sure You Want to Delete your profile? \n All your Data (Including Company) Will be lost!');" 
                href="profile.php?useraction=deleteuser" 
                class="btn btn-primary btn-sm" 
                style="float: right;" 
              >Delete</a> -->
              <?php } ?>
            </h2>
          </div>
        </div>
        <?php echo $msg; //include('inc/errors.php'); ?>

        <!-- add consignee & CNF -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <?php if($mainusercompanyid == $viewusercompanyid){ ?>
                  <div class="title">
                    <strong>Profile Settings</strong>
                  </div>

                  <div class="table-responsive"> 
                    <form method="post" action="<?php echo pagename().pageurl(); ?>" enctype="multipart/form-data">
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col" class="col-4"></th>
                          <th scope="col" class="col-1"></th>
                          <th scope="col" class="col-7"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td rowspan="3" style="border: 1px solid white;">
                            <img src="img/userimg/<?php echo $user['image']; ?>" alt="..." style="border-radius: 7%;" height="250">
                          </td>
                          <td>Name</td>
                          <td><input type="text" name="name" value="<?php echo $user['name']; ?>" <?php echo $value; ?>></td>
                        </tr>
                        <tr>
                          <td>Email</td>
                          <td><input type="text" name="email" value="<?php echo $user['email']; ?>" <?php echo $value; ?>></td>
                        </tr>
                        <tr>
                          <td>Contact</td>
                          <td><input type="text" name="contact" value="<?php echo $user['contact']; ?>" <?php echo $value; ?>></td>
                        </tr>

                        <tr>
                          <td>
                            <?php if($value == "enabled"){ ?>
                            <input type="file" name="pp">
                            <?php } ?>
                          </td>
                          <td><input type="text" name="oldpass" placeholder="Old Password" <?php echo $value; ?>></td>
                          <td><input type="text" name="newpass" placeholder="New Password" <?php echo $value; ?>></td>
                        </tr>
                        <?php if($value == "enabled"){ ?>
                        <tr>
                          <td colspan="4" style="text-align: right;">
                            <button type="submit" class="btn btn-success" name="updateProfile" value="<?php echo $user['id']; ?>">Update</button>
                            <!-- <button type="submit" class="btn btn-danger" name="cancel" value="cancel">Cancel</button> -->
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    </form>
                  </div>

                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </section>


        <?php
          if ($my['id'] == $company['adminid']) {$comsetting = "enabled";}
          else{$comsetting = "disabled";}
        ?>
        <!-- Company Settings -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <?php if($mainusercompanyid == $viewusercompanyid){ ?>
                  <div class="title">
                    <strong>Company Settings</strong>
                  </div>

                    <form method="post" action="<?php echo pagename().pageurl(); ?>">
                      <div class="form-row">
                        <div class="form-group col-md-5">
                          <label for="inputEmail4">Company name</label>
                          <input type="text" class="form-control" name="companyname" value="<?php echo $company['companyname']; ?>" <?php echo $comsetting; ?>>
                        </div>

                        <div class="form-group col-md-4">
                          <label for="inputEmail4">Company Email</label>
                          <input type="text" class="form-control" name="email" value="<?php echo $company['email']; ?>" <?php echo $comsetting; ?>>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="inputEmail4">Company Contact</label>
                          <input type="text" class="form-control" name="telephone" value="<?php echo $company['telephone']; ?>" <?php echo $comsetting; ?>>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="inputEmail4">Company AIN Number</label>
                          <input type="text" class="form-control" name="companyain" value="<?php echo $company['ain']; ?>" <?php echo $comsetting; ?>>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="inputEmail4">Company Port.</label>
                          <select id="inputState" name="port" class="form-control search" required>
                            <?php if(empty($company['port'])){ ?>
                              <option value="">--SELECT--</option>
                            <?php }else{ ?>
                              <option value="<?php echo $company['port']; ?>"><?php echo allData('loadport', $company['port'], 'port_name'); ?></option>
                            <?php } ?>
                            
                            <?php selectOptions('loadport', 'port_name', 'all'); ?>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Package</label>

                          <select name="package" class="form-control" disabled>
                            <option value="<?php echo $company['package']; ?>">
                              <?php echo ucwords($company['package']); ?>    
                            </option>
                            <option value="free">Free</option>
                            <option value="monthly">Monthly</option>
                            <option value="vesselwise">Vessel Wise</option>
                          </select>
                        </div>

                        <div class="form-group col-md-2">
                          <label for="inputEmail4">Balance</label>
                          <input type="text" class="form-control" name="balance" value="<?php echo $my['balance']." /-"; ?>" disabled>
                        </div>
                      </div>


                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="inputEmail4">Address</label>
                          <textarea type="text" class="form-control" name="address" value="" rows="5" <?php echo $comsetting; ?>><?php echo $company['address']; ?></textarea>
                        </div>
                      </div>
                      <?php if($comsetting == "enabled"){ ?>
                      <button type="submit" name="companyupdate" class="btn btn-success" <?php echo $comsetting; ?>>Update</button>
                      <?php } ?>
                    </form>

                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </section>


        
        <?php include('inc/footercredit.php'); ?>
      </div>
    </div>
    <?php include('inc/footer.php'); ?>