CREATE SCHEMA IF NOT EXISTS lbaw2152;

SET search_path TO lbaw2152;

DROP TABLE IF EXISTS gosto CASCADE;
DROP TABLE IF EXISTS grupoutilizador CASCADE;
DROP TABLE IF EXISTS grupopublicacao CASCADE;
DROP TABLE IF EXISTS grupo CASCADE;
DROP TABLE IF EXISTS moderador CASCADE;
DROP TABLE IF EXISTS comentario CASCADE;
DROP TABLE IF EXISTS notificacaoutilizador CASCADE;
DROP TABLE IF EXISTS notificacao CASCADE;
DROP TABLE IF EXISTS publicacao CASCADE;
DROP TABLE IF EXISTS administrador CASCADE;
DROP TABLE IF EXISTS colega CASCADE;
DROP TABLE IF EXISTS utilizadordocente CASCADE;
DROP TABLE IF EXISTS utilizadorestudante CASCADE;
DROP TABLE IF EXISTS users CASCADE;

DROP TYPE IF EXISTS tipoanexo CASCADE;
DROP TYPE IF EXISTS privacidade CASCADE;
DROP TYPE IF EXISTS tipogrupo CASCADE;
DROP TYPE IF EXISTS tiponotificacao CASCADE;

CREATE TYPE tipoanexo AS ENUM ('mp3', 'photo', 'document','NULL');
CREATE TYPE privacidade AS ENUM ('public', 'private');
CREATE TYPE tipogrupo AS ENUM ('lazer', 'work');
CREATE TYPE tiponotificacao AS ENUM ('like', 'comment', 'post');

CREATE TABLE users (
    id serial PRIMARY KEY,
    nome text,
    email text NOT NULL UNIQUE,
    password text NOT NULL,
    dataNascimento timestamp CONSTRAINT notBornYesterday CHECK (dataNascimento <now()::timestamp),
    dataTempoRegisto timestamp CONSTRAINT notRegisteredYesterday CHECK (dataTempoRegisto > dataNascimento),
    atualizado timestamp,
    instituicaoEnsino text,
    privacidade privacidade NOT NULL,
    fotoPerfil text,
    is_blocked boolean,
    fotoHeader text
);

CREATE TABLE utilizadorestudante (
    id serial PRIMARY KEY REFERENCES users ON DELETE CASCADE ON UPDATE CASCADE,
    curso text, 
    anoCorrente integer,
    media float
);

CREATE TABLE utilizadordocente (
    id serial PRIMARY KEY REFERENCES users ON DELETE CASCADE ON UPDATE CASCADE,
    departamento text, 
    formacao text
);

CREATE TABLE grupo (
    id serial PRIMARY KEY,
    nome text,
    dataTempoCriacao timestamp,
    atualizado timestamp,
    privacidade privacidade NOT NULL,
    tipo tipoGrupo NOT NULL,
    CONSTRAINT notCreatedYesterday CHECK (dataTempoCriacao <= now()::timestamp)
);

CREATE TABLE moderador (
    idGrupo integer NOT NULL REFERENCES grupo (id) ON DELETE CASCADE ON UPDATE CASCADE,
    idUtilizador integer NOT NULL REFERENCES users (id)  ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (idGrupo, idUtilizador)
);

