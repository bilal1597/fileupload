<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>Upload Product Images </h1>
            <a href="/products" class="btn btn-primary float-end">Back</a> <br> <br>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{session('status')}}</div>
            @endif
            <h3>Product name: {{$show_images ->product_name }}</h3>
            <hr> <br>

            @if ($errors->any())
            <ul class="alert alert-danger ">
                @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
            @endif
            <form action="{{url('/image/view/'.$show_images ->id)}}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="mb-3 ">
                    <label>Upload Images</label>   <br> <br>
                    <input type="file" name="images[]" multiple  class="form-control">
                </div>
                <div class="mb-3 ">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

        </form>
        </div>
    </div>
    <div class="col-md-12 mt-4 " >
        @foreach ($product_images as $imgs)
        <img src="{{asset($imgs ->image)}}" class="border p-2 m-3" style="width: 100px; height:140px " alt="">
        <a href="{{url('image/delete/'.$imgs ->id)}}" class="btn btn-sm btn-danger">-</a>
        @endforeach
    </div>
</div>
</body>
</html>

