-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 18 Juin 2017 à 16:27
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `rush003`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(6) NOT NULL,
  `nom_categorie` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`) VALUES
(1, 'E-cigarettes'),
(2, 'E-liquides'),
(3, 'Accessoires');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(6) NOT NULL,
  `montant` float NOT NULL,
  `id_membre` int(5) DEFAULT NULL,
  `date_commande` datetime NOT NULL,
  `date_estimation` datetime NOT NULL,
  `adresse` text NOT NULL,
  `cp` int(5) NOT NULL,
  `ville` varchar(60) NOT NULL,
  `statut` enum('En cours de traitement','En cours de livraison','Livré') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `montant`, `id_membre`, `date_commande`, `date_estimation`, `adresse`, `cp`, `ville`, `statut`) VALUES
(6, 60, NULL, '2015-05-24 22:51:56', '0000-00-00 00:00:00', 'rue Saint Sébastien', 75013, 'Paris ', 'En cours de traitement'),
(7, 125, 9, '2015-05-31 11:47:02', '2015-05-31 11:47:07', '253 rue de la Paix', 75013, 'Paris', 'En cours de traitement'),
(8, 75, 11, '2015-05-31 12:21:47', '2015-05-31 12:21:52', 'rue de l&#039;espoir', 33210, 'Bordeaux', 'En cours de traitement'),
(9, 39.5, 12, '2015-05-31 16:08:21', '2015-05-31 16:08:26', '85 rue de la mer', 44510, 'Le Pouliguen', 'En cours de traitement'),
(14, 39.5, 12, '2015-05-31 16:23:40', '2015-05-31 16:23:45', '85 rue de la mer', 44510, 'Le Pouliguen', 'En cours de traitement'),
(18, 39.5, 14, '2015-05-31 16:33:11', '2015-05-31 16:33:16', '56 rue des mouettes', 44238, 'Saint Brevin', 'En cours de traitement'),
(19, 94.8, 16, '2015-05-31 16:40:39', '2015-05-31 16:40:44', '85 rue des souppirs', 43526, 'Baumont', 'En cours de traitement'),
(22, 65, 13, '2015-05-31 17:15:05', '2015-05-31 17:15:10', 'Rue de la cannebi&egrave;re', 13000, 'Marseille', 'En cours de traitement'),
(23, 73, 19, '2015-05-31 19:44:51', '2015-05-31 19:44:56', '3 rue de la riviere', 50600, 'Rennes', 'En cours de traitement'),
(24, 139, 18, '2015-06-03 22:37:15', '2015-06-03 22:37:20', 'jojo123', 52600, 'Quimper', 'En cours de traitement'),
(25, 55, 2, '2017-06-17 18:47:54', '2017-06-17 18:47:59', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(26, 55, 2, '2017-06-17 18:51:40', '2017-06-17 18:51:45', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(27, 55, 2, '2017-06-17 18:54:13', '2017-06-17 18:54:18', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(28, 55, 2, '2017-06-17 18:54:47', '2017-06-17 18:54:52', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(29, 55, 2, '2017-06-17 18:55:48', '2017-06-17 18:55:53', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(30, 55, 2, '2017-06-17 18:56:15', '2017-06-17 18:56:20', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(31, 55, 2, '2017-06-17 18:57:28', '2017-06-17 18:57:33', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(32, 120, 2, '2017-06-17 19:25:22', '2017-06-17 19:25:27', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(33, 60, 2, '2017-06-17 21:13:15', '2017-06-17 21:13:20', '30 rue Saint S&eacute;bastien', 75010, 'Paris  ', 'En cours de traitement'),
(34, 452, 10002, '2017-06-17 21:22:07', '2017-06-17 21:22:12', 'wefevvdghfh', 81310, 'wegfvav', 'En cours de traitement'),
(35, 0, 1, '2017-06-18 17:42:37', '2017-06-18 17:42:42', 'rue Saint S&eacute;bastien', 75013, 'Paris ', 'En cours de traitement'),
(36, 28, 1, '2017-06-18 17:43:26', '2017-06-18 17:43:31', 'rue Saint S&eacute;bastien', 75013, 'Paris ', 'En cours de traitement');

-- --------------------------------------------------------

--
-- Structure de la table `details_categorie`
--

CREATE TABLE `details_categorie` (
  `id_details_categorie` int(6) NOT NULL,
  `id_categorie` int(6) DEFAULT NULL,
  `id_produit` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `details_categorie`
--

INSERT INTO `details_categorie` (`id_details_categorie`, `id_categorie`, `id_produit`) VALUES
(1, 1, 114),
(12, 1, 115),
(13, 3, 114),
(14, 3, 115),
(15, 1, 116),
(16, 3, 116),
(17, 3, 108),
(18, 3, 109),
(19, 3, 110),
(20, 3, 111),
(21, 3, 112),
(22, 2, 99),
(23, 2, 113),
(24, 2, 100),
(25, 2, 101),
(26, 2, 102),
(27, 2, 103),
(28, 2, 104),
(29, 2, 105),
(30, 2, 106),
(31, 2, 107),
(32, 1, 61),
(33, 1, 62),
(34, 1, 117),
(35, 1, 112),
(36, 2, NULL),
(37, 1, NULL),
(38, 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_details_commande` int(6) NOT NULL,
  `id_commande` int(6) NOT NULL,
  `id_produit` int(5) NOT NULL,
  `quantite` int(5) NOT NULL,
  `prix_promo` float NOT NULL,
  `code_promo` varchar(20) NOT NULL,
  `reduction` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `details_commande`
