<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function getProductById($id);
    public function getTopDiscountProducts();
    public function getTopRecommendProducts();
    public function getTopPopularProducts();
    public function getAllCategories();
    public function getAllAuthors();
    public function createProductReview($fields);
    public function search($request);
}
