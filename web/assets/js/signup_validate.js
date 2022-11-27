function signup_validate(){
    var email = $('#signUpForm').find('input[name="email"]').val();
    var password = $('#signUpForm').find('input[name="password"]').val();

    var message = [];
    if (!email) message.push('\nemail');
    if (!password) message.push('\nPassword');


    if (message.length > 0){
        alert('Please fill:\n'+message);
        return false;
    }

    if (!validateEmail(email)) {
        alert('Fill email correct! Available: a-z 0-9 dot minus @');
        return false;
    }
}

function validateEmail(email) {
    if(email.indexOf('+') + 1) return false;
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}