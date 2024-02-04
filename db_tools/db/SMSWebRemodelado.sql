DROP DATABASE smsweb;

CREATE DATABASE smsweb;

USE smsweb;

CREATE TABLE permissao (
 id_permissao int(2) NOT NULL auto_increment,
 incluir_contato tinyint(1),
 incluir_grupo tinyint(1),
 incluir_sms tinyint(1),
 alterar_config tinyint(1),
 enviar_sms tinyint(1),
 master tinyint(1),
 CONSTRAINT PK_permissao PRIMARY KEY(id_permissao)
);

CREATE TABLE admin (
 id_admin int(2) NOT NULL auto_increment,
 login VARCHAR(100),
 senha VARCHAR(100),
 nome VARCHAR(100),
 fone VARCHAR(14),
 situacao VARCHAR(1),
 id_permissao INT(2) NOT NULL,
 CONSTRAINT PK_admin PRIMARY KEY(id_admin)
);

CREATE TABLE envio_prog (
 id_envio_prog INT(4) NOT NULL auto_increment,
 tipo_destino VARCHAR(1) NOT NULL,
 tipo_msgm VARCHAR(1) NOT NULL,
 data_envio DATE NOT NULL,
 hora_envio VARCHAR(5) NOT NULL,
 situacao VARCHAR(1),
 id_admin INT(2),
 CONSTRAINT PK_envio_prog PRIMARY KEY(id_envio_prog)
);

CREATE TABLE esp_ep_msgm_n_pred (
 id_envio_prog INT(4) NOT NULL,
 mensagem VARCHAR(170) NOT NULL,
 CONSTRAINT PK_esp_ep_msgm_n_pred PRIMARY KEY (id_envio_prog)
);

CREATE TABLE grupo (
 id_grupo INT(2) NOT NULL auto_increment,
 grupo VARCHAR(100) NOT NULL,
 situacao VARCHAR(1),
 id_admin INT(2),
 CONSTRAINT PK_grupo PRIMARY KEY(id_grupo)
);

CREATE TABLE mensagem (
 id_mensagem INT(2) NOT NULL auto_increment,
 titulo VARCHAR(50) NOT NULL,
 mensagem VARCHAR(170) NOT NULL,
 temporaria VARCHAR(1) NOT NULL,
 situacao VARCHAR(1),
 id_admin INT(2),
 CONSTRAINT PK_mensagem PRIMARY KEY(id_mensagem)
);

CREATE TABLE municipe (
 id_municipe INT(2) NOT NULL auto_increment,
 nome VARCHAR(150) NOT NULL,
 data_nasc DATE NOT NULL,
 sexo VARCHAR(1),
 email VARCHAR(150),
 situacao tinyint(1),
 id_admin INT(2),
 CONSTRAINT PK_municipe PRIMARY KEY(id_municipe)
);

CREATE TABLE telefone (
 id_telefone INT(2) NOT NULL auto_increment,
 fone_sms VARCHAR(14) NOT NULL,
 id_municipe int(2) NOT NULL,
 fone_fixo VARCHAR(14),
 fone_recado VARCHAR(14) NOT NULL,
 CONSTRAINT PK_telefone PRIMARY KEY(id_telefone,fone_sms,id_municipe)
);

CREATE TABLE bairro (
 id_bairro INT(2) NOT NULL auto_increment,
 bairro VARCHAR(50) NOT NULL,
 situacao VARCHAR(1),
 id_admin INT(2),
 CONSTRAINT PK_bairro PRIMARY KEY(id_bairro)
);

CREATE TABLE endereco (
 id_endereco INT(2) NOT NULL auto_increment,
 rua VARCHAR(100) NOT NULL,
 numero INT(5) NOT NULL,
 id_municipe INT(2) NOT NULL,
 id_bairro INT(2) NOT NULL,
 CONSTRAINT PK_endereco PRIMARY KEY(id_endereco)
);

CREATE TABLE esp_ep_grupo (
 id_envio_prog INT(2) NOT NULL,
 id_grupo INT(2) NOT NULL,
 CONSTRAINT PK_esp_ep_grupo PRIMARY KEY (id_envio_prog,id_grupo)
);

CREATE TABLE esp_ep_msgm_pred (
 id_envio_prog INT(2) NOT NULL,
 id_mensagem INT(2) NOT NULL,
 CONSTRAINT PK_esp_ep_msgm_pred PRIMARY KEY (id_envio_prog,id_mensagem)
);

CREATE TABLE esp_ep_municipe (
 id_envio_prog INT(2) NOT NULL,
 id_municipe INT(2) NOT NULL,
 CONSTRAINT PK_esp_ep_municipe PRIMARY KEY (id_envio_prog,id_municipe)
);

