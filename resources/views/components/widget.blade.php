<div class="rounded-2xl bg-white text-[var(--grijs-7)] border-gray-200 border-1 p-5 {{ isset($small) ? "flex-auto" : "flex-initial max-w-[900px]"}} {{$class ?? ""}}">
    @if(!isset($noedit) && isset($menuItems))
    <div class="widget-menu" tabindex="0">
        <ol>
            {!! $menuItems !!}
        </ol>
    </div>
    @endif
    <h2 class="font-bold">{{ $title }}</h2>
    {!! $slot !!}
    @if(isset($bottomText))<p class="text-[var(--grijs-6)]">{{ $bottomText }}</p>@endif
</div>
