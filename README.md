# Proyecto Laravel con Docker

Este proyecto es una aplicación Laravel configurada para ejecutarse en un entorno Docker. Sigue los pasos a continuación para configurarlo y levantarlo.

## Requisitos

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

     Instrucciones de Configuración

1. **Clona el repositorio y navega a la carpeta del proyecto:**

   ```bash
    git clone <URL_DO_REPOSITORIO>
   cd <NOME_DO_PROJETO>
   
   Copie o arquivo de ambiente:

    cp .env.example .env

   Levante os contêineres do Docker:

   docker-compose up -d

   Instale as dependências do Composer:

   Execute o Composer dentro do contêiner da aplicação:

   docker-compose exec app composer install

   Execute as migrações e seeders:

   Isso cria o banco de dados e preenche as tabelas com dados iniciais:

   docker-compose exec app php artisan migrate:fresh --seed

   Configure o Passport para autenticação de API:

   Crie um cliente pessoal do Passport para gerenciar os tokens:

   docker-compose exec app php artisan passport:client --personal

   Crie o link simbólico para armazenamento:

   Isso torna o armazenamento público disponível em /storage:
    
   docker-compose exec app php artisan storage:link

   Ajuste as permissões das pastas:

   docker-compose exec app chown -R www-data:www-data /var/www/html -R

   Inicie o worker para processar os jobs:

   O Laravel usa filas para gerenciar tarefas em segundo plano. Você pode iniciar 
   um worker para processar os jobs no banco de dados:

   docker-compose exec app php artisan queue:work --daemon

   Agora, você pode acessar a aplicação em http://localhost:8000

   Comandos Úteis
   Desligar os contêineres:

   docker-compose down

   Executar comandos adicionais do Artisan:

   Você pode executar qualquer comando do Artisan com:

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
