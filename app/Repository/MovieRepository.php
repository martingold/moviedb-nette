<?php declare(strict_types=1);

namespace App\Repository;

use DateTime;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Strings;

class MovieRepository
{

    /**
     * @var \Nette\Database\Context
     */
    private $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function getMovieBySlug(string $slug): ActiveRow
    {
        return $this->database->table('movie')
            ->where('slug = ?', $slug)
            ->fetch();
    }

    /**
     * @return array<mixed>
     */
    public function getMovies(): array
    {
        return $this->database->table('movie')->fetchAll();
    }

    /**
     * @return array<mixed>
     */
    public function getLatest(): array
    {
        return $this->database->table('movie')
            ->order('created DESC')
            ->limit(3)
            ->fetchAll();
    }

    /**
     * @return array<mixed>
     */
    public function bestMovies(): array
    {
        return $this->database->table('movie')
            ->order('overallRating DESC')
            ->limit(100)
            ->fetchAll();
    }

    public function addMovie(string $title, string $description, string $imageUrl)
    {
        $this->database->table('movie')
            ->insert([
                'title' => $title,
                'description' => $description,
                'imageUrl' => $imageUrl,
                'slug' => Strings::webalize($title),
                'created' => new DateTime(),
            ]);

        return Strings::webalize($title);
    }

}