--

INSERT INTO `details_commande` (`id_details_commande`, `id_commande`, `id_produit`, `quantite`, `prix_promo`, `code_promo`, `reduction`) VALUES
(14, 8, 99, 1, 4, '', ''),
(15, 8, 103, 1, 0, 'PM20', '20'),
(16, 8, 105, 1, 0, '', ''),
(24, 18, 109, 1, 0, '0', '0'),
(25, 18, 102, 1, 0, 'PM10', '50'),
(26, 18, 106, 1, 0, '0', '0'),
(29, 19, 106, 1, 0, '0', '0'),
(35, 22, 107, 1, 0, '0', '0'),
(36, 22, 113, 1, 0, '0', '0'),
(38, 23, 104, 1, 0, '0', '0'),
(40, 24, 115, 1, 0, '0', '0'),
(41, 24, 116, 1, 0, '0', '0'),
(42, 24, 114, 1, 0, '0', '0'),
(43, 31, 116, 1, 0, '0', '0'),
(44, 32, 115, 1, 0, '0', '0'),
(45, 32, 116, 1, 0, '0', '0'),
(46, 32, 113, 1, 0, '0', '0'),
(47, 33, 100, 1, 0, '0', '0'),
(48, 33, 99, 8, 0, '0', '0'),
(49, 33, 110, 1, 0, '0', '0'),
(50, 34, 62, 1, 0, '0', '0'),
(51, 34, 114, 7, 0, '0', '0'),
(52, 36, 99, 7, 0, '0', '0');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(5) NOT NULL,
  `pseudo` varchar(15) NOT NULL,
  `mdp` varchar(512) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `naissance` date NOT NULL,
  `email` varchar(30) NOT NULL,
  `telephone` int(10) UNSIGNED ZEROFILL NOT NULL,
  `sexe` enum('m','f') NOT NULL,
  `ville` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `adresse` varchar(30) NOT NULL,
  `statut` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `naissance`, `email`, `telephone`, `sexe`, `ville`, `cp`, `adresse`, `statut`) VALUES
