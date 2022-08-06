<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookStoreRequest;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return BookResource::collection(Book::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookStoreRequest $request
     * @return BookResource
     */
    public function store(BookStoreRequest $request): BookResource
    {
        $validatedRequest = $request->validated();

        $bookModel = Book::firstOrCreate([
            'name' => $validatedRequest['name']
        ]);

        $publisherModel = Publisher::firstOrCreate([
            'name' => $validatedRequest['publisher']
        ]);

        $bookModel->publishers()->syncWithoutDetaching($publisherModel);

        foreach ($validatedRequest['authors'] as $author) {
            $itemAuthorModel = Author::firstOrCreate([
                'name' => $author['name'],
            ]);
            $bookModel->authors()->syncWithoutDetaching($itemAuthorModel);
        }

        return new BookResource($bookModel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookStoreRequest $request
     * @param Book $book
     * @return BookResource
     */
    public function update(BookStoreRequest $request, Book $book): BookResource
    {
        $book->update($request->validated());

        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Book $book
     * @return Response
     */
    public function destroy(Book $book): Response
    {
        $book->authors()->detach();
        Author::doesntHave('books')->delete();

        $book->publishers()->detach();
        Publisher::doesntHave('books')->delete();

        $book->delete();


        return response(null, Response::HTTP_NO_CONTENT);
    }
}
