<?php declare(strict_types=1);

namespace App\Service\Movie;

use Nette\Database\Context;

class OverallRatingCalculator
{

    private Context $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function updateOverallRating(): void
    {
        $this->database->query('
            UPDATE
                movie m
            SET overallRating = (
                SELECT AVG(rating) * 10 FROM rating WHERE movie_id = m.Id
            ) WHERE m.id > 0
        ');
    }

}