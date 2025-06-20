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
  const form = document.querySelector("#addSeedModal form");
  const saveBtn = document.getElementById("add_seed_to_inventory");
  const cancelBtn = document.querySelector("#addSeedModal .btn-secondary");

  form.addEventListener("submit", function () {
    saveBtn.innerHTML = "Saving...";
    saveBtn.disabled = true;
    cancelBtn.disabled = true;
  });

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
    });
  });
});
