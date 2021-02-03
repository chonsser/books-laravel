<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return BookResource::collection(Book::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return BookResource
     */
    public function store(Request $request)
    {
        return $this->persistBook($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return BookResource
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return BookResource
     */
    public function update(Request $request, Book $book)
    {
        return $this->persistBook($request, $book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();
    }

    private function persistBook(Request $request, ?Book $book = null) {
        $request->validate([
            'title' => 'required|between:1,100',
            'author' => 'required|between:1,70',
            'year' => 'required|between:1,4',
        ]);

        if(!$book) {
            $book = new Book();
            $book->uuid = Str::uuid();
        }

        $book->title = $request->request->get('title');
        $book->author = $request->request->get('author');
        $book->release_year = $request->request->getDigits('year');

        $book->save();

        return new BookResource($book);
    }
}
