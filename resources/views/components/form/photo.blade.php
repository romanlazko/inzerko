@props(['name' => '', 'src' => ''])

<div {!! $attributes->merge(['class' => 'aspect-square rounded-full overflow-hidden flex items-center justify-center border h-min']) !!}>
    <input id="photo" name="{{ $name }}" type="file" class="hidden"/>
    <x-form.label for="photo">
        <div class="w-full h-full object-cover object-center" >
            <img id="photoPreview" src="{{ $src }}" class="w-full h-full object-cover object-center">
        </div>
    </x-form.label>
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#photo').change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#photoPreview').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endpush