
<?php include("header.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Land Surveying Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Custom styles */
        .hero-section {
            height: 100vh;
            background: url('https://images.unsplash.com/photo-1595658658481-d53d3f999875?auto=format&fit=crop&q=80&w=2070') center/cover no-repeat;
            position: relative;
            margin-bottom: 2rem;
        }

        .hero-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .service-icon {
            height: 200px;
            overflow: hidden;
        }

        .service-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .surveyor-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .surveyor-card img {
            height: 200px;
            object-fit: cover;
        }

        /* Navbar adjustments */
        .navbar {
            padding: 1rem 0;
            transition: background-color 0.3s ease;
        }

        /* Form styling */
        .form-control {
            padding: 0.75rem;
            border-radius: 0.5rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                height: 70vh;
            }

            .display-3 {
                font-size: 2.5rem;
            }
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
.black{
    color: black;
}
    </style>
</head>
<body>

   <!-- Hero Section with Background Image Carousel -->
<header class="hero-section position-relative">
    <!-- Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/l1.webp" class="d-block w-100" alt="Surveying">
            </div>
            <div class="carousel-item">
                <img src="images/l2.jpg" class="d-block w-100" alt="Survey Equipment">
            </div>
            <div class="carousel-item">
                <img src="images/l3.webp" class="d-block w-100" alt="Land Surveying">
            </div>
        </div>

        <!-- Controls (Prev/Next Buttons) -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- Hero Content -->
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 text-white text-center">
                <h1 class="display-3 fw-bold black">Professional Land Surveying Services</h1>
                <p class="lead mb-4 black">Precise measurements, accurate results, professional service</p>
                <a href="#professionals" class="buttonDesign btn btn-primary btn-lg">Find a Surveyor</a>
            </div>
        </div>
    </div>
</header>

    

    <!-- Introduction Section -->
    <section id="introduction" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Land Surveying is Crucial for Building</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <p class="lead text-center">
                        Land surveying is the first and most critical step in any construction project. It ensures that the land is properly measured, boundaries are accurately defined, and potential issues are identified before construction begins. Without proper surveying, projects can face legal disputes, costly delays, and structural failures.
                    </p>
                    <p class="text-center">
                        Our team of professional surveyors specializes in boundary mapping, topographic surveys, and construction layout to ensure your project starts on the right foundation.
                    </p>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Professionals Section -->
    <section id="professionals" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Find Professional Surveyors</h2>
            <div class="row" id="surveyorsList">
                <!-- Surveyors will be loaded here via JavaScript -->
            </div>
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="btn btn-primary">Load More Surveyors</button>
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

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 GeoSurvey Pro. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data for surveyors
        const surveyors = [
            {
                name: "John Smith",
                specialization: "Boundary Mapping",
                experience: "15 years",
                image: "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=300",
                rating: 4.8
            },
            {
                name: "Sarah Johnson",
                specialization: "Boundary Mapping",
                experience: "12 years",
                image: "https://images.unsplash.com/photo-1573496799652-408c2ac9fe98?auto=format&fit=crop&q=80&w=300",
                rating: 4.9
            },
            {
                name: "Michael Brown",
                specialization: " Boundary Mapping",
                experience: "10 years",
                image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=300",
                rating: 4.7
            }
        ];

        let currentPage = 0;
        const surveyorsPerPage = 3;

        // Function to create surveyor cards
        function createSurveyorCard(surveyor) {
            return `
                <div class="col-lg-4 col-md-6">
                    <div class="card surveyor-card">
                        <img src="${surveyor.image}" class="card-img-top" alt="${surveyor.name}">
                        <div class="card-body">
                            <h5 class="card-title">${surveyor.name}</h5>
                            <p class="card-text">
                                <strong>Specialization:</strong> ${surveyor.specialization}<br>
                                <strong>Experience:</strong> ${surveyor.experience}<br>
                                <strong>Rating:</strong> ${surveyor.rating} ⭐
                            </p>
                            <button class="btn btn-primary" onclick="contactSurveyor('${surveyor.name}')">
                                Contact Surveyor
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Function to load surveyors
        function loadSurveyors() {
            const startIndex = currentPage * surveyorsPerPage;
            const endIndex = startIndex + surveyorsPerPage;
            const surveyorsToShow = surveyors.slice(startIndex, endIndex);

            const surveyorsList = document.getElementById('surveyorsList');

            surveyorsToShow.forEach(surveyor => {
                surveyorsList.innerHTML += createSurveyorCard(surveyor);
            });

            // Hide "Load More" button if no more surveyors
            if (endIndex >= surveyors.length) {
                document.getElementById('loadMoreBtn').style.display = 'none';
            }
        }

        // Contact surveyor function
        function contactSurveyor(name) {
            alert(`Contacting ${name}. In a real application, this would open a contact form or messaging system.`);
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Load initial surveyors
            loadSurveyors();

            // Load More button click handler
            document.getElementById('loadMoreBtn').addEventListener('click', () => {
                currentPage++;
                loadSurveyors();
            });

            // Contact form submission
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    alert('Thank you for your message! We will get back to you soon.');
                    contactForm.reset();
                });
            }

            // Navbar scroll effect
            window.addEventListener('scroll', () => {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.backgroundColor = '#1a1a1a';
                } else {
                    navbar.style.backgroundColor = 'rgba(33, 37, 41, 0.9)';
                }
            });
        });
    </script>
</body>
</html>
<?php
include("footer.php");
?>