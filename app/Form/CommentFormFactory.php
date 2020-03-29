<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model\UserManager;
use App\Repository\CommentRepository;
use App\Repository\MovieRepository;
use App\Repository\RatingRepository;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class CommentFormFactory
{
    use Nette\SmartObject;

    private FormFactory $factory;

    private RatingRepository $ratingRepository;

    private User $user;

    public function __construct(FormFactory $factory, RatingRepository $ratingRepository, User $user)
    {
        $this->factory = $factory;
        $this->ratingRepository = $ratingRepository;
        $this->user = $user;
    }

    public function create(string $slug, callable $onSuccess): Form
    {
        $form = $this->factory->create();

        $rating = $this->ratingRepository->getRatingBySlug($slug, $this->user->getId());
        $defaultValues = $rating === null ? [] : $rating;

        $form->addTextArea('comment', 'Komentář')
            ->setRequired('Zadejte komentář.')
            ->setDefaultValue($defaultValues['comment'] ?? '');

        $form->addText('rating', 'Hodnocení (1-10)')
            ->setHtmlType('number')
            ->setRequired('Hodnocení.')
            ->setDefaultValue($defaultValues['rating'] ?? '');

        $form->addSubmit('send', 'Ohodnotit');

        $form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess, $slug): void {
            $this->ratingRepository->rateMovie($slug, $this->user->getId(), (int) $values->rating, $values->comment);
            $onSuccess();
        };

        return $form;
    }
}
