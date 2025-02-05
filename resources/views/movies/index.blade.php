<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('partials._navbar')
    <div class="container my-5">
        <div class="d-flex justify-content-end mb-3">
            <div class="input-group" style="width: 300px;">
                <input type="text" id="search-query" class="form-control" placeholder="Search...">
                <button id="search-button" class="btn btn-secondary">Search</button>
            </div>
        </div>

        <div class="sort-container col-md-3 mb-4" style="display: none;">
            <label for="sort-by">Sort Movies By:</label>
            <select id="sort-by" class="form-select">
                <option value=""></option>
                <option value="title">by Title</option>
                <option value="release_year">by Release Year</option>
            </select>
        </div>

        <div id="error-message-container" style="display: none;" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong><br/> 
            <p id="error-message"></p>
        </div>
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div id="loading" style="display: none;">
            <div class="spinner"></div>
        </div>

        <div id="movie-results" class="d-flex flex-wrap gap-3 justify-content-center"></div>

        <div id="pagination-controls" class="d-flex justify-content-center my-4" style="display: none;">
            <button id="prev-page" class="btn btn-primary">Previous</button>&nbsp;
            <button id="next-page" class="btn btn-primary">Next</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let currentPage = 1;
            let perPage = 5;

            loadFavorites(); 

            $('#search-button').click(function() {
                var query = $('#search-query').val();
                if (query) {
                    $('#loading').show();
                    $.ajax({
                        url: '{{ route('search.movies') }}',
                        method: 'GET',
                        data: { query: query },
                        success: function(response) {
                            $('#loading').hide();
                            $('.sort-container').show();
                            renderMovies(response.movies);
                        },
                        error: function(response) {
                            $('#loading').hide();
                            $('.sort-container').hide();
                            $('#movie-results').html('');
                            $("#error-message").text(response.responseJSON.error);
                            $('#error-message-container').show();
                            
                        }
                    });
                }
            });

            $('#prev-page').click(function() {
                if (currentPage > 1) {
                    currentPage--;
                    loadMovies();
                }
            });

            $('#next-page').click(function() {
                currentPage++;
                loadMovies();
            });

            function loadMovies() {
                $('#loading').show();
                var query = $('#search-query').val();
                if (query) {
                    $.ajax({
                        url: '{{ route('search.movies') }}',
                        method: 'GET',
                        data: { query: query, per_page: perPage, page: currentPage },
                        success: function(response) {
                            $('#loading').hide();
                            renderMovies(response.movies);
                            updatePagination(response.total_pages, response.current_page);
                        },
                        error: function() {
                            $('#loading').hide();
                            $('#error-message-container').show();
                        }
                    });
                }
            }

            $('#sort-by').change(function() {
                let selectedOption = $(this).val();
                let movies = $('#movie-results').data('movies');
                if (movies) {
                    movies = sortMovies(movies, selectedOption);
                    renderMovies(movies);
                }
            });

            // Handle Add/Remove from Favorites
            $(document).on("click", ".favorite-btn", function (event) {
                event.preventDefault();
                event.stopPropagation(); 

                let movie = $(this).data("movie");
                let favorites = getFavorites();

                let index = favorites.findIndex(fav => fav.id === movie.id);
                if (index === -1) {
                    favorites.push(movie);
                } else {
                    favorites.splice(index, 1);
                }

                localStorage.setItem("favorites", JSON.stringify(favorites));
                loadFavorites();
            });

            $("#favorites-link").click(function (event) {
                let favorites = getFavorites();
                renderMovies(favorites);
            });
        });

        function renderMovies(movies) {
            const fallbackImage = "{{ asset('images/movie_not_found_image.png') }}";
            let movieHtml = '<div class="row">';
            if (movies.length > 0) {
                movies.forEach(movie => {
                    movieHtml += `
                        <div class="col-md-3 mb-4 movie-box">
                        <a href="{{ url('/movie') }}/${movie.id}" class="text-decoration-none text-dark">
                            <div class="card h-100">
                                <img src="${movie.poster_path ? '{{ env('TMDB_IMG_URL') }}' + movie.poster_path : fallbackImage}" class="card-img-top" alt="${movie.title}" style="height: 350px;">
                                <div class="card-body">
                                    <h5 class="card-title">${movie.title}</h5>
                                    <p class="card-text release-year">${movie.release_date ? movie.release_date.substring(0, 4) : "N/A"}</p>
                                    <p class="card-text">${movie.overview}</p>
                                    <button class="btn btn-outline-danger favorite-btn" data-movie='${JSON.stringify(movie)}'>❤️ Add to Favorites</button>
                                </div>
                            </div>
                        </a>
                        </div>
                    `;
                });
                movieHtml += '</div>';
            } else {
                movieHtml = '<p>No movies found.</p>';
            }
            $('#movie-results').html(movieHtml);
            $('#movie-results').data('movies', movies); // Save movie data for sorting
            loadFavorites();
        }

        function getFavorites() {
            return JSON.parse(localStorage.getItem("favorites")) || [];
        }

        function loadFavorites() {
            let favorites = getFavorites();
            $(".favorite-btn").each(function () {
                let movie = $(this).data("movie");
                let isFavorite = favorites.some(fav => fav.id === movie.id);
                $(this).toggleClass("btn-danger", isFavorite).toggleClass("btn-outline-danger", !isFavorite);
                $(this).text(isFavorite ? "❤️ Remove from Favorites" : "❤️ Add to Favorites");
            });
        }

        function sortMovies(movies, criteria) {
            return movies.sort((a, b) => {
                if (criteria === "title") {
                    return a.title.localeCompare(b.title);
                } else if (criteria === "release_year") {
                    return a.release_date.substring(0, 4) - b.release_date.substring(0, 4);
                }
                return 0;
            });
        }

        function updatePagination(totalPages, currentPage) {
            $('#pagination-controls').show();
            $('#prev-page').prop('disabled', currentPage === 1);
            $('#next-page').prop('disabled', currentPage === totalPages);
        }
    </script>

</body>
</html>
