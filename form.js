document.addEventListener('DOMContentLoaded', function() {
  // Focus on the first input field when the form loads
  const firstInput = document.querySelector('input');
  if (firstInput) firstInput.focus();

  // Event listener for the login form
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function(event) {
      const email = document.getElementById('email').value;
      const pid = document.getElementById('pid').value;

      // Check if PID is exactly 6 digits
      if (!/^\d{6}$/.test(pid)) {
        alert('Please enter a valid 6-digit PID number.');
        event.preventDefault(); // Prevent form submission
      }

      // Check if the email is in the correct domain
      const validEmailDomains = ["student.sfit.ac.in", "staff.sfit.ac.in"];
      const emailDomain = email.split('@')[1];

      if (!validEmailDomains.includes(emailDomain)) {
        alert("Email must be a valid 'student.sfit.ac.in' or 'staff.sfit.ac.in' address.");
        event.preventDefault();
      }
    });
  }

  // Event listener for the registration form
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function(event) {
      const email = document.getElementById('email').value;
      const pid = document.getElementById('pid').value;
      const password = document.getElementById('password').value;

      // Check if PID is exactly 6 digits
      if (!/^\d{6}$/.test(pid)) {
        alert('Please enter a valid 6-digit PID number.');
        event.preventDefault();
      }

      // Check if the email is in the correct domain
      const validEmailDomains = ["student.sfit.ac.in", "staff.sfit.ac.in"];
      const emailDomain = email.split('@')[1];

      if (!validEmailDomains.includes(emailDomain)) {
        alert("Email must be a valid 'student.sfit.ac.in' or 'staff.sfit.ac.in' address.");
        event.preventDefault();
      }

      // Check if the password is at least 6 characters long
      if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        event.preventDefault();
      }
    });
  }
});