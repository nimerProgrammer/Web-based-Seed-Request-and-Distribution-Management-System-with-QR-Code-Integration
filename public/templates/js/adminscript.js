document.addEventListener("DOMContentLoaded", function () {
  // 1. Apply saved state
  if (localStorage.getItem("sidebar-collapsed") === "true") {
    document.body.classList.add("sidebar-collapse");
  }

  // 2. Toggle and save state
  document
    .querySelectorAll("[data-widget='pushmenu']")
    .forEach(function (toggleBtn) {
      toggleBtn.addEventListener("click", function () {
        const isCollapsed =
          document.body.classList.contains("sidebar-collapse");
        localStorage.setItem("sidebar-collapsed", !isCollapsed); // save opposite (it will collapse now)
      });
    });
});

$(document).ready(function () {
  preventDevTools(false);
  // preventMobileAccess();

  window.addEventListener("pageshow", function () {
    hideLoader(); // Your custom function to hide the loader
  });

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
  document.querySelectorAll(".nav-sidebar .nav-link").forEach((link) => {
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

  // Inventory Table
  $("#seedTable").DataTable({
    ordering: false,
  });

  $(".set-Table").DataTable({
    ordering: false,
  });
  // function preventMobileAccess() {
  //     if (/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
  //         document.body.innerHTML = `
  //         <div style="display: flex; height: 100vh; align-items: center; justify-content: center; background-color: #f8d7da; color: #721c24; text-align: center; padding: 20px; font-family: Arial, sans-serif;">
  //             <div>
  //                 <h1 style="font-size: 3rem;">Access Denied</h1>
  //                 <p style="font-size: 1.5rem;">This page is not accessible on mobile devices.</p>
  //             </div>
  //         </div>`;
  //     }
  // }

  // for search suggestions
  let baseURL = $("#base_url").val();

  const pages = [
    { name: "Dashboard", keywords: ["dashboard"], route: "dashboard" },
    { name: "Inventory", keywords: ["inventory", "stock"], route: "inventory" },
    {
      name: "Seed Requests",
      keywords: ["seeds", "requests"],
      route: "seedsRequests",
    },
    {
      name: "Beneficiaries",
      keywords: ["beneficiaries", "farmers"],
      route: "beneficiaries",
    },
    { name: "Reports", keywords: ["reports"], route: "reports" },
    { name: "Logs", keywords: ["logs"], route: "logs" },
    { name: "Logout", keywords: ["logout", "sign out"], route: "logout" },
  ];

  // Show suggestions
  $("#searchInput").on("input", function () {
    const input = $(this).val().trim().toLowerCase();
    const suggestions = $("#suggestionsList");
    suggestions.empty().hide();

    if (input.length === 0) return;

    const matches = pages.filter((page) =>
      page.keywords.some(
        (keyword) => keyword.includes(input) || input.includes(keyword)
      )
    );

    if (matches.length > 0) {
      matches.forEach((page) => {
        suggestions.append(
          `<li class="list-group-item suggestion-item" data-route="${page.route}">${page.name}</li>`
        );
      });
      suggestions.show();
    }
  });

  // Click suggestion
  $(document).on("click", ".suggestion-item", function () {
    const route = $(this).data("route");
    window.location.href = baseURL + route;
  });

  // Submit search
  $("#searchForm").on("submit", function (e) {
    e.preventDefault();
    const input = $("#searchInput").val().trim().toLowerCase();

    const match = pages.find((page) =>
      page.keywords.some(
        (keyword) => keyword.includes(input) || input.includes(keyword)
      )
    );

    if (match) {
      window.location.href = baseURL + match.route;
    } else {
      alert("No matching page found.");
    }
  });

  // Hide suggestions when clicking outside
  $(document).on("click", function (e) {
    if (!$(e.target).closest("#searchForm").length) {
      $("#suggestionsList").hide();
    }
  });

  // Save New Seed to Inventory
  const addSeedForm = document.querySelector("#addSeedModal form");
  if (addSeedForm) {
    const saveBtn = document.getElementById("add_seed_to_inventory");
    const cancelBtn = document.querySelector("#addSeedModal .btn-secondary");

    addSeedForm.addEventListener("submit", function () {
      saveBtn.innerHTML = "Saving...";
      saveBtn.disabled = true;
      cancelBtn.disabled = true;
      showLoader();
    });
  }

  // Edit Seed Inventory
  document.querySelectorAll(".edit-inventory-button").forEach((button) => {
    button.addEventListener("click", function () {
      document.getElementById("edit_inventory_id").value = this.dataset.id;
      document.getElementById("edit_seed_name").value = this.dataset.name;
      document.getElementById("edit_seed_class").value = this.dataset.class;
      document.getElementById("edit_stock").value = this.dataset.stock;

      // Set form action dynamically
      document.getElementById(
        "editSeedForm"
      ).action = `/admin/inventory/update/${this.dataset.id}`;
    });

    // Wait for DOM to load
    const form = document.getElementById("editSeedForm");
    const updateBtn = document.getElementById("edit_update_button");
    const cancelBtn = document.getElementById("edit_cancel_button");

    form.addEventListener("submit", function () {
      updateBtn.innerHTML = "Updating...";
      updateBtn.disabled = true;
      cancelBtn.disabled = true;
      showLoader();
    });
  });

  document.querySelectorAll(".delete-inventory-button").forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      const href = this.getAttribute("href");

      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        customClass: {
          confirmButton: "btn btn-sm btn-primary mr-1",
          cancelButton: "btn btn-sm btn-secondary",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.isConfirmed) {
          showLoader();
          window.location.href = href;
        }
      });
    });
  });

  const swalFormHandler = (selector, title, text) => {
    document.querySelectorAll(selector).forEach(function (form) {
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        Swal.fire({
          title: title,
          text: text,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, continue",
          cancelButtonText: "Cancel",
          customClass: {
            confirmButton: "btn btn-sm btn-primary me-2",
            cancelButton: "btn btn-sm btn-secondary",
          },
          buttonsStyling: false,
        }).then(function (result) {
          if (result.isConfirmed) {
            showLoader(); // Optional
            form.submit();
          }
        });
      });
    });
  };

  // Bind each action with appropriate confirmation
  swalFormHandler(
    ".approve-form",
    "Approve request?",
    "Are you sure you want to approve this request?"
  );
  swalFormHandler(
    ".undo-approve-form",
    "Undo approval?",
    "Are you sure you want to undo this approval?"
  );
  swalFormHandler(
    ".reject-form",
    "Reject request?",
    "Are you sure you want to reject this request?"
  );
  swalFormHandler(
    ".undo-reject-form",
    "Undo rejection?",
    "Are you sure you want to undo this rejection?"
  );

  document.querySelectorAll(".select-barangay").forEach((item) => {
    item.addEventListener("click", function (e) {
      e.preventDefault();
      const value = this.getAttribute("data-value");
      document.getElementById("barangayDataInput").value = value;
      showLoader(); // Optional: show loading spinner
      document.getElementById("barangayForm").submit();
    });
  });

  function attachSwal(selector, title, text) {
    document.querySelectorAll(selector).forEach(function (form) {
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        Swal.fire({
          title: title,
          text: text,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, continue",
          cancelButtonText: "Cancel",
          customClass: {
            confirmButton: "btn btn-sm btn-primary me-2",
            cancelButton: "btn btn-sm btn-secondary",
          },
          buttonsStyling: false,
        }).then(function (result) {
          if (result.isConfirmed) {
            showLoader();
            form.submit();
          }
        });
      });
    });
  }

  attachSwal(
    ".mark-receive-form",
    "Mark as received?",
    "Confirm that this beneficiary has received the seeds?"
  );
  attachSwal(
    ".undo-receive-form",
    "Undo received?",
    "Are you sure you want to undo the received status?"
  );

  $(".select-season").on("click", function (e) {
    e.preventDefault();

    var selected = $(this).data("value"); // Get value from data-value
    $("#seasonDataInput").val(selected); // Set hidden input

    if (typeof showLoader === "function") {
      showLoader(); // Optional: show loading spinner
    }

    $("#seasonForm").submit(); // Submit the form
  });

  $(".dropdownListReports").on("click", function () {
    showLoader();
  });

  $("#exportExcelBtn").on("click", function (e) {
    e.preventDefault();

    // Get the active tab
    const activeTab = document.querySelector(
      "#seedRequestsReportsTabs .nav-link.active"
    );
    if (!activeTab) return;

    // Extract inventory ID from tab's data-bs-target
    const targetId = activeTab.getAttribute("data-bs-target"); // e.g., "#tab-content-5"
    const inventoryId = targetId.replace("#tab-content-", "");

    // Set hidden input value and submit the form
    $("#excelInventoryId").val(inventoryId);
    $("#excelExportForm").submit();
  });

  $("#seedRequestExportPdfBtn").on("click", function (e) {
    e.preventDefault();

    // Get the active tab
    const activeTab = document.querySelector(
      "#seedRequestsReportsTabs .nav-link.active"
    );
    if (!activeTab) return;

    // Extract inventory ID
    const targetId = activeTab.getAttribute("data-bs-target");
    const inventoryId = targetId.replace("#tab-content-", "");

    // Get the seed name directly from the active tab
    const seedName = activeTab.getAttribute("data-seed-name");

    // Fill hidden inputs
    $("#pdfInventoryId").val(inventoryId);
    $("#pdfSeedName").val(seedName);

    // Submit form
    $("#seedRequestPDFExportForm").submit();
  });

  $("#beneficiariesExportPdfBtn").on("click", function (e) {
    e.preventDefault();

    // Get the active tab
    const activeTab = document.querySelector(
      "#beneficiariesReportsTabs .nav-link.active"
    );
    if (!activeTab) return;

    // Extract inventory ID
    const targetId = activeTab.getAttribute("data-bs-target");
    const inventoryId = targetId.replace("#tab-content-", "");

    // Get the seed name directly from the active tab
    const seedName = activeTab.getAttribute("data-seed-name");

    // Fill hidden inputs
    $("#pdfInventoryId").val(inventoryId);
    $("#pdfSeedName").val(seedName);

    // Submit form
    $("#beneficiariesPDFExportForm").submit();
  });

  /* Clear Logs Confirmation */
  $("#clearLogsBtn").on("click", function (e) {
    e.preventDefault();

    Swal.fire({
      title: "Are you sure?",
      text: "This will permanently delete all logs!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, clear logs!",
      cancelButtonText: "Cancel",
      customClass: {
        confirmButton: "btn btn-sm btn-primary me-2",
        cancelButton: "btn btn-sm btn-secondary",
      },
      buttonsStyling: false,
    }).then((result) => {
      if (result.isConfirmed) {
        showLoader();
        $("#clearLogsForm").submit();
      }
    });
  });
});
