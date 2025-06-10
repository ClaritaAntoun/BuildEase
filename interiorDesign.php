<!-- Clarita Antoun -->
<?php
include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interior Design Services</title>
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
                <img src="images/pa1.jpeg" class="d-block w-100" alt="Interior Design">
            </div>
            <div class="carousel-item">
                <img src="images/pa2.jpeg" class="d-block w-100" alt="Painting">
            </div>
            <div class="carousel-item">
                <img src="images/pa3.webp" class="d-block w-100" alt="Painting">
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
        <h1 class="display-4 fw-bold">Interior Design Services</h1>
        <p class="lead">Transform your space with expert design and finishing</p>
    </div>
</header>

<!-- Step 13: Interior Design Planning -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Step 13: Interior Design Planning</h2>
        <p class="text-center">Interior Designers plan color schemes, finishes, and aesthetic details to create a perfect ambiance.</p>
        <div class="row" id="interiorDesignersList"></div>
    </div>
</section>

<!-- Step 14: Painting & Finishing -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Step 14: Painting & Finishing</h2>
        <p class="text-center">Painters apply high-quality finishes to walls, ceilings, and surfaces to bring designs to life.</p>
        <div class="row" id="paintersList"></div>
    </div>
</section>

<script>
    const professionals = {
        interiorDesigners: [
            { name: "Sophia Martinez", experience: "11 years", image: "images/interior.jpg" },
            { name: "Liam Anderson", experience: "9 years", image: "images/interior2.jpg" }
        ],
        painters: [
            { name: "Ethan Harris", experience: "10 years", image: "images/painter.jpg" },
            { name: "Olivia White", experience: "7 years", image: "images/painter2.jpg" }
        ]
    };

    function loadProfessionals(professionals, containerId) {
        const container = document.getElementById(containerId);
        professionals.forEach(pro => {
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
        loadProfessionals(professionals.interiorDesigners, "interiorDesignersList");
        loadProfessionals(professionals.painters, "paintersList");
    });
</script>

</body>
</html>
<?php
include("footer.php");
?>
