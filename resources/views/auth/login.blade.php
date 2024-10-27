<!DOCTYPE html>
<html lang="fr"> <!-- Déclaration du type de document et langue -->
<head>
    <meta charset="UTF-8"> <!-- Définition du jeu de caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Réglage de la vue pour les appareils mobiles -->
    <title>Connexion</title> <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <script src="https://cdn.tailwindcss.com"></script> <!-- Inclusion de Tailwind CSS pour le style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Inclusion de Font Awesome pour les icônes -->
    <style>
        /* Styles CSS pour personnaliser l'apparence de la page */
        body {
            font-family: 'Poppins', sans-serif; /* Définir la police utilisée */
            background-color: #ffffff; /* Couleur de fond de la page */
            display: flex; /* Utilisation du flexbox pour centrer le contenu */
            justify-content: center; /* Centrer horizontalement */
            align-items: center; /* Centrer verticalement */
            height: 100vh; /* Hauteur de la page sur toute la hauteur de la fenêtre */
            margin: 0; /* Supprimer les marges par défaut du body */
        }
        .container {
            background-color: white; /* Couleur de fond du conteneur */
            border-radius: 15px; /* Arrondi des coins du conteneur */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); /* Ombre portée pour un effet de profondeur */
            overflow: hidden; /* Cacher les débordements */
            display: flex; /* Utilisation du flexbox pour aligner l'image et le formulaire */
            max-width: 500px; /* Largeur maximale du conteneur */
            width: 50%; /* Largeur du conteneur par rapport à la fenêtre */
            height: 600px; /* Hauteur fixe du conteneur */
        }
        .login-image {
            background-color: #1C627B; /* Couleur de fond de la partie image */
            color: white; /* Couleur du texte dans cette section */
            width: 50%; /* Largeur de la section image */
            display: flex; /* Utilisation du flexbox pour centrer l'image */
            justify-content: center; /* Centrer horizontalement */
            align-items: center; /* Centrer verticalement */
        }
        .login-image img {
            width: 70%; /* Largeur de l'image par rapport à son conteneur */
        }
        .login-form {
            width: 50%; /* Largeur de la section formulaire */
            padding: 50px; /* Espacement interne du formulaire */
            display: flex; /* Utilisation du flexbox pour organiser les éléments du formulaire */
            flex-direction: column; /* Disposition verticale des éléments du formulaire */
            justify-content: center; /* Centrer verticalement le contenu du formulaire */
        }
        .form-control {
            margin-bottom: 20px; /* Espacement entre les champs du formulaire */
            display: flex; /* Utilisation du flexbox pour aligner le label et l'input */
            flex-direction: column; /* Disposition verticale du label et de l'input */
            position: relative; /* Position relative pour positionner l'icône d'œil */
        }
        .form-control label {
            margin-bottom: 5px; /* Espacement sous le label */
        }
        .form-control input {
            padding: 10px; /* Espacement interne du champ de saisie */
            padding-right: 40px; /* Espacement à droite pour l'icône d'œil */
            border: 1px solid #ddd; /* Bordure grise claire */
            border-radius: 5px; /* Arrondi des coins des champs de saisie */
        }
        .error-message {
            color: red; /* Couleur des messages d'erreur */
            font-size: 12px; /* Taille de la police des messages d'erreur */
            margin-top: 5px; /* Espacement au-dessus des messages d'erreur */
        }
        .btn-submit {
            background-color: #6DD5ED; /* Couleur de fond du bouton */
            color: white; /* Couleur du texte du bouton */
            padding: 10px; /* Espacement interne du bouton */
            border: none; /* Suppression de la bordure par défaut */
            border-radius: 5px; /* Arrondi des coins du bouton */
            cursor: pointer; /* Changer le curseur au survol */
        }
        .btn-submit:hover {
            background-color: #1C627B; /* Couleur du bouton au survol */
        }
        .toggle-password {
            position: absolute; /* Positionnement absolu pour placer l'icône par rapport au champ */
            right: 10px; /* Distance à droite du champ de saisie */
            top: 70%; /* Position verticale ajustée pour espacer l'icône de l'input */
            transform: translateY(-50%); /* Centrer l'icône verticalement */
            cursor: pointer; /* Changer le curseur au survol */
            color: #888; /* Couleur de l'icône d'œil */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Partie gauche avec l'image -->
        <div class="login-image">
            <img src="{{ asset('images/minibank.png') }}" alt="Logo"> <!-- Image du logo -->
        </div>

        <!-- Partie droite avec le formulaire -->
        <div class="login-form">
            <h2 class="text-3xl font-bold mb-6">Connexion</h2> <!-- Titre du formulaire -->

            <!-- Formulaire de connexion -->
            <form method="POST" action="{{ route('login') }}"> <!-- Début du formulaire avec méthode POST -->
                @csrf <!-- Protection CSRF pour sécuriser le formulaire -->
                <div class="form-control">
                    <label for="email">E-mail ou Numéro de compte</label> <!-- Label pour l'input d'email -->
                    <input type="email" id="email" name="email" placeholder="Entrer votre e-mail ou numéro de compte" required value="{{ old('email') }}"> <!-- Champ de saisie pour l'email -->
                    <span class="error-message" id="error-email"></span> <!-- Message d'erreur pour l'email -->
                    @error('email') <!-- Affichage d'erreur si l'email n'est pas valide -->
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="password">Mot de passe</label> <!-- Label pour l'input de mot de passe -->
                    <input type="password" id="password" name="password" placeholder="Entrer votre mot de passe" required> <!-- Champ de saisie pour le mot de passe -->
                    <i class="fas fa-eye toggle-password" id="toggle-password"></i> <!-- Icône d'œil pour masquer/démasquer le mot de passe -->
                    @error('password') <!-- Affichage d'erreur si le mot de passe n'est pas valide -->
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Message d'erreur général pour l'authentification -->
                @if (session('login_error')) <!-- Vérification si une erreur de connexion est présente dans la session -->
                    <div class="error-message">{{ session('login_error') }}</div> <!-- Affichage du message d'erreur -->
                @endif

                <button type="submit" class="btn-submit">Se connecter</button> <!-- Bouton de soumission du formulaire -->

                <div class="mt-4">
                    <a href="#" class="text-sm text-purple-500">Mot de passe oublié ?</a> <!-- Lien pour récupérer le mot de passe -->
                </div>
            </form>
        </div>
    </div>

    <script>
        // Vérification en temps réel du format de l'email
        const emailInput = document.getElementById('email'); // Récupération de l'élément input d'email
        const errorEmail = document.getElementById('error-email'); // Récupération de l'élément pour le message d'erreur

        emailInput.addEventListener('input', function () { // Événement déclenché lors de la saisie dans le champ email
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expression régulière pour valider le format de l'email
            if (!regex.test(emailInput.value)) { // Vérification du format de l'email
                emailInput.classList.add('border-red-500'); // Ajout d'une classe pour souligner l'input en rouge
                errorEmail.textContent = "Veuillez entrer un e-mail valide (ex : example@gmail.com)."; // Affichage du message d'erreur
            } else {
                emailInput.classList.remove('border-red-500'); // Retrait de la classe d'erreur si le format est valide
                errorEmail.textContent = ""; // Effacement du message d'erreur
            }
        });

        // Fonctionnalité de masquer/démasquer le mot de passe
        const togglePassword = document.getElementById('toggle-password'); // Récupération de l'icône pour masquer/démasquer le mot de passe
        togglePassword.addEventListener('click', function () { // Événement au clic sur l'icône
            const passwordInput = document.getElementById('password'); // Récupération de l'input de mot de passe
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password'; // Changement du type de l'input
            passwordInput.setAttribute('type', type); // Mise à jour du type de l'input
            this.classList.toggle('fa-eye-slash'); // Changement de l'icône entre œil et œil barré
        });
    </script>
</body>
</html>
