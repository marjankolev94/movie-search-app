<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MovieApiService
{
    public function searchMovies($query, $perPage = 5, $page = 1)
    {
        $endpoint = '/search/movie';
        $params = [
            'query' => $query,
            'page' => $page,
            'per_page' => $perPage
        ];

        return $this->getTmdbData($endpoint, $params);
    }

    public function getMovieDetails($movieId)
    {
        $endpoint = '/movie/' . $movieId;

        return $this->getTmdbData($endpoint);
    }

    private function getTmdbData($endpoint, $params = []) 
    {
        $response = Http::get(env('TMDB_API_URL') . $endpoint, array_merge($params, [
            'api_key' => env('TMDB_API_KEY'),
        ]));

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
