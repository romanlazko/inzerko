@props(['_method' => '', 'method' => 'post', 'action' => ''])
<form method="post" action="{{ $action }}" {!! $attributes->merge(['class' => '']) !!}>
    @csrf
    @method($method)
    
    <div class="space-y-6">
        {{ $slot }}
    </div>
</form>