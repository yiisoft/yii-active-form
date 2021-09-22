<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\MatchRegularExpression;
use Yiisoft\Validator\Rule\Number;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Url;

final class AttributesValidatorForm extends FormModel
{
    private string $email = '';
    private string $number = '';
    private string $password = '';
    private string $pattern = '';
    private string $required = '';
    private string $telephone = '';
    private string $text = '';
    private string $url = '';

    public function getRules(): array
    {
        return [
            'email' => [
                Required::rule(),
                HasLength::rule()->min(8)->tooShortMessage('Is too short.')->max(20)->tooLongMessage('Is too long.'),
                MatchRegularExpression::rule('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/')
                    ->message('Is not a valid email address.'),
                Email::rule(),
            ],
            'number' => [
                Required::rule(),
                Number::rule()->min(3)->tooSmallMessage('Is too small.')->max(5)->tooBigMessage('Is too big.'),
            ],
            'pattern' => [
                MatchRegularExpression::rule('/\w+/'),
            ],
            'password' => [
                Required::rule(),
                HasLength::rule()->min(4)->tooShortMessage('Is too short.')->max(8)->tooLongMessage('Is too long.'),
                MatchRegularExpression::rule('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{4,8}$/')
                    ->message('Is not a valid password.'),
            ],
            'telephone' => [
                Required::rule(),
                HasLength::rule()->min(8)->tooShortMessage('Is too short.')->max(16)->tooLongMessage('Is too long.'),
                MatchRegularExpression::rule('/[^0-9+\(\)-]/')->message('Is not a valid telephone number.'),
            ],
            'text' => [
                Required::rule(),
                HasLength::rule()->min(3)->tooShortMessage('Is too short.')->max(6)->tooLongMessage('Is too long.'),
                MatchRegularExpression::rule('/^[a-zA-Z0-9_.-]+$/')->message('Is not a valid text.'),
            ],
            'url' => [
                Required::rule(),
                HasLength::rule()->min(15)->tooShortMessage('Is too short.')->max(20)->tooLongMessage('Is too long.'),
                MatchRegularExpression::rule(
                    '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'
                )->message('Is not a valid URL.'),
                Url::rule(),
            ],
        ];
    }

    public function getAttributeHints(): array
    {
        return ['email' => 'Write your email.'];
    }
}
