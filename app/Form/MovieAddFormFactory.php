<?php

declare(strict_types=1);

namespace App\Forms;

use App\Repository\MovieRepository;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;

final class MovieAddFormFactory
{
    use Nette\SmartObject;

    private FormFactory $factory;

    private User $user;

    private MovieRepository $movieRepository;

    public function __construct(FormFactory $factory, MovieRepository $movieRepository, User $user)
    {
        $this->factory = $factory;
        $this->user = $user;
        $this->movieRepository = $movieRepository;
    }

    public function create(callable $onSuccess): Form
    {
        $form = $this->factory->create();

        $form->addText('name', 'Název')
            ->setRequired('Vyplňte název.');

        $form->addTextArea('description', 'Popis')
            ->setRequired('Vyplňte popis.');

        $form->addText('imageUrl', 'URL obrázku')
            ->setRequired('Vyplňte URL obrázku.');

        $form->addSubmit('send', 'Přidat');

        $form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
            $slug = $this->movieRepository->addMovie($values->name, $values->description, $values->imageUrl);
            $onSuccess($slug);
        };

        return $form;
    }
}
