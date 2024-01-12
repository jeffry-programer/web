@if ($paginator->hasPages())
<div class="row mt-3">
    <div class="col-4 offset-md-4">
        <ul class="flex justify-between">
            <!-- prev -->
            @if ($paginator->onFirstPage())
            <li class="w-20 py-1 text-center rounded border bg-gray-200">Anterior</li>
            @else
            <li class="w-20 py-1 text-center rounded border shadow bg-white cursor-pointer" wire:click="previousPage">Anterior</li>
            @endif
            <!-- prev end -->

            <!-- numbers -->
            @foreach ($elements as $element)
            <div class="flex">
                @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="mx-2 w-10 px-2 py-1 text-center rounded border shadow bg-primary text-white cursor-pointer" wire:click="gotoPage({{$page}})">{{$page}}</li>
                @else
                <li class="mx-2 w-10 px-2 py-1 text-center rounded border shadow bg-white cursor-pointer" wire:click="gotoPage({{$page}})">{{$page}}</li>
                @endif
                @endforeach
                @endif
            </div>
            @endforeach
            <!-- end numbers -->


            <!-- next  -->
            @if ($paginator->hasMorePages())
            <li class="w-20 py-1 text-center rounded border shadow bg-white cursor-pointer" wire:click="nextPage">Siguiente</li>
            @else
            <li class="w-20 py-1 text-center rounded border bg-gray-200">Siguiente</li>
            @endif
            <!-- next end -->
        </ul>
    </div>
</div>
@endif