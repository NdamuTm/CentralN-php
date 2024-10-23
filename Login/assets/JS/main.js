const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () =>
container.classList.add('right-panel-active'));

signInButton.addEventListener('click', () =>
container.classList.remove('right-panel-active'));

const signUpButtona = document.getElementById('signUpa');
const signInButtonb = document.getElementById('signInb');

signUpButtona.addEventListener('click', () =>
container.classList.add('right-panel-active'));

signInButtonb.addEventListener('click', () =>
container.classList.remove('right-panel-active'));


document.getElementById("forgotPasswordLink").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent the default link behavior
    document.querySelector("form[action='../../user'][method='post']").style.display = "none"; // Hide the login form
    document.getElementById("forgotPasswordForm").style.display = "block"; // Show the forgot password form
});
