
<?php
include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiling Services</title>
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
                    <img src="images/t1.jpeg" class="d-block w-100" alt="Tiling Work">
                </div>
                <div class="carousel-item">
                    <img src="images/t2.jpeg" class="d-block w-100" alt="Tiling Tools">
                </div>
                <div class="carousel-item">
                    <img src="images/t3.jpg" class="d-block w-100" alt="Tile Installation">
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-white text-center">
                    <h1 class="display-3 fw-bold black">Tiling Services</h1>
                    <p class="lead mb-4 black">Professional tile installation for floors, walls, and more</p>
                    <a href="#tilers" class="buttonDesign btn btn-primary btn-lg">Find a Tiler</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Tilers Section -->
    <section id="tilers" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Find Professional Tilers</h2>
            <div class="row" id="tilersList"></div>
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="btn btn-primary">Load More Tilers</button>
            </div>
        </div>
    </section>

    <script>
        const tilers = [
            {
                name: "James Wilson",
                specialization: "Floor & Wall Tiling",
                experience: "15 years",
                image: "images/tiler1.jpg",
                rating: 4.8
            },
            {
                name: "Sophia Johnson",
                specialization: "Backsplash & Decorative Tiling",
                experience: "12 years",
                image: "images/tiler2.jpg",
                rating: 4.7
            },
            {
                name: "Liam Brown",
                specialization: "Ceramic & Porcelain Tiling",
                experience: "10 years",
                image: "images/tiler3.jpg",
                rating: 4.9
            }
        ];

        let currentPage = 0;
        const tilersPerPage = 3;

        function createTilerCard(tiler) {
            return `
                <div class="col-lg-4 col-md-6">
                    <div class="card tiler-card">
                        <img src="${tiler.image}" class="card-img-top" alt="${tiler.name}">
                        <div class="card-body">
                            <h5 class="card-title">${tiler.name}</h5>
                            <p class="card-text">
                                <strong>Specialization:</strong> ${tiler.specialization}<br>
                                <strong>Experience:</strong> ${tiler.experience}<br>
                                <strong>Rating:</strong> ${tiler.rating} ⭐
                            </p>
                            <button class="btn btn-primary" onclick="contactTiler('${tiler.name}')">
                                Contact Tiler
                            </button>
                        </div>
                    </div>
                </div>`;
        }

        function loadTilers() {
            const startIndex = currentPage * tilersPerPage;
            const endIndex = startIndex + tilersPerPage;
            const tilersToShow = tilers.slice(startIndex, endIndex);
            const tilersList = document.getElementById('tilersList');
            tilersToShow.forEach(tiler => {
                tilersList.innerHTML += createTilerCard(tiler);
            });
            if (endIndex >= tilers.length) {
                document.getElementById('loadMoreBtn').style.display = 'none';
            }
        }

        function contactTiler(name) {
            alert(`Contacting ${name}. In a real application, this would open a contact form or messaging system.`);
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadTilers();
            document.getElementById('loadMoreBtn').addEventListener('click', () => {
                currentPage++;
                loadTilers();
            });
        });
    </script>
</body>
</html>
<?php
include ("footer.php");
?>
