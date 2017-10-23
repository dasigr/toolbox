CREATE TABLE recipes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR (400) NOT NULL,
  description TEXT,
  category_id INT,
  chef VARCHAR (255),
  created DATETIME
);

CREATE TABLE categories (
  id INT PRIMARY  KEY AUTO_INCREMENT,
  name VARCHAR (400) NOT NULL
);

INSERT INTO categories (name) VALUES ('Starter');
INSERT INTO categories (name) VALUES ('Main');
INSERT INTO categories (name) VALUES ('Pudding');

INSERT INTO recipes (name, description, category_id, chef, created) VALUES ('Apple Crumble', 'Traditional pudding with crunchy crumble layered over sweet fruit and baked', 3, 'Lorna', NOW());
INSERT INTO recipes (name, description, category_id, chef, created) VALUES ('Fruit Salad', 'Combination of in-season fruits, covered with fruit juice and served chilled', 3, 'Lorna', NOW());
