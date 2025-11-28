@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Artikel</h1>
        <p class="text-gray-600 mb-4">Selamat datang di halaman manajemen! Di sini Anda dapat menambah, mengedit, dan menghapus artikel.</p>
        
        <a href="{{ route('admin.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow">
            + Tambah Artikel Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $article)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full hover:shadow-2xl transition duration-300 border border-gray-100">
                <div class="h-48 overflow-hidden relative">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif
                    <div class="absolute top-0 right-0 bg-gray-900 bg-opacity-70 text-white text-xs px-2 py-1 m-2 rounded">
                        {{ $article->created_at->format('d M Y') }}
                    </div>
                </div>

                <div class="p-6 flex-grow flex flex-col">
                    <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $article->title }}</h2>
                    <p class="text-sm text-gray-500 mb-4">Penulis: <span class="font-semibold">{{ $article->user->name ?? 'Unknown' }}</span></p>
                    <p class="text-gray-600 mb-4 flex-grow line-clamp-3">
                        {{ Str::limit($article->content, 120) }}
                    </p>
                    
                    <hr class="border-gray-200 mb-4">

                    <div class="flex space-x-3 mt-auto">
                        <a href="{{ route('admin.edit', $article->id) }}" class="flex-1 text-center bg-yellow-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-yellow-600 transition duration-300 shadow-sm flex items-center justify-center">
                            Edit
                        </a>

                        <form action="{{ route('admin.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-700 transition duration-300 shadow-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900">Belum ada artikel</h3>
                <p class="text-gray-500 mt-1">Silakan tambahkan artikel baru untuk memulainya.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection