#Library

The data in the database is placed in 3 tables (publishers, authors, books) and 2 additional tables for storing many-to-many relationships (pivot).
The project implemented filling the database with the help of seeders and Faker.

To deploy the project locally, you need to perform the following steps:
* Clone the project with ```git clone https://github.com/SystemX86/Library .```
* create empty database and specify the database connection settings in the env file
* install migrations and seeders to create a database structure and fill it with test data ```php artisan migrate --seed```
* perform dependency installations ```composer install```
* start the server with ```php artisan serve command```

the project implemented API documentation using swagger, it is available at ```/api/documentation```.
The documentation describes the implemented endpoints (list of books, adding a book, deleting, editing), call parameters, methods, you can try the requests themselves there.

work logic:
when creating a book, it is passed to the body of the json request, which indicates the title of the book, authors, publisher. if the book is already in the database, then it will not be re-added, since it is a duplicate, but if the book has a different publisher or other authors, then they will be attached to the existing book.
When editing a book, a new title is transferred (editing authors and publishers was not specifically done in this method, I think that this should be in a separate controller and endpoints).
when deleting a book, the links from the pivot tables are first removed, then the book itself is deleted. A check is made for authors and publishers without books, and if there are, they will also be deleted.

on the main page, the output of the table-library with pagination has been implemented. used to style bootstrap output, pagination requests are designed by ajax.

**Technologies used: Laravel 8 (Factory, Seeders, Resource, Models), Faker, Ajax, Jquery, Bootstrap, Swagger, Git, Composer, Blade**
