document.addEventListener("DOMContentLoaded", () => {
  // Navigation active state
  const navLinks = document.querySelectorAll(".nav-link");
  navLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      navLinks.forEach((l) => l.classList.remove("active"));
      link.classList.add("active");
    });
  });

  // Logout button
  const logoutButton = document.querySelector(".button-primary");
  logoutButton.addEventListener("click", () => {
    // Add logout logic here
    window.location.href = "../html/index.html";
  });

  // Progress bars animation
  const progressBars = document.querySelectorAll(".progress-bar-fill");
  progressBars.forEach((bar) => {
    const width = bar.style.width;
    bar.style.width = "0";
    setTimeout(() => {
      bar.style.width = width;
    }, 100);
  });

  // Button interactions
  const buttons = document.querySelectorAll(".button");
  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      if (this.classList.contains("button-success")) {
        handleApproval(this);
      } else if (this.classList.contains("button-danger")) {
        handleRejection(this);
      }
    });
  });

  // Handle approvals
  function handleApproval(button) {
    const listItem = button.closest(".list-item");
    const title = listItem.querySelector(".list-item-title").textContent;

    // Add success state
    button.textContent = "Approved";
    button.disabled = true;

    // Disable reject button
    const rejectButton = listItem.querySelector(".button-danger");
    if (rejectButton) {
      rejectButton.disabled = true;
    }

    // Show success message
    showNotification(`Successfully approved ${title}`, "success");
  }

  // Handle rejections
  function handleRejection(button) {
    const listItem = button.closest(".list-item");
    const title = listItem.querySelector(".list-item-title").textContent;

    // Add rejected state
    button.textContent = "Rejected";
    button.disabled = true;

    // Disable approve button
    const approveButton = listItem.querySelector(".button-success");
    if (approveButton) {
      approveButton.disabled = true;
    }

    // Show rejection message
    showNotification(`${title} has been rejected`, "error");
  }

  // Notification system
  function showNotification(message, type = "success") {
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
      notification.classList.add("show");
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
      notification.classList.remove("show");
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 3000);
  }

  // Add notification styles
  const style = document.createElement("style");
  style.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            border-radius: 4px;
            color: white;
            font-weight: 500;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            background-color: var(--success);
        }

        .notification-error {
            background-color: var(--danger);
        }
    `;
  document.head.appendChild(style);

  // Handle card expansions
  const cardHeaders = document.querySelectorAll(".card-header");
  cardHeaders.forEach((header) => {
    header.addEventListener("click", () => {
      const card = header.closest(".card");
      const content = card.querySelector(".list");

      if (content.style.maxHeight) {
        content.style.maxHeight = null;
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
      }
    });
  });

  // Handle filter and sort buttons
  const filterButtons = document.querySelectorAll(
    ".button:not(.button-success):not(.button-danger)"
  );
  filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const isActive = button.classList.contains("active");
      button.classList.toggle("active");

      if (button.textContent === "Filter") {
        // Add filter logic here
        showNotification(isActive ? "Filters cleared" : "Filters applied");
      } else if (button.textContent === "Sort") {
        // Add sort logic here
        showNotification(isActive ? "Default sorting" : "Sorting applied");
      }
    });
  });
});
