<?php

declare(strict_types=1);

namespace SwiftOtter\FriendRecommendations\Model\Graphql\Resolver\CreateRecommendationList;

use Magento\Customer\Model\Validator\Name;
use Magento\Framework\Validator\EmailAddress;
use SwiftOtter\FriendRecommendations\RecommendationListProductCreateDataNotValid;

class Validator
{
    const EMAIL_KEY = 'email';
    const FRIEND_NAME_KEY = 'friendName';
    const TITLE_KEY = 'title';
    const NOTE_KEY = 'note';
    const PRODUCTS_SKUS_KEY = 'productSkus';

    protected Name $nameValdiator;
    private EmailAddress $emailValidator;

    public function __construct(
        Name $nameValidator,
        EmailAddress $emailValidator
    ) {
        $this->nameValdiator  = $nameValidator;
        $this->emailValidator = $emailValidator;
    }

    public function validate(array $data)
    {
        try {
            $this->validateName($data[self::FRIEND_NAME_KEY] ?? '');
            $this->validateEmail($data[self::EMAIL_KEY] ?? '');
            $this->validateNote($data[self::NOTE_KEY] ?? '');
            $this->validateTitle($data[self::TITLE_KEY] ?? '');
            $this->validateSkus($data[self::PRODUCTS_SKUS_KEY] ?? []);
        } catch (RecommendationListProductCreateDataNotValid $e) {
            echo $e->getMessage();
        }
    }

    private function validateName(string $name)
    {
        if (empty($name)) {
            throw new RecommendationListProductCreateDataNotValid(__('Friend name does not exist! Please fill it.'));
        }
    }

    private function validateEmail(string $email)
    {
        if (empty($email)) {
            throw new RecommendationListProductCreateDataNotValid(__('Email does not exist! Please fill it.'));
        }

        if (!$this->emailValidator->isValid($email)) {
            throw new RecommendationListProductCreateDataNotValid(__('Email is invalid. Please use valid email.'));
        }
    }

    private function validateNote(string $note)
    {
        if (empty($note)) {
            throw new RecommendationListProductCreateDataNotValid(__('Note does not exist! Please fill it.'));
        }
    }

    private function validateTitle(string $title)
    {
        if (empty($title)) {
            throw new RecommendationListProductCreateDataNotValid(__('Title does not exist! Please fill it.'));
        }
    }

    private function validateSkus(array $skus)
    {
        if (empty($skus)) {
            throw new RecommendationListProductCreateDataNotValid(__('Products list is empty! Please fill it.'));
        }
    }
}
