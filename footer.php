<footer class="bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            <!-- Sección de Información de la Empresa -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-warning text-uppercase mb-4">Sobre Nosotros</h5>
                <p  class=".text-secondary-emphasis">En este sitio web podrás encontrar las mejores historias de la Biblia para adultos, jóvenes y niños, completamente ilustradas para que puedas disfrutar de leer.</p>
<!--                <ul class="list-inline mt-4 footer-menu">
                    <li class="list-inline-item"><a href="#" >Política de privacidad</a></li>
                    <li class="list-inline-item"><a href="#" >Términos de uso</a></li>
                </ul>-->
            </div>

            <!-- Sección de Enlaces Útiles -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-warning text-uppercase mb-4">Enlaces Útiles</h5>
                <?php
                    if (has_nav_menu('header-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'header-menu',
                            'container' => false,
                            'menu_class' => 'footer-menu list-unstyled',
                            'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                            'fallback_cb' => false
                        ));
                    }
                ?>
            </div>

            <!-- Sección de Contacto -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-warning text-uppercase mb-4">Contacto</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><span class="text-white fw-bold">Dirección: </span>Lima - Perú</li>
                    <li class="mb-2"><span class="text-white fw-bold">WhatsApp: </span>+51 ---------</li>
                    <li class="mb-2"><span class="text-white fw-bold">Email: </span>info@historiasdelabiblia.org</li>
                </ul>
            </div>

            <!-- Sección de Redes Sociales -->
            <div class="col-lg-3 col-md-6 mb-lg-0">
                <h5 class="text-warning text-uppercase mb-4">Síguenos</h5>
                <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white me-2"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <!-- Derechos de Autor y Aviso Legal -->
        <div class="border-top pt-4 mt-4 .text-secondary-emphasis">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">© 2019 - 2023 HistoriasDeLaBiblia. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small mb-0">Diseñado por <a href="https://agenciaoregoom.com/" target="_blank" class="text-decoration-none text-white fw-bold">AgenciaOregoom.com</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>



        <?php wp_footer(); ?>

    </body>
</html>

