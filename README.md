# Movie Search App

Movie Search App is a PHP/Laravel application designed to search for movies from specific APIs by their title.

All the API calls (Routes) except Login and Register.

## Features

- **Search for Movie**: Input field for entering movie title to search for.
- **Movie Details**: By clicking on the listed movie, all details for it can be seen.
- **User-Friendly Messages**: If there is an error while retrieving movies or movie details, user-friendly messages are displayed.
- **Sort Movies**: Movies can be sorted by Year or Title.
- **Favorite Movie**: Movies can be added to the Favorite list, or removed from it.
## Getting Started

### Prerequisites
- PHP and Laravel installed on your system
- A web server environment (such as XAMPP or Laravel Sail)

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/marjankolev94/movie-search-app.git

2. Navigate to the project directory:
   ```bash
   cd movie-search-app

3. Install dependencies:
   ```bash
   composer install

4. Set up environment variables:
- Copy .env.example to .env and configure your TMDB API and application settings as needed.

5. Serve the application
   ```bash
   php artisan serve

### Usage
- Postman can be used for testing the API calls
### Technologies Used
- PHP: Server-side scripting language.
- Laravel: PHP framework for building modern web applications.
- jQuery: Client-side language.
- Http: Used for the API call to the TMDB movie API.
### Contributing
Contributions are welcome! Please fork the repository and create a pull request with any improvements or bug fixes.

### License
This project is open-source and available under the MIT License.
