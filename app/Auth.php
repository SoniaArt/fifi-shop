<?php
require_once 'DB_Connection.php';
require_once 'DB_Config.php';
require_once 'User.php';

class Auth {
    private $pdo;
    
    public function __construct() {
        $db = DB_Connection::getInstance();
        $this->pdo = $db->getPDO();
        $this->startSession();
    }
    
    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(DB_Config::SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => DB_Config::SESSION_LIFETIME,
                'path' => '/',
                'domain' => '',
                'secure' => false, 
                'httponly' => true,
                'samesite' => 'Strict'
            ]);      
            session_start();
        
            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
            }
        }
    }
    
    public function checkAuth() {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            return true;
        }
        
        if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
            return $this->checkRememberCookie($_COOKIE['id'], $_COOKIE['hash']);
        }
        
        return false;
    }
    
    private function checkRememberCookie($user_id, $hash) {
        $stmt = $this->pdo->prepare("
            SELECT id, email, phone, first_name, last_name, middle_name, role
            FROM users 
            WHERE id = ? AND hash = ?
        ");
        $stmt->execute([$user_id, $hash]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $this->clearRememberCookies();
            return false;
        }
        
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_phone'] = $user['phone'];
        
        $nameParts = array_filter([
            $user['first_name'],
            $user['middle_name'],
            $user['last_name']
        ]);
        $_SESSION['user_name'] = implode(' ', $nameParts);
        
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['user_role'] = $user['role'];
        
        return true;
    }

    public function isAdmin() {
        if (!$this->checkAuth()) return false;
        return $_SESSION['user_role'] === 'admin';
    }

    public function requireAdmin($redirect_to = '/FIFI/index.php') {
        if (!$this->isAdmin()) {
            header("Location: $redirect_to");
            exit;
        }
    }
    
    public function requireAuth($redirect_to = '/FIFI/index.php') {
        if (!$this->checkAuth()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];        
            header("Location: $redirect_to");
            exit;
        }
    }
    
    public function logout() {
        if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
            $this->clearRememberCookies();

            if (isset($_SESSION['user_id'])) {
                $stmt = $this->pdo->prepare("UPDATE users SET hash = '' WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
            }
        }

        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }       
        session_destroy();
    }

    private function clearRememberCookies() {
        setcookie('id', '', time() - 3600, '/', '', false, true);
        setcookie('hash', '', time() - 3600, '/', '', false, true);
    }

    public function getCurrentUser() {
        if (!$this->checkAuth()) {
            return null;
        }
        
        $user_class = new User();
        return $user_class->getUserById($_SESSION['user_id']);
    }
}
?>