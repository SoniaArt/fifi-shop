<?php
require_once 'DB_Connection.php';

class Basket {
    private $pdo;

    public function __construct() {
        $db = DB_Connection::getInstance();
        $this->pdo = $db->getPDO();
    }

    public function getByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT c.id, c.product_id, c.size_id, c.quantity, 
                p.name, p.price, p.image, p.quantity as product_quantity,
                ps.size as size_name, ps.quantity as total_quantity
            FROM cart c
            JOIN products p ON p.id = c.product_id
            JOIN product_sizes ps ON ps.id = c.size_id
            WHERE c.user_id = ?
            ORDER BY c.created_at DESC
        ");

        $stmt->execute([$userId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($items as &$item) {
            $item['available_quantity'] = $item['total_quantity'] - $item['quantity'];
            $item['max_quantity'] = $item['total_quantity']; 
        }
        
        return $items;
    }

    public function add($userId, $productId, $sizeId) {
        $stmt = $this->pdo->prepare("
            SELECT quantity as total_quantity
            FROM product_sizes
            WHERE id = ?
        ");
        $stmt->execute([$sizeId]);
        $sizeData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sizeData || $sizeData['total_quantity'] <= 0) {
            return [
                'success' => false,
                'error' => 'Товара нет в наличии'
            ];
        }

        $stmt = $this->pdo->prepare("
            SELECT quantity FROM cart
            WHERE user_id = ?
            AND product_id = ?
            AND size_id = ?
        ");
        $stmt->execute([$userId, $productId, $sizeId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $currentQuantity = $existing ? $existing['quantity'] : 0;

        if ($currentQuantity >= $sizeData['total_quantity']) {
            return [
                'success' => false,
                'error' => 'Нет доступного количества'
            ];
        }

        if ($existing) {
            $stmt = $this->pdo->prepare("
                UPDATE cart
                SET quantity = quantity + 1
                WHERE user_id = ?
                AND product_id = ?
                AND size_id = ?
            ");
            $stmt->execute([$userId, $productId, $sizeId]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO cart (user_id, product_id, size_id, quantity)
                VALUES (?, ?, ?, 1)
            ");
            $stmt->execute([$userId, $productId, $sizeId]);
        }

        return [
            'success' => true
        ];
    }

    public function remove($userId, $productId, $sizeId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT quantity FROM cart
                WHERE user_id = ?
                AND product_id = ?
                AND size_id = ?
            ");
            $stmt->execute([$userId, $productId, $sizeId]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$current) {
                return [
                    'success' => true
                ];
            }

            if ($current['quantity'] > 1) {
                $stmt = $this->pdo->prepare("
                    UPDATE cart
                    SET quantity = quantity - 1
                    WHERE user_id = ?
                    AND product_id = ?
                    AND size_id = ?
                ");
                $stmt->execute([$userId, $productId, $sizeId]);
            } else {
                $stmt = $this->pdo->prepare("
                    DELETE FROM cart
                    WHERE user_id = ?
                    AND product_id = ?
                    AND size_id = ?
                ");
                $stmt->execute([$userId, $productId, $sizeId]);
            }

            return [
                'success' => true
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => 'Ошибка базы данных: ' . $e->getMessage()
            ];
        }
    }
}