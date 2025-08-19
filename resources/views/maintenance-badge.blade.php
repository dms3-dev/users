<div
    class="environment-indicator hidden sm:flex items-center h-10 rounded-lg px-3 text-white text-sm font-medium cursor-pointer"
    style="background-color: orange; margin-right: 1rem;"
    onclick="copyLinkWithSecret()"
>
    Maintenance
</div>

<input id="urlWithSecret" type="text" value="{{ config('app.url') }}/{{ $secret }}" style="display: none;" />

<script>

    function copyLinkWithSecret(){
        var copyText = document.getElementById("urlWithSecret");

        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        navigator.clipboard.writeText(copyText.value);

        alert("Copied the maintenance url");
    }
</script>
