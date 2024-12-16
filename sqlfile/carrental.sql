

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carrental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '5c428d8875d2948607f3e3fe134d71b4', '2017-06-18 12:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE IF NOT EXISTS `tblbooking` (
  `id` int(11) NOT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `VehicleId` int(11) DEFAULT NULL,
  `FromDate` varchar(20) DEFAULT NULL,
  `ToDate` varchar(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`id`, `userEmail`, `VehicleId`, `FromDate`, `ToDate`, `message`, `Status`, `PostingDate`) VALUES
(1, 'test@gmail.com', 2, '22/06/2017', '25/06/2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco', 1, '2017-06-19 20:15:43'),
(2, 'test@gmail.com', 3, '30/06/2017', '02/07/2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco', 2, '2017-06-26 20:15:43'),
(3, 'test@gmail.com', 4, '02/07/2017', '07/07/2017', 'Lorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ', 0, '2017-06-26 21:10:06');

-- --------------------------------------------------------

--
-- Table structure for table `tblbrands`
--

CREATE TABLE IF NOT EXISTS `tblbrands` (
  `id` int(11) NOT NULL,
  `BrandName` varchar(120) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbrands`
--

INSERT INTO `tblbrands` (`id`, `BrandName`, `CreationDate`, `UpdationDate`) VALUES
(1, 'Maruti', '2017-06-18 16:24:34', '2017-06-19 06:42:23'),
(2, 'BMW', '2017-06-18 16:24:50', NULL),
(3, 'Audi', '2017-06-18 16:25:03', NULL),
(4, 'Nissan', '2017-06-18 16:25:13', NULL),
(5, 'Toyota', '2017-06-18 16:25:24', NULL),
(6, 'Honda', '2017-06-19 06:22:13', NULL);
(7, 'kia', '2017-06-20 06:21:13', NULL);
(8, 'Hyundai', '2017-06-21 06:19:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactusinfo`
--

CREATE TABLE IF NOT EXISTS `tblcontactusinfo` (
  `id` int(11) NOT NULL,
  `Address` tinytext,
  `EmailId` varchar(255) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcontactusinfo`
--

INSERT INTO `tblcontactusinfo` (`id`, `Address`, `EmailId`, `ContactNo`) VALUES
(1, 'Test Demo test demo																									', 'test@test.com', '8585233222');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactusquery`
--

CREATE TABLE IF NOT EXISTS `tblcontactusquery` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `ContactNumber` char(11) DEFAULT NULL,
  `Message` longtext,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcontactusquery`
--

INSERT INTO `tblcontactusquery` (`id`, `name`, `EmailId`, `ContactNumber`, `Message`, `PostingDate`, `status`) VALUES
(1, 'Rajiv Kumar', 'rajiv@gmail.com', '2147483647', 'Hi, I am currently renting a car from your platform (Booking ID: 12345) and would like to extend the rental period by two days, from November 10th to November 12th. Could you please confirm if this is possible and provide the updated charges? Let me know if any further steps are needed.', '2017-06-18 10:03:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE IF NOT EXISTS `tblpages` (
  `id` int(11) NOT NULL,
  `PageName` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `detail` longtext NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`id`, `PageName`, `type`, `detail`) VALUES
(1, 'Terms and Conditions', 'terms', '<P align=justify><FONT size=2><STRONG><FONT color=#990000>(1) ACCEPTANCE OF TERMS</FONT><BR><BR></STRONG>Welcome to Yahoo! India. 1Yahoo Web Services India Private Limited Yahoo", "we" or "us" as the case may be) provides the Service (defined below) to you, subject to the following Terms of Service ("TOS"), which may be updated by us from time to time without notice to you. You can review the most current version of the TOS at any time at: <A href="http://in.docs.yahoo.com/info/terms/">http://in.docs.yahoo.com/info/terms/</A>. In addition, when using particular Yahoo services or third party services, you and Yahoo shall be subject to any posted guidelines or rules applicable to such services which may be posted from time to time. All such guidelines or rules, which maybe subject to change, are hereby incorporated by reference into the TOS. In most cases the guides and rules are specific to a particular part of the Service and will assist you in applying the TOS to that part, but to the extent of any inconsistency between the TOS and any guide or rule, the TOS will prevail. We may also offer other services from time to time that are governed by different Terms of Services, in which case the TOS do not apply to such other services if and to the extent expressly excluded by such different Terms of Services. Yahoo also may offer other services from time to time that are governed by different Terms of Services. These TOS do not apply to such other services that are governed by different Terms of Service. </FONT></P>\r\n<P align=justify><FONT size=2>Welcome to Yahoo! India. Yahoo Web Services India Private Limited Yahoo", "we" or "us" as the case may be) provides the Service (defined below) to you, subject to the following Terms of Service ("TOS"), which may be updated by us from time to time without notice to you. You can review the most current version of the TOS at any time at: </FONT><A href="http://in.docs.yahoo.com/info/terms/"><FONT size=2>http://in.docs.yahoo.com/info/terms/</FONT></A><FONT size=2>. In addition, when using particular Yahoo services or third party services, you and Yahoo shall be subject to any posted guidelines or rules applicable to such services which may be posted from time to time. All such guidelines or rules, which maybe subject to change, are hereby incorporated by reference into the TOS. In most cases the guides and rules are specific to a particular part of the Service and will assist you in applying the TOS to that part, but to the extent of any inconsistency between the TOS and any guide or rule, the TOS will prevail. We may also offer other services from time to time that are governed by different Terms of Services, in which case the TOS do not apply to such other services if and to the extent expressly excluded by such different Terms of Services. Yahoo also may offer other services from time to time that are governed by different Terms of Services. These TOS do not apply to such other services that are governed by different Terms of Service. </FONT></P>\r\n<P align=justify><FONT size=2>Welcome to Yahoo! India. Yahoo Web Services India Private Limited Yahoo", "we" or "us" as the case may be) provides the Service (defined below) to you, subject to the following Terms of Service ("TOS"), which may be updated by us from time to time without notice to you. You can review the most current version of the TOS at any time at: </FONT><A href="http://in.docs.yahoo.com/info/terms/"><FONT size=2>http://in.docs.yahoo.com/info/terms/</FONT></A><FONT size=2>. In addition, when using particular Yahoo services or third party services, you and Yahoo shall be subject to any posted guidelines or rules applicable to such services which may be posted from time to time. All such guidelines or rules, which maybe subject to change, are hereby incorporated by reference into the TOS. In most cases the guides and rules are specific to a particular part of the Service and will assist you in applying the TOS to that part, but to the extent of any inconsistency between the TOS and any guide or rule, the TOS will prevail. We may also offer other services from time to time that are governed by different Terms of Services, in which case the TOS do not apply to such other services if and to the extent expressly excluded by such different Terms of Services. Yahoo also may offer other services from time to time that are governed by different Terms of Services. These TOS do not apply to such other services that are governed by different Terms of Service. </FONT></P>'),
(2, 'Privacy Policy', 'privacy', '<span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat</span>'),
(3, 'About Us ', 'aboutus', '<span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;">Welcome to Rent Wheels, your trusted partner for hassle-free car rentals. We are dedicated to providing a seamless, affordable, and reliable car rental experience tailored to your travel needs. With a diverse fleet of vehicles, from compact cars to luxury sedans and SUVs, we ensure there’s a perfect ride for every journey.

Our user-friendly platform allows you to book a car in just a few clicks, with flexible rental options, competitive pricing, and transparent policies. Customer satisfaction is at the heart of what we do, which is why we offer 24/7 support, roadside assistance, and well-maintained vehicles to make your journey safe and enjoyable.

Whether it’s a weekend getaway, a business trip, or a long-term rental, we are here to make your travel stress-free and memorable. Experience convenience, comfort, and reliability with Rent Wheels.</span>'),
(11, 'FAQs', 'faqs', 
'<p style="
  color: rgb(50, 50, 50); 
  font-family: Arial, sans-serif; 
  font-size: 18px; 
  line-height: 1.8; 
  text-align: justify; 
  background-color: #f9f9f9; 
  padding: 20px; 
  border-radius: 10px; 
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
  max-width: 800px; 
  margin: 20px auto;">
1. What documents do I need to rent a car?  
To rent a car, you’ll need a valid driver’s license, an identification document (like a passport or Aadhar card), and a valid credit/debit card for the security deposit. International travelers may also need an International Driving Permit (IDP) depending on local regulations.<br>

2. Can I rent a car if I am under 25 years old?  
Yes, you can rent a car if you are under 25; however, a young driver surcharge may apply. The minimum age requirement varies by location and car category, so please check the specific terms for your rental.<br>

3. Is insurance included in the rental price?  
Basic insurance is included in most rentals, covering damage and theft. However, it may not cover the full cost of damages or liabilities. We recommend opting for additional insurance coverage for peace of mind.<br>

4. How do I pick up and drop off the rental car?  
You can pick up and drop off your rental car at the selected location during the booking process. For convenience, some locations offer doorstep delivery and pick-up services for an additional fee.<br>

5. What happens if I return the car late?  
If you return the car later than the agreed time, a late fee may be charged. Please contact us in advance if you anticipate being late to avoid additional charges.<br>

6. Can I modify or cancel my booking?  
Yes, you can modify or cancel your booking online or by contacting our customer support. Please note that cancellations may be subject to charges depending on the timing of your request.<br>

7. Are there mileage limits on the cars?  
Some rental plans include unlimited mileage, while others may have a daily mileage cap. If you exceed the mileage limit, additional charges may apply. Please check the terms of your chosen plan for details.<br>

8. Can someone else drive the car I rented?  
Yes, an additional driver can be added to your rental agreement for a small fee. The additional driver must meet the same requirements as the primary renter and provide their driver’s license and ID.<br>

9. What should I do in case of an accident or breakdown?  
In the event of an accident or brea kdown, contact our 24/7 customer support immediately. We will guide you through the process and assist with roadside support or a replacement vehicle if necessary.<br>

10. Do you offer long-term car rentals?  
Yes, we offer flexible long-term rental options at discounted rates. Whether you need a car for a few weeks or months, we have plans to suit your needs. Please contact us for custom pricing and terms.<br>
</p>
');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubscribers`
--

CREATE TABLE IF NOT EXISTS `tblsubscribers` (
  `id` int(11) NOT NULL,
  `SubscriberEmail` varchar(120) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsubscribers`
--

INSERT INTO `tblsubscribers` (`id`, `SubscriberEmail`, `PostingDate`) VALUES
(1, 'User@gmail.com', '2017-06-22 16:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbltestimonial`
--

CREATE TABLE IF NOT EXISTS `tbltestimonial` (
  `id` int(11) NOT NULL,
  `UserEmail` varchar(100) NOT NULL,
  `Testimonial` mediumtext NOT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltestimonial`
--

INSERT INTO `tbltestimonial` (`id`, `UserEmail`, `Testimonial`, `PostingDate`, `status`) VALUES
(1, 'ishaan@gmail.com', '"I had a fantastic experience with Rent Wheels! The booking process was straightforward, and the car I rented was spotless and in excellent condition. The customer support team was quick to respond to my questions, and the pickup and drop-off process was seamless. Will definitely use their service again!"', '2017-06-18 07:44:31', 1),
(2, 'aditya@gmail.com', '"I needed a car for a weekend trip, and this platform exceeded my expectations. They had a wide range of options, and the prices were very competitive. The car performed perfectly throughout the trip, and returning it was hassle-free. Highly recommend for anyone looking for a budget-friendly yet reliable option!"', '2017-06-18 07:46:05', 1);
(3, 'ansh@gmail.com', '"I appreciated the variety of cars available on this platform, from compact vehicles to luxury models. I opted for a mid-sized sedan, which was comfortable and fuel-efficient. The flexible rental options allowed me to extend my booking easily when I needed an extra day. Super convenient!"', '2017-06-18 07:46:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE IF NOT EXISTS `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `EmailId`, `Password`, `ContactNo`, `dob`, `Address`, `City`, `Country`, `RegDate`, `UpdationDate`) VALUES
(1, 'Aditya', 'aditya@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2147483647', NULL, NULL, NULL, NULL, '2017-06-17 19:59:27', '2017-06-26 21:02:58'),
(2, 'Ansh', 'ansh@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '8285703354', NULL, NULL, NULL, NULL, '2017-06-17 20:00:49', '2017-06-26 21:03:09'),
(3, 'Harish', 'harish@gmail.com', 'f09df7868d52e12bba658982dbd79821', '09999857868', '03/02/1990', 'PKL', 'PKL', 'PKL', '2017-06-17 20:01:43', '2017-06-17 21:07:41'),
(4, 'Ishaan', 'ishaan@gmail.com', '5c428d8875d2948607f3e3fe134d71b4', '9999857868', '', 'PKL', 'XYZ', 'XYZ', '2017-06-17 20:03:36', '2017-06-26 19:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `tblvehicles`
--

CREATE TABLE IF NOT EXISTS `tblvehicles` (
  `id` int(11) NOT NULL,
  `VehiclesTitle` varchar(150) DEFAULT NULL,
  `VehiclesBrand` int(11) DEFAULT NULL,
  `VehiclesOverview` longtext,
  `PricePerDay` int(11) DEFAULT NULL,
  `FuelType` varchar(100) DEFAULT NULL,
  `ModelYear` int(6) DEFAULT NULL,
  `SeatingCapacity` int(11) DEFAULT NULL,
  `Vimage1` varchar(120) DEFAULT NULL,
  `Vimage2` varchar(120) DEFAULT NULL,
  `Vimage3` varchar(120) DEFAULT NULL,
  `Vimage4` varchar(120) DEFAULT NULL,
  `Vimage5` varchar(120) DEFAULT NULL,
  `AirConditioner` int(11) DEFAULT NULL,
  `PowerDoorLocks` int(11) DEFAULT NULL,
  `AntiLockBrakingSystem` int(11) DEFAULT NULL,
  `BrakeAssist` int(11) DEFAULT NULL,
  `PowerSteering` int(11) DEFAULT NULL,
  `DriverAirbag` int(11) DEFAULT NULL,
  `PassengerAirbag` int(11) DEFAULT NULL,
  `PowerWindows` int(11) DEFAULT NULL,
  `CDPlayer` int(11) DEFAULT NULL,
  `CentralLocking` int(11) DEFAULT NULL,
  `CrashSensor` int(11) DEFAULT NULL,
  `LeatherSeats` int(11) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblvehicles`
--

INSERT INTO `tblvehicles` (`id`, `VehiclesTitle`, `VehiclesBrand`, `VehiclesOverview`, `PricePerDay`, `FuelType`, `ModelYear`, `SeatingCapacity`, `Vimage1`, `Vimage2`, `Vimage3`, `Vimage4`, `Vimage5`, `AirConditioner`, `PowerDoorLocks`, `AntiLockBrakingSystem`, `BrakeAssist`, `PowerSteering`, `DriverAirbag`, `PassengerAirbag`, `PowerWindows`, `CDPlayer`, `CentralLocking`, `CrashSensor`, `LeatherSeats`, `RegDate`, `UpdationDate`) VALUES
(1, 'Hyundai Tucson', 8, 'The 2023 Hyundai Tucson is engaging to look at—we just wish it were as engaging to drive. Slotting between the Kona and the Santa Fe, the Tucson occupies a popular niche, but a lackluster powertrain prevents it from toppling the compact crossover hegemony. For 2023, value gets even better thanks to improved standard features on most trims.', 1500, 'Petrol', 2023, 5, 'Hyundai_1.1.avif', 'Hyundai_1.2.avif', 'Hyundai_1.3.avif', 'Hyundai_1.4.avif', 'Hyundai_1.5.avif', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 11:46:23', '2017-06-20 18:38:13'),

(2, 'Honda CR-V', 6, 'Honda hot-selling compact crossover is the CR-V, and it carries over mostly unchanged for 2025. The current sixth-generation model arrived for 2023 with fresh styling, a more spacious cabin, and an updated powertrain. A 2025 CR-V Hybrid is also available. Rivals in this fiercely competitive segment include the Nissan Rogue, Toyota RAV4, and Subaru Forester . ', 1200, 'CNG', 2022, 4, 'Honda_1.1.avif', 'Honda_1.2.avif', 'Honda_1.3.webp', 'Honda_1.4.avif', 'Honda_1.5.webp', 1, 1, 1, 1, 1, 1, 1, NULL, 1, 1, NULL, NULL, '2017-06-19 16:16:17', '2017-06-20 16:57:11'),

(3, 'Kia Niro', 7, 'More hatchback than small SUV, the futuristic-looking Niro is Kia most affordable hybrid. Besides the standard hybrid, its also available as a plug-in hybrid and a fully electric model. A 2023 redesign made considerable improvements to the small hatchback, so it receives few changes for the new year. Competitors to the 2024 Kia Niro include the Toyota Corolla Cross Hybrid and Hyundai Kona .', 1700, 'Petrol', 2022, 5, 'Kia_1.1.avif', 'Kia_1.2.avif', 'Kia_1.3.avif', 'Kia_1.4.avif', 'Kia_1.5.avif', 1, 1, 1, 1, 1, 1, NULL, 1, 1, NULL, NULL, NULL, '2017-06-19 16:18:20', '2017-06-20 18:40:11'),

(4, 'Toyota Prius', 5, 'Defying the gasoline versus electric binary, the 2024 Toyota Prius takes the middle path with its hybrid drivetrain that returns segment-leading fuel economy. Now in its fifth generation, the well-rounded and surprisingly fun Prius won our 2024 Car of the Year award. Rivals include other compact hybrids such as the Kia Niro and Hyundai Elantra Hybrid', 2000, 'Petrol', 2024, 5, 'Toyota_1.1.avif', 'Toyota_1.2.avif', 'Toyota_1.3.webp', 'Toyota_1.4.webp', 'Toyota_1.5.avif', 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, '2017-06-19 16:18:43', '2017-06-20 18:44:12'),

(5, 'Nissan Versa', 4, 'The Versa subcompact sedan is the least expensive Nissan you can buy. Introduced to the North American market for 2007, the Versa remains a good example of solid affordable transportation. The subcompact car segment is shrinking, so much so that the Versa now only has one direct competitor: the Mitsubishi Mirage .', 1800, 'Petrol', 2023, 4, 'Nissan_1.1.avif', 'Nissan_1.2.avif', 'Nissan_1.3.avif', 'Nissan_1.4.avif', 'Nissan_1.5.webp', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),

(6, 'BMW 2 Series Gran Coupe', 2, 'Now entering its fifth year on the market, the 2024 BMW 2 Series Gran Coupe is the cheapest way to get a blue-and-white roundel on your hood. The model carries over almost entirely unchanged from last year.The 2 Series Gran Coupe, a four-door, is a separate model from the 2 Series Coupe . The Gran Coupe competes against subcompact luxury sedans including the Mercedes-Benz CLA-Class and Audi A3.', 2500, 'Petrol', 2023, 4, 'BMW_1.1.avif', 'BMW_1.2.webp', 'BMW_1.3.webp', 'BMW_1.4.avif', 'BMW_1.5.webp', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),

(7, 'BMW X1', 2, 'BMW continues to refine its delightful X1 subcompact SUV, delivering more tech for 2025. New for 2023, the third-generation model is among our favorite versions of the entry-level premium crossover. Luxury subcompact SUV competitors include the Alfa Romeo Tonale, Audi Q3, and Mercedes-Benz GLB-Class.', 2200, 'Petrol', 2024, 4, 'BMW_2.1.avif', 'BMW_2.2.avif', 'BMW_2.3.avif', 'BMW_2.4.avif', 'BMW_2.5.webp', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),

(8, 'Audi RS3', 3, 'Audi rambunctious performance sedan sits at the top of the subcompact A3 lineup . After a significant redesign for the 2022 model year, the 2024 Audi RS3 carries over mostly unchanged. That is alright with us; this five-cylinder marvel has all the go-fast componentry necessary to thrill. Competitors include other small, high-power cars like the BMW M2, Cadillac CT4-V Blackwing, and Mercedes-AMG CLA45 Coupe. ', 3000, 'Petrol', 2024, 4, 'Audi_1.1.avif', 'Audi_1.2.avif', 'Audi_1.3.avif', 'Audi_1.4.avif', 'Audi_1.5.webp', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),

(9, 'Kia Seltos', 7, 'Kia offers two subcompact SUVs, and although the Soul has its charms, the Seltos is the more conventionally SUV-looking offering. Slotting above the Soul and below the Sportage in size and price, the Seltos is a relatively new model in Kia’s lineup of gas-powered SUVs. Competitors to the 2025 Kia Seltos include the Subaru Crosstrek, Mazda CX-30, and Chevrolet Trailblazer.', 1900, 'Petrol', 2022, 4, 'Kia_2.1.avif', 'Kia_2.2.avif', 'Kia_2.3.avif', 'Kia_2.4.avif', 'Kia_2.5.avif', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),

(10, 'Toyota RAV4 Hybrid', 5, 'We like the Toyota RAV4, but we love the RAV4 Hybrid due to its refinement and fuel frugality. Because it was updated for 2023, there are not any major changes for the 2024 model year. The RAV4 Hybrid competes with other hybrid compact crossovers such as the Honda CR-V Hybrid, Ford Escape Hybrid, and Hyundai Tucson Hybrid.', 2200, 'Petrol', 2023, 4, 'Toyota_2.1.avif', 'Toyota_2.2.avif', 'Toyota_2.3.avif', 'Toyota_2.4.webp', 'Toyota_2.5.avif', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43');
--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbrands`
--
ALTER TABLE `tblbrands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactusinfo`
--
ALTER TABLE `tblcontactusinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactusquery`
--
ALTER TABLE `tblcontactusquery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsubscribers`
--
ALTER TABLE `tblsubscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltestimonial`
--
ALTER TABLE `tbltestimonial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblvehicles`
--
ALTER TABLE `tblvehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tblbrands`
--
ALTER TABLE `tblbrands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tblcontactusinfo`
--
ALTER TABLE `tblcontactusinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tblcontactusquery`
--
ALTER TABLE `tblcontactusquery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `tblsubscribers`
--
ALTER TABLE `tblsubscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbltestimonial`
--
ALTER TABLE `tbltestimonial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tblvehicles`
--
ALTER TABLE `tblvehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
