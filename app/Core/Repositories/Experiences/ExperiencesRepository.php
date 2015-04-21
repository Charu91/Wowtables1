<?php namespace WowTables\Core\Repositories\Experiences;


use WowTables\Http\Models\Eloquent\Products\Product;

class ExperiencesRepository {


    protected $attributes = [];


    public function getAll()
    {
        return Product::all();
    }

    public function add(Product $product)
    {
        $product->save();
    }
}
