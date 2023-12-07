<!-- Fonts -->
<link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/61cc44f0a1.js" crossorigin="anonymous"></script>
<style>
    body {
        font-family: 'Nunito', sans-serif;
    }
</style>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('pageTitle', 'Edit')

<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Course Classes
        </h2>
    </div>
</header>

<div class="min-h-screen w-full sm:px-6 lg:px-14 bg-gray-100">
    <div class="container pb-8">
        <div class="px-5 py-10">
            <form action="{{ route('course_classes.update', $courseClass->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1">
                    <label for="name" class="font-semibold">Name :</label>
                    <input class="mt-2 border border-gray-500 w-[500px] py-2 px-4 rounded-lg" type="text"
                        name="name" value="{{ $courseClass->name }}">
                </div>
                <div class="grid grid-cols-1 mt-5">
                    <label for="class_code" class="font-semibold">Class Code :</label>
                    <input class="mt-2 border border-gray-500 w-[500px] py-2 px-4 rounded-lg" type="text"
                        name="class_code" value="{{ $courseClass->class_code }}">
                </div>
                <div class="grid grid-cols-1 mt-5">
                    <label for="settings" class="font-semibold">Settings :</label>
                    <input class="mt-2 border border-gray-500 w-[500px] py-2 px-4 rounded-lg" type="text"
                        name="settings" value="{{ $courseClass->settings }}">
                </div>

                <button class="bg-gray-800 w-[150px] mt-8 py-2 text-white font-bold rounded-lg"
                    type="submit">Update</button>
            </form>
        </div>
    </div>
</div>
