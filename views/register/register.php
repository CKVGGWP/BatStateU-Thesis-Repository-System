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
                                            <label for="firstName" class="form-label">First Name</label>
                                            <input type="text" name="firstName" class="form-control" id="firstName" required>
                                            <div class="invalid-feedback">Please, enter your first name!</div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <label for="middleName" class="form-label">Middle Name</label>
                                            <input type="text" name="middleName" class="form-control" id="middleName">
                                        </div>

                                        <div class="col-md-4  col-12">
                                            <label for="lastName" class="form-label">Last Name</label>
                                            <input type="text" name="lastName" class="form-control" id="lastName" required>
                                            <div class="invalid-feedback">Please, enter your last name!</div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6 col-12 mt-2">
                                            <label for="campus" class="form-label">Campus</label>
                                            <select class="form-control" name="campus" id="campus" required>
                                                <option value selected disabled>- Campus -</option>
                                                <?php foreach ($campuses as $key => $row) : ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['campusName']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback">Please enter a Campus!</div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-2">
                                            <label for="department" class="form-label">Department</label>
                                            <select class="form-control" name="department" id="department" required>
                                                <option value selected disabled>- Department -</option>
                                            </select>
                                            <div class="invalid-feedback">Please enter a Department!</div>
                                        </div>

                                    </div>
                                    <div class="col-md-12 col-12 mt-2">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="email" pattern=".+@g\.batstate-u\.edu\.ph" required>
                                        <div class="invalid-feedback">Please enter your valid Email address!</div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6 col-12">
                                            <label for="srCode" class="form-label">SR Code</label>
                                            <div class="input has-validation">
                                                <input type="text" name="srCode" class="form-control" id="srCode" required>
                                                <div class="invalid-feedback">Please input your SR Code!</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="password" required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>
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