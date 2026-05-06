@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Section -->
    <div class="w-1/2 p-8 flex flex-col justify-center items-center">
        <div class="w-full max-w-md">

            <h1 class="text-3xl font-bold mb-2">Welcome Back</h1>
            <p class="text-gray-500 mb-8">Welcome Back, Please enter Your details</p>

            <div class="flex gap-2 mb-8">
                <a href="{{ route('login') }}" class="flex-1 py-2 bg-white border rounded-lg font-medium text-center">Sign In</a>
                <a href="{{ route('register') }}" class="flex-1 py-2 text-gray-500 bg-gray-100 rounded-lg text-center">Signup</a>
            </div>

            <!-- Login Form Start -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div class="relative">
                    <input type="email" name="email" class="w-full pl-4 pr-4 py-3 border rounded-lg" placeholder="Email Address" required autofocus>
                </div>

                <div class="relative">
                    <input type="password" name="password" class="w-full pl-4 pr-4 py-3 border rounded-lg" placeholder="Password" required>
                </div>

                <button type="submit" class="w-full py-3 bg-pink-600 text-white rounded-lg font-medium">Continue</button>
            </form>
            <!-- Login Form End -->

            <p class="text-gray-500 text-sm text-center mt-8">
                Wernoin adalah platform inovatif untuk eksplorasi, pengelolaan, dan pembuatan palet warna yang mempermudah desain visual.
            </p>
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

            0%,
            100% {
                transform: translate(-50%, -75%) rotate(0deg);
            }

            50% {
                transform: translate(-50%, -25%) rotate(180deg);
            }
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

    </style>
</div>
@endsection
