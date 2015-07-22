-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 22, 2015 at 03:59 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ln`
--

-- --------------------------------------------------------

--
-- Table structure for table `cln_additional_item_numbers`
--

CREATE TABLE IF NOT EXISTS `cln_additional_item_numbers` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`item_id`,`item_number`),
  UNIQUE KEY `item_number` (`item_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_app_config`
--

CREATE TABLE IF NOT EXISTS `cln_app_config` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cln_app_config`
--

INSERT INTO `cln_app_config` (`key`, `value`) VALUES
('additional_payment_types', ''),
('always_show_item_grid', '0'),
('auto_focus_on_item_after_sale_and_receiving', '0'),
('automatically_email_receipt', '0'),
('automatically_show_comments_on_receipt', '0'),
('averaging_method', 'moving_average'),
('barcode_price_include_tax', '0'),
('calculate_average_cost_price_from_receivings', '0'),
('change_sale_date_when_completing_suspended_sale', '0'),
('change_sale_date_when_suspending', '0'),
('commission_default_rate', '0'),
('company', 'LOAN & PAWN'),
('company_logo', '1'),
('currency_symbol', '$'),
('customers_store_accounts', '0'),
('date_format', 'little_endian'),
('default_payment_type', 'Cash'),
('default_sales_person', 'logged_in_employee'),
('default_tax_1_name', 'Sales Tax'),
('default_tax_1_rate', ''),
('default_tax_2_cumulative', '0'),
('default_tax_2_name', 'Sales Tax 2'),
('default_tax_2_rate', ''),
('default_tax_3_name', ''),
('default_tax_3_rate', ''),
('default_tax_4_name', ''),
('default_tax_4_rate', ''),
('default_tax_5_name', ''),
('default_tax_5_rate', ''),
('default_tax_rate', '8'),
('disable_confirmation_sale', '0'),
('disable_giftcard_detection', '0'),
('disable_sale_notifications', '0'),
('disable_subtraction_of_giftcard_amount_from_sales', '0'),
('group_all_taxes_on_receipt', '0'),
('hide_barcode_on_sales_and_recv_receipt', '0'),
('hide_customer_recent_sales', '0'),
('hide_dashboard_statistics', '0'),
('hide_layaways_sales_in_reports', '0'),
('hide_signature', '0'),
('hide_store_account_payments_from_report_totals', '0'),
('hide_store_account_payments_in_reports', '0'),
('id_to_show_on_sale_interface', 'number'),
('language', 'khmer'),
('legacy_detailed_report_export', '0'),
('number_of_items_per_page', '20'),
('prices_include_tax', '0'),
('print_after_receiving', '0'),
('print_after_sale', '0'),
('receipt_text_size', 'small'),
('require_customer_for_sale', '0'),
('return_policy', 'Change return policy'),
('round_cash_on_sales', '0'),
('round_tier_prices_to_2_decimals', '0'),
('sale_prefix', 'CLN'),
('select_sales_person_during_sale', '0'),
('show_receipt_after_suspending_sale', '0'),
('spreadsheet_format', 'XLSX'),
('time_format', '12_hour'),
('track_cash', '0'),
('version', '14.4'),
('website', '');

-- --------------------------------------------------------

--
-- Table structure for table `cln_app_files`
--

