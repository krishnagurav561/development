<?php

return [
    'name' => [
        'label' => __('Name', 'site-reviews'),
        'placeholder' => esc_attr__('Your name', 'site-reviews'),
        'type' => 'text',
    ],
    'email' => [
        'label' => __('Email', 'site-reviews'),
        'placeholder' => esc_attr__('your@email.com', 'site-reviews'),
        'type' => 'email',
    ],
    'rating' => [
        'label' => __('Rating', 'site-reviews'),
        'type' => 'rating',
    ],
    'content' => [
        'label' => __('Review', 'site-reviews'),
        'rows' => 5,
        'type' => 'textarea',
    ],
    'title' => [
        'label' => __('Title of your review', 'site-reviews'),
        'placeholder' => esc_attr__('Summarize your review or highlight an interesting detail', 'site-reviews'),
        'type' => 'text',
    ],
    'terms' => [
        'label' => __('This review is based on my own experience and is my genuine opinion.', 'site-reviews'),
        'type' => 'toggle',
    ],
];
