# Simple PHP MVC application

### To run application locally

Required components

```
XAMPP
Composer
```

`Enable rewrite module`

### Clone repo

Clone repository `git clone https://github.com/kaisekig/election-app.git` to the destination of your choice.\
Move all application content to the `htdocs` directory. It should be the root directory for app. 

Inside htdocs execute `composer install` to install all required dependencies.

Directory database constains a single sql file. Execute it in order to create schema and insert basic data.\
At the begining of the file, information about users credentials are included.

### Finally run application

Start Apache server and optionally MySQL server if already hasn't started.\
Browser `localhost`.

That's it! 🎉
