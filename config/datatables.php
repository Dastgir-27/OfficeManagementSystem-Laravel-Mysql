<?php
// config/datatables.php (publish with: php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider")

return [
    'search' => [
        'smart' => true,
        'multi_term' => true,
        'case_insensitive' => true,
        'use_wildcards' => false,
        'starts_with' => false,
    ],
    'index_column' => 'DT_RowIndex',
    'engines' => [
        'eloquent' => Yajra\DataTables\EloquentDataTable::class,
        'query' => Yajra\DataTables\QueryDataTable::class,
        'collection' => Yajra\DataTables\CollectionDataTable::class,
        'resource' => Yajra\DataTables\ApiResourceDataTable::class,
    ],
    'builders' => [],
    'nulls_last' => null,
    'error' => null,
    'columns' => [
        'excess' => ['id', 'created_at', 'updated_at'],
        'escape' => '*',
        'raw' => ['action'],
        'blacklist' => ['password', 'remember_token'],
        'whitelist' => '*',
    ],
    'json' => [
        'header' => [],
        'options' => 0,
    ],
];