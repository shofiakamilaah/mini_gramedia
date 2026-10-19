<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookCategory;
use Yajra\DataTables\Facades\DataTables;

class BookCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$request->ajax jika ada permintaan dari ajax js, permintaan proses datatbels dipanggil maemlalu ajax js di bladenya
       if ($request->ajax()){
        $model = BookCategory::query();

        return DataTables::eloquent($model)
        //memberi nomor urut 123
        ->addIndexColumn()
        //menambah data selain yang ada di databese : mengubah data atau untuk btn aksi
        ->addColumn('action', function($data){
            $editUrl = route('admin.book-categories.edit', $data->id);
            $deleteUrl = route('admin.book-categories.destroy', $data->id);
            $csrf = csrf_field();
            $method = method_field('DELETE');
            $btnEdit =  '<a href="'. $editUrl . ' "class="btn btn-warning btn-sm me-2">Edit</a>';
            $btnDelete =  '<form action="'. $deleteUrl .'" method="POST"
                            class="d-inline">' . $csrf . $method . '<button type="submit"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm(\'Apakah anda yakin menghapus\')">Hapus</button>
                        </form>';

                        return $btnEdit . $btnDelete;
        })
        //menyimpan dari addcolumn yg ada di html didalamnya
        ->rawColumns(['action'])
        ->toJson();
       }
        return view('admin.book-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.book-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
        ]);

        BookCategory::create(['name' => $validated['nama']]);
        return redirect()->route('admin.book-categories.index')->with('success', 'Kategori buku berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //findOrFail : mencari data berdasarkan id, jika tidak ditemukan akan menampilkan error 404 atau bisa gunakan find() unutk mencari data berdasarkan id, jika tidak ditemukan akan mengembalikan null
        $bookCategory = BookCategory::findOrFail($id);
        return view('admin.book-categories.edit' , compact('bookCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $bookCategory = BookCategory::findOrFail($id);

        $validateData = $request->validate([
            'name' =>['required' , ' string' , 'max:225'],
        ]);
    //update digunakan untuk mengubah data, namun pengguananya harus setelah prosess pencarian data yang akan diubahnya
        $bookCategory->update($validateData);
        return redirect()->route('admin.book-categories.index')->with('sucsess' , 'Kategori buku telah berhasil diperbarui');
    }

    public function showCategory(Request $request, BookCategory $bookCategory)
    {
        $search = $request->input('q');
        $sort = $request->input('sort', 'terbaru');

        if (!in_array($sort, ['termurah', 'termahal', 'terbaru'])) {
            $sort = 'terbaru';
        }

        $books = $bookCategory->books()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('writer', 'like', '%' . $search . '%');
                });
            })
            ->when($sort == 'termurah', function ($query) {
                $query->orderBy('price', 'asc');
            })
            ->when($sort == 'termahal', function ($query) {
                $query->orderBy('price', 'desc');
            })
            ->when($sort == 'terbaru', function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->paginate(12)
            ->withQueryString();

        return view('book-category', compact(
            'bookCategory',
            'books',
            'search',
            'sort'
        ));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $bookCategory = BookCategory::findOrFail($id);
        $bookCategory->delete();
        //delete digunakan pada model untuk menghapus data namun penggunanya harus setelah proses pencarian data yang akan diubahnya
        return redirect()->route('admin.book-categories.index')->with('sucsess' , 'Kategori buku telah berhasil dihapus');
    }
}
