<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="dashboard.php?title=Dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <?php if ($_SESSION['role'] == 1) : ?>
            <li class="nav-item">
                <a class="nav-link " href="dashboard.php?title=Account Management">
                    <i class="bi bi-person"></i>
                    <span>Account Management</span>
                </a>
            </li><!-- End Account Management Nav -->

            <li class="nav-item">
                <a class="nav-link " href="dashboard.php?title=View Request">
                    <i class="bi bi-book"></i>
                    <span>View Request</span>
                </a>
            </li><!-- End View Request Nav -->

            <li class="nav-item">
                <a class="nav-link " href="dashboard.php?title=View Manuscript">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span>Manuscript</span>
                </a>
            </li><!-- End Manuscript Nav -->
        <?php endif; ?>

        <?php if ($_SESSION['role'] == 0) : ?>
            <li class="nav-item">
                <a class="nav-link " href="dashboard.php?title=Browse Manuscript">
                    <i class="bi bi-search"></i>
                    <span>Browse Manuscript</span>
                </a>
            </li><!-- End Browse Manuscript Nav -->
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link " href="dashboard.php?title=Upload Manuscript">
                <i class="bi bi-upload"></i>
                <span>Upload</span>
            </a>
        </li><!-- End Upload Nav -->
    </ul>

</aside><!-- End Sidebar-->