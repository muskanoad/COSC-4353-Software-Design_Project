<?php

$pwdRegister = $_POST["password"];

$options = [
    'cost' => 12
];

$hasedPwd = password_hash($pwdRegister, PASSWORD_BCRYPT, $options);

$pwdLogin = $_POST['password'];
if (password_verify($pwdLogin, $hasedPwd)) {
    echo "There are the same";
} else {
    echo "There are not the same";
}
