function validateLogin() {
    //get data
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    //check if email form is empty
    if (email === "") {
        alert("Email is required!");
        return false;
    }
    //check if password form is empty
    if (password === "") {
        alert("Password is required!");
        return false;
    }
    return true;
}