(1, 'admin', 'admin', 'Administrateur', 'Jury', '1996-03-10', 'contact@quaidesvaps.fr', 0566247586, 'f', 'Paris ', 75013, 'rue Saint Sébastien', 1),
(2, 'test', 'test', 'Le testeur', 'Monsieur', '1951-08-16', 'test@test.fr', 0612458590, 'f', 'Paris  ', 75010, '30 rue Saint Sébastien', 0),
(3, 'Loulou', 'loulou123', 'Louisa', 'Corrioux', '1972-03-12', 'louisa@gmail.com', 0632856412, 'm', 'Nice', 56222, 'Adresse de Louisa', 0),
(4, 'cloe1804', '', 'LEG', 'cloe', '1994-01-30', 'cloe.legoube@gmail.com', 0171529648, 'f', 'Meaux ', 77100, 'rue Cornillon', 0),
(6, 'Tamy', 'tania123', 'Lamy', 'Tania', '1988-02-03', 'tania@gmail.com', 0652849661, 'f', 'Parçay', 37210, '3 rue de la Loire', 0),
(9, 'sylvia', 'sylvia123', 'Masseron', 'Sylvia', '1960-04-19', 'masseron@hotmail.fr', 0237559426, 'f', 'Paris', 75013, '253 rue de la Paix', 0),
(10, 'Carla', 'carla123', 'Bruni', 'Carla', '1950-07-25', 'carla@gmail.fr', 0625894562, 'f', 'Paris', 75013, '33 rue de la bastille', 0),
(11, 'Louis', 'louis123', 'Joubert', 'Louis', '1985-04-23', 'louis@gmail.co', 0566247586, 'm', 'Bordeaux', 33210, 'rue de l&#039;espoir', 0),
(12, 'Alfred', 'alfa123', 'Manouir', 'Alfred', '1975-03-12', 'manouir@hotmail.fr', 0645128495, 'm', 'Le Pouliguen', 44510, '85 rue de la mer', 0),
(13, 'Mery', 'mery123', 'Grey', 'Meredith', '1978-05-02', 'grey@yahoo.fr', 0257489613, 'f', 'Marseille', 13000, 'Rue de la cannebi&egrave;re', 0),
(14, 'Marine', 'marine123', 'Donnard', 'Marine', '1995-06-05', 'marine.donnard@hotmail.fr', 0345896245, 'f', 'Saint Brevin', 44238, '56 rue des mouettes', 0),
(15, 'Abdel', 'abda123', 'Malik', 'Abdel', '1981-12-12', 'abdel@yahoo.fr', 0654589542, 'm', 'La plaine', 75013, '56 rue des minimes', 0),
(16, 'Mimi25', 'mimi123', 'Lefol', 'Emilie', '1986-06-18', 'emilie.lefol@gmail.com', 0247559988, 'f', 'Baumont', 43526, '85 rue des souppirs', 0),
(17, 'Emilien', 'milien123', 'Rocard', 'Emilien', '1986-06-01', 'milien@hotmail.fr', 0652345978, 'm', 'Lille', 15012, '417 rue du Nord', 0),
(18, 'JOJO', 'jojo123', 'lamalice', 'Jojo', '1994-05-24', 'jojo@yahoo.fr', 0065248956, 'm', 'Quimper', 52600, 'jojo123', 0),
(19, 'Marcel', 'marcel123', 'Pagnol', 'Marcel', '1985-05-24', 'pagnol@yahoo.fr', 0250859632, 'm', 'Rennes', 50600, '3 rue de la riviere', 0),
(20, 'Philippine', 'pde', 'Pde-Maul', 'Philou', '1988-05-05', 'phillipine@gmail.com', 0613254789, 'f', 'Asni&egrave;res sur seine', 92600, '87 avenue de la Marne', 0),
(21, 'pde', '123', 'maul', 'pde', '2075-05-20', 'pde-maul@free.fr', 0620468454, 'f', 'wegfvav', 81310, 'wefevvdghfh', 0),
(22, 'lol', 'lol', 'pde', 'pde', '8000-05-20', 'lol@juju.fr', 1234567897, 'm', 'dvsfdgf', 87645, 'sgh', 0);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(5) NOT NULL,
  `titre` varchar(60) NOT NULL,
  `photo` varchar(300) NOT NULL,
  `prix` float(8,0) NOT NULL,
  `prix_promo` float(8,0) NOT NULL,
  `categorie` varchar(100) NOT NULL,
  `stock` int(4) NOT NULL,
  `ref` int(4) NOT NULL,
  `descriptif` text NOT NULL,
  `diametre` int(4) NOT NULL,
  `matiere` varchar(60) NOT NULL,
  `hauteur` int(4) NOT NULL,
  `poids` int(4) NOT NULL,
  `contenance` int(4) NOT NULL,
  `caracteristique5` varchar(200) NOT NULL,
  `caracteristique6` varchar(200) NOT NULL,
  `caracteristique7` varchar(200) NOT NULL,
  `caracteristique8` varchar(200) NOT NULL,
  `caracteristique9` varchar(200) NOT NULL,
  `caracteristique10` varchar(200) NOT NULL,
  `caracteristique11` varchar(200) NOT NULL,
  `caracteristique12` varchar(200) NOT NULL,
  `complement` text NOT NULL,
  `id_promo` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `titre`, `photo`, `prix`, `prix_promo`, `categorie`, `stock`, `ref`, `descriptif`, `diametre`, `matiere`, `hauteur`, `poids`, `contenance`, `caracteristique5`, `caracteristique6`, `caracteristique7`, `caracteristique8`, `caracteristique9`, `caracteristique10`, `caracteristique11`, `caracteristique12`, `complement`, `id_promo`) VALUES
