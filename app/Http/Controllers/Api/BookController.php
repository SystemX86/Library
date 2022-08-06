<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return BookResource::collection(Book::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return BookResource
     */
    public function store(Request $request): BookResource
    {
        $bookInfoFromRequest = $request->all();

        $bookModel = Book::firstOrCreate([
            'name' => $bookInfoFromRequest['name']
        ]);

        $publisherModel = Publisher::firstOrCreate([
            'name' => $request->all()['publisher']
        ]);

        $bookModel->publishers()->syncWithoutDetaching($publisherModel);

        foreach ($request->all()['authors'] as $author) {
            $authorModel = Author::firstOrCreate([
                'name' => $author['name'],
            ]);
            $bookModel->authors()->syncWithoutDetaching($authorModel);
        }

        return new BookResource($bookModel);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
