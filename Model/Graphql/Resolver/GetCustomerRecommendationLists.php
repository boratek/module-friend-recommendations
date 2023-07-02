<?php

declare(strict_types=1);

namespace SwiftOtter\FriendRecommendations\Model\Graphql\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProductRepository;
class GetCustomerRecommendationLists implements ResolverInterface
{
    private RecommendationListProductRepository $repository;

    public function __construct(
        RecommendationListProductRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        //TODO implement
    }
}
