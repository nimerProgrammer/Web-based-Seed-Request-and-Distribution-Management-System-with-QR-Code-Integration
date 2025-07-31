document.addEventListener("DOMContentLoaded", function () {
  const BASE_URL = document.querySelector("#base_url")?.value || "";

  const pages = [
    { name: "Profile", keywords: ["profile"], route: "public/profile" },
    { name: "Home", keywords: ["home"], route: "" },
    {
      name: "Request a Seed",
      keywords: [
        "request",
        "request seed",
        "request a seed",
        "seed request",
        "seeds",
      ],
      route: "public/seedsRequests",
    },
    {
      name: "Sent Requests",
      keywords: ["sent", "requests"],
      route: "public/sentRequests",
    },
    { name: "About", keywords: ["about", "info"], route: "public/about" },
  ];

  const searchInput = document.querySelector("#searchInput");
  const searchForm = document.querySelector("#searchForm");

  // Create and style the suggestion list
  const suggestionsList = document.createElement("ul");
  suggestionsList.id = "suggestionsList";
  suggestionsList.style.cssText = `
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1050;
    width: 100%;
    display: none;
    background-color: white;
    border: 1px solid #ccc;
    border-top: none;
    border-radius: 0 0 0.25rem 0.25rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    font-size: 0.875rem;
    list-style: none;
    padding-left: 0;
    margin-top: 2px;
  `;
  searchForm.appendChild(suggestionsList);

  searchInput.addEventListener("input", function () {
    const input = this.value.trim().toLowerCase();
    suggestionsList.innerHTML = "";
    suggestionsList.style.display = "none";

    if (!input) return;

    const matches = pages.filter((page) =>
      page.keywords.some(
        (keyword) => keyword.includes(input) || input.includes(keyword)
      )
    );

    if (matches.length > 0) {
      matches.forEach((page) => {
        const li = document.createElement("li");
        li.textContent = page.name;
        li.setAttribute("data-route", page.route);
        li.style.cssText = `
          padding: 6px 10px;
          cursor: pointer;
          border-bottom: 1px solid #eee;
        `;
        li.addEventListener("click", function () {
          window.location.href = BASE_URL + this.getAttribute("data-route");
        });
        suggestionsList.appendChild(li);
      });
      suggestionsList.style.display = "block";
    }
  });

  // Hide suggestions on outside click
  document.addEventListener("click", function (e) {
    if (!searchForm.contains(e.target)) {
      suggestionsList.style.display = "none";
    }
  });

  // Handle submit
  searchForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const input = searchInput.value.trim().toLowerCase();
    const match = pages.find((page) =>
      page.keywords.some(
        (keyword) => keyword.includes(input) || input.includes(keyword)
      )
    );
    if (match) {
      window.location.href = BASE_URL + match.route;
    } else {
      alert("No matching page found.");
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const allCarousels = document.querySelectorAll(".carousel");

  allCarousels.forEach((carousel) => {
    const indicators = carousel.querySelectorAll(
      ".carousel-indicators [data-bs-target]"
    );

    function updateIndicators() {
      const activeIndex = [...indicators].findIndex((dot) =>
        dot.classList.contains("active")
      );

      indicators.forEach((dot, i) => {
        dot.classList.remove("active-dot", "near-active-dot");
        if (i === activeIndex) {
          dot.classList.add("active-dot");
        } else if (Math.abs(i - activeIndex) <= 2) {
          dot.classList.add("near-active-dot");
        }
      });
    }

    // Initial indicator styling
    updateIndicators();

    // Update indicators on slide
    carousel.addEventListener("slid.bs.carousel", updateIndicators);
  });
});

window.addEventListener("DOMContentLoaded", () => {
  const navbar = document.querySelector(".main-header.navbar");
  if (navbar) {
    const navbarHeight = navbar.offsetHeight;
    document.body.style.paddingTop = `${navbarHeight}px`;
  }
});

// Optional: Recalculate on window resize
window.addEventListener("resize", () => {
  const navbar = document.querySelector(".main-header.navbar");
  if (navbar) {
    const navbarHeight = navbar.offsetHeight;
    document.body.style.paddingTop = `${navbarHeight}px`;
  }
});

$(document).ready(function () {
  preventDevTools(false);

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

    const isLoggedIn = $(this).data("is-logged-in"); // boolean true/false
    const signupUrl = $(this).data("signup-url");

    hideLoader(); // Optional custom function
    if (isLoggedIn) {
      showLoader();
      const modal = new bootstrap.Modal(
        document.getElementById("requestSeedModal")
      );
      modal.show();
      hideLoader();
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
            const loginModal = new bootstrap.Modal(
              document.getElementById("loginModalDialog")
            );
            Swal.close();
            loginModal.show();
            hideLoader();
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

  $("#publicSentRequestLink").on("click", function (e) {
    e.preventDefault();

    const isLoggedIn = $(this).data("is-logged-in"); // boolean true/false
    const signupUrl = $(this).data("signup-url");
    const targetUrl = $(this).attr("data-sentRequests-url");

    hideLoader();

    if (isLoggedIn) {
      showLoader();
      window.location.href = targetUrl;
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
            const loginModal = new bootstrap.Modal(
              document.getElementById("loginModalDialog")
            );
            Swal.close();
            loginModal.show();
            hideLoader();
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

  const toggle = document.getElementById("togglePassword");
  const password = document.getElementById("login_password");

  if (toggle && password) {
    toggle.addEventListener("change", function () {
      password.type = this.checked ? "text" : "password";
    });
  }

  $("#sigUpBtn").on("click", function () {
    showLoader();
  });

  $("#logoutBtn").on("click", function () {
    Swal.fire({
      title: "Log Out?",
      text: "Are you sure you want to log out?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, log out",
      cancelButtonText: "Cancel",
      customClass: {
        confirmButton: "btn btn-md btn-primary mr-2",
        cancelButton: "btn btn-md btn-secondary",
      },
      buttonsStyling: false,
    }).then((result) => {
      if (result.isConfirmed) {
        showLoader();
        const logoutUrl = $(this).data("url");
        window.location.href = logoutUrl;
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
