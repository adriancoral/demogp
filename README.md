# Demo GP

API demo GP en Laravel 9.19, PHP 8.1

## Inicio

Leer todo este archivo primero. Entorno utilizado en esta descripción Ubuntu 18.4

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local para propósitos de desarrollo y testing._

### Requisitos

-   Docker - [How to install](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04)
-   Docker-compose - [How to install](https://www.digitalocean.com/community/tutorials/how-to-install-docker-compose-on-ubuntu-18-04)

**Docker & Compose versions used**

-   Docker version 20.10.21, build baeda1f
-   docker-compose version 1.21.2, build a133471
-   Docker-compose YML V3

**Docker image base**
- [php:8.1.13-apache-buster] (https://hub.docker.com/_/php)
- [mysql:5.7.22] (https://hub.docker.com/_/mysql)
- [adminer:latest] (https://hub.docker.com/_/adminer)


**Docker Ports**

-   :::80->80/tcp (Port)
-   :::8080->8080/tcp (port)
-   :::3306->3306/tcp (port)

**Laravel Framework & Paquetes**

```sh
./composer.json
```

### Instalación

Setear usuario en el grupo de docker (en caso de no tenerlo):

sudo usermod -aG docker <myusername>

NOTA: es importante cerrar sesión y volver a iniciarla (o reiniciar) para que los cambios se apliquen

La primera vez se deben crear las imágenes docker, la ejecución puede tomar unos minutos,
si la imagen base no se encuentra en el host, baja automáticamente y luego comienza la instalación de
todos los paquetes indicados en el docker file.

Los puertos 80, 8080 y 3306 TCP deben estar libres siempre que se arranque el contenedor, de lo contrario fallara

Clonar este repositorio

```sh
git clone git@github.com:adriancoral/demogp.git .

```

Iniciar el stack de docker

```bash
# Primera vez
docker-compose up -d --build

# Luego
docker-compose up -d

# Detener los servicios
docker-compose down

```

La aplicación se montara dentro del contenedor en /var/www/

```bash
#  docker ps: muestra los contenedores activos
docker ps
CONTAINER ID   IMAGE            COMMAND                  CREATED        STATUS        PORTS                                       NAMES
a126a05db5bb   php81apache:gp   "docker-php-entrypoi…"   14 hours ago   Up 14 hours   0.0.0.0:80->80/tcp, :::80->80/tcp           httpservergp
554e33a193d4   adminer          "entrypoint.sh docke…"   14 hours ago   Up 14 hours   0.0.0.0:8080->8080/tcp, :::8080->8080/tcp   adminer
5f77e94b2fea   mysql:5.7.22     "docker-entrypoint.s…"   14 hours ago   Up 14 hours   0.0.0.0:3306->3306/tcp, :::3306->3306/tcp   mysqldb
```

### Configuración

Para instalar los paquetes de laravel hay que entrar al contendor PHP, la base de datos ya fue creada
cuando se inició el contenedor de MySQL, los datos de acceso ya está en `.env.example`

```bash
# ingresar al contenedor
docker exec -it -u acoral httpservergp /bin/bash

#developer@zoho:
composer install

# Configurar laravel, la configuración para entorno local esta en env.example (sobreescribir el que crea laravel)
# Configurar la DB, Mailer según su entorno
cp .env.example .env

# Si todo esta bien, probamos el comando artisan
php artisan

# `migrate` crea solo la estructura de tablas
php artisan migrate

# `db:seed` crea datos para el uso de la aplicacion
php artisan db:seed

```

### Debug y logs

Si hay errores de PHP se mostraran por el stdout del contenedor o en los logs,
alternativamente y dependiendo de la configuración, se puede entrar al contenedor para ver otros logs

```bash
# Ver logs con docker-composer
docker-compose logs -f

# Logs con docker
docker logs -f [nombre-del-contenedor]

# Ingreso al contenedor
docker exec -it [nombre-del-contenedor] /bin/bash

```

### Problemas comunes
Si nos encontramos con puertos en uso, primero verificamos no haya otras instancias
```
docker-compose down  # Stop container on current dir if there is a docker-compose.yml
docker rm -fv $(docker ps -aq)  # Remove all containers
sudo lsof -i -P -n | grep <port number>  # List who's using the port
sudo kill -9 <pid>

```
### Testing

La app implementa test unitarios y features con PHPUnit.

[PHPUnit Manual](https://phpunit.readthedocs.io/en/9.3/index.html)

[Laravel Mocking](https://laravel.com/docs/8.x/mocking)

```bash
docker exec -it -u acoral httpservergp /bin/bash

# Run whole suite
php artisan test 
php artisan test --filter TournamentTest

# Testing from host
docker exec -it -u acoral httpservergp php artisan test

# PhpUnit CLI Options
# https://phpunit.readthedocs.io/en/9.3/textui.html#command-line-options
```

Output

```bash
 PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\TournamentTest
  ✓ a tournament can be created

  Tests:  2 passed
  Time:   0.24s
```

## API Description

La aplicación soporta la creación de torneos de ATP o WTA, generando una lista de competidores aleatoria 
según el número de participantes del torneo y la asociación seleccionada. La misma está orientada a eventos, 
por cada torneo creado se genera el evento `TournamentCreated` el es escuchado por el listener `PlayersRandomEnrollment` 
este ultimo genera la lista de usuarios aleatorios para el torneo, al terminar cambia el estado del torneo a 'Playing',
El cambio de estado despache el job `GameSimulation`, el cual se encarga de la simulacion de las fases y de cada juego 
hasta llegar al final del toneo.


## Endpoints Documentation

### Register User

POST http://localhost/api/register

```json
{
	"name": "Pepe",
	"email": "pepe@argento.com",
	"password": "12345678"
}
```
Response
```json
{
    "success": true,
    "payload": {
        "user": {
            "name": "Pepe",
            "email": "pepe@argento.com",
            "updated_at": "2022-12-06T15:34:15.000000Z",
            "created_at": "2022-12-06T15:34:15.000000Z",
            "id": 7
        },
        "token": "9|9oVuzCExdafbT1NSiTB2NYGqBgEcKahgM4kPU3Cs",
        "token_type": "bearer"
    }
}
```

### Login User

POST http://localhost/api/login

```json
{
    "email": "pepe@argento.com",
    "password": "12345678"
}
```
Response
```json
{
    "success": true,
    "payload": {
        "user": {
            "id": 5,
            "name": "Pepe",
            "email": "pepe@argento.com",
            "email_verified_at": null,
            "created_at": "2022-12-05T03:07:15.000000Z",
            "updated_at": "2022-12-05T03:07:15.000000Z"
        },
        "token": "8|5UpuEXaSSJNvWyjiyIzkv2QUPuR12wp3gsc7aZ1p",
        "token_type": "bearer"
    }
}
```

### User list

GET http://localhost/api/users

Response
```json
{
    "success": true,
    "payload": [
        {
            "id": 1,
            "name": "Pepe",
            "email": "pepe@argento.com",
            "email_verified_at": null,
            "created_at": "2022-12-05T00:51:32.000000Z",
            "updated_at": "2022-12-05T00:51:32.000000Z"
        },
        {
            "id": 3,
            "name": "Moni",
            "email": "moni@argento.com",
            "email_verified_at": null,
            "created_at": "2022-12-05T00:56:49.000000Z",
            "updated_at": "2022-12-05T00:56:49.000000Z"
        },
    ]
}
```

### User me

GET http://localhost/api/me

Response
```json
{
    "success": true,
    "payload": {
        "id": 5,
        "name": "Pepe",
        "email": "pepe@argento.com",
        "email_verified_at": null,
        "created_at": "2022-12-05T03:07:15.000000Z",
        "updated_at": "2022-12-05T03:07:15.000000Z"
    }
}
```

### User logout

POST http://localhost/api/logout

Response
```json
{
    "success": true,
    "payload": [
        "ok"
    ]
}
```

### Create Tournament

POST http://localhost/api/tournament

- `association` supported values: "atp", "wta"

- `players_total` supported values: 32, 16, 8

**ATP**
```json
{
    "name": "Test ATP 16",
    "association": "atp",
    "players_total": 16
}
```

Response
```json
{
    "success": true,
    "payload": {
        "status": "playing",
        "name": "Test ATP 16",
        "association": "atp",
        "players_total": 16,
        "updated_at": "2022-12-06T13:50:05.000000Z",
        "created_at": "2022-12-06T13:50:05.000000Z",
        "id": 23
    }
}
```

**WTA**
```json
{
    "name": "Test WTA 16",
    "association": "wta",
    "players_total": 16
}
```

Response
```json
{
    "success": true,
    "payload": {
        "status": "playing",
        "name": "Test WTA 16",
        "association": "wta",
        "players_total": 16,
        "updated_at": "2022-12-06T13:50:05.000000Z",
        "created_at": "2022-12-06T13:50:05.000000Z",
        "id": 23
    }
}
```

### Tournament Results

GET http://localhost/api/tournament/23/results

Response
```json
{
    "success": true,
    "payload": {
        "tournament": {
            "id": 23,
            "name": "Test WTA 16",
            "association": "atp",
            "players_total": 16,
            "status": "playing",
            "created_at": "2022-12-06T13:50:05.000000Z",
            "updated_at": "2022-12-06T13:50:05.000000Z"
        },
        "winner": {
            "id": 138,
            "name": "Sidney Dare",
            "handicap": 98,
            "association": "atp",
            "features": {
                "speed": 84,
                "strength": 44
            },
            "created_at": "2022-12-06T11:48:15.000000Z",
            "updated_at": "2022-12-06T11:48:15.000000Z"
        }
    }
}
```

### Tournament list

GET http://localhost/api/tournament/list

Response
```json
{
    "success": true,
    "payload": [        
        {
            "id": 22,
            "name": "Test WTA 16",
            "association": "atp",
            "players_total": 16,
            "status": "playing",
            "created_at": "2022-12-06T13:49:44.000000Z",
            "updated_at": "2022-12-06T13:49:44.000000Z"
        },
        {
            "id": 23,
            "name": "Test WTA 16",
            "association": "wta",
            "players_total": 16,
            "status": "playing",
            "created_at": "2022-12-06T13:50:05.000000Z",
            "updated_at": "2022-12-06T13:50:05.000000Z"
        }
    ]
}
```

### Player Show

GET http://localhost/api/player/138

Response
```json
{
    "success": true,
    "payload": {
        "player": {
            "id": 138,
            "name": "Sidney Dare",
            "handicap": 98,
            "association": "atp",
            "features": {
                "speed": 84,
                "strength": 44
            },
            "created_at": "2022-12-06T11:48:15.000000Z",
            "updated_at": "2022-12-06T11:48:15.000000Z"
        },
        "score": {
            "games": 4,
            "win": 4,
            "champion": 1,
            "tournaments": 1
        }
    }
}
```


## Using Adminer Service

Visit http://localhost:8080

