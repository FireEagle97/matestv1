@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Profile Information</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update your account's profile information and email address.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('producer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $producer->name) }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                            <textarea id="bio" name="bio" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('bio', $producer->bio) }}</textarea>
                        </div>

                        <!-- Contact Info -->
                        <div>
                            <label for="contact_info" class="block text-sm font-medium text-gray-700">Contact Information</label>
                            <input type="text" name="contact_info" id="contact_info" value="{{ old('contact_info', $producer->contact_info) }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Profile Picture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                            <div class="mt-1 flex items-center">
                                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                    @if($producer->profile_url)
                                        <img src="{{ $producer->profile_url }}" alt="Profile picture" class="h-full w-full object-cover">
                                    @else
                                        <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.111 0 7.8 1.34 9.95 3.993l.046.001zM16.002 18c-1.35.985-3.003 1.55-4.998 1.55-1.995 0-3.648-.565-4.998-1.55A12.975 12.975 0 0012.004 17c2.396 0 4.583.8 5.998 1zM12 13c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm0-9a4 4 0 100 8 4 4 0 000-8z"/>
                                        </svg>
                                    @endif
                                </span>
                                <input type="file" name="profile_picture" id="profile_picture" class="ml-5">
                            </div>
                        </div>

                        <!-- Banner Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Banner Image</label>
                            <div class="mt-1 flex items-center">
                                <span class="inline-block h-32 w-full overflow-hidden bg-gray-100">
                                    @if($producer->banner_url)
                                        <img src="{{ $producer->banner_url }}" alt="Banner image" class="h-full w-full object-cover">
                                    @else
                                        <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.111 0 7.8 1.34 9.95 3.993l.046.001zM16.002 18c-1.35.985-3.003 1.55-4.998 1.55-1.995 0-3.648-.565-4.998-1.55A12.975 12.975 0 0012.004 17c2.396 0 4.583.8 5.998 1zM12 13c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm0-9a4 4 0 100 8 4 4 0 000-8z"/>
                                        </svg>
                                    @endif
                                </span>
                                <input type="file" name="banner_image" id="banner_image" class="ml-5">
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 