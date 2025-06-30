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

  $("#publicRequestSeedLink").on("click", function (e) {
    e.preventDefault();

    const isLoggedIn = $(this).data("is-logged-in") === "1";
    const requestUrl = $(this).data("request-url");
    const loginUrl = $(this).data("login-url");
    const signupUrl = $(this).data("signup-url");

    if (isLoggedIn) {
      // ✅ User is logged in — go directly to seed request page
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
            window.location.href = loginUrl;
          });
          $("#swal-signup").on("click", () => {
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
});
