<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieApiService;

class MovieController extends Controller
{
    protected $movieApiService;

    public function __construct(MovieApiService $movieApiService)
    {
        $this->movieApiService = $movieApiService;
    }

    public function index()
    {
        return view('movies.index');
    }

    public function searchMovies(Request $request)
    {
        $query = $request->input('query'); 
        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1); 
        $movies = $this->movieApiService->searchMovies($query, $perPage, $page);

        foreach($movies['results'] as &$movie) {
            if (strlen($movie['overview']) > 100) {
                $movie['overview'] = mb_substr($movie['overview'], 0, 100) . '...';
            }
        }
        unset($movie);

        if ($movies) {
            return response()->json([
                'movies' => $movies['results'],
                'total_pages' => $movies['total_pages'],
                'current_page' => $page
            ]);
        }

        return response()->json(['error' => 'Oops! We couldn’t find that movie. Please try again later.'], 500);
    }

    public function show($id)
    {
        $movie = $this->movieApiService->getMovieDetails($id);

        if($movie) {
            return view('movies.show', compact('movie'));
        }

        return redirect()->route('movies.index')->with('error', "Sorry, we couldn't fetch the movie details you requested. Please check your input or try again soon.");
    }
}
