@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
   <!-- Left Section -->
   <div class="w-1/2 p-8 flex flex-col justify-center items-center">
       <div class="w-full max-w-md">
           <h1 class="text-3xl font-bold mb-2">Create Account</h1>
           <p class="text-gray-500 mb-8">Please enter your details to register</p>

           <div class="flex gap-2 mb-8">
                <a href="{{ route('login') }}" class="flex-1 py-2 text-gray-500 bg-gray-100 rounded-lg text-center">Sign In</a>
                <a href="{{ route('register') }}" class="flex-1 py-2 bg-white border rounded-lg font-medium text-center">Signup</a>
            </div>
        

           <form action="{{ route('register') }}" method="POST" class="space-y-4">
               @csrf
               <div class="relative">
                   <input id="name" name="name" type="text" required class="w-full px-4 py-3 border rounded-lg" placeholder="Name">
               </div>

               <div class="relative">
                   <input id="email" name="email" type="email" required class="w-full px-4 py-3 border rounded-lg" placeholder="Email address">
               </div>

               <div class="relative">
                   <input id="password" name="password" type="password" required class="w-full px-4 py-3 border rounded-lg" placeholder="Password">
               </div>

               <div class="relative">
                   <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full px-4 py-3 border rounded-lg" placeholder="Confirm Password">
               </div>

               @if ($errors->any())
                   <div class="text-red-500">
                       <ul>
                           @foreach ($errors->all() as $error)
                               <li>{{ $error }}</li>
                           @endforeach
                       </ul>
                   </div>
               @endif

               <button type="submit" class="w-full py-3 bg-pink-600 text-white rounded-lg font-medium">Register</button>
           </form>

           <div class="text-center mt-6">
               <a href="{{ route('login') }}" class="font-medium text-pink-600 hover:text-pink-500">
                   Already have an account? Sign in
               </a>
           </div>
       </div>
   </div>

   <!-- Right Section with Wave Animation -->
   <div class="w-1/2 flex items-center justify-center relative overflow-hidden">
       <div class="wave-container">
           <div class="wave"></div>
           <div class="wave"></div>
           <div class="wave"></div>
       </div>
   </div>
</div>

<style>
.wave-container {
   position: relative;
   width: 100%;
   height: 100%;
   background: #f0f9ff;
   overflow: hidden;
}

.wave {
   position: absolute;
   width: 200%;
   height: 200%;
   background: linear-gradient(45deg, #FF1493, #8000ff, #ff0040, #FF69B4);
   opacity: 0.6;
   background-size: 400% 400%;
   animation: wave 15s ease-in-out infinite,
              gradientBG 10s ease infinite;
}

.wave:nth-child(2) {
   animation-delay: -5s;
}

.wave:nth-child(3) {
   animation-delay: -10s;
}

@keyframes wave {
   0%, 100% { transform: translate(-50%, -75%) rotate(0deg); }
   50% { transform: translate(-50%, -25%) rotate(180deg); }
}

@keyframes gradientBG {
   0% { background-position: 0% 50%; }
   50% { background-position: 100% 50%; }
   100% { background-position: 0% 50%; }
}
</style>
@endsection