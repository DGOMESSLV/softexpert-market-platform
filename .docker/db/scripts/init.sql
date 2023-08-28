CREATE DATABASE market;

\c market;

CREATE SEQUENCE products_id_sequence;

CREATE TABLE products (
    id INT NOT NULL PRIMARY KEY DEFAULT nextval('products_id_sequence'),
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    photo TEXT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    tax_rate DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE SEQUENCE sales_id_sequence;
CREATE TYPE pf_document AS ENUM('card', 'physical_money', 'pix', 'fidelity_card');

CREATE TABLE sales (
    id INT NOT NULL PRIMARY KEY DEFAULT nextval('sales_id_sequence'),
    client_document CHAR(11) DEFAULT NULL,
    products JSON NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    tax_rate_total DECIMAL(10, 2) NOT NULL,
    payment_method pf_document NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);