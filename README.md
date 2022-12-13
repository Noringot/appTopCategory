# Usage
- Get information about the position of the application
- https://tests-for-you.com/api/appTopCategory?date={insert_your_date}
- Get all saved positions
- https://tests-for-you.com/api/saved_positions

# For local develop

 ### Installation instructions
 - git clone git@github.com:Noringot/appTopCategory.git
 - composer install
 - npm i
 
 ### Insert information about database and ftp in .env file(example in .env.example)
<p>
  <div>DB_CONNECTION=mysql</div>
  <div>DB_HOST=</div>
  <div>DB_PORT=</div>
  <div>DB_DATABASE=</div>
  <div>DB_USERNAME=</div>
  <div>DB_PASSWORD=</div>
</p>

### Running migration and start project
- php artisan migrate
- php artisan serve
