<?php

namespace App\Http\Requests;

use App\Enums\Sort;
use App\Models\Sorting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filters' => 'array|nullable',
            'sort' => 'integer|nullable',
            'search' => 'string|nullable',
            'location' => 'array|nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $data = $this->unserializeData($this->input('data'));

        $this->merge([
            'filters' => $this->getFilters($data),
            'sort' => $this->getSorting($data),
            'search' => $this->getSearch($data),
            'location' => $this->getLocation($data),
        ]);
    }

    public function serializedData()
    {
        foreach ($this->validated() as $key => $value) {
            Session::put($key, $value);
        }

        return urlencode(encrypt(serialize($this->validated())));
    }

    private function unserializeData(?string $data): ?array
    {
        return $data ? unserialize(decrypt(urldecode($data))) : null;
    }

    private function recursive_array_filter($array)
    {
        if (is_array($array)) {
            $array = array_filter($array);

            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->recursive_array_filter($value);
                }
            }
        }

        return $array;
    }

    private function getSorting($data) 
    {
        $sort = $data['sort'] ?? session('sort') ?? Sorting::default()->id;

        return $this->input('sort', $sort);
    }

    private function getFilters($data)
    {
        $filters = $data['filters'] ?? session('filters') ?? [];

        return $this->recursive_array_filter($this->input('filters', $filters));
    }

    private function getSearch($data)
    {
        $search = $data['search'] ?? session('search');

        return $this->input('search', $search);
    }

    private function getLocation($data)
    {
        $location = $data['location'] ?? session('location');

        return $this->recursive_array_filter($this->input('location', $location));
    }
}
