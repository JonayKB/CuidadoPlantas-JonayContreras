# CuidadoPlantas
- [CuidadoPlantas](#cuidadoplantas)
  - [Plantas](#plantas)
  - [How to use project](#how-to-use-project)
  - [Entity Relationship Diagram](#entity-relationship-diagram)
  - [Database Structure](#database-structure)
  - [Test Coverage](#test-coverage)

## Plantas 
“Plants” is a social network that is based on helping users with plant problems, contains the ability to upload multiple images and secondary database in case the main one fails, plus soft deletes.
It consists of the application used by the users and an administrative page that performs almost all the necessary functionalities.

## How to use project

- Download the project
- Run docker-compose 
  ```
  cd Plantas
  docker-compose up -d --build
  ```
- Wait mysql to connect
- Enter http://localhost:8093
- You need to login as administrator to verify an user:
  - email: root@gmail.com
  - password: 12345678
- Enjoy!
  
## Entity Relationship Diagram

<div align="center">
<img src="Diagramas/DiagramaEntidadRelacion.drawio.png">
</div>

## Database Structure
<div align="center">
<img src="Diagramas/DiagramaBaseDeDatos.png">
</div>





## Test Coverage
[View Test Coverage](Plantas/coverage/index.html)
