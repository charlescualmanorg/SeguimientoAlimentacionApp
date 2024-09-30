@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($posts as $post)
            <div class="card mb-4 position-relative">
                <div class="card-header">
                    {{ $post->user->name }} publicó:

                    <!-- Botón de eliminar (solo visible para el creador del post) -->
                    @if(Auth::user()->id === $post->user_id)
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 m-0 text-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este post?');">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    <p>{{ $post->content }}</p>

                    <!-- Mosaico de imágenes -->
                    <div class="row">
                        @foreach($post->images->take(4) as $index => $image)
                        <div class="col-6 mb-2">
                            <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid" alt="Imagen del post">
                        </div>
                        @endforeach

                        @if($post->images->count() > 4)
                        <div class="col-6 mb-2 position-relative">
                            <img src="{{ asset('storage/' . $post->images[3]->path) }}" class="img-fluid" alt="Imagen del post">
                            <div class="overlay d-flex justify-content-center align-items-center"
                                 style="position:absolute; top:0; left:0; right:0; bottom:0; background-color: rgba(0, 0, 0, 0.6);">
                                <a href="#" class="text-white" data-toggle="modal" data-target="#carouselModal{{$post->id}}">
                                    +{{ $post->images->count() - 4 }} imágenes
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Modal con el carrusel -->
                    <div class="modal fade" id="carouselModal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="carouselModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Imágenes del post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="carousel{{$post->id}}" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($post->images as $key => $image)
                                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100" alt="Imagen del post">
                                            </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#carousel{{$post->id}}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carousel{{$post->id}}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Likes y Comentarios -->
                    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="mb-2">
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
                                        {{ $comment->comment }}
                                    </div>
                                </div>
                            @else
                                <!-- Comentarios de otros usuarios alineados a la izquierda -->
                                <div class="d-flex justify-content-start">
                                    <div class="comment-bubble bg-light p-2 rounded">
                                        <strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}
                                    </div>
                                </div>
                            @endif
<div>
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
                        </div>
                        @endforeach
                    </div>
                    <form action="{{ route('comments.store', $post->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="comment" class="form-control" placeholder="Escribe un comentario..." required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Comentar</button>
                            </div>
                        </div>
                    </form>
<!-- Listar Comentarios -->
<!--                     
                    <div class="mt-3">
                        <h6>Comentarios:</h6>
                        @foreach($post->comments as $comment)
                            <div class="border-bottom pb-1">
                                <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                            </div>
                        @endforeach
                    </div> -->
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
