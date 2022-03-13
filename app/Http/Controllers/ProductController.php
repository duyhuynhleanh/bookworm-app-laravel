<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Carbon\Carbon;

class ProductController extends Controller
{
    protected $product;
    public function __construct(ProductService $product)
    {
        $this->product = $product;
    }

    // @desc    Fetch single product
    // @route   GET /api/products/:id
    // @access  Public
    public function getProductById($id)
    {
        return $this->product->getProductById($id);
    }

    // @desc    Fetch 10 books with the most discount
    // @route   GET /api/products/topdiscount
    // @access  Public
    public function getTopDiscountProducts()
    {
        return $this->product->getTopDiscountProducts();
    }

    // @desc    Fetch top 8 books with most rating stars
    // @route   GET /api/products/toprecommend
    // @access  Public
    public function getTopRecommendProducts()
    {
        return $this->product->getTopRecommendProducts();
    }

    // @desc    Fetch top 8 books with most reviews - total number review of a book and lowest final price
    // @route   GET /api/products/toppopular
    // @access  Public
    public function getTopPopularProducts()
    {
        return $this->product->getTopPopularProducts();
    }

    // @desc    Fetch all categories with category's name
    // @route   GET /api/categories
    // @access  Public
    public function getAllCategories()
    {
        return $this->product->getAllCategories();
    }

    // @desc    Fetch all authors with author's name
    // @route   GET /api/authors
    // @access  Public
    public function getAllAuthors()
    {
        return $this->product->getAllAuthors();
    }

    // @desc    Create new review
    // @route   POST /api/products/:id/reviews
    // @access  Public
    public function createProductReview(ReviewRequest $request){
        $id = $request->id;
        $inputs = $request->validated();
        return $this->product->createProductReview($id, $inputs);
    }

    // @desc    Fetch all filtered products with sort and paginate and search
    // @route   GET /api/search
    // @access  Public
    public function search(Request $request){
        return $this->product->search($request);
    }
}
