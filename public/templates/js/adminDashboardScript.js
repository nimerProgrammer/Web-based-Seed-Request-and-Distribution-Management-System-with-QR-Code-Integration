$(document).ready(function () {
  function showLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
      loader.style.display = "flex";
    }
  }

  $("#seasonsTable").DataTable({
    ordering: false,
    searching: false,
    lengthChange: false, // ✅ disables the "Show entries" dropdown
  });

  $(".dash-btn").on("click", function (e) {
    showLoader();
  });
  function validateSeasonCombination() {
    const seasonName = $("#season_name").val();
    const seasonYear = $("#season_year").val();
    const url = $("#season_name").data("url");

    // Only run if both fields have a value
    if (!seasonName || !seasonYear) return;

    $.ajax({
      url: url,
      type: "POST",
      data: {
        season_name: seasonName,
        season_year: seasonYear,
      },
      success: function (response) {
        if (response.exists) {
          // Show invalid style
          $("#season_name, #season_year").addClass("is-invalid");

          // Show error messages
          $("#seasonNameFeedback")
            .text("This season name already exists with the selected year.")
            .show();
          $("#seasonYearFeedback")
            .text("This season year is already taken for the selected name.")
            .show();

          // Optionally disable submit
          $("#submit_btn").prop("disabled", true);
        } else {
          // Clear invalid style
          $("#season_name, #season_year").removeClass("is-invalid");

          // Clear messages
          $("#seasonNameFeedback").text("").hide();
          $("#seasonYearFeedback").text("").hide();

          // Enable submit
          $("#submit_btn").prop("disabled", false);
        }
      },
      error: function () {
        console.error("AJAX error: Could not check season combination.");
      },
    });
  }

  function validateStartYearMatch() {
    const selectedYear = $("#season_year").val();
    const startDate = $("#season_start_date").val();

    if (!selectedYear || !startDate) {
      $("#season_year").removeClass("is-invalid");
      $("#season_start_date").removeClass("is-invalid");
      $("#startDateFeedback").text("").hide();
      return;
    }

    const startYear = new Date(startDate).getFullYear();

    if (parseInt(selectedYear) !== startYear) {
      $("#season_year").addClass("is-invalid");
      $("#season_start_date").addClass("is-invalid");
      $("#seasonYearFeedback")
        .text("Season year must match the selected start date year.")
        .show();
      $("#startDateFeedback")
        .text("Start date year must match the selected season year.")
        .show();
    } else {
      $("#season_year").removeClass("is-invalid");
      $("#season_start_date").removeClass("is-invalid");
      $("#startDateFeedback").text("").hide();
      $("#seasonYearFeedback").text("").hide();
    }
  }

  // Run on both changes
  $("#season_year, #season_start_date").on(
    "change input",
    validateStartYearMatch
  );

  // Trigger check on both input fields
  $("#season_name, #season_year").on("input change", validateSeasonCombination);

  $("#season_start_date").on("input", function () {
    const startDate = $(this).val();
    const url = $(this).data("url"); // Get the base_url from data attribute

    if (!startDate || !url) return;

    $.ajax({
      url: url,
      type: "POST",
      data: {
        season_start_date: startDate,
      },
      success: function (response) {
        if (response.conflict) {
          $("#season_start_date").addClass("is-invalid");
          $("#startDateFeedback")
            .text("Start date overlaps with an existing cropping season.")
            .show();
        } else {
          $("#season_start_date").removeClass("is-invalid");
          $("#startDateFeedback").text("").hide();
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        console.error("Response:", xhr.responseText);
      },
    });
  });

  $("#season_start_date, #season_end_date").on("input", function () {
    const startDate = $("#season_start_date").val();
    const endDate = $("#season_end_date").val();

    if (startDate && endDate) {
      if (endDate <= startDate) {
        $("#season_start_date").addClass("is-invalid");
        $("#season_end_date").addClass("is-invalid");

        $("#startDateFeedback")
          .text("Start date must be before end date.")
          .show();
        $("#endDateFeedback").text("End date must be after start date.").show();
      } else {
        $("#season_start_date").removeClass("is-invalid");
        $("#season_end_date").removeClass("is-invalid");

        $("#startDateFeedback").text("").hide();
        $("#endDateFeedback").text("").hide();
      }
    } else {
      $("#season_start_date").removeClass("is-invalid");
      $("#season_end_date").removeClass("is-invalid");

      $("#startDateFeedback").text("").hide();
      $("#endDateFeedback").text("").hide();
    }
  });

  $("#newSeasonForm").on("submit", function (e) {
    e.preventDefault(); // Prevent default form submission
    const form = this;

    // Check if there are any HTML5 validation errors or .is-invalid classes (custom)
    const hasHTML5Invalid = !form.checkValidity();
    const hasCustomInvalid = $(form).find(".is-invalid").length > 0;

    if (hasHTML5Invalid || hasCustomInvalid) {
      e.preventDefault(); // stop form from submitting

      // Show SweetAlert error
      Swal.fire({
        icon: "error",
        title: "Invalid Input",
        text: "Please correct the highlighted fields before submitting.",
        timer: 3000, // 3 seconds
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
    } else {
      showLoader();
      $(".btn-primary").text("Adding...");
      this.submit();
    }
  });

  /* For Edit Season */
  $(".edit-season-btn").on("click", function () {
    const season = $(this).data("season");
    const year = $(this).data("year");
    const start = $(this).data("start");
    const end = $(this).data("end");
    const id = $(this).data("id");

    // Set values
    $("#edit_cropping_season_tbl_id").val(id);
    $("#edit_season_name").val(season);
    $("#edit_season_year").val(year);
    $("#edit_season_start_date").val(start);
    $("#edit_season_end_date").val(end);

    $("#editSeasonModal").modal("show");
  });

  function validateEditSeasonCombination() {
    const seasonName = $("#edit_season_name").val();
    const seasonYear = $("#edit_season_year").val();
    const seasonId = $("#edit_cropping_season_tbl_id").val();
    const url = $("#edit_season_name").data("url");

    if (!seasonName || !seasonYear) return;

    $.ajax({
      url: url,
      type: "POST",
      data: {
        season_name: seasonName,
        season_year: seasonYear,
        cropping_season_tbl_id: seasonId, // 👈 pass it
      },
      success: function (response) {
        if (response.exists) {
          $("#edit_season_name, #edit_season_year").addClass("is-invalid");
          $("#editSeasonNameFeedback")
            .text("This season name already exists with the selected year.")
            .show();
          $("#editSeasonYearFeedback")
            .text("This season year is already taken for the selected name.")
            .show();
          $("#edit_submit_btn").prop("disabled", true);
        } else {
          $("#edit_season_name, #edit_season_year").removeClass("is-invalid");
          $("#editSeasonNameFeedback").text("").hide();
          $("#editSeasonYearFeedback").text("").hide();
          $("#edit_submit_btn").prop("disabled", false);
        }
      },
      error: function () {
        console.error("AJAX error: Could not check edit season combination.");
      },
    });
  }

  function validateEditStartYearMatch() {
    const selectedYear = $("#edit_season_year").val();
    const startDate = $("#edit_season_start_date").val();

    if (!selectedYear || !startDate) {
      $("#edit_season_year, #edit_season_start_date").removeClass("is-invalid");
      $("#editStartDateFeedback, #editSeasonYearFeedback").text("").hide();
      return;
    }

    const startYear = new Date(startDate).getFullYear();
    if (parseInt(selectedYear) !== startYear) {
      $("#edit_season_year, #edit_season_start_date").addClass("is-invalid");
      $("#editStartDateFeedback")
        .text("Start date year must match the selected season year.")
        .show();
      $("#editSeasonYearFeedback")
        .text("Season year must match the selected start date year.")
        .show();
    } else {
      $("#edit_season_year, #edit_season_start_date").removeClass("is-invalid");
      $("#editStartDateFeedback, #editSeasonYearFeedback").text("").hide();
    }
  }

  $("#edit_season_year, #edit_season_start_date").on(
    "change input",
    validateEditStartYearMatch
  );
  $("#edit_season_name, #edit_season_year").on(
    "input change",
    validateEditSeasonCombination
  );

  $("#edit_season_start_date").on("input", function () {
    const startDate = $(this).val();
    const url = $(this).data("url");
    const id = $("#edit_cropping_season_tbl_id").val(); // get the current record ID

    if (!startDate || !url || !id) return;

    $.ajax({
      url: url,
      type: "POST",
      data: {
        season_start_date: startDate,
        cropping_season_tbl_id: id, // send the current ID
      },
      success: function (response) {
        if (response.conflict) {
          $("#edit_season_start_date").addClass("is-invalid");
          $("#editStartDateFeedback")
            .text("Start date overlaps with an existing cropping season.")
            .show();
        } else {
          $("#edit_season_start_date").removeClass("is-invalid");
          $("#editStartDateFeedback").text("").hide();
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        console.error("Response:", xhr.responseText);
      },
    });
  });

  $("#edit_season_start_date, #edit_season_end_date").on("input", function () {
    const startDate = $("#edit_season_start_date").val();
    const endDate = $("#edit_season_end_date").val();

    if (startDate && endDate) {
      if (endDate <= startDate) {
        $("#edit_season_start_date, #edit_season_end_date").addClass(
          "is-invalid"
        );
        $("#editStartDateFeedback")
          .text("Start date must be before end date.")
          .show();
        $("#editEndDateFeedback")
          .text("End date must be after start date.")
          .show();
      } else {
        $("#edit_season_start_date, #edit_season_end_date").removeClass(
          "is-invalid"
        );
        $("#editStartDateFeedback, #editEndDateFeedback").text("").hide();
      }
    } else {
      $("#edit_season_start_date, #edit_season_end_date").removeClass(
        "is-invalid"
      );
      $("#editStartDateFeedback, #editEndDateFeedback").text("").hide();
    }
  });

  $("#editSeasonForm").on("submit", function (e) {
    e.preventDefault();
    const form = this;
    const hasHTML5Invalid = !form.checkValidity();
    const hasCustomInvalid = $(form).find(".is-invalid").length > 0;

    if (hasHTML5Invalid || hasCustomInvalid) {
      Swal.fire({
        icon: "error",
        title: "Invalid Input",
        text: "Please correct the highlighted fields before submitting.",
        timer: 3000,
        showConfirmButton: false,
        customClass: {
          confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
      });
    } else {
      showLoader();
      $(".btn-primary").text("Saving...");
      form.submit();
    }
  });

  $(".delete-season-btn").on("click", function (e) {
    e.preventDefault();

    const button = $(this);
    if (button.hasClass("disabled")) return;

    const url = button.data("url");
    const csrfName = button.data("csrf-name");
    const csrfHash = button.data("csrf-hash");

    Swal.fire({
      title: "Are you sure?",
      text: "This cropping season will be permanently deleted.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "Cancel",
      customClass: {
        confirmButton: "btn btn-danger me-2",
        cancelButton: "btn btn-secondary",
      },
      buttonsStyling: false,
    }).then((result) => {
      if (result.isConfirmed) {
        showLoader();
        $(".btn-danger").text("Deleting...");
        $.ajax({
          url: url,
          method: "POST",
          data: {
            [csrfName]: csrfHash,
          },
          success: function (response) {
            Swal.fire({
              icon: "success",
              title: "Deleted!",
              text: response.message,
              timer: 2000,
              showConfirmButton: false,
            }).then(() => {
              location.reload();
            });
          },
          error: function (xhr) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text:
                xhr.responseJSON?.message ||
                "Failed to delete cropping season.",
            });
          },
        });
      }
    });
  });
});
