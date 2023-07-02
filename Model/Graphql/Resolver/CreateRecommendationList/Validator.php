<?php

declare(strict_types=1);

namespace SwiftOtter\FriendRecommendations\Model\Graphql\Resolver\CreateRecommendationList;

use Magento\Customer\Model\Validator\Name;
use Magento\Framework\Validator\EmailAddress;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class Validator
{
    const EMAIL_KEY = 'email';
    const FRIEND_NAME_KEY = 'friendName';
    const TITLE_KEY = 'title';
    const NOTE_KEY = 'note';
    const PRODUCTS_SKUS_KEY = 'productSkus';

    protected Name $nameValdiator;
    private EmailAddress $emailValidator;

    public function __construct(Name $nameValidator, EmailAddress $emailValidator)
    {
        $this->nameValdiator = $nameValidator;
        $this->emailValidator = $emailValidator;
    }

    public function validate(array $data)
    {
        try {
            $this->validateName($data[self::FRIEND_NAME_KEY] ?? '');
            $this->validateEmail($data(self::EMAIL_KEY) ?? '');
            $this->validateNote($data[self::NOTE_KEY] ?? '');
            $this->validateTitle($data[self::TITLE_KEY] ?? '');
            $this->validateSkus($data[self::PRODUCTS_SKUS_KEY] ?? []);
        } catch (ValidationFailedException $e) {

        }
    }

    private function validateName(string $name)
    {
    }

    private function validateEmail(string $email)
    {

    }

    private function validateNote(string $note)
    {

    }

    private function validateTitle(string $title)
    {

    }

    private function validateSkus(array $skus)
    {
    }
}
