var allowSubmit = false;

function capcha_filled() {
    allowSubmit = true;
}

function capcha_expired() {
    allowSubmit = false;
}

function check_if_capcha_is_filled() {
    console.log(allowSubmit);
    if(allowSubmit) return true;

    alert('Captcha!');
    return false;
}