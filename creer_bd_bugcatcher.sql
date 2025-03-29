-- Base de données: `bug_catcher`
CREATE DATABASE IF NOT EXISTS `bug_catcher` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bug_catcher`;

-- Structure de la table `utilisateurs`
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(145) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `statuts`
CREATE TABLE IF NOT EXISTS `statuts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statut` varchar(50) NOT NULL,
  `couleur` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `statut` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `bogues`
CREATE TABLE IF NOT EXISTS `bogues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `statut_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `statut_id` (`statut_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `bogues_ibfk_1` FOREIGN KEY (`statut_id`) REFERENCES `statuts` (`id`),
  CONSTRAINT `bogues_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données pour la table `statuts`
INSERT INTO `statuts` (`statut`, `couleur`) VALUES
('Nouveau', 'primary'),
('En cours', 'warning'),
('Résolu', 'success'),
('Fermé', 'secondary'),
('Rejeté', 'danger');