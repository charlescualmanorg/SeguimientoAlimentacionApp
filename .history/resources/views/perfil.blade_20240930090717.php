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
                                <img id="profileImagePreview" src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : '' }}" 
                                     class="rounded-circle img-thumbnail" 
                                     alt="Foto de perfil" 
                                     style="display: {{ auth()->user()->profile_image ? 'block' : 'none' }};">

                                <!-- Mensaje para cuando no hay imagen -->
                                <div id="emptyProfileImage" style="display: {{ auth()->user()->profile_image ? 'none' : 'block' }};">
                                    <div class="empty-profile-image">
                                        <span class="plus-icon">+</span>
                                        <span>Fotografía</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Input para cargar imagen -->
                            <input type="file" id="profileImageInput" name="profile_image" class="form-control-file mt-2">
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
        const profileImageInput = document.getElementById('profileImageInput');
        const profileImagePreview = document.getElementById('profileImagePreview');
        const emptyProfileImage = document.getElementById('emptyProfileImage');

        profileImageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImagePreview.src = e.target.result;
                    profileImagePreview.style.display = 'block';
                    emptyProfileImage.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                profileImagePreview.style.display = 'none';
                emptyProfileImage.style.display = 'block';
            }
        });

        // Lógica para agregar/eliminar inputs de enfermedades preexistentes
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
            const inputs = diseasesContainer.querySelectorAll('input');
            let hasEmptyInput = false;

            inputs.forEach(function (input) {
                if (input.value === '') {
                    hasEmptyInput = true;
                }
            });

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

                addRemoveEventListeners();
            }
        });

        addRemoveEventListeners();
    });
</script>
@endsection
