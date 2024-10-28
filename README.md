
## About Auto Blog

Auto Blog is a Laravel platform that allows you to create a blog that automatically generates articles based on a given topic. The blog uses AI to generate articles and can be used to create a blog that generates articles on a daily basis. 

It also uses Flux to generate images for the articles and can be used to create a blog that generates articles and images on a daily basis.

## License

Auto Blog is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

#
### Auto Blog SETUP

run `composer install`

run `php artisan key:generate`

edit `the .env file to match your database credentials`

run `php artisan migrate`

run `php artisan storage:link`

run `npm install`

run `npm run build`

run `php artisan serve`

edit `.env file to include the various AI api keys`

go to `http://localhost/register and add yourself as a user`

mysql `open users table and change the role of the user to admin ( member_type = 1)`
