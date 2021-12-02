
let passwdOK = "#aaFFaa";
let passwdNG = "#FFaaaa";

function checkLogin() {
    let login = document.getElementById("loginNameInput").value;
    if (login.length > 3) {
        setInputOK(loginNameInput);
        return true;
    } else {
        setInputNG(loginNameInput);
        return false;
    }
}

function checkPassword() {
    let passwd = document.getElementById("loginPassInput").value;
    let passwdAgain = document.getElementById("loginPassAgainInput").value;

    if (passwd.length >= 8) {
        setInputOK(loginPassInput);
        if (passwd === passwdAgain) {
            setInputOK(loginPassAgainInput);
            return true;
        } else {
            setInputNG(loginPassAgainInput);
            return false;
        }
    } else {
        setInputNG(loginPassInput);
    }
}
function checkPassword2() {
    let passwd = document.getElementById("loginPassInput").value;
    let passwdAgain = document.getElementById("loginPassAgainInput").value;


    if (passwd.length >= 8) {
        loginPassInput.style.backgroundColor = passwdOK;
        loginPassInput.classList.remove('is-invalid');
        loginPassInput.classList.add('is-valid')
        if (passwd === passwdAgain) {
            loginPassAgainInput.style.backgroundColor = passwdOK;
            loginPassAgainInput.classList.remove('is-invalid');
            loginPassAgainInput.classList.add('is-valid');
            return true;
        } else {
            loginPassAgainInput.style.backgroundColor = passwdNG;
            loginPassAgainInput.classList.remove('is-valid');
            loginPassAgainInput.classList.add('is-invalid');
            return false;
        }
    } else {
        loginPassInput.style.backgroundColor = passwdNG;
        loginPassInput.classList.remove('is-valid');
        loginPassInput.classList.add('is-invalid');
    }
}

function emptyPass() {
    if (document.getElementById("loginPassInput").value.length > 0) {
        setInputOK(loginPassInput);
        return false;
    } else {
        setInputNG(loginPassInput);
        return true;
    }
}
function setInputOK(input) {
    input.style.backgroundColor = passwdOK;
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
}

function setInputNG(input) {
    input.style.backgroundColor = passwdNG;
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
}