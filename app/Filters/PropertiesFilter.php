<?php

namespace App\Filters;
use App\Filters\ApiFilter;

class PropertiesFilter  extends ApiFilter
{
    protected $safeParams = [
        'transactionType' => ['eq'],
        'propertyTypeId'  => ['eq'],
        'city'            => ['eq'],
        'status'          => ['eq'],
        'price'           => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'surface'         => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'bedrooms'        => ['eq', 'gte', 'lte'],
        'bathrooms'       => ['eq', 'gte', 'lte'],
        'floors'          => ['eq','gte','lte']
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'gt' => '>',
        'lte' => '<=',
        'gte' => '>='
    ];

    protected $columnMap = [
        'transactionType' => 'transaction_type',
        'propertyTypeId' => 'property_type_id',
    ];
}
?>