CREATE TABLE grupo_assoc_municipe (
 id_assoc INT(2) NOT NULL auto_increment,
 id_grupo INT(2) NOT NULL,
 id_municipe INT(2) NOT NULL,
 CONSTRAINT PK_grupo_assoc_municipe PRIMARY KEY (id_assoc)
);

CREATE TABLE config (
 mensagem_aniversario INT(11),
 hora_envio_aniversario VARCHAR(5),
 tempo_atualizacao INT(11),
 telefone_info VARCHAR(14),
 endereco_ip VARCHAR(15),
 porta VARCHAR(4),
 registro_pagina VARCHAR(2)
);

ALTER TABLE admin ADD CONSTRAINT FK_admin_0 FOREIGN KEY (id_permissao) REFERENCES permissao (id_permissao);

ALTER TABLE envio_prog ADD CONSTRAINT FK_envio_prog_0 FOREIGN KEY (id_admin) REFERENCES admin (id_admin);

ALTER TABLE esp_ep_msgm_n_pred ADD CONSTRAINT FK_esp_ep_msgm_n_pred_0 FOREIGN KEY (id_envio_prog) REFERENCES envio_prog (id_envio_prog);

ALTER TABLE grupo ADD CONSTRAINT FK_grupo_0 FOREIGN KEY (id_admin) REFERENCES admin (id_admin);

ALTER TABLE mensagem ADD CONSTRAINT FK_mensagem_0 FOREIGN KEY (id_admin) REFERENCES admin (id_admin);

ALTER TABLE municipe ADD CONSTRAINT FK_municipe_0 FOREIGN KEY (id_admin) REFERENCES admin (id_admin);

ALTER TABLE telefone ADD CONSTRAINT FK_telefone_0 FOREIGN KEY (id_municipe) REFERENCES municipe (id_municipe);

ALTER TABLE bairro ADD CONSTRAINT FK_bairro_0 FOREIGN KEY (id_admin) REFERENCES admin (id_admin);

ALTER TABLE endereco ADD CONSTRAINT FK_endereco_0 FOREIGN KEY (id_municipe) REFERENCES municipe (id_municipe);
ALTER TABLE endereco ADD CONSTRAINT FK_endereco_1 FOREIGN KEY (id_bairro) REFERENCES bairro (id_bairro);

ALTER TABLE esp_ep_grupo ADD CONSTRAINT FK_esp_ep_grupo_0 FOREIGN KEY (id_envio_prog) REFERENCES envio_prog (id_envio_prog);
ALTER TABLE esp_ep_grupo ADD CONSTRAINT FK_esp_ep_grupo_1 FOREIGN KEY (id_grupo) REFERENCES grupo (id_grupo);

ALTER TABLE esp_ep_msgm_pred ADD CONSTRAINT FK_esp_ep_msgm_pred_0 FOREIGN KEY (id_envio_prog) REFERENCES envio_prog (id_envio_prog);
ALTER TABLE esp_ep_msgm_pred ADD CONSTRAINT FK_esp_ep_msgm_pred_1 FOREIGN KEY (id_mensagem) REFERENCES mensagem (id_mensagem);

ALTER TABLE esp_ep_municipe ADD CONSTRAINT FK_esp_ep_municipe_0 FOREIGN KEY (id_envio_prog) REFERENCES envio_prog (id_envio_prog);
ALTER TABLE esp_ep_municipe ADD CONSTRAINT FK_esp_ep_municipe_1 FOREIGN KEY (id_municipe) REFERENCES municipe (id_municipe);

ALTER TABLE grupo_assoc_municipe ADD CONSTRAINT FK_grupo_assoc_municipe_0 FOREIGN KEY (id_grupo) REFERENCES grupo (id_grupo);
ALTER TABLE grupo_assoc_municipe ADD CONSTRAINT FK_grupo_assoc_municipe_1 FOREIGN KEY (id_municipe) REFERENCES municipe (id_municipe);

INSERT INTO permissao (incluir_contato,incluir_grupo,incluir_sms,alterar_config,enviar_sms,master) VALUES (1,1,1,1,1,1);
--usermaster 
--master
INSERT INTO admin (login, senha, nome, fone, situacao, id_permissao) VALUES ('usermaster','4f26aeafdb2367620a393c973eddbe8f8b846ebd','UserMaster','4588262228','a','1');

INSERT INTO grupo (grupo, situacao, id_admin) VALUES ('Todos','1','1');

INSERT INTO mensagem (titulo, mensagem, temporaria, situacao, id_admin) VALUES ("ANIVERSÁRIO","muita saúde e novas oportunidades para concretizar os seus sonhos mais desejados. São os votos da prefeitura de Ibema!","1","1",1);

INSERT INTO bairro (bairro, situacao, id_admin) VALUES ("CENTRO","1",1);

INSERT INTO config (mensagem_aniversario, hora_envio_aniversario, tempo_atualizacao, endereco_ip, porta, registro_pagina) VALUES (1, '12:30',30,'127.0.0.1','8800','15');

