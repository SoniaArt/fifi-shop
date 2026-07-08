<?php
require_once 'DB_Connection.php';
require_once 'DB_Config.php';

class User {
    private $pdo;
    
    public function __construct() {
        $db = DB_Connection::getInstance();
        $this->pdo = $db->getPDO();
    }

    public function register($email, $phone, $password, $first_name, $last_name, $middle_name = null) {
        try {            
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return ['success' => false, 'error' => 'Email уже используется'];
            }

            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE phone = ?");
            $stmt->execute([$phone]);
            if ($stmt->fetch()) {
                return ['success' => false, 'error' => 'Номер телефона уже используется'];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("
                INSERT INTO users (email, phone, password, first_name, last_name, middle_name, role) 
                VALUES (?, ?, ?, ?, ?, ?, 'user')
            ");
        
            $success = $stmt->execute([$email, $phone, $hashed_password, $first_name, $last_name, $middle_name]);
           
            return ['success' => $success];
            
        } catch (PDOException $e) {
            error_log("Ошибка регистрации: " . $e->getMessage());
            return ['success' => false, 'error' => 'Ошибка базы данных. Попробуйте позже.'];
        }
    }

    public function isAdmin($user_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user && isset($user['role']) && $user['role'] === 'admin';
        } catch (PDOException $e) {
            error_log("Ошибка проверки роли: " . $e->getMessage());
            return false;
        }
    }
    
    public function login($login, $password, $remember = false) {
        sleep(2);
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Неверный email/телефон или пароль'];
        }
    
        session_regenerate_id(true);
        
        $nameParts = array_filter([
            $user['last_name'],
            $user['first_name'],
            $user['middle_name']
        ]);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_phone'] = $user['phone'];
        $_SESSION['user_name'] = implode(' ', $nameParts);
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['user_role'] = $user['role'];   
         
        if ($remember) {
            $this->setRememberCookie($user['id']);
        }

        return ['success' => true];
    }

    public function updateProfile($user_id, $first_name, $last_name, $middle_name = null, $email = null, $phone = null) {
        try {
            $fields = [];
            $params = [];
            
            if ($first_name !== null) {
                $fields[] = "first_name = ?";
                $params[] = $first_name;
            }
            
            if ($last_name !== null) {
                $fields[] = "last_name = ?";
                $params[] = $last_name;
            }
            
            if ($middle_name !== null) {
                $fields[] = "middle_name = ?";
                $params[] = $middle_name;
            }
            
            if ($email !== null) {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $user_id]);
                if ($stmt->fetch()) {
                    return ['success' => false, 'error' => 'Email уже используется другим пользователем'];
                }
                $fields[] = "email = ?";
                $params[] = $email;
            }
            
            if ($phone !== null) {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
                $stmt->execute([$phone, $user_id]);
                if ($stmt->fetch()) {
                    return ['success' => false, 'error' => 'Номер телефона уже используется другим пользователем'];
                }
                $fields[] = "phone = ?";
                $params[] = $phone;
            }
            
            if (empty($fields)) {
                return ['success' => false, 'error' => 'Нет данных для обновления'];
            }
            
            $params[] = $user_id;
            
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            $nameParts = array_filter([
                $first_name,
                $middle_name,
                $last_name
            ]);

            $_SESSION['user_name'] = implode(' ', $nameParts);
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;

            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Ошибка обновления профиля: " . $e->getMessage());
            return ['success' => false, 'error' => 'Ошибка обновления профиля'];
        }
    }

    public function getUserById($user_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);        
            return $user ?: null;
        } catch (PDOException $e) {
            error_log("Ошибка получения пользователя по ID: " . $e->getMessage());
            return null;
        }
    }
    
    public function getUserByResetToken($token) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, email, phone, first_name, last_name
                FROM users
                WHERE reset_token = ? 
                AND reset_expires > CURRENT_TIMESTAMP
            ");
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Ошибка получения пользователя по токену: " . $e->getMessage());
            return null;
        }
    }

    public function requestPasswordReset($login) {
        try {
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
            $stmt->execute([$login, $login]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return ['success' => false, 'error' => 'Пользователь с таким email или телефоном не найден'];
            }
            
            $token = bin2hex(random_bytes(16));

            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET reset_token = ?, reset_expires = CURRENT_TIMESTAMP + INTERVAL '1 hour'
                WHERE id = ?
            ");
            
            $stmt->execute([$token, $user['id']]);
            
            $link = "http://localhost/FIFI/pages/new_password.php?token=" . $token;

            return [
                'success' => true,
                'link' => $link
            ];
            
        } catch (PDOException $e) {
            error_log("Ошибка восстановления пароля: " . $e->getMessage());
            return ['success' => false, 'error' => 'Ошибка при восстановлении пароля'];
        }
    }

    public function resetPassword($token, $new_password) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, password FROM users
                WHERE reset_token = ? 
                AND reset_expires > CURRENT_TIMESTAMP
            ");
            $stmt->execute([$token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'Недействительная или просроченная ссылка'
                ];
            }

            if (password_verify($new_password, $user['password'])) {
                return [
                    'success' => false,
                    'error' => 'Новый пароль должен отличаться от старого'
                ];
            }

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET password = ?, reset_token = NULL, reset_expires = NULL 
                WHERE id = ?
            ");
            
            $stmt->execute([$hashed_password, $user['id']]);
            return ['success' => true];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => 'Ошибка при смене пароля'
            ];
        }
    }

    public function changePassword($user_id, $current_password, $new_password) {
        try {
            $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if (!$user || !password_verify($current_password, $user['password'])) {
                return ['success' => false, 'error' => 'Текущий пароль неверен'];
            }

            if (password_verify($new_password, $user['password'])) {
                return ['success' => false, 'error' => 'Новый пароль должен отличаться от текущего'];
            }            
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Ошибка смены пароля: " . $e->getMessage());
            return ['success' => false, 'error' => 'Ошибка смены пароля'];
        }
    }

    private function setRememberCookie($user_id) {
        $hash = md5(uniqid());
        $expires = time() + (DB_Config::REMEMBER_DAYS * 24 * 60 * 60);
        
        $stmt = $this->pdo->prepare("UPDATE users SET hash = ? WHERE id = ?");
        $stmt->execute([$hash, $user_id]);
        
        setcookie('id', $user_id, $expires, '/');
        setcookie('hash', $hash, $expires, '/');
    }

    public function checkEmailExists($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    public function checkPhoneExists($phone) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        return (bool) $stmt->fetch();
    }
}
?>