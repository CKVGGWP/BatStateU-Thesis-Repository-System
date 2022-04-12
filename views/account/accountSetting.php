<main id="main" class="main">

  <div class="pagetitle">
    <h1>Account Settings</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Account Settings</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">

        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="assets/img/default.png" alt="Profile" class="rounded-circle">
            <h2><?php echo $name; ?></h2>
            <h3><?php echo $title; ?></h3>
          </div>
        </div>

      </div>

      <div class="col-xl-8">

        <div class="card">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
              </li>

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                <!-- Profile Edit Form -->
                <form>
                  <div class="row mb-3">
                    <label for="firstName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="firstName" type="text" class="form-control" id="firstName" value="<?php echo $firstName; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="middleName" class="col-md-4 col-lg-3 col-form-label">Middle Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="middleName" type="text" class="form-control" id="middleName" value="<?php echo $middleName; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="lastName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="lastName" type="text" class="form-control" id="lastName" value="<?php echo $lastName; ?>">
                    </div>
                  </div>

                  <?php if ($title == 'Librarian') : ?>

                    <div class="row mb-3">
                      <label for="campus" class="col-md-4 col-lg-3 col-form-label">Campus</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="campus" id="userCampus" class="form-select">
                          <option value="<?php echo $campID; ?>" selected><?php echo $campus; ?></option>
                          <?php foreach ($campuses as $key => $row) : ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['campusName']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="department" class="col-md-4 col-lg-3 col-form-label">Department</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="department" id="userDepartment" class="form-select">
                          <option value="<?php echo $deptID; ?>"><?php echo $department; ?></option>
                        </select>
                      </div>
                    </div>

                  <?php else : ?>

                    <div class="row mb-3">
                      <label for="campus" class="col-md-4 col-lg-3 col-form-label">Campus</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="campus" type="text" class="form-control" id="campus" value="<?php echo $campus; ?>" readonly>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="department" class="col-md-4 col-lg-3 col-form-label">Department</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="department" type="text" class="form-control" id="department" value="<?php echo $department; ?>" readonly>
                      </div>
                    </div>

                  <?php endif; ?>

                  <div class="row mb-3">
                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="text" class="form-control" id="email" value="<?php echo $email; ?>">
                    </div>
                  </div>

                  <?php if ($title == 'Librarian') : ?>

                    <div class="row mb-3">
                      <label for="srCode" class="col-md-4 col-lg-3 col-form-label">Employee ID</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="srCode" type="text" class="form-control" id="srCode" value="<?php echo $srCode; ?>" readonly>
                      </div>
                    </div>

                  <?php else : ?>

                    <div class="row mb-3">
                      <label for="srCode" class="col-md-4 col-lg-3 col-form-label">SR Code</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="srCode" type="text" class="form-control" id="srCode" value="" readonly>
                      </div>
                    </div>

                  <?php endif; ?>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form><!-- End Profile Edit Form -->

              </div>

              <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form>

                  <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="password" type="password" class="form-control" id="currentPassword">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="newpassword" type="password" class="form-control" id="newPassword">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                  </div>
                </form><!-- End Change Password Form -->

              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>
  </section>

</main>
<!-- End #main -->