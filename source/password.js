
document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.querySelector("#password");
    const cpasswordInput = document.querySelector("#cpassword");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClose = document.getElementById("eyeClose");
    
eyeOpen.addEventListener("click", () => {
    passwordInput.type = "text";
    cpasswordInput.type = "text";
    eyeOpen.style.display = "none";
    eyeClose.style.display = "block";
  });
  
  eyeClose.addEventListener("click", () => {
    passwordInput.type = "password";
    cpasswordInput.type = "password";
    eyeClose.style.display = "none";
    eyeOpen.style.display = "block";
  });
  
    // Add event listeners and logic related to password fields here
  });
  

