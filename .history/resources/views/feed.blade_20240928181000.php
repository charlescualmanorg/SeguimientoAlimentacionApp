@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Mostrar todas las publicaciones -->
            @foreach($posts as $post)
            <div class="card mb-4">
                <div class="card-header">
                    {{ $post->user->name }} publicó:
                </div>

                <div class="card-body">
                    <p>{{ $post->content }}</p>

                    <!-- Likes -->
                    <form action="{{ route('posts.like', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            Like ({{ $post->likes->count() }})
                        </button>
                    </form>

                    <!-- Comentarios -->
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="form-group">
                            <textarea name="comment" class="form-control" rows="2" placeholder="Escribe un comentario..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success">Comentar</button>
                    </form>

                    <!-- Listar comentarios -->
                    <div class="mt-3">
                        @foreach($post->comments as $comment)
                        <div class="alert alert-secondary">
                            <strong>{{ $comment->user->name }}</strong> comentó:
                            <p>{{ $comment->comment }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
