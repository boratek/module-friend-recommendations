<?php

declare(strict_types=1);

namespace SwiftOtter\FriendRecommendations\Model\Graphql\Resolver\CreateRecommendationList;

use Magento\Framework\Escaper;

class Sanitizer
{
    private Escaper $escaper;

    public function __construct(Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    public function sanitize(array $args): array
    {
        $result = [];

        foreach ($args as $key => $arg) {
            if (is_array($arg)) {
                $arg = $this->sanitize($arg);
            } else {
                $matches = [];
                preg_match('/\$[:]*{(.)*}/', $arg, $matches);
                if (count($matches) > 0) {
                    $arg = $this->escaper->escapeHtml($this->escaper->escapeJs($arg));
                } else {
                    $arg = $this->escaper->escapeHtml($arg);
                }
            }


            $result[$key] = $arg;
        }

        return $result;
    }
}
