<?php

namespace App\Controller;

class MovieController
{
    private array $movies = [
        1 => ['id' => 1, 'title' => 'Inception', 'year' => 2010, 'description' => 'Профессиональные воры внедряются в подсознание людей через сны.'],
        2 => ['id' => 2, 'title' => 'Interstellar', 'year' => 2014, 'description' => 'Группа исследователей отправляется в путешествие сквозь черную дыру.'],
        3 => ['id' => 3, 'title' => 'The Dark Knight', 'year' => 2008, 'description' => 'Бэтмен поднимает ставки в войне с криминалом в Готэме.'],
    ];

    private function render(string $template, array $data = []): string
    {
        extract($data);
        ob_start();
        include dirname(__DIR__, 2) . '/templates/' . $template;
        return ob_get_clean();
    }

    public function index(): string
    {
        return $this->render('movies/index.html.twig', [
            'movies' => $this->movies,
            'page' => 'index'
        ]);
    }

    public function random(): string
    {
        $randomId = array_rand($this->movies);
        return $this->render('movies/index.html.twig', [
            'movie' => $this->movies[$randomId],
            'page' => 'show'
        ]);
    }

    public function show(int $id): string
    {
        $movie = $this->movies[$id] ?? null;
        if (!$movie) {
            header("HTTP/1.0 404 Not Found");
            return "<h1>404 Фильм не найден</h1>";
        }
        return $this->render('movies/index.html.twig', [
            'movie' => $movie,
            'page' => 'show'
        ]);
    }
}
