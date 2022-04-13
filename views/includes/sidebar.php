<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="dashboard.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <?php if ($_SESSION['role'] == 1) : ?>
            <li class="nav-item">
                <a class="nav-link " href="accountManagementAdmin.php">
                    <i class="bi bi-person"></i>
                    <span>Account Management</span>
                </a>
            </li><!-- End Account Management Nav -->

            <li class="nav-item">
                <a class="nav-link " href="requestAdmin.php">
                    <i class="bi bi-book"></i>
                    <span>View Request</span>
                </a>
            </li><!-- End View Request Nav -->
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link " href="viewAdmin.php">
                <i class="bi bi-file-earmark-pdf"></i>
                <span>Manuscript</span>
            </a>
        </li><!-- End Manuscript Nav -->

        <li class="nav-item">
            <a class="nav-link " href="upload.php">
                <i class="bi bi-upload"></i>
                <span>Upload</span>
            </a>
        </li><!-- End Upload Nav -->
    </ul>

</aside><!-- End Sidebar-->