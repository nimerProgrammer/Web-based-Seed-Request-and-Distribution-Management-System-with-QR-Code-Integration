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

  const select = document.getElementById("seed_name");
  const submitBtn = document.getElementById("submitRequestSeedBtn");

  if (!select || !submitBtn) return;

  const enabledOptions = Array.from(select.options).filter(
    (option) => option.value !== "" && !option.disabled
  );

  if (enabledOptions.length === 0) {
    submitBtn.disabled = true;
    submitBtn.textContent = "No seeds available";
  }

  $("#submitRequestSeedBtn").on("click", function (e) {
    e.preventDefault();
    const selected = $("#seed_name").val();
    if (selected) {
      showLoader();
      $("#submitRequestSeedForm").submit();
    } else {
      Swal.fire({
        icon: "info",
        title: "Oops...",
        text: "Please select seed type.",
        customClass: {
          confirmButton: "btn btn-md btn-primary",
        },
        buttonsStyling: false,
      });
    }
  });
});
