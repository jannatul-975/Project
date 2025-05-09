<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Khulna University Guest House</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Hero Section */
        .hero {
            background-color: #0077b3;
            color: white;
            text-align: center;
            padding: 50px 0;
        }

        .hero h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
        }

        /* About Section */
        .about {
            padding: 40px 0;
            background-color: white;
            text-align: center;
        }

        .about .container {
            width: 80%;
            margin: 0 auto;
        }

        .about h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .about p {
            font-size: 18px;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        /* Facilities Section */
        .facilities {
            text-align: left;
            margin: 30px 0;
        }

        .facilities h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .facilities ul {
            list-style: none;
            padding-left: 0;
        }

        .facilities ul li {
            font-size: 16px;
            margin: 10px 0;
        }

        /* Mission Section */
        .mission {
            text-align: left;
            margin: 30px 0;
        }

        .mission h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .mission p {
            font-size: 18px;
        }
    </style>
</head>

<body>
    <!-- Include Header -->
    <?php include("header.php"); ?>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Welcome to Khulna University Guest House</h1>
        <p>Your comfortable stay in the heart of Khulna University campus</p>
    </section>

    <!-- About Section -->
    <section class="about">
        <div class="container">
            <h2>About the Guest House</h2>
            <p>The Khulna University Guest House offers a peaceful and serene environment for visitors to the university. Located within the campus, the guest house provides comfortable accommodations with all necessary amenities to ensure a pleasant stay for guests, faculty, staff, and alumni.</p>
            
            <div class="facilities">
                <h3>Our Facilities</h3>
                <ul>
                    <li>Spacious rooms with modern furniture</li>
                    <li>24/7 security and emergency services</li>
                    <li>Wi-Fi access throughout the guest house</li>
                    <li>Conference and event rooms</li>
                    <li>On-site dining facilities</li>
                    <li>Friendly and professional staff</li>
                </ul>
            </div>

            <div class="mission">
                <h3>Our Mission</h3>
                <p>To provide a comfortable, convenient, and welcoming environment for all university guests, ensuring their stay is memorable and enjoyable while maintaining the highest standards of service and hospitality.</p>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include("footer_index.php"); ?>
</body>
</html>
