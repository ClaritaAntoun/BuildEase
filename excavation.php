<?php
include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excavation Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .hero-section {
            position: relative;
            height: 100vh;
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
        .black {
            color: black;
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
    </style>
</head>
<body>
    <!-- Hero Section with Background Image Carousel -->
    <header class="hero-section position-relative">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/g2.jpg" class="d-block w-100" alt="Excavation Work">
                </div>
                <div class="carousel-item">
                    <img src="images/g1.jpeg" class="d-block w-100" alt="Heavy Equipment">
                </div>
                <div class="carousel-item">
                    <img src="images/g3.jpeg" class="d-block w-100" alt="Construction Site">
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-white text-center">
                    <h1 class="display-3 fw-bold black">Excavation Services</h1>
                    <p class="lead mb-4 black">Efficient land clearing, earth removal, and site preparation</p>
                    <a href="#excavators" class="buttonDesign btn btn-primary btn-lg">Find an Excavator</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Professionals Section -->
    <section id="excavators" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Find Professional Excavators</h2>
            <div class="row" id="excavatorsList"></div>
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="btn btn-primary">Load More Excavators</button>
            </div>
        </div>
    </section>

    <script>
        const excavators = [
            {
                name: "Jake Wilson",
                specialization: "Earth Moving & Grading",
                experience: "18 years",
                image: "images/excavator1.jpg",
                rating: 4.9
            },
            {
                name: "Emma Davis",
                specialization: "Site Preparation & Digging",
                experience: "14 years",
                image: "images/excavator2.jpg",
                rating: 4.8
            },
            {
                name: "Chris Brown",
                specialization: "Rock & Soil Removal",
                experience: "12 years",
                image: "images/excavator3.jpg",
                rating: 4.7
            }
        ];
        let currentPage = 0;
        const excavatorsPerPage = 3;
        function createExcavatorCard(excavator) {
            return `
                <div class="col-lg-4 col-md-6">
                    <div class="card surveyor-card">
                        <img src="${excavator.image}" class="card-img-top" alt="${excavator.name}">
                        <div class="card-body">
                            <h5 class="card-title">${excavator.name}</h5>
                            <p class="card-text">
                                <strong>Specialization:</strong> ${excavator.specialization}<br>
                                <strong>Experience:</strong> ${excavator.experience}<br>
                                <strong>Rating:</strong> ${excavator.rating} ⭐
                            </p>
                            <button class="btn btn-primary" onclick="contactExcavator('${excavator.name}')">
                                Contact Excavator
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        function loadExcavators() {
            const startIndex = currentPage * excavatorsPerPage;
            const endIndex = startIndex + excavatorsPerPage;
            const excavatorsToShow = excavators.slice(startIndex, endIndex);
            const excavatorsList = document.getElementById('excavatorsList');
            excavatorsToShow.forEach(excavator => {
                excavatorsList.innerHTML += createExcavatorCard(excavator);
            });
            if (endIndex >= excavators.length) {
                document.getElementById('loadMoreBtn').style.display = 'none';
            }
        }
        function contactExcavator(name) {
            alert(`Contacting ${name}. In a real application, this would open a contact form or messaging system.`);
        }
        document.addEventListener('DOMContentLoaded', () => {
            loadExcavators();
            document.getElementById('loadMoreBtn').addEventListener('click', () => {
                currentPage++;
                loadExcavators();
            });
        });
    </script>
</body>
</html>
<?php
include ("footer.php");
?>