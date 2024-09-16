-- apagando banco de dados
 drop database dbcantina;

-- criando banco de dados
create database dbcantina;

-- acessando banco de dados
use dbcantina;

-- criando as tabelas

create table tbfuncionarios (
    codFunc int not null auto_increment,
    nome varchar(100) not null unique,
    email varchar(100) not null unique,
    telefone varchar(15), -- Alterado para varchar para maior flexibilidade
    primary key (codFunc)
);

create table tbUsuarios (
    codUsu int not null auto_increment,
    nome varchar(25) not null unique,
    senha varchar(255) not null, -- Alterado para varchar(255) para segurança
    email varchar(100),
    telCelular varchar(15), -- Alterado para varchar para maior flexibilidade
    codFunc int not null,
    primary key (codUsu),
    foreign key (codFunc) references tbfuncionarios(codFunc)
);

create table tbProdutos (
    codProd int not null auto_increment,
    descricao varchar(100),
    quantidade int,
    valor decimal(9,2),
    validade date,
    dataEntrada date,
    horaEntrada time,
    primary key (codProd)
);

create table tbVendas (
    codVenda int not null auto_increment,
    dataVenda date,
    horaVenda time,
    quantidade int,
    codUsu int not null,
    codProd int not null,
    status varchar(20) default 'pendente', -- Adicionado campo status
    primary key (codVenda),
    foreign key (codUsu) references tbUsuarios(codUsu),
    foreign key (codProd) references tbProdutos(codProd)
);

insert into tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada) 
values ('Refrigerante Coca-Cola 350ml', 100, 4.50, '2024-12-31', '2024-09-15', '08:30:00');

insert into tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada) 
values ('Coxinha de Frango', 50, 5.00, '2024-09-20', '2024-09-15', '08:45:00');

insert into tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada) 
values ('Pão de Queijo', 75, 2.50, '2024-09-19', '2024-09-15', '09:00:00');

insert into tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada) 
values ('Suco de Laranja 300ml', 80, 3.00, '2024-09-25', '2024-09-15', '09:15:00');

insert into tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada) 
values ('Salgado de Presunto e Queijo', 60, 4.00, '2024-09-18', '2024-09-15', '09:30:00');

insert into tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada) 
values ('Bolo de Chocolate', 40, 6.50, '2024-09-22', '2024-09-15', '09:45:00');

INSERT INTO tbfuncionarios (nome, email, telefone) 
VALUES ('João da Silva', 'joao.silva@example.com', '123456789');

INSERT INTO tbUsuarios (nome, senha, email, telCelular, codFunc) 
VALUES ('Maria Oliveira', 'senhaSegura123', 'maria.oliveira@example.com', '987654321', 1);
