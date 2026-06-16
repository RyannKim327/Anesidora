@extends('app')

@section('title', $user->name . "'s Profile")

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <!-- Facebook Style Header -->
    <div class="bg-slate-800 rounded-xl overflow-hidden shadow-2xl border border-slate-700 mb-8">
        <!-- Cover Photo -->
        <div class="h-48 md:h-64 bg-gradient-to-r from-blue-600 to-indigo-800 relative">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        </div>

        <!-- Profile Info Bar -->
        <div class="relative px-6 pb-6">
            <div class="flex flex-col md:flex-row items-center md:items-end -mt-16 md:-mt-20 mb-4 md:space-x-6">
                <!-- Avatar -->
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-slate-800 bg-slate-700 shadow-2xl flex items-center justify-center text-5xl font-bold text-white overflow-hidden relative group">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                    @if($isOwner)
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity cursor-pointer">
                            <i class="fa fa-camera text-xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Name and Stats -->
                <div class="mt-4 md:mb-2 text-center md:text-left flex-1">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">{{ $user->name }}</h1>
                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mt-2 text-slate-400">
                        <span class="flex items-center"><i class="fa fa-calendar-o mr-2 text-blue-500"></i>Joined {{ $user->created_at->format('M Y') }}</span>
                        <span class="flex items-center"><i class="fa fa-file-text-o mr-2 text-indigo-500"></i>{{ count($user->files) }} uploads</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($isOwner)
                    <div class="mt-6 md:mb-4 flex gap-3">
                        <a href="{{ route('upload.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold transition-all transform hover:scale-105 shadow-lg flex items-center">
                            <i class="fa fa-upload mr-2"></i>Upload
                        </a>
                        <button class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2.5 rounded-lg font-bold transition-all flex items-center border border-slate-600">
                            <i class="fa fa-cog"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Sidebar: About/Intro -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-slate-800/80 backdrop-blur-sm p-6 rounded-xl border border-slate-700 shadow-xl">
                <h3 class="text-xl font-bold mb-4 text-white flex items-center">
                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                    Intro
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded bg-slate-700 flex items-center justify-center mr-3 mt-0.5 text-blue-400">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Email</p>
                            <p class="text-slate-200">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded bg-slate-700 flex items-center justify-center mr-3 mt-0.5 text-indigo-400">
                            <i class="fa fa-info-circle"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Status</p>
                            <p class="text-slate-200">Active Member</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content: Recent Files -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-800/80 backdrop-blur-sm p-6 rounded-xl border border-slate-700 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <span class="w-2 h-6 bg-indigo-500 rounded-full mr-3"></span>
                        Recent Uploads
                    </h3>
                    <span class="bg-slate-700 text-slate-300 text-xs px-3 py-1 rounded-full border border-slate-600 font-medium">
                        {{ count($files) }} visible
                    </span>
                </div>

                @if($files->isEmpty())
                    <div class="bg-slate-900/50 rounded-xl p-12 text-center border border-dashed border-slate-700">
                        <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-600">
                            <i class="fa fa-file-o text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-slate-400">No uploads found</h4>
                        <p class="text-slate-500 mt-1 max-w-xs mx-auto">Upload some files to see them listed here on your profile.</p>
                        @if($isOwner)
                            <a href="{{ route('upload.index') }}" class="mt-6 inline-block text-blue-500 hover:text-blue-400 font-bold underline transition-colors">Start uploading now</a>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($files as $file)
                            @if (!$file->expiration || $file->expiration->isFuture())
                            <div class="group relative bg-slate-700/30 hover:bg-slate-700/60 p-4 rounded-xl border border-slate-700 hover:border-blue-500/50 transition-all duration-300 shadow-sm hover:shadow-blue-500/10">
                                <div class="flex items-center space-x-4">
                                    <!-- File Icon -->
                                    <div class="w-14 h-14 rounded-lg {{ $file->password ? 'bg-amber-500/10 text-amber-500' : 'bg-blue-500/10 text-blue-500' }} flex items-center justify-center text-2xl transition-transform group-hover:scale-110">
                                        <i class="fa {{ $file->password ? 'fa-lock' : 'fa-file-code-o' }}"></i>
                                    </div>

                                    <!-- File Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <h4 class="font-bold text-slate-100 truncate group-hover:text-white transition-colors">
                                                <a href="/file/{{ $file->public_url }}">{{ $file->file }}</a>
                                            </h4>
                                            @if($file->password)
                                                <span class="ml-2 px-2 py-0.5 rounded text-[10px] uppercase font-black tracking-tighter bg-amber-500/20 text-amber-400 border border-amber-500/30">Private</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center mt-1 text-sm text-slate-500 space-x-3">
                                            <span class="flex items-center"><i class="fa fa-clock-o mr-1.5 text-[12px]"></i>{{ $file->created_at->diffForHumans() }}</span>
                                            <span class="hidden sm:inline">•</span>
                                            <span class="flex items-center"><i class="fa fa-download mr-1.5 text-[12px]"></i>{{ $file->downloads }} hits</span>
                                        </div>
                                    </div>

                                    <!-- Action -->
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="/file/{{ $file->public_url }}" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-500 shadow-lg transition-all">
                                            <i class="fa fa-external-link"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
