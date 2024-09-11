-- apagando banco de dados

 -- drop database dbcantina;

-- criando banco de dados

create database dbcantina;

-- acessando banco de dados

use dbcantina;

-- criando as tabelas

create table tbfuncionarios (
codFunc int not null auto_increment,
nome varchar(100) not null unique,
email varchar(100) not null unique,
telefone char (10),
primary key (codFunc));

create table tbUsuarios(
codUsu int not null auto_increment,
nome varchar (25) not null unique,
senha varchar (10) not null,
email varchar (100),
telCelular char(10),
codFunc int not null,
primary key (codUsu),
foreign key (codFunc)references tbfuncionarios(codFunc));


create table tbProdutos(
codProd int not null auto_increment,
descricao varchar (100),
quantidade int,
valor decimal(9,2),
validade date,
dataEntrada date,
horaEntrada time,
primary key (codProd));

create table tbVendas(
codVenda int not null auto_increment,
dataVenda date,
horaVenda time,
quantidade int,
codUsu int not null,
codProd int not null,
 primary key(codVenda),
 foreign key(codUsu)references tbUsuarios(codUsu),
 foreign key(codProd)references tbProdutos(codProd));