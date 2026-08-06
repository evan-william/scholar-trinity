<?php

return [
    'number_prefix' => env('REGISTRATION_NUMBER_PREFIX', 'APR'),
    'grade_levels' => ['8', '9', '10', '11', '12', 'Gap Year', 'Other'],
    'practice_exam_fee' => (int) env('PRACTICE_EXAM_FEE', 2800),
    'subject_categories' => [
        'Arts',
        'Computer Science',
        'English',
        'General',
        'History',
        'Mathematics',
        'Sciences',
        'Social Sciences',
        'World Languages',
    ],
];
