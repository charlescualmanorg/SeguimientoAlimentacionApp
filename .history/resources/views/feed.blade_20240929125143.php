@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($posts as $post)
            <div class="card mb-4">
                <div class="card-header">
                    {{ $post->user->name }} publicó:
                    <!-- Botón de eliminar post -->
                    @if(Auth::user()->id === $post->user_id)
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 m-0 text-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    <p>{{ $post->content }}</p>
                    
                    <!-- Mostrar imágenes aquí -->

                    <!-- Likes -->
                    <form action="{{ route('posts.like', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            Like ({{ $post->likes->count() }})
                        </button>
                    </form>

                    <!-- Comentarios -->
                    <h5>Comentarios:</h5>
                    <div class="comments-container">
                        @foreach($post->comments->sortByDesc('created_at') as $comment)
                        <div class="mb-2">
                            @if($comment->user_id === $post->user_id)
                                <!-- Comentarios del propietario del post alineados a la derecha -->
                                <div class="d-flex justify-content-end">
                                    <div class="comment-bubble bg-primary text-white p-2 rounded">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                            @else
                                <!-- Comentarios de otros usuarios alineados a la izquierda -->
                                <div class="d-flex justify-content-start">
                                    <div class="comment-bubble bg-light p-2 rounded">
                                        <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                                    </div>
                                </div>
                            @endif

                            <!-- Botón de eliminar comentario -->
                            @if(Auth::user()->id === $comment->user_id || Auth::user()->id === $post->user_id)
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este comentario?');">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Formulario para agregar un comentario -->
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="2" placeholder="Escribe un comentario..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Comentar</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
