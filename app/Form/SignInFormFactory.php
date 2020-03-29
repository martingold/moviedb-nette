<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;

final class SignInFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
    private $factory;

    /** @var User */
    private $user;

    public function __construct(FormFactory $factory, User $user)
    {
        $this->factory = $factory;
        $this->user = $user;
    }

    public function create(callable $onSuccess): Form
    {
        $form = $this->factory->create();
        $form->addText('username', 'E-mail')
            ->setRequired('Zadejte e-mail.');

        $form->addPassword('password', 'Heslo')
            ->setRequired('Please enter your password.');

        $form->addSubmit('send', 'Přihlásit se');

        $form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
            try {
                $this->user->setExpiration('14 days');
                $this->user->login($values->username, $values->password);
            } catch (Nette\Security\AuthenticationException $e) {
                $form->addError('Uživatel se zadným e-mailem neexistuje nebo bylo zadáno nesprávné heslo.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
