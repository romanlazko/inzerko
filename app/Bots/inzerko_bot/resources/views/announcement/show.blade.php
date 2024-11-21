<b>{{ 
    $announcement->getSectionByName('type')
        ?->pluck('value')
        ?->map(fn ($value) => str()
            ->of($value)
            ->lower()
            ->replace(' ', '_')
            ->prepend('#')
        )
        ?->implode(' ')
}}</b>

<b>{{ 
    $announcement->title
}}</b>

Стоимость: {{ 
    $announcement->price
}}

<b>{{
    $announcement->categories
        ?->pluck('name')
        ?->map(fn ($value) => str()
            ->of($value)
            ->lower()
            ->replace(' ', '_')
            ->prepend('#')
        )
        ?->implode(' ')
}}</b>