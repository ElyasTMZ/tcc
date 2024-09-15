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
cpf char(14) not null unique,
sexo char (1) default 'F' check (sexo in ('F','M')),
salario decimal (9,2) default 0 check(salario >=0),
nascimento date,
telefone char (10),
primary key (codFunc));


create table tbFornecedores(
codForn int not null auto_increment,
email varchar (100)not null,
nome varchar (100)not null,
cnpj char (17) not null unique,
primary key (codForn));

create table tbClientes(
codCli int not null auto_increment,
nome varchar(100) not null,
email varchar (100),
telCelular char(10),
primary key(codCli));



create table tbUsuarios(
	codUsu int not null auto_increment,
	nome varchar (25) not null unique,
	senha varchar (10) not null,
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
codForn int not null,
primary key (codProd),
foreign key (codForn)references tbFornecedores(codForn));

create table tbVendas(
codVenda int not null auto_increment,
dataVenda date,
horaVenda time,
quantidade int,
codUsu int not null,
codCli int not null,
codProd int not null,
 primary key(codVenda),
 foreign key(codUsu)references tbUsuarios(codUsu),
 foreign key(codCli)references tbClientes(codCli),
 foreign key(codProd)references tbProdutos(codProd));



-- visualizando tabela

desc tbfuncionarios;
desc tbFornecedores;
desc tbClientes;
desc tbUsuarios;
desc tbProdutos;
desc tbVendas;
