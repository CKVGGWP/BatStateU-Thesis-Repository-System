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
            <div class="col-lg-12">
                <div class="row">

                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Pending Request/s</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-hourglass"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>3</h6>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-8 col-xl-12">

                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Manuscript Status</h5>

                                <table class="table table-borderless table-sm table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" class="text-primary" title="Sample title here">Sample title here</a></td>
                                            <td>Condoriano</td>
                                            <td><span class="badge bg-warning">PENDING</span></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>


                    </div>

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Search Manuscript</h5>

                                <table class="table table-borderless table-sm table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Date Uploaded</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" class="text-primary" title="Sample title here">Sample title here1</a></td>
                                            <td>God Ussop</td>
                                            <td>Jan 3, 2020</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->
                </div>
            </div><!-- End Left side columns -->
    </section>

</main><!-- End #main -->