<?php
/**
 * connexion.php - Connexion au système
 * Ce fichier permet de se connecter au système avec un compte.
 * @author Alexandru Ciuca
 * @date 2025-03-29
 */
session_start();
require_once '../../lib/requetes.php';
require_once '../../lib/fonctions.php';

       

// A FAIRE: Vérifier si l'utilisateur est déjà connecté
// Si oui, le rediriger vers la page d'accueil
if (est_connecte()) {
  header("Location: " . BASE_URL . "index.php");
  exit();
}
// Initialiser les variables
$email = '';
$erreurs = [];

// A FAIRE: Traitement du formulaire de connexion
// 1. Vérifier si le formulaire a été soumis (méthode POST)
// 2. Récupérer et nettoyer les données du formulaire (email, mot de passe) 
//    => créer un fonction pour obtenir les paramètres
// 3. Valider les champs (vérifier qu'ils ne sont pas vides)
//    => créer une fonction pour valider les champs
// Créer une fonction pour authentifier l'utilisateur:
//  4. Si validation OK, vérifier les identifiants dans la base de données
//  5. Si identifiants valides, créer la session utilisateur et rediriger
//  6. Sinon, afficher un message d'erreur

//1.
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  //2.
  $email = obtenir_parametre('email');
  $mdp = obtenir_parametre('mot_de_passe');
  //3.
  $erreurs = valider_champ_obligatoires($_POST, ['email', 'mot_de_passe']);
  
  if(empty($erreurs)){
    $utilisateur = trouver_utilisateur_par_email($email);

    if($utilisateur && password_verify($mdp, $utilisateur['mot_de_passe'])){

      $_SESSION['utilisateur'] = [
        'id'=> $utilisateur['id'],
        'email' => $utilisateur['email']
      ];
      session_regenerate_id(true);
 
      $_SESSION['succes'] = "Connexion réussie. Bienvenue !";
      unset($_SESSION['erreur']);
      header("Location: " . BASE_URL . "/index.php");
      exit();
    }else{
      $erreurs['general'] = "Email ou mot de passe incorrect";
      $_SESSION['erreur'] = "Identifiants incorrects";
    }
  } else{
    $erreurs['general'] = "Email ou mot de passe incorrect";
    $_SESSION['erreur'] = "Identifiants incorrects";
  }
}

include '../partials/_en_tete.php';
?>
<!-- Faut-il des required input tags? -->
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card shadow">
      <div class="card-header">
        <h5>Connexion</h5>
      </div>
      <div class="card-body">

      <?php if (isset($_SESSION['succes'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['succes']; unset($_SESSION['succes']); ?></div>
        <?php endif; ?>

        <?php if (isset($erreurs['general'])): ?>
            <div class="alert alert-danger"><?php echo $erreurs['general']; ?></div>
        <?php endif; ?>

        <form method="post" action="">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control <?php echo isset($erreurs['email']) ? 'is-invalid' : ''; ?>" 
                   id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <!-- A FAIRE: Afficher les erreurs de validation pour l'email -->
            <?php if (isset($erreurs['email'])): ?>
                <div class="invalid-feedback"><?php echo $erreurs['email'];?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label for="mot-passe" class="form-label">Mot de passe</label>
            <input type="password" class="form-control <?php echo isset($erreurs['mot_de_passe']) ? 'is-invalid' : ''; ?>" 
                   id="mot-passe" name="mot_de_passe" >

            <!-- A FAIRE: Afficher les erreurs de validation pour le mot de passe -->
            <?php if (isset($erreurs['mot_de_passe'])): ?>
                <div class="invalid-feedback"><?php echo $erreurs['mot_de_passe']; ?></div>
            <?php endif; ?>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Connexion</button>
          </div>
        </form>

        <div class="mt-4 text-center">
          <p>Vous n'avez pas de compte? <a href="<?php echo BASE_URL; ?>/views/enregistrement/inscription.php">Inscrivez-vous ici</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../partials/_pied_page.php'; ?>