<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int             $id
 * @property int             $book_id
 * @property CarbonImmutable $created_at
 * @property BookStock       $book
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookStock query()
 *
 * @mixin \Eloquent
 */
class BookStock extends Model
{
    public const null UPDATED_AT = null;


    protected $fillable = [
        'book_id',
    ];

    protected $casts = [
        'id' => 'int',
        'book_id' => 'int',
        'created_at' => 'immutable_datetime',
    ];

    /**
     * @return BelongsTo<Book, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
