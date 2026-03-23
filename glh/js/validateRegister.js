function validateRegister() {
    //get data
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let address = document.getElementById("address").value;

    //check if the name form is empty
    if (name === "") {
        alert("Name is required!");
        return false; //returning false stops the form from submitting
    }
    if (email === "") {
        alert("Email is required!");
        return false;
    }
    //check the email format by using an email regex
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!email.match(regex)) {
        alert("Enter a valid email!");
        return false;
    }
    //check if password form is empty
    if (password === "") {
        alert("Password is required");
        return false;
    }
    //check password length
    if (password.length < 6) {
        alert("Password needs to be at least 6 characters long!");
        return false;
    }
    //check if the address form is empty
    if (address === "") {
        alert("Address is required!");
        return false;
    }
    return true;
}