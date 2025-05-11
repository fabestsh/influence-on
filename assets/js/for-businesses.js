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

// Testimonials Slider
const testimonialsSlider = document.querySelector(".testimonials-slider");
const prevBtn = document.querySelector(".prev-btn");
const nextBtn = document.querySelector(".next-btn");
const testimonialCards = document.querySelectorAll(".testimonial-card");
let currentIndex = 0;

function updateSlider() {
  const offset = -currentIndex * 100;
  testimonialsSlider.style.transform = `translateX(${offset}%)`;
}

function showNextTestimonial() {
  currentIndex = (currentIndex + 1) % testimonialCards.length;
  updateSlider();
}

function showPrevTestimonial() {
  currentIndex =
    (currentIndex - 1 + testimonialCards.length) % testimonialCards.length;
  updateSlider();
}

nextBtn.addEventListener("click", showNextTestimonial);
prevBtn.addEventListener("click", showPrevTestimonial);

// Auto-advance testimonials
let testimonialInterval = setInterval(showNextTestimonial, 5000);

// Pause auto-advance on hover
testimonialsSlider.addEventListener("mouseenter", () => {
  clearInterval(testimonialInterval);
});

testimonialsSlider.addEventListener("mouseleave", () => {
  testimonialInterval = setInterval(showNextTestimonial, 5000);
});

// Scroll Animations
const animatedSections = document.querySelectorAll(".animated");

function checkScroll() {
  animatedSections.forEach((section) => {
    const sectionTop = section.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;

    if (sectionTop < windowHeight * 0.75) {
      section.classList.add("section-reveal");
    }
  });
}

// Initial check for elements in view
checkScroll();

// Check on scroll
window.addEventListener("scroll", checkScroll);

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

  // Testimonials Slider
  const testimonialsSlider = document.querySelector(".testimonials-slider");
  const prevBtn = document.querySelector(".prev-btn");
  const nextBtn = document.querySelector(".next-btn");
  const testimonialCards = document.querySelectorAll(".testimonial-card");
  let currentIndex = 0;

  function updateSlider() {
    const offset = -currentIndex * 100;
    testimonialsSlider.style.transform = `translateX(${offset}%)`;
  }

  function showNextTestimonial() {
    currentIndex = (currentIndex + 1) % testimonialCards.length;
    updateSlider();
  }

  function showPrevTestimonial() {
    currentIndex =
      (currentIndex - 1 + testimonialCards.length) % testimonialCards.length;
    updateSlider();
  }

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", showPrevTestimonial);
    nextBtn.addEventListener("click", showNextTestimonial);
  }

  // Auto-advance testimonials
  let testimonialInterval = setInterval(showNextTestimonial, 5000);

  // Pause auto-advance on hover
  if (testimonialsSlider) {
    testimonialsSlider.addEventListener("mouseenter", () => {
      clearInterval(testimonialInterval);
    });

    testimonialsSlider.addEventListener("mouseleave", () => {
      testimonialInterval = setInterval(showNextTestimonial, 5000);
    });
  }

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
