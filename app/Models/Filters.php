<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;

class Filters
{
    /**
     * @param Request
     */
    private $request;

    /**
     * @param array<string, string>
     */
    private $like;

    /**
     * @param Request $request Let the container inject current request
     */
    public function __construct(Request $request)
    {
        $this->like = [];
        $this->request = $request;
    }

    public function like(string $key, string $column): Filters
    {
        $value = $this->request->query($key);
        if (!empty($value)) {
            $this->like[$column] = $value;
        }
        return $this;
    }

    public function apply(Builder $query): Paginator
    {
        $this->applyLike($query);
        return $query->paginate()->appends($this->request->query());
    }

    private function applyLike(Builder $query)
    {
        foreach ($this->like as $column => $value) {
            $query->where($column, 'like', '%' . $value . '%');
        }
    }
}
