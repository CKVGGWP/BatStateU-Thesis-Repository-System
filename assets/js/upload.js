$(".btnNext").click(function () {
  $(".nav-tabs-bordered > .nav-item")
    .next("li")
    .find("button")
    .trigger("click");
});

//upload from adminUpload
$("#adminUpload").on("submit", function (e) {
  e.preventDefault();

  var title = $("#title").val();
  var yearPub = $("#yearPub").val();
  var authors;
  var department = $("#department").length > 0 ? $("#department").val() : "";
  var program = $("#program").length > 0 ? $("#program").val() : "";
  var tags = $("#tags").val();

  var abstract = $("#abstract").prop("files")[0];
  var journal = $("#journal").prop("files")[0];

  if ($("#registeredAuthors").length > 0) {
    authors = $("#registeredAuthors").val();
  } else {
    authors = $("#textAreaAuthors").val();
  }

  if (authors.includes(", ")) {
    authors = authors.replace(", ", ",");
  }

  if (authors.includes(";") || authors.includes("; ")) {
    authors = authors.replace("; ", ",");
    authors = authors.replace(";", ",");
  }

  if (authors.includes(" and ")) {
    authors = authors.replace(" and ", ",");
  }

  if (authors.includes("@") || authors.includes("@ ")) {
    authors = authors.replace("@ ", ",");
    authors = authors.replace("@", ",");
  }

  console.log(authors);

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
  } else if (yearPub > new Date().getFullYear()) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter a valid year of publication! You cannot input a future year.",
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
    Swal.fire({
      title: "Are you sure that you want to upload " + title + "?",
      text: "You are about to upload your thesis/capstone! Please make sure all the information provided is correct!",
      icon: "question",
      showCancelButton: true,
    }).then((result) => {
      if (result.isConfirmed) {
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
        formData.append("tags", tags);
        formData.append("abstract", abstract);
        formData.append("journal", journal);
        formData.append("uploadAdmin", true);

        console.log(formData);

        $.ajax({
          url: "controllers/uploadController.php",
          type: "POST",
          data: formData,
          // dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
            console.log(data);

            $("#uploadFiles").html("UPLOAD");
            $("#uploadFiles").attr("disabled", false);

            if (data == 1) {
              Swal.fire({
                icon: "success",
                title: title + " has been uploaded successfully!",
                showConfirmButton: false,
                timer: 1500,
              }).then((result) => {
                location.reload();
              });
            } else if (data == 3) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "You have an author that has an existing group!",
              });
            } else if (data == 0) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "There was an error uploading your thesis/capstone!",
              });
            } else if (data == 4) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "You cannot add an admin to the list of authors!",
              });
            }
          },
        });
      }
    });
  }
});

$("#abstract").on("change", function () {
  $("#abstractView").attr("hidden", false);
  let src = window.URL.createObjectURL(this.files[0]);
  $("#abstractPreview").attr("src", src);
});

$("#journal").on("change", function () {
  $("#journalView").attr("hidden", false);
  let src = window.URL.createObjectURL(this.files[0]);
  $("#journalPreview").attr("src", src);
});

$(document).ready(function () {
  $.ajax({
    url: "controllers/manuscriptController.php",
    type: "POST",
    data: {
      getPendingByGroup: 1,
    },
    dataType: "json",
    success: function (data) {
      if (data.pending) {
        $("#uploadFiles").attr("disabled", true);
        $("#uploadFiles").html(data.message);
      } else if (data.approve) {
        $("#uploadFiles").attr("disabled", true);
        $("#uploadFiles").html(data.message);
      }
    },
  });
});
