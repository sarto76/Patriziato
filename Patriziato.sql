drop database if exists Patriziato;
create database Patriziato;
use Patriziato;
create table Proprieta(TitoloProprieta varchar(40) primary key, Descrizione varchar(200), Tipo varchar(50), PercorsoFoto varchar(100));
create table Contatto (Telefono varchar(20) primary key, Email varchar(50));
create table MembroUfficio(Carica varchar(50) primary key, Nome varchar(100), Cognome varchar(150), DataElezione date);
create table MembroCommissione(IdMembro int primary key auto_increment, Nome varchar(100), Cognome varchar(150));
create table Doc(PercorsoDoc varchar(100) primary key, TitoloDoc varchar(100));
create table Link(PercorsoLink varchar(100) primary key, TitoloLink varchar(100));
create table Utente(Email varchar(100) primary key, Password varchar(100), Tipo enum('A', 'U'));
create table News(IdNews int primary key auto_increment, `Data` date, Titolo varchar(100), Testo varchar(300), Home enum('si', 'no'));


