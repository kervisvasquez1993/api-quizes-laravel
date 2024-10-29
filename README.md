# Proyecto Laravel con Docker

Este proyecto es una aplicación Laravel configurada para ejecutarse en un entorno Docker. Sigue los pasos a continuación para configurarlo y levantarlo.

## Requisitos

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Instrucciones de Configuración

1. **Clona el repositorio y navega a la carpeta del proyecto:**

   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd <NOMBRE_DEL_PROYECTO>
   
   Copia el archivo de entorno:

   cp .env.example .env

    Levanta los contenedores de Docker:

    docker-compose up -d

    Instala las dependencias de Composer:

    Ejecuta Composer dentro del contenedor de la aplicación:

    docker-compose exec app composer install

    Ejecuta las migraciones y los seeders:

    Esto crea la base de datos y llena las tablas con datos iniciales:

    docker-compose exec app php artisan migrate:fresh --seed

    Configura Passport para autenticación de API:

    Crea un cliente personal de Passport para el manejo de tokens:

    docker-compose exec app php artisan passport:client --personal

    Crea el enlace simbólico para almacenamiento:

    Esto hace que el almacenamiento público esté disponible en /storage:
    
    docker-compose exec app php artisan storage:link

    Ajusta los permisos de las carpetas:

    docker-compose exec app chown -R www-data:www-data /var/www/html -R

    Inicia el worker para procesar los jobs:

    Laravel usa colas para gestionar tareas en segundo plano, y puedes iniciar 
    un worker para que procese los jobs en la base de datos:

    docker-compose exec app php artisan queue:work --daemon


    Ahora, puedes acceder a la aplicación en http://localhost:8000

    Comandos Útiles
    Apagar los contenedores:

    docker-compose down

    Ejecutar comandos de Artisan adicionales:

    Puedes ejecutar cualquier comando de Artisan con:

    docker-compose exec app php artisan <comando>
    
    ```







  

> Project Structure

  

**We worked with a clean design pattern following the repository pattern, where unique responsibilities are assigned to each layer. The layers worked on are:
  
Rules Layer: This layer is responsible for defining business rules before reaching the controller.
  
Controller: The controller is responsible for returning the HTTP response along
  
with the requested data.
  
Service: In this layer, the business logic is added, where necessary operations are managed.
  
Repository Layer: This layer interacts with the database, inserting and retrieving information. It serves as an intermediary between the controller and the service.
  
Additionally, it includes a DTO (Data Transfer Object) that assists in transferring data between the different layers of the system.**

  

> Database Structure



**User Model: username, email, password, role
  
Quiz Model: title, description, user\_id
  
Question Model: quiz\_id, question, image, correct\_answer, user\_id PlayerAnswer Model: user\_id, question\_id, given\_answer, is\_correct**


## API Routes

### Authentication
| Method | Route         | Description               |
|--------|---------------|---------------------------|
| POST   | `/login`      | User login                |
| POST   | `/register`   | User registration         |

### User
| Method | Route                  | Description                        |
|--------|-------------------------|------------------------------------|
| GET    | `/list-user-point`     | List points for each user         |
| GET    | `/me`                  | Get current authenticated user    |
| GET    | `/profile`             | Get current user profile details  |

### Quiz
| Method | Route             | Description                  |
|--------|--------------------|------------------------------|
| GET    | `/quiz`           | List all quizzes             |
| GET    | `/quiz/{id}`      | Get details of a specific quiz |
| POST   | `/quiz`           | Create a new quiz            |
| PUT    | `/quiz/{id}`      | Update a specific quiz       |
| DELETE | `/quiz/{id}`      | Delete a specific quiz       |

### Questions
| Method | Route                                 | Description                                      |
|--------|---------------------------------------|--------------------------------------------------|
| GET    | `/quiz/{quizId}/questions`            | List questions for a specific quiz               |
| POST   | `/quiz/{quizId}/questions`            | Add a question to a specific quiz                |
| PUT    | `/questions/{id}`                     | Update a specific question                       |
| POST   | `/questions/{id}/image`               | Update the image of a specific question          |
| DELETE | `/questions/{id}`                     | Delete a specific question                       |

### Player Answers
| Method | Route                                          | Description                                           |
|--------|------------------------------------------------|-------------------------------------------------------|
| GET    | `/user/{id}/answers`                           | List answers for a specific user                      |
| GET    | `/questions/{id}/answers`                      | List answers for a specific question                  |
| POST   | `/questions/{questionsId}/player-answer`       | Submit an answer to a question                        |
| GET    | `/my-answer-question`                          | List answers submitted by the authenticated user      |

> **Note**: Routes under `/me`, `/profile`, `/quiz`, `/questions`, and `/my-answer-question` require authentication (i.e., they are protected by the `auth:api` middleware).
