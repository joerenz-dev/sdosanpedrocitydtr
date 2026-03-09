-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 07:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dtr_wfh`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(50) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `functional_division` enum('OSDS','SGOD','CID','') NOT NULL,
  `position` varchar(255) NOT NULL,
  `id_picture` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `date_hired` date DEFAULT NULL,
  `Flexi` enum('Full','Fixed7am','Fixed8am','Fixed9am','Full6am','Full8am','Full2pm','Full10pm') DEFAULT NULL,
  `immediate_sup` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_number`, `employee_name`, `functional_division`, `position`, `id_picture`, `birth_date`, `date_hired`, `Flexi`, `immediate_sup`) VALUES
(14, '4569779', 'LORINA B. JURADA', 'OSDS', '', '4569779_LORINA_B__JURADA.png', '1981-10-05', '2022-07-20', 'Full', NULL),
(17, '6458804', 'MICHAEL ANGELO S. MORESCA', 'OSDS', '', '6458804_MICHAEL_ANGELO_S__MORESCA.png', '1991-03-28', '2022-09-12', 'Fixed8am', NULL),
(18, '6458802', 'FLOYD CHRISTOPHER A. GRIJALDO', 'OSDS', '', '6458802_FLOYD_CHRISTOPHER_A__GRIJALDO.png', '1987-02-20', '2022-09-12', 'Fixed8am', NULL),
(19, '6459300', 'AIKO A. TORIANO', 'OSDS', '', '6459300_AIKO_A__TORIANO.png', '1990-08-03', '2022-10-18', 'Fixed8am', NULL),
(20, '6458809', 'MARY KRIS ANNE B. CAJANO', 'OSDS', '', '6458809_MARY_KRIS_ANNE_B__CAJANO.png', '1984-12-24', '2022-09-12', 'Fixed8am', NULL),
(22, '6466198', 'ARCEL F. SOPEÑA', 'OSDS', '', '6466198_ARCEL_F__SOPE__A.png', '1997-08-19', '2024-03-04', 'Fixed8am', NULL),
(24, '1000002', 'LEEROYD S. BELLEN', 'OSDS', '', '1000002_LEEROYD_S__BELLEN.png', '2002-11-17', '2025-02-24', 'Fixed8am', NULL),
(25, '6519582', 'EMELLOU S. ORIBIA', 'OSDS', '', '1000003_EMELLOU_S__ORIBIA.png', '2002-09-23', '2025-03-24', 'Fixed8am', NULL),
(26, '6519579', 'ABIGAIL A. OLIVENZA', 'OSDS', '', '1000004_ABIGAIL_A__OLIVENZA.png', '1999-06-26', '2025-10-07', 'Fixed8am', NULL),
(33, '092193', 'ELJOHN BELETA', 'OSDS', '', '092193_ELJOHN_BELETA.png', '1993-05-26', '2025-03-17', 'Fixed8am', NULL),
(34, '060701', 'ROGELIO SAPITULA JR', 'OSDS', '', '060701_JR_SAPITULA.png', '2001-05-26', '2024-05-15', 'Fixed8am', NULL),
(36, '6459308', 'LYKA JANE LEOSALA', 'OSDS', '', '6459308_LYKA_JANE_LEOSALA.png', '1988-06-07', '2022-10-10', 'Full', NULL),
(37, '6404524', 'PAUL JEREMY I. AGUJA', 'OSDS', '', '6404524_Paul_Jeremy_I_Aguja.png', '1992-12-13', '2020-12-17', 'Full', NULL),
(42, '5909780', 'ANNRA T. ELEN', 'OSDS', '', '5909780_ANNRA_T__ELEN.png', '1985-06-03', '2022-07-26', 'Full', NULL),
(43, '6465358', 'EDWIN JOSEPH B. DE PERALTA', 'OSDS', '', '6465358_EDWIN_JOSEPH_B__DE_PERALTA.png', '1982-06-05', '2024-02-06', 'Fixed8am', NULL),
(44, '1000005', 'JUN JUN DC. MARIÑAS', 'OSDS', '', '1000005_JUN_JUN_DC__MARI__AS.png', '1997-06-30', '2021-11-11', 'Fixed8am', NULL),
(45, '1000006', 'MICAH JOY O. ALAO', 'OSDS', '', '1000006_MICAH_JOY_O__ALAO.png', '1998-06-11', '2024-03-13', 'Fixed8am', NULL),
(46, '1000007', 'BERNADETTE DELLORO', 'OSDS', '', '1000007_BERNADETTE_DELLORO.png', '1987-01-20', '2024-07-16', 'Fixed8am', NULL),
(49, '6404953', 'NERIE VIEN C. ECHON', 'OSDS', '', '6404953_NERIE_VIEN_C__ECHON.png', '1998-05-18', '2022-09-16', 'Fixed8am', NULL),
(50, '0005794', 'CAROLINA B. ALCANTARA', 'OSDS', '', '0005794_CAROLINA_B_ALCANTARA.png', '1965-01-21', '2022-07-20', 'Full', NULL),
(54, '6397830', 'MICHELLE G. RENDORA', 'OSDS', '', '6397830_MICHELLE_G_RENDORA.png', '1986-08-06', '2022-09-16', 'Full8am', NULL),
(59, '5485802', 'CHRISTOPER E. COMIA', 'OSDS', '', '5485802_CHRISTOPER_E_COMIA.png', '1977-07-25', '2022-07-20', 'Full', NULL),
(62, '6459299', 'DANIEL M. DAITOL', 'OSDS', '', '6459299_DANIEL_M_DAITOL.png', '1992-01-15', '2022-09-16', 'Fixed7am', NULL),
(65, '4360172', 'MA. CRISTINA N. PACIA', 'OSDS', '', '4360172_MA_CRISTINA_N_PACIA.png', '1978-11-06', '2022-07-20', 'Full', NULL),
(68, '6459305', 'KRISTINE KATE R. AGUILAR', 'OSDS', '', '6459305_KRISTINE_KATE_R_AGUILAR.png', '1997-12-12', '2022-10-17', 'Fixed8am', NULL),
(70, '6313204', 'ZAIDA A. ABRIGO', 'OSDS', '', '6313204_ZAIDA_A_ABRIGO.png', '1963-08-23', '2022-09-16', 'Full', NULL),
(72, '6459301', 'LOIDA GRACE B. ALARCON', 'OSDS', '', '6459301_LOIDA_GRACE_B_ALARCON.png', '1965-01-18', '2022-10-17', 'Fixed8am', NULL),
(74, '1000011', 'MARY JACQUELINE J. DOROL', 'OSDS', '', '1000011_MARY_JACQUELINE_J_DOROL.png', '1983-05-05', '2021-12-09', 'Fixed8am', NULL),
(76, '6458826', 'SHEILA MAE D. LAUDE', 'OSDS', '', '6458826_SHEILA_MAE_D_LAUDE.png', '1990-05-15', '2022-09-05', 'Full', NULL),
(78, '6458806', 'GIZELLE E. CABREJAS', 'OSDS', '', '6458806_GIZELLE_E_CABREJAS.png', '1999-05-14', '2023-09-14', 'Fixed8am', NULL),
(81, '6468178', 'BABY HAZEL ANN D. PERFAS', 'OSDS', '', '6468178_HAZEL_PERFAS.png', '1997-02-25', '2024-08-09', 'Fixed8am', NULL),
(82, '6472858', 'ADRIAN GERALD JAMES O. VICENCIO', 'CID', '', '6472858_Adrian_vicencio.png', '1996-05-12', '2025-01-14', 'Fixed8am', NULL),
(83, '1000009', 'LARRY M. BONOAN', 'OSDS', '', '1000009_LARRY_BONOAN.png', NULL, NULL, 'Fixed8am', NULL),
(84, '6471110', 'CRISTAL O. JOSUE', 'OSDS', '', '6471110_CRISTAL _JOSUE.png', '2001-10-29', '2024-10-01', 'Fixed8am', NULL),
(85, '6463758', 'PIETRO PAOLO S. PAGANA', 'OSDS', '', '6463758_petro_paulo_pagana.png', NULL, NULL, 'Fixed8am', NULL),
(86, '1000010', 'MARIA DANICA B. GOMEZ', 'OSDS', '', '1000010_MARIA_DANICA.png', NULL, NULL, 'Fixed8am', NULL),
(87, '6466229', 'FERNAN JOSEPH C. ALFONSO', 'OSDS', '', '6466229_fernan_joseph_alfonso.png', '1985-07-29', '2024-03-04', 'Fixed8am', NULL),
(88, '6467340', 'MA. THERESA D. MARQUEZ', 'OSDS', '', '6467340_matheresa_marquez.png', '1990-08-19', '2024-01-01', 'Fixed8am', NULL),
(89, '6471111', 'ARLENE R. ANGELES', 'OSDS', '', '6471111_ARLENE_R_ANGELES.jpg', '1994-09-08', '2024-10-01', 'Fixed8am', NULL),
(91, '6459306', 'MARVIN O. DEFANTE', 'OSDS', '', '6459306_MARVIN_O_DEFANTE.png', '1977-09-09', '2002-10-04', 'Fixed7am', NULL),
(94, '4645589', 'FREDERICK G. BYRD JR.', 'SGOD', '', '4645589_FREDERICK_G_BYRD_JR.png', '1970-09-18', '2022-07-20', 'Full', NULL),
(96, '4130587', 'VIVIAN L. PETRASANTA', 'SGOD', '', '4130587_VIVIAN_L_PETRASANTA.png', '1969-11-18', '2022-09-01', 'Full', NULL),
(98, '6313312', 'JEROME Q. JASA', 'SGOD', '', '6313312_JEROME_Q_JASA.png', '1986-04-12', '2022-09-01', 'Full', NULL),
(100, '4569771', 'EMMANUELLE M. BARRAGO', 'SGOD', '', '4569771_EMMANUELLE_M_BARRAGO.png', '1985-01-01', '2022-09-01', 'Full', NULL),
(102, '6317759', 'JENNETH J. LAREÑA', 'SGOD', '', '6317759_JENNETH_J_LAREA.png', '1973-01-03', '2022-09-01', 'Full', NULL),
(104, '6317826', 'ARMIE JOYCE P. ATERRADO', 'SGOD', '', '6317826_ARMIE_JOYCE_P_ATERRADO.png', '1992-11-21', '2022-09-01', 'Full', NULL),
(107, '4252033', 'NICANOR A. LIMJUICO', 'SGOD', '', '4252033_NICANOR_A_LIMJUICO.png', '1964-08-08', '2022-09-01', 'Full', NULL),
(111, '4460121', 'LAURENCE E. PARTO', 'SGOD', '', '4460121_LAURENCE_E_PARTO.png', '1986-09-02', '2024-04-18', 'Full', NULL),
(113, '4569589', 'MARYROSE S. AGUILAR', 'SGOD', '', '4569589_MARYROSE_S_AGUILAR.png', '1976-02-22', '2022-09-01', 'Fixed7am', NULL),
(115, '6460131', 'SHERWIN P. SANTOS', 'SGOD', '', '6460131_SHERWIN_P_SANTOS.png', '1991-09-26', '2022-10-17', 'Full', NULL),
(117, '6317489', 'ORIMAR M. GUAB-DAGANDAN', 'SGOD', '', '6317489_ORIMAR_M_GUAB-DAGANDAN.png', '1987-03-01', '2022-12-02', 'Full', NULL),
(119, '6458797', 'JECEL B. VILLANUEVA', 'SGOD', '', '6458797_JECEL_B_VILLANUEVA.png', '1990-01-17', '2022-09-01', 'Full', NULL),
(121, '5017335', 'ABIGAIL HAZEL M. JAVIER', 'SGOD', '', '5017335_ABIGAIL_HAZEL_M_JAVIER.png', '1984-06-27', '2022-08-09', 'Full', NULL),
(125, '6458800', 'JAMIE LEE V. ASEOCHE', 'SGOD', '', '6458800_JAMIE_LEE_V_ASEOCHE.png', '1990-03-07', '2022-09-01', 'Full', NULL),
(127, '6458799', 'MA. SOFIA P. HONDUNA', 'SGOD', '', '6458799_MA_SOFIA_P_HONDUNA.png', '1995-11-08', '2022-09-01', 'Full', NULL),
(129, '6458798', 'KAYZLE LYNNE T. MORALES', 'SGOD', '', '6458798_KAYZLE_LYNNE_T_MORALES.png', '1988-09-16', '2022-09-01', 'Full', NULL),
(131, '6458807', 'RUPERT F. BARICUATRO', 'SGOD', '', '6458807_RUPERT_F_BARICUATRO.png', '1972-10-05', '2022-09-02', 'Full', NULL),
(133, '6461345', 'JENINA R. AMBAYEC', 'SGOD', '', '6461345_JENINA_R_AMBAYEC.png', '1985-09-25', '2023-03-13', 'Full', NULL),
(136, '1000015', 'FELISSA MAE S. GARCIA', 'SGOD', '', '1000015_FELISSA_MAE_S_GARCIA.png', '1987-06-10', '2021-10-18', 'Fixed8am', NULL),
(138, '1000016', 'ANA MARIE M. MERCADO', 'SGOD', '', '1000016_ANA_MARIE_MERCADO.png', '1977-05-26', '2024-02-29', 'Fixed8am', NULL),
(139, '1000017', 'SHIENA PEARL M. MANALO', 'SGOD', '', '1000017_SHIENA_PEARL_MANALO.png', NULL, NULL, 'Fixed8am', NULL),
(140, '4369177', 'ERMA S. VALENZUELA', 'CID', '', '4369177_ERMA_S_VALENZUELA.png', '1966-09-16', '2022-07-20', 'Full', NULL),
(142, '4246363', 'LAILYN C. VINZON', 'CID', '', '4246363_LAILYN_C_VINZON.png', '1976-06-15', '2022-09-01', 'Full', NULL),
(144, '0006248', 'ERNESTO C. CABERTE JR.', 'CID', '', '0006248_ERNESTO_C_CABERTE_JR.png', '1979-12-22', '2022-10-18', 'Full', NULL),
(147, '4265330', 'MARIA BELYNDA L. LALLABBAN', 'CID', '', '4265330_MARIA_BELYNDA_L_LALLABBAN.png', '1980-09-16', '2022-09-01', 'Full', NULL),
(149, '4364184', 'ASHER H. PASCO', 'CID', '', '4364184_ASHER_H_PASCO.png', '1984-08-08', '2022-09-01', 'Full', NULL),
(151, '6313346', 'MARITES R. MARTINEZ', 'CID', '', '6313346_MARITES_R_MARTINEZ.png', '1962-05-16', '2022-09-01', 'Full', NULL),
(153, '4645662', 'CHARINA C. SUYAO', 'CID', '', '4645662_CHARINA_C_SUYAO.png', '1968-09-19', '2022-09-01', 'Full', NULL),
(155, '4167298', 'CRISPENIANA P. BAUYON', 'CID', '', '4167298_CRISPENIANA_P_BAUYON.png', '1978-02-12', '2022-10-12', 'Full', NULL),
(157, '4239981', 'NIDA C. SANTOS', 'CID', '', '4239981_NIDA_C_SANTOS.png', '1977-10-22', '2023-02-01', 'Full', NULL),
(159, '6519578', 'ASHNIE HAYA A. PAPANDAYAN', 'OSDS', '', '1000018_ASHNIE_HAYA_A_PAPANDAYAN.png', '2001-11-16', '2024-11-18', 'Fixed8am', NULL),
(161, '4267890', 'REGINAL G. GRAFIL', 'CID', '', '4267890_REGINAL_GRAFIL.png', NULL, NULL, 'Full', NULL),
(162, '4459552', 'RAINIEL VICTOR M. CRISOLOGO', 'CID', '', '4459552_RAINIEL_VICTOR_M_CRISOLOGO.png', '1988-02-24', '2022-09-30', 'Full', NULL),
(164, '4245736', 'RENANTE R. SORIANO', 'CID', '', '4245736_RENANTE_R_SORIANO.png', '1977-09-13', '2022-09-12', 'Full', NULL),
(166, '4360263', 'JOHN DANIEL P. TEC', 'CID', '', '4360263_JOHN_DANIEL_P_TEC.png', '1978-10-27', '2022-09-12', 'Full', NULL),
(168, '6519577', 'DOVAN MOISES L. PECHAYCO', 'OSDS', '', '1000008_DOVAN_PECHAYCO.png', '1998-05-22', '2024-10-01', 'Fixed8am', NULL),
(169, '4184290', 'LUCIA F. TOLENTINO', 'CID', '', '4184290_LUCIA_F_TOLENTINO.png', '1976-09-16', '2022-09-12', 'Full', NULL),
(171, '6382031', 'SHIRLEY J. BRITOS', 'CID', '', '6382031_SHIRLEY_J_BRITOS.png', '1976-06-22', '2022-09-12', 'Full', NULL),
(173, '4560318', 'MERLINA J. PLACINO', 'CID', '', '4560318_MERLINA_J_PLACINO.png', '1978-11-24', '2022-09-16', 'Full', NULL),
(175, '4428856', 'VENANCIO T. MIRASOL', 'CID', '', '4428856_VENANCIO_T_MIRASOL.png', '1972-03-16', '2022-09-14', 'Full', NULL),
(177, '4253275', 'EMMA E. CARRILLO', 'CID', '', '4253275_EMMA_E_CARRILLO.png', '1980-10-04', '2022-12-16', 'Full', NULL),
(181, '4177257', 'REGINA N. RAMIREZ', 'CID', '', '4177257_REGINA_N_RAMIREZ.png', '1962-05-16', '2022-09-01', 'Full', NULL),
(183, '4423948', 'ROSALIE M. MABALE', 'CID', '', '4423948_ROSALI_MABALE.png', NULL, NULL, 'Full', NULL),
(184, '4401488', 'EMELINDA O. AMIL', 'CID', '', '4401488_emelinda_amil.png', '1962-10-05', '2025-03-11', 'Full', NULL),
(185, '4648268', 'APRIL CLAIRE  P. MANLANGIT - BANAAG', 'CID', '', '4648268_april_claire_manlangit_banaag.png', '1989-04-29', '2023-07-04', 'Full', NULL),
(186, '4650404', 'ROWENA JUNE B. MIRONDO', 'CID', '', '4650404_ROWENA_MIRONDO.png', NULL, NULL, 'Full', NULL),
(188, '6459296', 'CARL ANTHONY B. ALORA', 'CID', '', '6459296_CARL_ANTHONY_B_ALORA.png', '1990-12-10', '2022-10-06', 'Full', NULL),
(190, '5499944', 'RICKY P. TORRENUEVA', 'CID', '', '5499944_RICKY_P_TORRENUEVA.png', '1990-02-17', '2022-09-13', 'Full', NULL),
(192, '6465299', 'MARY GRACE O. SORIANO', 'CID', '', NULL, NULL, NULL, 'Full', NULL),
(193, '1000029', 'MARY GLECER D. DELFIN', 'CID', '', '1000029_MARY_GLECER_D_DELFIN.png', '1983-09-30', '2021-10-18', 'Full', NULL),
(195, '1000030', 'BRIANNE B. BASILAN', 'CID', '', '1000030_BRIANNE_BASILAN.png', '1995-02-13', '2025-03-17', 'Fixed8am', NULL),
(196, '1000031', 'WILLIAM C. TEMPROSA JR.', 'OSDS', '', '1000031_WILLIAM_TEMPROSA.png', NULL, NULL, 'Full', NULL),
(197, '1000019', 'ROWENA P. IRINCO', 'OSDS', '', '1000019_ROWENA_IRINCO.png', NULL, NULL, 'Fixed8am', NULL),
(198, '1000020', 'EVANGELINE F. CARDAÑO', 'OSDS', '', '1000020_EVANGELINE_F__CARDA__O.png', '1963-12-08', '2014-01-01', 'Full6am', NULL),
(199, '1000021', 'JEFFREY D. PENTECOSTE', 'OSDS', '', '1000021_JEFFREY_PENTECOSTE.png', NULL, NULL, 'Full', NULL),
(200, '1000022', 'ABEL A. MANALO', 'OSDS', '', '1000022_ABEL_A_MANALO.png', '1995-06-26', '2017-05-16', 'Full2pm', NULL),
(202, '1000023', 'JAIME O. AMIL JR.', 'OSDS', '', '1000023_JAIME_O_AMIL_JR.png', '1993-08-04', '2015-02-11', 'Full2pm', NULL),
(206, '1000025', 'CHRISTIAN JAMES C. REMOQUILLO', 'OSDS', '', '1000025_CHRISTIAN_REMOQUILLO.png', '2005-01-01', '2024-09-02', 'Fixed8am', NULL),
(207, '1000026', 'JOHN EDWARD I. PENTECOSTE', 'OSDS', '', '1000026_JOHN_EDWARD_PENTECOSTE.png', NULL, NULL, 'Full', NULL),
(208, '1000027', 'MARIANO L. ABIAD', 'OSDS', '', '1000010_MARIANO_ABIAD.png', NULL, NULL, 'Full', NULL),
(209, '1000028', 'CARLYLE POLIQUIT', 'OSDS', '', '1000028_CARYLE_POLIQUIT.png', '1991-05-26', '2025-03-24', 'Fixed8am', NULL),
(210, '6472855', 'MARLON P. METRAN', 'OSDS', '', '6472855_MARLON_P__METRAN.png', '1988-02-19', '2025-01-06', 'Fixed8am', NULL),
(212, '1000034', 'CLAUDINE S. TAPIA', 'CID', '', '1000034_CLAUDINE_TAPIA.png', NULL, NULL, 'Fixed8am', NULL),
(213, '1000035', 'CARLO MAGO', 'OSDS', '', '1000035_carlo_mago.png', NULL, NULL, 'Fixed8am', NULL),
(214, '6464868', 'JAYPEE Q. JASA', 'OSDS', '', '6464868_JAYPEE_JASA.png', NULL, NULL, 'Fixed8am', NULL),
(215, '6459605', 'JESSA M. FIEDALAN', 'OSDS', '', '6459605_jessa_fiedalan.png', NULL, NULL, 'Fixed8am', NULL),
(216, '12345', 'Test Access', 'OSDS', '', '12345_Test_Access.png', '2025-03-17', '2025-07-01', 'Full2pm', NULL),
(217, '1000032', 'Rose Jean Lozande', 'SGOD', '', '1000032_ROSE_JEAN_LOZANDE.png', NULL, NULL, 'Fixed8am', NULL),
(218, '6475363', 'CHARLYN JOY N. VALDERAMA', 'OSDS', '', '6475363_charlynjoy_valderrama.png', '1998-04-22', '2025-07-01', 'Fixed8am', NULL),
(221, '1000037', 'MARVIN R. AUSTRIA', 'OSDS', '', '1000037_marvin_austria.png', NULL, NULL, 'Fixed8am', NULL),
(226, '1000038', 'MARIA KLARIES R. MACALINAO', 'OSDS', '', '1000038_mk_macalinao.png', '2002-11-01', '2025-07-28', 'Fixed8am', NULL),
(230, '6519567', 'MARY VIANNEY R. ZAPA', 'SGOD', '', '6519567_mary_vianney_zapa.png', '1996-09-13', '2025-07-29', 'Full', NULL),
(231, '1000039', 'ROLANDO YABUT JAGONASE JR.', 'OSDS', '', '1000039_rolando_jagonase.png', NULL, NULL, 'Fixed8am', NULL),
(232, '1000040', 'KAREN B MACALOS', 'OSDS', '', '1000040_karen_macalos.png', '1986-11-12', '2025-09-01', 'Fixed8am', NULL),
(234, '6519576', 'CHRISTOPHER JOHN C. TABRILLA', 'OSDS', '', '6519576_christopher_john_tabrilla.png', '1991-12-04', '2025-09-16', 'Fixed8am', NULL),
(235, '1000043', 'Princess Leanna Brazas', 'SGOD', '', '1000043_princess_leanna_brazas.png', NULL, NULL, 'Fixed8am', NULL),
(236, '4537187', 'JOE BREN L. CONSUELO', 'OSDS', '', '4537187_JOE_BREN_L__CONSUELO.png', NULL, '2025-09-29', 'Full', NULL),
(237, '6519580', 'URAYJAN M. BORLAZA', 'CID', '', '6519580_urayjan_borlaza.png', NULL, NULL, 'Fixed8am', NULL),
(238, '6404956', 'SARAH LYNNE G. DELA CRUZ', 'OSDS', '', NULL, NULL, NULL, 'Full', NULL),
(239, '6519581', 'PAMELA M. OLIVENZA', 'OSDS', '', '6519581_Pamela_Olivenza.png', NULL, NULL, 'Fixed8am', NULL),
(240, '4176916', 'ENCARNACION T. ESCUADRO', 'SGOD', '', NULL, NULL, NULL, 'Fixed8am', NULL),
(241, '1000042', 'CEDRICK V. BACARESAS', 'OSDS', '', '1000042_Cedrick_Bacaresas.png', NULL, NULL, 'Fixed8am', NULL),
(242, '1000046', 'REDGINE A. PINEDES', 'OSDS', '', '1000046_Redgine_Pinedes.png', NULL, NULL, 'Fixed8am', NULL),
(243, '1000044', 'ALGEN D. LOVERES', 'OSDS', '', '1000044_Algen_Loveres.png', NULL, NULL, 'Fixed8am', NULL),
(244, '1000045', 'ALEXANDER  JOERENZ E. ESCALLENTE', 'OSDS', '', '1000045_Alexander_escallente.png', NULL, NULL, 'Fixed8am', NULL),
(245, '1000047', 'ELAINE JULIA FRANCISCO', 'OSDS', '', NULL, NULL, NULL, 'Full', NULL),
(246, '1000048', 'JOHN DANNRILL CRUZ', 'OSDS', '', NULL, NULL, NULL, 'Fixed8am', NULL),
(247, '1000049', 'SHAIRA MAE REYES LIMOSINERO', 'OSDS', '', NULL, NULL, NULL, 'Fixed8am', NULL),
(254, '6457105', 'JOHN CARLO A. BITUIN', 'SGOD', '', NULL, NULL, NULL, 'Fixed8am', NULL),
(255, '1000050', 'ALEXIA JASNEL S. PEDERNAL', 'SGOD', '', NULL, NULL, NULL, 'Fixed8am', NULL),
(256, '1000051', 'MEL APRYLL A. RAMOS', 'SGOD', '', NULL, NULL, NULL, 'Fixed8am', NULL),
(257, '4128196', 'EDITA R. FAJARDO', 'CID', '', NULL, NULL, NULL, 'Full', NULL),
(258, '1000052', 'HIPOLITO M. FERNANDEZ JR.', 'SGOD', '', NULL, NULL, NULL, 'Fixed8am', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `idlar_uploaded`
--

CREATE TABLE `idlar_uploaded` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(50) NOT NULL,
  `emp_name` varchar(255) NOT NULL,
  `func_div_office` varchar(255) NOT NULL,
  `idlar_file` varchar(255) NOT NULL,
  `date_uploaded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idlar_uploaded`
--

INSERT INTO `idlar_uploaded` (`id`, `employee_number`, `emp_name`, `func_div_office`, `idlar_file`, `date_uploaded`) VALUES
(1, '092193', '', '', 'Sample PDF 1.pdf', '2026-03-09 03:16:32'),
(2, '092193', '', '', 'Sample PDF 1.pdf', '2026-03-09 03:18:53'),
(3, '060701', '', '', 'Sample PDF 2.pdf', '2026-03-09 03:23:18'),
(4, '092193', 'ELJOHN BELETA', 'OSDS', 'Sample PDF 3.pdf', '2026-03-09 03:27:22'),
(5, '060701', 'ROGELIO SAPITULA JR', 'OSDS', 'new BG.jpg', '2026-03-09 04:03:41'),
(6, '092193', 'ELJOHN BELETA', 'OSDS', 'new BG.jpg', '2026-03-09 05:38:53'),
(7, '092193', 'ELJOHN BELETA', 'OSDS', 'new BG.jpg', '2026-03-09 05:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `time_logs`
--

CREATE TABLE `time_logs` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(50) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `date_record` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `lunch_out` time DEFAULT NULL,
  `lunch_in` time DEFAULT NULL,
  `undertime` varchar(5) DEFAULT NULL,
  `total_undertime` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_logs`
--

INSERT INTO `time_logs` (`id`, `employee_number`, `employee_name`, `date_record`, `time_in`, `time_out`, `status`, `lunch_out`, `lunch_in`, `undertime`, `total_undertime`) VALUES
(1, '092193', 'ELJOHN BELETA', '2026-03-09', '10:06:06', NULL, 'Late', '12:56:14', '13:22:27', NULL, NULL),
(2, '060701', 'ROGELIO SAPITULA JR', '2026-03-09', '10:09:18', NULL, 'Late', '13:22:53', NULL, NULL, NULL),
(3, '12345', 'Test Access', '2026-03-09', '10:26:35', NULL, 'On Time', NULL, NULL, NULL, NULL),
(4, '6458826', 'SHEILA MAE D. LAUDE', '2026-03-09', '10:28:50', NULL, 'On Time', NULL, NULL, NULL, NULL),
(5, '6458806', 'GIZELLE E. CABREJAS', '2026-03-09', '10:28:55', NULL, 'Late', NULL, NULL, NULL, NULL),
(6, '6459308', 'LYKA JANE LEOSALA', '2026-03-09', '10:28:59', NULL, 'On Time', NULL, NULL, NULL, NULL),
(7, '1000042', 'CEDRICK V. BACARESAS', '2026-03-09', '10:29:03', NULL, 'Late', NULL, NULL, NULL, NULL),
(8, '1000045', 'ALEXANDER  JOERENZ E. ESCALLENTE', '2026-03-09', '10:29:32', NULL, 'Late', NULL, NULL, NULL, NULL),
(9, '1000046', 'REDGINE A. PINEDES', '2026-03-09', '10:29:38', NULL, 'Late', NULL, NULL, NULL, NULL),
(10, '1000051', 'MEL APRYLL A. RAMOS', '2026-03-09', '10:29:46', NULL, 'Late', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `idlar_uploaded`
--
ALTER TABLE `idlar_uploaded`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_number` (`employee_number`,`date_record`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `idlar_uploaded`
--
ALTER TABLE `idlar_uploaded`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `time_logs`
--
ALTER TABLE `time_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
