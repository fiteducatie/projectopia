<div
    x-data="{ open: @entangle('show') }"
    x-show="open"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    @click.self="open = false">

    <div class="bg-white rounded-xl shadow-xl p-6 w-14/20 relative">
        <button
            x-on:click="open = false"
            class="absolute top-2 right-2 text-gray-400 hover:text-gray-800">&times;
        </button>

        <h2 class="text-xl font-semibold mb-4">Hoe gebruik je deze pagina?</h2>
        <p>{!! $infoPopup !!}</p>
    </div>
</div>
