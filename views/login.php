<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="py-4 d-flex justify-content-center">
                            <a href="login.php" class="logo align-items-center text-center w-auto">
                                <img class="" src="./assets/img/BatStateU.png" alt="BatStateU">
                                <div>
                                    <span>BatStateU Online Thesis Repository and Management System</span>
                                </div>

                            </a>
                        </div>
                        <!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-2 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">
                                        Login to Your Account
                                    </h5>
                                </div>

                                <form method="POST" autocomplete="off" class="row g-3 needs-validation" novalidate id="login">
                                    <div class="col-12">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group has-validation">
                                            <input type="email" name="email" class="form-control" id="email" required />
                                            <div class="invalid-feedback">
                                                Please enter your email.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="password" required />
                                        <div class="invalid-feedback">
                                            Please enter your password.
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <button type="submit" class="btn btn-danger rounded-pill w-100">
                                            Login
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPassword">
                                            <small>
                                                <p class="text-center">Forgot your Password?</p>
                                            </small>
                                        </a>
                                        <div class="modal fade" id="forgotPassword" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Forgot Password</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-dark">Enter your registered email
                                                            address
                                                            below.</p>
                                                        <form>
                                                            <div class="form-group">
                                                                <input type="email" class="form-control" id="email" placeholder="Email Address">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <p class="small mb-0">
                                            Don't have account?
                                            <a href="register.php">Create an account</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<!-- End #main -->a