$(document).ready(function () {
  preventDevTools(false);
  // preventMobileAccess();

  function preventDevTools(enable) {
    if (!enable) return;

    document.addEventListener("contextmenu", (e) => {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Right click is disabled for security reasons.",
      });

      e.preventDefault();
    });

    document.addEventListener("keydown", (e) => {
      if (
        (e.ctrlKey && e.shiftKey && (e.key === "I" || e.key === "J")) ||
        (e.ctrlKey && (e.key === "u" || e.key === "s" || e.key === "p")) ||
        e.key === "F12"
      ) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "DevTools is disabled for security reasons.",
        });

        e.preventDefault();
      }
    });

    setInterval(() => {
      const devtools =
        window.outerWidth - window.innerWidth > 160 ||
        window.outerHeight - window.innerHeight > 160;

      if (devtools) {
        console.clear();

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "DevTools is disabled for security reasons.",
        });
      }
    }, 1000);
  }

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

  // Sidebar link auto-show loader
  document.querySelectorAll(".top-loader").forEach((link) => {
    link.addEventListener("click", function (e) {
      if (
        !e.ctrlKey &&
        !e.metaKey &&
        !e.shiftKey &&
        this.getAttribute("target") !== "_blank"
      ) {
        showLoader();
      }
    });
  });

  // Auto-hide loader when page fully loads
  window.addEventListener("load", hideLoader);

  $("#publicRequestSeedLink").on("click", function (e) {
    e.preventDefault();

    const isLoggedIn = $(this).data("is-logged-in") === "1";
    const requestUrl = $(this).data("request-url");
    const loginUrl = $(this).data("login-url");
    const signupUrl = $(this).data("signup-url");

    hideLoader(); // Hide loader before showing SweetAlert
    if (isLoggedIn) {
      // ✅ User is logged in — go directly to seed request page
      showLoader();
      window.location.href = requestUrl;
    } else {
      // ❌ Not logged in — show SweetAlert with Login + Signup + Cancel
      Swal.fire({
        icon: "warning",
        title: "Login Required",
        html: `
                <p>You must log in before requesting seeds.</p>
                <div class="d-flex justify-content-center mt-3">
                    <button id="swal-login" class="btn btn-sm btn-primary me-2">Login</button>
                    <button id="swal-signup" class="btn btn-sm btn-success me-2">Sign Up</button>
                    <button id="swal-cancel" class="btn btn-sm btn-secondary">Cancel</button>
                </div>
            `,
        showConfirmButton: false,
        showCancelButton: false,
        buttonsStyling: false,
        didOpen: () => {
          $("#swal-login").on("click", () => {
            showLoader();
            window.location.href = loginUrl;
          });
          $("#swal-signup").on("click", () => {
            showLoader();
            window.location.href = signupUrl;
          });
          $("#swal-cancel").on("click", () => {
            Swal.close();
          });
        },
      });
    }
  });

  $("#testSweetAlert").on("click", function (e) {
    e.preventDefault();
    Swal.fire({
      icon: "info",
      title: "Test Successful",
      text: "SweetAlert is working!",
      confirmButtonText: "OK",
    });
  });

  $("#last_name").on("input", function () {
    checkFormValidity();
  });

  $("#first_name").on("input", function () {
    checkFormValidity();
  });

  $("#middle_name").on("input", function () {
    checkFormValidity();
  });

  $("#suffix").on("input", function () {
    checkFormValidity();
  });

  $("#gender").on("input", function () {
    checkFormValidity();
  });

  $("#barangay").on("input", function () {
    checkFormValidity();
  });

  $("#farm_area").on("input", function () {
    checkFormValidity();
  });

  $("#land_owner").on("input", function () {
    checkFormValidity();
  });

  // $("#username").on("input", function () {
  //   checkFormValidity();
  // });

  $("#birthdate").on("input", function () {
    const value = $(this).val();
    const input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    const birthdate = new Date(value + "T00:00:00+08:00"); // treat input as PH time
    const nowPH = new Date(
      new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" })
    );

    let age = nowPH.getFullYear() - birthdate.getFullYear();
    let hasHadBirthday =
      nowPH.getMonth() > birthdate.getMonth() ||
      (nowPH.getMonth() === birthdate.getMonth() &&
        nowPH.getDate() >= birthdate.getDate());

    let isAtLeast18 = age > 18 || (age === 18 && hasHadBirthday);

    if (!isAtLeast18) {
      input.addClass("is-invalid");

      if (!input.next(".invalid-feedback").length) {
        input.after('<div class="invalid-feedback"></div>');
      }

      input
        .next(".invalid-feedback")
        .text("You must be at least 18 years old to sign up.")
        .show();
    } else {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
    }

    checkFormValidity();
  });

  $("#rsbsa_no").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) return; // Skip empty input

    $.post(
      BASE_URL + "public/signUp/checker",
      {
        table: "client_info",
        field: "rsbsa_ref_no",
        value: value,
      },
      function (res) {
        if (res.exists) {
          input.addClass("is-invalid");
          input
            .next(".invalid-feedback")
            .text("This RSBSA number is already in use.")
            .removeClass("d-none");
        } else {
          input.removeClass("is-invalid");
          input.next(".invalid-feedback").addClass("d-none");
        }
      },
      "json"
    );

    checkFormValidity();
  });

  $("#contact_no").on("keypress", function (e) {
    const char = String.fromCharCode(e.which);
    const value = $(this).val();

    // Allow digits
    if (/\d/.test(char)) return;

    // Allow "+" only at the start and only once
    if (char === "+" && this.selectionStart === 0 && !value.includes("+"))
      return;

    e.preventDefault(); // Block everything else
  });

  // Input length limiter and validation
  $("#contact_no").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) return;

    // Slice value based on prefix
    if (value.startsWith("+639")) {
      value = value.slice(0, 13);
    } else if (value.startsWith("09")) {
      value = value.slice(0, 11);
    } else {
      value = value.slice(0, 13);
    }

    $(this).val(value);

    // Validate pattern
    let isValid = false;
    if (/^09\d{9}$/.test(value)) {
      isValid = true;
    } else if (/^\+639\d{9}$/.test(value)) {
      isValid = true;
    } else {
      isValid = false;
    }

    if (!isValid) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Must start with 09 (11 digits) or +639 (13 characters).");
    } else {
      $.post(
        BASE_URL + "public/signUp/checker",
        {
          table: "users",
          field: "contact_no",
          value: value,
        },
        function (res) {
          if (res.exists) {
            input.addClass("is-invalid");
            input
              .next(".invalid-feedback")
              .text("This contact number is already in use.");
          } else {
            input.removeClass("is-invalid");
          }
        },
        "json"
      );
    }

    checkFormValidity();
  });

  $("#email").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    // Allow only specific domains
    let allowedDomains = ["@gmail.com", "@email.com", "@yahoo.com"];
    let isValidDomain = allowedDomains.some((domain) => value.endsWith(domain));

    if (!isValidDomain) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Email must end with @gmail.com, @email.com, or @yahoo.com.")
        .show();
      return;
    }

    // Proceed to AJAX duplicate check
    $.post(
      BASE_URL + "public/signUp/checker",
      {
        table: "users",
        field: "email",
        value: value,
      },
      function (res) {
        if (res.exists) {
          input.addClass("is-invalid");
          input
            .next(".invalid-feedback")
            .text("This email address is already in use.")
            .show();
        } else {
          input.removeClass("is-invalid");
          input.next(".invalid-feedback").hide();
        }
      },
      "json"
    );

    checkFormValidity();
  });

  $("#username").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    // Check for minimum length
    if (value.length <= 7) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Username must be at least 8 characters long.")
        .show();
      checkFormValidity();
      return;
    }

    // Disallow the literal word "username"
    else if (value.toLowerCase() === "username") {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Username cannot be 'username'.")
        .show();
      checkFormValidity();
      return;
    }

    // AJAX duplicate check
    $.post(
      BASE_URL + "public/signUp/checker",
      {
        table: "users", // Fixed typo here
        field: "username",
        value: value,
      },
      function (res) {
        if (res.exists) {
          input.addClass("is-invalid");
          input
            .next(".invalid-feedback")
            .text("This username is already in use.")
            .show();
        } else {
          input.removeClass("is-invalid");
          input.next(".invalid-feedback").hide();
          checkFormValidity();
        }
      },
      "json"
    );

    checkFormValidity();
  });

  $("#password").on("input", function () {
    let value = $(this).val().trim();
    let input = $(this);
    let desc = input.closest(".col-md-6").find(".description");

    // Rule checks
    let hasUppercase = /[A-Z]/.test(value);
    let hasNumber = /[0-9]/.test(value);
    let hasSymbol = /[^A-Za-z0-9]/.test(value);
    let isLongEnough = value.length >= 8;

    // Final HTML message
    let html = `
      <div><strong>Password must be contains the following:</strong></div>
      <ul class="list-unstyled mb-0">
        <li class="d-flex align-items-center">
          <i class="bi me-2 ${
            hasSymbol
              ? "bi-check-circle-fill text-success"
              : "bi-x-circle-fill text-danger"
          }"></i>
          At least one special symbol <small class="text-muted">(e.g. !@#$)</small>
        </li>
        <li class="d-flex align-items-center">
          <i class="bi me-2 ${
            hasUppercase
              ? "bi-check-circle-fill text-success"
              : "bi-x-circle-fill text-danger"
          }"></i>
          At least one uppercase letter <small class="text-muted">(e.g. A-Z)</small>
        </li>
        <li class="d-flex align-items-center">
          <i class="bi me-2 ${
            hasNumber
              ? "bi-check-circle-fill text-success"
              : "bi-x-circle-fill text-danger"
          }"></i>
          At least one number <small class="text-muted">(e.g. 0–9)</small>
        </li>
        <li class="d-flex align-items-center"> 
          <i class="bi me-2 ${
            isLongEnough
              ? "bi-check-circle-fill text-success"
              : "bi-x-circle-fill text-danger"
          }"></i>
          At least 8 characters
        </li>
      </ul>
    `;

    desc.html(html);

    // Input border color
    if (hasSymbol && hasUppercase && hasNumber && isLongEnough) {
      input.removeClass("is-invalid");
    } else {
      input.addClass("is-invalid");
    }

    checkFormValidity();
  });

  function checkFormValidity() {
    const form = $("form");

    // Check if all required fields (input + select) are filled
    const requiredFieldsFilled = form
      .find("input[required], select[required]")
      .toArray()
      .every((field) => {
        return $(field).val().trim() !== "";
      });

    // Get target fields for custom validation
    const rsbsa = $("#rsbsa_no");
    const contact = $("#contact_no");
    const email = $("#email");
    const username = $("#username");
    const password = $("#password");

    // Field-specific validation
    const isRSBSAValid =
      !rsbsa.hasClass("is-invalid") && rsbsa.val().trim() !== "";
    const isContactValid =
      !contact.hasClass("is-invalid") && contact.val().trim() !== "";
    const isEmailValid =
      !email.hasClass("is-invalid") && email.val().trim() !== "";
    const isUsernameValid =
      !username.hasClass("is-invalid") &&
      username.val().trim().length >= 8 &&
      username.val().toLowerCase() !== "username";

    // Password rules
    const pw = password.val().trim();
    const hasUppercase = /[A-Z]/.test(pw);
    const hasNumber = /[0-9]/.test(pw);
    const hasSymbol = /[^A-Za-z0-9]/.test(pw);
    const isLongEnough = pw.length >= 8;
    const isPasswordValid =
      hasUppercase && hasNumber && hasSymbol && isLongEnough;

    // Final check — all required fields filled & no .is-invalid fields
    const noInvalids = form.find(".is-invalid").length === 0;

    if (
      requiredFieldsFilled &&
      isRSBSAValid &&
      isContactValid &&
      isEmailValid &&
      isUsernameValid &&
      isPasswordValid &&
      noInvalids
    ) {
      $("#submitBtn").prop("disabled", false);
    } else {
      $("#submitBtn").prop("disabled", true);
    }
  }

  $("#submitBtn").on("click", function (e) {
    e.preventDefault(); // Prevent default form submission

    if (typeof checkFormValidity === "function") {
      checkFormValidity(); // Optional re-check before submit
    }

    if (!$(this).prop("disabled")) {
      if (typeof showLoader === "function") {
        showLoader();
      }

      showLoader();
      $(this).text("Submitting...").prop("disabled", true);

      $("#signUpForm").submit(); // Manually trigger form submit
    }
  });

  const toggle = document.getElementById("togglePassword");
  const password = document.getElementById("login_password");

  if (toggle && password) {
    toggle.addEventListener("change", function () {
      password.type = this.checked ? "text" : "password";
    });
  }
});
