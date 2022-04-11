let url = window.location.href;

$("#login").on("submit", function (e) {
  e.preventDefault();
  let email = $("#email").val();
  let password = $("#password").val();

  if (email == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Email Field is Empty!",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (password == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Password Field is Empty!",
    }).then((result) => {
      password.focus();
      password.addClass("is-invalid");
    });
  } else {
    $("#loginBtn").blur();
    $("#loginBtn").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Login'
    );
    $.ajax({
      url: "controllers/loginRegisterController.php",
      method: "POST",
      data: {
        email: email,
        password: password,
        login: true,
      },
      success: function (response) {
        $("#loginBtn").html("Login");
        if (response == 1) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Account does not exist!",
          });
        } else if (response == 2) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Account is not yet verified!",
          });
        } else if (response == 3) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Email/Password is Incorrect!",
          });
        } else {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: "Login Successful!",
          }).then((result) => {
            window.location.href = "index.php";
          });
        }
      },
    });
  }
});

$("#register").on("submit", function (e) {
  e.preventDefault();

  let srCode = $("#srCode").val();
  let email = $("#email").val();
  let firstName = $("#firstName").val();
  let middleName = $("#middleName").val();
  let lastName = $("#lastName").val();
  let department = $("#department").val();
  let campus = $("#campus").val();
  let password = $("#password").val();

  if (srCode == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "SR Code Field is Empty!",
    }).then((result) => {
      srCode.focus();
      srCode.addClass("is-invalid");
    });
  } else if (email == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Email Field is Empty!",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (emailValidation(email) == false) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Invalid Email!",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (firstName == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "First Name Field is Empty!",
    }).then((result) => {
      firstName.focus();
      firstName.addClass("is-invalid");
    });
  } else if (lastName == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Last Name Field is Empty!",
    }).then((result) => {
      lastName.focus();
      lastName.addClass("is-invalid");
    });
  } else if (department == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Department Field is Empty!",
    }).then((result) => {
      department.focus();
      department.addClass("is-invalid");
    });
  } else if (campus == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Campus Field is Empty!",
    }).then((result) => {
      campus.focus();
      campus.addClass("is-invalid");
    });
  } else if (password == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Password Field is Empty!",
    }).then((result) => {
      password.focus();
      password.addClass("is-invalid");
    });
  } else {
    $("#createAccBtn").blur();
    $("#createAccBtn").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...'
    );
    $.ajax({
      url: "controllers/loginRegisterController.php",
      method: "POST",
      data: {
        srCode: srCode,
        email: email,
        firstName: firstName,
        middleName: middleName,
        lastName: lastName,
        department: department,
        campus: campus,
        password: password,
        register: true,
      },
      success: function (response) {
        $("#createAccBtn").html("Create Account");
        if (response == 1) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Account already exists!",
          });
        } else if (response == 2) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Please enter a valid SR Code!",
          });
        } else if (response == 3) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Something went wrong with the server!",
          });
        } else if (response == 4) {
          Swal.fire({
            icon: "success",
            title: "Registration Successful!",
            text: "We've sent you a verification email to your email address!",
          }).then((result) => {
            window.location.href = "index.php";
          });
        } else if (response == 5) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "SR Code is already taken!",
          });
        }
      },
    });
  }
});

$("#forgotPassForm").on("submit", function (e) {
  e.preventDefault();

  let email = $("#emailForgot").val();

  if (email == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Email Field is Empty!",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else if (emailValidation(email) == false) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Account does not exists!",
    }).then((result) => {
      email.focus();
      email.addClass("is-invalid");
    });
  } else {
    $.ajax({
      url: "controllers/loginRegisterController.php",
      method: "POST",
      data: {
        email: email,
        forgotPass: true,
      },
      success: function (response) {
        console.log(response);
        if (response == 1) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Account does not exist!",
          });
        } else if (response == 2) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Something went wrong with the server! Please try again later!",
          });
        } else {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: "We've sent you a password reset email to your email address!",
          }).then((result) => {
            window.location.href = "index.php";
          });
        }
      },
    });
  }
});

if (url == "verify.php") {
  $.ajax({
    url: "controllers/loginRegisterController.php",
    method: "POST",
    data: {
      tokenKey: getParameterByName("tokenKey"),
      srCode: getParameterByName("srCode"),
      verify: true,
    },
    success: function (response) {
      if (response == 1) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Token is Invalid!",
        });
      } else if (response == 2) {
        Swal.fire({
          icon: "success",
          title: "Success",
          text: "Account Verified Successfully!",
        }).then((result) => {
          window.location.href = "index.php";
        });
      } else if (response == 3) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Something went wrong verifying your account! Please try again later!",
        });
      }
    },
  });
}
