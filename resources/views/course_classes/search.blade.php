<!-- Fonts -->
<link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/61cc44f0a1.js" crossorigin="anonymous"></script>
<style>
    body {
        font-family: 'Nunito', sans-serif;
    }
</style>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('pageTitle', 'get All classes')

<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Search Result
        </h2>
    </div>
</header>

<div class="min-h-screen w-full sm:px-6 lg:px-14 bg-gray-100">
    <div class="container pb-8">
        <div class="flex flex-row sm:justify-between mb-3 p-4 sm:px-0 -mr-2 sm:-mr-3">
            <form action="{{ route('course_classes.search') }}" method="GET" class="w-1/3">
                <div class="form-control">
                    <div class="flex items-center">
                        <input type="text" name="query" placeholder="Search…"
                            class="mt-1 p-3 border rounded-md w-full max-w-xs" value="" />
                        <button type="submit"
                            class="btn h-12 w-12 btn-square rounded-r-md bg-gray-800 hover:bg-black flex items-center justify-center"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white " fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <p class="mb-5">Showing match results for: {{ $query }}</p>

        @if (count($results) > 0)
            <ul class="grid md:grid-cols-3 text-white mb-4 gap-x-6 gap-y-4 md:gap-y-12">
                @foreach ($results as $result)
                    <li
                        class="flex flex-col p-2 overflow-hidden bg-gray-700 border-t border-gray-600 shadow-2xl shadow-primary-800/10 rounded-xl">
                        <a href="" class="block flex items-center justify-center">
                            <?php
                            // check if thumbnail_img is a url or a path
                            $isUrl = filter_var($result['thumbnail_img'], FILTER_VALIDATE_URL);
                            if (empty($result['thumbnail_img'])) {
                                $result['thumbnail_img'] = "https://via.placeholder.com/374x210/000000/FFFFFF?text={$result['name']}";
                            } else {
                                $result['thumbnail_img'] = $isUrl ? $result['thumbnail_img'] : url('storage/' . substr($result['thumbnail_img'], 6));
                            }
                            ?>
                            <img src="{{ $result['thumbnail_img'] }}" class="aspect-[16/9] bg-gray-800 rounded-lg">
                        </a>
                        <header class="flex flex-col grow justify-between px-2 py-4 mt-2">
                            <h2 class="text-xl font-bold">
                                <a href="">{{ $result['name'] }}</a>
                            </h2>
                            <ul class="flex flex-wrap items-center gap-4 mt-6 text-sm">
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-users-rectangle"></i>
                                    <span>{{ $result['class_code'] }}</span>
                                </li>
                            </ul>
                        </header>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center">
                <p class="text-gray-500">{{ __('No classes found.') }}</p>
            </div>
        @endif
    </div>
</div>

