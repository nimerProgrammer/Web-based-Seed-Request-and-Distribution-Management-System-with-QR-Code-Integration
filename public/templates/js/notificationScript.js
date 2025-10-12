$(document).ready(function () {
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
        } else {
          $("#notifCount").addClass("d-none");
        }

        // ✅ Use data.notifications instead of data.forEach
        if (unseenCount > 0 && data.notifications.length > 0) {
          data.notifications.forEach((item) => {
            html += `
              <li class="mt-2">
                  <i class="${item.icon} ${item.color}"></i> ${item.content}
                  <br>
                  <small class="text-muted">${formatNotificationDate(
                    item.created_at
                  )}</small>
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
});
