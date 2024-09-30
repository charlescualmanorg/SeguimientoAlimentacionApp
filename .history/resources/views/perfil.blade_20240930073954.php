@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Imagen de perfil -->
                    <div class="profile-pic-wrapper">
                        <label for="profilePicInput">
                            <div class="profile-pic-placeholder d-flex align-items-center justify-content-center" style="border-radius: 50%; width: 150px; height: 150px; background-color: #f0f0f0;">
                                @if(auth()->user()->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile Image" style="border-radius: 50%; width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span class="text-muted" style="font-size: 48px;">+</span>
                                @endif
                            </div>
                        </label>
                        <input type="file" id="profilePicInput" name="profile_image" class="d-none" accept="image/*">
                        <small class="form-text text-muted">Fotografía</small>
                    </div>

                    <!-- Formulario de perfil -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                            <div class="card-header">
                                <h5>Enfermedades Preexistentes</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary float-right" id="add-disease">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                            <div class="card-body" id="diseases-container">
                                @if(auth()->user()->diseases)
                                    @foreach(auth()->user()->diseases as $disease)
                                        <div class="form-group">
                                            <input type="text" name="diseases[]" class="form-control" value="{{ $disease }}" required>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group">
                                        <input type="text" name="diseases[]" class="form-control" placeholder="Enfermedad" required>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Actualizar Perfil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('add-disease').addEventListener('click', function() {
        const container = document.getElementById('diseases-container');
        const input = document.createElement('div');
        input.classList.add('form-group');
        input.innerHTML = '<input type="text" name="diseases[]" class="form-control" placeholder="Enfermedad" required>';
        container.appendChild(input);
    });
</script>
@endsection
