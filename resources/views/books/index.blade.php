<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog BookSales</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Katalog Buku</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($books as $book)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="h-48 bg-gray-200 flex flex-col items-center justify-center p-4 text-center">
                        <span class="text-xs text-gray-400 mb-1">Cover File:</span>
                        <span class="text-sm font-semibold text-gray-600 italic">{{ $book->cover_photo }}</span>
                    </div>
                    <div class="p-5">
                        <h2 class="text-xl font-bold text-gray-800 truncate">{{ $book->title }}</h2>
                        <p class="text-sm text-gray-600 mb-3">Penulis: <span class="font-medium text-gray-800">{{ $book->author->name ?? 'Tanpa Penulis' }}</span></p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                            <span class="text-xs font-medium px-2 py-1 bg-green-100 text-green-800 rounded-full">Stok: {{ $book->stock }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-full text-center">Belum ada buku tersedia.</p>
            @endforelse
        </div>
    </div>
</body>
</html>