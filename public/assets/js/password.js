let eyeopen = document.querySelector(".eye-open");
let eyeoff = document.querySelector(".eye-off");
let passwordField = document.querySelector("input[type=password]");

eyeopen.addEventListener("click", () => {
    eyeopen.style.display = "none";
    eyeoff.style.display = "block";
    passwordField.type = "text";
  });
  
eyeoff.addEventListener("click", () => {
    eyeoff.style.display = "none";
    eyeopen.style.display = "block";
    passwordField.type = "password";
  });