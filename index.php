<?php 

include('includes/header.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<style>

    .hero { 
        height: 80vh; 
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                    url('https://images.pexels.com/photos/189296/pexels-photo-189296.jpeg?auto=compress&cs=tinysrgb&w=1920'); 
        background-size: cover; 
        background-position: center; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: white; 
        text-align: center; 
    }
    .hero h1 { font-family: 'Playfair Display', serif; font-size: 4.5rem; margin: 0; letter-spacing: 4px; animation: fadeInDown 1.5s ease; }
    .hero p { font-size: 1.1rem; letter-spacing: 3px; text-transform: uppercase; opacity: 0.9; }

    .section { display: flex; align-items: center; padding: 100px 10%; gap: 60px; }
    .section:nth-child(even) { flex-direction: row-reverse; background: #f8f9fa; }
    
    .content { flex: 1; }
    .content h2 { font-family: 'Playfair Display', serif; font-size: 2.8rem; color: #121212; margin-bottom: 25px; position: relative; }
    .content h2::after { content: ''; display: block; width: 60px; height: 3px; background: #f39c12; margin-top: 10px; }
    .content p { line-height: 1.8; color: #555; font-size: 1.1rem; margin-bottom: 30px; }

    .image-box { 
        flex: 1.2; 
        border-radius: 12px; 
        overflow: hidden; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.15); 
        height: 480px; 
        position: relative;
    }
    
    .image-box img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
       
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); 
    }
    
    .image-box:hover img { 
        transform: scale(1.15);
    }

    .btn-gold { 
        background: #f39c12; 
        color: white; 
        padding: 15px 40px; 
        text-decoration: none; 
        display: inline-block; 
        font-weight: 600; 
        border-radius: 4px; 
        transition: 0.4s; 
        letter-spacing: 1px;
    }
    .btn-gold:hover { background: #d3830d; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3); }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .section { flex-direction: column !important; padding: 50px 5%; }
        .hero h1 { font-size: 2.5rem; }
    }
</style>

<div class="hero">
    <div>
        <p>Experience the Extraordinary</p>
        <h1>Beyond Luxury</h1>
    </div>
</div>

<div class="section">
    <div class="content">
        <h2>The Royal Suites</h2>
        <p>Our rooms are designed to be a sanctuary of peace and luxury. Featuring hand-picked furniture and state-of-the-art technology to ensure your stay is nothing short of perfect.</p>
        <a href="rooms.php" class="btn-gold">DISCOVER ROOMS</a>
    </div>
    <div class="image-box">
        <img src="https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Luxury Suite">
    </div>
</div>

<div class="section">
    <div class="content">
        <h2>Culinary Excellence</h2>
        <p>Savor exquisite dishes from our award-winning kitchen. Our chefs use only the freshest seasonal ingredients to create a menu that celebrates global flavors with a local touch.</p>
    </div>
    <div class="image-box">
        <img src="https://images.pexels.com/photos/941861/pexels-photo-941861.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Dining Experience">
    </div>
</div>

<div class="section">
    <div class="content">
        <h2>Serenity Spa</h2>
        <p>Escape the world and rediscover your inner balance. Our spa offers a wide range of holistic treatments, a steam room, and a heated indoor pool for your ultimate relaxation.</p>
    </div>
    <div class="image-box">
        <img src="https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Spa and Wellness Center">
    </div>
</div>

<?php 

include('includes/footer.php'); 
?>