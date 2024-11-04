
<x-telegram::layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Update subcategory') }}
            </h2>
            <div class="flex-col">
                <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.category.index') }}" class="float-right">
                    {{ __("←Back") }}
                </x-telegram::a-buttons.secondary>
            </div>
        </div>
    </x-slot>
    <x-slot name="main">
        <div class="sm:flex grid sm:grid-cols-2 grid-cols-1 sm:space-x-2 justify-between sm:space-y-0 space-y-6">
            <div class="flex-col max-w-xl w-full sm:w-1/2 ">
                
                <x-telegram::white-block>
                    <div class="flex justify-between">
                        <h2 class="text-lg font-medium text-gray-900 ">
                            {{ __('Update subcategory') }}
                        </h2>
                        <form action="{{ route('pozor_baraholka_bot.subcategory.destroy', $subcategory) }}" method="post" style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <x-telegram::buttons.danger>X</x-telegram::buttons.danger>
                        </form>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Use this form to update subcategory.') }}
                    </p>
                    <form method="post" action="{{ route('pozor_baraholka_bot.subcategory.update', $subcategory) }}" class="space-y-6 ">
                        @csrf
                        @method('patch')

                        <div>
                            <x-telegram::form.label for="is_active" >
                                <div class="w-full flex items-stretch space-x-2">
                                    <div class="flex-col">
                                        <input type="hidden" name="is_active" value="0">
                                        <x-telegram::form.input id="is_active" name="is_active" type="checkbox" class="mt-1 block" value="1" :checked="$subcategory->is_active === 1"/>
                                    </div>
                                    <div class="flex-col">
                                        {{ __('Is active:') }}
                                    </div>
                                </div>
                            </x-telegram::form.label>
                            
                            <x-telegram::form.error class="mt-2" :messages="$errors->get('is_active')" />
                        </div>
                
                        <div>
                            <x-telegram::form.label for="name" :value="__('Name:')" />
                            <x-telegram::form.input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $subcategory->name)" required autofocus autocomplete="name" />
                            <x-telegram::form.error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-telegram::form.label for="icon_name" :value="__('Icon name:')" />
                            <x-telegram::form.input id="icon_name" name="icon_name" type="text" class="mt-1 block w-full" :value="old('icon_name', $subcategory->icon_name)" required autofocus autocomplete="icon_name" />
                            <x-telegram::form.error class="mt-2" :messages="$errors->get('icon_name')" />
                        </div>

                        

                        @forelse ($categories as $category)
                            <div>
                                <x-telegram::form.label for="{{ $category->id }}">
                                    <div class="w-full flex items-center space-x-2">
                                        <div class="flex-col">
                                            <x-telegram::form.input id="{{ $category->id }}" name="category" type="radio" value="{{ $category->id }}" :checked="($subcategory->category?->id === $category->id)"/>
                                        </div>
                                        <div class="flex-col">
                                            {{ $category->name }} 
                                        </div>
                                    </div>
                                </x-telegram::form.label>
                            </div>
                        @empty
                            
                        @endforelse

                        <div class="flex items-center gap-4">
                            <x-telegram::buttons.primary>{{ __('Update') }}</x-telegram::buttons.primary>
                        </div>
                    </form>
                </x-telegram::white-block>
            </div>
        </div>
    </x-slot>
</x-telegram::layout>
