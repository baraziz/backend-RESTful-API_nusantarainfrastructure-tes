<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new BookCollection(Book::where('user_id', $request->user()->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|numeric',
            'title' => 'required',
            'subtitle' => 'required',
            'author' => 'required',
            'published' => 'required|date_format:Y-m-d H:i:s',
            'publisher' => 'required',
            'pages' => 'required|numeric',
            'description' => 'required',
            'website' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request body error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;

        $book = Book::create(
            $data
        );

        return response()->json([
            'message' => "Add Book description message",
            'book' => new BookResource($book)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $book = Book::find($id);
        if ($book === null) {
            return response()->json([
                'message' => 'Book ID not found'
            ], 404);
        }
        if ($request->user()->id !== $book->user_id) {
            return response()->json([
                'message' => "User doesn't have right to do the request"
            ], 403);
        }

        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|numeric',
            'title' => 'required',
            'subtitle' => 'required',
            'author' => 'required',
            'published' => 'required|date_format:Y-m-d H:i:s',
            'publisher' => 'required',
            'pages' => 'required|numeric',
            'description' => 'required',
            'website' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request body error',
                'errors' => $validator->errors()
            ], 422);
        }
        if ($book === null) {
            return response()->json([
                'message' => "User doesn't have right to do the request"
            ], 403);
        }
        if ($request->user()->id !== $book->user_id) {
            return response()->json([
                'message' => "User doesn't have right to do the request"
            ], 403);
        }

        $book->update(
            $validator->validated()
        );

        if ($book) {
            return response()->json([
                'message' => "Update Book success response",
                'book' => new BookResource($book)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $book = Book::find($id);
        if ($book === null) {
            return response()->json([
                'message' => "User doesn't have right to do the request"
            ], 403);
        }
        if ($request->user()->id !== $book->user_id) {
            return response()->json([
                'message' => "User doesn't have right to do the request"
            ], 403);
        }

        $temp = $book;
        $book->delete();

        return response()->json([
            "message" => "Delete Book success response",
            "book" => new BookResource($temp)
        ]);
    }
}
