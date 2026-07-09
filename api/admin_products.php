<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/Product.php';
require_once '../app/DB_Connection.php';

$auth = new Auth();

if (!$auth->checkAuth() || !$auth->isAdmin()) {
    echo json_encode([
        'success' => false,
        'error' => 'Доступ запрещен'
    ]);
    exit;
}

$pdo = DB_Connection::getInstance()->getPDO();
$productModel = new Product($pdo);

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

try {
    if ($action === 'get') {
        $id = $data['id'] ?? 0;
        $product = $productModel->getById($id);
        
        if (!$product) {
            echo json_encode(['success' => false, 'error' => 'Товар не найден']);
            exit;
        }
        
        $product['sizes'] = $productModel->getSizes($id);
        $product['images'] = $productModel->getImages($id);
        
        echo json_encode([
            'success' => true,
            'product' => $product
        ]);
        exit;
    }
    
if ($action === 'create') {
    try {    
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            INSERT INTO products (name, description, price, image, category, color, quantity)
            VALUES (?, ?, ?, ?, ?, ?, 0)
        ");
        $result = $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image'],
            $data['category'],
            $data['color']
        ]);
        
        if ($result) {
            $productId = $pdo->lastInsertId();
            error_log('Product created with ID: ' . $productId);

            if (isset($data['sizes']) && is_array($data['sizes'])) {
                foreach ($data['sizes'] as $size) {
                    if ($size['quantity'] > 0) {
                        $stmt = $pdo->prepare("
                            INSERT INTO product_sizes (product_id, size, quantity)
                            VALUES (?, ?, ?)
                        ");
                        $stmt->execute([$productId, $size['size'], $size['quantity']]);
                    }
                }
            }

            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $image) {
                    if ($image && $image !== $data['image']) {
                        $stmt = $pdo->prepare("
                            INSERT INTO product_images (product_id, image_path)
                            VALUES (?, ?)
                        ");
                        $stmt->execute([$productId, $image]);
                    }
                }
            }

            $stmt = $pdo->prepare("
                UPDATE products 
                SET quantity = (
                    SELECT COALESCE(SUM(quantity), 0) 
                    FROM product_sizes 
                    WHERE product_id = ?
                )
                WHERE id = ?
            ");
            $stmt->execute([$productId, $productId]);
            
            $pdo->commit();
            echo json_encode(['success' => true]);
        } else {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => 'Не удалось создать товар']);
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Error creating product: ' . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}
    
    if ($action === 'update') {
        $id = $data['id'];

        $stmt = $pdo->prepare("
            UPDATE products 
            SET name = ?, description = ?, price = ?, image = ?, category = ?, color = ?
            WHERE id = ?
        ");
        $result = $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image'],
            $data['category'],
            $data['color'],
            $id
        ]);
        
        if (isset($data['sizes']) && is_array($data['sizes'])) {
            foreach ($data['sizes'] as $size) {
                if ($size['id']) {
                    $stmt = $pdo->prepare("
                        UPDATE product_sizes 
                        SET quantity = ? 
                        WHERE id = ?
                    ");
                    $stmt->execute([$size['quantity'], $size['id']]);
                } else if ($size['quantity'] > 0) {
                    $stmt = $pdo->prepare("
                        INSERT INTO product_sizes (product_id, size, quantity)
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([$id, $size['size'], $size['quantity']]);
                }
            }
        }
        
        if (isset($data['images']) && is_array($data['images'])) {
            $stmt = $pdo->prepare("
                DELETE FROM product_images 
                WHERE product_id = ?
            ");
            $stmt->execute([$id]);
            
            foreach ($data['images'] as $image) {
                if ($image && $image !== $data['image']) {
                    $stmt = $pdo->prepare("
                        INSERT INTO product_images (product_id, image_path)
                        VALUES (?, ?)
                    ");
                    $stmt->execute([$id, $image]);
                }
            }
        }
        
        $stmt = $pdo->prepare("
            UPDATE products 
            SET quantity = (
                SELECT COALESCE(SUM(quantity), 0) 
                FROM product_sizes 
                WHERE product_id = ?
            )
            WHERE id = ?
        ");
        $stmt->execute([$id, $id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'delete') {
        $id = $data['id'];
        
        $pdo->prepare("DELETE FROM product_sizes WHERE product_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM product_images WHERE product_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM cart WHERE product_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM favorites WHERE product_id = ?")->execute([$id]);
        
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        echo json_encode(['success' => $result]);
        exit;
    }
    
    echo json_encode([
        'success' => false,
        'error' => 'Неизвестное действие'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}