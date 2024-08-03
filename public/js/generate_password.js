

function generatePassword() {
     // Password length
    var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; // Characters to include in password
    var password = "";
    for (var i = 0; i < 8; i++) {
        var randomChar = charset.charAt(Math.floor(Math.random() * charset.length));
        password += randomChar;
    }
    document.getElementById("password").value = password;
}


document.getElementById('password_visibility').addEventListener('change',function(){
    const passwordField = document.getElementById('password');
    if (this.checked) {
        passwordField.type = 'text';
    } else {
        passwordField.type = 'password';
    }
});