document.addEventListener("DOMContentLoaded", function () {
  const filterBtn = document.querySelector(".search-filter .btn-primary");
  if (filterBtn) {
    filterBtn.addEventListener("click", function () {
      alert("Search functionality coming soon!");
    });
  }
  revealOnScroll();

  // Hamburger menu logic
  const navToggle = document.querySelector(".mobile-nav-toggle");
  const mobileNav = document.querySelector(".mobile-nav");
  const navOverlay = document.querySelector(".mobile-nav-overlay");
  const navLinks = document.querySelectorAll(".mobile-nav a");

  function closeMobileNav() {
    navToggle.classList.remove("open");
    mobileNav.classList.remove("open");
    if (navOverlay) navOverlay.style.display = "none";
    document.body.style.overflow = "";
  }
  function openMobileNav() {
    navToggle.classList.add("open");
    mobileNav.classList.add("open");
    if (navOverlay) navOverlay.style.display = "block";
    document.body.style.overflow = "hidden";
  }
  if (navToggle && mobileNav) {
    navToggle.addEventListener("click", function () {
      if (mobileNav.classList.contains("open")) {
        closeMobileNav();
      } else {
        openMobileNav();
      }
    });
  }
  if (navOverlay) {
    navOverlay.addEventListener("click", closeMobileNav);
  }
  navLinks.forEach((link) => {
    link.addEventListener("click", closeMobileNav);
  });
});

// Animation: Reveal on scroll
function revealOnScroll() {
  const animatedEls = document.querySelectorAll(".animated, .section-reveal");
  const staggerParents = document.querySelectorAll(".stagger-parent");

  const observer = new window.IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          // Stagger children if parent
          if (entry.target.classList.contains("stagger-parent")) {
            const children = entry.target.querySelectorAll(".stagger-child");
            children.forEach((child, idx) => {
              setTimeout(() => child.classList.add("visible"), idx * 120);
            });
          }
          obs.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.18 }
  );

  animatedEls.forEach((el) => observer.observe(el));
  staggerParents.forEach((parent) => observer.observe(parent));
}
