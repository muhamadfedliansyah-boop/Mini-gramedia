<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPackageBook;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $subscriptionPackages = SubscriptionPackage::all();
        $latestBooks = Book::orderBy('created_at', 'desc')->limit(10)->get();
        $freeBooks = Book::where('price', 0)->orderBy('created_at', 'desc')->limit(10)->get();
        return view('home', compact('subscriptionPackages', 'latestBooks', 'freeBooks'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $books = Book::where('title', 'like', '%' . $search . '%')
            ->orWhere('writer', 'like', '%' . $search . '%')
            ->get();

        return view('search_result', compact('search', 'books'));
    }

    public function category(Request $request, $id)
    {
        $category = BookCategory::findOrFail($id);

        $query = Book::where('book_category_id', $category->id);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('writer', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'termurah') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'termahal') {
                $query->orderBy('price', 'desc');
            } elseif ($sort === 'terbaru') {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $books = $query->get();

        return view('search_result', compact('category', 'books'));
    }

    public function subscriptionPackageDetail(Request $request, $id){
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $query = SubscriptionPackageBook::with(['book.bookCategory'])
            ->where('subscription_package_id', $subscriptionPackage->id);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('writer', 'like', '%' . $search . '%');
            });
        }

        $packageBooks = $query->get();
        $books = $packageBooks->pluck('book')->filter();
        $subscriptionPackage->setRelation('books', $books);

        if (view()->exists('User.SubscriptionPackage.show')) {
            return view('User.SubscriptionPackage.show', compact('subscriptionPackage', 'packageBooks', 'books'));
        }

        return view('subscription_package_detail', compact('subscriptionPackage', 'packageBooks', 'books'));
    }
}

