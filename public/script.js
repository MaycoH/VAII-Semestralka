/** Hex kód farby pre OK políčko (v tvare #RRGGBB) */
let passwdOK = "#aaFFaa";
/** Hex kód farby pre NG políčko (v tvare #RRGGBB) */
let passwdNG = "#FFaaaa";

/** Funkcia pre kontrolu dĺžky prihlasovacieho mena.
 * @returns {boolean} true, ak prihlasovacie meno má viac ako 3 znaky, ináč false. */
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

/** Funkcia pre kontrolu oboch hesiel pri registrácii. Funkcia kontroluje heslo na jeho dĺžku ( >= 8 znakov)
 *  a zhodnosť oboch hesiel.
 * @returns {boolean} true, ak heslo má >= 8 znakov a zopakované heslo je rovnaké, ináč false. */
function checkPasswords() {
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
        return false;
    }
}

// function emptyPass2(input) {
//     if (document.getElementById(input).value.length > 0) {
//         setInputOK(input);
//         return false;
//     } else {
//         setInputNG(input);
//         return true;
//     }
// }
/** Funkcia pre kontrolu platnosti zadaného hesla (či bolo zadané)
 * @param input input, ktorý sa má kontrolovať
 * @returns {boolean} true, ak input niečo obsahuje, ináč false. */
function emptyPass(input) {
    let el = document.getElementById(input);
    if (el.value.length > 0) {
        setInputOK(el);
        return false;
    } else {
        setInputNG(el);
        return true;
    }
}

/** Funkcia pre presmerovanie na hlavnú stránku */
function redirectToHome() {
    window.location.replace('?c=home');
}

/** Funkcia pre nastavenie textInputu ako OK
 * @param input názov inputu, ktorý sa má nastaviť ako OK */
function setInputOK(input) {
    input.style.backgroundColor = passwdOK;
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
}

/** FUnkcia pre nastavenie textInputu ako NG
 * @param input názov inputu, ktorý sa má nastaviť ako NG */
function setInputNG(input) {
    input.style.backgroundColor = passwdNG;
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
}