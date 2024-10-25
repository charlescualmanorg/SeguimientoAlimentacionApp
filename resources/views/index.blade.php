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
                        <h5 class="card-title">{{ \Carbon\Carbon::parse($date->date)->locale('es')->isoFormat('dddd, D MMMM YYYY') }}</h5>
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

<!-- Modal para la respuesta de la IA -->
<div class="modal fade" id="aiResponseModal" tabindex="-1" role="dialog" aria-labelledby="aiResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aiResponseModalLabel">Análisis de IA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="aiResponseContent" style="font-family: 'Courier New', Courier, monospace; font-size: 14px;">
                    <!-- La respuesta de la IA se mostrará aquí -->
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function loadPublications(date) {
    $.ajax({
        url: '/posts/publications/' + date, // Ajusta esta URL a tu controlador
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
                                            ? `<img src="/storage/${publication.images[0].path}" class="img-fluid ml-2 mt-2 mb-2" style="width: 70px; height: 60px; object-fit: cover; border-radius: 10px;" alt="Imagen de publicación">`
                                            : `<img src="ruta/por/defecto.jpg" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;" alt="Sin imagen">` // Puedes poner una ruta de imagen por defecto
                                    }
                                </div>
                                <div class="col-8">
                                    <p>${publication.content}</p>
                                    <small>${publication.created_at}</small>
                                </div>
                                <div class="col-12">
                                    <!-- Mostrar mensaje si el post aún no ha sido analizado -->
                                    ${
                                        publication.is_analyzed 
                                        ? `<button class="btn btn-link" onclick="showAIResponse(${publication.id})">Tenemos ayuda para mejorar tu alimentación</button>`
                                        : `<p><i>El análisis está en progreso...</i></p>`
                                    }
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

function showAIResponse(postId) {
    $.ajax({
        url: '/posts/' + postId + '/ai-response', // Ruta para obtener la respuesta de la IA
        method: 'GET',
        success: function(data) {
            if (data.error) {
                $('#aiResponseContent').html(`<p class="text-danger">${data.error}</p>`);
            } else {
                // Usar <pre> para respetar los saltos de línea y la sangría
                $('#aiResponseContent').html(`<pre style="white-space: pre-wrap; word-wrap: break-word;">${data.ai_response}</pre>`);
            }
            $('#aiResponseModal').modal('show');
        },
        error: function(xhr) {
            console.error("Error al cargar la respuesta de la IA: ", xhr);
        }
    });
}

</script>
@endsection
