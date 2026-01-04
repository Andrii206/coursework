<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;


class BooksFilter extends AbstractFilter
{

    const SEARCH = 'search';
    const CATEGORY = 'category_id';
    const AUTHORS = 'author_id';
    const TAGS = 'tags';

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],

            self::CATEGORY => [$this, 'categories'],

            self::AUTHORS => [$this, 'authors'],

            self::TAGS => [$this, 'tags'],
        ];
    }

    protected function categories(Builder $builder, $value)
    {
        $builder->whereIn('category_id', (array) $value);
    }
    protected function authors(Builder $builder, $value)
    {
        $builder->whereIn('author_id', (array) $value);
    }
    protected function tags(Builder $builder, $value)
    {
        $builder->whereHas('tags', function ($b) use ($value) {
            $b->whereIn('tag_id', $value);
        });
    }
    protected function search(Builder $builder, $value)
    {
        $builder->where(function ($query) use ($value) {
            $query->where('title', 'like', "%{$value}%")->orWhere('description', 'like', "%{$value}%");  
        });
    }
}
