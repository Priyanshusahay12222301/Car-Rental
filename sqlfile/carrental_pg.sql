-- ============================================================
-- Car Rental — PostgreSQL Schema (lowercase column names)
-- All column names are lowercase to match PHP queries which
-- PostgreSQL folds to lowercase by default.
-- ============================================================

BEGIN;

-- --------------------------------------------------------
-- Table: admin
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id            SERIAL PRIMARY KEY,
    username      VARCHAR(100) NOT NULL,
    password      VARCHAR(100) NOT NULL,
    updationdate  TIMESTAMP DEFAULT NULL
);

INSERT INTO admin (id, username, password, updationdate) VALUES
(1, 'admin', '5c428d8875d2948607f3e3fe134d71b4', '2017-06-18 12:22:38')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('admin', 'id'), MAX(id)) FROM admin;

-- --------------------------------------------------------
-- Table: tblbooking
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblbooking (
    id           SERIAL PRIMARY KEY,
    useremail    VARCHAR(100) DEFAULT NULL,
    vehicleid    INT DEFAULT NULL,
    fromdate     VARCHAR(20) DEFAULT NULL,
    todate       VARCHAR(20) DEFAULT NULL,
    message      VARCHAR(255) DEFAULT NULL,
    status       INT DEFAULT NULL,
    postingdate  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tblbooking (id, useremail, vehicleid, fromdate, todate, message, status, postingdate) VALUES
(1, 'test@gmail.com', 2, '22/06/2017', '25/06/2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 1, '2017-06-19 20:15:43'),
(2, 'test@gmail.com', 3, '30/06/2017', '02/07/2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 2, '2017-06-26 20:15:43'),
(3, 'test@gmail.com', 4, '02/07/2017', '07/07/2017', 'Lorem ipsum dolor sit amet.', 0, '2017-06-26 21:10:06')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblbooking', 'id'), MAX(id)) FROM tblbooking;

-- --------------------------------------------------------
-- Table: tblbrands
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblbrands (
    id            SERIAL PRIMARY KEY,
    brandname     VARCHAR(120) NOT NULL,
    creationdate  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updationdate  TIMESTAMP DEFAULT NULL
);

INSERT INTO tblbrands (id, brandname, creationdate, updationdate) VALUES
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
    id         SERIAL PRIMARY KEY,
    address    TEXT,
    emailid    VARCHAR(255) DEFAULT NULL,
    contactno  VARCHAR(11) DEFAULT NULL
);

INSERT INTO tblcontactusinfo (id, address, emailid, contactno) VALUES
(1, 'Test Demo test demo', 'test@test.com', '8585233222')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblcontactusinfo', 'id'), MAX(id)) FROM tblcontactusinfo;

-- --------------------------------------------------------
-- Table: tblcontactusquery
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblcontactusquery (
    id             SERIAL PRIMARY KEY,
    name           VARCHAR(100) DEFAULT NULL,
    emailid        VARCHAR(120) DEFAULT NULL,
    contactnumber  VARCHAR(11) DEFAULT NULL,
    message        TEXT,
    postingdate    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status         INT DEFAULT NULL
);

INSERT INTO tblcontactusquery (id, name, emailid, contactnumber, message, postingdate, status) VALUES
(1, 'Rajiv Kumar', 'rajiv@gmail.com', '9999999999', 'Hi, I am currently renting a car from your platform and would like to extend the rental period by two days.', '2017-06-18 10:03:07', 1)
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblcontactusquery', 'id'), MAX(id)) FROM tblcontactusquery;

-- --------------------------------------------------------
-- Table: tblpages
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblpages (
    id        SERIAL PRIMARY KEY,
    pagename  VARCHAR(255) DEFAULT NULL,
    type      VARCHAR(255) NOT NULL DEFAULT '',
    detail    TEXT NOT NULL DEFAULT ''
);

INSERT INTO tblpages (id, pagename, type, detail) VALUES
(1,  'Terms and Conditions', 'terms',   '<p>Terms and Conditions content here.</p>'),
(2,  'Privacy Policy',       'privacy', '<p>Privacy Policy content here.</p>'),
(3,  'About Us',             'aboutus', '<span>Welcome to Rent Wheels, your trusted partner for hassle-free car rentals.</span>'),
(11, 'FAQs',                 'faqs',    '<p>1. What documents do I need to rent a car? A valid driver''s license and ID.</p>')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblpages', 'id'), 22);

-- --------------------------------------------------------
-- Table: tblsubscribers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblsubscribers (
    id               SERIAL PRIMARY KEY,
    subscriberemail  VARCHAR(120) DEFAULT NULL,
    postingdate      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tblsubscribers (id, subscriberemail, postingdate) VALUES
(1, 'User@gmail.com', '2017-06-22 16:35:32')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblsubscribers', 'id'), MAX(id)) FROM tblsubscribers;

-- --------------------------------------------------------
-- Table: tbltestimonial
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tbltestimonial (
    id           SERIAL PRIMARY KEY,
    useremail    VARCHAR(100) NOT NULL,
    testimonial  TEXT NOT NULL,
    postingdate  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status       INT DEFAULT NULL
);

INSERT INTO tbltestimonial (id, useremail, testimonial, postingdate, status) VALUES
(1, 'ishaan@gmail.com', 'I had a fantastic experience with Rent Wheels! The booking process was straightforward, and the car I rented was spotless and in excellent condition.', '2017-06-18 07:44:31', 1),
(2, 'aditya@gmail.com', 'I needed a car for a weekend trip, and this platform exceeded my expectations. They had a wide range of options, and the prices were very competitive.', '2017-06-18 07:46:05', 1),
(3, 'ansh@gmail.com',   'I appreciated the variety of cars available on this platform, from compact vehicles to luxury models. The flexible rental options allowed me to extend my booking easily.', '2017-06-18 07:46:05', 1)
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbltestimonial', 'id'), MAX(id)) FROM tbltestimonial;

-- --------------------------------------------------------
-- Table: tblusers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS tblusers (
    id            SERIAL PRIMARY KEY,
    fullname      VARCHAR(120) DEFAULT NULL,
    emailid       VARCHAR(100) DEFAULT NULL,
    password      VARCHAR(100) DEFAULT NULL,
    contactno     VARCHAR(11)  DEFAULT NULL,
    dob           VARCHAR(100) DEFAULT NULL,
    address       VARCHAR(255) DEFAULT NULL,
    city          VARCHAR(100) DEFAULT NULL,
    country       VARCHAR(100) DEFAULT NULL,
    regdate       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updationdate  TIMESTAMP DEFAULT NULL
);

INSERT INTO tblusers (id, fullname, emailid, password, contactno, dob, address, city, country, regdate, updationdate) VALUES
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
    id                      SERIAL PRIMARY KEY,
    vehiclestitle           VARCHAR(150) DEFAULT NULL,
    vehiclesbrand           INT DEFAULT NULL,
    vehiclesoverview        TEXT,
    priceperday             INT DEFAULT NULL,
    fueltype                VARCHAR(100) DEFAULT NULL,
    modelyear               INT DEFAULT NULL,
    seatingcapacity         INT DEFAULT NULL,
    vimage1                 VARCHAR(120) DEFAULT NULL,
    vimage2                 VARCHAR(120) DEFAULT NULL,
    vimage3                 VARCHAR(120) DEFAULT NULL,
    vimage4                 VARCHAR(120) DEFAULT NULL,
    vimage5                 VARCHAR(120) DEFAULT NULL,
    airconditioner          INT DEFAULT NULL,
    powerdoorlocks          INT DEFAULT NULL,
    antilockbrakingsystem   INT DEFAULT NULL,
    brakeassist             INT DEFAULT NULL,
    powersteering           INT DEFAULT NULL,
    driverairbag            INT DEFAULT NULL,
    passengerairbag         INT DEFAULT NULL,
    powerwindows            INT DEFAULT NULL,
    cdplayer                INT DEFAULT NULL,
    centrallocking          INT DEFAULT NULL,
    crashsensor             INT DEFAULT NULL,
    leatherseats            INT DEFAULT NULL,
    regdate                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updationdate            TIMESTAMP DEFAULT NULL
);

INSERT INTO tblvehicles (id, vehiclestitle, vehiclesbrand, vehiclesoverview, priceperday, fueltype, modelyear, seatingcapacity, vimage1, vimage2, vimage3, vimage4, vimage5, airconditioner, powerdoorlocks, antilockbrakingsystem, brakeassist, powersteering, driverairbag, passengerairbag, powerwindows, cdplayer, centrallocking, crashsensor, leatherseats, regdate, updationdate) VALUES
(1,  'Hyundai Tucson',          8, 'The 2023 Hyundai Tucson is engaging to look at. Slotting between the Kona and the Santa Fe, the Tucson occupies a popular niche. For 2023, value gets even better thanks to improved standard features on most trims.',        1500, 'Petrol', 2023, 5, 'Hyundai_1.1.avif', 'Hyundai_1.2.avif', 'Hyundai_1.3.avif', 'Hyundai_1.4.avif', 'Hyundai_1.5.avif', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 11:46:23', '2017-06-20 18:38:13'),
(2,  'Honda CR-V',              6, 'Honda hot-selling compact crossover is the CR-V, and it carries over mostly unchanged for 2025. The current sixth-generation model arrived for 2023 with fresh styling and a more spacious cabin.',                               1200, 'CNG',    2022, 4, 'Honda_1.1.avif',   'Honda_1.2.avif',   'Honda_1.3.webp',   'Honda_1.4.avif',   'Honda_1.5.webp',   1, 1, 1, 1, 1, 1, 1, NULL, 1, 1, NULL, NULL, '2017-06-19 16:16:17', '2017-06-20 16:57:11'),
(3,  'Kia Niro',                7, 'More hatchback than small SUV, the futuristic-looking Niro is Kia most affordable hybrid. Besides the standard hybrid, it is also available as a plug-in hybrid and a fully electric model.',                                 1700, 'Petrol', 2022, 5, 'Kia_1.1.avif',     'Kia_1.2.avif',     'Kia_1.3.avif',     'Kia_1.4.avif',     'Kia_1.5.avif',     1, 1, 1, 1, 1, 1, NULL, 1, 1, NULL, NULL, NULL, '2017-06-19 16:18:20', '2017-06-20 18:40:11'),
(4,  'Toyota Prius',            5, 'The 2024 Toyota Prius takes the middle path with its hybrid drivetrain that returns segment-leading fuel economy. Now in its fifth generation, the well-rounded Prius won our 2024 Car of the Year award.',                     2000, 'Petrol', 2024, 5, 'Toyota_1.1.avif',  'Toyota_1.2.avif',  'Toyota_1.3.webp',  'Toyota_1.4.webp',  'Toyota_1.5.avif',  1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, '2017-06-19 16:18:43', '2017-06-20 18:44:12'),
(5,  'Nissan Versa',            4, 'The Versa subcompact sedan is the least expensive Nissan you can buy. Introduced to the North American market for 2007, the Versa remains a good example of solid affordable transportation.',                                1800, 'Petrol', 2023, 4, 'Nissan_1.1.avif',  'Nissan_1.2.avif',  'Nissan_1.3.avif',  'Nissan_1.4.avif',  'Nissan_1.5.webp',  1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(6,  'BMW 2 Series Gran Coupe', 2, 'Now entering its fifth year on the market, the 2024 BMW 2 Series Gran Coupe is the cheapest way to get a blue-and-white roundel on your hood.',                                                                              2500, 'Petrol', 2023, 4, 'BMW_1.1.avif',     'BMW_1.2.webp',     'BMW_1.3.webp',     'BMW_1.4.avif',     'BMW_1.5.webp',     1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(7,  'BMW X1',                  2, 'BMW continues to refine its delightful X1 subcompact SUV, delivering more tech for 2025. New for 2023, the third-generation model is among our favorite versions of the entry-level premium crossover.',                       2200, 'Petrol', 2024, 4, 'BMW_2.1.avif',     'BMW_2.2.avif',     'BMW_2.3.avif',     'BMW_2.4.avif',     'BMW_2.5.webp',     1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(8,  'Audi RS3',                3, 'Audi rambunctious performance sedan sits at the top of the subcompact A3 lineup. After a significant redesign for the 2022 model year, the 2024 Audi RS3 carries over mostly unchanged.',                                     3000, 'Petrol', 2024, 4, 'Audi_1.1.avif',    'Audi_1.2.avif',    'Audi_1.3.avif',    'Audi_1.4.avif',    'Audi_1.5.webp',    1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(9,  'Kia Seltos',              7, 'Kia offers two subcompact SUVs, and the Seltos is the more conventionally SUV-looking offering. Slotting above the Soul and below the Sportage in size and price, the Seltos is a relatively new model.',                    1900, 'Petrol', 2022, 4, 'Kia_2.1.avif',     'Kia_2.2.avif',     'Kia_2.3.avif',     'Kia_2.4.avif',     'Kia_2.5.avif',     1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43'),
(10, 'Toyota RAV4 Hybrid',      5, 'We like the Toyota RAV4, but we love the RAV4 Hybrid due to its refinement and fuel frugality. Because it was updated for 2023, there are not any major changes for the 2024 model year.',                                    2200, 'Petrol', 2023, 4, 'Toyota_2.1.avif',  'Toyota_2.2.avif',  'Toyota_2.3.avif',  'Toyota_2.4.webp',  'Toyota_2.5.avif',  1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2017-06-19 17:57:09', '2017-06-20 16:56:43')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('tblvehicles', 'id'), MAX(id)) FROM tblvehicles;

COMMIT;
