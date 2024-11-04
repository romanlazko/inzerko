<x-telegram::layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{-- {{ __('Categories') }} --}}
            </h2>
            <div class="flex-col grid grid-cols-1 sm:grid-cols-2 space-y-1 sm:space-x-2 sm:space-y-0">
                <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.subcategory.create') }}" class="float-right">
                    {{ __("✚Add subcategory") }}
                </x-telegram::a-buttons.secondary>
                <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.category.create') }}" class="float-right">
                    {{ __("✚Add category") }}
                </x-telegram::a-buttons.secondary>
            </div>
        </div>
    </x-slot>
    <x-slot name="main">
        <x-telegram::white-block class="p-0">
            <x-telegram::search :action="route('pozor_baraholka_bot.category.index')"/>
        </x-telegram::white-block>

        <x-telegram::white-block class="p-0">
            <x-telegram::table.table class="whitespace-nowrap">
                <x-telegram::table.thead>
                    <tr>
                        <x-telegram::table.th>id</x-telegram::table.th>
                        <x-telegram::table.th>Name</x-telegram::table.th>
                        <x-telegram::table.th></x-telegram::table.th>
                    </tr>
                </x-telegram::table.thead>
                <x-telegram::table.tbody>
                    @forelse ($categories as $index => $category)
                        <tr class="@if($index % 2 === 0) bg-gray-100 @endif">
                            <x-telegram::table.td>{{ $category->id }}</x-telegram::table.td>
                            <x-telegram::table.td><i class="fa-solid {{ $category->icon_name }}"></i> {{ $category->trans_name() }}</x-telegram::table.td>
                            <x-telegram::table.td>
                                <div class="p-1">
                                    @forelse ($category->subcategories as $subcategory_index => $subcategory)
                                        <x-telegram::badge :active="$subcategory->is_active">
                                            <i class="fa-solid {{ $subcategory->icon_name }}"></i>
                                            <a href="{{ route('pozor_baraholka_bot.subcategory.edit', $subcategory) }}">{{ $subcategory->trans_name($category->name) }}</a>
                                        </x-telegram::badge>
                                        @if ($subcategory_index % 5 === 4) 
                                            <br>
                                        @endif
                                    @empty
                                        
                                    @endforelse
                                </div>
                               
                            </x-telegram::table.td>
                            <x-telegram::table.buttons>
                                <x-telegram::a-buttons.primary href="{{ route('pozor_baraholka_bot.category.edit', $category) }}">Edit</x-telegram::a-buttons.primary>
                                <form action="{{ route('pozor_baraholka_bot.category.destroy', $category) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-telegram::buttons.danger onclick="return confirm('Sure?')">Delete</x-telegram::buttons.dangertton>
                                </form>
                            </x-telegram::table.buttons>
                        </tr>
                    @empty
                    @endforelse
                </x-telegram::table.tbody>
            </x-telegram::table.table>
        </x-telegram::white-block>
    </x-slot>
</x-telegram::layout>