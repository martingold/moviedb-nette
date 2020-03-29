<?php declare(strict_types=1);

namespace App\Repository;

use DateTime;
use Nette\Database\Context;

class RatingRepository
{

    /**
     * @var \Nette\Database\Context
     */
    private $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    /**
     * @return array<mixed>
     */
    public function getLatest()
    {
        return $this->database->table('rating')
            ->order('created DESC')
            ->limit(3)
            ->fetchAll();
    }

    public function getRatingBySlug(string $slug, int $userId): ?array
    {
        $rating = $this->database->table('rating')
            ->where('movie.slug = ?', $slug)
            ->where('user.id = ?', $userId)
            ->fetch();

        return $rating === null ? null : $rating->toArray();
    }

    public function rateMovie(string $slug, int $userId, int $rating, string $comment): void
    {
        $existingRating = $this->getRatingBySlug($slug, $userId);
        if ($existingRating === null) {

            $movieId = $this->database->table('movie')
                ->where('slug = ?', $slug)
                ->fetch()
                ->id;

            $this->database->table('rating')
                ->insert([
                    'user_id' => $userId,
                    'movie_id' => $movieId,
                    'rating' => $rating,
                    'comment' => $comment,
                    'created' => new DateTime(),
                ]);

            return;
        }

        $this->database->table('rating')
            ->where('id = ?', $existingRating['id'])
            ->update([
                'rating' => $rating,
                'comment' => $comment,
            ]);
    }

}
