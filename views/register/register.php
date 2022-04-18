<?php include('controllers/informationController.php'); ?>
<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-1">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8 d-flex flex-column align-items-center justify-content-center">

                        <div class="py-4 d-flex justify-content-center">
                            <a href="index.php" class="logo align-items-center text-center w-auto">
                                <img class="" src="./assets/img/BatStateU.png" alt="BatStateU">
                                <div>
                                    <span>BatStateU Online Thesis Repository and Management System</span>
                                </div>

                            </a>
                        </div>

                        <div class="card mb-3 register">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                </div>

                                <form method="POST" autocomplete="off" class="needs-validation" novalidate id="register">
                                    <div class="row g-2">
                                        <div class="col-md-4 col-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="firstName" placeholder="First Name" required>
                                                <label for="firstName">First Name</label>
                                            </div>
                                            <div class="invalid-feedback">Please enter your first name!</div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="middleName" placeholder="Middle Name">
                                                <label for="middleName">Middle Name</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4  col-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="lastName" placeholder="Last Name" required>
                                                <label for="lastName">Last Name</label>
                                            </div>
                                            <div class="invalid-feedback">Please enter your last name!</div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6 col-12 mt-2">
                                            <div class="form-floating">
                                                <select class="form-control" name="campus" id="campus" required>
                                                    <option value selected disabled>Please select a Campus</option>
                                                    <?php foreach ($campuses as $key => $row) : ?>
                                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['campusName']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <label for="campus" class="form-label">Campus</label>
                                            </div>
                                            <div class="invalid-feedback">Please enter a Campus!</div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-2">
                                            <div class="form-floating">
                                                <select class="form-control" name="department" id="department" required>
                                                    <option value selected disabled>Please select a Department</option>
                                                </select>
                                                <label for="department" class="form-label">Department</label>
                                            </div>
                                            <div class="invalid-feedback">Please enter a Department!</div>
                                        </div>

                                    </div>

                                    <div class="col-md-12 col-12 mt-2 d-none" id="programDiv">

                                        <div class="form-floating">
                                            <select class="form-control" name="program" id="program">
                                                <option value selected disabled>Please select a Program</option>
                                            </select>
                                            <label for="program" class="form-label">Program</label>
                                        </div>
                                        <div class="invalid-feedback">Please enter a Program!</div>

                                    </div>

                                    <div class="col-md-12 col-12 mt-2">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="email" placeholder="Email" pattern=".+@g\.batstate-u\.edu\.ph" required>
                                            <label for="email">Email</label>
                                            <div class="invalid-feedback">Please enter your valid Email address!</div>
                                        </div>

                                        <div class="row g-2 mt-2">
                                            <div class="col-md-6  col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="srCode" placeholder="SR Code" required>
                                                    <label for="srCode">SR Code</label>
                                                </div>
                                                <div class="invalid-feedback">Please enter your SR Code!</div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="password" placeholder="Password" required>
                                                    <label for="password">Password</label>
                                                </div>
                                                <div class="invalid-feedback">Please enter your password!</div>
                                            </div>
                                            <div class="d-flex justify-content-center mt-4">
                                                <button class="btn btn-danger rounded-pill w-100" type="submit" id="createAccBtn">Create
                                                    Account</button>
                                            </div>
                                            <div class="col-12 mt-3 text-center">
                                                <p class="small mb-0">Already have an account? <a href="index.php">Login</a></p>
                                            </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main><!-- End #main -->