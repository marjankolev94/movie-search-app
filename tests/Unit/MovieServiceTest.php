<?php

namespace Tests\Unit;

use Tests\TestCase; 
use App\Services\MovieApiService;
class MovieServiceTest extends TestCase
{
    protected $movieService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->movieService = new MovieApiService();
    }

    public function test_user_can_search_real_movie_successfully()
    {
        $searchQuery = 'Inception';
        $result = $this->movieService->searchMovies($searchQuery);

        $this->assertIsArray($result);
        $this->assertGreaterThan(0, count($result));
        $this->assertArrayHasKey('title', $result['results'][0]);
        $this->assertEquals('Inception', $result['results'][0]['title']);
    }

    public function test_user_cannot_get_results_for_not_real_movie()
    {
        $searchQuery = 'NonExistingMovie';
        $result = $this->movieService->searchMovies($searchQuery);

        $this->assertIsArray($result);
        $this->assertCount(0, $result['results']);
    }

    public function test_user_can_get_existing_movie_details()
    {
        $movieId = 27205; 
        $movieDetails = $this->movieService->getMovieDetails($movieId);

        $this->assertIsArray($movieDetails);
        $this->assertArrayHasKey('original_title', $movieDetails);
        $this->assertArrayHasKey('overview', $movieDetails);
        $this->assertArrayHasKey('release_date', $movieDetails);
    }

    public function test_user_not_get_movie_details_for_invalid_id()
    {
        $invalidMovieId = 999991285858;
        $movieDetails = $this->movieService->getMovieDetails($invalidMovieId);

        $this->assertNull($movieDetails);
    }
}
