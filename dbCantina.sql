-- Exclui o banco de dados existente, se houver
DROP DATABASE IF EXISTS dbcantina;

-- Cria um novo banco de dados
CREATE DATABASE dbcantina;

-- Seleciona o banco de dados
USE dbcantina;

-- Cria a tabela de usuários com distinção entre tipos de usuários (admin e comum)
CREATE TABLE tbUsuarios (
    codUsu INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telCelular VARCHAR(15) NOT NULL,
    senha VARCHAR(255) NOT NULL, -- Hash da senha
    salt VARCHAR(32) NOT NULL, -- Salt para proteger a senha
    tipoUsuario ENUM('usuario', 'admin') DEFAULT 'usuario', -- Diferencia usuários comuns de administradores
    status ENUM('ativo', 'inativo') DEFAULT 'ativo', -- Define se o usuário está ativo ou inativo
    dataCadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data de cadastro do usuário
    foto VARCHAR(255) DEFAULT NULL, -- Coluna para armazenar o caminho da foto do usuário
    PRIMARY KEY (codUsu)
);

-- Insere o primeiro administrador manualmente
INSERT INTO tbUsuarios (nome, email, telCelular, senha, salt, tipoUsuario) 
VALUES ('Administrador', 'admin@cantina.com', '11999999999', 'ae708c50e9845c894c2c091be8b0479f3ad88861afacfe9761c2d70755b3697e', '66612b4187aa08c278be2fd745bc675e', 'admin');

-- Cria a tabela de produtos
CREATE TABLE tbProdutos (
    codProd INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(25) NOT NULL,
    descricao VARCHAR(100),
    quantidade INT,
    valor DECIMAL(9,2),
    validade DATE,
    dataEntrada DATE,
    horaEntrada TIME,
    PRIMARY KEY (codProd)
);

-- Cria a tabela de vendas com o campo metodoPagamento
CREATE TABLE tbVendas (
    codVenda INT NOT NULL AUTO_INCREMENT,
    dataVenda DATE,
    horaVenda TIME,
    quantidade INT,
    codUsu INT NOT NULL,
    codProd INT NOT NULL,
    metodoPagamento VARCHAR(20), -- Campo para armazenar o método de pagamento
    status VARCHAR(20) DEFAULT 'pendente',
    PRIMARY KEY (codVenda),
    FOREIGN KEY (codUsu) REFERENCES tbUsuarios(codUsu),
    FOREIGN KEY (codProd) REFERENCES tbProdutos(codProd)
);

-- Insere dados de exemplo na tabela tbProdutos
INSERT INTO tbProdutos (nome, descricao, quantidade, valor, validade, dataEntrada, horaEntrada) VALUES
('Misto Quente', 'Sanduíche de presunto e queijo, bem quentinho.', 50, 8.50, '2025-12-31', '2024-09-16', '08:00:00'),
('Coxinha', 'Coxinha de frango crocante, recheada e deliciosa.', 40, 6.00, '2025-11-30', '2024-09-16', '09:15:00'),
('Empada de Palmito', 'Empada recheada com palmito e temperos especiais.', 30, 7.75, '2025-10-31', '2024-09-16', '10:30:00'),
('Bolo de Chocolate', 'Bolo de chocolate fofinho com cobertura cremosa.', 20, 12.00, '2025-12-15', '2024-09-16', '11:45:00'),
('Suco Natural', 'Suco natural de laranja, refrescante e saudável.', 60, 4.50, '2026-01-10', '2024-09-16', '13:00:00');
