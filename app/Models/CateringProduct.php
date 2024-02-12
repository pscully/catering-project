<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'description',
        'sku',
        'image',
        'price'
    ];

    /**
     * Get product name by SKU.
     *
     * @param string $sku
     * @return string|null
     */
//    public static function getProductNameBySku(string $sku): ?string
//    {
//        $product = self::where('sku', $sku)->first();
//        return $product ? $product->name : null;
//    }
//
//    /**
//     * Get product price by SKU.
//     *
//     * @param string $sku
//     * @return float
//     */
//    public static function getProductPriceBySku(string $sku): float
//    {
//        $product = self::where('sku', $sku)->first();
//        return $product ? $product->price : 0;
//    }

}
