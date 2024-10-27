**Project Laravel with Docker**

This project uses Laravel 11 and Docker to create a web application. Below are the steps to set up and run the project.
**Requirements:** 
-- Docker
-- Docker Compose

**

> Project Setup

**
**Build the Docker images: docker-compose build**

  

**Start the containers in the background: docker-compose up -d**

  

**Install Composer dependencies: docker-compose exec app composer install**

  

**Copy the example configuration file: cp .env.example .env**

  

**Run migrations and seed the database: docker-compose exec app php artisan migrate --seed**

  

**Generate keys for Passport:**

  

**docker-compose exec app php artisan passport****

  

**docker-compose exec app php artisan passport --personal**

  

**Create symbolic links for storage: docker-compose exec app php artisan storage**

  

> Note: If you are not using Docker to run the project, execute the
> following command to generate the application key:

  

**php artisan key**

  

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
