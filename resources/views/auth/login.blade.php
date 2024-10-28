<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Utilise Tailwind pour les styles -->
    
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
            max-width: 500px; /* Diminue la longueur maximale */
            width: 50%;
            height: 600px; /* Augmente la hauteur */
        }
        .login-image {
            background-color: #1C627B ;
            color: white;
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-image img {
            width: 70%;
        }
        .login-form {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-control {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        .form-control label {
            margin-bottom: 5px;
        }
        .form-control input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
        .btn-submit {
            background-color: #6DD5ED;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #1C627B;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Left part with image -->
        <div class="login-image">
            <img src="{{ asset('images/minibank.png') }}" alt="Logo">
        </div>

        <!-- Right part with form -->
        <div class="login-form">
            <h2 class="text-3xl font-bold mb-6">Connexion</h2>

            <!-- Formulaire de connexion -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf <!-- Protection CSRF de Laravel -->
                <div class="form-control">
                    <label for="email">E-mail ou Numéro de compte</label>
                    <input type="email" id="email" name="email" placeholder="Entrer votre e-mail ou numéro de compte" required>
                    <span class="error-message" id="error-email"></span>
                </div>

                <div class="form-control">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Entrer votre mot de passe" required>
                </div>

                <button type="submit" class="btn-submit">Se connecter</button>

                <div class="mt-4">
                    <a href="#" class="text-sm text-purple-500">Mot de passe oublié ?</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const errorEmail = document.getElementById('error-email');

        const validateEmail = () => {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isValid = regex.test(emailInput.value);
            if (!isValid) {
                errorEmail.textContent = 'Format d\'email invalide (ex: example@gmail.com).';
            } else {
                errorEmail.textContent = '';
            }
            return isValid;
        };

        emailInput.addEventListener('input', validateEmail);

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            if (!validateEmail()) {
                e.preventDefault();  // Empêche l'envoi si l'email est invalide
            }
        });
    </script>
</body>
</html>
