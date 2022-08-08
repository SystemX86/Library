<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookStoreRequest;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Book library OpenApi documentation",
 *      description="Swagger OpenApi"
 * )
 */
class BookController extends Controller
{
    /**
     * Get list of Books
     *
     * @OA\Get(
     *     path="/api/V1/book",
     *     tags={"Book"},
     *     summary="Get list of Books",
     *     description="Returns list of Books with Authors and Publishers",
     *     @OA\Response(response=200, description="Successful operation", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=403, description="Forbidden"),
     *  )
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return BookResource::collection(Book::all());
    }

    /**
     * Create new book
     *
     * @OA\Post(
     *     path="/api/V1/book/",
     *     summary="Store Book",
     *     tags={"Book"},
     *
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="White Fang"),
     *              @OA\Property(property="authors", type="array",
     *                  @OA\Items(@OA\Property(property="name", type="string", example="Macmillan"),),
     *              ),
     *              @OA\Property(property="publisher", type="string", example="Jack London"),
     *          ),
     *     ),
     *
     *     @OA\Response(response=200, description="Success", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=403, description="Forbidden"))
     * )
     *
     * @param BookStoreRequest $request
     * @return BookResource
     *
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
     * @OA\Put(
     *     path="/api/V1/book/{book_id}",
     *     summary="Update Book",
     *     tags={"Book"},
     *
     *     @OA\Parameter(name="book_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="White Fang"),
     *          ),
     *     ),
     *
     *     @OA\Response(response=200, description="Success", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=403, description="Forbidden"))
     * )
     *
     * @param BookStoreRequest $request
     * @param Book $book
     * @return BookResource
     **/
    public function update(BookStoreRequest $request, Book $book): BookResource
    {
        $book->update($request->validated());

        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/V1/book/{book_id}",
     *     tags={"Book"},
     *     summary="Delete Book",
     *
     *     @OA\Parameter(name="book_id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=204, description="Success", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=404, description="not found"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     *
     * @param Book $book
     * @return Response
     **/
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
