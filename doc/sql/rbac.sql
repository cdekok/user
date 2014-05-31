/**
* User table
**/
CREATE TABLE rbac_user
(
  username character varying(70) NOT NULL,
  password character varying(255) NOT NULL,
  email character varying(255) NOT NULL,
  created timestamp without time zone NOT NULL,
  modified timestamp without time zone,
  CONSTRAINT rbac_user_pkey PRIMARY KEY (username),
  CONSTRAINT rbac_user_email_uniq UNIQUE (email),
  CONSTRAINT rbac_user_username_valid CHECK (username::text ~* '^[a-z0-9-]+$'::text)
);

CREATE INDEX rbac_user_email
  ON rbac_user
  USING btree
  (email COLLATE pg_catalog."default");

/**
* Roles
**/
CREATE TABLE rbac_role
(
  title character varying(255) NOT NULL,
  description text,
  created timestamp without time zone NOT NULL,
  modified timestamp without time zone,
  CONSTRAINT rbac_role_pkey PRIMARY KEY (title)
);

/**
* Permissions
**/
CREATE TABLE rbac_permission
(
  title character varying(255) NOT NULL,
  resource character varying(255) NOT NULL,
  description text,  
  created timestamp without time zone NOT NULL,
  modified timestamp without time zone,
  CONSTRAINT rbac_permission_pkey PRIMARY KEY (title, resource)
);

/**
* Role has permission
**/

CREATE TABLE rbac_role_has_permission
(
  role_title character varying(255) NOT NULL,
  permission_title character varying(255) NOT NULL,
  permission_resource character varying(255) NOT NULL,
  CONSTRAINT rbac_rhp_pkey PRIMARY KEY (role_title, permission_title),
  CONSTRAINT rbac_rhp_fk_permission FOREIGN KEY (permission_title, permission_resource)
      REFERENCES rbac_permission (title, resource) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT rbac_rhp_fk_role_title FOREIGN KEY (role_title)
      REFERENCES rbac_role (title) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
);

/**
* User has role
**/
CREATE TABLE rbac_user_has_role
(
  user_username character varying(70) NOT NULL,
  role_title character varying(255) NOT NULL,
  CONSTRAINT rbac_user_role_pkey PRIMARY KEY (user_username, role_title),
  CONSTRAINT rbac_fk_role_title FOREIGN KEY (role_title)
      REFERENCES rbac_role (title) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT rbac_fk_user_username FOREIGN KEY (user_username)
      REFERENCES rbac_user (username) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
);