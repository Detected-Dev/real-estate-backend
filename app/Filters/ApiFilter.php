<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    protected $safeParams = [];

    protected $operatorMap = [];

    protected $columnMap = [];

    public function transform(Request $request)
    {
        $eloQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param); // [eq => 2000] // name = $param = price

            if (!isset($query)) continue;

            $column = $this->columnMap[$param] ?? $param;

            $eloQuery[] = [

                $column,
                $this->operatorMap[key($query)],
                $query[key($query)]

            ];
        }

        return $eloQuery;
    }
}
