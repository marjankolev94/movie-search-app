<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('partials._navbar')
    <div class="container mt-4">
        <h1>{{ $movie['title'] }}</h1>
        <div class="row">
            <div class="col-md-4">
                <img src="{{ env('TMDB_IMG_URL') }}{{ $movie['poster_path'] }}" class="img-fluid" alt="{{ $movie['title'] }}">
            </div>
            <div class="col-md-8">
                <h3>Overview</h3>
                <p>{{ $movie['overview'] }}</p>
                <hr>
                <p><strong>Release Date:</strong> {{ $movie['release_date'] }}</p>
                <p><strong>Genres:</strong>
                    @foreach($movie['genres'] as $genre)
                        {{ $genre['name'] }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
                <p><strong>Runtime:</strong> {{ $movie['runtime'] }} minutes</p>
                <p><strong>Rating:</strong> {{ $movie['vote_average'] }}/10</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>