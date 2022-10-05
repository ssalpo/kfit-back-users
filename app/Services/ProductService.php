<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function add(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create($data);

            $product->goods()->sync(Arr::get($data, 'goods', []));

            return $product->refresh();
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $product = Product::findOrFail($id);

            $product->update($data);

            $product->goods()->sync(Arr::get($data, 'goods', []));

            return $product->refresh();
        });
    }
}
