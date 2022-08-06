<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $books = Book::with(['authors', 'publishers'])->paginate(5);
        if ($request->ajax()) {
            return view('bookpagiresult',compact('books'));
        }
        return view('index',compact('books'));
    }
}
