use Patriziato;
insert into Proprieta values('Bar1', 'è un bar', 'Bar e ristoranti', 'assets/img/head.JPG');
insert into Proprieta values('Bar2', 'è un bar', 'Bar e ristoranti', 'assets/img/head.JPG');
insert into Proprieta values('Bar3', 'è un bar', 'Bar e ristoranti', 'assets/img/head.JPG');
insert into Proprieta values('Ristorante1', 'è un ristorante', 'Bar e ristoranti', 'assets/img/head.JPG');
insert into Proprieta values('Ristorante2', 'è un ristorante', 'Bar e ristoranti', 'assets/img/head.JPG');
insert into Proprieta values('Ristorante3', 'è un ristorante', 'Bar e ristoranti', 'assets/img/head.JPG');
insert into Proprieta values('Terreno1', 'è un terreno', 'Terreni', 'assets/img/head.JPG');
insert into Proprieta values('Terreno2', 'è un terreno', 'Terreni', 'assets/img/head.JPG');
insert into Proprieta values('Terreno3', 'è un terreno', 'Terreni', 'assets/img/head.JPG');
insert into Proprieta values('Scuola', 'è una scuola', 'Strutture pubbliche', 'assets/img/head.JPG');
insert into Proprieta values('Asilo', 'è un asilo', 'Strutture pubbliche', 'assets/img/head.JPG');
insert into Proprieta values('Municipio', 'è un municipio', 'Strutture pubbliche', 'assets/img/head.JPG');

insert into Contatto values ('+41 (0)917542236', 'patriziato.bosco@gmail.com');

insert into MembroUfficio values('Presidente','Egidio','Bronz','2013-04-28');
insert into MembroUfficio values('Vice-Presidente','Lino','Tomamichel','2013-04-28');
insert into MembroUfficio values('Membro','Raffaele','Sartori','2013-04-28');
insert into MembroUfficio values('Segretario','Massimo','Sartori','2013-04-28');

insert into MembroCommissione values(null, 'Marco', 'Leoni');
insert into MembroCommissione values(null, 'Tiziana', 'Vedova');
insert into MembroCommissione values(null, 'Renzo', 'Mossi');

insert into Doc values('docs/lop.pdf','Legge Organica Patriziale LOP');
insert into Doc values('docs/rlop.pdf','Regolamento di applicazione alla LOP');
insert into Doc values('docs/regolamento.pdf','Regolamento Patriziale Bosco Gurin');
insert into Doc values('docs/Linee_guida.pdf','Linee guida nuova LOP 2012');

insert into Link values('http://www.bosco-gurin.ch/it/', 'Comune di Bosco Gurin');
insert into Link values('http://www.bosco-gurin.ch/it/associazione-paesaggio/','Associazione Paesaggio');
insert into Link values('http://www.walserhaus.ch/','Museo Walserhaus');
insert into Link values('http://alleanzapatriziale.ch/','Alleanza patriziale ticinese');

insert into Utente values('Amministratore@email.com', 'Amministratore','A');

insert into news values(null, '2013-04-25', 'Assemblee', 'Data: 2013-04-25, ora: 20:00, Data: 2013-04-25, ora: 20:00', 'si');
insert into news values(null, '2013-04-28', 'Elezioni', 'Egidio Bronz, Lino Tomamichel, Raffaele sartori, data elezioni: 2013-04-28', 'si');

