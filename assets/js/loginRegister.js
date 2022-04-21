let pathname = window.location.pathname.split("/").pop();

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
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Logging In...'
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
            showConfirmButton: false,
            timer: 1500,
          }).then((result) => {
            window.location.href = "dashboard.php?title=Dashboard";
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
  let program = $("#program").val();
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
    $("#createAccBtn").attr("disabled", true);
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
        program: program,
        password: password,
        register: true,
      },
      success: function (response) {
        $("#createAccBtn").attr("disabled", false);
        $("#createAccBtn").html("Create Account");
        console.log(response);
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
            text: "We've sent you a verification email to your email address! If you did not receive an email confirmation within a few minutes, please check your spam folder",
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
    $("#closePass").attr("disabled", true);
    $("#confirmPass").attr("disabled", true);
    $("#confirmPass").blur();
    $("#confirmPass").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...'
    );
    $.ajax({
      url: "controllers/loginRegisterController.php",
      method: "POST",
      data: {
        email: email,
        forgotPass: true,
      },
      success: function (response) {
        $("#closePass").attr("disabled", false);
        $("#confirmPass").attr("disabled", false);
        $("#confirmPass").html("Confirm");
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
            text: "We've sent you a password reset email to your email address! If you did not receive an email confirmation within a few minutes, please check your spam folder",
          }).then((result) => {
            window.location.href = "index.php";
          });
        }
      },
    });
  }
});

if (getParameterByName("title") == "Verify Account") {
  let tokenKey = getParameterByName("tokenKey");
  let srCode = getParameterByName("srCode");
  $.ajax({
    url: "controllers/loginRegisterController.php",
    method: "POST",
    data: {
      tokenKey: tokenKey,
      srCode: srCode,
      verify: true,
    },
    success: function (response) {
      console.log(response);
      if (response == 1) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Token is Invalid!",
        }).then((result) => {
          window.location.href = "index.php";
        });
      } else if (response == 2) {
        Swal.fire({
          icon: "success",
          title: "Success",
          text: "Account Verified Successfully! You can now login to your account!",
        }).then((result) => {
          window.location.href = "index.php";
        });
      } else if (response == 3) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Something went wrong verifying your account! Please try again later!",
        }).then((result) => {
          window.location.href = "index.php";
        });
      } else if (response == 4) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Invalid SR Code!",
        }).then((result) => {
          window.location.href = "index.php";
        });
      } else if (response == 5) {
        Swal.fire({
          icon: "info",
          title: "Info",
          text: "Account already verified!",
        }).then((result) => {
          window.location.href = "index.php";
        });
      }
    },
  });
}

if (getParameterByName("title") == "Reset Password") {
  let tokenKey = getParameterByName("tokenKey");
  let srCode = getParameterByName("srCode");

  if (tokenKey == "" || srCode == "") {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "You are not allowed to access this page!",
    }).then((result) => {
      window.location.href = "index.php";
    });
  }

  $("#resetPass").on("submit", function (e) {
    e.preventDefault();

    let newPassword = $("#newPassword").val();
    let confirmPassword = $("#confirmPassword").val();

    if (tokenKey == "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Your Token Key is Empty!",
      });
    } else if (srCode == "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Your SR Code is Empty!",
      });
    } else if (newPassword == "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "New Password Field is Empty!",
      });
    } else if (confirmPassword == "") {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Confirm Password Field is Empty!",
      });
    } else if (newPassword != confirmPassword) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "New Password and Confirm Password do not match!",
      });
    } else {
      $("#resetPassword").attr("disabled", true);
      $("#resetPassword").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Resetting...'
      );
      $.ajax({
        url: "controllers/loginRegisterController.php",
        method: "POST",
        data: {
          tokenKey: tokenKey,
          srCode: srCode,
          newPassword: newPassword,
          resetPass: true,
        },
        success: function (response) {
          $("#resetPassword").attr("disabled", false);
          $("#resetPassword").html("Reset");
          console.log(response);
          if (response == 1) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "SR Code does not exists!",
            });
          } else if (response == 2) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Token Key does not exists!",
            });
          } else if (response == 3) {
            Swal.fire({
              icon: "info",
              title: "Info",
              text: "New Password cannot be the same as the old password!",
            });
          } else if (response == 4) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Something went wrong resetting your password! Please try again later!",
            });
          } else if (response == 5) {
            Swal.fire({
              icon: "success",
              title: "Success",
              text: "Password Reset Successfully!",
            }).then((result) => {
              window.location.href = "index.php";
            });
          }
        },
      });
    }
  });
}
