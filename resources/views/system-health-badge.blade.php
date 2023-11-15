@php
    $bgColor;

    if( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::ERROR)->exists()) $bgColor='danger';
                    elseif ( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::WARNING)->exists()) $bgColor='warning';
                else $bgColor='success';
@endphp

<div class="mr-4  flex items-end gap-3  p-1.5 rounded-3xl cursor-pointer text-l" onclick="goToSystemHealth()">


        <span class="inline-flex  items-center bg-{{$bgColor}}-500 text-white text-xl font-medium  p-0.5 rounded-full">
{{--                <span class="w-2 h-2 me-1 bg-{{$bgColor}}-500 rounded-full"></span>--}}


            @if( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::ERROR)->exists())
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
</svg>


            @elseif ( \Mediamouse\Users\Models\SystemHealth::query()->where('status',\Mediamouse\Users\Enums\SystemHealthStatus::WARNING)->exists())
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                     stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z"/>
</svg>

            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4.5"
                     stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
</svg>

            @endif
            </span>

    Health Check

</div>


<script>

    function goToSystemHealth() {
        window.location = '{{ \Mediamouse\Users\Filament\Pages\SystemHealth::getUrl() }}';
    }
</script>
