<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
</head>
<body class="bg-dark">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Authors</th>
                    <th>Publishers</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($books as $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>
                            @foreach($value->authors as $author)
                                <p>{{$author->name}}</p>
                            @endforeach</td>
                        <td>
                            @foreach($value->publishers as $publisher)
                                <p>{{$publisher->name}}</p>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination">
                {!! $books->render() !!}
            </div>
        </div>
    </div>
</div>
</body>
</html>
