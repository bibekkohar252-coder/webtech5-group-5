<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$hiddenFile = __DIR__ . '/../data/hidden_programmes.json';
$hidden = file_exists($hiddenFile) ? json_decode(file_get_contents($hiddenFile), true) : [];

$sql = "SELECT p.*, l.LevelName, s.Name as LeaderName 
        FROM Programmes p
        LEFT JOIN Levels l ON p.LevelID = l.LevelID
        LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
        WHERE 1=1";
if (!empty($hidden)) {
    $placeholders = implode(',', array_fill(0, count($hidden), '?'));
    $sql .= " AND p.ProgrammeID NOT IN ($placeholders)";
}
$sql .= " ORDER BY p.ProgrammeName LIMIT 6";
$stmt = $pdo->prepare($sql);
if (!empty($hidden)) {
    foreach ($hidden as $i => $hid) $stmt->bindValue($i+1, $hid, PDO::PARAM_INT);
}
$stmt->execute();
$programmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalProgrammes = $pdo->query("SELECT COUNT(*) FROM Programmes")->fetchColumn();
$totalModules = $pdo->query("SELECT COUNT(*) FROM Modules")->fetchColumn();
$totalStaff = $pdo->query("SELECT COUNT(*) FROM Staff")->fetchColumn();

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <div class="hero-badge" data-aos="fade-up">
            <i class="fas fa-graduation-cap"></i> Top-Ranked University 2024
        </div>
        <h1 data-aos="fade-up" data-aos-delay="100">Shape Your Future at <span>Gorkha Institute<br>of Technology</span></h1>
        <p data-aos="fade-up" data-aos-delay="200">Discover world‑class programmes, learn from industry experts, and launch your career in technology.</p>
        
        <div class="hero-search-section" data-aos="fade-up" data-aos-delay="300">
            <form action="programmes.php" method="get" class="modern-search">
                <span class="search-icon"><i class="fas fa-search"></i></span>
                <input type="text" name="search" placeholder="What do you want to study? e.g., Computer Science, Cyber Security...">
                <button type="submit" class="search-btn">Find Courses <i class="fas fa-arrow-right"></i></button>
            </form>
            <div class="popular-searches">
                <span><i class="fas fa-fire"></i> Popular:</span>
                <a href="programmes.php?search=Computer Science">Computer Science</a>
                <a href="programmes.php?search=Cyber Security">Cyber Security</a>
                <a href="programmes.php?search=Artificial Intelligence">Artificial Intelligence</a>
                <a href="programmes.php?search=Data Science">Data Science</a>
            </div>
        </div>
        
        <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
            <a href="programmes.php?level=1" class="btn-outline"><i class="fas fa-graduation-cap"></i> Undergraduate</a>
            <a href="programmes.php?level=2" class="btn-outline"><i class="fas fa-university"></i> Postgraduate</a>
            <a href="#gallery" class="btn-outline virtual-tour-btn"><i class="fas fa-video"></i> Virtual Tour</a>
        </div>
    </div>
    <div class="scroll-indicator" data-aos="fade-up" data-aos-delay="500">
        <span>Scroll to explore</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<div class="stats">
    <div class="stat" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-number" id="programmeCount">0</div>
        <div class="stat-label">Programmes</div>
    </div>
    <div class="stat" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-number" id="moduleCount">0</div>
        <div class="stat-label">Modules</div>
    </div>
    <div class="stat" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-number" id="staffCount">0</div>
        <div class="stat-label">Expert Faculty</div>
    </div>
</div>

<section class="programmes-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Explore Our Programmes</h2>
            <p class="section-subtitle">Choose from undergraduate and postgraduate degrees designed for the future</p>
        </div>
        <div class="programme-grid">
            <?php 
            $images = [
                'https://images.unsplash.com/photo-1581091226033-d5c48150dbaa?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1580894732444-8ecded7900cd?w=400&h=250&fit=crop'
            ];
            $delay = 0;
            $index = 0;
            foreach ($programmes as $p): 
                $image = $p['Image'] ?: $images[$index % count($images)];
                $index++;
                $delay += 100;
            ?>
                <div class="programme-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                    <div class="card-image" style="background-image: url('<?= htmlspecialchars($image) ?>');">
                        <div class="card-overlay"></div>
                    </div>
                    <div class="card-content">
                        <span class="badge"><?= htmlspecialchars($p['LevelName']) ?></span>
                        <h3><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
                        <p><?= htmlspecialchars(substr($p['Description'], 0, 100)) ?>…</p>
                        <a href="programme.php?id=<?= $p['ProgrammeID'] ?>" class="card-link">
                            Learn more <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="view-all" data-aos="fade-up">
            <a href="programmes.php" class="btn-outline">View All Programmes <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<section class="why-choose-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Why Choose GIT?</h2>
            <p class="section-subtitle">Experience education that transforms lives</p>
        </div>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon"><i class="fas fa-chalkboard-user"></i></div>
                <h3>Expert Faculty</h3>
                <p>Learn from industry professionals with years of real-world experience</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon"><i class="fas fa-microchip"></i></div>
                <h3>Cutting-Edge Curriculum</h3>
                <p>Stay ahead with modern technology-focused programmes</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon"><i class="fas fa-building"></i></div>
                <h3>Modern Facilities</h3>
                <p>State-of-the-art labs and learning environments</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon"><i class="fas fa-globe"></i></div>
                <h3>Global Community</h3>
                <p>Join students from over 100 countries worldwide</p>
            </div>
        </div>
    </div>
