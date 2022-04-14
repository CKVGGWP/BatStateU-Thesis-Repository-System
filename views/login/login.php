<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="py-4 d-flex justify-content-center">
                            <a href="index.php" class="logo align-items-center text-center w-auto">
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
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="email" placeholder="Email">
                                            <label for="email">Email</label>
                                            <div class="invalid-feedback">
                                                Please enter your email.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password" placeholder="Password">
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter your password.
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <button type="submit" class="btn btn-danger rounded-pill w-100" id="loginBtn">
                                            Login
                                        </button>
                                    </div>
                                </form>
                                <div class="col-12">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPassword">
                                        <small>
                                            <p class="text-center">Forgot your Password?</p>
                                        </small>
                                    </a>
                                    <div class="modal fade" id="forgotPassword" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                                        <form method="POST" id="forgotPassForm">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Forgot Password</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-dark">Enter your registered email address below.</p>
                                                        <div class="form-group">
                                                            <input type="email" class="form-control" id="emailForgot" placeholder="Email Address">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" id="closePass">Close</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill" id="confirmPass">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <p class="small mb-0">
                                        Don't have account?
                                        <a href="index.php?title=Create Account">Create an account</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<!-- End #main -->