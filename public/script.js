
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