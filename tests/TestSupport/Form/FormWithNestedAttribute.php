<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Form;

use Yiisoft\Form\FormModel;

final class FormWithNestedAttribute extends FormModel
{
    private ?int $id = null;
    private ?LoginForm $user = null;

    public function __construct()
    {
        $this->user = new LoginForm();
        parent::__construct();
    }

    public function getAttributeLabels(): array
    {
        return [
            'id' => 'ID',
        ];
    }

    public function getAttributeHints(): array
    {
        return [
            'id' => 'Readonly ID',
        ];
    }

    public function getAttributePlaceholders(): array
    {
        return [
            'id' => 'Type ID.',
        ];
    }

    public function getRules(): array
    {
        return [
            'id' => new Required(),
        ];
    }

    public function setUserLogin(string $login): void
    {
        $this->user->login('admin');
    }

    public function getUserLogin(): ?string
    {
        return $this->user->getLogin();
    }
}
