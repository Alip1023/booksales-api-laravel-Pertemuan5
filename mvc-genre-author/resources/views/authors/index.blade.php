
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Author</title>
</head>
<body>
    <h1>Daftar Author Buku</h1>

    <nav>
        <a href="{{ route('genres.index') }}">Genre</a> |
        <a href="{{ route('authors.index') }}">Author</a>
    </nav>

    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Author</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($authors as $author)
                <tr>
                    <td>{{ $author['id'] }}</td>
                    <td>{{ $author['name'] }}</td>
                    <td>{{ $author['email'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
