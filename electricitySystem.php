<?php
include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electrical Services</title>
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
                <img src="images/e3.jpg" class="d-block w-100" alt="Electrical System Design">
            </div>
            <div class="carousel-item">
                <img src="images/e2.jpg.crdownload" class="d-block w-100" alt="Wiring Installation">
            </div>
            <div class="carousel-item">
                <img src="images/e1.jpeg" class="d-block w-100" alt="Electrical Work">
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
        <h1 class="display-4 fw-bold">Electrical Services</h1>
        <p class="lead">Professional electrical engineers and electricians for all your electrical needs</p>
    </div>
</header>

<!-- Step Sections -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Step 1: Electrical System Design</h2>
        <p class="text-center">An electrical engineer designs the electrical system to ensure a safe, efficient, and code-compliant installation.</p>
        <div class="row" id="engineersList"></div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Step 2: Wiring & Installations</h2>
        <p class="text-center">Electricians handle all wiring and installation of electrical fixtures, ensuring proper functionality and safety.</p>
        <div class="row" id="electriciansList"></div>
    </div>
</section>

<script>
    const professionals = {
        engineers: [
            { name: "James Williams", experience: "10 years", image: "images/engineer1.jpg" },
            { name: "Sophia Anderson", experience: "8 years", image: "images/engineer2.jpg" }
        ],
        electricians: [
            { name: "Michael Johnson", experience: "15 years", image: "images/electrician1.jpg" },
            { name: "Olivia Martinez", experience: "12 years", image: "images/electrician2.jpg" }
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
        loadProfessionals(professionals.engineers, "engineersList");
        loadProfessionals(professionals.electricians, "electriciansList");
    });
</script>

</body>
</html>
<?php
include ("footer.php");
?>
