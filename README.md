# Projeto Laravel com Docker

Este projeto utiliza Laravel 11 e Docker para criar uma aplicação web. Abaixo estão os passos para configurar e executar o projeto.

## Requisitos

- Docker
- Docker Compose

## Configuração do Projeto

1. **Construir as imagens do Docker:**

   ```bash
   docker-compose build
Iniciar os contêineres em segundo plano:
docker-compose up -d
Instalar as dependências do Composer:
docker-compose exec app composer install
Copiar o arquivo de exemplo de configuração:
cp .env.example .env
Executar migrações e semear a base de dados:
docker-compose exec app php artisan migrate --seed
Gerar chaves para o Passport:
docker-compose exec app php artisan passport:key
docker-compose exec app php artisan passport:client --personal
Criar links simbólicos para o armazenamento:
docker-compose exec app php artisan storage:link
Observação: Se você não estiver usando Docker para levantar o projeto, execute o seguinte comando para gerar a chave da aplicação:
php artisan key:generate
Estrutura do Projeto
Trabalhamos com um padrão de design limpo seguindo o padrão de repositório, onde responsabilidades únicas são atribuídas a cada camada. As camadas trabalhadas são:

Camada de Regras (Rules): Esta camada é responsável por definir as regras de negócio antes de chegar ao controlador.

Controlador: O controlador é responsável por devolver a resposta HTTP junto com os dados solicitados.

Serviço: Nesta camada, é adicionada a lógica de negócio, onde as operações necessárias são gerenciadas.

Camada de Repositório: Esta camada interage com a base de dados, inserindo e recuperando informações. Funciona como intermediária entre o controlador e o serviço.

Além disso, inclui um DTO (Data Transfer Object) que auxilia na transferência de dados entre as diferentes camadas do sistema.

Estrutura do Banco de Dados
Os modelos do banco de dados são os seguintes:

Modelo User
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];
}
Modelo Quiz
class Quiz extends Model
{
    protected $table = 'quizzes';
    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];
}
Modelo Question
class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = ['quiz_id', 'question', 'image', 'correct_answer', 'user_id'];
}

Modelo PlayerAnswer
class PlayerAnswer extends Model
{
    protected $fillable = ['user_id', 'question_id', 'given_answer', 'is_correct'];
}
