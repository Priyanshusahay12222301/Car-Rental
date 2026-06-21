-- ============================================================
-- Car Rental — PostgreSQL Schema
-- Converted from MySQL (carrental.sql)
-- Run this against the Render PostgreSQL 18 database.
-- ============================================================

BEGIN;

-- --------------------------------------------------------
-- Table: admin
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id            SERIAL PRIMARY KEY,
    "UserName"    VARCHAR(100) NOT NULL,
    "Password"    VARCHAR(100) NOT NULL,
    "updationDate" TIMESTAMP DEFAULT NULL
);

INSERT INTO admin ("id", "UserName", "Password", "updationDate") VALUES
(1, 'admin', '5c428d8875d2948607f3e3fe134d71b4', '2017-06-18 12:22:38')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('admin', 'id'), MAX(id)) FROM admin;

-- --------------------------------------------------------
-- Table: tblbooking
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblbooking (
    id           SERIAL PRIMARY KEY,
    "userEmail"  VARCHAR(100) DEFAULT NULL,
    "VehicleId"  INT DEFAULT NULL,
    "FromDate"   VARCHAR(20) DEFAULT NULL,
    "ToDate"     VARCHAR(20) DEFAULT NULL,
    message      VARCHAR(255) DEFAULT NULL,
    "Status"     INT DEFAULT NULL,
    "PostingDate" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tblbooking (id, "userEmail", "VehicleId", "FromDate", "ToDate", message, "Status", "PostingDate") VALUES
(1, 'test@gmail.com', 2, '22/06/2017', '25/06/2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco', 1, '2017-06-19 20:15:43'),
(2, 'test@gmail.com', 3, '30/06/2017', '02/07/2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco', 2, '2017-06-26 20:15:43'),
(3, 'test@gmail.com', 4, '02/07/2017', '07/07/2017', 'Lorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ', 0, '2017-06-26 21:10:06')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblbooking', 'id'), MAX(id)) FROM tblbooking;

-- --------------------------------------------------------
-- Table: tblbrands
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblbrands (
    id             SERIAL PRIMARY KEY,
    "BrandName"    VARCHAR(120) NOT NULL,
    "CreationDate" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "UpdationDate" TIMESTAMP DEFAULT NULL
);

INSERT INTO tblbrands (id, "BrandName", "CreationDate", "UpdationDate") VALUES
(1, 'Maruti',  '2017-06-18 16:24:34', '2017-06-19 06:42:23'),
(2, 'BMW',     '2017-06-18 16:24:50', NULL),
(3, 'Audi',    '2017-06-18 16:25:03', NULL),
(4, 'Nissan',  '2017-06-18 16:25:13', NULL),
(5, 'Toyota',  '2017-06-18 16:25:24', NULL),
(6, 'Honda',   '2017-06-19 06:22:13', NULL),
(7, 'kia',     '2017-06-20 06:21:13', NULL),
(8, 'Hyundai', '2017-06-21 06:19:13', NULL)
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblbrands', 'id'), MAX(id)) FROM tblbrands;

-- --------------------------------------------------------
-- Table: tblcontactusinfo
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblcontactusinfo (
    id          SERIAL PRIMARY KEY,
    "Address"   TEXT,
    "EmailId"   VARCHAR(255) DEFAULT NULL,
    "ContactNo" VARCHAR(11) DEFAULT NULL
);

INSERT INTO tblcontactusinfo (id, "Address", "EmailId", "ContactNo") VALUES
(1, 'Test Demo test demo', 'test@test.com', '8585233222')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblcontactusinfo', 'id'), MAX(id)) FROM tblcontactusinfo;

-- --------------------------------------------------------
-- Table: tblcontactusquery
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblcontactusquery (
    id              SERIAL PRIMARY KEY,
    name            VARCHAR(100) DEFAULT NULL,
    "EmailId"       VARCHAR(120) DEFAULT NULL,
    "ContactNumber" VARCHAR(11) DEFAULT NULL,
    "Message"       TEXT,
    "PostingDate"   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status          INT DEFAULT NULL
);

INSERT INTO tblcontactusquery (id, name, "EmailId", "ContactNumber", "Message", "PostingDate", status) VALUES
(1, 'Rajiv Kumar', 'rajiv@gmail.com', '9999999999', 'Hi, I am currently renting a car from your platform (Booking ID: 12345) and would like to extend the rental period by two days, from November 10th to November 12th. Could you please confirm if this is possible and provide the updated charges? Let me know if any further steps are needed.', '2017-06-18 10:03:07', 1)
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblcontactusquery', 'id'), MAX(id)) FROM tblcontactusquery;

-- --------------------------------------------------------
-- Table: tblpages
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblpages (
    id         SERIAL PRIMARY KEY,
    "PageName" VARCHAR(255) DEFAULT NULL,
    type       VARCHAR(255) NOT NULL DEFAULT '',
    detail     TEXT NOT NULL DEFAULT ''
);

INSERT INTO tblpages (id, "PageName", type, detail) VALUES
(1,  'Terms and Conditions', 'terms',   '<p>Terms and Conditions content here.</p>'),
(2,  'Privacy Policy',       'privacy', '<p>Privacy Policy content here.</p>'),
(3,  'About Us',             'aboutus', '<span>Welcome to Rent Wheels, your trusted partner for hassle-free car rentals. We are dedicated to providing a seamless, affordable, and reliable car rental experience tailored to your travel needs. With a diverse fleet of vehicles, from compact cars to luxury sedans and SUVs, we ensure there''s a perfect ride for every journey. Our user-friendly platform allows you to book a car in just a few clicks, with flexible rental options, competitive pricing, and transparent policies. Customer satisfaction is at the heart of what we do, which is why we offer 24/7 support, roadside assistance, and well-maintained vehicles to make your journey safe and enjoyable. Whether it''s a weekend getaway, a business trip, or a long-term rental, we are here to make your travel stress-free and memorable. Experience convenience, comfort, and reliability with Rent Wheels.</span>'),
(11, 'FAQs',                 'faqs',    '<p>1. What documents do I need to rent a car? To rent a car, you''ll need a valid driver''s license, an identification document (like a passport or Aadhar card), and a valid credit/debit card for the security deposit.</p>')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblpages', 'id'), 22);

-- --------------------------------------------------------
-- Table: tblsubscribers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblsubscribers (
    id                  SERIAL PRIMARY KEY,
    "SubscriberEmail"   VARCHAR(120) DEFAULT NULL,
    "PostingDate"       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tblsubscribers (id, "SubscriberEmail", "PostingDate") VALUES
(1, 'User@gmail.com', '2017-06-22 16:35:32')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblsubscribers', 'id'), MAX(id)) FROM tblsubscribers;

-- --------------------------------------------------------
-- Table: tbltestimonial
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tbltestimonial (
    id            SERIAL PRIMARY KEY,
    "UserEmail"   VARCHAR(100) NOT NULL,
    "Testimonial" TEXT NOT NULL,
    "PostingDate" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status        INT DEFAULT NULL
);

INSERT INTO tbltestimonial (id, "UserEmail", "Testimonial", "PostingDate", status) VALUES
(1, 'ishaan@gmail.com', '"I had a fantastic experience with Rent Wheels! The booking process was straightforward, and the car I rented was spotless and in excellent condition. The customer support team was quick to respond to my questions, and the pickup and drop-off process was seamless. Will definitely use their service again!"', '2017-06-18 07:44:31', 1),
(2, 'aditya@gmail.com', '"I needed a car for a weekend trip, and this platform exceeded my expectations. They had a wide range of options, and the prices were very competitive. The car performed perfectly throughout the trip, and returning it was hassle-free. Highly recommend for anyone looking for a budget-friendly yet reliable option!"', '2017-06-18 07:46:05', 1),
(3, 'ansh@gmail.com',   '"I appreciated the variety of cars available on this platform, from compact vehicles to luxury models. I opted for a mid-sized sedan, which was comfortable and fuel-efficient. The flexible rental options allowed me to extend my booking easily when I needed an extra day. Super convenient!"', '2017-06-18 07:46:05', 1)
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbltestimonial', 'id'), MAX(id)) FROM tbltestimonial;

-- --------------------------------------------------------
-- Table: tblusers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblusers (
    id              SERIAL PRIMARY KEY,
    "FullName"      VARCHAR(120) DEFAULT NULL,
    "EmailId"       VARCHAR(100) DEFAULT NULL,
    "Password"      VARCHAR(100) DEFAULT NULL,
    "ContactNo"     VARCHAR(11)  DEFAULT NULL,
    dob             VARCHAR(100) DEFAULT NULL,
    "Address"       VARCHAR(255) DEFAULT NULL,
    "City"          VARCHAR(100) DEFAULT NULL,
    "Country"       VARCHAR(100) DEFAULT NULL,
    "RegDate"       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "UpdationDate"  TIMESTAMP DEFAULT NULL
);

INSERT INTO tblusers (id, "FullName", "EmailId", "Password", "ContactNo", dob, "Address", "City", "Country", "RegDate", "UpdationDate") VALUES
(1, 'Aditya', 'aditya@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2147483647', NULL, NULL, NULL, NULL, '2017-06-17 19:59:27', '2017-06-26 21:02:58'),
(2, 'Ansh',   'ansh@gmail.com',   'f925916e2754e5e03f75dd58a5733251', '8285703354', NULL, NULL, NULL, NULL, '2017-06-17 20:00:49', '2017-06-26 21:03:09'),
(3, 'Harish', 'harish@gmail.com', 'f09df7868d52e12bba658982dbd79821', '9999857868', '03/02/1990', 'PKL', 'PKL', 'PKL', '2017-06-17 20:01:43', '2017-06-17 21:07:41'),
(4, 'Ishaan', 'ishaan@gmail.com', '5c428d8875d2948607f3e3fe134d71b4', '9999857868', '', 'PKL', 'XYZ', 'XYZ', '2017-06-17 20:03:36', '2017-06-26 19:18:14')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblusers', 'id'), MAX(id)) FROM tblusers;

-- --------------------------------------------------------
-- Table: tblvehicles
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblvehicles (
    id                        SERIAL PRIMARY KEY,
    "VehiclesTitle"           VARCHAR(150) DEFAULT NULL,
    "VehiclesBrand"           INT DEFAULT NULL,
    "VehiclesOverview"        TEXT,
    "PricePerDay"             INT DEFAULT NULL,
    "FuelType"                VARCHAR(100) DEFAULT NULL,
    "ModelYear"               INT DEFAULT NULL,
    "SeatingCapacity"         INT DEFAULT NULL,
    "Vimage1"                 VARCHAR(120) DEFAULT NULL,
    "Vimage2"                 VARCHAR(120) DEFAULT NULL,
    "Vimage3"                 VARCHAR(120) DEFAULT NULL,
    "Vimage4"                 VARCHAR(120) DEFAULT NULL,
    "Vimage5"                 VARCHAR(120) DEFAULT NULL,
    "AirConditioner"          INT DEFAULT NULL,
    "PowerDoorLocks"          INT DEFAULT NULL,
    "AntiLockBrakingSystem"   INT DEFAULT NULL,
    "BrakeAssist"             INT DEFAULT NULL,
    "PowerSteering"           INT DEFAULT NULL,
    "DriverAirbag"            INT DEFAULT NULL,
    "PassengerAirbag"         INT DEFAULT NULL,
    "PowerWindows"            INT DEFAULT NULL,
    "CDPlayer"                INT DEFAULT NULL,
    "CentralLocking"          INT DEFAULT NULL,
    "CrashSensor"             INT DEFAULT NULL,
    "LeatherSeats"            INT DEFAULT NULL,
    "RegDate"                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "UpdationDate"            TIMESTAMP DEFAULT NULL
);

INSERT INTO tblvehicles (id, "VehiclesTitle", "VehiclesBrand", "VehiclesOverview", "PricePerDay", "FuelType", "ModelYear", "SeatingCapacity", "Vimage1", "Vimage2", "Vimage3", "Vimage4", "Vimage5", "AirConditioner", "PowerDoorLocks", "AntiLockBrakingSystem", "BrakeAssist", "PowerSteering", "DriverAirbag", "PassengerAirbag", "PowerWindows", "CDPlayer", "CentralLocking", "CrashSensor", "LeatherSeats", "RegDate", "UpdationDate") VALUES
(1,  'Hyundai Tucson',        8, 'The 2023 Hyundai Tucson is engaging to look at—we just wish it were as engaging to drive. Slotting between the Kona and the Santa Fe, the Tucson occupies a popular niche, but a lackluster powertrain prevents it from toppling the compact crossover hegemony. For 2023, value gets even better thanks to improved standard features on most trims.', 1500, 'Petrol', 2023, 5, 'Hyundai_1.1.avif', 'Hyundai_1.2.avif', 'Hyundai_1.3.avif', 'Hyundai_1.4.avif', 'Hyundai_1.5.avif', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 11:46:23', '2017-06-20 18:38:13'),
(2,  'Honda CR-V',            6, 'Honda hot-selling compact crossover is the CR-V, and it carries over mostly unchanged for 2025. The current sixth-generation model arrived for 2023 with fresh styling, a more spacious cabin, and an updated powertrain.', 1200, 'CNG',    2022, 4, 'Honda_1.1.avif',   'Honda_1.2.avif',   'Honda_1.3.webp',   'Honda_1.4.avif',   'Honda_1.5.webp',   1, 1, 1, 1, 1, 1, 1, NULL, 1, 1, NULL, NULL, '2017-06-19 16:16:17', '2017-06-20 16:57:11'),
(3,  'Kia Niro',              7, 'More hatchback than small SUV, the futuristic-looking Niro is Kia most affordable hybrid. Besides the standard hybrid, its also available as a plug-in hybrid and a fully electric model.', 1700, 'Petrol', 2022, 5, 'Kia_1.1.avif',     'Kia_1.2.avif',     'Kia_1.3.avif',     'Kia_1.4.avif',     'Kia_1.5.avif',     1, 1, 1, 1, 1, 1, NULL, 1, 1, NULL, NULL, NULL, '2017-06-19 16:18:20', '2017-06-20 18:40:11'),
(4,  'Toyota Prius',          5, 'Defying the gasoline versus electric binary, the 2024 Toyota Prius takes the middle path with its hybrid drivetrain that returns segment-leading fuel economy. Now in its fifth generation, the well-rounded and surprisingly fun Prius won our 2024 Car of the Year award.', 2000, 'Petrol', 2024, 5, 'Toyota_1.1.avif',  'Toyota_1.2.avif',  'Toyota_1.3.webp',  'Toyota_1.4.webp',  'Toyota_1.5.avif',  1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, '2017-06-19 16:18:43', '2017-06-20 18:44:12'),
(5,  'Nissan Versa',          4, 'The Versa subcompact sedan is the least expensive Nissan you can buy. Introduced to the North American market for 2007, the Versa remains a good example of solid affordable transportation.', 1800, 'Petrol', 2023, 4, 'Nissan_1.1.avif',  'Nissan_1.2.avif',  'Nissan_1.3.avif',  'Nissan_1.4.avif',  'Nissan_1.5.webp',  1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(6,  'BMW 2 Series Gran Coupe', 2, 'Now entering its fifth year on the market, the 2024 BMW 2 Series Gran Coupe is the cheapest way to get a blue-and-white roundel on your hood.', 2500, 'Petrol', 2023, 4, 'BMW_1.1.avif',     'BMW_1.2.webp',     'BMW_1.3.webp',     'BMW_1.4.avif',     'BMW_1.5.webp',     1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(7,  'BMW X1',                2, 'BMW continues to refine its delightful X1 subcompact SUV, delivering more tech for 2025. New for 2023, the third-generation model is among our favorite versions of the entry-level premium crossover.', 2200, 'Petrol', 2024, 4, 'BMW_2.1.avif',     'BMW_2.2.avif',     'BMW_2.3.avif',     'BMW_2.4.avif',     'BMW_2.5.webp',     1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(8,  'Audi RS3',              3, 'Audi rambunctious performance sedan sits at the top of the subcompact A3 lineup. After a significant redesign for the 2022 model year, the 2024 Audi RS3 carries over mostly unchanged.', 3000, 'Petrol', 2024, 4, 'Audi_1.1.avif',    'Audi_1.2.avif',    'Audi_1.3.avif',    'Audi_1.4.avif',    'Audi_1.5.webp',    1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(9,  'Kia Seltos',            7, 'Kia offers two subcompact SUVs, and although the Soul has its charms, the Seltos is the more conventionally SUV-looking offering. Slotting above the Soul and below the Sportage in size and price, the Seltos is a relatively new model.', 1900, 'Petrol', 2022, 4, 'Kia_2.1.avif',     'Kia_2.2.avif',     'Kia_2.3.avif',     'Kia_2.4.avif',     'Kia_2.5.avif',     1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(10, 'Toyota RAV4 Hybrid',    5, 'We like the Toyota RAV4, but we love the RAV4 Hybrid due to its refinement and fuel frugality. Because it was updated for 2023, there are not any major changes for the 2024 model year.', 2200, 'Petrol', 2023, 4, 'Toyota_2.1.avif',  'Toyota_2.2.avif',  'Toyota_2.3.avif',  'Toyota_2.4.webp',  'Toyota_2.5.avif',  1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblvehicles', 'id'), MAX(id)) FROM tblvehicles;

COMMIT;
