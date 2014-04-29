CREATE TABLE role (
    id uuid NOT NULL,
    title character varying(255) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone
);

CREATE INDEX role_id
  ON role
  USING btree
  (id);

ALTER TABLE ONLY role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);

ALTER TABLE ONLY role
    ADD CONSTRAINT role_title_uniq UNIQUE (title);

CREATE TABLE "user" (
    id uuid NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone
);

CREATE INDEX user_id
  ON "user"
  USING btree
  (id);

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_username_uniq UNIQUE (username);

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_email_uniq UNIQUE (email);

CREATE TABLE user_has_role (
    user_id uuid NOT NULL,
    role_id uuid NOT NULL
);

CREATE INDEX uhr_role_id
  ON user_has_role
  USING btree
  (role_id);

CREATE INDEX uhr_user_id
  ON user_has_role
  USING btree
  (user_id);

ALTER TABLE ONLY user_has_role
    ADD CONSTRAINT user_role_pkey PRIMARY KEY (user_id, role_id);

ALTER TABLE ONLY user_has_role
    ADD CONSTRAINT fk_role_id FOREIGN KEY (role_id) REFERENCES role(id);

ALTER TABLE ONLY user_has_role
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES "user"(id);