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

                    <!-- Mosaico de imágenes -->
                    <div class="row">
                        @foreach($post->images->take(4) as $index => $image)
                        <div class="col-6 mb-2">
                            <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid" alt="Imagen del post">
                        </div>
                        @endforeach

                        <!-- Si hay más de 4 imágenes, mostrar un mosaico con texto -->
                        @if($post->images->count() > 4)
                        <div class="col-6 mb-2 position-relative">
                            <img src="{{ asset('storage/' . $post->images[3]->path) }}" class="img-fluid" alt="Imagen del post">
                            <div class="overlay d-flex justify-content-center align-items-center"
                                 style="position:absolute; top:0; left:0; right:0; bottom:0; background-color: rgba(0, 0, 0, 0.6);">
                                <a href="#" class="text-white text-center" data-toggle="modal" data-target="#carouselModal{{$post->id}}">
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
            <div class="
