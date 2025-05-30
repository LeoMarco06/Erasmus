function checkData() {
    var username = document.getElementById("username").value;
    var firstName = document.getElementById("name").value;
    var surname = document.getElementById("surname").value;
    var email = document.getElementById("email").value;
    var date = document.getElementById("date").value;
    var pwd = document.getElementById("Passwd").value;
    var nameError = `<input type="text" autocomplete="off" aria-label="First name" class="form-control" name="name" placeholder="Name" id="name" required>`;
    var userNameError = `<div class="input-group" style="margin-bottom:0;"><input type="text" autocomplete="off" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1"  name="UserName" id="username" novalidate required></div>`;
    var surnameError = `<input type="text" autocomplete="off" aria-label="Last name" class="form-control" name="surname" placeholder="Surname" id="surname" required>`;
    var pwdError = `<label for="Passwd" class="form-label" style="text-align:left; margin-top:1vh;" id="pwd">Password</label><input type="password" id="Passwd" class="form-control" aria-describedby="passwordHelpBlock" name="Passwd" novalidate required>`;
    var emailError = `<span class="input-group-text" id="basic-addon2">@</span><input type="text" autocomplete="off" class="form-control" placeholder="Email" aria-label="Username" aria-describedby="basic-addon1"  name="email" id="email" novalidate required>`;



    // Length check
    if ((username.length > 30) || (username.length < 6)) {
        document.getElementById("userName").innerHTML = userNameError;
    }
    if ((firstName.length > 30) || (firstName.length < 3)) {
        document.getElementById("divName").innerHTML = nameError;
    }
    if ((surname.length > 30) || (surname.length < 3)) {
        document.getElementById("divSurname").innerHTML = surnameError;
    }
    if ((pwd.length > 20) || (pwd.length < 8)) {
        document.getElementById("divPwd").innerHTML = pwdError;
    }
    if (email.length > 64) {
        document.getElementById("divEmail").innerHTML = emailerror;
    }

    // Special char check
    if (containsSpecialCharacter(username,0)) {
        document.getElementById("userName").innerHTML = userNameError;
    }
    if (containsSpecialCharacter(firstName,1)) {
        document.getElementById("divName").innerHTML = nameError;
    }
    if (containsSpecialCharacter(surname,1)) {
        document.getElementById("divSurname").innerHTML = surnameError;
    }
    if (containsSpecialCharacter(pwd,3)) {
        document.getElementById("divPwd").innerHTML = pwdError;
    }
    if (!(containsSpecialCharacter(email,2)) || (email.indexOf(".") == -1) || (email.indexOf("@") == -1) ) {
        document.getElementById("divEmail").innerHTML = emailError;
    }
    
    // Numbers check
    if (containNumbers(firstName)) {
        document.getElementById("divName").innerHTML = nameError;
    }
    if (containNumbers(surname)) {
        document.getElementById("divSurname").innerHTML = surnameError;
    }

    return true;
}

function containsSpecialCharacter(str, type) {
    let specialChars;
    if(type == 0){
        specialChars = `/[!@#$%^&*()+\=\[\]{};':"\\|,.<>\/?]+/`; // Special characters not allowed for username
    }
    else if(type == 1){
        specialChars = `/[!@#$%^&*_()-+\=\[\]{};':"\\|,.<>\/?]+/`; // Special characters not allowed for name,surname
    }
    else if(type == 2){
        specialChars = `/[!@#$%^&*()+\=\[\]{};':"\\|,<>\/?]+/`; // Special characters not allowed for email
    }
    else if(type == 3){
        specialChars = `/[#$%^&*()+\=\[\]{};':"\\|,<>\/]+/`; // Special characters not allowed for password
    }
    for (var i = 0; i < str.length; i++) {
        if (specialChars.indexOf(str[i]) !== -1) {
            return true;
        }
    }
    return false;
}

function containNumbers(str){
    numbers = `1234567890`;
    for (var i = 0; i < str.length; i++) {
        if (numbers.indexOf(str[i]) !== -1) {
            return true;
        }
    }
    return false;
}