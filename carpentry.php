<?php
include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpentry Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .hero-section {
            position: relative;
            height: 80vh;
            overflow: hidden;
        }

        .hero-section .carousel img {
            height: 80vh;
            object-fit: cover;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero-content {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 2;
        }

    </style>
</head>
<body>
<header class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/ca1.avif" class="d-block w-100" alt="Woodworking">
            </div>
            <div class="carousel-item">
                <img src="images/ca2.webp" class="d-block w-100" alt="Framing">
            </div>
            <div class="carousel-item">
                <img src="images/ca3.jpeg" class="d-block w-100" alt="Finishing Work">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="overlay"></div>
    <div class="hero-content">
        <h1 class="display-4 fw-bold">Carpentry Services</h1>
        <p class="lead">Skilled carpenters for building and installing wooden frameworks and fixtures</p>
    </div>
</header>

<!-- Step Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Step 1: Building & Installing Wooden Frameworks and Fixtures</h2>
        <p class="text-center">Carpenters expertly build and install wooden structures such as frameworks, doors, windows, shelves, and custom fixtures to meet your design needs.</p>
        <div class="row" id="carpentersList"></div>
    </div>
</section>

<script>
    const carpenters = [
        { name: "John Doe", experience: "10 years", image: "images/woodworker1.jpg" },
        { name: "Sarah Williams", experience: "8 years", image: "images/woodworker2.jpg" }
    ];

    function loadCarpenters(carpenters, containerId) {
        const container = document.getElementById(containerId);
        carpenters.forEach(pro => {
            container.innerHTML += `
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <img src="${pro.image}" class="card-img-top" alt="${pro.name}">
                        <div class="card-body">
                            <h5 class="card-title">${pro.name}</h5>
                            <p class="card-text">Experience: ${pro.experience}</p>
                            <button class="btn btn-primary">Contact</button>
                        </div>
                    </div>
                </div>`;
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        loadCarpenters(carpenters, "carpentersList");
    });
</script>

</body>
</html>
<?php
include ("footer.php");
?>
