<?php
include "header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Civil Engineering - Home Plan Design</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Custom styles */
        .hero-section {
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .hero-section .carousel {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .hero-section .carousel img {
            height: 100vh;
            object-fit: cover;
        }

        .hero-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .hero-section .container {
            position: relative;
            z-index: 2;
        }

        .buttonDesign {
            width: 100%;
            padding: 12px 20px;
            background: grey;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        }
        .buttonDesign:hover {
            background: orangered;
            transform: scale(1.05);
        }
        .buttonDesign:active {
            background: orange;
            transform: scale(0.98);
        }
        .black{
            color: black;
        }
    </style>
</head>
<body>

   <!-- Hero Section with Background Image Carousel -->
<header class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/ci1.jpg" class="d-block w-100" alt="Home Planning">
            </div>
            <div class="carousel-item">
                <img src="images/ci2.webp" class="d-block w-100" alt="Architectural Drawings">
            </div>
            <div class="carousel-item">
                <img src="images/ci3.avif" class="d-block w-100" alt="Structural Design">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <div class="overlay"></div>

    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 text-white text-center">
                <h1 class="display-3 fw-bold black">Expert Civil Engineers for Home Plan Design</h1>
                <p class="lead mb-4 black"><b>Transforming your surveyed land into well-designed home plans</b></p>
                <a href="#engineers" class="buttonDesign btn btn-primary btn-lg">Find a Civil Engineer</a>
            </div>
        </div>
    </div>
</header>

<!-- Introduction Section -->
<section id="introduction" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">The Role of Civil Engineers in Home Plan Design</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <p class="lead text-center">
                    Once your land is surveyed, a civil engineer takes over to design a home plan that is structurally sound and complies with regulations. They ensure the optimal use of space, proper foundation, and aesthetic appeal of your home.
                </p>
                <p class="text-center">
                    Our experienced civil engineers specialize in residential home design, structural planning, and compliance with local building codes.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Professionals Section -->
<section id="engineers" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Find Professional Civil Engineers</h2>
        <div class="row" id="engineersList">
            <!-- Engineers will be loaded here via JavaScript -->
        </div>
        <div class="text-center mt-4">
            <button id="loadMoreBtn" class="btn btn-primary">Load More Engineers</button>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <h2 class="text-center mb-4">Contact Us</h2>
                <form id="contactForm">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Your Email" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const engineers = [
        {
            name: "Alice Brown",
            specialization: "Residential Home Design",
            experience: "10 years",
            image: "images/eng1.jpg",
            rating: 4.9
        },
        {
            name: "David Wilson",
            specialization: "Structural Planning",
            experience: "15 years",
            image: "images/eng2.jpg",
            rating: 4.8
        },
        {
            name: "Emma Taylor",
            specialization: "Architectural Drawings",
            experience: "12 years",
            image: "images/eng3.jpg",
            rating: 4.7
        }
    ];

    let currentPage = 0;
    const engineersPerPage = 3;

    function createEngineerCard(engineer) {
        return `
            <div class="col-lg-4 col-md-6">
                <div class="card surveyor-card">
                    <img src="${engineer.image}" class="card-img-top" alt="${engineer.name}">
                    <div class="card-body">
                        <h5 class="card-title">${engineer.name}</h5>
                        <p class="card-text">
                            <strong>Specialization:</strong> ${engineer.specialization}<br>
                            <strong>Experience:</strong> ${engineer.experience}<br>
                            <strong>Rating:</strong> ${engineer.rating} ⭐
                        </p>
                        <button class="btn btn-primary">Contact Engineer</button>
                    </div>
                </div>
            </div>
        `;
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('engineersList').innerHTML = engineers.map(createEngineerCard).join('');
    });
</script>

</body>
</html>
<?php
include "footer.php";
?>
