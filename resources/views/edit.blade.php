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
        <div class="flex">
            <div class="mr-10 py-10 flex">
                <div>
                    <h3 class="font-semibold text-xl text-gray-800 leading-tight pb-2">
                        Class Profile
                    </h3>
                    <form id="updateForm" action="{{ route('course_classes.update', $courseClass->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1">
                            <label for="name" class="font-semibold">Name :</label>
                            <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
                                name="name" value="{{ $courseClass->name }}">
                        </div>
                        <div class="grid grid-cols-1 mt-5">
                            <label for="class_code" class="font-semibold">Class Code :</label>
                            <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
                                name="class_code" value="{{ $courseClass->class_code }}">
                        </div>
                        <div class="grid grid-cols-1 mt-5">
                            <label for="settings" class="font-semibold">Settings :</label>
                            <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
                                name="settings" value="{{ $courseClass->settings }}">
                        </div>

                        <button class="bg-gray-800 w-[150px] mt-8 py-2 text-white font-bold rounded-lg"
                            type="submit">Update</button>
                        <button id="deleteButton" class="bg-red-800 w-[150px] mt-8 py-2 text-white font-bold rounded-lg"
                        >Delete</button>                         
                    </form>
                </div>
            </div>
            <div class="ml-10 px-5 py-10 flex">
                <div>
                    <h3 class="font-semibold text-xl text-gray-800 leading-tight pb-2">
                        Add Student
                    </h3>
                    <div id="addStudent">
                        <label for="table-search" class="sr-only">Cari Id</label>
                        <div class="relative mt-1 flex">
                            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 light:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="search" class="block pt-2 ps-10 text-sm text-gray-900 border w-[200px]  border-gray-500 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500" placeholder="Search for items">
                            <button onclick="searchBtn('{{ $courseClass->id }}')" class="bg-gray-800 w-[100px] ml-1 py-2 text-white font-bold rounded-lg"
                            type="submit">Search</button>
                        </div>
                        <div id="student-info"></div>
                    </div>
                </div>
            </div>
        </div>
        @if ($courseClass->joinClass != null)
        <div class="pb-4 bg-gray-100 light:bg-gray-900">
            <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                Students List
            </h3>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 light:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 light:bg-gray-700 light:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Student Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>                   
                    
                <tbody>
                    
                @foreach ($courseClass->joinClass as $student)
                    <tr class="bg-white border-b light:bg-gray-800 light:border-gray-700 hover:bg-gray-50 light:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap light:text-white">
                            {{$student->data->name}}
                        </th>
                        <td class="px-6 py-4">
                            {{$student->student_user_id}}
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" onclick="deleteStudent('{{ $student->id }}', '{{ $courseClass->id }}')" class="font-medium text-blue-600 light:text-blue-500 hover:underline">Delete</a>
                        </td>
                    </tr>             
                @endforeach
                </tbody>
            </table>
        </div>        
        @endif
    </div>
</div>
{{-- <form id="updateForm" action="{{ route('course_classes.update', $courseClass->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1">
        <label for="name" class="font-semibold">Name :</label>
        <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
            name="name" value="{{ $courseClass->name }}">
    </div>
    <div class="grid grid-cols-1 mt-5">
        <label for="class_code" class="font-semibold">Class Code :</label>
        <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
            name="class_code" value="{{ $courseClass->class_code }}">
    </div>
    <div class="grid grid-cols-1 mt-5">
        <label for="settings" class="font-semibold">Settings :</label>
        <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
            name="settings" value="{{ $courseClass->settings }}">
    </div>

    <button class="bg-gray-800 w-[150px] mt-8 py-2 text-white font-bold rounded-lg"
        type="submit">Update</button>
    <button id="deleteButton" class="bg-red-800 w-[150px] mt-8 py-2 text-white font-bold rounded-lg"
    >Delete</button>                         
</form> --}}
<form id="deleteForm" action="{{ route('deleteclass', $courseClass->class_code) }}" method="POST">
    @csrf
    @method('DELETE')            
</form>
<form id="deleteStudent" action="#" method="POST">
    @csrf
    @method('DELETE')            
</form> 

<script>
    document.getElementById('deleteButton').addEventListener('click', function(event) {
        event.preventDefault(); // Menghentikan aksi default (pengiriman formulir)
        document.getElementById('deleteForm').submit(); // Mengirim formulir penghapusan
    });

    function deleteStudent(studentId, classId) {
        let form = document.getElementById('deleteStudent');
        form.action = "{{ route('deleteStudent', ['idClass'=> ':classId','id' => ':studentId']) }}".replace(':studentId', studentId).replace(':classId',classId);
        form.submit();
    }

    async function searchBtn(classId) {
        let id = document.getElementById('search').value;
        let mytext = null;
        if (id != null) {
            try {
                let response = await fetch(`http://127.0.0.1:8080/api/users/${id}`);
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                myText = await response.json();
                console.log(myText);
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        } else return;
        if (myText != null) {
            document.getElementById('student-info').innerHTML = `
            <form id="updateForm" action="{{ route('addStudent')}}" method="POST">
                
                <div class="grid grid-cols-1">
                <input type="hidden" name="course_class_id" value="{{$courseClass->id}}" />
                </div>
                <div class="grid grid-cols-1 mt-8">
                    <label for="name" class="font-semibold">Name :</label>
                    <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
                        name="name" value="${myText.name}">
                </div>
                <div class="grid grid-cols-1">
                    <label for="name" class="font-semibold">Student Id :</label>
                    <input class="mt-2 border border-gray-500 w-[400px] py-2 px-4 rounded-lg" type="text"
                        name="student_user_id" value="${myText.id}">
                </div>
                <button class="bg-gray-800 w-[150px] mt-8 py-2 text-white font-bold rounded-lg"
                type="submit">Add</button>
                      
            </form>
            `;
        }else return;
    }

</script>