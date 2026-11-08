<?php

namespace App\Controller;

class BookController
{
    private array $users = [
        1 => ['id' => 1, 'name' => 'Коля', 'email' => 'kolya@gang.ru'],
        2 => ['id' => 2, 'name' => 'Иван', 'email' => 'ivan@mail.ru'],
        3 => ['id' => 3, 'name' => 'Анна', 'email' => 'anna@yandex.ru'],
    ];

    private array $books = [
        101 => ['id' => 101, 'title' => 'Золотой том (Супер раритет)', 'price' => 7500],
        102 => ['id' => 102, 'title' => 'Основы PHP и Symfony', 'price' => 1200],
        103 => ['id' => 103, 'title' => 'Чистый код', 'price' => 2500],
        104 => ['id' => 104, 'title' => 'Изучаем базы данных', 'price' => 800],
    ];

    private array $orders = [
        ['id' => 1, 'user_id' => 1, 'book_ids' => [101, 103]],
        ['id' => 2, 'user_id' => 2, 'book_ids' => [102]],
        ['id' => 3, 'user_id' => 1, 'book_ids' => [104]],
        ['id' => 4, 'user_id' => 3, 'book_ids' => [101, 102, 103]],
        ['id' => 5, 'user_id' => 2, 'book_ids' => [103]],
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
        $userStats = [];
        foreach ($this->users as $u) {
            $userStats[$u['id']] = ['name' => $u['name'], 'email' => $u['email'], 'order_count' => 0, 'total_spent' => 0];
        }
        foreach ($this->orders as $o) {
            if (isset($userStats[$o['user_id']])) {
                $userStats[$o['user_id']]['order_count']++;
            }
        }

        $ordersDetails = [];
        $allOrdersSums = [];
        foreach ($this->orders as $o) {
            $orderSum = 0;
            $titles = [];
            foreach ($o['book_ids'] as $bId) {
                if (isset($this->books[$bId])) {
                    $titles[] = $this->books[$bId]['title'];
                    $orderSum += $this->books[$bId]['price'];
                }
            }
            $userStats[$o['user_id']]['total_spent'] += $orderSum;
            $allOrdersSums[] = $orderSum;

            $ordersDetails[] = [
                'id' => $o['id'],
                'buyer' => $this->users[$o['user_id']]['name'],
                'books' => implode(', ', $titles),
                'sum' => $orderSum
            ];
        }

        $tempStats = $userStats;
        uasort($tempStats, function($a, $b) { return $b['total_spent'] <=> $a['total_spent']; });
        $top3 = array_slice($tempStats, 0, 3);

        $averageCheck = count($allOrdersSums) > 0 ? round(array_sum($allOrdersSums) / count($allOrdersSums), 2) : 0;

        $expensiveBook = null;
        foreach ($this->books as $b) {
            if ($expensiveBook === null || $b['price'] > $expensiveBook['price']) { $expensiveBook = $b; }
        }

        return $this->render('books/index.html.twig', [
            'userStats' => $userStats,
            'ordersDetails' => $ordersDetails,
            'top3' => $top3,
            'averageCheck' => $averageCheck,
            'expensiveBook' => $expensiveBook
        ]);
    }
}
