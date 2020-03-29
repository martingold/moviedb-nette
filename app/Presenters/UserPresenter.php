<?php declare(strict_types=1);

namespace App\Presenters;

use App\Repository\UserRepository;

class UserPresenter extends BasePresenter
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function renderList(): void
    {
        $this->template->users = $this->userRepository->getUserRatingCount();
    }

}