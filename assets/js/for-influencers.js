// Mobile Navigation
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

// Initial check for elements in view
checkScroll();

// Check on scroll
window.addEventListener("scroll", checkScroll);

document.addEventListener("DOMContentLoaded", function () {
  // Mobile Navigation Toggle
  const mobileNavToggle = document.querySelector(".mobile-nav-toggle");
  const mobileNav = document.querySelector(".mobile-nav");
  const mobileNavOverlay = document.querySelector(".mobile-nav-overlay");
  const body = document.body;

  function toggleMobileNav() {
    mobileNavToggle.classList.toggle("active");
    mobileNav.classList.toggle("active");
    mobileNavOverlay.classList.toggle("active");
    body.style.overflow = mobileNav.classList.contains("active")
      ? "hidden"
      : "";
  }

  mobileNavToggle.addEventListener("click", toggleMobileNav);
  mobileNavOverlay.addEventListener("click", toggleMobileNav);

  // Close mobile nav when clicking a link
  const mobileNavLinks = document.querySelectorAll(".mobile-nav .nav-links a");
  mobileNavLinks.forEach((link) => {
    link.addEventListener("click", () => {
      if (mobileNav.classList.contains("active")) {
        toggleMobileNav();
      }
    });
  });

  // Brand Logos Hover Effect
  const brandLogos = document.querySelectorAll(".brand-logo img");
  brandLogos.forEach((logo) => {
    logo.addEventListener("mouseenter", () => {
      logo.style.filter = "grayscale(0)";
      logo.style.opacity = "1";
      logo.style.transition = "all 0.3s ease";
    });
    logo.addEventListener("mouseleave", () => {
      logo.style.filter = "grayscale(1)";
      logo.style.opacity = "0.7";
    });
  });

  // Scroll Animations
  function revealOnScroll() {
    const animatedEls = document.querySelectorAll(".animated, .section-reveal");
    const staggerParents = document.querySelectorAll(".stagger-parent");

    const observer = new IntersectionObserver(
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

  // Initialize scroll animations
  revealOnScroll();

  // Smooth scroll for navigation links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
        // Close mobile nav if open
        if (mobileNav.classList.contains("active")) {
          toggleMobileNav();
        }
      }
    });
  });
});
