#!/usr/bin/env bash
set -e

sudo apt-get update -y
sudo apt-get install -y postgresql postgresql-contrib

# Escuchar en todas las interfaces
sudo sed -i "s/^#\?listen_addresses.*/listen_addresses = '*'/" /etc/postgresql/*/main/postgresql.conf

# Permitir acceso desde la VM web (192.168.56.19)
PG_HBA="/etc/postgresql/$(ls /etc/postgresql)/main/pg_hba.conf"
sudo bash -c "echo 'host    all             all             192.168.56.19/32        md5' >> ${PG_HBA}"

sudo systemctl enable postgresql
sudo systemctl restart postgresql

# Credenciales y objetos
DB_NAME="appdb"
DB_USER="appuser"
DB_PASS="appsecret"

sudo -u postgres psql <<SQL
DO
\$do\$
BEGIN
   IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = '${DB_USER}') THEN
      CREATE ROLE ${DB_USER} LOGIN PASSWORD '${DB_PASS}';
   END IF;
END
\$do\$;

CREATE DATABASE ${DB_NAME} OWNER ${DB_USER} TEMPLATE template1;
GRANT ALL PRIVILEGES ON DATABASE ${DB_NAME} TO ${DB_USER};
SQL

sudo -u postgres psql -d ${DB_NAME} <<SQL
CREATE TABLE IF NOT EXISTS people (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL
);

INSERT INTO people (name, email) VALUES
  ('Jorge Medina', 'jorge_andres.medina@uao.edu.co'),
  ('Angie Ílamo', 'angie.ilamo@uao.edu.co')
ON CONFLICT DO NOTHING;
SQL

sudo -u postgres psql -d ${DB_NAME} <<SQL
GRANT CONNECT ON DATABASE ${DB_NAME} TO ${DB_USER};
GRANT USAGE ON SCHEMA public TO ${DB_USER};
ALTER TABLE IF EXISTS public.people OWNER TO ${DB_USER};
ALTER SEQUENCE IF EXISTS public.people_id_seq OWNER TO ${DB_USER};
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO ${DB_USER};
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO ${DB_USER};
ALTER DEFAULT PRIVILEGES IN SCHEMA public
  GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO ${DB_USER};
ALTER DEFAULT PRIVILEGES IN SCHEMA public
  GRANT USAGE, SELECT ON SEQUENCES TO ${DB_USER};
SQL