<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <script> document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('login-form');
        const createAccountForm = document.getElementById('create-account-form');
    
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            // Implement login functionality here
            alert(`Logging in with email: ${email}`);
        });
    
        createAccountForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('create-email').value;
            const password = document.getElementById('create-password').value;
            const confirmPassword = document.getElementById('create-confirm-password').value;
            
            if (password === confirmPassword) {
                // Implement create account functionality here
                alert(`Creating account with email: ${email}`);
            } else {
                alert('Passwords do not match.');
            }
        });
    });
    </script>
    <div class="main">
        <?php include'header.php';
        ?>
     <header>
        <nav>
            <ul>
                <img src="image/Black illustrative Education Logo.png">StudentSuccessHub
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Create Account</a></li>
            </ul>
        </nav>
    </header> 

    <section id="home">
       <div class="home-1"><h7>StudentSuccessHub</h7><br>
        <p>
            Your One-Stop Solution for Assignment Help, Web Development, and More!
            At StudentSuccessHub, we are dedicated to providing top-notch services tailored to meet your needs. Whether you're a student seeking help with assignments or a business looking to establish a strong online presence, we've got you covered.
            
            <br><h6>Why Choose Us?</h6><br>
            Expertise Across Domains
            Our team of skilled professionals brings expertise in various fields,<br> ensuring that you receive high-quality assistance and solutions.</p>
       </div>
        </section>
    

    <section id="about">
        <h4>About Us</h4>
        <p>Hello! we  are StudentSuccessHub, a dedicated freelancer with a passion for helping businesses ,individual grow and  succeed in the digital world. With over 10 years of experience in Assingment help, Editing and proofreading, Technical Writting, and content writing, I bring a wealth of knowledge and creativity to every project. Let's work together to achieve your business goals and create something amazing!</p>
    </section>

    <section class="services">
        <h1>Our services</h1>
        <div class="box-container">
            <div class="box">
                <h2>Web Development</h2>
                <p>Creating responsive and modern websites tailored to your business needs, ensuring a strong online presence.</p>
            </div>
            <div class="box">
                <h2>Editing and proofreading</h2>
                <p>Our editing and proofreading services ensure that your written content is polished, professional, and error-free. We meticulously review and refine your documents to enhance clarity, coherence, and overall quality.</p>
            </div>
            <div class="box">
                <h2>Technical Writter</h2>
                <p>Our technical writing services are designed to convey complex information clearly and accurately. We specialize in creating comprehensive and user-friendly documentation that meets the specific needs of your audience..</p>
            </div>
            <div class="box">
                <h2>Content Writing</h2>
                <p>Providing high-quality, engaging content for blogs, websites, and social media to attract and retain your audience.</p>
            </div>
             <div class="box">
            <h2>Online quize</h2>
            <p>Getting low grades in your quiz? Worry no more,<br> our team can help you in completing those quizs</p>
            </div>
        </div>
    </section>

    <section id="contact">
        <h1 class="title">Contact</h1>
        <p>Feel free to get in touch if you'd like to discuss a project, request a quote, or just say hello. You can reach me at:</p>
       <div class="image"> <img src="image/icons8-phone-50.png">+2540757556131
       <div class="image"> <img src="image/icons8-email-64.png">kennie@gmail.com
    
    </div>
        
    </section>

    <footer>
    <marquee width="100%" direction="right" height="100px">
        <p> &copy; 2024 StudentSuccessHub Freelancer Website. All rights reserved</p>
        </marquee> 
    </footer>
</div>
"> <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/66a18e5fbecc2fed692a999b/1i3nbo20o';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
       <!--Start of Tawk.to Script-->

</body>
   
</body>
</html>
