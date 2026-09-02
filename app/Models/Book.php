<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['cover', 'title', 'price', 'descripsion', 'language', 'publisher', 'writer', 'relase_date', 'page_of_book', 'book_category_id'])]
class Book extends Model
{
    //nama tunggal karena books_categories berperan sebagai one
    //pada relasi one to many milik kategori buku
    public function bookCategory() {
        return $this->belongsTo(BookCategory::class);
    }

    public function checkoutBooks() {
        return $this->hasMany(CheckoutBook::class);
    }

    public function subscriptionPackageBooks(): HasMany{
        return $this->hasMany(SubscriptionPackageBook::class);
    }
}
