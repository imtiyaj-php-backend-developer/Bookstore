<?php

namespace App\Http\Controllers;

use App\Models\Bookstore;
use Illuminate\Http\Request;
use Pest\Contracts\Plugins\Bootable;

class BookStoreController extends Controller
{
    protected $statusCodeSuccess = 200;
    protected $statusCodeError = 400;
    protected $statusCodeValidation = 401;

    public function bookAdd(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'author' => 'required|string',
                'ISBN' => 'required|numeric',
                'Pages' => 'required|string',
                'Language' => 'required|string',
                'publisher' => 'required|string',
                'published_date' => 'nullable|date',
            ]);

            $product = Bookstore::create([
                'title' => $request->title,
                'author' => $request->author,
                'ISBN' => $request->ISBN,
                'Pages' => $request->Pages,
                'Language' => $request->Language,
                'publisher' => $request->publisher,
                'published_date' => $request->published_date,
            ]);

            if($product != null){
                return response([
                    'statusCode' => $this->statusCodeSuccess,
                    'message' => 'Book Added Successfully',
                    'status' => 'success',
                    'data' => $product
                ], $this->statusCodeSuccess);
            }else{
                return response([
                    'statusCode' => $this->statusCodeValidation,
                    'message' => 'Product not created!',
                    'status' => 'failed',
                    'data' => []
                ], $this->statusCodeValidation);
            }

        } catch (\Exception $exception) {
            return response([
                'statusCode' => $this->statusCodeError,
                'message' => 'Something went wrong',
                'status' => 'error',
                'data' => []
            ], $this->statusCodeError);
        }
    }
    public function fetchBook(){
        $bookget = Bookstore::get();
        if(!$bookget->isEmpty()){
            return response([
                'statusCode' => $this->statusCodeSuccess,
                'message' => 'All Book Fetch Successfully',
                'status' => 'success',
                'data' => $bookget
            ], $this->statusCodeSuccess);
        }else{
            return response([
                'statusCode' => $this->statusCodeValidation,
                'message' => 'Currently, there are no book available!',
                'status' => 'failed',
                'data' => []
            ], $this->statusCodeValidation);
        }
    }
    public function bookUpdate(Request $request)
    {
        
        $request->validate([
                'book_id' => 'required',
                'title' => 'required|string',
                'author' => 'required|string',
                'ISBN' => 'required|numeric',
                'Pages' => 'required|string',
                'Language' => 'required|string',
                'publisher' => 'required|string',
                'published_date' => 'nullable|date',
        ]);
        try {
            $book = Bookstore::find($request->book_id);

            if (!$book) {
                return response([
                    'statusCode' => $this->statusCodeValidation,
                    'message' => 'Book not found!',
                    'status' => 'failed',
                    'data' => []
                ], $this->statusCodeValidation);
            }

            $book->title = $request->title;
            $book->author = $request->author;
            $book->ISBN = $request->ISBN;
            $book->Pages = $request->Pages;
            $book->Language = $request->Language;
            $book->publisher = $request->publisher;
            $book->published_date = $request->published_date;
            $book->save();

            return response([
                'statusCode' => $this->statusCodeSuccess,
                'message' => 'Book updated successfully',
                'status' => 'success',
                'data' => $book
            ], $this->statusCodeSuccess);

        } catch (\Exception $exception) {
            dd($exception);
            return response([
                'statusCode' => $this->statusCodeError,
                'message' => 'Something went wrong',
                'status' => 'error',
                'data' => []
            ], $this->statusCodeError);
        }
    }

    public function deleteBook(Request $request)
    {
        
        $request->validate([
            'book_id' => 'required|exists:bookstores,id', 
        ]);
        try {
            $bookfind = Bookstore::find($request->book_id);
            if (!$bookfind) {
                return response([
                    'statusCode' => $this->statusCodeValidation,
                    'message' => 'Book not found!',
                    'status' => 'failed',
                    'data' => []
                ], $this->statusCodeValidation);
            }
            $bookfind->delete();
            return response()->json([
                'statusCode' => $this->statusCodeSuccess,
                'message' => 'Book deleted successfully',
                'status' => 'success',
                'data' => []
            ], $this->statusCodeSuccess);

        } catch (\Exception $exception) {
            return response()->json([
                'statusCode' => $this->statusCodeError,
                'message' => 'Something went wrong',
                'status' => 'error',
                'error' => $exception->getMessage(),
                'data' => []
            ], $this->statusCodeError);
        }
    }

    public function fetchBookId(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:bookstores,id', 
        ]);
        $book = Bookstore::find($request->book_id);
        try {
            if (!$book) {
                return response([
                    'statusCode' => $this->statusCodeValidation,
                    'message' => 'Book not found!',
                    'status' => 'failed',
                    'data' => []
                ], $this->statusCodeValidation);
            }

            return response([
                'statusCode' => $this->statusCodeSuccess,
                'message' => 'Book details fetched successfully',
                'status' => 'success',
                'data' => $book
            ], $this->statusCodeSuccess);

        } catch (\Exception $exception) {
            return response([
                'statusCode' => $this->statusCodeError,
                'message' => 'Something went wrong',
                'status' => 'error',
                'data' => []
            ], $this->statusCodeError);
        }
    }


}
