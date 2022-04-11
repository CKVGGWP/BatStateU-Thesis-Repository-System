$("#campus").on("change", function () {
  let campus = $(this).val();

  $.ajax({
    url: "controllers/newInformationController.php",
    method: "POST",
    data: {
      campus: campus,
      getCampus: true,
    },
    dataType: "json",
    success: function (data) {
      $("#department").html(data);
    },
  });
});
