@vite('resources/css/app.css')

@section('pageTitle', 'Create New Classes')

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              {{ __('Create New Class') }}
            </h2>
        </div>
    </header>

    <div class="bg-gray-100">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
          <div class="text-sm breadcrumbs pl-2 py-6 font-bold text-gray-600">
            <ul class= "flex space-x-2" >
                <li class="text-blue-600"><a href="">Home</a></li>
                <li class="separator text-gray-400">&gt;</li>
                <li class="text-blue-600"><a href="{{ route('getAllClass') }}">Classes</a></li>
                <li class="separator text-gray-400">&gt;</li>
                <li>Create</li>
            </ul>
          </div>

          <div class="bg-white shadow overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200"> 
            <form method="POST" action="{{ route('class.create') }}">
                @csrf

            
                <div class="form-control w-full p-3">
                        <label class="label" >
                            <span class="label-text block text-md text-gray-600" >Course ID</span>
                        </label>
                        <select class="select mt-1 p-3 border rounded-md w-full max-w-xs" name="course_id">
                            <option disabled selected>Choose the course</option>
                            <!-- tambahin select id -->
                            @foreach ($courses as $course)
                            <option
                                value="{{ $course['code'] }}" {{ (old("course_code") == $course['code'] ? "selected":"") }}>{{ $course['name'] }}</option>
                            @endforeach

                        </select>
                </div>

                <div class="form-control w-full p-3">
                        <label class="label">
                            <span class="label-text block text-md text-gray-600">Syllabus</span>
                        </label>
                        <select class="select mt-1 p-3 border rounded-md w-full max-w-xs" name="syllabus_id">
                            <option disabled selected>Choose the syllabus</option>
                                @foreach($syllabi as $syllabus)
                                  <option value="{{ $syllabus['id'] }}">{{ $syllabus['title'] }}</option>
                                @endforeach
                        </select>
                        
                </div>

                <div class="form-control w-full p-3">
                  <label class="label"> <span class="label-text block">Class Name</span></label>
                  <input type="text" name="name" placeholder="Class name " required 
                    class="mt-1 p-2 border rounded-md w-full max-w-xs" value="{{ old('name') }}">
               </div>
               
                <div class="form-control w-full p-3">
                  <label class="label">
                      <span class="label-text block">Class Code</span>
                  </label>
                  <input type="text" name="class_code" placeholder="Class code" required
                    class="input mt-1 p-2 border rounded-md w-full max-w-xs" value="{{ old('class_code') }}"/>
                       
                </div>

                <div class="form-control w-full p-3">
                  <label class="label">
                    <span class="label-text block">Creator User Id</span>
                  </label>
                  <input type="text" name="creator_user_id" placeholder="Creator User Id" required
                      class="mt-1 p-2 border rounded-md w-full max-w-xs" value="{{ old('creator_user_id') }}"/>  
                </div>
                
                <div class="form-control w-full p-3">
                  <label class="label">
                    <span class="label-text block">Setting</span>
                  </label>
                  <input type="text" name="settings" placeholder="Setting"
                         class="input mt-1 p-2 border rounded-md w-full max-w-xs" value="{{ old('settings') }}"/>
                       
                </div>

                <div class="form-control w-full p-3">
                        <label class="label">
                            <span class="label-text block">Thumbnail Image</span>
                        </label>
                        <input type="text" name="thumbnail_img" placeholder="Link Thumbnail Image" class="input mt-1 p-2 border rounded-md w-full max-w-xs "/>
                      
                    </div>

                <div class="mt-4 p-4 space-x-2 ">
                        <button type="submit" class="btn btn-sm px-7 bg-purple-500 text-white p-2 rounded-md focus:outline-none focus:ring focus:border-purple-300 active:bg-purple-700">
                            Save
                        </button>
                        <a href="{{ route('getAllClass') }}">{{ __('Cancel') }}</a>
                </div>

              </form>
            </div>
          </div>
        </div>
    </div>