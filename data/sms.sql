-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 23, 2016 at 09:25 AM
-- Server version: 5.7.12-0ubuntu1
-- PHP Version: 7.0.8-0ubuntu0.16.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `display` tinyint(4) NOT NULL DEFAULT '1',
  `description` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(100) CHARACTER SET utf8 NOT NULL,
  `m` varchar(20) CHARACTER SET utf8 NOT NULL,
  `c` varchar(20) CHARACTER SET utf8 NOT NULL,
  `a` varchar(20) CHARACTER SET utf8 NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `parentId`, `display`, `description`, `url`, `m`, `c`, `a`, `sort`, `level`) VALUES
(1, '系统设置', 0, 1, '', '', '', '', '', 0, 1),
(2, '用户管理', 1, 1, '', '', '', '', '', 0, 2),
(3, '用户管理', 2, 1, '', '', '', 'user', 'index', 0, 3),
(4, '角色管理', 2, 1, '', '', '', 'role', 'index', 0, 3),
(5, '菜单管理', 1, 1, '', '', '', '', '', 0, 2),
(6, '菜单管理', 5, 1, '', '', '', 'menu', 'index', 0, 3),
(7, '基础权限', 0, 0, '', '', '', '', '', 0, 1),
(8, '用户登陆', 7, 0, '', '', '', 'index', 'index', 0, 2),
(9, '显示主页面', 7, 0, '', '', '', 'index', 'main', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` varchar(100) CHARACTER SET utf8 NOT NULL,
  `sort` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `sort`) VALUES
(1, '超级管理员', '最高权限者', 0),
(2, '部门管理员', '用于管理部门', 0),
(7, '车间管理员', '管理车间用的', 0);

-- --------------------------------------------------------

--
-- Table structure for table `role_menu`
--

CREATE TABLE `role_menu` (
  `roleId` int(11) NOT NULL,
  `menuId` int(11) NOT NULL,
  `level` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_menu`
--

INSERT INTO `role_menu` (`roleId`, `menuId`, `level`) VALUES
(7, 1, 1),
(7, 2, 2),
(7, 3, 3),
(7, 4, 3),
(7, 5, 2),
(7, 6, 3),
(7, 7, 1),
(7, 8, 2),
(7, 9, 2),
(2, 1, 1),
(2, 2, 2),
(2, 3, 3),
(2, 4, 3),
(2, 7, 1),
(2, 8, 2),
(2, 9, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `realName` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `sex` tinyint(4) NOT NULL DEFAULT '0',
  `lastIp` varchar(50) DEFAULT NULL,
  `lastTime` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `birthday` date DEFAULT NULL,
  `card` varchar(20) DEFAULT NULL,
  `addTime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `mobile`, `realName`, `email`, `sex`, `lastIp`, `lastTime`, `status`, `birthday`, `card`, `addTime`) VALUES
(1, 'admin', '111111', '15724703695', '', '', 0, '127.0.0.1', 1474593564, 1, '1990-12-03', '', 0),
(2, 'lsm', '111111', '15724703695', '', '', 1, '127.0.0.1', 1474537127, 1, '1980-02-26', NULL, 1474439782),
(12, 'hello', '111111', '15724703695', '111111', '', 0, '127.0.0.1', 1474530060, 1, '2016-09-29', NULL, 1474528521);

-- --------------------------------------------------------

--
-- Table structure for table `user_panel`
--

CREATE TABLE `user_panel` (
  `userId` int(11) NOT NULL,
  `menuId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`userId`, `roleId`) VALUES
(1, 1),
(2, 7),
(12, 1),
(2, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
