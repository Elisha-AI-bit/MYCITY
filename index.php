<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Fetch Categories
$stmt = $pdo->query("SELECT * FROM categories LIMIT 6");
$categories = $stmt->fetchAll();

// Fetch Latest Services
$stmt = $pdo->query("
    SELECT s.*, c.name as category_name 
    FROM services s
    JOIN categories c ON s.category_id = c.id
    WHERE s.status = 'active'
    ORDER BY s.created_at DESC
    LIMIT 3
");
$latest_services = $stmt->fetchAll();

$pageTitle = "Home";
require_once 'partials/header.php';
?>

<main>
    <!-- Hero Section -->
    <section
        style="padding: 6rem 0; background: linear-gradient(135deg, rgba(13, 148, 136, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);">
        <div class="container" style="text-align: center;">
            <h1
                style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1.5rem; background: linear-gradient(to right, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Find & Book Local Services Instantly
            </h1>
            <p style="font-size: 1.25rem; color: var(--text-muted); max-width: 700px; margin: 0 auto 2.5rem;">
                The smartest way to discover reliable plumbers, electricians, cleaners, and more in your city.
            </p>

            <form action="services.php" method="GET" class="card glass"
                style="max-width: 800px; margin: 0 auto; padding: 1rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <div
                    style="flex: 1; min-width: 200px; display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; border-right: 1px solid var(--border-color);">
                    <i data-lucide="search" style="color: var(--text-muted);"></i>
                    <input type="text" name="q" placeholder="What service do you need?"
                        style="border: none; background: transparent; width: 100%; outline: none; font-size: 1rem;">
                </div>
                <div
                    style="flex: 1; min-width: 200px; display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem;">
                    <i data-lucide="map-pin" style="color: var(--text-muted);"></i>
                    <input type="text" name="loc" placeholder="Your City"
                        style="border: none; background: transparent; width: 100%; outline: none; font-size: 1rem;">
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem;">Search</button>
            </form>
        </div>
    </section>

    <!-- Latest Services Section -->
    <?php if (!empty($latest_services)): ?>
        <section style="padding: 5rem 0; background: var(--bg-white);">
            <div class="container">
                <div style="text-align: center; margin-bottom: 3rem;">
                    <h2 style="font-size: 2rem; font-weight: 700;">Featured Services</h2>
                    <p style="color: var(--text-muted);">Handpicked services just for you</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    <?php foreach ($latest_services as $s): ?>
                        <div class="card" style="padding: 0; overflow: hidden; transition: var(--transition);">
                            <img src="<?php echo BASE_URL; ?>assets/img/services/<?php echo $s['image']; ?>"
                                onerror="this.src='https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=400&auto=format'"
                                style="width: 100%; height: 200px; object-fit: cover;">
                            <div style="padding: 1.5rem;">
                                <span
                                    style="font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase;"><?php echo $s['category_name']; ?></span>
                                <h3 style="font-size: 1.25rem; font-weight: 700; margin-top: 0.5rem;"><?php echo $s['name']; ?>
                                </h3>
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                                    <span
                                        style="font-size: 1.25rem; font-weight: 800; color: var(--primary);"><?php echo formatCurrency($s['price']); ?>/hr</span>
                                    <a href="service-details.php?id=<?php echo $s['id']; ?>" class="btn btn-primary"
                                        style="padding: 0.5rem 1.25rem;">Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Categories Section -->
    <section style="padding: 5rem 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
                <div>
                    <h2 style="font-size: 2rem; font-weight: 700;">Browse Categories</h2>
                    <p style="color: var(--text-muted);">Commonly requested services in your area</p>
                </div>
                <a href="#"
                    style="color: var(--primary); font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    View all <i data-lucide="arrow-right" style="width: 18px;"></i>
                </a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem;">
                <?php foreach ($categories as $cat): ?>
                    <a href="services.php?category=<?php echo $cat['id']; ?>" class="card"
                        style="text-align: center; transition: var(--transition);">
                        <div
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: var(--primary); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; opacity: 0.9;">
                            <i data-lucide="<?php echo $cat['icon']; ?>"></i>
                        </div>
                        <h3 style="font-size: 1.125rem; font-weight: 600;"><?php echo $cat['name']; ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'partials/footer.php'; ?>