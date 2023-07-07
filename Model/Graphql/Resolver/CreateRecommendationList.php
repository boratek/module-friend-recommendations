<?php

declare(strict_types=1);

namespace SwiftOtter\FriendRecommendations\Model\Graphql\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SwiftOtter\FriendRecommendations\Model\Graphql\Resolver\CreateRecommendationList\Sanitizer;
use SwiftOtter\FriendRecommendations\Model\Graphql\Resolver\CreateRecommendationList\Validator;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProduct;

//use SwiftOtter\FriendRecommendations\Model\RecommendationListFactory as Factory;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProductFactory as Factory;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProductRepository as Repository;

class CreateRecommendationList implements ResolverInterface
{
    private Repository $repository;
    private Factory $factory;

    private Sanitizer $sanitizer;

    private Validator $validator;

    public function __construct(
        Repository $repository,
        Factory $factory,
        Sanitizer $sanitizer,
        Validator $validator
    ) {
        $this->repository = $repository;
        $this->factory    = $factory;
        $this->sanitizer  = $sanitizer;
        $this->validator  = $validator;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        try {
            $data = $this->sanitize($args);
            $this->validate($data);
            $recommendation = $this->factory->create();
            $recommendation->setData($data);
            $this->repository->save($recommendation);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Inspired by
     * \Magento\LoginAsCustomerAdminUi\Ui\Customer\Component\ConfirmationPopup\Options::sanitizeName
     */
    private function sanitize(array $args)
    {
        return $this->sanitizer->sanitize($args);
    }

    private function validate(array $args)
    {
        $this->validator->validate($args);
    }
}
