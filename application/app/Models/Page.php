<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $casts = [
        'hidden_sections' => 'array',
    ];

    public function sectionKeys(): array
    {
        return is_string($this->secs) ? json_decode($this->secs, true) ?? [] : ($this->secs ?? []);
    }

    public function hiddenSectionKeys(): array
    {
        return is_array($this->hidden_sections) ? $this->hidden_sections : [];
    }

    public function visibleSections(): array
    {
        return array_values(array_diff($this->sectionKeys(), $this->hiddenSectionKeys()));
    }
}
