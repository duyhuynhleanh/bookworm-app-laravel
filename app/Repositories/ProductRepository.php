<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Review;
use App\Interfaces\ProductInterface;
use Illuminate\Support\Facades\DB;


class ProductRepository implements ProductInterface
{
    protected $product;
    
    public function __construct(Book $product)
    {
        $this->product = $product;
    }


    public function getProductById($id)
    {
        $product = $this->product::where('books.id', $id)
        ->select('books.*')
        ->with('author')
        ->with('category')
        // ->with('reviews')
        ->selectFinalPrice()
        ->selectAverageStar()
        ->selectCountComment()
        ->first();

        return [
            "id"=> $product->id,
            "category"=> $product->category,
            "author"=> $product->author,
            "book_title"=> $product->book_title,
            "book_summary"=> $product->book_summary,
            "book_price"=> $product->book_price,
            "book_cover_photo"=> $product->book_cover_photo,
            "final_price"=> $product->final_price,
            "rating"=> $product->rating,
            "numReviews"=> $product->numReviews,
        ];
    }

    public function getTopDiscountProducts()
    {
        $products = DB::table('books')->join('discounts', 'books.id', '=', 'discounts.book_id')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->select(
                'books.id',
                'books.book_cover_photo',
                'books.book_title',
                'books.book_price',
                'authors.author_name',
                'discounts.discount_price',
                DB::raw('books.book_price - discounts.discount_price as sub_price'),
            DB::raw('CASE WHEN (discounts.discount_price isnull) THEN books.book_price ELSE discounts.discount_price end as final_price')
            )
            ->where(function ($query) {
                $query->whereDate('discount_start_date', '<=', now()->toDateString())
                      ->whereDate('discount_end_date', '>=', now()->toDateString());
            })
            ->orWhere(function ($query) {
                $query->whereDate('discount_start_date', '<=', now()->toDateString())
                      ->whereNull('discounts.discount_end_date');
            })
            ->orderBy('sub_price', 'desc')
            ->take(10)
            ->get();

        return $products;
    }

    public function getTopRecommendProducts()
    {

        $products = Book::leftJoin('discounts', 'books.id', '=', 'discounts.book_id')
        ->select('books.*')
        ->finalPrice()
        ->selectAverageStar()
        ->orderByRaw('rating DESC NULLS LAST')
        ->orderBy('final_price', 'asc')
        ->take(8)
        ->get()
        ;

        return $products;
    }

    public function getTopPopularProducts(){
        $products =  DB::table('books')
        ->join('reviews', 'books.id','=','reviews.book_id')
        ->join('authors', 'books.author_id','=','authors.id')
        ->leftJoin('discounts','books.id','=','discounts.book_id')
        ->select('books.id','books.book_cover_photo','books.book_title','authors.author_name','books.book_price',
        DB::raw('CASE WHEN (discounts.discount_price isnull) THEN books.book_price ELSE discounts.discount_price end  as final_price'),
        DB::raw('count(books.id) as num_reviews'))
        ->where(function($query) {
            $query->whereDate('discount_start_date','<=', now()->toDateString())
                  ->whereDate('discount_end_date','>=', now()->toDateString());
        })
        ->orWhere(function($query){
            $query->whereDate('discount_start_date','<=', now()->toDateString())
                  ->whereNull('discounts.discount_end_date');
        })
        ->groupBy('final_price')
        ->groupBy('books.id')
        ->groupBy('authors.author_name')
        ->orderBy('num_reviews', 'desc')
        ->orderBy('final_price', 'asc')
        ->take(8)
        ->get();
        return $products;
    }


    public function getAllCategories()
    {
        return DB::table('categories')
        ->select('categories.id', 'categories.category_name')
        ->get();
    }

    public function getAllAuthors(){
        return DB::table('authors')
        ->select('authors.id', 'authors.author_name')
        ->get();
    }

    public function createProductReview($fields) {
        return Review::create($fields);
    }

    public function search($request)
    {
        $product_query = Book::with(['category', 'author']);
        
        if($request->keyword){
            $product_query->where('book_title', 'LIKE', '%'.$request->keyword. '%');
        }

        if($request->category){
            $product_query
            ->whereHas('category', function($query) use($request){
                $query->where('category_id', '=', $request->category);
            });
            
        }

        if($request->author){
            $product_query
            ->whereHas('author', function($query) use($request){
                $query->where('author_id', '=', $request->author);
            });
        }
        if($request->rating){
            $product_query
            ->join('reviews', 'books.id', '=', 'reviews.book_id')
            ->select('books.*')
            ->groupBy(['books.id', 'discounts.discount_price'])
            ->havingRaw("avg(CAST(reviews.rating_start AS FLOAT)) >= ?", [$request->rating]);
        }

        if($request->sortOrder && in_array($request->sortOrder,['sale', 'popular', 'priceASC', 'priceDESC'])){
            $sortOrder = $request->sortOrder;
            $product_query->leftJoin("discounts", function ($join) {
                $join
                    ->on("books.id", "=", "discounts.book_id")
                    ->whereDate("discount_start_date", "<=", now())
                    ->where(function ($query) {
                        $query
                            ->whereDate("discount_end_date", ">", now())
                            ->orWhereNull("discount_end_date");
                    });
            })->select(
                "books.*",
                "discounts.discount_price",
                DB::raw(
                    "(CASE WHEN discount_price IS NULL THEN 0 ELSE book_price - discount_price END) as sub_price"
                ),
                DB::raw(
                    "CASE WHEN (discounts.discount_price IS NULL) THEN books.book_price ELSE discounts.discount_price end  as final_price"
                )
            );
            switch($sortOrder){
                case 'sale':
                default:
                    $product_query
                    ->orderBy("sub_price", 'desc')
                    ->orderBy("final_price")
                    ->orderBy("book_title");
                    break;
                case 'popular':
                    $product_query->withCount('reviews')
                    ->orderBy("reviews_count", 'desc')
                    ->orderBy("final_price")
                    ->orderBy("book_title");
                    break;
                case 'priceASC':
                    $product_query
                    ->orderBy("final_price", 'asc')
                    ->orderBy("book_title");
                    break;
                case 'priceDESC':
                    $product_query
                    ->orderBy('final_price', 'desc')
                    ->orderBy("book_title");
            };
        }

        if($request->perPage){
            $perPage = $request->perPage;
        } else {
            $perPage = 5;
        }
        
        $products = $product_query->paginate($perPage);

        return $products;
    }

    public function getRatingDetails($id){
        $ratingDetails =DB::table("books")
        ->join("reviews", "books.id", "=", "reviews.book_id")
        ->select(
            "reviews.rating_start",
            DB::raw("count(cast(reviews.rating_start as int)) as quantity")
        )
        ->where("books.id", $id)
        ->groupBy(["reviews.rating_start"])
        ->get();
        return $ratingDetails;
    }

    public function searchReviews($id, $request) {
        $review_query=Review::query()->where('book_id', $id);
        if($request->rating){
            $review_query->where('rating_start', '=', $request->rating);
        }
        if($request->sortOrder && in_array($request->sortOrder,['asc', 'desc'])){
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'desc';
        }

        if($request->perPage){
            $perPage = $request->perPage;
        } else {
            $perPage = 5;
        }

        if($request->paginate){
            $reviews = $review_query->OrderBy('review_date', $sortOrder)->paginate($perPage);
        } else {
            $reviews = $review_query->OrderBy('review_date', $sortOrder)->get();
        }

        return $reviews;
    }
}