</section>

<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Campus Life</h2>
            <p class="section-subtitle">Experience life at Gorkha Institute of Technology</p>
        </div>
        <div class="gallery-grid">
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                <img src="https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg?w=400&h=300&fit=crop" alt="Modern Library">
                <div class="gallery-overlay"><h4>Modern Library</h4><p>24/7 access to thousands of resources</p></div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                <img src="https://images.pexels.com/photos/1181359/pexels-photo-1181359.jpeg?w=400&h=300&fit=crop" alt="Tech Lab">
                <div class="gallery-overlay"><h4>Advanced Tech Labs</h4><p>State-of-the-art equipment</p></div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                <img src="https://images.pexels.com/photos/207691/pexels-photo-207691.jpeg?w=400&h=300&fit=crop" alt="Study Area">
                <div class="gallery-overlay"><h4>Study Spaces</h4><p>Collaborative learning areas</p></div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
                <img src="https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?w=400&h=300&fit=crop" alt="Graduation">
                <div class="gallery-overlay"><h4>Graduation Ceremony</h4><p>Celebrate your success</p></div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                <img src="https://images.pexels.com/photos/1181263/pexels-photo-1181263.jpeg?w=400&h=300&fit=crop" alt="Sports">
                <div class="gallery-overlay"><h4>Sports Facilities</h4><p>Stay active and healthy</p></div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="600">
                <img src="https://images.pexels.com/photos/159844/cellular-education-school-159844.jpeg?w=400&h=300&fit=crop" alt="Students">
                <div class="gallery-overlay"><h4>Student Life</h4><p>Vibrant campus community</p></div>
            </div>
        </div>
        <div class="gallery-cta" data-aos="fade-up"><a href="#" class="btn-outline"><i class="fas fa-camera"></i> Explore More Campus Photos</a></div>
    </div>
</section>

<section class="testimonials-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>What Our Students Say</h2>
            <p class="section-subtitle">Real experiences from real students</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
                <p class="testimonial-text">"The faculty at GIT is exceptional. The hands-on learning approach and industry connections helped me land my dream job before graduation."</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Student"></div>
                    <div class="author-info"><h4>Priya Sharma</h4><span>BSc Computer Science, 2024</span><div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></div>
                </div>
            </div>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
                <p class="testimonial-text">"The modern labs and cutting-edge curriculum prepared me for the real world. The professors are always available to help and guide."</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Student"></div>
                    <div class="author-info"><h4>Rahul Verma</h4><span>MSc AI, 2024</span><div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></div>
                </div>
            </div>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
                <p class="testimonial-text">"The campus environment is amazing! Great facilities, supportive staff, and a vibrant student community. Proud to be a GIT student!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Student"></div>
                    <div class="author-info"><h4>Anjali Thapa</h4><span>BSc Cyber Security, 2025</span><div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="events-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Upcoming Events</h2>
            <p class="section-subtitle">Don't miss these important dates</p>
        </div>
        <div class="events-grid">
            <div class="event-card" data-aos="fade-up" data-aos-delay="100">
                <div class="event-date"><span class="event-day">30</span><span class="event-month">MAR</span></div>
                <div class="event-details"><h3>Open Day 2025</h3><p>Visit our campus, meet faculty, and explore facilities</p><div class="event-time"><i class="fas fa-clock"></i> 10:00 AM - 4:00 PM</div><div class="event-location"><i class="fas fa-map-marker-alt"></i> Main Campus</div></div>
                <a href="#" class="event-btn">Register</a>
            </div>
            <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                <div class="event-date"><span class="event-day">15</span><span class="event-month">APR</span></div>
                <div class="event-details"><h3>Application Deadline</h3><p>Last date to submit applications for Fall 2025 intake</p><div class="event-time"><i class="fas fa-clock"></i> 11:59 PM</div><div class="event-location"><i class="fas fa-globe"></i> Online</div></div>
                <a href="#" class="event-btn">Apply Now</a>
            </div>
            <div class="event-card" data-aos="fade-up" data-aos-delay="300">
                <div class="event-date"><span class="event-day">05</span><span class="event-month">MAY</span></div>
                <div class="event-details"><h3>Webinar: AI Careers</h3><p>Learn about career opportunities in Artificial Intelligence</p><div class="event-time"><i class="fas fa-clock"></i> 2:00 PM - 3:30 PM</div><div class="event-location"><i class="fas fa-globe"></i> Online</div></div>
                <a href="#" class="event-btn">Join</a>
            </div>
        </div>
    </div>
