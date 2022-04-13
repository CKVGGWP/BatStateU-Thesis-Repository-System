<main id="main" class="main">

  <div class="pagetitle">
    <h1>Manuscript Upload</h1>
    <nav>
      <?php include('views/dashboard/breadcrumbs.php'); ?>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Upload</h5>

            <form class="row g-3" id="adminUpload" enctype="multipart/form-data">
              <div class="col-md-12">
                <div class="form-floating">
                  <textarea type="text" class="form-control" id="title" placeholder="Title"></textarea>
                  <label for="title">Title</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" id="yearPub" placeholder="Year of Publication">
                  <label for="yearPub">Year of Publication</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" id="authors" placeholder="Authors">
                  <label for="authors">Authors</label>
                </div>
              </div>
              <?php if ($role == 'Admin') : ?>
                <div class="col-md-6">
                  <div class="form-floating">
                    <select class="form-select" id="department" placeholder="Department">
                      <?php foreach ($campuses as $key => $row) : ?>
                        <option value="<?= $row['id'] ?>"><?= $row['campusName'] ?></option>
                      <?php endforeach; ?>
                    </select>
                    <label for="yearPub">Department</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="program" placeholder="Program">
                    <label for="program">Program</label>
                  </div>
                </div>
              <?php endif; ?>
              <div class="col-md-6">
                <label for="inputNumber" class="col-sm-2 col-form-label">Abstract</label>
                <div class="col-md-12">
                  <input class="form-control" type="file" id="abstract" accept="application/pdf">
                </div>
              </div>
              <div class="col-md-6">
                <label for="inputNumber" class="col-sm-2 col-form-label">Journal</label>
                <div class="col-md-12">
                  <input class="form-control" type="file" id="journal" accept="application/pdf">
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-danger w-25 mt-3 rounded-pill" id="uploadFiles">UPLOAD</button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </section>

</main><!-- End #main -->