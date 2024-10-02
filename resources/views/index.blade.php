<!-- resources/views/posts/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">Mis Publicaciones</h2>

    <div class="row">
        @foreach($dates as $date)
            <div class="col-12 mb-2">
                <div class="card" style="cursor: pointer;" onclick="loadPublications('{{ $date->date }}')">
                    <div class="card-body">
                        <h5 class="card-title">{{ \Carbon\Carbon::parse($date->date)->format('l, d F Y') }}</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="publicationsModal" tabindex="-1" role="dialog" aria-labelledby="publicationsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="publicationsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="publicationsContent">
                <!-- Las publicaciones se agregarán aquí dinámicamente -->
            </div>
        </div>
    </div>
</div>


<script>
 function loadPublications(date) {
    $.ajax({
        url: 'http://localhost:8000/posts/publications/' + date, // Ajusta esta URL a tu controlador
        method: 'GET',
        success: function(data) {
            $('#publicationsModalLabel').text(date);
            $('#publicationsContent').html(''); // Limpiar el contenido del modal

            // Comprobar si hay publicaciones
            if (data.length === 0) {
                $('#publicationsContent').append('<p>No hay publicaciones para esta fecha.</p>');
            } else {
                data.forEach(function(publication) {
                    $('#publicationsContent').append(`
                        <div class="card mb-2">
                            <div class="row">
                                <div class="col-4">
                                    ${
                                        publication.images.length > 0
                                            ? `<img src="/storage/${publication.images[0].path}" class="img-fluid" style="width: 200px; height: 200px; object-fit: cover; border-radius: 10px;" alt="Imagen de publicación">`
                                            : `<img src="ruta/por/defecto.jpg" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;" alt="Sin imagen">` // Puedes poner una ruta de imagen por defecto
                                    }
                                </div>
                                <div class="col-8">
                                    <p>${publication.content}</p>
                                    <small>${publication.created_at}</small>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }

            $('#publicationsModal').modal('show');
        },
        error: function(xhr) {
            console.error("Error al cargar las publicaciones: ", xhr);
        }
    });
}
</script>
@endsection
