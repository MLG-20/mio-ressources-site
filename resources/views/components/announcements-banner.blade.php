@php
    use Illuminate\Support\Carbon;

    $now = Carbon::now();
    $activeAnnouncements = \App\Models\Announcement::where('is_active', true)
        ->where(function ($query) use ($now) {
            $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
        })
        ->get();
@endphp

@if($activeAnnouncements->isNotEmpty())
<style>
    @keyframes scroll-marquee {
        0%   { transform: translateX(calc(100% + 20px)); }
        100% { transform: translateX(-100%); }
    }
    .scroll-text {
        display: inline-block;
        animation: scroll-marquee 30s linear infinite;
        white-space: nowrap;
    }
    @media (max-width: 768px) {
        .scroll-text {
            animation-duration: 20s;
        }
    }
</style>

<div x-data="{
    activeSlide: 0,
    slidesCount: {{ $activeAnnouncements->count() }},
    init() {
        if (this.slidesCount > 1) {
            setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.slidesCount
            }, 30000)
        }
    }
}" class="overflow-hidden bg-yellow-50 border-b border-blue-600">

    @foreach($activeAnnouncements as $index => $announcement)
        <div class="py-2 px-2 sm:px-4"
             x-show="activeSlide === {{ $index }}"
             x-transition:enter="transition-opacity duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-cloak>
            <span class="scroll-text text-sm sm:text-base md:text-base font-semibold text-gray-800">
                📢 {{ $announcement->title }} — {!! html_entity_decode(strip_tags($announcement->content)) !!}
            </span>
        </div>
    @endforeach
</div>
@endif
