@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Perfil de Usuario</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Imagen de perfil -->
                        <div class="text-center mb-3">
                            <div class="profile-image-circle">
                                @if (auth()->user()->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Foto de perfil" class="rounded-circle img-thumbnail">
                                @else
                                    <div class="empty-profile-image">
                                        <span class="plus-icon">+</span>
                                        <span>Fotografía</span>
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="profile_image" class="form-control-file mt-2">
                        </div>

                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>

                        <!-- Apellidos -->
                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', auth()->user()->apellidos) }}" required>
                        </div>

                        <!-- Usuario -->
                        <div class="form-group">
                            <label for="usuario">Usuario</label>
                            <input type="text" name="usuario" class="form-control" value="{{ old('usuario', auth()->user()->usuario) }}" required>
                        </div>

                        <!-- Edad -->
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" name="edad" class="form-control" value="{{ old('edad', auth()->user()->edad) }}" required>
                        </div>

                        <!-- Peso -->
                        <div class="form-group">
                            <label for="peso">Peso (kg)</label>
                            <input type="number" name="peso" step="0.1" class="form-control" value="{{ old('peso', auth()->user()->peso) }}" required>
                        </div>

                        <!-- Altura -->
                        <div class="form-group">
                            <label for="altura">Altura (cm)</label>
                            <input type="number" name="altura" step="0.1" class="form-control" value="{{ old('altura', auth()->user()->altura) }}" required>
                        </div>

                        <!-- Enfermedades Preexistentes -->
                        <div class="card">
                            <div class="card-header">Enfermedades Preexistentes</div>
                            <div class="card-body">
                                <div id="diseases-container">
                                    @if(old('diseases', json_decode(auth()->user()->diseases, true)))
                                        @foreach(old('diseases', json_decode(auth()->user()->diseases, true)) as $disease)
                                            <div class="mb-3">
                                                <input type="text" name="diseases[]" value="{{ $disease }}" class="form-control" placeholder="Nombre de la enfermedad">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button id="add-disease-btn" type="button" class="btn btn-primary mt-2">
                                    + Agregar Enfermedad
                                </button>
                            </div>
                        </div>

                        <!-- Botón para guardar -->
                        <button type="submit" class="btn btn-success mt-3">Guardar Perfil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addDiseaseBtn = document.getElementById('add-disease-btn');
        const diseasesContainer = document.getElementById('diseases-container');

        addDiseaseBtn.addEventListener('click', function () {
            // Verificar si hay algún input vacío
            const inputs = diseasesContainer.querySelectorAll('input');
            let hasEmptyInput = false;

            inputs.forEach(function (input) {
                if (input.value === '') {
                    hasEmptyInput = true;
                }
            });

            // Si no hay inputs vacíos, agregar uno nuevo
            if (!hasEmptyInput) {
                const inputWrapper = document.createElement('div');
                inputWrapper.classList.add('mb-3');

                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'diseases[]';
                newInput.placeholder = 'Nombre de la enfermedad';
                newInput.classList.add('form-control');

                inputWrapper.appendChild(newInput);
                diseasesContainer.appendChild(inputWrapper);
            }
        });
    });
</script>
@endsection
