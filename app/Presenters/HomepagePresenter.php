<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Repository\MovieRepository;
use App\Repository\RatingRepository;

final class HomepagePresenter extends BasePresenter
{

    /**
     * @var \App\Repository\MovieRepository
     */
    private $movieRepository;
    /**
     * @var \App\Repository\RatingRepository
     */
    private $ratingRepository;

    public function __construct(
        RatingRepository $ratingRepository,
        MovieRepository $movieRepository
    ) {
        parent::__construct();
        $this->ratingRepository = $ratingRepository;
        $this->movieRepository = $movieRepository;
    }

    public function renderDefault()
    {
        $this->template->setParameters([
            'latestRatings' => $this->ratingRepository->getLatest(),
            'latestMovies' => $this->movieRepository->getLatest(),
        ]);
    }

}
