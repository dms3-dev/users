@php
    $bgColor;

    if( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::ERROR)->exists()) $bgColor='danger';
                    elseif ( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::WARNING)->exists()) $bgColor='warning';
                else $bgColor='success';
@endphp

<div class="mr-4  flex items-center gap-0 text-{{$bgColor}}-500  p-1.5 rounded-3xl cursor-pointer font-bold text-l" onclick="goToSystemHealth()">


        <span class="inline-flex  items-center  text-sm font-bold  rounded-full">
{{--                <span class="w-2 h-2 me-1 bg-{{$bgColor}}-500 rounded-full"></span>--}}


            @if( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::ERROR)->exists())
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-6 h-6">
  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
</svg>



            @elseif ( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::WARNING)->exists())

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-6 h-6">
  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
</svg>



            @else
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-6 h-6">
  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
</svg>


            @endif
            </span>

    Health

</div>


<script>

    function goToSystemHealth() {
        window.location = '{{ \Mediamouse\Users\Filament\Pages\SystemHealth::getUrl() }}';
    }
</script>
