<?php
/**
 * db_init.php — Auto-initialises the PostgreSQL schema on first run.
 * Included by config.php. Checks if core tables exist; if not, creates them.
 * Safe to run on every request (fast existence check first).
 */

function db_init_schema(PDO $dbh): void
{
    // Quick existence check — if tblvehicles already has the right column, skip
    try {
        $check = $dbh->query(
            "SELECT column_name FROM information_schema.columns
             WHERE table_name='tblvehicles' AND column_name='vehiclestitle' LIMIT 1"
        );
        if ($check && $check->rowCount() > 0) {
            return; // Schema is correct, nothing to do
        }
    } catch (Exception $e) {
        // Continue to initialise
    }

    // Drop old tables (wrong column case or missing entirely) and recreate
    $dbh->exec("BEGIN");
    try {
        $dbh->exec("DROP TABLE IF EXISTS tblbooking      CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tbltestimonial  CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tblsubscribers  CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tblvehicles     CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tblcontactusquery CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tblcontactusinfo  CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tblpages        CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tblbrands       CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS tblusers        CASCADE");
        $dbh->exec("DROP TABLE IF EXISTS admin           CASCADE");

        // admin
        $dbh->exec("CREATE TABLE admin (
            id SERIAL PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(100) NOT NULL,
            updationdate TIMESTAMP DEFAULT NULL
        )");
        $dbh->exec("INSERT INTO admin (id,username,password,updationdate) VALUES
            (1,'admin','0192023a7bbd73250516f069df18b500','2017-06-18 12:22:38')");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('admin','id'), MAX(id)) FROM admin");

        // tblusers
        $dbh->exec("CREATE TABLE tblusers (
            id SERIAL PRIMARY KEY,
            fullname VARCHAR(120) DEFAULT NULL,
            emailid VARCHAR(100) DEFAULT NULL,
            password VARCHAR(100) DEFAULT NULL,
            contactno VARCHAR(11) DEFAULT NULL,
            dob VARCHAR(100) DEFAULT NULL,
            address VARCHAR(255) DEFAULT NULL,
            city VARCHAR(100) DEFAULT NULL,
            country VARCHAR(100) DEFAULT NULL,
            regdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updationdate TIMESTAMP DEFAULT NULL
        )");
        $dbh->exec("INSERT INTO tblusers (id,fullname,emailid,password,contactno,dob,address,city,country,regdate,updationdate) VALUES
            (1,'Aditya','aditya@gmail.com','f925916e2754e5e03f75dd58a5733251','2147483647',NULL,NULL,NULL,NULL,'2017-06-17 19:59:27','2017-06-26 21:02:58'),
            (2,'Ansh','ansh@gmail.com','f925916e2754e5e03f75dd58a5733251','8285703354',NULL,NULL,NULL,NULL,'2017-06-17 20:00:49','2017-06-26 21:03:09'),
            (3,'Harish','harish@gmail.com','f09df7868d52e12bba658982dbd79821','9999857868','03/02/1990','PKL','PKL','PKL','2017-06-17 20:01:43','2017-06-17 21:07:41'),
            (4,'Ishaan','ishaan@gmail.com','5c428d8875d2948607f3e3fe134d71b4','9999857868','','PKL','XYZ','XYZ','2017-06-17 20:03:36','2017-06-26 19:18:14')");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblusers','id'), MAX(id)) FROM tblusers");

        // tblbrands
        $dbh->exec("CREATE TABLE tblbrands (
            id SERIAL PRIMARY KEY,
            brandname VARCHAR(120) NOT NULL,
            creationdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updationdate TIMESTAMP DEFAULT NULL
        )");
        $dbh->exec("INSERT INTO tblbrands (id,brandname,creationdate,updationdate) VALUES
            (1,'Maruti','2017-06-18 16:24:34','2017-06-19 06:42:23'),
            (2,'BMW','2017-06-18 16:24:50',NULL),
            (3,'Audi','2017-06-18 16:25:03',NULL),
            (4,'Nissan','2017-06-18 16:25:13',NULL),
            (5,'Toyota','2017-06-18 16:25:24',NULL),
            (6,'Honda','2017-06-19 06:22:13',NULL),
            (7,'kia','2017-06-20 06:21:13',NULL),
            (8,'Hyundai','2017-06-21 06:19:13',NULL)");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblbrands','id'), MAX(id)) FROM tblbrands");

        // tblvehicles
        $dbh->exec("CREATE TABLE tblvehicles (
            id SERIAL PRIMARY KEY,
            vehiclestitle VARCHAR(150) DEFAULT NULL,
            vehiclesbrand INT DEFAULT NULL,
            vehiclesoverview TEXT,
            priceperday INT DEFAULT NULL,
            fueltype VARCHAR(100) DEFAULT NULL,
            modelyear INT DEFAULT NULL,
            seatingcapacity INT DEFAULT NULL,
            vimage1 VARCHAR(120) DEFAULT NULL,
            vimage2 VARCHAR(120) DEFAULT NULL,
            vimage3 VARCHAR(120) DEFAULT NULL,
            vimage4 VARCHAR(120) DEFAULT NULL,
            vimage5 VARCHAR(120) DEFAULT NULL,
            airconditioner INT DEFAULT NULL,
            powerdoorlocks INT DEFAULT NULL,
            antilockbrakingsystem INT DEFAULT NULL,
            brakeassist INT DEFAULT NULL,
            powersteering INT DEFAULT NULL,
            driverairbag INT DEFAULT NULL,
            passengerairbag INT DEFAULT NULL,
            powerwindows INT DEFAULT NULL,
            cdplayer INT DEFAULT NULL,
            centrallocking INT DEFAULT NULL,
            crashsensor INT DEFAULT NULL,
            leatherseats INT DEFAULT NULL,
            regdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updationdate TIMESTAMP DEFAULT NULL
        )");
        $dbh->exec("INSERT INTO tblvehicles (id,vehiclestitle,vehiclesbrand,vehiclesoverview,priceperday,fueltype,modelyear,seatingcapacity,vimage1,vimage2,vimage3,vimage4,vimage5,airconditioner,powerdoorlocks,antilockbrakingsystem,brakeassist,powersteering,driverairbag,passengerairbag,powerwindows,cdplayer,centrallocking,crashsensor,leatherseats,regdate,updationdate) VALUES
            (1,'Hyundai Tucson',8,'The 2023 Hyundai Tucson is a popular crossover with improved standard features.',1500,'Petrol',2023,5,'Hyundai_1.1.avif','Hyundai_1.2.avif','Hyundai_1.3.avif','Hyundai_1.4.avif','Hyundai_1.5.avif',1,1,1,1,1,1,1,1,1,1,1,1,'2017-06-19 11:46:23','2017-06-20 18:38:13'),
            (2,'Honda CR-V',6,'The CR-V carries over mostly unchanged for 2025 with fresh styling and a more spacious cabin.',1200,'CNG',2022,4,'Honda_1.1.avif','Honda_1.2.avif','Honda_1.3.webp','Honda_1.4.avif','Honda_1.5.webp',1,1,1,1,1,1,1,NULL,1,1,NULL,NULL,'2017-06-19 16:16:17','2017-06-20 16:57:11'),
            (3,'Kia Niro',7,'The futuristic-looking Niro is Kia most affordable hybrid, also available as a plug-in hybrid and fully electric model.',1700,'Petrol',2022,5,'Kia_1.1.avif','Kia_1.2.avif','Kia_1.3.avif','Kia_1.4.avif','Kia_1.5.avif',1,1,1,1,1,1,NULL,1,1,NULL,NULL,NULL,'2017-06-19 16:18:20','2017-06-20 18:40:11'),
            (4,'Toyota Prius',5,'The 2024 Toyota Prius takes the middle path with its hybrid drivetrain that returns segment-leading fuel economy.',2000,'Petrol',2024,5,'Toyota_1.1.avif','Toyota_1.2.avif','Toyota_1.3.webp','Toyota_1.4.webp','Toyota_1.5.avif',1,1,1,1,1,1,1,1,1,NULL,NULL,NULL,'2017-06-19 16:18:43','2017-06-20 18:44:12'),
            (5,'Nissan Versa',4,'The Versa subcompact sedan is the least expensive Nissan you can buy.',1800,'Petrol',2023,4,'Nissan_1.1.avif','Nissan_1.2.avif','Nissan_1.3.avif','Nissan_1.4.avif','Nissan_1.5.webp',1,1,1,1,1,1,1,1,1,1,1,1,'2017-06-19 17:57:09','2017-06-20 16:56:43'),
            (6,'BMW 2 Series Gran Coupe',2,'The cheapest way to get a blue-and-white roundel on your hood.',2500,'Petrol',2023,4,'BMW_1.1.avif','BMW_1.2.webp','BMW_1.3.webp','BMW_1.4.avif','BMW_1.5.webp',1,1,1,1,1,1,1,1,1,1,1,1,'2017-06-19 17:57:09','2017-06-20 16:56:43'),
            (7,'BMW X1',2,'BMW continues to refine its delightful X1 subcompact SUV, delivering more tech for 2025.',2200,'Petrol',2024,4,'BMW_2.1.avif','BMW_2.2.avif','BMW_2.3.avif','BMW_2.4.avif','BMW_2.5.webp',1,1,1,1,1,1,1,1,1,1,1,1,'2017-06-19 17:57:09','2017-06-20 16:56:43'),
            (8,'Audi RS3',3,'Audi rambunctious performance sedan sits at the top of the subcompact A3 lineup.',3000,'Petrol',2024,4,'Audi_1.1.avif','Audi_1.2.avif','Audi_1.3.avif','Audi_1.4.avif','Audi_1.5.webp',1,1,1,1,1,1,1,1,1,1,1,1,'2017-06-19 17:57:09','2017-06-20 16:56:43'),
            (9,'Kia Seltos',7,'Kia Seltos slots above the Soul and below the Sportage in size and price.',1900,'Petrol',2022,4,'Kia_2.1.avif','Kia_2.2.avif','Kia_2.3.avif','Kia_2.4.avif','Kia_2.5.avif',1,1,1,1,1,1,1,1,1,1,1,1,'2017-06-19 17:57:09','2017-06-20 16:56:43'),
            (10,'Toyota RAV4 Hybrid',5,'We love the RAV4 Hybrid due to its refinement and fuel frugality.',2200,'Petrol',2023,4,'Toyota_2.1.avif','Toyota_2.2.avif','Toyota_2.3.avif','Toyota_2.4.webp','Toyota_2.5.avif',1,1,1,1,1,1,1,1,1,1,1,1,'2017-06-19 17:57:09','2017-06-20 16:56:43')");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblvehicles','id'), MAX(id)) FROM tblvehicles");

        // tblbooking
        $dbh->exec("CREATE TABLE tblbooking (
            id SERIAL PRIMARY KEY,
            useremail VARCHAR(100) DEFAULT NULL,
            vehicleid INT DEFAULT NULL,
            fromdate VARCHAR(20) DEFAULT NULL,
            todate VARCHAR(20) DEFAULT NULL,
            message VARCHAR(255) DEFAULT NULL,
            status INT DEFAULT NULL,
            postingdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
        $dbh->exec("INSERT INTO tblbooking (id,useremail,vehicleid,fromdate,todate,message,status,postingdate) VALUES
            (1,'test@gmail.com',2,'22/06/2017','25/06/2017','Lorem ipsum dolor sit amet.',1,'2017-06-19 20:15:43'),
            (2,'test@gmail.com',3,'30/06/2017','02/07/2017','Lorem ipsum dolor sit amet.',2,'2017-06-26 20:15:43'),
            (3,'test@gmail.com',4,'02/07/2017','07/07/2017','Lorem ipsum.',0,'2017-06-26 21:10:06')");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblbooking','id'), MAX(id)) FROM tblbooking");

        // tblcontactusinfo
        $dbh->exec("CREATE TABLE tblcontactusinfo (
            id SERIAL PRIMARY KEY,
            address TEXT,
            emailid VARCHAR(255) DEFAULT NULL,
            contactno VARCHAR(11) DEFAULT NULL
        )");
        $dbh->exec("INSERT INTO tblcontactusinfo (id,address,emailid,contactno) VALUES
            (1,'Test Demo','test@test.com','8585233222')");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblcontactusinfo','id'), MAX(id)) FROM tblcontactusinfo");

        // tblcontactusquery
        $dbh->exec("CREATE TABLE tblcontactusquery (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) DEFAULT NULL,
            emailid VARCHAR(120) DEFAULT NULL,
            contactnumber VARCHAR(11) DEFAULT NULL,
            message TEXT,
            postingdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            status INT DEFAULT NULL
        )");
        $dbh->exec("INSERT INTO tblcontactusquery (id,name,emailid,contactnumber,message,postingdate,status) VALUES
            (1,'Rajiv Kumar','rajiv@gmail.com','9999999999','Hi, I would like to extend my rental.','2017-06-18 10:03:07',1)");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblcontactusquery','id'), MAX(id)) FROM tblcontactusquery");

        // tblpages
        $dbh->exec("CREATE TABLE tblpages (
            id SERIAL PRIMARY KEY,
            pagename VARCHAR(255) DEFAULT NULL,
            type VARCHAR(255) NOT NULL DEFAULT '',
            detail TEXT NOT NULL DEFAULT ''
        )");
        $dbh->exec("INSERT INTO tblpages (id,pagename,type,detail) VALUES
            (1,'Terms and Conditions','terms','<p>Terms and Conditions content here.</p>'),
            (2,'Privacy Policy','privacy','<p>Privacy Policy content here.</p>'),
            (3,'About Us','aboutus','<p>Welcome to Rent Wheels, your trusted partner for hassle-free car rentals.</p>'),
            (11,'FAQs','faqs','<p>1. What documents do I need to rent a car? A valid driver license and ID.</p>')");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblpages','id'), 22)");

        // tblsubscribers
        $dbh->exec("CREATE TABLE tblsubscribers (
            id SERIAL PRIMARY KEY,
            subscriberemail VARCHAR(120) DEFAULT NULL,
            postingdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        $dbh->exec("INSERT INTO tblsubscribers (id,subscriberemail,postingdate) VALUES
            (1,'User@gmail.com','2017-06-22 16:35:32')");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tblsubscribers','id'), MAX(id)) FROM tblsubscribers");

        // tbltestimonial
        $dbh->exec("CREATE TABLE tbltestimonial (
            id SERIAL PRIMARY KEY,
            useremail VARCHAR(100) NOT NULL,
            testimonial TEXT NOT NULL,
            postingdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            status INT DEFAULT NULL
        )");
        $dbh->exec("INSERT INTO tbltestimonial (id,useremail,testimonial,postingdate,status) VALUES
            (1,'ishaan@gmail.com','I had a fantastic experience with Rent Wheels! The car was spotless and the process was seamless.','2017-06-18 07:44:31',1),
            (2,'aditya@gmail.com','This platform exceeded my expectations. Wide range of options and very competitive prices.','2017-06-18 07:46:05',1),
            (3,'ansh@gmail.com','Great variety of cars. The flexible rental options allowed me to extend my booking easily.','2017-06-18 07:46:05',1)");
        $dbh->exec("SELECT setval(pg_get_serial_sequence('tbltestimonial','id'), MAX(id)) FROM tbltestimonial");

        $dbh->exec("COMMIT");
    } catch (Exception $e) {
        $dbh->exec("ROLLBACK");
        // Silently fail — app will show empty content rather than crashing
    }
}

db_init_schema($dbh);

// Always ensure admin credentials are correct (fixes live DB without schema reset)
try {
    $dbh->exec("UPDATE admin SET username='admin', password='0192023a7bbd73250516f069df18b500' WHERE id=1");
} catch (Exception $e) {
    // Silently ignore — table may not exist yet (handled by db_init_schema above)
}
?>
