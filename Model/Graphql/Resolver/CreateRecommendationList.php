<?php

declare(strict_types=1);

namespace SwiftOtter\FriendRecommendations\Model\Graphql\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SwiftOtter\FriendRecommendations\Model\Graphql\Resolver\CreateRecommendationList\Sanitizer;
use SwiftOtter\FriendRecommendations\Model\Graphql\Resolver\CreateRecommendationList\Validator;
use SwiftOtter\FriendRecommendations\Model\RecommendationList;
use SwiftOtter\FriendRecommendations\Model\RecommendationListFactory as ListFactory;
use SwiftOtter\FriendRecommendations\Model\RecommendationListRepository as ListRepository;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProduct;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProductFactory as ListProductFactory;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProductRepository as ListProductRepository;

class CreateRecommendationList implements ResolverInterface
{

    private RecommendationList $list;
    private ListFactory $listFactory;
    private ListRepository $listRepository;
    private RecommendationListProduct $listProduct;
    private ListProductFactory $listProductFactory;
    private ListProductRepository $listProductRepository;
    private Sanitizer $sanitizer;
    private Validator $validator;

    public function __construct(
        RecommendationList $list,
        ListFactory $listFactory,
        ListRepository $listRepository,
        RecommendationListProduct $listProduct,
        ListProductFactory $listProductFactory,
        ListProductRepository $listProductRepository,
        Sanitizer $sanitizer,
        Validator $validator
    ) {
        $this->list                  = $list;
        $this->listFactory           = $listFactory;
        $this->listRepository        = $listRepository;
        $this->listProduct           = $listProduct;
        $this->listProductFactory    = $listProductFactory;
        $this->listProductRepository = $listProductRepository;
        $this->sanitizer             = $sanitizer;
        $this->validator             = $validator;
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
            $list = $this->listFactory->create();

            $list->setEmail($data['email']);
            $list->setFriendName($data['friendName']);
            $list->setTitle($data['title']);
            $list->setNote($data['note']);
            $savedList = $this->listRepository->save($list);

            $listProductsToSave = $data['productSkus'];
            foreach ($listProductsToSave as $sku) {
                $listProduct = $this->listProductFactory->create();
                $listProduct->setListId((int)$savedList->getId());
                $listProduct->setSku($sku);
                $this->listProductRepository->save($listProduct);
            }

            return [
                'email' => $data['email'],
                'friendName' => $data['friendName'],
                'title' => $data['title'],
                'note' => $data['note']
            ];
        } catch (\Exception $e) {
            return [
                'email' => '',
                'friendName' => '',
                'title' => 'ERROR',
                'note' => $e->getMessage()
            ];
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
