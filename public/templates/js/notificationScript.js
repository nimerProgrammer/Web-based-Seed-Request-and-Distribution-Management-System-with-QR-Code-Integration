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

  function fetchNotifications() {
    $.ajax({
      url: "notifications/fetch", // Backend route
      method: "GET",
      dataType: "json",
      success: function (data) {
        let html = "";
        let unseenCount = data.count; // Count unseen based on returned data

        // ✅ Toggle red bell if unseen > 0
        if (unseenCount > 0) {
          $("#notifCount").removeClass("d-none").text(unseenCount);
          $("#viewAllBtn").removeClass("d-none");
        } else {
          $("#notifCount").addClass("d-none");
          $("#viewAllBtn").addClass("d-none");
        }

        // ✅ Use data.notifications instead of data.forEach
        if (unseenCount > 0 && data.notifications.length > 0) {
          data.notifications.forEach((item) => {
            let approveBtn = "";

            // Add Approve button only if type is 'new request'
            if (item.type === "new request") {
              approveBtn = `
                  <br>
                  <button class="btn btn-sm btn-success ms-2 approve-btn" data-request_id="${item.request_id}" data-id="${item.notifications_tbl_id}">
                      <i class="bi bi-check-lg"></i> Approve
                  </button>

                  <button class="btn btn-sm btn-danger ms-2 reject-btn" data-request_id="${item.request_id}" data-id="${item.notifications_tbl_id}">
                      <i class="bi bi-x-lg"></i> Reject
                  </button>
              `;
            }

            html += `
              <li class="mt-2 notification-item" data-notifid="${
                item.notifications_tbl_id
              }">
                  <i class="${item.icon} ${item.color}"></i> ${item.content}
                  <br>
                  <small class="text-muted">${formatNotificationDate(
                    item.created_at
                  )}</small>
                  ${approveBtn}
              </li>
            `;
          });
        } else {
          html = `<li class="text-muted">No notifications yet...</li>`;
        }

        $("#notificationList").html(html);
      },
    });
  }

  // Load immediately
  fetchNotifications();

  // Refresh every 10 seconds
  setInterval(fetchNotifications, 30000);

  function formatNotificationDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();

    const isToday = date.toDateString() === now.toDateString();

    // Format time (6:50 AM)
    const options = { hour: "numeric", minute: "numeric", hour12: true };
    const formattedTime = date.toLocaleTimeString([], options);

    // Calculate "time ago"
    const seconds = Math.floor((now - date) / 1000);
    let interval = Math.floor(seconds / 31536000);

    let timeAgo = "";
    if (interval >= 1) {
      timeAgo = interval + "y ago";
    } else {
      interval = Math.floor(seconds / 2592000);
      if (interval >= 1) {
        timeAgo = interval + "m ago";
      } else {
        interval = Math.floor(seconds / 86400);
        if (interval >= 1) {
          timeAgo = interval + "d ago";
        } else {
          interval = Math.floor(seconds / 3600);
          if (interval >= 1) {
            timeAgo = interval + "h ago";
          } else {
            interval = Math.floor(seconds / 60);
            if (interval >= 1) {
              timeAgo = interval + "m ago";
            } else {
              timeAgo = "Just now";
            }
          }
        }
      }
    }

    if (isToday) {
      return `${formattedTime} • ${timeAgo}`;
    } else {
      // Format like Dec. 10 • 6:50 AM • 2d ago
      const monthDay = date.toLocaleString("en-US", {
        month: "short",
        day: "numeric",
      });
      return `${monthDay} • ${formattedTime} • ${timeAgo}`;
    }
  }

  $("#viewAllBtn").on("click", function () {
    showLoader();

    $.ajax({
      url: "notifications/seenAll", // Backend route
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.success) {
          fetchNotifications();
          hideLoader();
        }
      },
    });
  });

  $(document).on("click", ".notification-item", function () {
    const id = $(this).data("notifid");

    // Example AJAX call to approve the request
    $.ajax({
      url: "notifications/seen", // Backend route
      method: "POST",
      data: { id: id },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          fetchNotifications();
        }
      },
      error: function () {
        alert("Something went wrong!");
      },
    });
  });

  // Event delegation for Approve button
  $(document).on("click", ".approve-btn", function () {
    const id = $(this).data("id");
    const request_id = $(this).data("request_id");

    // Example AJAX call to approve the request
    $.ajax({
      url: "notifications/approve", // Backend route
      method: "POST",
      data: { id: id, request_id: request_id },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          location.reload();
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "This request cannot be approved. It may have been canceled.",
            timer: 4000,
            showConfirmButton: false,
            customClass: {
              confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
          });
          return;
        }
      },
      error: function () {
        alert("Something went wrong!");
      },
    });
  });

  // Event delegation for Reject button
  $(document).on("click", ".reject-btn", function () {
    const id = $(this).data("id");
    const request_id = $(this).data("request_id");

    // Example AJAX call to reject the request
    $.ajax({
      url: "notifications/reject", // Backend route
      method: "POST",
      data: { id: id, request_id: request_id },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          location.reload();
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "This request cannot be rejected. It may have been canceled.",
            timer: 4000,
            showConfirmButton: false,
            customClass: {
              confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
          });
          return;
        }
      },
      error: function () {
        alert("Something went wrong!");
      },
    });
  });
});
