<!-- Clarita Antoun -->
<?php
include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plumbing Services</title>
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
                    <img src="images/p2.jpeg" class="d-block w-100" alt="Plumbing Work">
                </div>
                <div class="carousel-item">
                    <img src="images/p1.webp" class="d-block w-100" alt="Plumbing Tools">
                </div>
                <div class="carousel-item">
                    <img src="images/p3.jpeg" class="d-block w-100" alt="Water Pipe Installation">
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-white text-center">
                    <h1 class="display-3 fw-bold black">Plumbing Services</h1>
                    <p class="lead mb-4 black">Expert plumbing solutions for every need</p>
                    <a href="#plumbers" class="buttonDesign btn btn-primary btn-lg">Find a Plumber</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Plumbing Steps Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Step 1: Plumbing Engineer Designs the Plumbing System</h2>
            <p class="text-center">Our experienced plumbing engineers carefully design an efficient and durable plumbing system tailored to your needs. They ensure that all systems are up to code and function optimally.</p>
            <div class="row" id="engineersList"></div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Step 2: Plumber Installs and Repairs Pipes and Fixtures</h2>
            <p class="text-center">Our skilled plumbers install and repair pipes, fittings, and fixtures, ensuring your plumbing system works flawlessly. Whether it's a new installation or a repair job, our plumbers get the job done right.</p>
            <div class="row" id="plumbersList"></div>
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="btn btn-primary">Load More Plumbers</button>
            </div>
        </div>
    </section>

    <script>
        const engineers = [
            {
                name: "Michael Davis",
                specialization: "Plumbing System Design",
                experience: "25 years",
                image: "images/engineer1.jpg",
                rating: 4.9
            },
            {
                name: "Sophia Johnson",
                specialization: "Plumbing System Optimization",
                experience: "18 years",
                image: "images/engineer2.jpg",
                rating: 4.8
            },
            {
                name: "David Brown",
                specialization: "Plumbing Engineering Consultant",
                experience: "22 years",
                image: "images/engineer3.jpg",
                rating: 4.7
            }
        ];

        const plumbers = [
            {
                name: "John Parker",
                specialization: "Pipe Installation & Repair",
                experience: "20 years",
                image: "images/plumber1.jpg",
                rating: 4.9
            },
            {
                name: "Olivia Smith",
                specialization: "Water Heater Installation",
                experience: "15 years",
                image: "images/plumber2.jpg",
                rating: 4.8
            },
            {
                name: "Daniel Lee",
                specialization: "Drain Cleaning & Leak Repairs",
                experience: "18 years",
                image: "images/plumber3.jpg",
                rating: 4.7
            }
        ];

        let currentPage = 0;
        const plumbersPerPage = 3;

        function createEngineerCard(engineer) {
            return `
                <div class="col-lg-4 col-md-6">
                    <div class="card engineer-card">
                        <img src="${engineer.image}" class="card-img-top" alt="${engineer.name}">
                        <div class="card-body">
                            <h5 class="card-title">${engineer.name}</h5>
                            <p class="card-text">
                                <strong>Specialization:</strong> ${engineer.specialization}<br>
                                <strong>Experience:</strong> ${engineer.experience}<br>
                                <strong>Rating:</strong> ${engineer.rating} ⭐
                            </p>
                            <button class="btn btn-primary" onclick="contactEngineer('${engineer.name}')">
                                Contact Engineer
                            </button>
                        </div>
                    </div>
                </div>`;
        }

        function createPlumberCard(plumber) {
            return `
                <div class="col-lg-4 col-md-6">
                    <div class="card plumber-card">
                        <img src="${plumber.image}" class="card-img-top" alt="${plumber.name}">
                        <div class="card-body">
                            <h5 class="card-title">${plumber.name}</h5>
                            <p class="card-text">
                                <strong>Specialization:</strong> ${plumber.specialization}<br>
                                <strong>Experience:</strong> ${plumber.experience}<br>
                                <strong>Rating:</strong> ${plumber.rating} ⭐
                            </p>
                            <button class="btn btn-primary" onclick="contactPlumber('${plumber.name}')">
                                Contact Plumber
                            </button>
                        </div>
                    </div>
                </div>`;
        }

        function loadEngineers() {
            const engineersList = document.getElementById('engineersList');
            engineers.forEach(engineer => {
                engineersList.innerHTML += createEngineerCard(engineer);
            });
        }

        function loadPlumbers() {
            const startIndex = currentPage * plumbersPerPage;
            const endIndex = startIndex + plumbersPerPage;
            const plumbersToShow = plumbers.slice(startIndex, endIndex);
            const plumbersList = document.getElementById('plumbersList');
            plumbersToShow.forEach(plumber => {
                plumbersList.innerHTML += createPlumberCard(plumber);
            });
            if (endIndex >= plumbers.length) {
                document.getElementById('loadMoreBtn').style.display = 'none';
            }
        }

        function contactEngineer(name) {
            alert(`Contacting ${name}. In a real application, this would open a contact form or messaging system.`);
        }

        function contactPlumber(name) {
            alert(`Contacting ${name}. In a real application, this would open a contact form or messaging system.`);
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadEngineers();
            loadPlumbers();
            document.getElementById('loadMoreBtn').addEventListener('click', () => {
                currentPage++;
                loadPlumbers();
            });
        });
    </script>
</body>
</html>
<?php
include ("footer.php");
?>
