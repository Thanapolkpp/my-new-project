<?php
namespace App\Services;

class Auth
{
    public static function login($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['role'] = ($user['is_admin'] == 1) ? 'admin' : 'customer';
    }

    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin()
    {
        return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    }

    public static function logout()
    {
        $_SESSION = [];
        session_unset();
    }
}