CREATE TABLE IF NOT EXISTS `cln_app_files` (
  `file_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_data` longblob NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cln_app_files`
--

INSERT INTO `cln_app_files` (`file_id`, `file_name`, `file_data`) VALUES
(1, 'loan-white.png', 0x89504e470d0a1a0a0000000d49484452000000aa000000290806000000a552b2f4000009c349444154789ced9d7f885dc515c73fdf4b5896250d216cc31282c41882581b52b1921609414af18f52d3682b68ab28d6d452296db5adf40f912241442cd85453a1129296d24a9b58881445aca8a5c14aa81245134925f8ab318410d334dd9c6fff98b9ebf4e6bdddb7fbde9ab7be7ce1b1f7cdbd73e6dc7bbf73e6cc99396f610011119b6d3b225c23224efb5e96e7efa722e2fd8878cef6ddb6d746c4bca9dab33d141197d9de14117f8d88f76d9f720768a1e35d1fc5333a8b3e80edcdc58b3f8da8adca9aa48d885311f1aeed876d9f63bb553b44c4f288d81611ff6aca9b8a9c8df6ead30349d429adc1c71535b1241db77d5212b6b18da476d70f491ab65d0195a4c5b66f92b41cf826f046a3daf9921eb1bda6965910fa3870b22e6b71bed60f60d8f6703bdd06010349d40619ee94f4cb82b86dc99a312ce942e04adbeb252d01d6d97ec0f657251dcf6d2c00b6003549df0476007f04f6da3ed16cab3e6e7618db7748fa715d3e881848a2367042d2d1695c7f14782a229e95b413d86a7b4cd23ae04bb67f0754b6bf26694dae7300b81e78beaaaaf1e92a68fb247cd8890611d59956e04ca0b654dd0ca355559db4fd24701f306e7b04b836ff1d01be010c9186f7bb8167254d9ba4250695a430a044add1ed8bafaa2a6cff816465012e95b40018052ec96587811d92a29bb606d93f85011ffa7bf1e2251d20917151fe8cd91e95349c2f7953d2a11eb4d3ad88398d81b4a8e5a4a55b644bf95e51342669acf8fe4ed78df07f518a5e889b7318488b5aceb66d0f4f5d6372d83e56c81e218593eaa2632d2b4d0fc31d44233ed61848a29266fa0048fa44b7c24aff3393a9ea31a1e6e776b0fd412f05cf150ce4d00fec2f2cde8ad968a0c733f415b54c49cd458581c04012d5f68b92c633992ecac1f96ee44d1c37435fdd12d6f618707e967902d8db95c0398a8124aaa47dc0cb00b6970157e565d199ca9b382e89d9039256b6af01eac9d96edb6f7525748e6220894a0a273d089ccc6bf7f7d8febaedd199082b97367be59b664b7a93a43b490b07276c3f081ce94903730c0339999214b67f035c61fb7252807e33b0c7f60bb65f97740438d1a1c8b176d653d252db1b3a94330c2c003e65fb626095a411db21e931493b0675d63fd0b0bdd4f6ef9bdbeb2643ab3dab8df31b22e2e62905758e53b6b73a59d881c5a00efd00483a086c046e05f63437a7b88dbfd9eeb8907b5a59abeb5a9515e78e022f02b700df95d4938583b98ab3e34886ed25c06ae042dbe74a5a48768d5cac6435b7e4d5c73041d0fb6ccf97b4d12da201e5b52d8ec7812392f6db7e59d29e76048d8885390a706210dc81397587b657008b8155e4d1c0f6f6aaaaa6b34d6f32f990fcc4c5a41d5013e5cd997d2bc266bc4522dc35c050d36a4eb6413a9785a4978103d9e2b7d3750d70bbedbbaaaafac7f4ee74eea1ef891a110b80cb801b24adb23d2269d476bdfa735e3741f08840d222db6b255d41b2aaf36d0fb51bc2271bda256d240dd9afd85e549f9f6a6f4161b1c3f6a1ec86ec2245275e6beebeca44fd13290ab0117866a6db08236208b847d25ae010f083dc59cea213d81ecb1389ff4c926fb4bccb362eb6fd84edffdaed93fc9ac74d14f536d85e6cfbfda6bc56c7adf2a21a3afc3322ae6aa1f79aba0ddb6fdbbecef68ca2384e692e3b6b591171c9d4b53e5af46d78caf642dbbf96b4aeb09efbf2e77071e98c377dd85e4f0a4b2d292ce231d26ea813b6c79b43b55bc44b7367995f9f6ff8b34724ed6ad699ccafccf52f045602e748da1211f3aaaafa6dd16629678c94f6f239a7b4958f5dacb52f899a2dc34d39bda3cae4b90b780c78679aa923a7210ff7cb817b812524bf70afed6dc033b68f483a0944d667a26e33a729ffdd0aac2daf2d887850d2b5d3d5d1f632db974bba0f5824e9a7b69f9234b1a5b0d12186819b6d2fb2fd23928fdb695b4d9dcfa213380df92fe5a1e88388f88ebb58e26c2222869c92f14ee521f62fb6cfe942dee38517b0c1f6e29cbb6fdb2fcd54aeed7911717f449c72724d6eab9f83ed35451bffcee9dbb50e7fb3fdf969e83f6c7b677639fa72e8efd738ea0ae0826cb95e95f4ab6e53394ae48dcd1b48f77fd8f61da42cd199ca9bb0b29e64c23403b9e392b6e5a17c9eed2b482b5713ed663c4fcaa6adaded25c0ce8858df89df5a6ea4a93ffd867e25eaaada2f95f48c720a72af60fb02b25f2a699fa4ddddbe9ce64b2ec9db255e23fbe1922e70b1d3ab903f2ee921d26f0bbc99cb4681876d7fcbf6d0640d947af6bab3f50a7de9a392424eb5ffb5bfd7c225ad2e5ec60b330debd428fcc456692ea311f1c346fb13d7d6df9bc70da257f9dc22494b29ac7f21eb6455558fd9de27690b29d170949425fb19dbb74b2a27a1a7e9d3d4a59fd0af449d5fbcacf7a6be7cdaf864f142deed5658bb207e3e3706dc5392b9beb649d012adc89fd16ae82f2df9de88b81ed824690369e7d575c052db73363edaaf437ff9e07be69b16182edae87487545bd47e60d3bf9b2c1c55ba08add0ae6e31996aab4f55556f282de13e44babf79c01781ad117169b36ef9bd1fad29f4af459dd590498b58675790b4a090753497d5f77058d2a3d394d72c5a677be57474cd13b05b6dff1dd8448ab55e043c6efb7bb6b72bed15e838be7b26d1b7449d4d5f692a6b361d38adea8c657d8314372d3bc15b4acbaaddb4b14dd2ca19d6dd0e1c041e00564a9a0fdc6ffb5cdbf7968b03fd6a4da18f877e98dddeddab97627b35b03013f3401990eff50cba94d5caa76d85aaaac6253d097c05783aeb345fd26dc0234ecbbd6de5f60bfa9aa8b3d5c39b13952ee48c48ba55e9677c02d8e522c71f7a66b52764b523e8544bb25555bd0a5c0dfc3cafba0d392d21ff59d2a5a5bc7e246bdf0efd307bbdbb1b92e63a43249f6f2369e1801cfad9964930d14e2f3a5b3364d53c57e83529aaaa3a9417378edafe366924582d698bf36eb1b371d419203fb0ab6d7fba39019a2c76d98ee0b65f011e2deb009fb57d63875676c4f60249e7d9be0c5856440eee97b4bb59bf579d6d2a399db6a3b46fe22711f1baa44da47cafe5bdf4db67037d4dd4fcd0ae2a574d26f3cd4ab2b621cb0e60478388eb812f7712f49654f9c35f9baeadcf1149f7023f6ba17b2f7de196df674a2c49db493f28fc3029aba16a77dffd80be262a74f6824a0bdb7cd84debdb824013cfa09d256c5a6cdbe3a414903da4959f5de590dfac375b68ea38cdbae311b11bb852d266db5f2077c07e445f12d5f613928e4df6f05b05a95b5ddfb0c62f91d2449e9334d4ae4eb3bc207b00476cef97f43cf0aa5a2cbfda3e0efc82e42abc3dc5ed7682c7f9f01703dfa8752a311382555505b0cff6d5c01dc0f7a13fc354fdd97d6619b6e79543dd542fb9b0a8a1b4a3e923d2b43d226204582a691e70ccf6c1aaaa66bc8a97e5dd08dc00dc5255d5ee1ea97a1667d17b38fdefac65675a8f26fe07761c8d5a796ce0820000000049454e44ae426082);

-- --------------------------------------------------------

--
-- Table structure for table `cln_customers`
--

CREATE TABLE IF NOT EXISTS `cln_customers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `credit_limit` decimal(23,10) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `cc_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_preview` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `tier_id` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`),
  KEY `cc_token` (`cc_token`),
  KEY `cln_customers_ibfk_2` (`tier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `cln_customers`
--

INSERT INTO `cln_customers` (`id`, `person_id`, `account_number`, `company_name`, `balance`, `credit_limit`, `taxable`, `cc_token`, `cc_preview`, `card_issuer`, `tier_id`, `deleted`) VALUES
(1, 22, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(2, 23, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(3, 24, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(4, 25, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(5, 26, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(6, 27, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(7, 28, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(8, 29, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(9, 30, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(10, 31, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(11, 32, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(12, 33, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(13, 34, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(14, 35, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(15, 36, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(16, 37, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(17, 38, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(18, 39, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(19, 40, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(20, 41, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0),
(21, 42, NULL, '', '0.0000000000', NULL, 0, NULL, NULL, '', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_employees`
--

CREATE TABLE IF NOT EXISTS `cln_employees` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `cln_employees`
--

INSERT INTO `cln_employees` (`id`, `username`, `password`, `person_id`, `language`, `commission_percent`, `deleted`) VALUES
(1, 'vannakpanha.mao', '25d55ad283aa400af464c76d713c07ad', 1, 'khmer', '0.0000000000', 0),
(2, 'hong.va', '25d55ad283aa400af464c76d713c07ad', 2, 'khmer', '0.0000000000', 0),
(3, 'reaksmey.se', '25d55ad283aa400af464c76d713c07ad', 3, 'khmer', '0.0000000000', 0),
(4, 'sokeara.sim', '25d55ad283aa400af464c76d713c07ad', 4, 'khmer', '0.0000000000', 0),
(5, 'thida.pen', '25d55ad283aa400af464c76d713c07ad', 5, 'khmer', '0.0000000000', 0),
(6, 'sreychen.sok', '25d55ad283aa400af464c76d713c07ad', 6, 'khmer', '0.0000000000', 0),
(7, 'vanneth.chan', '25d55ad283aa400af464c76d713c07ad', 7, 'khmer', '0.0000000000', 0),
(8, 'panha.sok@gmail.com', '25d55ad283aa400af464c76d713c07ad', 8, 'khmer', '0.0000000000', 0),
(9, 'piseth.yen', '25d55ad283aa400af464c76d713c07ad', 9, 'khmer', '0.0000000000', 0),
(10, 'sopheakmonkol.sok', '25d55ad283aa400af464c76d713c07ad', 10, 'khmer', '0.0000000000', 0),
(11, 'chhay.ao', '25d55ad283aa400af464c76d713c07ad', 11, 'khmer', '0.0000000000', 0),
(12, 'rady.ros', '25d55ad283aa400af464c76d713c07ad', 12, 'khmer', '0.0000000000', 0),
(13, 'chhingchhing.hem', '25d55ad283aa400af464c76d713c07ad', 13, 'khmer', '0.0000000000', 0),
(14, 'sreynak.chet', '25d55ad283aa400af464c76d713c07ad', 14, 'khmer', '0.0000000000', 0),
(15, 'rothana.ly', '25d55ad283aa400af464c76d713c07ad', 15, 'khmer', '0.0000000000', 0),
(16, 'sovannborey.ly', '25d55ad283aa400af464c76d713c07ad', 16, 'khmer', '0.0000000000', 0),
(17, 'rithy.thul', '25d55ad283aa400af464c76d713c07ad', 17, 'khmer', '0.0000000000', 0),
(18, 'saorin.phan', '25d55ad283aa400af464c76d713c07ad', 18, 'khmer', '0.0000000000', 0),
(19, 'sokleak.lanh', '25d55ad283aa400af464c76d713c07ad', 19, 'khmer', '0.0000000000', 0),
(20, 'chyeng.mao', '25d55ad283aa400af464c76d713c07ad', 20, 'khmer', '0.0000000000', 0),
(21, 'somnnang.sok', '25d55ad283aa400af464c76d713c07ad', 21, 'khmer', '0.0000000000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_employees_locations`
--

CREATE TABLE IF NOT EXISTS `cln_employees_locations` (
  `employee_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`employee_id`,`location_id`),
  KEY `cln_employees_locations_ibfk_2` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cln_employees_locations`
--

INSERT INTO `cln_employees_locations` (`employee_id`, `location_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cln_expenses`
--

CREATE TABLE IF NOT EXISTS `cln_expenses` (
  `id` int(11) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `amount` float DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `deleted` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_expenses_attachment`
--

CREATE TABLE IF NOT EXISTS `cln_expenses_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(245) COLLATE utf8_unicode_ci NOT NULL,
  `expense_id` int(11) NOT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_expenses_detail`
--

CREATE TABLE IF NOT EXISTS `cln_expenses_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) DEFAULT '0' COMMENT '	',
  `unit_price` float DEFAULT NULL,
  `rate` float DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `expense_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_expense_attachment`
--

CREATE TABLE IF NOT EXISTS `cln_expense_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(245) COLLATE utf8_unicode_ci NOT NULL,
  `expense_id` int(11) NOT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_expense_detail`
--

CREATE TABLE IF NOT EXISTS `cln_expense_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) DEFAULT '0' COMMENT '	',
  `unit_price` float DEFAULT NULL,
  `rate` float DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `expense_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_giftcards`
--

CREATE TABLE IF NOT EXISTS `cln_giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` decimal(23,10) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`),
  KEY `deleted` (`deleted`),
  KEY `cln_giftcards_ibfk_1` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_inventory`
--

CREATE TABLE IF NOT EXISTS `cln_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text COLLATE utf8_unicode_ci NOT NULL,
  `trans_inventory` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`trans_id`),
  KEY `cln_inventory_ibfk_1` (`trans_items`),
  KEY `cln_inventory_ibfk_2` (`trans_user`),
  KEY `location_id` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_items`
--

CREATE TABLE IF NOT EXISTS `cln_items` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `cost_price` decimal(23,10) NOT NULL,
  `unit_price` decimal(23,10) NOT NULL,
  `promo_price` decimal(23,10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reorder_level` decimal(23,10) DEFAULT NULL,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `image_id` int(10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `is_service` int(1) NOT NULL DEFAULT '0',
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `commission_fixed` decimal(23,10) DEFAULT '0.0000000000',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `cln_items_ibfk_1` (`supplier_id`),
  KEY `name` (`name`),
  KEY `category` (`category`),
  KEY `deleted` (`deleted`),
  KEY `cln_items_ibfk_2` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_items_taxes`
--

CREATE TABLE IF NOT EXISTS `cln_items_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`item_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_items_tier_prices`
--

CREATE TABLE IF NOT EXISTS `cln_items_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_id`),
  KEY `cln_items_tier_prices_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_item_kits`
--

CREATE TABLE IF NOT EXISTS `cln_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_kit_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `unit_price` decimal(23,10) DEFAULT NULL,
  `cost_price` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `commission_fixed` decimal(23,10) DEFAULT '0.0000000000',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_kit_id`),
  UNIQUE KEY `item_kit_number` (`item_kit_number`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `name` (`name`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_item_kits_taxes`
--

CREATE TABLE IF NOT EXISTS `cln_item_kits_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`item_kit_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_item_kits_tier_prices`
--

CREATE TABLE IF NOT EXISTS `cln_item_kits_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_kit_id`),
  KEY `cln_item_kits_tier_prices_ibfk_2` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_item_kit_items`
--

CREATE TABLE IF NOT EXISTS `cln_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(23,10) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `cln_item_kit_items_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_laon_setting`
--

CREATE TABLE IF NOT EXISTS `cln_laon_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate_one` float NOT NULL,
  `rate_two` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cln_laon_setting`
--

INSERT INTO `cln_laon_setting` (`id`, `rate_one`, `rate_two`) VALUES
(1, 1.99, 2.99);

-- --------------------------------------------------------

--
-- Table structure for table `cln_loan`
--

CREATE TABLE IF NOT EXISTS `cln_loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL DEFAULT '0',
  `rate` float NOT NULL DEFAULT '0',
  `borrow_date` datetime DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `currency` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `product_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `status` int(11) DEFAULT '0',
  `deposit` float DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `person_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cln_loan_cln_customers1_idx` (`person_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Dumping data for table `cln_loan`
--

INSERT INTO `cln_loan` (`id`, `amount`, `rate`, `borrow_date`, `duration`, `start_date`, `end_date`, `currency`, `product_name`, `comments`, `status`, `deposit`, `deleted`, `person_id`) VALUES
(1, 1500, 2.99, '2015-04-10 00:00:00', 12, '2015-04-15 00:00:00', '2016-03-15 00:00:00', 'usd', 'Iphone1', '', 0, 200, 0, 23),
(2, 3000, 2.99, '2015-04-14 00:00:00', 14, '2015-04-18 00:00:00', '2016-05-18 00:00:00', 'usd', 'Iphone 3', '', 0, 100, 0, 25),
(3, 400, 2.99, '2015-04-18 00:00:00', 12, '2015-04-20 00:00:00', '2016-03-20 00:00:00', 'reils', 'Iphone 4', '', 0, 0, 0, 28),
(4, 1000, 2.99, '2015-04-15 00:00:00', 18, '2015-04-24 00:00:00', '2016-09-24 00:00:00', 'usd', 'Iphone 1', '', 0, 0, 0, 27),
(5, 200, 2.99, '2015-04-20 00:00:00', 12, '2015-04-30 00:00:00', '2016-03-30 00:00:00', 'usd', 'Iphone 2', '', 0, 20, 0, 31),
(6, 2000, 2.99, '2015-04-26 00:00:00', 15, '2015-04-29 00:00:00', '2016-06-29 00:00:00', 'reils', 'SamSung', '', 0, 20, 0, 26),
(7, 3100, 2.99, '2011-04-11 00:00:00', 20, '2013-12-20 00:00:00', '2014-07-20 00:00:00', 'usd', 'SamSung', 'Some Comment', 0, 0, 0, 41),
(8, 2000, 2.99, '2015-04-30 00:00:00', 10, '2015-05-01 00:00:00', '2016-02-01 00:00:00', 'usd', '', '', 0, 10, 0, 36),
(9, 1000, 2.99, '2015-04-27 00:00:00', 10, '2015-04-30 00:00:00', '2016-01-30 00:00:00', 'usd', 'Iphone 6', '', 0, 0, 0, 39),
(10, 2000, 1.99, '2015-04-23 00:00:00', 12, '2015-04-28 00:00:00', '2016-03-28 00:00:00', 'usd', 'Xiami 2', '', 0, 500, 0, 42),
(11, 1000, 1.99, '2015-05-01 00:00:00', 10, '2015-05-10 00:00:00', '2016-02-10 00:00:00', 'reils', 'SamSung', '', 0, 200, 0, 28),
(12, 1000, 2.99, '2015-05-12 00:00:00', 8, '2015-05-20 00:00:00', '2016-11-20 00:00:00', 'usd', 'Iphone8', '', 0, 10, 0, 29),
(13, 1000, 1.99, '2015-05-20 00:00:00', 10, '2015-05-26 00:00:00', '2016-02-26 00:00:00', 'usd', 'Product 2', '', 0, 200, 0, 30),
(14, 1000, 2.99, '2015-05-08 00:00:00', 15, '2015-05-20 00:00:00', '2016-07-20 00:00:00', 'reils', 'Iphone 9', '', 0, 20, 0, 32),
(15, 800, 2.99, '2015-05-10 00:00:00', 10, '2015-05-15 00:00:00', '2016-02-15 00:00:00', 'usd', 'Iphone 30', '', 0, 50, 0, 31),
(16, 1200, 2.99, '2015-05-12 00:00:00', 10, '2015-05-20 00:00:00', '2016-02-20 00:00:00', 'reils', '', '', 0, 0, 0, 34),
(17, 200, 2.99, '2015-05-14 00:00:00', 12, '2015-05-20 00:00:00', '2016-04-20 00:00:00', 'usd', '', '', 0, 20, 0, 38),
(18, 200, 1.99, '2015-05-20 00:00:00', 13, '2015-05-26 00:00:00', '2016-05-26 00:00:00', 'reils', '', '', 0, 100, 0, 35),
(19, 1000, 2.99, '2015-05-13 00:00:00', 12, '2015-05-26 00:00:00', '2016-04-26 00:00:00', 'reils', 'Iphone 4', '', 0, 20, 0, 40),
(20, 2000, 2.99, '2015-05-14 00:00:00', 20, '2015-05-28 00:00:00', '2017-11-28 00:00:00', 'usd', '', '', 0, 12, 0, 41),
(21, 4000, 2.99, '2015-05-26 00:00:00', 12, '2015-05-30 00:00:00', '2016-04-30 00:00:00', 'reils', '', '', 0, 10, 0, 31),
(22, 1000, 2.99, '2015-05-20 00:00:00', 12, '2015-05-30 00:00:00', '2016-04-30 00:00:00', 'usd', '', '', 0, 120, 0, 30),
(23, 2000, 2.99, '2015-04-14 00:00:00', 12, '2015-04-28 00:00:00', '2016-03-28 00:00:00', 'usd', 'CorolaFC', 'My Comment Last night', 0, 250, 0, 22),
(24, 3000, 2.99, '2015-04-28 00:00:00', 10, '2015-05-05 00:00:00', '2016-02-05 00:00:00', 'usd', 'Google Glash', 'Really Good', 0, 500, 0, 24),
(25, 2000, 2.99, '2015-04-16 00:00:00', 20, '2015-05-13 00:00:00', '2017-11-13 00:00:00', 'usd', 'SamSung Galaxy Note', '', 0, 100, 0, 34),
(26, 240, 2.99, '2015-03-07 00:00:00', 12, '2015-05-06 00:00:00', '2016-04-06 00:00:00', 'usd', '', '', 0, 0, 0, 40),
(27, 200, 2.99, '2015-06-07 00:00:00', 12, '2015-06-15 00:00:00', '2016-05-15 00:00:00', 'usd', '', '', 0, 12, 0, 40),
(28, 2000, 2.99, '2015-06-07 00:00:00', 12, '2015-06-18 00:00:00', '2016-05-18 00:00:00', 'usd', '', '', 0, 120, 0, 40),
(29, 2000, 2.99, '2015-03-07 00:00:00', 12, '2015-06-03 00:00:00', '2016-05-03 00:00:00', 'usd', '', '', 0, 12, 0, 40),
(30, 1200, 2.99, '2015-06-10 00:00:00', 10, '2015-06-14 00:00:00', '2016-03-14 00:00:00', 'usd', '', '', 0, 0, 0, 34);

-- --------------------------------------------------------

--
-- Table structure for table `cln_loan_schedule`
--

CREATE TABLE IF NOT EXISTS `cln_loan_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_date` datetime NOT NULL,
  `number_day` int(11) DEFAULT '0',
  `pay_principle` float DEFAULT NULL,
  `pay_rate` float DEFAULT NULL,
  `pay_balance` float DEFAULT NULL,
  `pay_total` float DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `pay_fine` float DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `late` int(11) DEFAULT '0',
  `pay_left` float DEFAULT '0',
  `paid_princ` float DEFAULT '0',
  `paid_rate` float DEFAULT '0',
  `loan_id` int(11) NOT NULL,
  `key` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cln_loan_schedule_cln_loan1_idx` (`loan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=422 ;

--
-- Dumping data for table `cln_loan_schedule`
--

INSERT INTO `cln_loan_schedule` (`id`, `pay_date`, `number_day`, `pay_principle`, `pay_rate`, `pay_balance`, `pay_total`, `note`, `pay_fine`, `status`, `late`, `pay_left`, `paid_princ`, `paid_rate`, `loan_id`, `key`) VALUES
(1, '2015-04-10 00:00:00', 0, 1500, NULL, 1500, 1500, NULL, 8, 0, 4, 0, 0, 0, 1, 0),
(2, '2015-04-15 00:00:00', 5, 125, 7.475, 1375, 132.475, NULL, 0, 1, 0, 1868.35, 125, 7.475, 1, 1),
(3, '2015-05-15 00:00:00', 30, 125, 44.85, 1250, 169.85, 'njknkljnkl', 0, 1, 0, 0, 0, 0, 1, 2),
(4, '2015-06-15 00:00:00', 30, 125, 44.85, 1125, 169.85, NULL, 8, 0, 4, 0, 0, 0, 1, 3),
(5, '2015-07-15 00:00:00', 30, 125, 44.85, 1000, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 4),
(6, '2015-08-15 00:00:00', 30, 125, 44.85, 875, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 5),
(7, '2015-09-15 00:00:00', 30, 125, 44.85, 750, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 6),
(8, '2015-10-15 00:00:00', 30, 125, 44.85, 625, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 7),
(9, '2015-11-15 00:00:00', 30, 125, 44.85, 500, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 8),
(10, '2015-12-15 00:00:00', 30, 125, 44.85, 375, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 9),
(11, '2016-01-15 00:00:00', 30, 125, 44.85, 250, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 10),
(12, '2016-02-15 00:00:00', 30, 125, 44.85, 125, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 11),
(13, '2016-03-15 00:00:00', 30, 125, 44.85, 0, 169.85, NULL, 0, 0, 0, 0, 0, 0, 1, 12),
(14, '2015-04-14 00:00:00', 0, 3000, NULL, 3000, 3000, NULL, 0, 0, 0, 0, 0, 0, 2, 0),
(15, '2015-04-18 00:00:00', 4, 214.286, 11.96, 2785.71, 226.246, NULL, 0, 0, 0, 0, 0, 0, 2, 1),
(16, '2015-05-18 00:00:00', 30, 214.286, 89.7, 2571.43, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 2),
(17, '2015-06-18 00:00:00', 30, 214.286, 89.7, 2357.14, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 3),
(18, '2015-07-18 00:00:00', 30, 214.286, 89.7, 2142.86, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 4),
(19, '2015-08-18 00:00:00', 30, 214.286, 89.7, 1928.57, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 5),
(20, '2015-09-18 00:00:00', 30, 214.286, 89.7, 1714.29, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 6),
(21, '2015-10-18 00:00:00', 30, 214.286, 89.7, 1500, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 7),
(22, '2015-11-18 00:00:00', 30, 214.286, 89.7, 1285.71, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 8),
(23, '2015-12-18 00:00:00', 30, 214.286, 89.7, 1071.43, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 9),
(24, '2016-01-18 00:00:00', 30, 214.286, 89.7, 857.143, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 10),
(25, '2016-02-18 00:00:00', 30, 214.286, 89.7, 642.857, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 11),
(26, '2016-03-18 00:00:00', 30, 214.286, 89.7, 428.571, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 12),
(27, '2016-04-18 00:00:00', 30, 214.286, 89.7, 214.286, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 13),
(28, '2016-05-18 00:00:00', 30, 214.286, 89.7, 0.000000000000738964, 303.986, NULL, 0, 0, 0, 0, 0, 0, 2, 14),
(29, '2015-04-18 00:00:00', 0, 400, NULL, 400, 400, NULL, 0, 0, 0, 0, 0, 0, 3, 0),
(30, '2015-04-20 00:00:00', 2, 33.3333, 0.797333, 366.667, 34.1307, NULL, 0, 0, 0, 0, 0, 0, 3, 1),
(31, '2015-05-20 00:00:00', 30, 33.3333, 11.96, 333.333, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 2),
(32, '2015-06-20 00:00:00', 30, 33.3333, 11.96, 300, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 3),
(33, '2015-07-20 00:00:00', 30, 33.3333, 11.96, 266.667, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 4),
(34, '2015-08-20 00:00:00', 30, 33.3333, 11.96, 233.333, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 5),
(35, '2015-09-20 00:00:00', 30, 33.3333, 11.96, 200, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 6),
(36, '2015-10-20 00:00:00', 30, 33.3333, 11.96, 166.667, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 7),
(37, '2015-11-20 00:00:00', 30, 33.3333, 11.96, 133.333, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 8),
(38, '2015-12-20 00:00:00', 30, 33.3333, 11.96, 100, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 9),
(39, '2016-01-20 00:00:00', 30, 33.3333, 11.96, 66.6667, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 10),
(40, '2016-02-20 00:00:00', 30, 33.3333, 11.96, 33.3333, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 11),
(41, '2016-03-20 00:00:00', 30, 33.3333, 11.96, 0.0000000000000142109, 45.2933, NULL, 0, 0, 0, 0, 0, 0, 3, 12),
(42, '2015-04-15 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 4, 0),
(43, '2015-04-24 00:00:00', 9, 55.5556, 8.97, 944.444, 64.5256, NULL, 0, 0, 0, 0, 0, 0, 4, 1),
(44, '2015-05-24 00:00:00', 30, 55.5556, 29.9, 888.889, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 2),
(45, '2015-06-24 00:00:00', 30, 55.5556, 29.9, 833.333, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 3),
(46, '2015-07-24 00:00:00', 30, 55.5556, 29.9, 777.778, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 4),
(47, '2015-08-24 00:00:00', 30, 55.5556, 29.9, 722.222, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 5),
(48, '2015-09-24 00:00:00', 30, 55.5556, 29.9, 666.667, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 6),
(49, '2015-10-24 00:00:00', 30, 55.5556, 29.9, 611.111, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 7),
(50, '2015-11-24 00:00:00', 30, 55.5556, 29.9, 555.556, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 8),
(51, '2015-12-24 00:00:00', 30, 55.5556, 29.9, 500, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 9),
(52, '2016-01-24 00:00:00', 30, 55.5556, 29.9, 444.444, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 10),
(53, '2016-02-24 00:00:00', 30, 55.5556, 29.9, 388.889, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 11),
(54, '2016-03-24 00:00:00', 30, 55.5556, 29.9, 333.333, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 12),
(55, '2016-04-24 00:00:00', 30, 55.5556, 29.9, 277.778, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 13),
(56, '2016-05-24 00:00:00', 30, 55.5556, 29.9, 222.222, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 14),
(57, '2016-06-24 00:00:00', 30, 55.5556, 29.9, 166.667, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 15),
(58, '2016-07-24 00:00:00', 30, 55.5556, 29.9, 111.111, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 16),
(59, '2016-08-24 00:00:00', 30, 55.5556, 29.9, 55.5556, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 17),
(60, '2016-09-24 00:00:00', 30, 55.5556, 29.9, 0.000000000000184741, 85.4556, NULL, 0, 0, 0, 0, 0, 0, 4, 18),
(61, '2015-04-20 00:00:00', 0, 200, NULL, 200, 200, NULL, 0, 0, 0, 0, 0, 0, 5, 0),
(62, '2015-04-30 00:00:00', 10, 16.6667, 1.99333, 183.333, 18.66, NULL, 0, 0, 0, 0, 0, 0, 5, 1),
(63, '2015-05-30 00:00:00', 30, 16.6667, 5.98, 166.667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 2),
(64, '2015-06-30 00:00:00', 30, 16.6667, 5.98, 150, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 3),
(65, '2015-07-30 00:00:00', 30, 16.6667, 5.98, 133.333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 4),
(66, '2015-08-30 00:00:00', 30, 16.6667, 5.98, 116.667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 5),
(67, '2015-09-30 00:00:00', 30, 16.6667, 5.98, 100, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 6),
(68, '2015-10-30 00:00:00', 30, 16.6667, 5.98, 83.3333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 7),
(69, '2015-11-30 00:00:00', 30, 16.6667, 5.98, 66.6667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 8),
(70, '2015-12-30 00:00:00', 30, 16.6667, 5.98, 50, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 9),
(71, '2016-01-30 00:00:00', 30, 16.6667, 5.98, 33.3333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 10),
(72, '2016-02-28 00:00:00', 30, 16.6667, 5.98, 16.6667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 11),
(73, '2016-03-30 00:00:00', 30, 16.6667, 5.98, 0.00000000000000710543, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 5, 12),
(74, '2015-04-26 00:00:00', 0, 2000, NULL, 2000, 2000, NULL, 0, 0, 0, 0, 0, 0, 6, 0),
(75, '2015-04-29 00:00:00', 3, 133.333, 5.98, 1866.67, 139.313, NULL, 0, 0, 0, 0, 0, 0, 6, 1),
(76, '2015-05-29 00:00:00', 30, 133.333, 59.8, 1733.33, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 2),
(77, '2015-06-29 00:00:00', 30, 133.333, 59.8, 1600, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 3),
(78, '2015-07-29 00:00:00', 30, 133.333, 59.8, 1466.67, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 4),
(79, '2015-08-29 00:00:00', 30, 133.333, 59.8, 1333.33, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 5),
(80, '2015-09-29 00:00:00', 30, 133.333, 59.8, 1200, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 6),
(81, '2015-10-29 00:00:00', 30, 133.333, 59.8, 1066.67, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 7),
(82, '2015-11-29 00:00:00', 30, 133.333, 59.8, 933.333, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 8),
(83, '2015-12-29 00:00:00', 30, 133.333, 59.8, 800, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 9),
(84, '2016-01-29 00:00:00', 30, 133.333, 59.8, 666.667, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 10),
(85, '2016-02-28 00:00:00', 30, 133.333, 59.8, 533.333, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 11),
(86, '2016-03-29 00:00:00', 30, 133.333, 59.8, 400, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 12),
(87, '2016-04-29 00:00:00', 30, 133.333, 59.8, 266.667, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 13),
(88, '2016-05-29 00:00:00', 30, 133.333, 59.8, 133.333, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 14),
(89, '2016-06-29 00:00:00', 30, 133.333, 59.8, 0.000000000000284217, 193.133, NULL, 0, 0, 0, 0, 0, 0, 6, 15),
(90, '2011-04-11 00:00:00', 0, 3100, NULL, 3100, 3100, NULL, 0, 0, 0, 0, 0, 0, 7, 0),
(91, '2013-12-20 00:00:00', 39, 155, 120.497, 2945, 275.497, NULL, 0, 0, 0, 0, 0, 0, 7, 1),
(92, '2014-01-20 00:00:00', 30, 155, 92.69, 2790, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 2),
(93, '2014-02-20 00:00:00', 30, 155, 92.69, 2635, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 3),
(94, '2014-03-20 00:00:00', 30, 155, 92.69, 2480, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 4),
(95, '2014-04-20 00:00:00', 30, 155, 92.69, 2325, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 5),
(96, '2014-05-20 00:00:00', 30, 155, 92.69, 2170, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 6),
(97, '2014-06-20 00:00:00', 30, 155, 92.69, 2015, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 7),
(98, '2014-07-20 00:00:00', 30, 155, 92.69, 1860, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 8),
(99, '2014-08-20 00:00:00', 30, 155, 92.69, 1705, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 9),
(100, '2014-09-20 00:00:00', 30, 155, 92.69, 1550, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 10),
(101, '2014-10-20 00:00:00', 30, 155, 92.69, 1395, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 11),
(102, '2014-11-20 00:00:00', 30, 155, 92.69, 1240, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 12),
(103, '2014-12-20 00:00:00', 30, 155, 92.69, 1085, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 13),
(104, '2015-01-20 00:00:00', 30, 155, 92.69, 930, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 14),
(105, '2015-02-20 00:00:00', 30, 155, 92.69, 775, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 15),
(106, '2015-03-20 00:00:00', 30, 155, 92.69, 620, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 16),
(107, '2015-04-20 00:00:00', 30, 155, 92.69, 465, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 17),
(108, '2015-05-20 00:00:00', 30, 155, 92.69, 310, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 18),
(109, '2015-06-20 00:00:00', 30, 155, 92.69, 155, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 19),
(110, '2015-07-20 00:00:00', 30, 155, 92.69, 0, 247.69, NULL, 0, 0, 0, 0, 0, 0, 7, 20),
(111, '2015-04-30 00:00:00', 0, 2000, NULL, 2000, 2000, NULL, 0, 0, 0, 0, 0, 0, 8, 0),
(112, '2015-05-01 00:00:00', 1, 200, 1.99333, 1800, 201.993, NULL, 0, 0, 0, 0, 0, 0, 8, 1),
(113, '2015-06-01 00:00:00', 30, 200, 59.8, 1600, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 2),
(114, '2015-07-01 00:00:00', 30, 200, 59.8, 1400, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 3),
(115, '2015-08-01 00:00:00', 30, 200, 59.8, 1200, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 4),
(116, '2015-09-01 00:00:00', 30, 200, 59.8, 1000, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 5),
(117, '2015-10-01 00:00:00', 30, 200, 59.8, 800, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 6),
(118, '2015-11-01 00:00:00', 30, 200, 59.8, 600, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 7),
(119, '2015-12-01 00:00:00', 30, 200, 59.8, 400, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 8),
(120, '2016-01-01 00:00:00', 30, 200, 59.8, 200, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 9),
(121, '2016-02-01 00:00:00', 30, 200, 59.8, 0, 259.8, NULL, 0, 0, 0, 0, 0, 0, 8, 10),
(122, '2015-04-27 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 9, 0),
(123, '2015-04-30 00:00:00', 3, 100, 2.99, 900, 102.99, NULL, 0, 0, 0, 0, 0, 0, 9, 1),
(124, '2015-05-30 00:00:00', 30, 100, 29.9, 800, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 2),
(125, '2015-06-30 00:00:00', 30, 100, 29.9, 700, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 3),
(126, '2015-07-30 00:00:00', 30, 100, 29.9, 600, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 4),
(127, '2015-08-30 00:00:00', 30, 100, 29.9, 500, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 5),
(128, '2015-09-30 00:00:00', 30, 100, 29.9, 400, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 6),
(129, '2015-10-30 00:00:00', 30, 100, 29.9, 300, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 7),
(130, '2015-11-30 00:00:00', 30, 100, 29.9, 200, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 8),
(131, '2015-12-30 00:00:00', 30, 100, 29.9, 100, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 9),
(132, '2016-01-30 00:00:00', 30, 100, 29.9, 0, 129.9, NULL, 0, 0, 0, 0, 0, 0, 9, 10),
(133, '2015-04-23 00:00:00', 0, 2000, NULL, 2000, 2000, NULL, 0, 0, 0, 0, 0, 0, 10, 0),
(134, '2015-04-28 00:00:00', 5, 166.667, 6.63333, 1833.33, 173.3, NULL, 0, 0, 0, 0, 0, 0, 10, 1),
(135, '2015-05-28 00:00:00', 30, 166.667, 39.8, 1666.67, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 2),
(136, '2015-06-28 00:00:00', 30, 166.667, 39.8, 1500, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 3),
(137, '2015-07-28 00:00:00', 30, 166.667, 39.8, 1333.33, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 4),
(138, '2015-08-28 00:00:00', 30, 166.667, 39.8, 1166.67, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 5),
(139, '2015-09-28 00:00:00', 30, 166.667, 39.8, 1000, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 6),
(140, '2015-10-28 00:00:00', 30, 166.667, 39.8, 833.333, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 7),
(141, '2015-11-28 00:00:00', 30, 166.667, 39.8, 666.667, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 8),
(142, '2015-12-28 00:00:00', 30, 166.667, 39.8, 500, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 9),
(143, '2016-01-28 00:00:00', 30, 166.667, 39.8, 333.333, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 10),
(144, '2016-02-28 00:00:00', 30, 166.667, 39.8, 166.667, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 11),
(145, '2016-03-28 00:00:00', 30, 166.667, 39.8, -0.00000000000017053, 206.467, NULL, 0, 0, 0, 0, 0, 0, 10, 12),
(146, '2015-05-01 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 11, 0),
(147, '2015-05-10 00:00:00', 9, 100, 5.97, 900, 105.97, NULL, 0, 0, 0, 0, 0, 0, 11, 1),
(148, '2015-06-10 00:00:00', 30, 100, 19.9, 800, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 2),
(149, '2015-07-10 00:00:00', 30, 100, 19.9, 700, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 3),
(150, '2015-08-10 00:00:00', 30, 100, 19.9, 600, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 4),
(151, '2015-09-10 00:00:00', 30, 100, 19.9, 500, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 5),
(152, '2015-10-10 00:00:00', 30, 100, 19.9, 400, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 6),
(153, '2015-11-10 00:00:00', 30, 100, 19.9, 300, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 7),
(154, '2015-12-10 00:00:00', 30, 100, 19.9, 200, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 8),
(155, '2016-01-10 00:00:00', 30, 100, 19.9, 100, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 9),
(156, '2016-02-10 00:00:00', 30, 100, 19.9, 0, 119.9, NULL, 0, 0, 0, 0, 0, 0, 11, 10),
(157, '2015-05-12 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 12, 0),
(158, '2015-05-20 00:00:00', 8, 125, 7.97333, 875, 132.973, NULL, 0, 0, 0, 0, 0, 0, 12, 1),
(159, '2015-06-20 00:00:00', 30, 125, 29.9, 750, 154.9, NULL, 0, 0, 0, 0, 0, 0, 12, 2),
(160, '2015-07-20 00:00:00', 30, 125, 29.9, 625, 154.9, NULL, 0, 0, 0, 0, 0, 0, 12, 3),
(161, '2015-08-20 00:00:00', 30, 125, 29.9, 500, 154.9, NULL, 0, 0, 0, 0, 0, 0, 12, 4),
(162, '2015-09-20 00:00:00', 30, 125, 29.9, 375, 154.9, NULL, 0, 0, 0, 0, 0, 0, 12, 5),
(163, '2015-10-20 00:00:00', 30, 125, 29.9, 250, 154.9, NULL, 0, 0, 0, 0, 0, 0, 12, 6),
(164, '2015-11-20 00:00:00', 30, 125, 29.9, 125, 154.9, NULL, 0, 0, 0, 0, 0, 0, 12, 7),
(165, '2015-12-20 00:00:00', 30, 125, 29.9, 0, 154.9, NULL, 0, 0, 0, 0, 0, 0, 12, 8),
(166, '2015-05-20 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 13, 0),
(167, '2015-05-26 00:00:00', 6, 100, 3.98, 900, 103.98, NULL, 0, 0, 0, 0, 0, 0, 13, 1),
(168, '2015-06-26 00:00:00', 30, 100, 19.9, 800, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 2),
(169, '2015-07-26 00:00:00', 30, 100, 19.9, 700, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 3),
(170, '2015-08-26 00:00:00', 30, 100, 19.9, 600, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 4),
(171, '2015-09-26 00:00:00', 30, 100, 19.9, 500, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 5),
(172, '2015-10-26 00:00:00', 30, 100, 19.9, 400, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 6),
(173, '2015-11-26 00:00:00', 30, 100, 19.9, 300, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 7),
(174, '2015-12-26 00:00:00', 30, 100, 19.9, 200, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 8),
(175, '2016-01-26 00:00:00', 30, 100, 19.9, 100, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 9),
(176, '2016-02-26 00:00:00', 30, 100, 19.9, 0, 119.9, NULL, 0, 0, 0, 0, 0, 0, 13, 10),
(177, '2015-05-08 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 14, 0),
(178, '2015-05-20 00:00:00', 12, 66.6667, 11.96, 933.333, 78.6267, NULL, 0, 0, 0, 0, 0, 0, 14, 1),
(179, '2015-06-20 00:00:00', 30, 66.6667, 29.9, 866.667, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 2),
(180, '2015-07-20 00:00:00', 30, 66.6667, 29.9, 800, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 3),
(181, '2015-08-20 00:00:00', 30, 66.6667, 29.9, 733.333, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 4),
(182, '2015-09-20 00:00:00', 30, 66.6667, 29.9, 666.667, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 5),
(183, '2015-10-20 00:00:00', 30, 66.6667, 29.9, 600, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 6),
(184, '2015-11-20 00:00:00', 30, 66.6667, 29.9, 533.333, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 7),
(185, '2015-12-20 00:00:00', 30, 66.6667, 29.9, 466.667, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 8),
(186, '2016-01-20 00:00:00', 30, 66.6667, 29.9, 400, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 9),
(187, '2016-02-20 00:00:00', 30, 66.6667, 29.9, 333.333, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 10),
(188, '2016-03-20 00:00:00', 30, 66.6667, 29.9, 266.667, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 11),
(189, '2016-04-20 00:00:00', 30, 66.6667, 29.9, 200, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 12),
(190, '2016-05-20 00:00:00', 30, 66.6667, 29.9, 133.333, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 13),
(191, '2016-06-20 00:00:00', 30, 66.6667, 29.9, 66.6667, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 14),
(192, '2016-07-20 00:00:00', 30, 66.6667, 29.9, 0.000000000000142109, 96.5667, NULL, 0, 0, 0, 0, 0, 0, 14, 15),
(193, '2015-05-10 00:00:00', 0, 800, NULL, 800, 800, NULL, 0, 0, 0, 0, 0, 0, 15, 0),
(194, '2015-05-15 00:00:00', 5, 80, 3.98667, 720, 83.9867, NULL, 0, 0, 0, 0, 0, 0, 15, 1),
(195, '2015-06-15 00:00:00', 30, 80, 23.92, 640, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 2),
(196, '2015-07-15 00:00:00', 30, 80, 23.92, 560, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 3),
(197, '2015-08-15 00:00:00', 30, 80, 23.92, 480, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 4),
(198, '2015-09-15 00:00:00', 30, 80, 23.92, 400, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 5),
(199, '2015-10-15 00:00:00', 30, 80, 23.92, 320, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 6),
(200, '2015-11-15 00:00:00', 30, 80, 23.92, 240, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 7),
(201, '2015-12-15 00:00:00', 30, 80, 23.92, 160, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 8),
(202, '2016-01-15 00:00:00', 30, 80, 23.92, 80, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 9),
(203, '2016-02-15 00:00:00', 30, 80, 23.92, 0, 103.92, NULL, 0, 0, 0, 0, 0, 0, 15, 10),
(204, '2015-05-12 00:00:00', 0, 1200, NULL, 1200, 1200, NULL, 0, 0, 0, 0, 0, 0, 16, 0),
(205, '2015-05-20 00:00:00', 8, 120, 9.568, 1080, 129.568, NULL, 0, 0, 0, 0, 0, 0, 16, 1),
(206, '2015-06-20 00:00:00', 30, 120, 35.88, 960, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 2),
(207, '2015-07-20 00:00:00', 30, 120, 35.88, 840, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 3),
(208, '2015-08-20 00:00:00', 30, 120, 35.88, 720, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 4),
(209, '2015-09-20 00:00:00', 30, 120, 35.88, 600, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 5),
(210, '2015-10-20 00:00:00', 30, 120, 35.88, 480, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 6),
(211, '2015-11-20 00:00:00', 30, 120, 35.88, 360, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 7),
(212, '2015-12-20 00:00:00', 30, 120, 35.88, 240, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 8),
(213, '2016-01-20 00:00:00', 30, 120, 35.88, 120, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 9),
(214, '2016-02-20 00:00:00', 30, 120, 35.88, 0, 155.88, NULL, 0, 0, 0, 0, 0, 0, 16, 10),
(215, '2015-05-14 00:00:00', 0, 200, NULL, 200, 200, NULL, 0, 0, 0, 0, 0, 0, 17, 0),
(216, '2015-05-20 00:00:00', 6, 16.6667, 1.196, 183.333, 17.8627, NULL, 0, 0, 0, 0, 0, 0, 17, 1),
(217, '2015-06-20 00:00:00', 30, 16.6667, 5.98, 166.667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 2),
(218, '2015-07-20 00:00:00', 30, 16.6667, 5.98, 150, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 3),
(219, '2015-08-20 00:00:00', 30, 16.6667, 5.98, 133.333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 4),
(220, '2015-09-20 00:00:00', 30, 16.6667, 5.98, 116.667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 5),
(221, '2015-10-20 00:00:00', 30, 16.6667, 5.98, 100, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 6),
(222, '2015-11-20 00:00:00', 30, 16.6667, 5.98, 83.3333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 7),
(223, '2015-12-20 00:00:00', 30, 16.6667, 5.98, 66.6667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 8),
(224, '2016-01-20 00:00:00', 30, 16.6667, 5.98, 50, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 9),
(225, '2016-02-20 00:00:00', 30, 16.6667, 5.98, 33.3333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 10),
(226, '2016-03-20 00:00:00', 30, 16.6667, 5.98, 16.6667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 11),
(227, '2016-04-20 00:00:00', 30, 16.6667, 5.98, 0.00000000000000710543, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 17, 12),
(228, '2015-05-20 00:00:00', 0, 200, NULL, 200, 200, NULL, 0, 0, 0, 0, 0, 0, 18, 0),
(229, '2015-05-26 00:00:00', 6, 15.3846, 0.796, 184.615, 16.1806, NULL, 0, 0, 0, 0, 0, 0, 18, 1),
(230, '2015-06-26 00:00:00', 30, 15.3846, 3.98, 169.231, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 2),
(231, '2015-07-26 00:00:00', 30, 15.3846, 3.98, 153.846, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 3),
(232, '2015-08-26 00:00:00', 30, 15.3846, 3.98, 138.462, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 4),
(233, '2015-09-26 00:00:00', 30, 15.3846, 3.98, 123.077, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 5),
(234, '2015-10-26 00:00:00', 30, 15.3846, 3.98, 107.692, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 6),
(235, '2015-11-26 00:00:00', 30, 15.3846, 3.98, 92.3077, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 7),
(236, '2015-12-26 00:00:00', 30, 15.3846, 3.98, 76.9231, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 8),
(237, '2016-01-26 00:00:00', 30, 15.3846, 3.98, 61.5385, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 9),
(238, '2016-02-26 00:00:00', 30, 15.3846, 3.98, 46.1538, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 10),
(239, '2016-03-26 00:00:00', 30, 15.3846, 3.98, 30.7692, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 11),
(240, '2016-04-26 00:00:00', 30, 15.3846, 3.98, 15.3846, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 12),
(241, '2016-05-26 00:00:00', 30, 15.3846, 3.98, -0.000000000000024869, 19.3646, NULL, 0, 0, 0, 0, 0, 0, 18, 13),
(242, '2015-05-13 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 19, 0),
(243, '2015-05-26 00:00:00', 13, 83.3333, 12.9567, 916.667, 96.29, NULL, 0, 0, 0, 0, 0, 0, 19, 1),
(244, '2015-06-26 00:00:00', 30, 83.3333, 29.9, 833.333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 2),
(245, '2015-07-26 00:00:00', 30, 83.3333, 29.9, 750, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 3),
(246, '2015-08-26 00:00:00', 30, 83.3333, 29.9, 666.667, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 4),
(247, '2015-09-26 00:00:00', 30, 83.3333, 29.9, 583.333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 5),
(248, '2015-10-26 00:00:00', 30, 83.3333, 29.9, 500, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 6),
(249, '2015-11-26 00:00:00', 30, 83.3333, 29.9, 416.667, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 7),
(250, '2015-12-26 00:00:00', 30, 83.3333, 29.9, 333.333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 8),
(251, '2016-01-26 00:00:00', 30, 83.3333, 29.9, 250, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 9),
(252, '2016-02-26 00:00:00', 30, 83.3333, 29.9, 166.667, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 10),
(253, '2016-03-26 00:00:00', 30, 83.3333, 29.9, 83.3333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 11),
(254, '2016-04-26 00:00:00', 30, 83.3333, 29.9, -0.0000000000000852651, 113.233, NULL, 0, 0, 0, 0, 0, 0, 19, 12),
(255, '2015-05-14 00:00:00', 0, 2000, NULL, 2000, 2000, NULL, 0, 0, 0, 0, 0, 0, 20, 0),
(256, '2015-05-28 00:00:00', 14, 100, 27.9067, 1900, 127.907, NULL, 0, 0, 0, 0, 0, 0, 20, 1),
(257, '2015-06-28 00:00:00', 30, 100, 59.8, 1800, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 2),
(258, '2015-07-28 00:00:00', 30, 100, 59.8, 1700, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 3),
(259, '2015-08-28 00:00:00', 30, 100, 59.8, 1600, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 4),
(260, '2015-09-28 00:00:00', 30, 100, 59.8, 1500, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 5),
(261, '2015-10-28 00:00:00', 30, 100, 59.8, 1400, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 6),
(262, '2015-11-28 00:00:00', 30, 100, 59.8, 1300, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 7),
(263, '2015-12-28 00:00:00', 30, 100, 59.8, 1200, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 8),
(264, '2016-01-28 00:00:00', 30, 100, 59.8, 1100, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 9),
(265, '2016-02-28 00:00:00', 30, 100, 59.8, 1000, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 10),
(266, '2016-03-28 00:00:00', 30, 100, 59.8, 900, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 11),
(267, '2016-04-28 00:00:00', 30, 100, 59.8, 800, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 12),
(268, '2016-05-28 00:00:00', 30, 100, 59.8, 700, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 13),
(269, '2016-06-28 00:00:00', 30, 100, 59.8, 600, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 14),
(270, '2016-07-28 00:00:00', 30, 100, 59.8, 500, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 15),
(271, '2016-08-28 00:00:00', 30, 100, 59.8, 400, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 16),
(272, '2016-09-28 00:00:00', 30, 100, 59.8, 300, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 17),
(273, '2016-10-28 00:00:00', 30, 100, 59.8, 200, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 18),
(274, '2016-11-28 00:00:00', 30, 100, 59.8, 100, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 19),
(275, '2016-12-28 00:00:00', 30, 100, 59.8, 0, 159.8, NULL, 0, 0, 0, 0, 0, 0, 20, 20),
(276, '2015-05-26 00:00:00', 0, 4000, NULL, 4000, 4000, NULL, 0, 0, 0, 0, 0, 0, 21, 0),
(277, '2015-05-30 00:00:00', 4, 333.333, 15.9467, 3666.67, 349.28, NULL, 0, 0, 0, 0, 0, 0, 21, 1),
(278, '2015-06-30 00:00:00', 30, 333.333, 119.6, 3333.33, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 2),
(279, '2015-07-30 00:00:00', 30, 333.333, 119.6, 3000, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 3),
(280, '2015-08-30 00:00:00', 30, 333.333, 119.6, 2666.67, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 4),
(281, '2015-09-30 00:00:00', 30, 333.333, 119.6, 2333.33, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 5),
(282, '2015-10-30 00:00:00', 30, 333.333, 119.6, 2000, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 6),
(283, '2015-11-30 00:00:00', 30, 333.333, 119.6, 1666.67, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 7),
(284, '2015-12-30 00:00:00', 30, 333.333, 119.6, 1333.33, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 8),
(285, '2016-01-30 00:00:00', 30, 333.333, 119.6, 1000, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 9),
(286, '2016-02-28 00:00:00', 30, 333.333, 119.6, 666.667, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 10),
(287, '2016-03-30 00:00:00', 30, 333.333, 119.6, 333.333, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 11),
(288, '2016-04-30 00:00:00', 30, 333.333, 119.6, -0.000000000000341061, 452.933, NULL, 0, 0, 0, 0, 0, 0, 21, 12),
(289, '2015-05-20 00:00:00', 0, 1000, NULL, 1000, 1000, NULL, 0, 0, 0, 0, 0, 0, 22, 0),
(290, '2015-05-30 00:00:00', 10, 83.3333, 9.96667, 916.667, 93.3, NULL, 0, 0, 0, 0, 0, 0, 22, 1),
(291, '2015-06-30 00:00:00', 30, 83.3333, 29.9, 833.333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 2),
(292, '2015-07-30 00:00:00', 30, 83.3333, 29.9, 750, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 3),
(293, '2015-08-30 00:00:00', 30, 83.3333, 29.9, 666.667, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 4),
(294, '2015-09-30 00:00:00', 30, 83.3333, 29.9, 583.333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 5),
(295, '2015-10-30 00:00:00', 30, 83.3333, 29.9, 500, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 6),
(296, '2015-11-30 00:00:00', 30, 83.3333, 29.9, 416.667, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 7),
(297, '2015-12-30 00:00:00', 30, 83.3333, 29.9, 333.333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 8),
(298, '2016-01-30 00:00:00', 30, 83.3333, 29.9, 250, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 9),
(299, '2016-02-28 00:00:00', 30, 83.3333, 29.9, 166.667, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 10),
(300, '2016-03-30 00:00:00', 30, 83.3333, 29.9, 83.3333, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 11),
(301, '2016-04-30 00:00:00', 30, 83.3333, 29.9, -0.0000000000000852651, 113.233, NULL, 0, 0, 0, 0, 0, 0, 22, 12),
(314, '2015-04-14 00:00:00', 0, 2000, NULL, 2000, 2000, NULL, 0, 0, 0, 0, 0, 0, 23, 0),
(315, '2015-04-28 00:00:00', 14, 166.667, 27.9067, 1833.33, 194.573, NULL, 0, 0, 0, 0, 0, 0, 23, 1),
(316, '2015-05-28 00:00:00', 30, 166.667, 59.8, 1666.67, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 2),
(317, '2015-06-28 00:00:00', 30, 166.667, 59.8, 1500, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 3),
(318, '2015-07-28 00:00:00', 30, 166.667, 59.8, 1333.33, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 4),
(319, '2015-08-28 00:00:00', 30, 166.667, 59.8, 1166.67, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 5),
(320, '2015-09-28 00:00:00', 30, 166.667, 59.8, 1000, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 6),
(321, '2015-10-28 00:00:00', 30, 166.667, 59.8, 833.333, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 7),
(322, '2015-11-28 00:00:00', 30, 166.667, 59.8, 666.667, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 8),
(323, '2015-12-28 00:00:00', 30, 166.667, 59.8, 500, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 9),
(324, '2016-01-28 00:00:00', 30, 166.667, 59.8, 333.333, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 10),
(325, '2016-02-28 00:00:00', 30, 166.667, 59.8, 166.667, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 11),
(326, '2016-03-28 00:00:00', 30, 166.667, 59.8, -0.00000000000017053, 226.467, NULL, 0, 0, 0, 0, 0, 0, 23, 12),
(327, '2015-04-28 00:00:00', 0, 3000, NULL, 3000, 3000, NULL, 0, 0, 0, 0, 0, 0, 24, 0),
(328, '2015-05-05 00:00:00', 7, 300, 20.93, 2700, 320.93, NULL, 0, 0, 0, 0, 0, 0, 24, 1),
(329, '2015-06-05 00:00:00', 30, 300, 89.7, 2400, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 2),
(330, '2015-07-05 00:00:00', 30, 300, 89.7, 2100, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 3),
(331, '2015-08-05 00:00:00', 30, 300, 89.7, 1800, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 4),
(332, '2015-09-05 00:00:00', 30, 300, 89.7, 1500, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 5),
(333, '2015-10-05 00:00:00', 30, 300, 89.7, 1200, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 6),
(334, '2015-11-05 00:00:00', 30, 300, 89.7, 900, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 7),
(335, '2015-12-05 00:00:00', 30, 300, 89.7, 600, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 8),
(336, '2016-01-05 00:00:00', 30, 300, 89.7, 300, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 9),
(337, '2016-02-05 00:00:00', 30, 300, 89.7, 0, 389.7, NULL, 0, 0, 0, 0, 0, 0, 24, 10),
(338, '2015-04-16 00:00:00', 0, 2000, NULL, 2000, 2000, 'មិនធម្មតា', 0, 0, 0, 0, 0, 0, 25, 0),
(339, '2015-05-13 00:00:00', 27, 100, 53.82, 1900, 153.82, 'This month is too late', 1, 1, 1, 3036.2, 100, 53.82, 25, 1),
(340, '2015-06-13 00:00:00', 30, 100, 59.8, 1800, 2438.2, 'ពិតជាសង្ហារ', 3, 2, 3, 0, 100, 59.8, 25, 2),
(341, '2015-07-13 00:00:00', 30, 100, 59.8, 1700, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 3),
(342, '2015-08-13 00:00:00', 30, 100, 59.8, 1600, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 4),
(343, '2015-09-13 00:00:00', 30, 100, 59.8, 1500, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 5),
(344, '2015-10-13 00:00:00', 30, 100, 59.8, 1400, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 6),
(345, '2015-11-13 00:00:00', 30, 100, 59.8, 1300, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 7),
(346, '2015-12-13 00:00:00', 30, 100, 59.8, 1200, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 8),
(347, '2016-01-13 00:00:00', 30, 100, 59.8, 1100, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 9),
(348, '2016-02-13 00:00:00', 30, 100, 59.8, 1000, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 10),
(349, '2016-03-13 00:00:00', 30, 100, 59.8, 900, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 11),
(350, '2016-04-13 00:00:00', 30, 100, 59.8, 800, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 12),
(351, '2016-05-13 00:00:00', 30, 100, 59.8, 700, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 13),
(352, '2016-06-13 00:00:00', 30, 100, 59.8, 600, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 14),
(353, '2016-07-13 00:00:00', 30, 100, 59.8, 500, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 15),
(354, '2016-08-13 00:00:00', 30, 100, 59.8, 400, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 16),
(355, '2016-09-13 00:00:00', 30, 100, 59.8, 300, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 17),
(356, '2016-10-13 00:00:00', 30, 100, 59.8, 200, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 18),
(357, '2016-11-13 00:00:00', 30, 100, 59.8, 100, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 19),
(358, '2016-12-13 00:00:00', 30, 100, 59.8, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 25, 20),
(359, '2015-03-07 00:00:00', 0, 240, NULL, 240, 240, NULL, 0, 0, 0, 0, 0, 0, 26, 0),
(360, '2015-05-06 00:00:00', 30, 20, 6.94452, 220, 26.9445, NULL, 0, 1, 0, 298.936, 20, 6.94452, 26, 1),
(361, '2015-06-06 00:00:00', 30, 20, 7.176, 200, 27.176, NULL, 0, 1, 0, 271.76, 20, 7.176, 26, 2),
(362, '2015-07-06 00:00:00', 30, 20, 7.176, 180, 27.176, NULL, 0, 1, 0, 244.584, 20, 7.176, 26, 3),
(363, '2015-08-06 00:00:00', 30, 20, 7.176, 160, 27.176, NULL, 0, 1, 0, 217.408, 20, 7.176, 26, 4),
(364, '2015-09-06 00:00:00', 30, 20, 7.176, 140, 27.176, '', 0, 1, 0, 190.232, 20, 7.176, 26, 5),
(365, '2015-10-06 00:00:00', 30, 20, 7.176, 120, 27.176, NULL, 0, 1, 0, 163.056, 20, 7.176, 26, 6),
(366, '2015-11-06 00:00:00', 30, 20, 7.176, 100, 27.176, NULL, 0, 1, 0, 135.88, 20, 7.176, 26, 7),
(367, '2015-12-06 00:00:00', 30, 20, 7.176, 80, 27.176, NULL, 32, 1, 16, 108.704, 20, 7.176, 26, 8),
(368, '2016-01-06 00:00:00', 30, 20, 7.176, 60, 27.176, NULL, 0, 1, 0, 81.528, 20, 7.176, 26, 9),
(369, '2016-02-06 00:00:00', 30, 20, 7.176, 40, 27.176, NULL, 0, 1, 0, 54.352, 20, 7.176, 26, 10),
(370, '2016-03-06 00:00:00', 30, 20, 7.176, 20, 27.176, NULL, 0, 1, 0, 27.176, 20, 7.176, 26, 11),
(371, '2016-04-06 00:00:00', 30, 20, 7.176, 0, 27.176, NULL, 0, 1, 0, -0.00000000000000355271, 20, 7.176, 26, 12),
(372, '2015-06-07 00:00:00', 0, 200, NULL, 200, 200, NULL, 0, 0, 0, 0, 0, 0, 27, 0),
(373, '2015-06-15 00:00:00', 8, 16.6667, 1.59467, 183.333, 18.2613, NULL, 0, 0, 0, 0, 0, 0, 27, 1),
(374, '2015-07-15 00:00:00', 30, 16.6667, 5.98, 166.667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 2),
(375, '2015-08-15 00:00:00', 30, 16.6667, 5.98, 150, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 3),
(376, '2015-09-15 00:00:00', 30, 16.6667, 5.98, 133.333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 4),
(377, '2015-10-15 00:00:00', 30, 16.6667, 5.98, 116.667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 5),
(378, '2015-11-15 00:00:00', 30, 16.6667, 5.98, 100, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 6),
(379, '2015-12-15 00:00:00', 30, 16.6667, 5.98, 83.3333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 7),
(380, '2016-01-15 00:00:00', 30, 16.6667, 5.98, 66.6667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 8),
(381, '2016-02-15 00:00:00', 30, 16.6667, 5.98, 50, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 9),
(382, '2016-03-15 00:00:00', 30, 16.6667, 5.98, 33.3333, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 10),
(383, '2016-04-15 00:00:00', 30, 16.6667, 5.98, 16.6667, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 11),
(384, '2016-05-15 00:00:00', 30, 16.6667, 5.98, 0.00000000000000710543, 22.6467, NULL, 0, 0, 0, 0, 0, 0, 27, 12),
(385, '2015-06-07 00:00:00', 0, 2000, NULL, 2000, 2000, NULL, 0, 0, 0, 0, 0, 0, 28, 0),
(386, '2015-06-18 00:00:00', 11, 166.667, 21.9267, 1833.33, 188.593, NULL, 0, 0, 0, 0, 0, 0, 28, 1),
(387, '2015-07-18 00:00:00', 30, 166.667, 59.8, 1666.67, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 2),
(388, '2015-08-18 00:00:00', 30, 166.667, 59.8, 1500, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 3),
(389, '2015-09-18 00:00:00', 30, 166.667, 59.8, 1333.33, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 4),
(390, '2015-10-18 00:00:00', 30, 166.667, 59.8, 1166.67, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 5),
(391, '2015-11-18 00:00:00', 30, 166.667, 59.8, 1000, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 6),
(392, '2015-12-18 00:00:00', 30, 166.667, 59.8, 833.333, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 7),
(393, '2016-01-18 00:00:00', 30, 166.667, 59.8, 666.667, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 8),
(394, '2016-02-18 00:00:00', 30, 166.667, 59.8, 500, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 9),
(395, '2016-03-18 00:00:00', 30, 166.667, 59.8, 333.333, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 10),
(396, '2016-04-18 00:00:00', 30, 166.667, 59.8, 166.667, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 11),
(397, '2016-05-18 00:00:00', 30, 166.667, 59.8, -0.00000000000017053, 226.467, NULL, 0, 0, 0, 0, 0, 0, 28, 12),
(398, '2015-03-07 00:00:00', 0, 2000, NULL, 2000, 2000, NULL, 0, 0, 0, 0, 0, 0, 29, 0),
(399, '2015-06-03 00:00:00', 27, 166.667, 52.0839, 1833.33, 218.751, 'ដសថសដថ', 3, 1, 3, 2491.14, 166.667, 52.0839, 29, 1),
(400, '2015-07-03 00:00:00', 30, 166.667, 59.8, 1666.67, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 2),
(401, '2015-08-03 00:00:00', 30, 166.667, 59.8, 1500, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 3),
(402, '2015-09-03 00:00:00', 30, 166.667, 59.8, 1333.33, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 4),
(403, '2015-10-03 00:00:00', 30, 166.667, 59.8, 1166.67, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 5),
(404, '2015-11-03 00:00:00', 30, 166.667, 59.8, 1000, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 6),
(405, '2015-12-03 00:00:00', 30, 166.667, 59.8, 833.333, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 7),
(406, '2016-01-03 00:00:00', 30, 166.667, 59.8, 666.667, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 8),
(407, '2016-02-03 00:00:00', 30, 166.667, 59.8, 500, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 9),
(408, '2016-03-03 00:00:00', 30, 166.667, 59.8, 333.333, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 10),
(409, '2016-04-03 00:00:00', 30, 166.667, 59.8, 166.667, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 11),
(410, '2016-05-03 00:00:00', 30, 166.667, 59.8, -0.00000000000017053, 226.467, NULL, 0, 0, 0, 0, 0, 0, 29, 12),
(411, '2015-06-10 00:00:00', 0, 1200, NULL, 1200, 1200, NULL, 0, 0, 0, 0, 0, 0, 30, 0),
(412, '2015-06-14 00:00:00', 4, 120, 4.784, 1080, 124.784, NULL, 0, 0, 0, 0, 0, 0, 30, 1),
(413, '2015-07-14 00:00:00', 30, 120, 35.88, 960, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 2),
(414, '2015-08-14 00:00:00', 30, 120, 35.88, 840, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 3),
(415, '2015-09-14 00:00:00', 30, 120, 35.88, 720, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 4),
(416, '2015-10-14 00:00:00', 30, 120, 35.88, 600, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 5),
(417, '2015-11-14 00:00:00', 30, 120, 35.88, 480, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 6),
(418, '2015-12-14 00:00:00', 30, 120, 35.88, 360, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 7),
(419, '2016-01-14 00:00:00', 30, 120, 35.88, 240, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 8),
(420, '2016-02-14 00:00:00', 30, 120, 35.88, 120, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 9),
(421, '2016-03-14 00:00:00', 30, 120, 35.88, 0, 155.88, NULL, 0, 0, 0, 0, 0, 0, 30, 10);

-- --------------------------------------------------------

--
-- Table structure for table `cln_locations`
--

CREATE TABLE IF NOT EXISTS `cln_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci,
  `address` text COLLATE utf8_unicode_ci,
  `phone` text COLLATE utf8_unicode_ci,
  `fax` text COLLATE utf8_unicode_ci,
  `email` text COLLATE utf8_unicode_ci,
  `receive_stock_alert` text COLLATE utf8_unicode_ci,
  `stock_alert_email` text COLLATE utf8_unicode_ci,
  `timezone` text COLLATE utf8_unicode_ci,
  `mailchimp_api_key` text COLLATE utf8_unicode_ci,
  `enable_credit_card_processing` text COLLATE utf8_unicode_ci,
  `merchant_id` text COLLATE utf8_unicode_ci,
  `merchant_password` text COLLATE utf8_unicode_ci,
  `default_tax_1_rate` text COLLATE utf8_unicode_ci,
  `default_tax_1_name` text COLLATE utf8_unicode_ci,
  `default_tax_2_rate` text COLLATE utf8_unicode_ci,
  `default_tax_2_name` text COLLATE utf8_unicode_ci,
  `default_tax_2_cumulative` text COLLATE utf8_unicode_ci,
  `default_tax_3_rate` text COLLATE utf8_unicode_ci,
  `default_tax_3_name` text COLLATE utf8_unicode_ci,
  `default_tax_4_rate` text COLLATE utf8_unicode_ci,
  `default_tax_4_name` text COLLATE utf8_unicode_ci,
  `default_tax_5_rate` text COLLATE utf8_unicode_ci,
  `default_tax_5_name` text COLLATE utf8_unicode_ci,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`location_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cln_locations`
--

INSERT INTO `cln_locations` (`location_id`, `name`, `address`, `phone`, `fax`, `email`, `receive_stock_alert`, `stock_alert_email`, `timezone`, `mailchimp_api_key`, `enable_credit_card_processing`, `merchant_id`, `merchant_password`, `default_tax_1_rate`, `default_tax_1_name`, `default_tax_2_rate`, `default_tax_2_name`, `default_tax_2_cumulative`, `default_tax_3_rate`, `default_tax_3_name`, `default_tax_4_rate`, `default_tax_4_name`, `default_tax_5_rate`, `default_tax_5_name`, `deleted`) VALUES
(1, 'P.Y.S', 'ភូមិ៣ សង្កាត់ស្មាច់មានជ័យ ក្រុងខេមរភូមិន្ទ ខេត្តកោះកុង', '081-377-737 / 092-377-737 <br/> 066-356-789 / 035-6357-333', '', 'vannakpanha.mao@gmail.com', '0', '', 'Asia/Phnom_Penh', '', '0', 'vannakpanha.mao', '12345678', NULL, 'Sales Tax', NULL, 'Sales Tax 2', '0', NULL, '', NULL, '', NULL, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_location_items`
--

CREATE TABLE IF NOT EXISTS `cln_location_items` (
  `location_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cost_price` decimal(23,10) DEFAULT NULL,
  `unit_price` decimal(23,10) DEFAULT NULL,
  `promo_price` decimal(23,10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quantity` decimal(23,10) DEFAULT '0.0000000000',
  `reorder_level` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_id`,`item_id`),
  KEY `cln_location_items_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_location_items_taxes`
--

CREATE TABLE IF NOT EXISTS `cln_location_items_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(16,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`location_id`,`item_id`,`name`,`percent`),
  KEY `cln_location_items_taxes_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_location_items_tier_prices`
--

CREATE TABLE IF NOT EXISTS `cln_location_items_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_id`,`location_id`),
  KEY `cln_location_items_tier_prices_ibfk_2` (`location_id`),
  KEY `cln_location_items_tier_prices_ibfk_3` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_location_item_kits`
--

CREATE TABLE IF NOT EXISTS `cln_location_item_kits` (
  `location_id` int(11) NOT NULL,
  `item_kit_id` int(11) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT NULL,
  `cost_price` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_id`,`item_kit_id`),
  KEY `cln_location_item_kits_ibfk_2` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_location_item_kits_taxes`
--

CREATE TABLE IF NOT EXISTS `cln_location_item_kits_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(16,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`location_id`,`item_kit_id`,`name`,`percent`),
  KEY `cln_location_item_kits_taxes_ibfk_2` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_location_item_kits_tier_prices`
--

CREATE TABLE IF NOT EXISTS `cln_location_item_kits_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_kit_id`,`location_id`),
  KEY `cln_location_item_kits_tier_prices_ibfk_2` (`location_id`),
  KEY `cln_location_item_kits_tier_prices_ibfk_3` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_modules`
--

CREATE TABLE IF NOT EXISTS `cln_modules` (
  `name_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(10) NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cln_modules`
--

INSERT INTO `cln_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `icon`, `module_id`) VALUES
('module_config', 'module_config_desc', 100, 'cogs', 'config'),
('module_customers', 'module_customers_desc', 10, 'group', 'customers'),
('module_employees', 'module_employees_desc', 80, 'user', 'employees'),
('module_expenses', 'module_expenses_desc', 75, 'filter', 'expenses'),
('module_giftcards', 'module_giftcards_desc', 90, 'credit-card', 'giftcards'),
('module_item_kits', 'module_item_kits_desc', 30, 'inbox', 'item_kits'),
('module_items', 'module_items_desc', 20, 'table', 'items'),
('module_loans', 'module_loan_desc', 15, 'usd', 'loans'),
('module_locations', 'module_locations_desc', 110, 'home', 'locations'),
('module_pawns', 'module_pawn_desc', 17, 'tasks', 'pawns'),
('module_receivings', 'module_receivings_desc', 60, 'cloud-download', 'receivings'),
('module_reports', 'module_reports_desc', 50, 'bar-chart-o', 'reports'),
('module_sales', 'module_sales_desc', 70, 'shopping-cart', 'sales'),
('module_suppliers', 'module_suppliers_desc', 40, 'download', 'suppliers');

-- --------------------------------------------------------

--
-- Table structure for table `cln_modules_actions`
--

CREATE TABLE IF NOT EXISTS `cln_modules_actions` (
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `action_name_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`action_id`,`module_id`),
  KEY `cln_modules_actions_ibfk_1` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cln_modules_actions`
--

INSERT INTO `cln_modules_actions` (`action_id`, `module_id`, `action_name_key`, `sort`) VALUES
('add_update', 'customers', 'module_action_add_update', 1),
('add_update', 'employees', 'module_action_add_update', 130),
('add_update', 'expenses', 'module_action_add_update', 185),
('add_update', 'giftcards', 'module_action_add_update', 200),
('add_update', 'item_kits', 'module_action_add_update', 70),
('add_update', 'items', 'module_action_add_update', 40),
('add_update', 'loans', 'module_action_add_update', 22),
('add_update', 'locations', 'module_action_add_update', 240),
('add_update', 'pawns', 'module_action_add_update', 25),
('add_update', 'suppliers', 'module_action_add_update', 100),
('assign_all_locations', 'employees', 'module_action_assign_all_locations', 151),
('delete', 'customers', 'module_action_delete', 20),
('delete', 'employees', 'module_action_delete', 140),
('delete', 'expenses', 'module_action_delete', 186),
('delete', 'giftcards', 'module_action_delete', 210),
('delete', 'item_kits', 'module_action_delete', 80),
('delete', 'items', 'module_action_delete', 50),
('delete', 'loans', 'module_action_delete', 23),
('delete', 'locations', 'module_action_delete', 250),
('delete', 'pawns', 'module_action_delete', 27),
('delete', 'suppliers', 'module_action_delete', 110),
('delete_sale', 'sales', 'module_action_delete_sale', 230),
('delete_suspended_sale', 'sales', 'module_action_delete_suspended_sale', 181),
('delete_taxes', 'sales', 'module_action_delete_taxes', 182),
('edit_sale', 'sales', 'module_edit_sale', 190),
('edit_sale_price', 'sales', 'module_edit_sale_price', 170),
('edit_store_account_balance', 'customers', 'customers_edit_store_account_balance', 31),
('give_discount', 'sales', 'module_give_discount', 180),
('search', 'customers', 'module_action_search_customers', 30),
('search', 'employees', 'module_action_search_employees', 150),
('search', 'expenses', 'module_action_search_expenses', 189),
('search', 'giftcards', 'module_action_search_giftcards', 220),
('search', 'item_kits', 'module_action_search_item_kits', 90),
('search', 'items', 'module_action_search_items', 60),
('search', 'loans', 'module_action_search_loans', 24),
('search', 'locations', 'module_action_search_locations', 260),
('search', 'pawns', 'module_action_search_pawns', 28),
('search', 'suppliers', 'module_action_search_suppliers', 120),
('see_cost_price', 'item_kits', 'module_see_cost_price', 91),
('see_cost_price', 'items', 'module_see_cost_price', 61),
('show_cost_price', 'reports', 'reports_show_cost_price', 290),
('show_profit', 'reports', 'reports_show_profit', 280),
('view_categories', 'reports', 'reports_categories', 100),
('view_commissions', 'reports', 'reports_commission', 111),
('view_customers', 'reports', 'reports_customers', 120),
('view_deleted_sales', 'reports', 'reports_deleted_sales', 130),
('view_discounts', 'reports', 'reports_discounts', 140),
('view_employees', 'reports', 'reports_employees', 150),
('view_expenses', 'reports', 'reports_expenses', 155),
('view_giftcards', 'reports', 'reports_giftcards', 160),
('view_inventory_reports', 'reports', 'reports_inventory_reports', 170),
('view_item_kits', 'reports', 'module_item_kits', 180),
('view_items', 'reports', 'reports_items', 190),
('view_loans', 'reports', 'reports_loans', 110),
('view_pawns', 'reports', 'reports_pawns', 130),
('view_payments', 'reports', 'reports_payments', 200),
('view_profit_and_loss', 'reports', 'reports_profit_and_loss', 210),
('view_receivings', 'reports', 'reports_receivings', 220),
('view_register_log', 'reports', 'reports_register_log_title', 230),
('view_sales', 'reports', 'reports_sales', 240),
('view_sales_generator', 'reports', 'reports_sales_generator', 110),
('view_store_account', 'reports', 'reports_store_account', 250),
('view_suppliers', 'reports', 'reports_suppliers', 260),
('view_suspended_sales', 'reports', 'reports_suspended_sales', 261),
('view_taxes', 'reports', 'reports_taxes', 270);

-- --------------------------------------------------------

--
-- Table structure for table `cln_pawn`
--

CREATE TABLE IF NOT EXISTS `cln_pawn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL DEFAULT '0',
  `rate` float NOT NULL DEFAULT '0',
  `is_loan` int(11) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `pay_type` int(11) NOT NULL DEFAULT '0',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `currency` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `product_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `status` int(11) DEFAULT '0',
  `deleted` int(11) DEFAULT '0',
  `check_fine` int(11) DEFAULT '0',
  `person_id` int(11) NOT NULL DEFAULT '0',
  `paid_rate` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_cln_loan_cln_customers1_idx` (`person_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cln_pawn`
--

INSERT INTO `cln_pawn` (`id`, `amount`, `rate`, `is_loan`, `duration`, `pay_type`, `start_date`, `end_date`, `currency`, `product_name`, `comments`, `status`, `deleted`, `check_fine`, `person_id`, `paid_rate`) VALUES
(1, 2000, 0.2, 0, 10, 1, '2015-06-14 00:00:00', '2015-06-24 00:00:00', 'usd', 'MB1200', 'Report Lulu', 0, 0, 0, 41, 0),
(2, 2000, 0.8, 0, 10, 2, '2015-05-01 00:00:00', '2016-03-01 00:00:00', 'usd', '', '', 0, 0, 0, 24, 0),
(3, 2500, 0.2, 1, 10, 2, '2015-05-06 00:00:00', '2016-03-06 00:00:00', 'usd', '', '', 0, 0, 0, 26, 0),
(4, 2100, 0.2, 0, 15, 3, '2015-05-10 00:00:00', '2015-05-25 00:00:00', 'usd', '', '', 0, 0, 0, 28, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_pawn_schedule`
--

CREATE TABLE IF NOT EXISTS `cln_pawn_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_date` datetime NOT NULL,
  `number_day` int(11) DEFAULT '0',
  `pay_principle` float DEFAULT '0',
  `pay_rate` float DEFAULT '0',
  `pay_total` float DEFAULT '0',
  `note` text COLLATE utf8_unicode_ci,
  `pay_fine` float DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `late` int(11) DEFAULT '0',
  `paid_princ` float DEFAULT '0',
  `borrow_more` float DEFAULT '0',
  `check_fine` int(11) DEFAULT '0',
  `paid` float DEFAULT '0',
  `pawn_id` int(11) NOT NULL DEFAULT '0',
  `key` int(11) NOT NULL DEFAULT '0',
  `paid_rate` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_cln_pawn_schedule_cln_pawn1_idx` (`pawn_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

--
-- Dumping data for table `cln_pawn_schedule`
--

INSERT INTO `cln_pawn_schedule` (`id`, `pay_date`, `number_day`, `pay_principle`, `pay_rate`, `pay_total`, `note`, `pay_fine`, `status`, `late`, `paid_princ`, `borrow_more`, `check_fine`, `paid`, `pawn_id`, `key`, `paid_rate`) VALUES
(1, '2015-06-14 00:00:00', 0, 2000, 40, 2040, NULL, 0, 1, 0, 2000, 0, 0, 0, 1, 1, 40),
(2, '2015-06-24 00:00:00', 10, 2000, 0, 2000, NULL, 1, 1, 1, 0, 0, 1, 0, 1, 2, 0),
(3, '2015-05-01 00:00:00', 0, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 1, 0),
(4, '2015-06-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(5, '2015-07-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 3, 0),
(6, '2015-08-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 4, 0),
(7, '2015-09-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 5, 0),
(8, '2015-10-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 6, 0),
(9, '2015-11-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 7, 0),
(10, '2015-12-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 8, 0),
(11, '2016-01-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 9, 0),
(12, '2016-02-01 00:00:00', 30, 2000, 16, 2016, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 10, 0),
(13, '2016-03-01 00:00:00', 30, 2000, 0, 2000, NULL, 0, 0, 0, 0, 0, 0, 0, 2, 11, 0),
(14, '2015-05-06 00:00:00', 0, 2500, 5, 255, NULL, 0, 0, 0, 0, 0, 0, 0, 3, 1, 0),
(15, '2015-06-06 00:00:00', 30, 2500, 4.5, 254.5, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 2, 0),
(16, '2015-07-06 00:00:00', 30, 2250, 4, 254, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 3, 0),
(17, '2015-08-06 00:00:00', 30, 2000, 3.5, 253.5, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 4, 0),
(18, '2015-09-06 00:00:00', 30, 1750, 3, 253, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 5, 0),
(19, '2015-10-06 00:00:00', 30, 1500, 2.5, 252.5, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 6, 0),
(20, '2015-11-06 00:00:00', 30, 1250, 2, 252, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 7, 0),
(21, '2015-12-06 00:00:00', 30, 1000, 1.5, 251.5, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 8, 0),
(22, '2016-01-06 00:00:00', 30, 750, 1, 251, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 9, 0),
(23, '2016-02-06 00:00:00', 30, 500, 0.5, 250.5, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 10, 0),
(24, '2016-03-06 00:00:00', 30, 250, 0, 250, NULL, 0, 0, 0, 250, 0, 0, 0, 3, 11, 0),
(25, '2015-05-10 00:00:00', 0, 2100, 2.1, 2102.1, NULL, 0, 0, 0, 0, 0, 0, 0, 4, 1, 0),
(26, '2015-05-25 00:00:00', 15, 2100, 0, 2100, NULL, 0, 0, 0, 0, 0, 0, 0, 4, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_people`
--

CREATE TABLE IF NOT EXISTS `cln_people` (
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `identity_no` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(10) DEFAULT NULL,
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`person_id`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `email` (`email`),
  KEY `cln_people_ibfk_1` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Dumping data for table `cln_people`
--

INSERT INTO `cln_people` (`first_name`, `last_name`, `gender`, `dob`, `identity_no`, `age`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `image_id`, `person_id`) VALUES
('ម៉ៅ', 'វណ្ណៈបញ្ញា', 'male', '1993-08-12', 'CDG000001', 22, '089664044', 'vannakpanha.mao@gmail.com', 'BP 511 St. 371 Phum Tropeang Chhuk (Borey Sorla) Sangtak Tek Thla, Khan Saen Sokh, Phnom Penh', '0', '0', '0', '0', '0', 'សួស្តី!​ ខ្ញុំគឺបញ្ញា ខ្ញុំមកពីខេត្តកំពង់ចាម។ ខ្ញុំជាមនុស្សម្នាក់ដែលធ្វើការអភិវឌ្ឍកម្មវិធីនេះ។ ខ្ញុំសង្ឃឹមថាអ្នកពិតជាពេញចិត្តកម្មវិធីនេះ។', 0, 1),
('វ៉ា', 'គីមហុង', 'male', '1993-02-03', 'CDG000002', 22, '0292382028', 'gmkimhong@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 2),
('សិ', 'រស្មី', 'male', '1992-05-01', 'CDG000003', 23, '02928228028', 'reaksmey.se@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 3),
('សុីម', 'សុខគារ៉ា', 'male', '1993-01-03', 'CDG000004', 22, '02928092', 'sokeara.sim@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 4),
('ប៉ែន', 'ធីតា', 'male', '1992-01-03', 'CDG000005', 23, '029829282', 'thida.pen@gmail.com', 'N/A', '0', '0', '0', '0', '0', '', NULL, 5),
('សុខ', 'ស្រីចិន', 'female', '1992-02-01', 'CDG000006', 23, '0298383893', 'sreychin.sok@gmail.com', '', '0', '0', '0', '0', '0', '', NULL, 6),
('ចាន់', 'វ៉ានណេត', 'female', '1992-10-03', 'CDG000007', 23, '0292883903', 'vanneth.chan@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 7),
('សុខ', 'បញ្ញា', 'male', '1991-01-03', 'CDG000008', 24, '0929290292', 'sokpanha@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 8),
('យិន', 'ពិសិទ្ធ', 'male', '1990-02-20', 'CDG000009', 25, '012938393', 'piseth.yen@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 9),
('សុខ', 'សុភមង្គល', 'male', '1989-02-01', 'CDG000010', 26, '03829282', 'sopheakmonkol.sok@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 10),
('អៅ', 'ឆាយ', 'male', '1990-01-20', 'CDG000011', 25, '012493039', 'chhayao@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 11),
('រស់', 'រ៉ាឌី', 'male', '1992-10-12', 'CDG000012', 23, '01929302', 'rady.ros@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 12),
('ហែម', 'ឈីងឈីង', 'male', '1990-10-21', 'CDG000013', 25, '029198223', 'chhingchhing.hem@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 13),
('ចែត', 'ស្រីណាក់', 'male', '1990-10-21', 'CDG000014', 25, '01928292', 'sreynak.chet@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 14),
('លី', 'រតនា', 'male', '1990-12-10', 'CDG000015', 25, '0292928', 'rothana.ly@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 15),
('លី', 'សុវណ្ណបុរី', 'male', '1989-10-10', 'CDG000016', 26, '12345678', 'sovannborey.ly@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 16),
('ថុល', 'រិទ្ធី', 'male', '1980-10-10', 'CDG000017', 35, '12345678', 'rithy.thul', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 17),
('ផាន', 'សោរិន', 'male', '1990-09-12', 'CDG000018', 25, '12345678', 'saorin.phan', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 18),
('ឡាន', 'សុខលាក់', 'male', '1989-10-01', 'CDG000019', 26, '12345678', 'soleak.lanh@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 19),
('ម៉ៅ', 'ជីអេង', 'male', '1990-10-02', 'CDG000020', 25, '12345678', 'chyeng.mao', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 20),
('សុខ', 'សំណាង', 'male', '1990-01-20', 'CDG000021', 25, '09876543', 'somnang.sok@gmail.com', 'N/A', '0', '0', '0', '0', '0', 'N/A', NULL, 21),
('គង់', 'មួយគា', 'female', '1990-01-10', 'C000001', 25, '12345678', 'mouykea.kong@gmail.com', 'N/A', '', '', '', '', '', 'N/A', NULL, 22),
('សីុម', 'ឆេងឡាយ', 'female', '1993-01-09', 'C000002', 22, '12345678', 'chhenglay.sim@gmail.com', 'N/A', '', '', '', '', '', 'N/A', NULL, 23),
('វ៉ា', 'ម៉េងទី', 'male', '1993-12-10', 'C000003', 22, '12345678', 'mengty.va@gmail.com', 'N/A', '', '', '', '', '', 'N/A', NULL, 24),
('ចាន់', 'ឈុនឡេង', 'male', '1993-10-11', 'C000004', 22, '12345678', 'chhunleng.chan@gmail.com', 'N/A', '', '', '', '', '', 'N/A', NULL, 25),
('ប៉ូ', 'សៀកឡេង', 'male', '1993-10-11', 'C000005', 22, '12345678', 'seakleng.po@gmail.com', 'N/A', '', '', '', '', '', 'N/A', NULL, 26),
('ហួត', 'ឈុនលី', 'male', '1993-10-11', 'C000006', 22, '12345678', 'chhunly.hout@gmail.com', '', '', '', '', '', '', '', NULL, 27),
('លាង', 'ឡៅជ្រា', 'male', '1993-10-11', 'C000007', 22, '12345678', 'laochrea.lao@gmail.com', '', '', '', '', '', '', '', NULL, 28),
('ឡាក់', 'សុកទី', 'female', '1993-10-11', 'C000008', 22, '12345678', 'sokthy.lak@gmail.com', 'N/A', '', '', '', '', '', '\n\n', NULL, 29),
('ម៉ម', 'មួយឆេង', 'female', '1993-10-11', 'C000009', 22, '12345678', 'mouycheng.morm@gmail.com', '', '', '', '', '', '', '', NULL, 30),
('គឿន', 'បុន្នី', 'female', '1993-10-11', 'C0000010', 22, '12345678', 'bunny.kouern@gmail.com', '', '', '', '', '', '', '', NULL, 31),
('គឿន', 'បុណ្ណា', 'male', '1993-10-11', 'C0000011', 22, '123456789', 'bunna.koeurn@gmail.com', '', '', '', '', '', '', '', NULL, 32),
('សែ', 'គិមវាសនា', 'male', '1993-10-11', 'C0000012', 22, '12345678', 'veasna.se@gmail.com', '', '', '', '', '', '', '', NULL, 33),
('លី', 'ម៉ារី', 'female', '1993-10-11', 'C0000013', 22, '12345678', 'mary.ly@gmail.com', '', '', '', '', '', '', '', NULL, 34),
('ម៉ៅ', 'ចាន់នីតា', 'female', '1993-10-11', 'C0000014', 22, '12345678', 'mao.channyta@gmail.com', '', '', '', '', '', '', '', NULL, 35),
('ជៀប', 'សុកកញ្ញា', 'female', '1993-10-11', 'C000015', 22, '12345678', 'sokkagna.cheap@gmail.com', '', '', '', '', '', '', '', NULL, 36),
('ណន់', 'ស្រីលក្ខណ៍', 'female', '1993-10-11', 'C0000016', 22, '12345678', 'sreyleak.non@gmail.com', '', '', '', '', '', '', '', NULL, 37),
('សីុន', 'ណៃសុីម', 'female', '1993-10-11', 'C0000017', 22, '12345678', 'naisim.sin@gmail.com', '', '', '', '', '', '', '', NULL, 38),
('មាស', 'ស្រីលក្ខណ៏', 'female', '1993-10-11', 'C000018', 22, '12345678', 'sreyleak.meas@gmail.com', '', '', '', '', '', '', '', NULL, 39),
('ឌឹម', 'បុនធឿន', 'male', '1993-10-11', 'C0000019', 22, '12345678', 'bunthoeurn@gmail.com', '', '', '', '', '', '', '', NULL, 40),
('សៅ', 'វ៉េត', 'male', '1993-10-11', 'C000020', 22, '12345678', 'vet.sao@gmail.com', '', '', '', '', '', '', '', NULL, 41),
('ហេង', 'មករា', 'male', '1993-10-11', 'C000021', 22, '12345678', 'makara@gmail.com', '', '', '', '', '', '', '', NULL, 42);

-- --------------------------------------------------------

--
-- Table structure for table `cln_permissions`
--

CREATE TABLE IF NOT EXISTS `cln_permissions` (
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cln_permissions`
--

INSERT INTO `cln_permissions` (`module_id`, `person_id`) VALUES
('config', 1),
('customers', 1),
('employees', 1),
('expenses', 1),
('giftcards', 1),
('item_kits', 1),
('items', 1),
('loans', 1),
('locations', 1),
('pawns', 1),
('receivings', 1),
('reports', 1),
('sales', 1),
('suppliers', 1),
('customers', 3),
('item_kits', 3),
('loans', 3),
('pawns', 3),
('customers', 4),
('loans', 4),
('pawns', 4),
('item_kits', 5),
('reports', 5),
('suppliers', 5),
('customers', 6),
('loans', 6),
('suppliers', 6),
('customers', 7),
('loans', 7),
('pawns', 7),
('items', 8),
('pawns', 8),
('customers', 9),
('loans', 9),
('reports', 9),
('customers', 10),
('item_kits', 10),
('loans', 10),
('pawns', 10),
('customers', 11),
('loans', 11),
('pawns', 11),
('customers', 12),
('item_kits', 12),
('loans', 12),
('customers', 13),
('items', 13),
('loans', 13),
('pawns', 13),
('customers', 14),
('loans', 14),
('pawns', 14),
('customers', 15),
('items', 15),
('loans', 15),
('pawns', 15),
('customers', 16),
('pawns', 16),
('customers', 17),
('pawns', 17),
('customers', 18),
('loans', 18),
('customers', 19),
('loans', 19),
('pawns', 19),
('customers', 20),
('pawns', 20),
('customers', 21),
('loans', 21);

-- --------------------------------------------------------

--
-- Table structure for table `cln_permissions_actions`
--

CREATE TABLE IF NOT EXISTS `cln_permissions_actions` (
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(11) NOT NULL,
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`,`action_id`),
  KEY `cln_permissions_actions_ibfk_2` (`person_id`),
  KEY `cln_permissions_actions_ibfk_3` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cln_permissions_actions`
--

INSERT INTO `cln_permissions_actions` (`module_id`, `person_id`, `action_id`) VALUES
('customers', 1, 'add_update'),
('customers', 1, 'delete'),
('customers', 1, 'edit_store_account_balance'),
('customers', 1, 'search'),
('employees', 1, 'add_update'),
('employees', 1, 'assign_all_locations'),
('employees', 1, 'delete'),
('employees', 1, 'search'),
('giftcards', 1, 'add_update'),
('giftcards', 1, 'delete'),
('giftcards', 1, 'search'),
('item_kits', 1, 'add_update'),
('item_kits', 1, 'delete'),
('item_kits', 1, 'search'),
('item_kits', 1, 'see_cost_price'),
('items', 1, 'add_update'),
('items', 1, 'delete'),
('items', 1, 'search'),
('items', 1, 'see_cost_price'),
('loans', 1, 'add_update'),
('loans', 1, 'delete'),
('loans', 1, 'search'),
('locations', 1, 'add_update'),
('locations', 1, 'delete'),
('locations', 1, 'search'),
('pawns', 1, 'add_update'),
('pawns', 1, 'delete'),
('pawns', 1, 'search'),
('reports', 1, 'show_cost_price'),
('reports', 1, 'show_profit'),
('reports', 1, 'view_categories'),
('reports', 1, 'view_commissions'),
('reports', 1, 'view_customers'),
('reports', 1, 'view_deleted_sales'),
('reports', 1, 'view_discounts'),
('reports', 1, 'view_employees'),
('reports', 1, 'view_giftcards'),
('reports', 1, 'view_inventory_reports'),
('reports', 1, 'view_item_kits'),
('reports', 1, 'view_items'),
('reports', 1, 'view_loans'),
('reports', 1, 'view_pawns'),
('reports', 1, 'view_payments'),
('reports', 1, 'view_profit_and_loss'),
('reports', 1, 'view_receivings'),
('reports', 1, 'view_register_log'),
('reports', 1, 'view_sales'),
('reports', 1, 'view_sales_generator'),
('reports', 1, 'view_store_account'),
('reports', 1, 'view_suppliers'),
('reports', 1, 'view_suspended_sales'),
('reports', 1, 'view_taxes'),
('sales', 1, 'delete_sale'),
('sales', 1, 'delete_suspended_sale'),
('sales', 1, 'delete_taxes'),
('sales', 1, 'edit_sale'),
('sales', 1, 'edit_sale_price'),
('sales', 1, 'give_discount'),
('suppliers', 1, 'add_update'),
('suppliers', 1, 'delete'),
('suppliers', 1, 'search'),
('customers', 2, 'add_update'),
('customers', 2, 'delete'),
('customers', 2, 'edit_store_account_balance'),
('customers', 2, 'search'),
('loans', 2, 'add_update'),
('loans', 2, 'delete'),
('loans', 2, 'search'),
('pawns', 2, 'add_update'),
('pawns', 2, 'delete'),
('pawns', 2, 'search'),
('customers', 3, 'add_update'),
('customers', 3, 'delete'),
('customers', 3, 'edit_store_account_balance'),
('customers', 3, 'search'),
('item_kits', 3, 'add_update'),
('item_kits', 3, 'delete'),
('item_kits', 3, 'search'),
('item_kits', 3, 'see_cost_price'),
('loans', 3, 'add_update'),
('loans', 3, 'delete'),
('loans', 3, 'search'),
('pawns', 3, 'add_update'),
('pawns', 3, 'delete'),
('pawns', 3, 'search'),
('customers', 4, 'add_update'),
('customers', 4, 'delete'),
('customers', 4, 'edit_store_account_balance'),
('customers', 4, 'search'),
('loans', 4, 'add_update'),
('loans', 4, 'delete'),
('loans', 4, 'search'),
('pawns', 4, 'add_update'),
('pawns', 4, 'delete'),
('pawns', 4, 'search'),
('item_kits', 5, 'add_update'),
('item_kits', 5, 'delete'),
('item_kits', 5, 'search'),
('item_kits', 5, 'see_cost_price'),
('reports', 5, 'show_cost_price'),
('reports', 5, 'show_profit'),
('reports', 5, 'view_categories'),
('reports', 5, 'view_commissions'),
('reports', 5, 'view_customers'),
('reports', 5, 'view_deleted_sales'),
('reports', 5, 'view_discounts'),
('reports', 5, 'view_employees'),
('reports', 5, 'view_giftcards'),
('reports', 5, 'view_inventory_reports'),
('reports', 5, 'view_item_kits'),
('reports', 5, 'view_items'),
('reports', 5, 'view_payments'),
('reports', 5, 'view_profit_and_loss'),
('reports', 5, 'view_receivings'),
('reports', 5, 'view_register_log'),
('reports', 5, 'view_sales'),
('reports', 5, 'view_sales_generator'),
('reports', 5, 'view_store_account'),
('reports', 5, 'view_suppliers'),
('reports', 5, 'view_suspended_sales'),
('reports', 5, 'view_taxes'),
('suppliers', 5, 'add_update'),
('suppliers', 5, 'delete'),
('suppliers', 5, 'search'),
('customers', 6, 'add_update'),
('customers', 6, 'delete'),
('customers', 6, 'edit_store_account_balance'),
('customers', 6, 'search'),
('loans', 6, 'add_update'),
('loans', 6, 'delete'),
('loans', 6, 'search'),
('suppliers', 6, 'add_update'),
('suppliers', 6, 'delete'),
('suppliers', 6, 'search'),
('customers', 7, 'add_update'),
('customers', 7, 'delete'),
('customers', 7, 'edit_store_account_balance'),
('customers', 7, 'search'),
('loans', 7, 'add_update'),
('loans', 7, 'delete'),
('loans', 7, 'search'),
('pawns', 7, 'add_update'),
('pawns', 7, 'delete'),
('pawns', 7, 'search'),
('items', 8, 'add_update'),
('items', 8, 'delete'),
('items', 8, 'search'),
('items', 8, 'see_cost_price'),
('pawns', 8, 'add_update'),
('pawns', 8, 'delete'),
('pawns', 8, 'search'),
('customers', 9, 'add_update'),
('customers', 9, 'delete'),
('customers', 9, 'edit_store_account_balance'),
('customers', 9, 'search'),
('loans', 9, 'add_update'),
('loans', 9, 'delete'),
('loans', 9, 'search'),
('reports', 9, 'show_cost_price'),
('reports', 9, 'show_profit'),
('reports', 9, 'view_categories'),
('reports', 9, 'view_commissions'),
('reports', 9, 'view_customers'),
('reports', 9, 'view_deleted_sales'),
('reports', 9, 'view_discounts'),
('reports', 9, 'view_employees'),
('reports', 9, 'view_giftcards'),
('reports', 9, 'view_inventory_reports'),
('reports', 9, 'view_item_kits'),
('reports', 9, 'view_items'),
('reports', 9, 'view_payments'),
('reports', 9, 'view_profit_and_loss'),
('reports', 9, 'view_receivings'),
('reports', 9, 'view_register_log'),
('reports', 9, 'view_sales'),
('reports', 9, 'view_sales_generator'),
('reports', 9, 'view_store_account'),
('reports', 9, 'view_suppliers'),
('reports', 9, 'view_suspended_sales'),
('reports', 9, 'view_taxes'),
('customers', 10, 'add_update'),
('customers', 10, 'delete'),
('customers', 10, 'edit_store_account_balance'),
('customers', 10, 'search'),
('item_kits', 10, 'add_update'),
('item_kits', 10, 'delete'),
('item_kits', 10, 'search'),
('item_kits', 10, 'see_cost_price'),
('loans', 10, 'add_update'),
('loans', 10, 'delete'),
('loans', 10, 'search'),
('pawns', 10, 'add_update'),
('pawns', 10, 'delete'),
('pawns', 10, 'search'),
('customers', 11, 'add_update'),
('customers', 11, 'delete'),
('customers', 11, 'edit_store_account_balance'),
('customers', 11, 'search'),
('loans', 11, 'add_update'),
('loans', 11, 'delete'),
('loans', 11, 'search'),
('pawns', 11, 'add_update'),
('pawns', 11, 'delete'),
('pawns', 11, 'search'),
('customers', 12, 'add_update'),
('customers', 12, 'delete'),
('customers', 12, 'edit_store_account_balance'),
('customers', 12, 'search'),
('item_kits', 12, 'add_update'),
('item_kits', 12, 'delete'),
('item_kits', 12, 'search'),
('item_kits', 12, 'see_cost_price'),
('loans', 12, 'add_update'),
('loans', 12, 'delete'),
('loans', 12, 'search'),
('customers', 13, 'add_update'),
('customers', 13, 'delete'),
('customers', 13, 'edit_store_account_balance'),
('customers', 13, 'search'),
('items', 13, 'add_update'),
('items', 13, 'delete'),
('items', 13, 'search'),
('items', 13, 'see_cost_price'),
('loans', 13, 'add_update'),
('loans', 13, 'delete'),
('loans', 13, 'search'),
('pawns', 13, 'add_update'),
('pawns', 13, 'delete'),
('pawns', 13, 'search'),
('customers', 14, 'add_update'),
('customers', 14, 'delete'),
('customers', 14, 'edit_store_account_balance'),
('customers', 14, 'search'),
('loans', 14, 'add_update'),
('loans', 14, 'delete'),
('loans', 14, 'search'),
('pawns', 14, 'add_update'),
('pawns', 14, 'delete'),
('pawns', 14, 'search'),
('customers', 15, 'add_update'),
('customers', 15, 'delete'),
('customers', 15, 'edit_store_account_balance'),
('customers', 15, 'search'),
('items', 15, 'add_update'),
('items', 15, 'delete'),
('items', 15, 'search'),
('items', 15, 'see_cost_price'),
('loans', 15, 'add_update'),
('loans', 15, 'delete'),
('loans', 15, 'search'),
('pawns', 15, 'add_update'),
('pawns', 15, 'delete'),
('pawns', 15, 'search'),
('customers', 16, 'add_update'),
('customers', 16, 'delete'),
('customers', 16, 'edit_store_account_balance'),
('customers', 16, 'search'),
('pawns', 16, 'add_update'),
('pawns', 16, 'delete'),
('pawns', 16, 'search'),
('customers', 17, 'add_update'),
('customers', 17, 'delete'),
('customers', 17, 'edit_store_account_balance'),
('customers', 17, 'search'),
('pawns', 17, 'add_update'),
('pawns', 17, 'delete'),
('pawns', 17, 'search'),
('customers', 18, 'add_update'),
('customers', 18, 'delete'),
('customers', 18, 'edit_store_account_balance'),
('customers', 18, 'search'),
('loans', 18, 'add_update'),
('loans', 18, 'delete'),
('loans', 18, 'search'),
('customers', 19, 'add_update'),
('customers', 19, 'delete'),
('customers', 19, 'edit_store_account_balance'),
('customers', 19, 'search'),
('loans', 19, 'add_update'),
('loans', 19, 'delete'),
('loans', 19, 'search'),
('pawns', 19, 'add_update'),
('pawns', 19, 'delete'),
('pawns', 19, 'search'),
('customers', 20, 'add_update'),
('customers', 20, 'delete'),
('customers', 20, 'edit_store_account_balance'),
('customers', 20, 'search'),
('pawns', 20, 'add_update'),
('pawns', 20, 'delete'),
('pawns', 20, 'search'),
('customers', 21, 'add_update'),
('customers', 21, 'delete'),
('customers', 21, 'edit_store_account_balance'),
('customers', 21, 'search'),
('loans', 21, 'add_update'),
('loans', 21, 'delete'),
('loans', 21, 'search');

-- --------------------------------------------------------

--
-- Table structure for table `cln_price_tiers`
--

CREATE TABLE IF NOT EXISTS `cln_price_tiers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_product_image`
--

CREATE TABLE IF NOT EXISTS `cln_product_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) DEFAULT NULL,
  `file_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_product_image_cln_loan1_idx` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_product_loan_attachment`
--

CREATE TABLE IF NOT EXISTS `cln_product_loan_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `file_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_product_image_copy1_cln_loan2` (`loan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

--
-- Dumping data for table `cln_product_loan_attachment`
--

INSERT INTO `cln_product_loan_attachment` (`id`, `loan_id`, `file_name`, `deleted`) VALUES
(1, 1, '1.jpg', 0),
(2, 2, '3.jpeg', 0),
(3, 3, '4.jpeg', 0),
(4, 4, '5.jpg', 0),
(5, 5, '6.jpg', 0),
(6, 6, '8.jpg', 0),
(7, 7, '8.jpg', 0),
(8, 8, '16.jpg', 0),
(9, 9, '18.jpeg', 0),
(10, 10, '18.jpeg', 0),
(11, 12, '10.jpg', 0),
(12, 13, '15.jpeg', 0),
(13, 14, '11.jpeg', 0),
(14, 15, '8.jpg', 0),
(15, 19, '7.jpg', 0),
(16, 23, '12.jpg', 0),
(17, 23, 'Employees Summary Report.csv', 0),
(19, 24, 'New-timeline.xlsx', 0),
(20, 25, 'Trainers Summary Report.csv', 0),
(21, 25, '9.jpg', 0),
(22, 25, 'Vannakpanha_Mao_Logo.psd', 0),
(23, 25, 'New-timeline.xlsx', 0),
(24, 26, 'database.php', 1),
(25, 26, 'online-arise-old-scanner.sql', 0),
(26, 26, 'online-arise.sql', 0),
(27, 26, '11165279_749467508499077_3974772951547284547_n.jpg', 0),
(28, 29, 'pawns_export.xlsx', 0),
(29, 29, '11262925_1610168919259684_723917718_n (1).jpg', 0),
(30, 30, 'maxresdefault.jpg', 0),
(31, 30, 'J7-278.JPG', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_product_pawn_attachment`
--

CREATE TABLE IF NOT EXISTS `cln_product_pawn_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pawn_id` int(11) NOT NULL,
  `file_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_product_image_copy1_cln_pawn2` (`pawn_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cln_product_pawn_attachment`
--

INSERT INTO `cln_product_pawn_attachment` (`id`, `pawn_id`, `file_name`, `deleted`) VALUES
(1, 4, '10270084_1610169095926333_1447864021_n.jpg', 1),
(2, 4, '11419973_1609798115963431_670864275_n.jpg', 0),
(3, 3, '10270084_1610169095926333_1447864021_n.jpg', 0),
(4, 3, '11262925_1610168919259684_723917718_n (1).jpg', 0),
(5, 4, '10270084_1610169095926333_1447864021_n.jpg', 0),
(6, 4, '11419973_1609798115963431_670864275_n.jpg', 1),
(7, 4, 'pawns_export.xlsx', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_receivings`
--

CREATE TABLE IF NOT EXISTS `cln_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_by` int(10) DEFAULT NULL,
  `suspended` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `transfer_to_location_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  KEY `location_id` (`location_id`),
  KEY `transfer_to_location_id` (`transfer_to_location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_receivings_items`
--

CREATE TABLE IF NOT EXISTS `cln_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_cost_price` decimal(23,10) NOT NULL,
  `item_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_registers`
--

CREATE TABLE IF NOT EXISTS `cln_registers` (
  `register_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`register_id`),
  KEY `deleted` (`deleted`),
  KEY `cln_registers_ibfk_1` (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cln_registers`
--

INSERT INTO `cln_registers` (`register_id`, `location_id`, `name`, `deleted`) VALUES
(1, 1, 'Default', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cln_register_log`
--

CREATE TABLE IF NOT EXISTS `cln_register_log` (
  `register_log_id` int(10) NOT NULL AUTO_INCREMENT,
  `employee_id_open` int(10) NOT NULL,
  `employee_id_close` int(11) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `shift_start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shift_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `open_amount` decimal(23,10) NOT NULL,
  `close_amount` decimal(23,10) NOT NULL,
  `cash_sales_amount` decimal(23,10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`register_log_id`),
  KEY `cln_register_log_ibfk_1` (`employee_id_open`),
  KEY `cln_register_log_ibfk_2` (`register_id`),
  KEY `cln_register_log_ibfk_3` (`employee_id_close`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_revenue`
--

CREATE TABLE IF NOT EXISTS `cln_revenue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `product_name` varchar(450) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profit` float DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_sales`
--

CREATE TABLE IF NOT EXISTS `cln_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `sold_by_employee_id` int(10) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `show_comment_on_receipt` int(1) NOT NULL DEFAULT '0',
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_ref_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `deleted_by` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `suspended` int(1) NOT NULL DEFAULT '0',
  `store_account_payment` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  `tier_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  KEY `location_id` (`location_id`),
  KEY `cln_sales_ibfk_4` (`deleted_by`),
  KEY `sales_search` (`location_id`,`store_account_payment`,`sale_time`,`sale_id`),
  KEY `cln_sales_ibfk_5` (`tier_id`),
  KEY `cln_sales_ibfk_7` (`register_id`),
  KEY `cln_sales_ibfk_6` (`sold_by_employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_sales_items`
--

CREATE TABLE IF NOT EXISTS `cln_sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_cost_price` decimal(23,10) NOT NULL,
  `item_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `commission` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_sales_items_taxes`
--

CREATE TABLE IF NOT EXISTS `cln_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_sales_item_kits`
--

CREATE TABLE IF NOT EXISTS `cln_sales_item_kits` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_kit_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_kit_cost_price` decimal(23,10) NOT NULL,
  `item_kit_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `commission` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`),
  KEY `item_kit_id` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_sales_item_kits_taxes`
--

CREATE TABLE IF NOT EXISTS `cln_sales_item_kits_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cln_sales_payments`
--

CREATE TABLE IF NOT EXISTS `cln_sales_payments` (
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(23,10) NOT NULL,
  `truncated_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `sale_id` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_store_accounts`
--

CREATE TABLE IF NOT EXISTS `cln_store_accounts` (
  `sno` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `transaction_amount` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sno`),
  KEY `cln_store_accounts_ibfk_1` (`sale_id`),
  KEY `cln_store_accounts_ibfk_2` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cln_suppliers`
--

CREATE TABLE IF NOT EXISTS `cln_suppliers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cln_additional_item_numbers`
--
ALTER TABLE `cln_additional_item_numbers`
  ADD CONSTRAINT `cln_additional_item_numbers_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `cln_items` (`item_id`);

--
-- Constraints for table `cln_customers`
--
ALTER TABLE `cln_customers`
  ADD CONSTRAINT `cln_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `cln_people` (`person_id`),
  ADD CONSTRAINT `cln_customers_ibfk_2` FOREIGN KEY (`tier_id`) REFERENCES `cln_price_tiers` (`id`);

--
-- Constraints for table `cln_employees`
--
ALTER TABLE `cln_employees`
  ADD CONSTRAINT `cln_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `cln_people` (`person_id`);

--
-- Constraints for table `cln_employees_locations`
--
ALTER TABLE `cln_employees_locations`
  ADD CONSTRAINT `cln_employees_locations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `cln_employees` (`person_id`),
  ADD CONSTRAINT `cln_employees_locations_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `cln_locations` (`location_id`);

--
-- Constraints for table `cln_giftcards`
--
ALTER TABLE `cln_giftcards`
  ADD CONSTRAINT `cln_giftcards_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `cln_customers` (`person_id`);

--
-- Constraints for table `cln_inventory`
--
ALTER TABLE `cln_inventory`
  ADD CONSTRAINT `cln_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `cln_items` (`item_id`),
  ADD CONSTRAINT `cln_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `cln_employees` (`person_id`),
  ADD CONSTRAINT `cln_inventory_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `cln_locations` (`location_id`);

--
-- Constraints for table `cln_items`
--
ALTER TABLE `cln_items`
  ADD CONSTRAINT `cln_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `cln_suppliers` (`person_id`),
  ADD CONSTRAINT `cln_items_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `cln_app_files` (`file_id`);

--
-- Constraints for table `cln_items_taxes`
--
ALTER TABLE `cln_items_taxes`
  ADD CONSTRAINT `cln_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `cln_items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `cln_items_tier_prices`
--
ALTER TABLE `cln_items_tier_prices`
  ADD CONSTRAINT `cln_items_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `cln_price_tiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cln_items_tier_prices_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `cln_items` (`item_id`);

--
-- Constraints for table `cln_item_kits_taxes`
--
ALTER TABLE `cln_item_kits_taxes`
  ADD CONSTRAINT `cln_item_kits_taxes_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `cln_item_kits` (`item_kit_id`) ON DELETE CASCADE;

--
-- Constraints for table `cln_item_kits_tier_prices`
--
ALTER TABLE `cln_item_kits_tier_prices`
  ADD CONSTRAINT `cln_item_kits_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `cln_price_tiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cln_item_kits_tier_prices_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `cln_item_kits` (`item_kit_id`);

--
-- Constraints for table `cln_item_kit_items`
--
ALTER TABLE `cln_item_kit_items`
  ADD CONSTRAINT `cln_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `cln_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cln_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `cln_items` (`item_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
