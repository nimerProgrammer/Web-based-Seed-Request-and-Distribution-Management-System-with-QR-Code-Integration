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

  $("#editBirthdate").on("input", function () {
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
  });

  $("#editContactNo").on("input", function () {
    let original = $("#originalContactNo").val();
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
        "checker",
        {
          table: "users",
          field: "contact_no",
          value: value,
          original: original,
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
  });

  $("#editEmail").on("input", function () {
    let original = $("#originalEmail").val();
    let value = $(this).val().trim();
    let input = $(this);

    if (!value) {
      input.removeClass("is-invalid");
      input.next(".invalid-feedback").hide();
      return;
    }

    // Allowed domains
    let allowedDomains = ["@gmail.com", "@email.com", "@yahoo.com"];
    let isValid = false;

    for (let domain of allowedDomains) {
      if (value.endsWith(domain)) {
        let prefix = value.slice(0, -domain.length);
        if (prefix.length > 0 && /^[A-Za-z0-9._%+-]+$/.test(prefix)) {
          isValid = true;
          break;
        }
      }
    }

    if (!isValid) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text(
          "Please enter a complete email address (e.g., abc@gmail.com, abc@yahoo.com, or abc@email.com), not only the domain."
        )
        .show();
      return;
    }

    // AJAX duplicate check
    $.post(
      "checker",
      {
        table: "users",
        field: "email",
        value: value,
        original: original,
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
  });

  $("#editUsername").on("input", function () {
    let original = $("#originalUsername").val();
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
      return;
    }

    // Disallow the literal word "username"
    else if (value.toLowerCase() === "username") {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Username cannot be 'username'.")
        .show();
      return;
    }

    // Disallow uppercase letters
    if (/[A-Z]/.test(value)) {
      input.addClass("is-invalid");
      input
        .next(".invalid-feedback")
        .text("Username must not contain uppercase letters.")
        .show();
      return;
    }

    // AJAX duplicate check
    $.post(
      "checker",
      {
        table: "users", // Fixed typo here
        field: "username",
        value: value,
        original: original,
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
        }
      },
      "json"
    );
  });

  $("#editFullnameBtn").on("click", function () {
    const firstName = $(this).data("firstname") || "";
    const lastName = $(this).data("lastname") || "";
    const middleName = $(this).data("middlename") || "";
    const extName = $(this).data("extname") || "";

    $("#editFirstName").val(firstName);
    $("#editLastName").val(lastName);
    $("#editMiddleName").val(middleName);
    $("#editExtName").val(extName);

    $("#editNameModal").modal("show");
  });

  $("#editGenderBtn").on("click", function () {
    const gender = $(this).data("gender") || "";
    $("#editGender").val(gender);
    $("#editGenderModal").modal("show");
  });

  $("#editBirthdateBtn").on("click", function () {
    const birthdate = $(this).data("birthdate") || "";
    $("#editBirthdate").val(birthdate);
    $("#editBirthdateModal").modal("show");
  });

  $("#editBarangayBtn").on("click", function () {
    const barangay = $(this).data("barangay") || "";
    $("#editBarangay").val(barangay);
    $("#editBarangayModal").modal("show");
  });

  $("#editFarmAreaBtn").on("click", function () {
    const farmArea = $(this).data("farmarea") || "";
    $("#editFarmArea").val(farmArea);
    $("#editFarmAreaModal").modal("show");
  });

  $("#editNameLandOwnerBtn").on("click", function () {
    const land_owner = $(this).data("namelandowner") || "";
    $("#editLand_owner").val(land_owner);
    $("#editNameLandOwnerModal").modal("show");
  });

  $("#editRSBSABtn").on("click", function () {
    const rsbsa = $(this).data("rsbsa") || "";
    $("#editRSBSA").val(rsbsa);
    $("#editRSBSAModal").modal("show");
  });

  $("#editContactNoBtn").on("click", function () {
    const contactno = $(this).data("contactno") || "";
    $("#editContactNo").val(contactno);
    $("#originalContactNo").val(contactno);
    $("#editContactNoModal").modal("show");
  });

  $("#editEmailBtn").on("click", function () {
    const email = $(this).data("email") || "";
    $("#editEmail").val(email);
    $("#originalEmail").val(email);
    $("#editEmailModal").modal("show");
  });

  $("#editUsernameBtn").on("click", function () {
    const username = $(this).data("username") || "";
    $("#editUsername").val(username);
    $("#originalUsername").val(username);
    $("#editUsernameModal").modal("show");
  });

  /* forms */
  $("#editFullnameForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editGenderForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editBirthdateForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid date, you must be at least 18 years old.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editBarangayForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editFarmAreaForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editNameLandOwnerForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editRSBSAForm").on("submit", function (e) {
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editContactNoForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid contact number, please correct it.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editEmailForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid email address, please correct it.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });

  $("#editUsernameForm").on("submit", function (e) {
    e.preventDefault(); // Stop form from submitting immediately

    let hasError = false;

    // Check all invalid-feedback elements for visible error messages
    $(this)
      .find(".invalid-feedback")
      .each(function () {
        if ($(this).is(":visible") && $(this).text().trim() !== "") {
          hasError = true;
        }
      });

    if (hasError) {
      Swal.fire({
        icon: "error",
        title: "Oops!",
        text: "Invalid username, please correct it.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
      return;
    }

    // If no errors, proceed
    this.submit(); // Submit the form normally
    $(".btn").text("Saving...");
    showLoader();
  });
});
