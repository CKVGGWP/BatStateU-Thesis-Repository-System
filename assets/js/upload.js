//upload from adminUpload
$("#adminUpload").on("submit", function (e) {
  e.preventDefault();

  var title = $("#title").val();
  var yearPub = $("#yearPub").val();
  var authors = $("#authors").val();
  var department = $("#department").length > 0 ? $("#department").val() : "";
  var program = $("#program").length > 0 ? $("#program").val() : "";

  var abstract = $("#abstract").prop("files")[0];
  var journal = $("#journal").prop("files")[0];

  if (title == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter your thesis/capstone title!",
    });
  } else if (yearPub == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter the year of publication!",
    });
  } else if (authors == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter the author(s) of the thesis/capstone!",
    });
  } else if (abstract == undefined) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please upload your abstract!",
    });
  } else if (journal == undefined) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please upload your journal!",
    });
  } else if (abstract != undefined && abstract.type != "application/pdf") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Your abstract must be a pdf file!",
    });
  } else if (journal != undefined && journal.type != "application/pdf") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Your journal must be a pdf file!",
    });
  } else {
    $("#uploadFiles").blur();
    $("#uploadFiles").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...'
    );
    $("#uploadFiles").attr("disabled", true);
    var formData = new FormData();
    formData.append("title", title);
    formData.append("yearPub", yearPub);
    formData.append("authors", authors);
    formData.append("department", department);
    formData.append("program", program);
    formData.append("abstract", abstract);
    formData.append("journal", journal);
    formData.append("uploadAdmin", true);

    $.ajax({
      url: "controllers/uploadController.php",
      type: "POST",
      data: formData,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        $("#uploadFiles").html("UPLOAD");
        $("#uploadFiles").attr("disabled", false);
        console.log(data);
        Swal.fire({
          icon: "success",
          title: "Upload Successful",
          showConfirmButton: false,
          timer: 1500,
        });
      },
      error: function (data) {
        console.log(data);
        Swal.fire({
          icon: "error",
          title: "Upload Failed",
          showConfirmButton: false,
          timer: 1500,
        });
      },
    });
  }
});