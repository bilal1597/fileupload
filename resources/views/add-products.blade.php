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
                <h1>Add Product </h1>
            </div>
                <div class="card-body">
                    <form action="{{route('post.product')}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Product name</label>
                            <input name="product_name" type="text" value="{{old('product_name')}}" class="form-control"  id="formGroupExampleInput" placeholder="Enter Product name">
                        @error('product_name')
                            <span class="text-danger">{{$message}} </span>
                        @enderror
                        </div>
                          <div class="mb-3">
                            <label for="formGroupExampleInput2" class="form-label">Details</label>
                            <input name="details" type="text" value="{{old('details')}}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Details">
                            @error('details')
                            <span class="text-danger">{{$message}} </span>
                        @enderror
                        </div>
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Upload File/Image</label>
                            <input type="file" name="image"  class="form-control" id="formGroupExampleInput" >
                            @error('image')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                          <div class="mb-3">
                            <label for="formGroupExampleInput2" class="form-label">Price</label>
                            <input name="price" type="number" value="{{old('price')}}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Price">
                            @error('price')
                            <span class="text-danger">{{$message}} </span>
                        @enderror
                        </div>

                          <button type="submit" class="btn btn-primary">Save</button>
                    </form>

            </div>
        </div>
    </div>
</body>
</html>
