@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nueva Publicación</div>

                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="content">Contenido</label>
                            <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="images">Imágenes (puedes subir hasta 10)</label>
                            <input type="file" name="images[]" id="images" class="form-control-file" multiple accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Publicar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
