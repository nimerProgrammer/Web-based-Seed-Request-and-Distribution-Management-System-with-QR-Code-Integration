$(document).ready(function () {
  function showLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
      loader.style.display = "flex";
    }
  }

  function hideLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
      loader.style.display = "none";
    }
  }

  let loginAttempts = 0;
  let lockoutTimer = null;
  const lockoutSeconds = 180;

  $("#login_username").on("input", function () {
    $("#login_username").removeClass("is-invalid");
    $("#username_error").text("").hide();
  });

  $("#login_password").on("input", function () {
    $("#login_password").removeClass("is-invalid");
    $("#password_error").text("").hide();
  });

  $("#loginForm").submit(function (e) {
    e.preventDefault();

    const username = $("#login_username").val().trim();
    const password = $("#login_password").val().trim();
    const $submitBtn = $("#login-btn");

    // if ($submitBtn.prop("disabled") && $submitBtn.text().includes("Locked"))
    //   return;

    // // Reset validation
    // $("#login_username").removeClass("is-invalid");
    // $("#login_username").next(".invalid-feedback").text("");
    // $("#login_password").removeClass("is-invalid");
    // $("#login_password").next(".invalid-feedback").text("");

    $submitBtn.text("Please wait...").attr("disabled", true);
    showLoader();

    var formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    $.ajax({
      url: "../public/login/check_credentials", // Your backend route
      type: "POST",
      data: formData,
      dataType: "JSON",
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          window.location.href = response.redirect_url || "public/home";
        } else {
          // loginAttempts++;

          if (response.error === "Username not found") {
            $("#login_username").addClass("is-invalid");
            $("#username_error").text("Username not found.").show();
            $submitBtn.text("Log in").attr("disabled", false);
            hideLoader();
          } else if (response.error === "Wrong password") {
            $("#login_password").addClass("is-invalid");
            $("#password_error").text("Wrong password.").show();
            $submitBtn.text("Log in").attr("disabled", false);
            hideLoader();
          }

          // if (loginAttempts >= 5) {
          //   let remaining = lockoutSeconds;
          //   $submitBtn.text("Locked (3:00)");

          //   lockoutTimer = setInterval(function () {
          //     remaining--;
          //     const mins = Math.floor(remaining / 60);
          //     const secs = String(remaining % 60).padStart(2, "0");
          //     $submitBtn.text(`Locked (${mins}:${secs})`);

          //     if (remaining <= 0) {
          //       clearInterval(lockoutTimer);
          //       $submitBtn.attr("disabled", false).text("Log in");
          //       loginAttempts = 0;
          //     }
          //   }, 1000);
          // } else {
          //   $submitBtn.text("Log in").attr("disabled", false);
          // }
        }
      },
      error: function (_, _, error) {
        console.error("AJAX Error:", error);
        alert("An error occurred. Please try again.");
        $submitBtn.text("Log in").attr("disabled", false);
        hideLoader();
      },
    });
  });

  $("#forgotPasswordForm").on("submit", function (e) {
    e.preventDefault();
    const email = $("#forgot_email").val();

    $("#forgotSpinner").removeClass("d-none");
    $("#forgotPasswordBtn").attr("disabled", true);
    // Example AJAX (replace URL with your actual endpoint)
    $.ajax({
      url: "forgotPassword", // Backend route here
      method: "POST",
      data: { email: email },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#forgot_message").html(
            `<div class="alert alert-success">Password reset link sent to your email!</div>`
          );
          $("#forgot_email").removeClass("is-invalid");
          $("#forgot_email").val("");
        } else {
          $("#forgot_message").html(
            `<div class="alert alert-danger">Email not found or not registered.</div>`
          );
          $("#forgot_email").addClass("is-invalid");
        }

        $("#forgotSpinner").addClass("d-none");
        $("#forgotPasswordBtn").attr("disabled", false);
      },
      error: function () {
        $("#forgot_message").html(
          `<div class="alert alert-danger p-2 mb-2">Email not found or error occurred.</div>`
        );
        $("#forgotSpinner").addClass("d-none");
        $("#forgotPasswordBtn").attr("disabled", false);
      },
    });
  });
});
