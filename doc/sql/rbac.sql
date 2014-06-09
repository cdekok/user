-- object: public.user | type: TABLE --
-- DROP TABLE public.user;
CREATE TABLE public.user(
	username character varying(70) NOT NULL,
	password character varying(255) NOT NULL,
	email character varying(255) NOT NULL,
	status integer NOT NULL,
	profile json,
	created timestamp NOT NULL,
	modified timestamp,
	CONSTRAINT rbac_user_username_valid CHECK (((username)::text ~* '^[a-z0-9-]+$'::text)),
	CONSTRAINT rbac_user_pkey PRIMARY KEY (username),
	CONSTRAINT rbac_user_email_uniq UNIQUE (email)

);
-- ddl-end --
-- object: rbac_user_email | type: INDEX --
-- DROP INDEX public.rbac_user_email;
CREATE INDEX rbac_user_email ON public.user
	USING btree
	(
	  email
	)	WITH (FILLFACTOR = 90);
-- ddl-end --


COMMENT ON COLUMN public.user.profile IS 'custom fields';
ALTER TABLE public.user OWNER TO blog;
-- ddl-end --

-- object: public.role | type: TABLE --
-- DROP TABLE public.role;
CREATE TABLE public.role(
	title character varying(255) NOT NULL,
	description text,
	parent character varying(255),
	created timestamp NOT NULL,
	modified timestamp,
	CONSTRAINT rbac_role_pkey PRIMARY KEY (title)

);
-- ddl-end --
ALTER TABLE public.role OWNER TO blog;
-- ddl-end --

-- object: public.permission | type: TABLE --
-- DROP TABLE public.permission;
CREATE TABLE public.permission(
	title character varying(255) NOT NULL,
	resource character varying(255) NOT NULL,
	description text,
	created timestamp NOT NULL,
	modified timestamp,
	CONSTRAINT rbac_permission_pkey PRIMARY KEY (title,resource)

);
-- ddl-end --
ALTER TABLE public.permission OWNER TO blog;
-- ddl-end --

-- object: public.role_has_permission | type: TABLE --
-- DROP TABLE public.role_has_permission;
CREATE TABLE public.role_has_permission(
	role_title character varying(255) NOT NULL,
	permission_title character varying(255) NOT NULL,
	permission_resource character varying(255) NOT NULL,
	CONSTRAINT rbac_rhp_pkey PRIMARY KEY (role_title,permission_title)

);
-- ddl-end --
ALTER TABLE public.role_has_permission OWNER TO blog;
-- ddl-end --

-- object: public.user_has_role | type: TABLE --
-- DROP TABLE public.user_has_role;
CREATE TABLE public.user_has_role(
	user_username character varying(70) NOT NULL,
	role_title character varying(255) NOT NULL,
	CONSTRAINT rbac_user_role_pkey PRIMARY KEY (user_username,role_title)

);
-- ddl-end --
ALTER TABLE public.user_has_role OWNER TO blog;
-- ddl-end --

-- object: public.login_history | type: TABLE --
-- DROP TABLE public.login_history;
CREATE TABLE public.login_history(
	uuid uuid,
	username_user character varying(70),
	ip inet NOT NULL,
	status smallint NOT NULL,
	created smallint NOT NULL,
	CONSTRAINT uuid PRIMARY KEY (uuid)

);
-- ddl-end --
-- object: user_fk | type: CONSTRAINT --
-- ALTER TABLE public.login_history DROP CONSTRAINT user_fk;
ALTER TABLE public.login_history ADD CONSTRAINT user_fk FOREIGN KEY (username_user)
REFERENCES public.user (username) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE;
-- ddl-end --


-- object: login_attempt_username_fk | type: INDEX --
-- DROP INDEX public.login_attempt_username_fk;
CREATE INDEX login_attempt_username_fk ON public.login_history
	USING btree
	(
	  username_user ASC NULLS LAST
	);
-- ddl-end --

-- object: parent_role | type: CONSTRAINT --
-- ALTER TABLE public.role DROP CONSTRAINT parent_role;
ALTER TABLE public.role ADD CONSTRAINT parent_role FOREIGN KEY (parent)
REFERENCES public.role (title) MATCH FULL
ON DELETE NO ACTION ON UPDATE NO ACTION;
-- ddl-end --


-- object: rbac_rhp_fk_permission | type: CONSTRAINT --
-- ALTER TABLE public.role_has_permission DROP CONSTRAINT rbac_rhp_fk_permission;
ALTER TABLE public.role_has_permission ADD CONSTRAINT rbac_rhp_fk_permission FOREIGN KEY (permission_title,permission_resource)
REFERENCES public.permission (title,resource) MATCH SIMPLE
ON DELETE CASCADE ON UPDATE CASCADE;
-- ddl-end --


-- object: rbac_rhp_fk_role_title | type: CONSTRAINT --
-- ALTER TABLE public.role_has_permission DROP CONSTRAINT rbac_rhp_fk_role_title;
ALTER TABLE public.role_has_permission ADD CONSTRAINT rbac_rhp_fk_role_title FOREIGN KEY (role_title)
REFERENCES public.role (title) MATCH SIMPLE
ON DELETE CASCADE ON UPDATE CASCADE;
-- ddl-end --


-- object: rbac_fk_role_title | type: CONSTRAINT --
-- ALTER TABLE public.user_has_role DROP CONSTRAINT rbac_fk_role_title;
ALTER TABLE public.user_has_role ADD CONSTRAINT rbac_fk_role_title FOREIGN KEY (role_title)
REFERENCES public.role (title) MATCH SIMPLE
ON DELETE CASCADE ON UPDATE CASCADE;
-- ddl-end --


-- object: rbac_fk_user_username | type: CONSTRAINT --
-- ALTER TABLE public.user_has_role DROP CONSTRAINT rbac_fk_user_username;
ALTER TABLE public.user_has_role ADD CONSTRAINT rbac_fk_user_username FOREIGN KEY (user_username)
REFERENCES public.user (username) MATCH SIMPLE
ON DELETE CASCADE ON UPDATE CASCADE;
-- ddl-end --


