<?php

namespace GeminiLabs\SiteReviews\Defaults;

use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract as Defaults;

class ColumnOrderbyDefaults extends Defaults
{
    /**
     * @return array
     */
    protected function defaults()
    {
        return [
            'author_name' => 'name',
            'author_email' => 'email',
            'ip_address' => 'ip_address',
            'is_pinned' => 'is_pinned',
            'rating' => 'rating',
            'type' => 'type',
        ];
    }
}
