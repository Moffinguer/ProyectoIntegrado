DROP DATABASE IF EXISTS BIBLIOTECA;
SET SQL_MODE
= "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT
= 0;
START TRANSACTION;
SET time_zone
= "+00:00";
/*CREATE DATABASE BIBLIOTECA*/
CREATE DATABASE
IF NOT EXISTS `biblioteca` DEFAULT CHARACTER
SET latin1
COLLATE latin1_swedish_ci;
USE `biblioteca`;
/*TABLE BOOKS PK=ISBN*/
CREATE TABLE `books`
(
  `ISBN` varchar (13) COLLATE utf8_bin NOT NULL,
  `TITLE` varchar (60) COLLATE utf8_bin NOT NULL,
  `PUBLISHING_HOUSE` varchar (40) COLLATE utf8_bin NOT NULL,
  `TOPIC` varchar (30) COLLATE utf8_bin NOT NULL,
  `SUBJECT` varchar (50) COLLATE utf8_bin NOT NULL,
  `STOCK` int (3) NOT NULL,
  `AUTHOR` varchar (60) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*TABLE USERS PK=ID*/
CREATE TABLE `users`
(
  `ID` varchar (9) COLLATE utf8_bin NOT NULL,
  `NAME` varchar (60) COLLATE utf8_bin NOT NULL,
  `SURNAMES` varchar (50) COLLATE utf8_bin NOT NULL,
  `PASSWORD` varchar (30) COLLATE utf8_bin NOT NULL,
  `EMAIL` varchar (320) COLLATE utf8_bin NOT NULL,
  `ACT_YEAR` date NOT NULL,
  `TYPE` varchar (7) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*TABLE IS_BORROWED PK=ID_USER,ISBN,REQUEST_DATE)*/
CREATE TABLE `is_borrowed`
(
  `ID_USER` varchar (9) COLLATE utf8_bin NOT NULL,
  `ISBN` varchar (13) COLLATE utf8_bin NOT NULL,
  `REQUEST_DATE` date NOT NULL,
  `BORROW_DATE` date DEFAULT NULL,
  `RETURN_DATE` date DEFAULT NULL,
  `STATUS_BOOK` varchar (14) COLLATE utf8_bin NOT NULL DEFAULT 'SOLICITADO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*DATA ON THE DATABASE*/
INSERT INTO `books` (`ISBN`,`TITLE`, `PUBLISHING_HOUSE`, `TOPIC`, `SUBJECT`, `STOCK`, `AUTHOR`) VALUES
('1788463677', 'FUNDICION Y METALURGIA', 'AUSTRAL', 'AVANZADO', 'TECNOLOGIA INDUSTRIAL II', 40, 'BEATRIZ ANTUNEZ ALPERIZ'),
('2788463677', 'CAMPOS VECTORIALES Y SU REPRESENTACION EN EL ESPACIO', 'ARIEL', 'OBLIGATORIO', 'MATEMATICAS II', 30, 'JULIO CARMONA ALONSO'),
('3788463677', 'GUSTAVO ADOLFO BEQUER', 'PLANETA', 'OBLIGATARIO', 'LENGUA Y LITERATURA', 15, 'MARIA JOSE CASADO SANTOS'),
('4788463677', 'INTRODUCCION A LAS SERENATAS DE MOZART', 'ELVIVE', 'ESTUDIO', 'MUSICA', 2, 'JOSEFINA FRAILE PLAZA'),
('5788453677', 'ACIDOS RIBONUCLEICOS', 'ELDEVIVES', 'COMPLEMENTO', 'BIOLOGIA', 4, 'VICTOR CRUZ REJANO'),
('6788463677', 'ACIDOS Y BASES', 'EDICE', 'COMPLEMENTO', 'QUIMICA', 10, 'GERMAN DIAZ LUENGO'),
('7788465077', 'LAS FUERZAS FUNDAMENTALES DE LA NATURALEZA', 'ALGAIDA', 'ANALISIS', 'FISICA II', 5, 'MARIA ANGELES CANFLANCA ARREGUI');
INSERT INTO `is_borrowed` (`ID_USER`,`ISBN`, `REQUEST_DATE`, `BORROW_DATE`, `RETURN_DATE`, `STATUS_BOOK`) VALUES
('30696569X', '4788463677', '2020-01-08', NULL, NULL, 'SOLICITADO'),
('49449459B', '6788463677', '2020-05-12', '2020-05-12', '2020-05-14', 'FALTA'),
('77038533X', '1788463677', '2020-04-26', '2020-04-30', '2020-05-19', 'PRESTADO'),
('81608933B', '4788463677', '2020-01-10', NULL, NULL, 'SOLICITADO'),
('81608933B', '5788453677', '2020-04-30', '2020-04-30', '2020-05-02', 'DEVUELTO');
INSERT INTO `users` (`ID`,`NAME`, `SURNAMES`, `PASSWORD`, `EMAIL`, `ACT_YEAR`, `TYPE`) VALUES
('01236650D', 'CRISTINA', 'RODRIGEZ SAENZ', 'crsD', 'cristinarodrigez@gmail.com', '2020-04-30', 'ADMIN'),
('07126568H', 'CLAUDIO', 'RUIZ BURGOS', 'crbH', 'claudioruiz@gmail.com', '2020-04-30', 'STUDENT'),
('30696569X', 'FERNANDO', 'MATEOS GOMEZ', 'fmgX', 'fermago11@gmail.com', '2020-04-30', 'STUDENT'),
('49449459B', 'PEDRO JAEN', 'FERNANDEZ DE VILLAJOLLOSA', 'pfvB', 'pedrojaendesevilla@gmail.com', '2020-04-30', 'STUDENT'),
('74346065Z', 'JOSE', 'PIÑEZ MALDONADO', 'jpmZ', 'josepinez@gmail.com', '2020-04-30', 'ADMIN'),
('77038533X', 'ANA', 'CAPARROS CALVILLO', 'accX', 'anacaparro@gmail.com', '2020-04-30', 'STUDENT'),
('81608933B', 'ELENA', 'MARTIN PEREZ', 'empB', 'elenamartin@gmail.com', '2020-04-30', 'STUDENT'),
('86181155V', 'ABEL', 'MONTERO QUESADA', 'amqV', 'abelmontero@gmail.com', '2020-04-30', 'ADMIN'),
('93634021D', 'ROBERTO', 'GROSSO BUSTAMANTE', 'rgbD', 'robertogrosso@gmail.com', '2020-04-30', 'STUDENT');

/*PK FK AND CK*/
ALTER TABLE `books`
ADD PRIMARY KEY
(`ISBN`);
ALTER TABLE `is_borrowed`
ADD PRIMARY KEY
(`ID_USER`,`ISBN`,`REQUEST_DATE`),
ADD KEY `FK_BOOKS`
(`ISBN`);
ALTER TABLE `users`
ADD PRIMARY KEY
(`ID`);
ALTER TABLE `is_borrowed`
ADD CONSTRAINT `is_borrowed_ibfk_2` FOREIGN KEY
(`ISBN`) REFERENCES `books`
(`ISBN`),
ADD CONSTRAINT `is_borrowed_ibfk_3` FOREIGN KEY
(`ID_USER`) REFERENCES `users`
(`ID`) ON
DELETE CASCADE;
/*Add temporal column*/
ALTER TABLE `is_borrowed`
ADD `TIMES_RENEWED` INT
(1) NOT NULL DEFAULT '0' AFTER `STATUS_BOOK`;
ALTER TABLE IS_BORROWED 
  ADD CONSTRAINT CK_STATUS CHECK (STATUS_BOOK='SOLICITADO' OR STATUS_BOOK='DEVUELTO' OR STATUS_BOOK='PRESTADO' OR STATUS_BOOK='FALTA');

/*EVENTS CREATED*/
SET GLOBAL event_scheduler
= ON;
CREATE EVENT
IF NOT EXISTS faultUser
	ON SCHEDULE EVERY 15 DAY_HOUR
	DO
UPDATE IS_BORROWED SET STATUS_BOOK='FALTA' WHERE CURDATE()=RETURN_DATE AND STATUS_BOOK='PRESTADO';
CREATE EVENT
IF NOT EXISTS deleteAsks
	ON SCHEDULE EVERY 15 DAY_HOUR
	DO
DELETE FROM IS_BORROWED WHERE STATUS_BOOK='SOLICITADO' AND REQUEST_DATE<CURDATE();

COMMIT;