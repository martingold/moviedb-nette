<?php declare(strict_types=1);

namespace App\Repository;

use Nette\Database\Context;
use Nette\Database\ResultSet;

class UserRepository
{

    /**
     * @var \Nette\Database\Context
     */
    private $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    public function getUserRatingCount(): ResultSet
    {
        return $this->database->query('
            SELECT
                u.id, u.name, COUNT(r.id) as count
            FROM user u
            LEFT JOIN rating r ON r.user_id = u.id
            GROUP BY r.user_id
            ORDER BY count DESC
        ');
    }

}