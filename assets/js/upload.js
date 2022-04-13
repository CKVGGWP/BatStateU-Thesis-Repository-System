//upload from adminUpload
$("#adminUpload").on("submit", function (e) {
  e.preventDefault();

  var title = $("#title").val();
  var yearPub = $("#yearPub").val();
  var authors = $("#authors").val();
  var department = $("#department").val() ? $("#department").val() : "";
  var program = $("#program").val() ? $("#program").val() : "";
 
  var abstract = $("#abstract").prop("files")[0];
  var journal = $("#journal").prop("files")[0];

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
});
