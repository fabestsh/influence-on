document.addEventListener("DOMContentLoaded", () => {
  // Form elements
  const loginForm = document.getElementById("loginForm");
  const registrationForm = document.getElementById("registrationForm");
  const showSignupLink = document.getElementById("showSignup");
  const signinLink = document.querySelector(".signin-link a");
  const form = document.getElementById("registrationForm");
  const step1 = document.getElementById("step1");
  const step2 = document.getElementById("step2");
  const nextStepBtn = document.getElementById("nextStep");
  const prevStepBtn = document.getElementById("prevStep");
  const roleInput = document.getElementById("roleInput");
  const businessFields = document.getElementById("businessFields");
  const influencerFields = document.getElementById("influencerFields");
  const passwordInput = document.getElementById("password");
  const togglePasswordBtn = document.querySelector(".toggle-password");
  const steps = document.querySelectorAll(".step");

  // Role selection
  const roleButtons = document.querySelectorAll(".role-btn");
  roleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      roleButtons.forEach((btn) => btn.classList.remove("active"));
      button.classList.add("active");
      roleInput.value = button.dataset.role;

      // Update visible fields in step 2
      if (button.dataset.role === "business") {
        businessFields.classList.remove("hidden");
        influencerFields.classList.add("hidden");
      } else {
        businessFields.classList.add("hidden");
        influencerFields.classList.remove("hidden");
      }
    });
  });

  // Password visibility toggle
  togglePasswordBtn.addEventListener("click", () => {
    const type = passwordInput.type === "password" ? "text" : "password";
    passwordInput.type = type;
    togglePasswordBtn.innerHTML =
      type === "password"
        ? '<span class="eye-icon">👁️</span>'
        : '<span class="eye-icon">🔒</span>';
  });

  // Form navigation
  nextStepBtn.addEventListener("click", () => {
    if (validateStep1()) {
      step1.classList.add("hidden");
      step2.classList.remove("hidden");
      steps[1].classList.add("active");
    }
  });

  prevStepBtn.addEventListener("click", () => {
    step2.classList.add("hidden");
    step1.classList.remove("hidden");
    steps[1].classList.remove("active");
  });

  // Form validation
  function validateStep1() {
    const email = document.getElementById("email").value;
    const password = passwordInput.value;
    let isValid = true;

    // Remove existing error messages
    document.querySelectorAll(".error").forEach((error) => error.remove());

    // Email validation
    if (!email || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
      showError("email", "Please enter a valid email address");
      isValid = false;
    }

    // Password validation
    if (!password || password.length < 8) {
      showError("password", "Password must be at least 8 characters long");
      isValid = false;
    }

    return isValid;
  }

  function showError(inputId, message) {
    const input = document.getElementById(inputId);
    const errorDiv = document.createElement("div");
    errorDiv.className = "error";
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
  }

  // Form submission
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (!validateStep1()) {
      step2.classList.add("hidden");
      step1.classList.remove("hidden");
      steps[1].classList.remove("active");
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch("../php/register.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        // Redirect to the appropriate dashboard based on user role
        window.location.href = result.redirect;
      } else {
        showError(
          "email",
          result.message || "Registration failed. Please try again."
        );
      }
    } catch (error) {
      showError("email", "An error occurred. Please try again later.");
    }
  });

  // Toggle between login and registration forms
  showSignupLink.addEventListener("click", (e) => {
    e.preventDefault();
    loginForm.classList.add("hidden");
    registrationForm.classList.remove("hidden");
  });

  signinLink.addEventListener("click", (e) => {
    e.preventDefault();
    registrationForm.classList.add("hidden");
    loginForm.classList.remove("hidden");
  });

  // Login form submission
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(loginForm);

    try {
      const response = await fetch("../php/login.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        window.location.href = result.redirect;
      } else {
        showError(
          "loginEmail",
          result.message || "Login failed. Please try again."
        );
      }
    } catch (error) {
      showError("loginEmail", "An error occurred. Please try again later.");
    }
  });
});
