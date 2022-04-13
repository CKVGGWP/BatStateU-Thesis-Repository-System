//upload from adminUpload
$("#adminUpload").on("submit", function (e) {
  e.preventDefault();
  $.ajax({
    url: "controllers/uploadController.php",
    type: "POST",
    data: //new FormData(this),
    { 
      formData : new FormData(this),
      uploadAdmin: 1,
    },
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
