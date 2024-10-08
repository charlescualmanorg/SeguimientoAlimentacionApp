@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Formulario de perfil -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Imagen de perfil -->
                        <div class="profile-pic-wrapper">
                            <label for="profilePicInput">
                                <div class="profile-pic-placeholder d-flex align-items-center justify-content-center" style="border-radius: 50%; width: 150px; height: 150px; background-color: #f0f0f0;">
                                    <img id="profilePicPreview" 
                                         src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : '#' }}" 
                                         alt="Profile Image" 
                                         style="border-radius: 50%; width: 100%; height: 100%; object-fit: cover; {{ auth()->user()->profile_image ? '' : 'display: none;' }}">
                                    
                                    @if(!auth()->user()->profile_image)
                                        <span class="text-muted" style="font-size: 48px;" id="addPhotoPlaceholder">+</span>
                                    @endif
                                </div>
                            </label>
                            <input type="file" id="profilePicInput" name="profile_image" class="d-none" accept="image/*">
                            <small class="form-text text-muted">Fotografía</small>
                        </div>

                        <!-- Información personal -->
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="surname">Apellidos</label>
                            <input type="text" name="surname" class="form-control" value="{{ old('surname', auth()->user()->surname) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="username">Usuario</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', auth()->user()->username) }}" required>
                            @if($errors->has('username'))
                                <div class="text-danger">{{ $errors->first('username') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="age">Edad</label>
                            <input type="number" name="age" class="form-control" value="{{ old('age', auth()->user()->age) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="weight">Peso (kg)</label>
                            <input type="number" name="weight" step="0.1" class="form-control" value="{{ old('weight', auth()->user()->weight) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="height">Altura (cm)</label>
                            <input type="number" name="height" step="0.1" class="form-control" value="{{ old('height', auth()->user()->height) }}" required>
                        </div>

                        <!-- Sección de salud -->
                        <div class="card mt-4">
                            <div class="card-header">Enfermedades Preexistentes</div>
                            <div class="card-body">
                                <div id="diseases-container">
                                    @if(old('diseases', json_decode(auth()->user()->diseases, true)))
                                        @foreach(old('diseases', json_decode(auth()->user()->diseases, true)) as $disease)
                                            <div class="input-group mb-3">
                                                <input type="text" name="diseases[]" value="{{ $disease }}" class="form-control" placeholder="Nombre de la enfermedad">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger remove-disease-btn">&times;</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button id="add-disease-btn" type="button" class="btn btn-primary mt-2">
                                    + Agregar Enfermedad
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Actualizar Perfil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profilePicInput = document.getElementById('profilePicInput');
        const profilePicPreview = document.getElementById('profilePicPreview');
        const addPhotoPlaceholder = document.getElementById('addPhotoPlaceholder');
        
        profilePicInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profilePicPreview.src = e.target.result;
                    profilePicPreview.style.display = 'block';
                    addPhotoPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        const addDiseaseBtn = document.getElementById('add-disease-btn');
        const diseasesContainer = document.getElementById('diseases-container');

        function addRemoveEventListeners() {
            const removeButtons = document.querySelectorAll('.remove-disease-btn');
            removeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    button.closest('.input-group').remove();
                });
            });
        }

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
                inputWrapper.classList.add('input-group', 'mb-3');

                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'diseases[]';
                newInput.placeholder = 'Nombre de la enfermedad';
                newInput.classList.add('form-control');

                const removeBtnWrapper = document.createElement('div');
                removeBtnWrapper.classList.add('input-group-append');

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.classList.add('btn', 'btn-danger', 'remove-disease-btn');
                removeBtn.innerHTML = '&times;';

                removeBtnWrapper.appendChild(removeBtn);
                inputWrapper.appendChild(newInput);
                inputWrapper.appendChild(removeBtnWrapper);

                diseasesContainer.appendChild(inputWrapper);

                // Re-agregar los eventos de eliminación
                addRemoveEventListeners();
            }
        });

        // Inicializar eventos de eliminación para los inputs existentes
        addRemoveEventListeners();
    });
</script>
@endsection
