<?php
include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construction Services</title>
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
    background: rgba(0, 0, 0, 0.5); /* This makes the overlay semi-transparent */
    z-index: 1; /* Ensure the overlay stays under the text */
}

.hero-content {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    z-index: 2; /* This ensures the text appears above the overlay */
}

    </style>
</head>
<body>
<header class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/s1.webp" class="d-block w-100" alt="Bricklaying">
            </div>
            <div class="carousel-item">
                <img src="images/s2.jpg" class="d-block w-100" alt="Masonry">
            </div>
            <div class="carousel-item">
                <img src="images/s3.png" class="d-block w-100" alt="Plastering">
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
        <h1 class="display-4 fw-bold">Professional Construction Services</h1>
        <p class="lead">Building strong foundations with expert professionals</p>
    </div>
</header>


<!-- Step Sections -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Step 1:Foundation and Column Construction</h2>
        <p class="text-center">Structural Concrete Worker do the process of laying the structural base (foundation) and erecting vertical supports (columns) that transfer the building’s load to the ground. This step ensures the stability, strength, and durability of the entire structure, forming the essential framework for the building.</p>
        <div class="row" id="bricklayersList"></div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Step 2: Masonry</h2>
        <p class="text-center">Masons specialize in laying concrete blocks or stones for robust structures.</p>
        <div class="row" id="masonsList"></div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Step 3: Plastering</h2>
        <p class="text-center">Plastering experts apply finishes to walls and ceilings for a smooth look.</p>
        <div class="row" id="plasterersList"></div>
    </div>
</section>

<script>
    const professionals = {
        bricklayers: [
            { name: "John Smith", experience: "10 years", image: "images/bricklayer.jpg" },
            { name: "Emily Johnson", experience: "8 years", image: "images/bricklayer2.jpg" }
        ],
        masons: [
            { name: "Michael Brown", experience: "12 years", image: "images/mason.jpg" },
            { name: "Sarah Wilson", experience: "9 years", image: "images/mason2.jpg" }
        ],
        plasterers: [
            { name: "David Lee", experience: "15 years", image: "images/plasterer.jpg" },
            { name: "Anna Taylor", experience: "7 years", image: "images/plasterer2.jpg" }
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
        loadProfessionals(professionals.bricklayers, "bricklayersList");
        loadProfessionals(professionals.masons, "masonsList");
        loadProfessionals(professionals.plasterers, "plasterersList");
    });
</script>

</body>
</html>
<?php
include ("footer.php");
?>