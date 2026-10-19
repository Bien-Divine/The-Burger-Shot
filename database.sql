CREATE DATABASE IF NOT EXISTS burgershot
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE burgershot;

CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  full_name     VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  phone         VARCHAR(30)  DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menu_items (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(120) NOT NULL,
  description  VARCHAR(255) DEFAULT NULL,
  category     ENUM('burger','pizza','shawarma','spring-rolls','parfait') NOT NULL,
  price        DECIMAL(10,2) NOT NULL,
  image_url    VARCHAR(500) DEFAULT NULL,
  is_available TINYINT(1) DEFAULT 1
);

CREATE TABLE orders (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NULL,
  customer_name VARCHAR(100) NOT NULL,
  phone         VARCHAR(30)  NOT NULL,
  address       VARCHAR(255) NOT NULL,
  notes         VARCHAR(255) DEFAULT NULL,
  total         DECIMAL(10,2) NOT NULL,
  status        ENUM('pending','preparing','delivered','cancelled') DEFAULT 'pending',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  order_id     INT NOT NULL,
  menu_item_id INT NULL,
  item_name    VARCHAR(120) NOT NULL,
  unit_price   DECIMAL(10,2) NOT NULL,
  quantity     INT NOT NULL,
  CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_items_menu  FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE SET NULL
);

-- Seed menu
INSERT INTO menu_items (name, description, category, price, image_url) VALUES
('The Classic Shot','Beef patty, cheddar, lettuce, tomato, pickles, house sauce','burger',8.50,'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80'),
('Double Barrel','Two beef patties, double cheddar, bacon, caramelised onion','burger',12.00,'https://images.unsplash.com/photo-1553979459-d2229ba7433b?auto=format&fit=crop&w=800&q=80'),
('Smoky BBQ Stack','Beef patty, BBQ sauce, onion rings, smoked cheddar','burger',11.00,'https://images.unsplash.com/photo-1571091718767-18b5b1457add?auto=format&fit=crop&w=800&q=80'),
('Crispy Chicken Shot','Buttermilk fried chicken, slaw, sriracha mayo','burger',9.50,'https://images.unsplash.com/photo-1606755962773-d324e0a13086?auto=format&fit=crop&w=800&q=80'),
('Mushroom Swiss','Beef patty, sauteed mushrooms, swiss cheese, truffle mayo','burger',10.50,'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80'),
('The Veggie Shot','Black bean patty, avocado, roasted peppers, chipotle mayo','burger',8.00,'https://images.unsplash.com/photo-1520072959219-c595dc870360?auto=format&fit=crop&w=800&q=80'),
('Pepperoni Blast','Pepperoni, mozzarella, tomato base, oregano','pizza',10.00,'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=800&q=80'),
('BBQ Chicken Pizza','Grilled chicken, BBQ drizzle, red onion, mozzarella','pizza',11.50,'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=800&q=80'),
('Margherita Classic','San Marzano tomato, fresh mozzarella, basil','pizza',9.00,'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?auto=format&fit=crop&w=800&q=80'),
('Chicken Shawarma Wrap','Marinated chicken, garlic sauce, pickles, warm flatbread','shawarma',7.00,'https://images.unsplash.com/photo-1529006557810-274b9b2fc783?auto=format&fit=crop&w=800&q=80'),
('Beef Shawarma Wrap','Spiced beef, tahini, tomato, onion, parsley','shawarma',7.50,'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?auto=format&fit=crop&w=800&q=80'),
('Spicy Mixed Shawarma','Chicken + beef, chilli sauce, crunchy slaw','shawarma',8.00,'https://images.unsplash.com/photo-1561651823-34feb02250e4?auto=format&fit=crop&w=800&q=80'),
('Veggie Spring Rolls (4)','Crispy rolls stuffed with cabbage, carrot, glass noodles','spring-rolls',5.00,'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80'),
('Chicken Spring Rolls (4)','Golden rolls with minced chicken and sweet chilli dip','spring-rolls',6.00,'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?auto=format&fit=crop&w=800&q=80'),
('Berry Yoghurt Parfait','Layers of Greek yoghurt, mixed berries, honey granola','parfait',4.50,'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=800&q=80'),
('Mango Granola Parfait','Mango puree, yoghurt, toasted granola, chia seeds','parfait',5.00,'https://images.unsplash.com/photo-1511690743698-d9d85f2fbf38?auto=format&fit=crop&w=800&q=80');