<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <?php include('breadcrumbs.php'); ?>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Pending Request Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Pending Request/s <a href="dashboard.php?title=View Request"><small> View Details</small></a></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-hourglass"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="requestManusriptAdminDash"></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Pending Approval Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Pending Approval <a href="dashboard.php?title=Pending Manuscripts"><small> View Details</small></a></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-hourglass-top"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="pendingManuscriptAdminDash"></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Pending Approval Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">Website Traffic</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-pie-chart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>1244</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Reports -->

                    <!-- Recently Added Manuscripts Card -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Recently Added Manuscript/s</h5>

                                <table class="table table table-borderless table-sm table-hover text-center dt-responsive">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Date Added</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recently Added Manuscripts Card -->
                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Total User Card -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Total Users <a href="dashboard.php?title=Account Management"><small> View Details</small></a></h5>

                        <div id="totalUsersPieGraph"></div>
                    </div>
                </div><!-- End Total Users Traffic -->

                <!-- News & Updates Traffic -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>
                </div><!-- End News & Updates -->

            </div><!-- End Right side columns -->

        </div>
    </section>

</main><!-- End #main -->