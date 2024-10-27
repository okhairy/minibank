<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        max-width: 1200px;
        width: 100%;
        height: auto;
    }

    .left {
        background-color: #1C627B;
        color: white;
        width: 40%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .left img {
        width: 70%;
    }

    .right {
        width: 60%;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-control {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }

    .form-row {
        display: flex;
        justify-content: space-between;
    }

    .form-control label {
        font-weight: 500;
        margin-bottom: 5px;
    }

    .required-label::after {
        content: ' *';
        color: red;
    }

    .form-control input,
    .form-control select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
    }

    .form-column {
        flex: 0 0 48%;
    }

    .error-message {
        color: red;
        font-size: 12px;
    }

    .btn-submit {
        background-color: #6DD5ED;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        margin-top: 20px;
        width: 30%;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }

    .btn-submit:hover {
        background-color: #1C627B;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <img src="{{ asset('images/minibank.png') }}" alt="Logo">
        </div>
        <div class="right">
            <h2 class="text-3xl font-bold mb-6">Inscription</h2>
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
                @csrf
                <!-- Ajout du token CSRF -->

                <!-- Nom et Prénom -->
                <div class="form-row">
                    <div class="form-control form-column">
                        <label for="nom" class="required-label">Nom</label>
                        <input id="nom" type="text" name="nom" required autofocus>
                        <span class="error-message" id="error-nom"></span>
                    </div>
                    <div class="form-control form-column">
                        <label for="prenom" class="required-label">Prénom</label>
                        <input id="prenom" type="text" name="prenom" required>
                        <span class="error-message" id="error-prenom"></span>
                    </div>
                </div>

                <!-- Mot de passe et Téléphone -->
                <div class="form-row">
                    <div class="form-control form-column">
                        <label for="mot_de_passe" class="required-label">Mot de passe</label>
                        <input id="mot_de_passe" type="password" name="password" required>
                        <span class="error-message" id="error-password"></span>
                    </div>
                    <div class="form-control form-column">
                        <label for="telephone" class="required-label">Téléphone</label>
                        <input id="telephone" type="text" name="telephone" required>
                        @if ($errors->has('telephone'))
                        <span class="error-message">{{ $errors->first('telephone') }}</span>
                        @endif
                    </div>
                </div>

                <!-- CIN et Email -->
                <div class="form-row">
                    <div class="form-control form-column">
                        <label for="cin" class="required-label">CIN</label>
                        <input id="cin" type="text" name="cin" required>
                        <span class="error-message" id="error-cin"></span>
                    </div>
                    <div class="form-control form-column">
                        <label for="email" class="required-label">Email</label>
                        <input id="email" type="email" name="email" required>
                        <span class="error-message" id="error-email"></span>
                    </div>
                </div>

                <!-- Date de naissance et Adresse -->
                <div class="form-row">
                    <div class="form-control form-column">
                        <label for="date_naissance" class="required-label">Date de naissance</label>
                        <input id="date_naissance" type="date" name="date_naissance" required>
                        <span class="error-message" id="error-date-naissance"></span>
                    </div>
                    <div class="form-control form-column">
                        <label for="adresse" class="required-label">Adresse</label>
                        <input id="adresse" type="text" name="adresse" required>
                    </div>
                </div>

                <!-- Rôle et Photo -->
                <div class="form-row">
                    <div class="form-control form-column">
                        <label for="role" class="required-label">Rôle</label>
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Sélectionnez un rôle</option>
                            <!-- Option vide par défaut -->
                            <option value="Distributeur">Distributeur</option>
                            <option value="Client">Client</option>
                        </select>
                    </div>
                    <div class="form-control form-column">
                        <label for="photo">Photo</label>
                        <input id="photo" type="file" name="photo">
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="btn-submit">S'inscrire</button>
            </form>
        </div>
    </div>
    <script>
    const form = document.getElementById('registrationForm');
    const telephoneInput = document.getElementById('telephone');
    const cinInput = document.getElementById('cin');
    const emailInput = document.getElementById('email');
    const dateNaissanceInput = document.getElementById('date_naissance');

    // Fonction pour supprimer les espaces
    const removeSpaces = (input) => {
        return input.replace(/\s+/g, '');
    };

    const validateTelephone = () => {
        const sanitizedValue = removeSpaces(telephoneInput.value);
        telephoneInput.value = sanitizedValue;

        if (sanitizedValue.length > 9) {
            telephoneInput.value = sanitizedValue.slice(0, 9);
        }

        const regex = /^(77|78|75|76)\d{7}$/;
        const isValid = regex.test(telephoneInput.value);
        const errorMessage = document.getElementById('error-telephone');
        if (!isValid) {
            errorMessage.textContent = 'Le téléphone doit commencer par 77, 78, 75 ou 76 et contenir 9 chiffres.';
        } else {
            errorMessage.textContent = '';
        }
        return isValid;
    };

    const validateCIN = () => {
        const sanitizedValue = removeSpaces(cinInput.value);
        cinInput.value = sanitizedValue;

        if (sanitizedValue.length > 17) {
            cinInput.value = sanitizedValue.slice(0, 17);
        }

        const regex = /^\d{1,17}$/;
        const isValid = regex.test(cinInput.value);
        const errorMessage = document.getElementById('error-cin');
        if (!isValid) {
            errorMessage.textContent = 'Le CIN doit contenir uniquement des chiffres (maximum 17 chiffres).';
        } else {
            errorMessage.textContent = '';
        }
        return isValid;
    };

    const validateDateNaissance = () => {
        const today = new Date();
        const birthDate = new Date(dateNaissanceInput.value);
        const age = today.getFullYear() - birthDate.getFullYear();
        const errorMessage = document.getElementById('error-date-naissance');

        // Vérifier si l'année de naissance est supérieure à l'année actuelle
        if (birthDate > today) {
            errorMessage.textContent = 'Lannée de naissance ne peut pas suupérieur la lannee actuelle.';
            return false;
        }

        // Vérifier si l'utilisateur a moins de 18 ans
        if (age < 18 || (age === 18 && today < new Date(today.getFullYear(), birthDate.getMonth(), birthDate
                .getDate()))) {
            errorMessage.textContent = 'Vous devez avoir au moins 18 ans.';
            return false;
        } else {
            errorMessage.textContent = '';
            return true;
        }
    };

    const validateEmail = () => {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex pour l'email
        const errorMessage = document.getElementById('error-email');

        if (!regex.test(emailInput.value)) {
            errorMessage.textContent = 'Email invalide. Veuillez entrer un email valide example@gmail.com';
            return false;
        } else {
            errorMessage.textContent = '';
            return true;
        }
    };

    // Écouter les changements de saisie
    telephoneInput.addEventListener('input', validateTelephone);
    cinInput.addEventListener('input', validateCIN);
    dateNaissanceInput.addEventListener('change', validateDateNaissance);
    emailInput.addEventListener('input', validateEmail);

    form.addEventListener('submit', (e) => {
        const isTelephoneValid = validateTelephone();
        const isCINValid = validateCIN();
        const isDateNaissanceValid = validateDateNaissance();
        const isEmailValid = validateEmail();

        // Si une validation échoue, empêcher la soumission du formulaire
        if (!(isTelephoneValid && isCINValid && isDateNaissanceValid && isEmailValid)) {
            e.preventDefault();
        }
    });

    const checkPhoneNumber = async () => {
    const response = await fetch('/check-phone', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ telephone: telephoneInput.value })
    });
    const data = await response.json();
    return data.exists; // Si existe, retourner true
};

// Exemple d'utilisation lors de la soumission
form.addEventListener('submit', async (e) => {
    const phoneExists = await checkPhoneNumber();
    if (phoneExists) {
        document.getElementById('error-telephone').textContent = 'Ce numéro de téléphone est déjà utilisé.';
        e.preventDefault(); // Empêche la soumission du formulaire
    }
});

    </script>
</body>

</html>