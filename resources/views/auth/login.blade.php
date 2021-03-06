@extends('layouts.app')

@section('content')
<div style="height:100px; width:100%; clear:both;"></div>
<div class="container mx-auto my-4">
    <div class="md:w-1/3 w-full md:mx-auto bg-white rounded-lg border border-red-300 p-8">
    <!-- <div class="px-3 bg-gray-800 mx-2 text-center text-white font-bold rounded-md hover:bg-gray-700 cursor-pointer flex items-center flex-shrink-0">
            <a href="{{route('home')}}" class="flex ml-10 bg-gray-800 text-white font-bold rounded-md hover:bg-gray-700"><img src="/img/IRC_logo/logo_square.png" class="self-center" style='height:45px;' width="45px"> <span class="p-3">Indian Racing Community</span> </a>
    </div> -->
    <div class="p-3 bg-red-200 text-center font-semibold rounded mx-2 my-2 text-red-700">
        @if(Session::has('error'))
            {{session('error')}}
        @else
            Access Denied!
        @endif
    </div>
    <div class="p-3 bg-blue-200 text-center font-semibold rounded mx-2 my-4">
        You need to Login to access this content
    </div>
    <div class="px-4 flex mx-2 py-3 bg-purple-600 text-white rounded font-semibold shadow-md cursor-pointer text-center hover:bg-gray-900">
        <a href="{{route('login.discord')}}" class="w-full"><i class="fab fa-discord mr-4"></i></i>Login with Discord</a>
    </div>
    </div>
</div>

<div style="height:400px; width:100%; clear:both;"></div>
@endsection
