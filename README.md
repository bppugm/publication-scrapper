# Publication Scrapper
Publication scrapper for Badan Penerbit dan Publikasi UGM

## System Requirement
1. PHP > 7.1
2. MySQL Database
3. MongoDB Database
4. Composer
5. NodeJS >= 8
5. MongoDB php extension
6. [Elsevier API Key](https://dev.elsevier.com/)
7. [Microsoft Academic API Key](https://labs.cognitive.microsoft.com/en-us/project-academic-knowledge)

## How to Install
1. Clone this repository to your local.
2. run `composer install`.
3. run `npm install`.
4. Make new `.env` based on `.env.example`.
5. Create a new MySQL database on your machine and modify the database connection in `.env`.
6. Fill `ELSEVIER_API_KEY` in `.env` using your Elsevier API Key
7. Fill `MA_API_KEY` in `.env` with your Microsoft Academic API Key
8. run `php artisan key:generate`.
9. run `php artisan migrate --seed`.
10. run `php artisan storage:link`.
11. run `php artisan passport:install`.

## Development
- Built with [Laravel 5.7](https://laravel.com) PHP Framework
- [Passport](https://laravel.com/docs/5.5/passport) for API Security
- [VueJs](https://vuejs.org/) Javascript Framework
- [Bootstrap](https://getbootstrap.com/) HTML and CSS Framework
