<?php
namespace App\Services;

use App\Interfaces\ProductInterface;
use App\Models\Book;


class ProductService
{
    protected $product;
    
    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    public function getProductById($id)
    {
        $product = $this->product->getProductById($id);
        if ($product) {
            return $product;
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function getTopDiscountProducts()
    {
        $products = $this->product->getTopDiscountProducts();
        if ($products) {
            return $products;
        } else {
            return response()->json(['message' => 'Products not found'], 404);
        }
    }

    public function getTopRecommendProducts()
    {
        $products = $this->product->getTopRecommendProducts();
        if ($products) {
            return $products;
        } else {
            return response()->json(['message' => 'Products not found'], 404);
        }
    }

    public function getTopPopularProducts()
    {
        $products = $this->product->getTopPopularProducts();
        if ($products) {
            return $products;
        } else {
            return response()->json(['message' => 'Products not found'], 404);
        }
    }

    public function getAllCategories(){
        $categories = $this->product->getAllCategories();
        if($categories) {
            return $categories;
        } else {
            return response()->json(['message' => 'Categories not found'], 404);
        }
    }

    public function getAllAuthors(){
        $authors = $this->product->getAllAuthors();
        if($authors) {
            return $authors;
        } else {
            return response()->json(['message' => 'Authors not found'],404);
        }
    }

    public function createProductReview($id, $inputs) {
        $product = Book::findOrFail($id);
        if($product) {
           $this->product->createProductReview([
            'review_title' => $inputs['review_title'],
            'review_details'=> $inputs['review_details'],
            'rating_start'=> $inputs['rating_start'],
            'book_id' => $product->id
           ]);
           return response()->json(['message' => 'Review added'], 201);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function search($request){
        $products = $this->product->search($request);
        if ($products) {
            return $products;
        } else {
            return response()->json(['message' => 'Products not found'], 404);
        }
    }
}