</section>

<section class="news-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Latest News</h2>
            <p class="section-subtitle">Stay updated with GIT happenings</p>
        </div>
        <div class="news-grid">
            <div class="news-card" data-aos="fade-up" data-aos-delay="100"><div class="news-badge">Award</div><h3>GIT wins "Best Tech University" Award 2024</h3><p>Recognized for excellence in technology education and research innovation...</p><a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a></div>
            <div class="news-card" data-aos="fade-up" data-aos-delay="200"><div class="news-badge">Announcement</div><h3>New AI Research Lab Inaugurated</h3><p>State-of-the-art facility for artificial intelligence and machine learning research...</p><a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a></div>
            <div class="news-card" data-aos="fade-up" data-aos-delay="300"><div class="news-badge">Partnership</div><h3>Partnership with Google for Tech Education</h3><p>Collaboration to provide industry certifications and internships...</p><a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a></div>
        </div>
    </div>
</section>

<section class="quick-links-section">
    <div class="container">
        <div class="quick-links-grid">
            <div class="quick-link-card" data-aos="fade-up" data-aos-delay="100"><i class="fas fa-file-pdf"></i><h3>Download Prospectus</h3><p>Get detailed information about all programmes</p><a href="#" class="quick-link-btn">Download <i class="fas fa-download"></i></a></div>
            <div class="quick-link-card" data-aos="fade-up" data-aos-delay="200"><i class="fas fa-rupee-sign"></i><h3>Fee Structure</h3><p>View programme fees and scholarship options</p><a href="#" class="quick-link-btn">View Details <i class="fas fa-arrow-right"></i></a></div>
            <div class="quick-link-card" data-aos="fade-up" data-aos-delay="300"><i class="fas fa-trophy"></i><h3>Scholarships</h3><p>Explore merit-based and need-based scholarships</p><a href="#" class="quick-link-btn">Apply Now <i class="fas fa-arrow-right"></i></a></div>
            <div class="quick-link-card" data-aos="fade-up" data-aos-delay="400"><i class="fas fa-headset"></i><h3>Contact Admissions</h3><p>Get answers to your questions</p><a href="#" class="quick-link-btn">Contact Us <i class="fas fa-envelope"></i></a></div>
        </div>
    </div>
</section>

<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content" data-aos="fade-up">
            <div class="newsletter-icon"><i class="fas fa-envelope-open-text"></i></div>
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for programme updates, events, and deadlines</p>
            <form class="newsletter-form" method="post"><input type="email" placeholder="Enter your email address" required><button type="submit"><i class="fas fa-paper-plane"></i> Subscribe</button></form>
            <p class="newsletter-note">No spam, unsubscribe anytime.</p>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2 data-aos="fade-up">Ready to Begin Your Journey?</h2>
        <p data-aos="fade-up" data-aos-delay="100">Join Gorkha Institute of Technology and shape your future in technology</p>
        <a href="programmes.php" class="cta-button" data-aos="fade-up" data-aos-delay="200">Explore Programmes <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<script>
function animateCounter(element, start, end, duration) {
    if (!element) return;
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const currentValue = Math.floor(progress * (end - start) + start);
        element.textContent = currentValue;
        if (progress < 1) window.requestAnimationFrame(step);
        else element.textContent = end;
    };
    window.requestAnimationFrame(step);
}
document.addEventListener('DOMContentLoaded', function() {
    animateCounter(document.getElementById('programmeCount'), 0, <?= $totalProgrammes ?>, 1500);
    animateCounter(document.getElementById('moduleCount'), 0, <?= $totalModules ?>, 1500);
    animateCounter(document.getElementById('staffCount'), 0, <?= $totalStaff ?>, 1500);
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>