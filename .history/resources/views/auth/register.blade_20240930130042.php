@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <!-- Alert for global errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>
                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required>
                                @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="age" class="col-md-4 col-form-label text-md-right">{{ __('Age') }}</label>
                            <div class="col-md-6">
                                <input id="age" type="number" class="form-control @error('age') is-invalid @enderror" name="age" value="{{ old('age') }}" required min="0">
                                @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="weight" class="col-md-4 col-form-label text-md-right">{{ __('Weight (kg)') }}</label>
                            <div class="col-md-6">
                                <input id="weight" type="number" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ old('weight') }}" required min="0" step="0.1">
                                @error('weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="height" class="col-md-4 col-form-label text-md-right">{{ __('Height (cm)') }}</label>
                            <div class="col-md-6">
                                <input id="height" type="number" class="form-control @error('height') is-invalid @enderror" name="height" value="{{ old('height') }}" required min="0" step="0.1">
                                @error('height')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Imagen de perfil -->
                        <div class="profile-pic-wrapper">
                            <label for="profilePicInput">
                                <div class="profile-pic-placeholder d-flex align-items-center justify-content-center" style="border-radius: 50%; width: 150px; height: 150px; background-color: #f0f0f0;">
                                    <img id="profilePicPreview" 
                                         src="#" 
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

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
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
