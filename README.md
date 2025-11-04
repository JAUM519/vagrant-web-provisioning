# README - Taller de Provisionamiento con Vagrant, Apache, PHP y PostgreSQL

## Descripción
Este proyecto implementa un entorno de desarrollo con **Vagrant** que levanta dos máquinas virtuales:
- **web**: Servidor Apache con PHP.
- **db**: Servidor PostgreSQL.

El objetivo es desplegar una página web en HTML y PHP que se conecte a una base de datos PostgreSQL y muestre datos en el navegador.

---

## Pasos realizados

### 1. Creación del proyecto
1. Se clonó el repositorio en el host.
2. Dentro de la carpeta se añadieron o modificaron los siguientes archivos:
   - `Vagrantfile`
   - `provision-web.sh`
   - `provision-db.sh`
   - Carpeta `www/` con los archivos del sitio (`index.html`, `info.php`, `data.php`).

---

### 2. Configuración del `Vagrantfile`
- Se definieron **dos máquinas virtuales**:
  - `web` con IP `192.168.56.19`.
  - `db` con IP `192.168.56.20`.
- Ambas basadas en la box `bento/ubuntu-20.04`.
- Se configuró una **carpeta compartida** entre el host y la VM (`./www` ↔ `/vagrant/www`).
- Se asignaron los scripts de provisionamiento correspondientes.

```ruby
Vagrant.configure("2") do |config|

  config.vm.define "web" do |web|
    web.vm.box = "bento/ubuntu-20.04"
    web.vm.network "private_network", ip: "192.168.56.19"
    web.vm.provision "shell", path: "provision-web.sh"
  end

  config.vm.define "db" do |db|
    db.vm.box = "bento/ubuntu-20.04"
    db.vm.network "private_network", ip: "192.168.56.20"
    db.vm.provision "shell", path: "provision-db.sh"
  end
end
```

---

### 3. Descripción del script `provision-web.sh`
- Instala **Apache** y **PHP**.
- Habilita el módulo PHP en Apache.
- Copia los archivos desde la carpeta compartida `/vagrant/www` hacia `/var/www/html`.
- Reinicia el servicio Apache para activar el sitio.

---

### 4. Creación del script `provision-db.sh`
- Instala **PostgreSQL** y sus componentes.
- Configura PostgreSQL para aceptar conexiones externas.
- Permite el acceso desde la máquina `web` (`192.168.56.19`).
- Crea una base de datos llamada `appdb` y un usuario `appuser`.
- Crea una tabla `people` con datos de ejemplo (nombre y correo electrónico).

---

### 5. Creación del sitio web (`www/`)
Se modificó la carpeta `www/` con los siguientes archivos:

#### a) `index.html`
Página de bienvenida con el nombre “Jorge Andrés Medina Urrutia” y enlaces a los otros scripts PHP.

#### b) `info.php`
Script con `phpinfo()` para verificar la correcta instalación de PHP.

#### c) `data.php`
Conexión a la base de datos PostgreSQL desde PHP usando PDO, mostrando los registros de la tabla `people` en una tabla HTML.

---

### 6. Levantamiento del entorno
1. Se ejecutó el comando:
   ```bash
   vagrant up
   ```
2. Vagrant descargó las máquinas, ejecutó los scripts y configuró ambos entornos automáticamente.

---

### 7. Verificación
- En el navegador del host:
  - `http://192.168.56.19` → Página principal (`index.html`).
  - `http://192.168.56.19/info.php` → Información de PHP.
  - `http://192.168.56.19/data.php` → Datos cargados desde PostgreSQL.

---

### 8. Conclusión
Con este proceso se automatizó la creación de un entorno web completo usando **Vagrant**.  
Se levantaron dos máquinas virtuales integradas: una para la capa de aplicación (Apache/PHP) y otra para la capa de datos (PostgreSQL), permitiendo la comunicación entre ambas mediante IPs privadas.

---

## Estructura final del proyecto

      ├─ Vagrantfile
      ├─ LICENSE
      ├─ provision-web.sh
      ├─ provision-db.sh
      └─ www/
            ├─ index.html
            ├─ info.php
            └─ data.php

---

## Autor
**Jorge Andrés Medina Urrutia**  
Ingeniería Informática – Universidad Autónoma de Occidente
