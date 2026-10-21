<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPackageBook;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageBookController extends Controller
{
    public function index($subscriptionPackageId, Request $request)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($subscriptionPackageId);

        if ($request->ajax()) {
            $model = Book::with('bookCategory');

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('coverImg', function ($row) {
                    return '<img src="' . asset($row->cover) . '" alt="cover" style="width: 100px;" class="d-block mx-auto" />';
                })
                ->addColumn('book_category_id', function ($row) {
                    return $row->bookCategory?->name ?? '-';
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->rawColumns(['coverImg'])
                ->toJson();
        }

        return view('admin.SubscriptionPackage.books', compact('subscriptionPackage'));
    }

    public function create(Request $request, $id)
    {
        $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id',
        ]);

        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        foreach ($request->book_ids as $bookId) {
            SubscriptionPackageBook::firstOrCreate([
                'subscription_package_id' => $subscriptionPackage->id,
                'book_id' => $bookId,
            ], [
                'expired_date' => now()->addMonth(),
            ]);
        }

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke paket langganan');
    }
}
