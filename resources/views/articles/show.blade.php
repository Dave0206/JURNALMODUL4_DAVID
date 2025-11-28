@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        
        <a href="{{ route('home') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Kembali ke Home</a>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>
        <p class="text-gray-500 text-sm mb-6">Dibuat oleh: {{ $article->user->name ?? 'Unknown' }} | {{ $article->created_at->format('d M Y') }}</p>

        @if($article->image)
            <div class="flex justify-center mb-6">
                <img src="{{ asset('storage/' . $article->image) }}" 
                     alt="{{ $article->title }}" 
                     class="rounded-lg shadow-md object-cover"
                     style="max-height: 400px; width: auto; max-width: 100%;"> 
            </div>
        @endif

        <div class="prose max-w-none text-gray-800 leading-relaxed text-justify mb-8">
            {!! nl2br(e($article->content)) !!}
        </div>

        <hr class="my-8 border-gray-300">

        <h3 class="text-2xl font-bold mb-4">Komentar ({{ $article->comments->count() }})</h3>

        <div class="space-y-4 mb-8">
            @forelse($article->comments as $comment)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <strong class="text-gray-900">{{ $comment->user->name }}</strong>
                            <span class="text-gray-500 text-xs ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                            <p class="text-gray-700 mt-1">{{ $comment->comment }}</p>
                        </div>
                        
                        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::id() === $comment->user_id))
                            <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-sm hover:underline">Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic">Belum ada komentar. Jadilah yang pertama!</p>
            @endforelse
        </div>

        @auth
            <form action="{{ route('comments.store', $article->id) }}" method="POST" class="bg-gray-100 p-4 rounded-lg">
                @csrf
                <label for="comment" class="block text-gray-700 font-bold mb-2">Tinggalkan Komentar</label>
                <textarea name="comment" id="comment" rows="3" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tulis komentar Anda di sini..." required></textarea>
                <button type="submit" class="mt-3 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Kirim Komentar</button>
            </form>
        @else
            <div class="bg-yellow-100 p-4 rounded text-yellow-800 text-center">
                Silakan <a href="{{ route('login') }}" class="font-bold underline">Login</a> untuk memberikan komentar.
            </div>
        @endauth

    </div>
</div>
@endsection