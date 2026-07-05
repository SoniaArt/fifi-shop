<?php
class Product {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll($sort = 'newest') {
        switch ($sort) {
            case 'price_asc':
                $orderBy = 'price ASC';
                break;
            case 'price_desc':
                $orderBy = 'price DESC';
                break;
            case 'newest':
            default:
                $orderBy = 'created_at DESC';
                break;
        }
        
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY $orderBy");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO products (name, description, price, image, category, color) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image'],
            $data['category'],
            $data['color']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE products 
            SET name = ?, description = ?, price = ?, image = ?, category = ?, color = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image'],
            $data['category'],
            $data['color'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getSortLabel($sort) {
        $labels = [
            'newest' => 'По новизне',
            'price_asc' => 'По возрастанию цены',
            'price_desc' => 'По убыванию цены'
        ];
        return $labels[$sort] ?? 'По новизне';
    }
}
?>