(61, 'EGO Premium Bleue', 'images/61-2849-ego_premium_bleu_boutique.jpg', 36, 31, 'E-cigarettes, Accessoires, ', 30, 2849, 'Le Kit e-smart Kanger est destiné aux petits et moyens vapoteurs E-cigarette raffinée, très simple à utiliser et entretenir Kit décliné en noir, blanc et rouge', 22, 'Acier inoxydable', 59, 79, 5, 'Connecteur 510 réglable', 'Contrôle du tirage (airflow) avec bagues de couleur optionnelles', 'Plateau de construction modèle S ', 'Chambre d\'atomisation: Aluminium anodisé Ematal - isolante (voir ci-dessous)', 'Tank: Verre borosilicate dépoli, interchangeable', 'Drip tip custom ', 'Joints de rechange, vis de rechange, clef Allen inclus', '', '', 5),
(62, 'EGO Premium Argenté', 'images/62-2459-EGO-Premium-argente.png', 32, 0, 'E-cigarettes', 9, 2459, 'Le Kit e-smart Kanger est destiné aux petis et moyens vapoteurs E-cigarette raffinée, très simple à utiliser et entretenir Kit décliné en noir, blanc et rouge', 22, 'Acier inoxydable', 59, 79, 5, 'Connecteur 510 réglable', 'Contrôle du tirage (airflow) avec bagues de couleur optionnelles', 'Plateau de construction modèle S ', 'Chambre d\'atomisation: Aluminium anodisé Ematal - isolante (voir ci-dessous)', 'Tank: Verre borosilicate dépoli, interchangeable', 'Drip tip custom ', 'Joints de rechange, vis de rechange, clef Allen inclus', '', 'complements d\'infos', 5),
(99, 'E-liquide - Goût Banane', 'images/-2487-Banane.jpg', 5, 4, 'E-liquides', 14, 2487, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 0, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(100, 'E-liquide - Goût Cola', 'images/-3100-Cola.jpg', 5, 0, 'E-liquides', 29, 3100, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(101, 'E-liquide - Goût Fraise', 'images/-3101-Fraise.jpg', 5, 4, 'E-liquides', 30, 3101, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(102, 'E-liquide - Goût Cappucino', 'images/-3102-Cappuccino.jpg', 5, 0, 'E-liquides', 29, 3102, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', 5),
(103, 'E-liquide - Goût Kiwi', 'images/-3103-Kiwi.jpg', 5, 0, 'E-liquides', 27, 3103, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', 2),
(104, 'E-liquide - Goût Pina Colada', 'images/-3104-Pina Colada.jpg', 5, 0, 'E-liquides', 29, 3104, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(105, 'E-liquide - Goût Pina Citron', 'images/-3105-Citron.jpg', 5, 4, 'E-liquides', 29, 3105, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(106, 'E-liquide - Goût Pomme', 'images/-3106-Pomme.jpg', 5, 2, 'E-liquides', 28, 3106, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(107, 'E-liquide - Goût Noix de coco', 'images/-3107-Noix de coco.jpg', 5, 0, 'E-liquides', 29, 3107, 'Ce produit a été fabriqué par notre partenaire Dekang.\r\n\r\nQui est Dekang ?\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste.\r\n\r\nDekang est réputé pour son sérieux et la qualité de ces e-liquides.\r\nDisposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté.\r\n\r\nDekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques).\r\n\r\nLes avantages Dekang ?\r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides.\r\nDe très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque.\r\nOn ne peut pas plaisanter avec un produit inhalés par nos clients.\r\nCombien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 6, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(108, 'Batterie Fantaisie Couleur', 'images/-3200-batterie-fantaisie-couleur.jpg', 15, 0, 'Accessoires', 19, 3200, 'Recharger votre e-cigarette grâce à cette batterie fantaisie. \r\nElle s\'adapte à tous nos modèles Premium.', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', NULL),
(109, 'Batterie Fantaisie Rouge à pois blancs', 'images/-3201-batterie-fantaisie-rouge-pois-blanc.png', 15, 0, 'Accessoires', 17, 3201, 'Recharger votre e-cigarette grâce à cette batterie fantaisie. \r\nElle s\'adapte à tous nos modèles Premium.', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', NULL),
(110, 'Tour de cou', 'images/110-3202-Tour-de-Cou.jpg', 15, 14, 'Accessoires', 18, 3202, 'Ne perdez plus votre e-cigarette et ayez là toujours à porter de main grâce à ce tour de cou.\r\nCe tour de cou s\'adapte à tous nos modèles.', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', NULL),
(111, 'Housse de rangement noir', 'images/111-3203-Housse-de-rangement-noir-Patrick-King-Crazy-Vapors.jpg', 20, 0, 'Accessoires', 19, 3203, 'Cette housse remplacera votre ancien paquet de cigarette. Vous pouvez y ranger votre e-cigarette, une batterie de rechange, le chargeur ainsi qu\'un atomisateur de rechange.', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 5),
(112, 'EGO case Rose', 'images/112-3204-EGO-Case-Rose.jpg', 22, 0, 'Accessoires', 19, 3204, 'Cette housse remplacera votre ancien paquet de cigarette. Vous pouvez y ranger votre e-cigarette, une batterie de rechange, le chargeur ainsi qu\'un atomisateur de rechange.\r\n\r\nCette housse est adaptée à nos modèles EGO.', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 5),
(113, 'E-liquide - Goût Vanille', 'images/-3108-Vanille.jpg', 5, 0, 'E-liquides', 11, 3108, 'Ce produit a été fabriqué par notre partenaire Dekang. \r\n\r\nQui est Dekang ? \r\n\r\nDekang est tout simplement le leader mondial de la fabrication de l\'e-liquide. Étant un des plus anciens acteurs du marché, il dispose d\'un savoir-faire unique et d\'un panel de saveur extraordinairement vaste. Dekang est réputé pour son sérieux et la qualité de ces e-liquides. Disposant d\'infrastructures certainement unique au monde sur ce secteur, Dekang garantit une qualité constante de fabrication et une application drastique des normes les plus exigeantes en terme de qualité et de sûreté. Dekang dispose notamment de la norme GMP, normes américaines allemandes (normes produits alimentaires pharmaceutiques). \r\n\r\nLes avantages Dekang ? \r\n\r\nSi nous avons choisi Dekang c\'est avant tout pour la qualité et la sûreté des e-liquides. De très (trop) nombreux produits vendus sur le marché actuellement proviennent d\'assembleurs dont les conditions de préparation sont totalement opaque. On ne peut pas plaisanter avec un produit inhalés par nos clients. Combien de liquide dis "Français" sont assemblés en "salle propre" respectant la norme GMP ?...', 0, '', 0, 0, 0, 'Tous nos e-liquides existent en 6 ml, 11ml,18 ml et 30ml', '', '', '', '', '', '', '', '', NULL),
(114, 'Coffret Divine Blanc', 'images/114-1520-Coffret-Divine-Blanc.jpg', 60, 42, 'E-cigarettes,Accessoires', 25, 1520, 'Coffret comprenant 2 e-cigarettes Divine couleur blanc avec leurs batteries et atomisateurs ainsi qu\'un chargeur USB ou sur secteur. Vous ne risquez plus de tomber en panne !', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', NULL),
(115, 'Coffret Divine Violet', 'images/115-1521-Coffret-Divine-Violet.jpg', 60, 42, 'E-cigarettes,Accessoires', 24, 1521, 'Coffret comprenant 2 e-cigarettes Divine couleur blanc avec leurs batteries et atomisateurs ainsi qu\'un chargeur USB ou sur secteur. Vous ne risquez plus de tomber en panne !', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', NULL),
(116, 'Coffret EGO bleue', 'images/116-1522-EGO-bleue-Coffret.jpg', 55, 0, 'E-cigarettes,Accessoires', 23, 1522, 'Coffret comprenant 2 e-cigarettes Divine couleur blanc avec leurs batteries et atomisateurs ainsi qu\'un chargeur USB ou sur secteur. Vous ne risquez plus de tomber en panne !', 0, '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 5),
(117, 'EGO premium Silver', 'images/117-1523-EGO-Premium-Silver.jpg', 36, 0, 'E-cigarettes', 45, 1523, 'Le Kit e-smart Kanger est destiné aux petits et moyens vapoteurs E-cigarette raffinée, très simple à utiliser et entretenir', 22, 'Acier inoxydable', 59, 79, 5, 'Connecteur 510 réglable', 'Contrôle du tirage (airflow) avec bagues de couleur optionnelles', 'Plateau de construction modèle S ', 'Chambre d\'atomisation: Aluminium anodisé Ematal - isolante (voir ci-dessous)', 'Tank: Verre borosilicate dépoli, interchangeable', 'Drip tip custom ', 'Joints de rechange, vis de rechange, clef Allen inclus', '', '', 5);

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `id_promo` int(2) NOT NULL,
  `code_promo` varchar(6) NOT NULL,
  `reduction` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `promotion`
--

INSERT INTO `promotion` (`id_promo`, `code_promo`, `reduction`) VALUES
(2, 'PM20', 20),
(5, 'PM10', 50);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `details_categorie`
--
ALTER TABLE `details_categorie`
  ADD PRIMARY KEY (`id_details_categorie`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_details_commande`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_commande_2` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_promo` (`id_promo`);

--
-- Index pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`id_promo`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT pour la table `details_categorie`
--
ALTER TABLE `details_categorie`
  MODIFY `id_details_categorie` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_details_commande` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10004;
--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id_promo` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `details_categorie`
--
ALTER TABLE `details_categorie`
  ADD CONSTRAINT `details_categorie_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL,
  ADD CONSTRAINT `details_categorie_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE SET NULL;

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `details_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `details_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_2` FOREIGN KEY (`id_promo`) REFERENCES `promotion` (`id_promo`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET GLOBAL SQL_MODE = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
