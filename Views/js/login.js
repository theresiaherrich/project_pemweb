function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

document.addEventListener("DOMContentLoaded", () => {
    const registerForm = document.getElementById("registerForm");

    if (registerForm) {
        registerForm.addEventListener("submit", (e) => {
            const pass = document.getElementById("regPassword").value;
            const confirm = document.getElementById("regConfirm").value;
            if (pass !== confirm) {
                e.preventDefault(); 
                alert("Password tidak sama!");
            }
        });
    }
});
