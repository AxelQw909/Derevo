<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_tree_id', 'first_name', 'last_name', 'patronymic',
        'birth_date', 'death_date', 'gender', 'role_in_family',
        'photo_path', 'bio', 'x_position', 'y_position'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    public function familyTree(): BelongsTo
    {
        return $this->belongsTo(FamilyTree::class);
    }

    // Связи: родители этого человека
    public function parents()
    {
        return $this->belongsToMany(Person::class, 'person_relations', 'person_id', 'related_person_id')
                    ->wherePivot('relation_type', 'parent');
    }

    // Связи: дети этого человека
    public function children()
    {
        return $this->belongsToMany(Person::class, 'person_relations', 'person_id', 'related_person_id')
                    ->wherePivot('relation_type', 'child');
    }

    // Связи: супруг(а) этого человека
    public function spouses()
    {
        return $this->belongsToMany(Person::class, 'person_relations', 'person_id', 'related_person_id')
                    ->wherePivot('relation_type', 'spouse');
    }
}