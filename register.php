<?php include('inc/login-register-header.php'); ?>
  
    <div class="login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1>AUTO FORWADINGS</h1>
                  </div>
                  <p>Designed to manage all your hardworks!</p>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form method="post" action="register.php" class="text-left form-validate">
                    <?php echo $msg; ?>
                    <div class="form-group-material">
                      <input id="register-username" type="text" name="registerUsername" value="<?php echo $username ?>" required data-msg="Please enter your username" class="input-material" placeholder="Nick Name">
                      <!-- <label for="register-username" class="label-material">Username</label> -->
                    </div>
                    <!-- <div class="form-group-material">
                      <input id="register-username" type="text" name="registerUsergoodname" required data-msg="Please enter your username" class="input-material" placeholder="Good name">
                    </div> -->
                    <div class="form-group-material">
                      <input id="register-email" type="email" name="registerEmail" value="<?php echo $email ?>" required data-msg="Please enter a valid email address" class="input-material" placeholder="Email Address">
                      <!-- <label for="register-email" class="label-material">Email Address</label> -->
                    </div>
                    <div class="form-group-material">
                      <input id="register-username" type="text" name="registerContact" value="<?php echo $contact ?>" required data-msg="Please enter your username" class="input-material" placeholder="Contact">
                      <!-- <label for="register-username" class="label-material">Contact</label> -->
                    </div>
                    <!-- <div class="form-group">
                      <label for="exampleFormControlSelect1">Office Position</label>
                      <select class="form-control" name="registerPosition" required>
                        <option value="">SELECT</option>
                        <option value="Chairman">Chairman</option>
                        <option value="Manager">Manager</option>
                        <option value="Assistant Manager">Assistant Manager</option>
                        <option value="Accountant">Accountant</option>
                        <option value="Shipping Exicutive">Shipping Exicutive</option>
                        <option value="Junior Shipping Exicutive">Junior Shipping Exicutive</option>
                        <option value="Representative">Representative</option>
                        <option value="Office Boy">Office Boy</option>
                      </select>
                    </div> -->
                    <div class="form-group-material">
                      <input id="register-password" type="password" name="registerPassword1" required data-msg="Please enter your password" class="input-material" placeholder="Password">
                      <!-- <label for="register-password" class="label-material">Password</label> -->
                    </div>

                    <div class="form-group-material">
                      <input id="register-password" type="password" name="registerPassword2" required data-msg="Please enter your password" class="input-material" placeholder="Confirm Password">
                      <!-- <label for="register-password" class="label-material">Password</label> -->
                    </div>

                    <div class="form-group text-center">
                      <input id="register" type="submit" name="register" value="Register" class="btn btn-primary">
                    </div>
                  </form><small>Already have an account? </small><a href="login.php" class="signup">Login</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
        <p>Design by <a href="https://bootstrapious.com" class="external">Bootstrapious</a></p>
        <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
      </div>
    </div>
<?php include('inc/login-register-header.php'); ?>