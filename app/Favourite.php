<?php
require_once 'DB_Connection.php';

class Favourite {
    private $pdo;

    public function __construct() {
        $db = DB_Connection::getInstance();
        $this->pdo = $db->getPDO();
    }

    public function getByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT f.id, p.id AS product_id, p.name, p.price, p.image, p.quantity
            FROM favorites f
            JOIN products p ON p.id = f.product_id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($userId, $productId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO favorites(user_id, product_id)
            VALUES (?, ?)
            ON CONFLICT (user_id, product_id)
            DO NOTHING
        ");

        $stmt->execute([
            $userId,
            $productId
        ]);

        return [
            'success'=>true
        ];
    }

    public function remove($userId, $productId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM favorites
            WHERE user_id = ?
            AND product_id = ?
        ");

        $stmt->execute([
            $userId,
            $productId
        ]);

        return [
            'success'=>true
        ];
    }

    public function exists($userId, $productId) {
        $stmt = $this->pdo->prepare("
            SELECT id
            FROM favorites
            WHERE user_id = ?
            AND product_id = ?
        ");

        $stmt->execute([
            $userId,
            $productId
        ]);

        return (bool)$stmt->fetch();
    }

    public function getIds($userId){
        $stmt = $this->pdo->prepare("
            SELECT product_id
            FROM favorites
            WHERE user_id = ?
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}