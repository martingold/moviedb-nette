<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model;
use App\Model\UserManager;
use Nette;
use Nette\Application\UI\Form;


final class SignUpFormFactory
{
    use Nette\SmartObject;

    private const PASSWORD_MIN_LENGTH = 7;

    /** @var FormFactory */
    private $factory;

    /** @var UserManager */
    private $userManager;

    public function __construct(FormFactory $factory, UserManager $userManager)
    {
        $this->factory = $factory;
        $this->userManager = $userManager;
    }

    public function create(callable $onSuccess): Form
    {
        $form = $this->factory->create();

        $form->addText('name', 'Jméno')
            ->setRequired('Zadejte jméno.');

        $form->addEmail('email', 'E-mail')
            ->setRequired('Zadejte e-mail.');

        $form->addPassword('password', 'Heslo')
            ->setRequired('Please create a password.')
            ->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

        $form->addSubmit('send', 'Registrovat se');

        $form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
            try {
                $this->userManager->add($values->name, $values->email, $values->password);
            } catch (Model\DuplicateNameException $e) {
                $form['username']->addError('Username is already taken.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
