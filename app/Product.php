<?php
class Product {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll( $sort = 'newest', $categories = [], $colors = [], $sizes = [],  $search='') {
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

        $sql = "
            SELECT p.*
            FROM products p
            WHERE 1=1
        ";

        $params = [];

        if (!empty($categories)) {
            $placeholders = [];

            foreach ($categories as $category) {
                $placeholders[] = '?';
                $params[] = $category;
            }

            $sql .= "
                AND p.category IN (" . implode(',', $placeholders) . ")
            ";
        }

        if (!empty($colors)) {
            $placeholders = [];

            foreach ($colors as $color) {
                $placeholders[] = '?';
                $params[] = $color;
            }

            $sql .= "
                AND p.color IN (" . implode(',', $placeholders) . ")
            ";
        }
        
        if (!empty($sizes)) {

            $placeholders = [];

            foreach ($sizes as $size) {
                $placeholders[] = '?';
                $params[] = $size;
            }
            
        $sql .= "
            AND EXISTS (
                SELECT 1
                FROM product_sizes ps
                WHERE ps.product_id = p.id
                AND ps.size IN (" . implode(',', $placeholders) . ")
            )
        ";
        }    

        if ($search !== '') {
            $sql .= "
                AND (
                    LOWER(p.name) LIKE LOWER(?)
                    OR LOWER(p.color) LIKE LOWER(?)
                )
            ";

            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
        }      

        $sql .= "
            ORDER BY
                CASE
                    WHEN p.quantity > 0 THEN 0
                    ELSE 1
                END,
                $orderBy
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $products = $stmt->fetchAll();

        foreach ($products as &$product) {
            $sizes = $this->getSizes($product['id']);
            $product['sizes'] = [];

            foreach ($sizes as $size) {
                $product['sizes'][] = $size['size'];
            }
        }

        return $products;
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getCategories() {
        $stmt = $this->pdo->query("
            SELECT DISTINCT category 
            FROM products 
            WHERE category IS NOT NULL AND category != '' 
            ORDER BY category
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getColors() {
        $stmt = $this->pdo->query("
            SELECT DISTINCT color 
            FROM products 
            WHERE color IS NOT NULL AND color != '' 
            ORDER BY color
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getImages($productId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM product_images 
            WHERE product_id = ? 
            ORDER BY id
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getSizes($productId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM product_sizes 
            WHERE product_id = ? 
            ORDER BY 
                CASE size 
                    WHEN 'XS' THEN 1
                    WHEN 'S' THEN 2
                    WHEN 'M' THEN 3
                    WHEN 'L' THEN 4
                    WHEN 'XL' THEN 5
                    ELSE 6
                END
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSortLabel($sort) {
        $labels = [
            'newest' => 'По новизне',
            'price_asc' => 'По возрастанию цены',
            'price_desc' => 'По убыванию цены'
        ];
        return $labels[$sort] ?? 'По новизне';
    }

    public function getAllSizes() {
    return ['XS', 'S', 'M', 'L', 'XL', 'One Size'];
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
}
?>