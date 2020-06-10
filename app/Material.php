<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    /**
     * Define the mass assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'amount',
        'returner_registration_mark',
        'tooker_registration_mark'
    ];

    /**
     * Hidde fields on the serialization.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * Define if the model's table will have
     * created_at and updated_at columns.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get the user's loans.
     */
    public function loans()
    {
        return $this->hasMany('App\Loan');
    }

    public function isLost()
    {
        return !is_null($this->returner_registration_mark);
    }

    /**
     * Scope a query to only return borrowable materials
     *
     * @param Builder $query
     * @return Builder $query
     */
    public function scopeBorrowable($query)
    {
        return $query->where([
            ['amount', '>=', 1],
            ['returner_registration_mark', '=', null],
            ['tooker_registration_mark', '=', null]
        ]);
    }

    public function hasBorrowableWithName(string $name)
    {
        $material = $this->borrowable()->where('name', $name)->first();
        return isset($material);
    }

    public function isAnBorrowableAmount(int $materialAmount)
    {
        return (! $this->amount) || ($this->amount < $materialAmount);
    }

    public function decrementAmount(int $amount)
    {
        $this->amount -= $amount;
        $this->save();
    }

    public function incrementAmount(int $amount)
    {
        $this->amount += $amount;
        $this->save();
    }

    public function filter($returned)
    {
        return Material::when($returned, function ($query, $returned) {
            return ($returned === "true")
                ? $query->whereNotNull('tooker_registration_mark')
                : $query->whereNotNull('returner_registration_mark')
                        ->whereNull('tooker_registration_mark');
        })
        ->get();
    }
}
