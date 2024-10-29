<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
</head>
<body>
    <div class="container">
        <h1>View File</h1>

        <iframe src="{{ asset($data->file) }}" width="100%" height="600px"></iframe>
        <br>
        <a href="{{ route('show.products') }}" class="btn btn-secondary">Back to Products</a>
    </div>
</body>
</html>