CREATE TABLE administrador (
    id serial PRIMARY KEY,
    email text UNIQUE NOT NULL,
    password text NOT NULL,
    idDocente integer REFERENCES utilizadorDocente(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE grupoUtilizador (
    idUtilizador integer NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    idGrupo integer NOT NULL REFERENCES grupo (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (idUtilizador, idGrupo)
);

CREATE TABLE notificacao (
    id serial PRIMARY KEY,
    dataTempo timestamp,
    atualizado timestamp,
    tipo tipoNotificacao NOT NULL,
    CONSTRAINT notNotifiedYesterday CHECK (dataTempo <= now()::timestamp)
);

CREATE TABLE publicacao (
    id serial PRIMARY KEY,
    dataTempo timestamp CONSTRAINT notPostedYesterday CHECK (dataTempo <= now()::timestamp),
    numeroGostos integer,
    atualizado timestamp,
    numeroComentarios integer,
    conteudo text,
    anexo text,
    idNotificacao integer REFERENCES notificacao(id) ON DELETE CASCADE ON UPDATE CASCADE,
    idUtilizador integer REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    tipoAnexo tipoAnexo,
    privacidade privacidade NOT NULL,
    CONSTRAINT positiveLikes CHECK (numeroGostos >= 0),
    CONSTRAINT positiveComments CHECK (numeroComentarios >= 0)
);

CREATE TABLE gosto (
    id serial PRIMARY KEY,
    idNotificacao integer REFERENCES notificacao(id) ON DELETE CASCADE ON UPDATE CASCADE,
    idUtilizador integer REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    idPublicacao integer REFERENCES publicacao(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE comentario (
    id serial PRIMARY KEY,
    dataTempo timestamp,
    atualizado timestamp,
    conteudo text,
    idNotificacao integer REFERENCES notificacao(id) ON DELETE CASCADE ON UPDATE CASCADE,
    idUtilizador integer REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    idPublicacao integer REFERENCES publicacao(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT notCommentedYesterday CHECK (dataTempo <= now()::timestamp)
);

CREATE TABLE notificacaoUtilizador (
    id serial PRIMARY KEY,
    idUtilizador integer NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    idNotificacao integer REFERENCES notificacao (id) ON DELETE CASCADE ON UPDATE CASCADE,
    vista boolean NOT NULL,
    UNIQUE (idUtilizador, idNotificacao)
);

CREATE TABLE grupoPublicacao (
    idGrupo integer REFERENCES grupo (id) ON DELETE CASCADE ON UPDATE CASCADE,
    idPublicacao integer PRIMARY KEY REFERENCES publicacao (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE colega (
    utilizador1 integer REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    utilizador2 integer REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (utilizador1, utilizador2)
);

--INDEXES PERFORMANCE
DROP INDEX IF EXISTS public_posts CASCADE;
DROP INDEX IF EXISTS group_posts CASCADE;
DROP INDEX IF EXISTS user_posts CASCADE;
DROP INDEX IF EXISTS search_user CASCADE;
DROP INDEX IF EXISTS search_group CASCADE;
DROP INDEX IF EXISTS search_post CASCADE;

--IDX01
CREATE INDEX public_posts 
ON publicacao(privacidade) 
WHERE privacidade = 'public';

--IDX02
--CREATE INDEX group_posts 
--ON grupoPublicacao 
--USING GIN (idGrupo);

--IDX03
CREATE INDEX user_posts
ON publicacao 
USING btree (idUtilizador);

--INDEXES FULL-TEXT SEARCH
--IDX11
ALTER TABLE users
ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS utilizador_search_update() CASCADE;
CREATE FUNCTION utilizador_search_update()
RETURNS TRIGGER AS $$
BEGIN 
 IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (setweight(to_tsvector('portuguese',NEW.nome),'A'));
 END IF;
 
 IF TG_OP = 'UPDATE' THEN 
    IF (NEW.nome <> OLD.nome) THEN 
        NEW.tsvectors = (setweight(to_tsvector('portuguese',NEW.nome),'A'));
    END IF;
 END IF;
 RETURN NEW;
END;
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS utilizador_search_update ON utilizador;
CREATE TRIGGER utilizador_search_update
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE PROCEDURE utilizador_search_update();

CREATE INDEX search_user 
ON users
USING GIN(tsvectors);

--IDX12
ALTER TABLE grupo
ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION grupo_search_update()
RETURNS TRIGGER AS $$
BEGIN 
 IF TG_OP = 'INSERT' THEN NEW.tsvectors = (setweight(to_tsvector('portuguese',NEW.nome),'A'));
 END IF;
 
 IF TG_OP = 'UPDATE' THEN
    IF (NEW.nome <> OLD.nome) THEN 
        NEW.tsvectors = (setweight(to_tsvector('portuguese',NEW.nome),'A'));
    END IF;
 END IF;
 RETURN NEW;
END;
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS grupo_search_update on grupo;
CREATE TRIGGER grupo_search_update
BEFORE INSERT OR UPDATE ON grupo
FOR EACH ROW 
EXECUTE PROCEDURE grupo_search_update();

CREATE INDEX search_group 
ON grupo 
USING GIN(tsvectors);

--IDX13
ALTER TABLE publicacao
ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION post_search_update()
RETURNS TRIGGER AS $$
BEGIN 
  IF TG_OP = 'INSERT' THEN NEW.tsvectors = (setweight(to_tsvector('portuguese',NEW.conteudo),'A')); 
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN 
    IF (NEW.conteudo <> OLD.conteudo) THEN 
        NEW.tsvectors = (setweight(to_tsvector('portuguese',NEW.conteudo),'A'));
    END IF;
  END IF;
  RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER post_search_update
BEFORE INSERT OR UPDATE ON publicacao
FOR EACH ROW 
EXECUTE PROCEDURE post_search_update();

CREATE INDEX search_post 
ON publicacao 
USING GiST(tsvectors);

--TRIGGERS
--TRIGGER01
DROP TRIGGER IF EXISTS num_likes ON gosto CASCADE;
DROP FUNCTION IF EXISTS num_likes();
CREATE FUNCTION num_likes() 
RETURNS TRIGGER AS 
$$ 
BEGIN
    UPDATE publicacao
    SET 
      numeroGostos = coalesce(publicacao.numeroGostos, 0) + 1
    WHERE id = new.id;
    
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER num_likes 
        AFTER INSERT ON gosto
        FOR EACH ROW
        EXECUTE PROCEDURE num_likes();

--TRIGGER02
DROP TRIGGER IF EXISTS num_comments ON comentario CASCADE;
DROP FUNCTION IF EXISTS num_comments();
CREATE FUNCTION num_comments()
RETURNS TRIGGER AS 
$$ 
BEGIN
    UPDATE publicacao
    SET 
      numeroComentarios = coalesce(publicacao.numeroComentarios, 0) + 1
    WHERE id = new.id;

    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER num_comments
       AFTER INSERT ON comentario
       FOR EACH ROW
       EXECUTE PROCEDURE num_comments();

--TRIGGER03
DROP TRIGGER IF EXISTS not_comments ON comentario CASCADE;
DROP FUNCTION IF EXISTS not_comments();
CREATE FUNCTION not_comments()
RETURNS TRIGGER AS 
$$ 
DECLARE
    n_id integer;
    id_u integer;
    
BEGIN
  INSERT INTO notificacao(tipo, dataTempo) values('comment',now()) RETURNING id INTO n_id;
  
  SELECT idUtilizador 
  INTO id_u
  FROM publicacao
  WHERE id = NEW.idPublicacao;
  
  INSERT INTO notificacaoUtilizador(idNotificacao, idUtilizador,vista) values (n_id, id_u, 'False');
  RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER not_comments 
       AFTER INSERT ON comentario
       FOR EACH ROW
       EXECUTE PROCEDURE not_comments();

--TRIGGER04
DROP TRIGGER IF EXISTS not_likes ON gosto CASCADE;
DROP FUNCTION IF EXISTS not_likes();
CREATE FUNCTION not_likes()
RETURNS TRIGGER AS
$$ 
DECLARE
  n_id integer;
  id_u integer;
  
BEGIN
    INSERT INTO notificacao(tipo, dataTempo) values('like',now()) RETURNING id INTO n_id;
    
    SELECT idUtilizador 
    INTO id_u
    FROM publicacao 
    WHERE id = NEW.idPublicacao;
    
    INSERT INTO notificacaoUtilizador(idNotificacao, idUtilizador, vista) values (n_id, id_u, 'False');
    RETURN NEW;

END;
$$
LANGUAGE plpgsql;

--TRIGGER05
DROP TRIGGER IF EXISTS not_grupo ON grupoPublicacao CASCADE;
DROP FUNCTION IF EXISTS not_grupo();
CREATE FUNCTION not_grupo()
RETURNS TRIGGER AS 
$$ 
DECLARE
    arrow record; 
    n_id integer;
	id_u integer;
    g_id integer;
BEGIN
    INSERT INTO notificacao(tipo, dataTempo) values('post',now()) RETURNING id INTO n_id;
    
    SELECT idUtilizador 
    INTO id_u
    FROM publicacao 
    WHERE id = NEW.idPublicacao;

    SELECT idGrupo
    INTO g_id
    FROM grupoUtilizador
    WHERE idUtilizador = id_u;
    
    FOR arrow IN (SELECT idUtilizador FROM grupoUtilizador) LOOP
        IF arrow.idUtilizador <> id_u AND arrow.idGrupo = g_id THEN 
            INSERT INTO notificacaoUtilizador(idNotificacao, idUtilizador) values (n_id, arrow.idUtilizador);
        END IF; 
    END LOOP;  
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER not_grupo
    AFTER INSERT ON grupoPublicacao
    FOR EACH ROW
    EXECUTE PROCEDURE not_grupo();

--TRIGGER06
DROP TRIGGER IF EXISTS num_moderadores ON moderador CASCADE;
DROP FUNCTION IF EXISTS num_moderadores();
CREATE FUNCTION num_moderadores()
RETURNS TRIGGER AS 
$$ 
DECLARE
    n_id integer;
    id_u integer;
    arrow record;
    id_alreadyhere integer;
    counter integer;
	g_t text;
    
BEGIN
    FOR arrow IN (SELECT idGrupo FROM moderador) LOOP 
        IF arrow.idGrupo = new.idGrupo THEN
            counter:=counter+1;
        END IF;
    END LOOP;
    
    IF counter = 2 THEN 
        RAISE EXCEPTION 'Este grupo já tem dois moderadores, não é possível adicionar mais.' ;
    END IF;
    
    IF counter = 1 THEN 
        SELECT tipo
        INTO g_t
        FROM grupo
        WHERE id = new.idGrupo;
        
        IF g_t = 'TRABALHO' THEN 
            FOR arrow IN (SELECT idGrupo FROM moderador) LOOP
                IF arrow.idGrupo = new.idGrupo THEN
                    id_alreadyhere := arrow.idUtilizador;
                END IF;
            END LOOP;
            
            IF EXISTS (SELECT id FROM utilizadorEstudante WHERE id = id_alreadyhere) AND EXISTS (SELECT id FROM utilizadorEstudante WHERE id = new.idUtilizador) THEN
               RAISE EXCEPTION 'Este grupo já tem um moderador estudante, precisa de um moderador docente.'; 
            END IF;
        
            IF EXISTS (SELECT id FROM utilizadorDocente WHERE id = id_alreadyhere) AND EXISTS (SELECT id FROM utilizadorDocente WHERE id = new.idUtilizador) THEN
                RAISE EXCEPTION 'Este grupo já tem um moderador docente, precisa de um moderador estudante.' ; 
            END IF;
        END IF; 
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER num_moderadores
       BEFORE INSERT ON moderador
       FOR EACH ROW 
       EXECUTE PROCEDURE num_moderadores();

--TRANSACTIONS
--TRAN01
CREATE OR REPLACE PROCEDURE replace_moderador
(
  idU integer,
  new_idU integer
) 
LANGUAGE plpgsql
AS
$$
DECLARE 
  grupo_id integer;
BEGIN
  grupo_id := (SELECT idGrupo FROM moderador WHERE idUtilizador = idU);

--Apagar moderador
DELETE FROM moderador WHERE idUtilizador = idU;

--Adicionar novo moderador
INSERT INTO moderador (idGrupo,idUtilizador) VALUES(grupo_id,new_idU);
END 
$$;

--TRAN02
CREATE OR REPLACE PROCEDURE constant_privacy
(
  idU integer,
  idN integer,
  dT timestamp,
  c text,
  a text,
  tA tipoAnexo,
  idG integer,
  idP integer
)
LANGUAGE plpgsql
AS
$$
DECLARE
   p_t privacidade;
BEGIN
--Antes de inserir em publicação vamos ver como é a privacidade no utilizador
p_t := (SELECT privacidade FROM users
WHERE id=idU);

INSERT INTO publicacao(idNotificacao,privacidade,dataTempo,conteudo,anexo,tipoAnexo,idUtilizador)   VALUES(idN,p_t,dT,c,a,tA,idU);

--Caso seja uma publicação para um grupo, esta fica com a privacidade do grupo
p_t := (SELECT privacidade FROM grupo WHERE id=idG);

UPDATE publicacao
SET 
   privacidade = p_t
WHERE
   id=idP;

INSERT INTO grupoPublicacao(idGrupo,idPublicacao) 
       VALUES(idG,idP);

END 
$$;

--TRAN03
CREATE OR REPLACE PROCEDURE constant_privacy
(
  idU integer,
  idN integer,
  dT timestamp,
  c text,
  a text,
  tA tipoAnexo,
  idG integer,
  idP integer
)
LANGUAGE plpgsql
AS
$$
DECLARE
   p_t privacidade;
BEGIN
--Antes de inserir em publicação vamos ver como é a privacidade no utilizador
p_t := (SELECT privacidade FROM users
WHERE id=idU);

INSERT INTO publicacao(idNotificacao,privacidade,dataTempo,conteudo,anexo,tipoAnexo,idUtilizador)   VALUES(idN,p_t,dT,c,a,tA,idU);

--Caso seja uma publicação para um grupo, esta fica com a privacidade do grupo
p_t := (SELECT privacidade FROM grupo WHERE id=idG);

UPDATE publicacao
SET 
   privacidade = p_t
WHERE
   id=idP;

INSERT INTO grupoPublicacao(idGrupo,idPublicacao) 
       VALUES(idG,idP);

END 
$$;

INSERT INTO grupo (nome, dataTempoCriacao, privacidade, tipo)
VALUES
  ( 'Projeto de LBAW | 2152', '2021-11-10 07:58:03', 'private', 'work'),
  ('memes for the soul', '2021-10-31 00:00:20', 'public', 'lazer');
INSERT INTO users (nome,email,password,dataNascimento,dataTempoRegisto,instituicaoEnsino, privacidade, fotoPerfil, fotoHeader)
VALUES
  ('Brianna Cortez','test@gmail.com','$2a$12$.yaBVBBYK4mlw2YYqsB3uOU9gjsosB/8kakpdEtWRbKo663iKBhMa','2001-10-20 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar47.jpg','assets/img/banner.jpg'),
  ('Macaulay Wilson','tellus.nunc.lectus@aol.couk','$2a$12$oF7JH.IzxV5eBOM/Z5SarOqId1Rs320vi6D8DclslKGHScqckLqm6,','2002-12-22 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar2.jpg','assets/img/banner2.jpg'),
  ('MacKenzie Jensen','lacinia.sed.congue@yahoo.couk','$2a$12$TuHtNkrP4gQ39n4GfrAMVey.pp8IEHlf82UsY6U2D0BZjZPgGuYdq','2001-11-10 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar3.jpg','assets/img/banner3.png'),
  ('Philip Howell','fringilla.porttitor.vulputate@aol.couk','$2a$12$CUNnCTVqUeJw9R.FfrKxNOP8LKbXq3GXfkSJA.PKPyiLpw2uVtg7e','2002-01-01 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar4.jpg','assets/img/banner4.png'),
  ('Ignatius Puckett','eget@hotmail.ca','$2a$12$clHq0ogcSQi8tT1a6GqDee4eBDOTVsf4xX1U/OZ93iMf.53wH3/cC','2001-03-23 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar5.jpg','assets/img/banner5.jpg'),
  ('Karly Cook','amet.nulla.donec@icloud.org','$2a$12$ngXZspKZCHLBs1CNhx.CZuaVm1WqFHaUf8bGaalyGwVUYB801Xcte','2001-10-30 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar6.jpg','assets/img/banner6.jpeg'),
  ('Lynn Lucas','tristique@outlook.edu','$2a$12$B35eB65gUGpK5ofkaHxEhuzuvsCPLmDEllzSbQc9.SSvEPe5PtoL6','2001-05-20 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar7.jpg','assets/img/banner7.jpeg'),
  ('Orson Peters','at@hotmail.couk','$2a$12$kXftfSg3hFg90Xs9GU3Yh.vr5DT3kFflKEIIwJDhByxDLQmxjGvHm','2003-04-20 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar8.jpg','assets/img/banner8.jpeg'),
  ('Iliana Le','sed.id@outlook.com','$2a$12$yb9EekoL2p7oqj0g44LlB.29uwlCoZ4OEPwlZdQQ9Ec0I9QLZyEr6','2001-09-30 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar9.jpg','assets/img/banner9.jpeg'),
  ('Alexis Jacobs','test2@gmail.com','$2a$12$UwxKJb1rb9fARxElewmoFeGx7l4V5nyh8mzj.UgPExQMJ0.0G8szy','2002-01-19 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar10.jpg','assets/img/banner10.jpeg');
INSERT INTO users (nome,email,password,dataNascimento,dataTempoRegisto,instituicaoEnsino, privacidade, fotoPerfil, fotoHeader)
VALUES
  ('Scarlet Smith','in.molestie.tortor@yahoo.ca','$2a$12$GXIEYfCYYwMlLjww6TGAW.dwntvINgqr/eXBsf2ynw6/1R5KnLFtG','2001-03-24 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar11.jpg','assets/img/banner11.jpeg'),
  ('Phillip Mayo','eget@yahoo.net','$2a$12$pH2/LEEWeFWE4/jfOj8f..kUBw.hpa7XyloR9P77iHgvbhslIwtte','2001-04-29 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar12.png','assets/img/banner12.jpeg'),
  ('Fallon Burch','dolor.dapibus@aol.edu','$2a$12$1jHvIh335638lt312uT.z.QJIFL7P4h48MXKtjgsBkkodVbtgI9H6','2001-02-20 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar13.jpg','assets/img/banner13.jpeg'),
  ('Dexter Mccullough','tristique.ac@google.ca','$2a$12$TtGriWDLx1oKbC3eyhkOn.gy3Dw7jJE9wZQC1JqTMfxbVLhepZ5oO','2002-10-20 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar14.jpg','assets/img/banner14.jpeg'),
  ('Delilah Blankenship','malesuada.integer@yahoo.com','$2a$12$OCpGJVukesMDI837aPoEL.3Ol7GQHZ0OXWchWipqfl4qV9cF7QNMq','2001-12-28 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar15.jpg','assets/img/banner15.jpeg'),
  ('Luke Sheppard','nisi.nibh@protonmail.edu','$2a$12$2uFstv4F3HS.Ed2p14s6C.LWLdzHh5q1DsggpLUrHbQjJfqRMWvi.','2001-10-20 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar16.jpg','assets/img/banner16.jpeg'),
  ('Miranda Carson','ornare@outlook.ca','$2a$12$P5zXs9u9Ekd4lE0ugCy7vOjIg4Jgu6Y78OeTxaSOBE1VdYBgAQl4G','2003-02-22 00:00:00','2021-10-01 00:00:00','FEUP', 'public','assets/img/avatar17.jpg','assets/img/banner17.jpeg'),
  ('Francis Roberts','sociis.natoque@icloud.org','$2a$12$CKWl.Iyx4tIqGTUD.JGXJuKpQl.7rMtqjx9es3gWsodblQ/ggOETe','2001-07-21 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar18.jpg','assets/img/banner18.jpeg'),
  ('Brynne Jackson','sollicitudin@yahoo.net','$2a$12$4gZresIGd38hLesxPxO.pO5c2iOcbXXBxaKs9V4FyIn0AjQcZZkoe','2001-05-04 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar19.jpg','assets/img/banner19.jpg'),
  ('Adrienne Acosta','nonummy@yahoo.org','$2a$12$L9OGH5TQjC2s0UhC23LXh.t7JYgqI2W4kZw2.p1zyv9l5omBskeNC','2001-08-20 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar20.jpg','assets/img/banner20.jpg');
INSERT INTO users (nome,email,password,dataNascimento,dataTempoRegisto,instituicaoEnsino, privacidade, fotoPerfil, fotoHeader)
VALUES
  ('Britanni Espinoza','auctor.velit.aliquam@hotmail.net','$2a$12$9eCqHuCl/Ne.9wBDG2TcPOtxmHZMYlZDG61GodSlHDWaHu/57iFNG','2001-05-03 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar21.jpg','assets/img/banner21.jpg'),
  ('Ingrid Gibson','imperdiet.ornare@hotmail.couk','$2a$12$UUzONFD2c00QjYpvuX7pIuVe/sU6fSDQIWam8TwYMaNFYxRkazXle','2001-10-02 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar22.jpg','assets/img/banner22.png'),
  ('Stone Spencer','donec.felis.orci@yahoo.edu','$2a$12$6nYRHmGajJhY.k8OhzU9.umVStfC9NJyp80cnrWgjSjRurDDMrM02','2002-12-20 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar23.jpg','assets/img/banner23.jpeg'),
  ('Craig Sanford','ut.cursus@outlook.com','$2a$12$6W7pe1IBnfRgTk.wOrnFUub2vnHKtzjC2d3w3judbXAw8kqIGTNz2','2001-12-08 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar24.jpg','assets/img/banner24.jpg'),
  ('Amber Oneal','sodales.at@yahoo.couk','$2a$12$TW7FUtZKs1ZMijrydeAoCOA1uMDfNX2A6TE93JXFfG5mGFqrmMwia','2001-04-23 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar25.jpg','assets/img/banner25.jpeg'),
  ('Lewis Jackson','ultrices.sit.amet@protonmail.com','$2a$12$Nm511502cIhH4T1tkRL0TOAlshi6HP/f6mXG.FdC1JrbxE9xCxY3i','2001-10-03 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar26.png','assets/img/banner26.jpeg'),
  ('Amal Roy','aliquam.eros@yahoo.com','$2a$12$2dgeoU0k.21.Oh5KeTW50uEx9N5g4YFuHynE1J3wZFb0BLbjFPw6q','2001-10-31 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar27.jpg','assets/img/banner27.jpg'),
  ('Ramona Sanders','habitant.morbi.tristique@icloud.net','$2a$12$GagRopDd253AXvIq26MJOuPGh8KtmTr1lHmULC2OVyhkzBDIMPM.K','2001-01-31 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar28.jpg','assets/img/banner28.jpg'),
  ('Denton Brady','elit.pellentesque.a@protonmail.net','$2a$12$8XhPBrJlqfzUNwG9DqZ8iOTE58RsJDPBrMxLyIn9IGMAZhIloiLV6','2001-10-29 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar29.jpg','assets/img/banner29.jpeg'),
  ('Lillith Combs','laoreet.libero@protonmail.ca','$2a$12$Ooh1s1FegpQQRdS6QQ8Q3.KFwxunWu4CrrHwzG3GEdrxwoDt9vKxe','2001-09-02 00:00:00','2021-10-02 00:00:00','FEUP', 'public','assets/img/avatar30.jpg','assets/img/banner30.jpeg');
INSERT INTO users (nome,email,password,dataNascimento,dataTempoRegisto,instituicaoEnsino, privacidade, fotoPerfil, fotoHeader)
VALUES
  ('Odysseus Peters','nunc.ullamcorper@outlook.ca','$2a$12$TPEKhNrJM.8ATWrvilKWIu5puKcJNqFF.mqQ9237hEh2vAdRppHcu','2001-12-25 00:00:00','2021-10-02 00:00:00','FEUP', 'private','assets/img/avatar31.jpg','assets/img/banner31.jpeg'),
  ('Kevyn Chang','lobortis.nisi@yahoo.edu','$2a$12$JjseiZCRKexvPI5Ol69KNOh6EgRPzF9uS3JjfrxgcfCjwYHmlQNo2','2001-10-08 00:00:00','2021-10-02 00:00:00','FEUP', 'private','assets/img/avatar32.jpg','assets/img/banner32.jpeg'),
  ('Moana Baker','ac.feugiat@hotmail.net','$2a$12$DMxNzyD.V6v6PJaKTmiivOSuMEcijI2cZ4kdqFvBZ/3vLuDL2UeJK','2001-07-23 00:00:00','2021-10-02 00:00:00','FEUP', 'private','assets/img/avatar33.jpg','assets/img/banner33.jpeg'),
  ('Wade King','senectus@outlook.couk','$2a$12$TwB92Ygi5hhXLUwL8dHVseLYSA5DCYbZgiCAT1N3jIlFN8BGf.L3K','2001-12-04 00:00:00','2021-10-02 00:00:00','FEUP', 'private','assets/img/avatar34.jpg','assets/img/banner34.jpeg'),
  ('Daquan Edwards','ipsum@aol.couk','$2a$12$emAqffIRkgrAr31Xn0i4TOn2SatiJD.favDebPdaJhwtSrgW.4nF6','2001-08-22 00:00:00','2021-10-02 00:00:00','FEUP', 'private','assets/img/avatar35.jpg','assets/img/banner35.jpeg'),
  ('Hilary Walters','pellentesque.ultricies@outlook.org','$2a$12$AGWWVtYQdVOk2kRjnvsqKuXctM2xdQsL84JhUMUHuchJuCt7Uw7qm','2001-12-03 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar36.png','assets/img/banner36.jpeg'),
  ('Ferris Spencer','in.ornare@protonmail.com','$2a$12$UxYzwgL2AUSmMjwB7TvYVeLdyBeL2SHe5KGBX7fpUUV2IOBQj/sTi','2001-05-09 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar37.png','assets/img/banner37.jpeg'),
  ('Fatima Garrett','sollicitudin@aol.ca','$2a$12$SVnSgNyJhAjIhOlfWbXABu0R/dSqV2SH5/.YeeDwOzS0la/Wo6x8.','2001-10-15 00:00:00','2021-10-03 00:00:00','FEUP', 'public','assets/img/avatar38.jpg','assets/img/banner38.jpeg'),
  ('Stewart Gomez','lorem.lorem@yahoo.net','$2a$12$WAA7/fnUuxQfFx1tURm4q.EaQxtDldz.MKUbxspHVWBhzSrh6W4XC','2001-10-27 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar39.jpg','assets/img/banner39.jpeg'),
  ('Clare Wiley','nec@protonmail.ca','$2a$12$uvXy9SgC2h9lx4y8.6txDeN3c.xzt4KLkfJwdi8mOboFgxMIr7f9.','2001-07-20 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar40.jpg','assets/img/banner40.jpeg');
INSERT INTO users (nome,email,password,dataNascimento,dataTempoRegisto,instituicaoEnsino, privacidade,fotoPerfil,fotoHeader)
VALUES
  ('Lucian Gillespie','augue@protonmail.com','$2a$12$PI/qxjpZBsAA9oqyCHoGkuTVdb719p1GMaxOJ7amIZv6iH9vk2ZcK','2001-06-07 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar41.jpg','assets/img/banner41.jpg'),
  ('Arthur Carlson','eu.ligula@google.ca','$2a$12$R0xAmx4ScTHvPhgVbjzbeO6iQMXIRxIu.1lrGM2cUXZ.BmaJX0n.S','2001-04-29 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar46.jpg','assets/img/banner42.jpg'),
  ('Jamalia Sullivan','aliquet.metus@outlook.net','$2a$12$PQgPU3mZwRxm8TysHRD1o.EcD.LXOFulf6sS1/s/OgXHgqt3XmqIC','2001-02-27 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar43.jpg','assets/img/banner43.jpeg'),
  ('Lucius Christensen','suspendisse@hotmail.com','$2a$12$idP332bebTMk76Swv9qVJ.Bp2uvUbiKJ.pcu9ff28k0qGZSQ/CUE6','2002-12-22 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar44.jpg','assets/img/banner44.jpeg'),
  ('Ila Reyes','consequat.purus.maecenas@outlook.com','$2a$12$BW17jX.XULYtHzBkPC4mZ.WXcmaviu6t4iPCJwBDaEJZ86JGfr/Ua','2002-05-24 00:00:00','2021-10-03 00:00:00','FEUP', 'private','assets/img/avatar45.jpg','assets/img/banner45.jpeg');
INSERT INTO utilizadorDocente (id,departamento,formacao)
VALUES
  (45,'DEI','LEIC'),
  (1,'DEQ','LEQ'),
  (2,'DEI','LEIC'),
  (3,'DEQ','LEQ'),
  (4,'DEI','LEIC'),
  (5,'DEQ','LEQ'),
  (6,'DEI','LEIC'),
  (7,'DEQ','LEQ'),
  (8,'DEI','LEIC'),
  (9,'DEQ','LEQ');
INSERT INTO utilizadorEstudante (id,curso,anoCorrente,media)
VALUES
-- Grupo 1 de Trabalho
  (10,'LEIC',3,11),
  (11,'LEIC',3,19),
  (12,'LEIC',3,13),
  (13,'LEIC',3,19),
  (14,'LEIC',3,17),
  (15,'LEIC',3,10),
  (16,'LEIC',3,13),
  (17,'LEIC',3,17),
  (18,'LEIC',3,19),
  (19,'LEIC',3,13);
INSERT INTO utilizadorEstudante (id,curso,anoCorrente,media)
VALUES
-- Grupo 2 de Lazer
  (20,'LEQ',5,11),
  (21,'LEQ',5,19),
  (22,'LEQ',5,13),
  (23,'LEQ',5,19),
  (24,'LEQ',5,17),
  (25,'LEQ',5,10),
  (26,'LEQ',5,13),
  (27,'LEQ',5,17),
  (28,'LEQ',5,19),
  (29,'LEQ',5,13);
INSERT INTO utilizadorEstudante (id,curso,anoCorrente,media)
VALUES
-- Sem grupos.
  (30,'LEIC',3,11),
  (31,'LEQ',3,19),
  (32,'LEIC',3,13),
  (33,'LEQ',3,19),
  (34,'LEIC',3,17),
  (35,'LEQ',5,10),
  (36,'LEIC',5,13),
  (37,'LEQ',5,17),
  (38,'LEIC',5,19),
  (39,'LEQ',5,13);
INSERT INTO utilizadorEstudante (id,curso,anoCorrente,media)
VALUES
  (40,'LEIC',5,11),
  (41,'LEQ',5,19),
  (42,'LEIC',5,13),
  (43,'LEQ',5,19),
  (44,'LEIC',5,17);
INSERT INTO notificacao (tipo)
VALUES
  ('post'),
  ('like'),
  ('post'),
  ('comment'),
  ('comment'),
  ('post'),
  ('post'),
  ('like'),
  ('post');
INSERT INTO notificacao (tipo)
VALUES
  ('like'),
  ('like'),
  ('comment');
INSERT INTO notificacaoUtilizador (idUtilizador,idNotificacao,vista)
VALUES
  (1,1,'True'),
  (20,2,'True'),
  (21,2,'False'),
  (22,2,'True'),
  (24,2,'True'),
  (25,2,'True'),
  (26,2,'True'),
  (27,2,'True'),
  (28,2,'True');
INSERT INTO notificacaoUtilizador (idUtilizador,idNotificacao,vista)
VALUES
  (29,2,'True'),
  (23,3,'True'),
  (23,4,'True'),
  (44,7,'True'),
  (13,9,'False'),
  (13,10,'False'),
  (13,11,'False');
INSERT INTO publicacao(dataTempo, numeroGostos, conteudo,anexo,idNotificacao,idUtilizador,tipoAnexo,privacidade)
            VALUES('2021-10-25 10:23:54',10, 'O céu é azul.',NULL,1, 1, 'NULL','public');

INSERT INTO publicacao(dataTempo, numeroGostos, conteudo,anexo,idNotificacao,idUtilizador,tipoAnexo,privacidade)
            VALUES('2021-10-25 16:22:04',100,'Quantos dias até o filme do Batman?',NULL,3, 3, 'NULL','public');

INSERT INTO publicacao(dataTempo, numeroGostos,conteudo,anexo,idNotificacao,idUtilizador,tipoAnexo,privacidade)
            VALUES('2021-11-05 06:03:44',5, NULL,'assets/img/avatar10.jpg',6, 5, 'photo','private');

INSERT INTO publicacao(dataTempo, numeroGostos, conteudo,anexo,idNotificacao,idUtilizador,tipoAnexo,privacidade)
            VALUES('2021-11-20 22:55:54',6,'Está uma confusão lá fora...',NULL,7, 6, 'NULL','private');

INSERT INTO publicacao(dataTempo, numeroGostos, conteudo, anexo, idNotificacao, idUtilizador,tipoAnexo,privacidade)
            VALUES('2021-11-22 23:23:54',2,'Já estou farto de ouvir músicas de Natal!',NULL,9, 2, 'NULL','public');
INSERT INTO gosto (idNotificacao,idUtilizador,idPublicacao)
VALUES
  (2,3,1),
  (8,7,4),
  (10,20,5),
  (11,23,5);
INSERT INTO grupoPublicacao (idGrupo,idPublicacao)
VALUES
  (2,2);
INSERT INTO moderador (idGrupo,idUtilizador)
VALUES 
    (1, 45),
    (1, 10),
    (2, 20),
    (2, 21);
INSERT INTO comentario (dataTempo,conteudo,idNotificacao,idUtilizador,idPublicacao)
VALUES
  ('2021-10-25 19:22:04','Em março, acho eu',4,14,2),
  ('2021-10-26 09:02:04','Março amigo ;)',5,25,2),
  ('2021-11-23 03:23:54','Que grinch',12,20,5);
INSERT INTO administrador (email,password, idDocente)
VALUES
  ('consequat.purus.maecenas@outlook.com','Integeraliquamadipiscinglacus.Ut',45),
  ('tellus.nunc.lectus@aol.couk','euaugueporttitor',1),
  ('lacinia.sed.congue@yahoo.couk','euismodac,fermentum vel,',2),
  ('fringilla.porttitor.vulputate@aol.couk','acmetusvitaevelit',3),
  ('eget@hotmail.ca','a, dui.',4),
  ('admin@gmail.com','$2a$12$awun9GczM65BkY0T4ALoIO195L7uHPujRO9EsNllPMhBzcd8qd9VG',NULL),
  ('cras@yahoo.edu','vitaediam.Proindolor. Nulla',NULL),
  ('et@yahoo.net','lobortis.Classaptent',NULL),
  ('cras.sed@google.net','purusmaurisanunc.',NULL),
  ('est.tempor@aol.net','seddictumeleifend,nuncrisus',NULL);
INSERT INTO colega (utilizador1,utilizador2)
VALUES
  (30, 31),
  (32, 33),
  (34, 35),
  (36, 37),
  (38, 39),
  (40, 41),
  (42, 43),
  (3,1),
  (1 , 3),
  (43, 44);
INSERT INTO grupoUtilizador (idUtilizador,idGrupo)
VALUES
  (10, 1),
  (11, 1),
  (12, 1),
  (13, 1),
  (14, 1),
  (15, 1),
  (16, 1),
  (17, 1),
  (18, 1),
  (19, 1);
INSERT INTO grupoUtilizador (idUtilizador,idGrupo)
VALUES
  (20, 2),
  (21, 2),
  (22, 2),
  (23, 2),
  (24, 2),
  (25, 2),
  (26, 2),
  (27, 2),
  (28, 2),
  (29, 2);