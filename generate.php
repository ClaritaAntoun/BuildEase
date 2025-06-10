
<!-- Clarita Antoun -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
  }
  $projectID = isset($_GET['projectID']) ? $_GET['projectID'] : null;

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Your Home Design with AI</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Add this to your Google Fonts link -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
   
  <style>
  #sendButton {
      background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 30px;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }
    
    #sendButton:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
      background: linear-gradient(135deg, #43A047 0%, #1B5E20 100%);
    }
    
    #sendButton:active {
      transform: translateY(0);
    }
    
    #sendButton i {
      margin-right: 8px;
    }
    
    /* Add this to make the buttons align nicely */
    .button-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
    }
      h2 {
      color: #f5a623 !important;
      border-bottom: 2px solid #f5a623;
      padding-bottom: 0.5rem;
    }
    body {
        font-family: 'Playfair Display', serif;
        background:rgba(255, 255, 255, 0.8)1a;
        color: #495057;
    }

    /* Header Top Bar */
    .header-area {
        background: #495057; /* Deep navy blue */
        padding: 12px 0;
        color:orange;
        font-family: 'Poppins', sans-serif;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    }

    .header-left a, .header-right ul li a {
        color:orange;
        font-weight: 500;
        transition: 0.3s;
        font-size: 15px;
    }
    .header-left a:hover, .header-right ul li a:hover {
        color: #FFFFFF;
        text-decoration: none;
    }

    /* Navigation Bar */
    .navigation {
        background: #FFFFFF;
        padding: 20px 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        border-bottom: 2px solid plum;
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: 0.5px;
    }
    .logo span {
        color:orange;
        font-weight: 400;
    }

    .navbar-nav .nav-link {
        color: #1a1a1a !important;
        font-weight: 500;
        padding: 15px 20px !important;
        transition: 0.3s;
        font-size: 16px;
        position: relative;
    }

    .navbar-nav .nav-link:hover, 
    .navbar-nav .nav-item.active .nav-link {
        color:yellow;
    }

    .navbar-nav .nav-link:hover::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background:orange;
    }

    /* Dropdown Menu */
    .dropdown-menu {
        background: #FFFFFF;
        border: 1px solid rgba(26, 42, 58, 0.1);
        border-radius: 4px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: 10px !important;
    }
    .dropdown-menu .dropdown-item {
        color: #1a1a1a !important;
        padding: 12px 25px;
        font-size: 15px;
        transition: 0.3s;
    }
    header {
  width: 100%;
  margin: 0;
  padding: 0;
}

    .dropdown-menu .dropdown-item:hover {
        background: #F8F9FA;
        color:orangered !important;
    }

    /* Call to Action Button */
    .appoint-btn a {
        background:orange;
        color: #1a1a1a !important;
        padding: 14px 30px;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
        letter-spacing: 0.5px;
        border: 2px solid transparent;
    }
    .appoint-btn a:hover {
        background: #1a1a1a;
        color: #FFFFFF !important;
        border-color:yellow;
        transform: translateY(-2px);
    }
    h5{
      text-decoration: underline;
            color: orange;
            font-weight: bolder;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
    body {
      background: linear-gradient(to right, #141e30, #243b55);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #fff;
      text-align: center;
      padding: 50px;
    }

    h2 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    textarea {
      width: 60%;
      height: 100px;
      padding: 10px;
      font-size: 16px;
      border-radius: 10px;
      border: none;
      resize: none;
      outline: none;
    }

    button {
      margin-top: 20px;
      padding: 12px 30px;
      font-size: 18px;
      background-color: #00c3ff;
      color: #000;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
    }

    button:hover {
      background-color: #00a3cc;
    }

    #result img {
      margin-top: 20px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.6);
    }
    .btn-ai {
    background: linear-gradient(135deg, #e28e1e 0%, #ff6b6b 100%);
    color: #fff !important;
    border-radius: 25px;
    padding: 8px 20px;
    margin-left: 15px;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    width: 120px;
}

.btn-ai:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(226,142,30,0.3);
    color: #fff !important;
}

.btn-ai i {
    font-size: 1.1em;
    transition: transform 0.3s ease;
}

.btn-ai:hover i {
    transform: scale(1.1);
}
  </style>
</head>
<body>
  
           
            <li class="nav-item ms-2">
    <a href="homeOwnerPage.php" class="btn btn-ai">
        <i class="fas fa-robot me-2"></i> 
        Go back
    </a>
</li>

  <br><br>
  <h2>Describe your dream house:</h2>
  <textarea id="prompt" placeholder="e.g., A futuristic glass house on a mountain"></textarea>
  <br>



  <div class="button-container">
    <button onclick="generateImage()" class="generate-btn">Generate Design</button>
    <button id="sendButton" style="display:none;" onclick="sendToProject()">
      <i class="fas fa-paper-plane"></i> Send to Contractor
    </button>
  </div>
  <div id="result"></div>
  <script>
     const projectID = <?php echo json_encode($projectID); ?>;
    let generatedImageBlob = null;
    
    async function generateImage() {
      const prompt = document.getElementById('prompt').value;
      const resultDiv = document.getElementById('result');
      const sendButton = document.getElementById('sendButton');
      
      if (!prompt) {
        alert('Please describe your dream house first!');
        return;
      }
      
      resultDiv.innerHTML = '<div class="text-white">Generating your design... <i class="fas fa-spinner fa-spin"></i></div>';
      sendButton.style.display = 'none';
      
      try {
        const response = await fetch("https://api-inference.huggingface.co/models/black-forest-labs/FLUX.1-dev", {
          method: "POST",
          headers: {
            "Authorization": "Bearer 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",
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ inputs: prompt })
        });
        
        if (!response.ok) throw new Error('Failed to generate image');
        
        generatedImageBlob = await response.blob();
        const imageUrl = URL.createObjectURL(generatedImageBlob);
        
        resultDiv.innerHTML = `
          <div class="result-image-container">
            <img src="${imageUrl}" width="512" class="generated-image">
          </div>
        `;
        sendButton.style.display = 'block';
        
      } catch (error) {
        resultDiv.innerHTML = `<div class="error-message">Error: ${error.message}</div>`;
        console.error("Generation failed:", error);
      }
    }
    
   async function sendToProject() {
  if (!generatedImageBlob) {
    alert('Please generate an image first!');
    return;
  }

  if (!projectID) {
    alert('Project ID is missing!');
    return;
  }

  const formData = new FormData();
  formData.append('image', generatedImageBlob, 'design.png');
  formData.append('projectID', projectID);
  formData.append('prompt', document.getElementById('prompt').value);
  
  try {
    const response = await fetch("save_image.php", {
      method: "POST",
      body: formData
    });
    
    const result = await response.json();
    
    if (response.ok) {
      alert('Design successfully sent to your contractor!');
    } else {
      throw new Error(result.message || 'Failed to send design');
    }
  } catch (error) {
    alert('Error: ' + error.message);
    console.error('Error:', error);
  }
}
  </script>
  <!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
