<!DOCTYPE html>
<html class="no-js" lang="tr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Thememarch" />
    <link rel="icon" href="assets/img/favicon.svg" />
    <title>Galeri – Celaloğlu Defransiyel Antalya</title>
    <link rel="stylesheet" href="assets/css/plugins/lightgallery.min.css" />
    <link rel="stylesheet" href="assets/css/plugins/swiper.min.css" />
    <link rel="stylesheet" href="assets/css/plugins/aos.css" />
    <link rel="stylesheet" href="assets/css/plugins/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <style>
        /* Video thumbnail overlay */
        .item.video-item { position: relative; }
        .video-play-icon {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 52px; height: 52px;
            background: rgba(232,184,75,0.92);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            pointer-events: none;
            z-index: 2;
        }
        .video-play-icon svg { margin-left: 4px; }

        /* Lightbox video player */
        .video-modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.92);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .video-modal-overlay.active { display: flex; }
        .video-modal-inner { position: relative; width: 90%; max-width: 900px; }
        .video-modal-inner video { width: 100%; border-radius: 10px; outline: none; }
        .video-modal-close {
            position: absolute;
            top: -40px; right: 0;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
            background: none;
            border: none;
            line-height: 1;
        }

        /* Boş galeri */
        .gallery-empty {
            text-align: center;
            padding: 80px 20px;
            color: #888;
        }
        .gallery-empty h3 { font-size: 20px; margin-bottom: 10px; }
        .gallery-empty p  { font-size: 14px; }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div id="preloader" class="preloader-content">
        <div class="loading-window">
           <img src="assets/img/yükleme_ikon.png" alt="Yükleniyor...">
        </div>
    </div>

    <!-- Start Header Section -->
    <header class="ak-site_header ak-style1 ak-sticky_header">
        <div class="ak-main_header">
            <div class="container">
                <div class="ak-main_header_in">
                    <div class="ak-main-header-left">
                         <a class="ak-site_branding" href="index.html">
                            <img src="assets/img/logo.png" alt="logo" />
                        </a>
                    </div>
                    <div class="ak-main-header-center">
                        <div class="ak-nav ak-medium">
                            <ul class="ak-nav_list">
                                <li><a href="index.html" class="text-hover-animaiton">Ana&nbsp;Sayfa</a></li>
                                <li><a href="about.html" class="text-hover-animaiton">Hakkımızda</a></li>
                                <li class="menu-item-has-children">
                                    <a href="services.html" class="text-hover-animaiton">Hizmetlerimiz</a>
                                    <ul>
                                        <li><a href="services-single.html" class="text-hover-animaiton">Defransiyel Servisi</a></li>
                                        <li><a href="services-single.html" class="text-hover-animaiton">Şanzuman Servisi</a></li>
                                        <li><a href="services-single.html" class="text-hover-animaiton">Yedek Parça</a></li>
                                    </ul>
                                </li>
                                <li><a href="gallery.php" class="text-hover-animaiton">Galeri</a></li>
                                <li><a href="contact-us.html" class="text-hover-animaiton">İletişim</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ak-main-header-right">
                        <a href="tel:0(530) 308 82 85">
                            <div class="d-flex align-items-center gap-3">
                                <div class="heartbeat-icon">
                                    <span class="ak-heartbeat-btn"><img src="assets/img/phone.svg" alt="..." /></span>
                                </div>
                                <h6>0(530) 308 82 85</h6>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-bar-border"></div>
    </header>
    <!-- End Header Section -->

    <div class="ak-height-125 ak-height-lg-80"></div>
    <div class="container">
        <div class="common-page-title">
            <h3 class="page-title">Galeri</h3>
        </div>
        <div class="primary-color-border"></div>
    </div>

    <!-- Start Gallery -->
    <div class="ak-height-75 ak-height-lg-80"></div>
    <section class="container">

        <?php
        $uploadDir  = __DIR__ . '/uploads/';
        $uploadUrl  = 'uploads/';
        $imageExts  = ['jpg','jpeg','png','gif','webp'];
        $videoExts  = ['mp4','webm','ogg'];

        $files = [];
        if (is_dir($uploadDir)) {
            foreach (scandir($uploadDir) as $file) {
                if ($file === '.' || $file === '..') continue;
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $imageExts) || in_array($ext, $videoExts)) {
                    $files[] = $file;
                }
            }
            // En yeniden eskiye sırala
            usort($files, fn($a,$b) => filemtime($uploadDir.$b) - filemtime($uploadDir.$a));
        }

        if (empty($files)):
        ?>
            <div class="gallery-empty">
                <h3>Henüz içerik eklenmedi</h3>
                <p>Yakında fotoğraf ve videolar burada görünecek.</p>
            </div>
        <?php else: ?>

        <div class="gallery" id="static-thumbnails">
            <?php foreach ($files as $file):
                $ext    = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $isImg  = in_array($ext, $imageExts);
                $isVid  = in_array($ext, $videoExts);
                $filePath = $uploadUrl . $file;
            ?>

            <?php if ($isImg): ?>
            <div class="item">
                <a href="<?= htmlspecialchars($filePath) ?>">
                    <img src="<?= htmlspecialchars($filePath) ?>" alt="Galeri" />
                    <div class="frame gallery-hover-icon">
                        <span><img src="assets/img/zoom.svg" alt="zoom"></span>
                    </div>
                </a>
            </div>

            <?php elseif ($isVid): ?>
            <div class="item video-item" onclick="openVideo('<?= htmlspecialchars($filePath) ?>')">
                <video src="<?= htmlspecialchars($filePath) ?>" muted preload="metadata"
                       style="width:100%;height:100%;object-fit:cover;display:block;cursor:pointer;"></video>
                <div class="video-play-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#000">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
                <div class="frame gallery-hover-icon">
                    <span><img src="assets/img/zoom.svg" alt="oynat"></span>
                </div>
            </div>
            <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <?php endif; ?>

    </section>

    <!-- Video Modal -->
    <div class="video-modal-overlay" id="videoModal" onclick="closeVideo(event)">
        <div class="video-modal-inner">
            <button class="video-modal-close" onclick="closeVideoBtn()">✕</button>
            <video id="modalVideo" controls></video>
        </div>
    </div>
    <!-- End Gallery -->

    <!-- Start Footer -->
    <div class="ak-height-125 ak-height-lg-80"></div>
    <footer class="footer style-1 footer-bg">
        <div class="container">
            <div class="ak-height-40 ak-height-lg-60"></div>
            <div class="footer-email" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="100" data-aos-offset="0">
                <div class="background-text" data-aos="fade-left" data-aos-delay="200" data-aos-duration="1000">DENGE</div>
                <div class="footer-heading-email">
                    <h5 class="email-title">Gücün dengesi CELALOĞLU DEFRANSİYEL'DE kurulur.</h5>
                </div>
            </div>
            <div class="ak-height-70 ak-height-lg-30"></div>
            <div class="primary-color-border"></div>
            <div class="ak-height-35 ak-height-lg-30"></div>
            <div class="footer-content">
                <div class="footer-info" data-aos="fade-up">
                    <p class="desp">Diferansiyelde doğru adres. Ses değil Güç duyulsun.</p>
                    <div class="ak-height-35 ak-height-lg-30"></div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="heartbeat-icon">
                            <a href="tel:(530) 308-8285">
                                <span class="ak-heartbeat-btn"><img src="assets/img/phone.svg" alt="..." /></span>
                            </a>
                        </div>
                        <a href="tel:(530) 308-8285" class="phone text-hover-animaiton white">(530) 308-8285</a>
                    </div>
                </div>
                <div class="footer-menu-one" data-aos="fade-up" data-aos-delay="50" data-aos-duration="500">
                    <div class="footer-menu">
                        <p class="menu-title">Hızlı Menü</p>
                        <a href="about.html" class="menu-item text-hover-animaiton white">Hakkımızda</a>
                        <a href="services.html" class="menu-item text-hover-animaiton white">Servislerimiz</a>
                        <a href="about.html" class="menu-item text-hover-animaiton white">Ekibimiz</a>
                        <a href="contact-us.html" class="menu-item text-hover-animaiton white">İletişim</a>
                    </div>
                </div>
                <div class="footer-address" data-aos="fade-up" data-aos-delay="150" data-aos-duration="500">
                    <p class="adress-title">Konum & İletişim</p>
                    <a href="contact-us.html" class="location">
                        <span class="me-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="19" viewBox="0 0 15 19" fill="none">
                                <path d="M7.83533 0.501953C3.9756 0.501953 0.835327 3.4927 0.835327 7.16863C0.835327 8.27215 1.12502 9.36629 1.67574 10.3368L7.45253 18.2871C7.52943 18.4198 7.67598 18.502 7.83533 18.502C7.99467 18.502 8.14122 18.4198 8.21813 18.2871L13.9971 10.3335C14.5456 9.36629 14.8353 8.27211 14.8353 7.16859C14.8353 3.4927 11.6951 0.501953 7.83533 0.501953ZM7.83533 10.502C5.90546 10.502 4.33535 9.0066 4.33535 7.16863C4.33535 5.33066 5.90546 3.83531 7.83535 3.83531C9.76519 3.83531 11.3353 5.33066 11.3353 7.16863C11.3353 9.0066 9.76519 10.502 7.83533 10.502Z" fill="white"/>
                            </svg>
                        </span>
                        Kepez mh. Yeşil Antalya Sanayi Sitesi 5069 sk. NO:24
                    </a>
                    <a href="mailto:celalogludefransiyelantalya@gmail.com" class="email">
                        <span class="me-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="none">
                                <g clip-path="url(#clip0_365_2422)">
                                    <path d="M19.2194 16.3586C19.669 16.3586 20.0585 16.2102 20.3897 15.9171L14.7234 10.2505C14.5874 10.3479 14.4557 10.4426 14.3312 10.5326C13.9071 10.845 13.563 11.0888 13.2987 11.2635C13.0345 11.4386 12.6829 11.617 12.2441 11.7993C11.8049 11.9817 11.3958 12.0726 11.0163 12.0726H11.0051H10.994C10.6145 12.0726 10.2054 11.9817 9.76625 11.7993C9.32714 11.617 8.97557 11.4386 8.71159 11.2635C8.44736 11.0888 8.10338 10.845 7.67912 10.5326C7.56089 10.4459 7.42977 10.3508 7.28801 10.249L1.62061 15.9171C1.95173 16.2102 2.34153 16.3586 2.79106 16.3586H19.2194Z" fill="white"/>
                                    <path d="M2.1326 6.68302C1.7086 6.40034 1.33259 6.07659 1.00525 5.71191V14.3331L5.99952 9.33882C5.00038 8.64128 3.71304 7.75703 2.1326 6.68302Z" fill="white"/>
                                    <path d="M19.8892 6.68302C18.3691 7.71193 17.077 8.59771 16.013 9.34081L21.0053 14.3333V5.71191C20.6852 6.06925 20.3132 6.39278 19.8892 6.68302Z" fill="white"/>
                                    <path d="M19.2194 0.644531H2.79109C2.21796 0.644531 1.77732 0.83807 1.46864 1.22475C1.15971 1.61161 1.00549 2.09542 1.00549 2.67563C1.00549 3.1443 1.21014 3.65211 1.61926 4.19921C2.02838 4.74609 2.46371 5.17565 2.92505 5.48811C3.17795 5.6668 3.94063 6.19701 5.21308 7.07858C5.89998 7.55458 6.49734 7.96947 7.01067 8.3275C7.44822 8.63237 7.82556 8.89639 8.13711 9.11549C8.17288 9.14058 8.22912 9.18081 8.30378 9.23419C8.38421 9.29196 8.48599 9.36527 8.61155 9.45594C8.85335 9.63081 9.05422 9.77217 9.21421 9.88016C9.37398 9.98819 9.56755 10.1089 9.79464 10.2428C10.0216 10.3766 10.2356 10.4773 10.4364 10.5442C10.6374 10.6111 10.8233 10.6446 10.9944 10.6446H11.0055H11.0167C11.1877 10.6446 11.3737 10.6111 11.5747 10.5442C11.7755 10.4773 11.9894 10.3769 12.2165 10.2428C12.4433 10.1089 12.6366 9.98793 12.7969 9.88016C12.9569 9.77217 13.1578 9.63085 13.3996 9.45594C13.5249 9.36527 13.6267 9.29192 13.7071 9.23437C13.7818 9.18077 13.838 9.1408 13.874 9.11549C14.1167 8.9466 14.4949 8.68367 15.0034 8.33059C15.9287 7.6877 17.2914 6.74146 19.0972 5.48811C19.6403 5.10877 20.0941 4.65099 20.4588 4.11544C20.8228 3.57989 21.0053 3.01812 21.0053 2.43031C21.0053 1.93921 20.8283 1.51898 20.4752 1.16897C20.1217 0.819406 19.703 0.644531 19.2194 0.644531Z" fill="white"/>
                                </g>
                                <defs><clipPath id="clip0_365_2422"><rect width="21" height="17" fill="white" transform="translate(0.835327 0.238281)"/></clipPath></defs>
                            </svg>
                        </span>
                        celalogludefransiyelantalya@gmail.com
                    </a>
                    <p class="date">
                        <span class="me-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <g clip-path="url(#clip0_365_2435)">
                                    <path d="M2.00201 17.6689C2.00254 18.1108 2.17831 18.5344 2.49075 18.8469C2.8032 19.1593 3.22682 19.3351 3.66868 19.3356H19.002C19.4439 19.3351 19.8675 19.1593 20.1799 18.8469C20.4924 18.5344 20.6682 18.1108 20.6687 17.6689V4.66895H2.00201V17.6689Z" fill="white"/>
                                    <path d="M19.002 2.00195H3.66868C3.22682 2.00248 2.8032 2.17825 2.49075 2.49069C2.17831 2.80314 2.00254 3.22675 2.00201 3.66862V4.00195H20.6687V3.66862C20.6682 3.22675 20.4924 2.80314 20.1799 2.49069C19.8675 2.17825 19.4439 2.00248 19.002 2.00195Z" fill="white"/>
                                </g>
                                <defs><clipPath id="clip0_365_2435"><rect width="20" height="20" fill="white" transform="translate(0.835327 0.00195312)"/></clipPath></defs>
                            </svg>
                        </span>
                        Pazartesi-Cumartesi 8.00-18.00 arası açığız
                    </p>
                </div>
            </div>
            <div class="ak-height-70 ak-height-lg-30"></div>
            <div class="primary-color-border"></div>
            <div class="copy-right">
                <p class="title text-hover-animaiton">© 2026 Celaloğlu Defransiyel Antalya. Tüm Hakları Saklıdır.</p>
                <div class="social-icon">
                    <a href="https://www.facebook.com/profile.php?id=61580683929251&locale=tr_TR">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                            <g clip-path="url(#clip0_365_2385)">
                                <path d="M9.2381 16.9756L9.2381 9.67777L11.6867 9.67777L12.0541 6.83284H9.2381V5.01676C9.2381 4.19335 9.46582 3.6322 10.6479 3.6322L12.1532 3.63158V1.08697C11.8929 1.05314 10.9993 0.975586 9.95931 0.975586C7.78764 0.975586 6.30087 2.30116 6.30087 4.735L6.30087 6.83284H3.84485L3.84485 9.67777H6.30087L6.30087 16.9756H9.2381Z" fill="white"/>
                            </g>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/celalogludefransiyelantalya/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                            <g clip-path="url(#clip0_365_2391)">
                                <path d="M15.9833 4.94233C15.9459 4.09218 15.8084 3.50772 15.6114 3.00127C15.4083 2.46369 15.0957 1.9824 14.6862 1.58229C14.2861 1.17596 13.8016 0.860229 13.2703 0.660239C12.7609 0.463301 12.1795 0.325823 11.3294 0.28834C10.4729 0.247682 10.201 0.238281 8.02866 0.238281C5.85636 0.238281 5.58446 0.247682 4.73114 0.285165C3.881 0.322648 3.29654 0.460248 2.79021 0.657064C2.25251 0.860229 1.77121 1.17279 1.37111 1.58229C0.964783 1.9824 0.649169 2.46687 0.449057 2.99822C0.252119 3.50772 0.114641 4.08901 0.0771582 4.93915C0.0365009 5.79564 0.0270996 6.06754 0.0270996 8.23984C0.0270996 10.4121 0.0365009 10.684 0.0739838 11.5374C0.111467 12.3875 0.249067 12.972 0.446005 13.4784C0.649169 14.016 0.964783 14.4973 1.37111 14.8974C1.77121 15.3037 2.25568 15.6195 2.78704 15.8195C3.29654 16.0164 3.87783 16.1539 4.72809 16.1913C5.58129 16.229 5.85331 16.2382 8.02561 16.2382C10.1979 16.2382 10.4698 16.229 11.3231 16.1913C12.1733 16.1539 12.7577 16.0164 13.2641 15.8195C14.3393 15.4037 15.1895 14.5536 15.6052 13.4784C15.802 12.9689 15.9396 12.3875 15.9771 11.5374C16.0146 10.684 16.024 10.4121 16.024 8.23984C16.024 6.06754 16.0208 5.79564 15.9833 4.94233Z" fill="white"/>
                                <path d="M8.02864 4.12988C5.75951 4.12988 3.91846 5.97082 3.91846 8.24006C3.91846 10.5093 5.75951 12.3502 8.02864 12.3502C10.2979 12.3502 12.1388 10.5093 12.1388 8.24006C12.1388 5.97082 10.2979 4.12988 8.02864 4.12988ZM8.02864 10.9062C6.55655 10.9062 5.36246 9.71227 5.36246 8.24006C5.36246 6.76785 6.55655 5.57389 8.02864 5.57389C9.50085 5.57389 10.6948 6.76785 10.6948 8.24006C10.6948 9.71227 9.50085 10.9062 8.02864 10.9062Z" fill="white"/>
                                <path d="M13.261 3.96735C13.261 4.49724 12.8313 4.92689 12.3013 4.92689C11.7714 4.92689 11.3418 4.49724 11.3418 3.96735C11.3418 3.43734 11.7714 3.00781 12.3013 3.00781C12.8313 3.00781 13.261 3.43734 13.261 3.96735Z" fill="white"/>
                            </g>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <span class="ak-scrollup">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 10L1.7625 11.7625L8.75 4.7875V20H11.25V4.7875L18.225 11.775L20 10L10 0L0 10Z" fill="currentColor"/>
        </svg>
    </span>

    <script src="assets/js/plugins/jquery-3.7.1.min.js"></script>
    <script src="assets/js/plugins/lightgallery.min.js"></script>
    <script src="assets/js/plugins/simplePagination.min.js"></script>
    <script src="assets/js/plugins/aos.js"></script>
    <script src="assets/js/plugins/swiper.min.js"></script>
    <script src="assets/js/plugins/SplitText.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        // Lightgallery sadece fotoğraflar için
        if (typeof lightGallery !== 'undefined') {
            lightGallery(document.getElementById('static-thumbnails'), {
                selector: '.item:not(.video-item) a'
            });
        }

        // Video modal
        function openVideo(src) {
            document.getElementById('modalVideo').src = src;
            document.getElementById('videoModal').classList.add('active');
        }
        function closeVideo(e) {
            if (e.target === document.getElementById('videoModal')) {
                closeVideoBtn();
            }
        }
        function closeVideoBtn() {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('modalVideo');
            modal.classList.remove('active');
            video.pause();
            video.src = '';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeVideoBtn();
        });
    </script>
</body>
</html>
