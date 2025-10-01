<?php get_header(); ?>

<?php
$args = array(
    'post_type' => 'especialidades',
    'orderby' => 'title',
    'order' => 'ASC',
);

$query = new WP_Query($args);
$titulos = [];

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $titulos[] = [
            'id'    => get_the_ID(),
            'title' => get_the_title()
        ];
    }
    wp_reset_postdata();
}
?>

<main class="seccion contenedor">

    <section class="hero bg-turqueza">
        <div class="left text-primary">
            <div>
                <?php if (get_field('titulo')) { ?>
                    <h1 class="tiny-lh"><?php the_field('titulo'); ?></h1>
                <?php } ?>

                <?php if (get_field('descripcion')) { ?>
                    <p class="p-hero"><?php the_field('descripcion'); ?></p>
                <?php } ?>
            </div>

            <!-- Buscador -->
            <?php
            $buscador = get_field('buscador');
            ?>

            <div class="buscador">
                <div class="form-group mg-b-2">
                    <label class="floating-label" for="search-input"><?php echo esc_html($buscador['nombre']['texto_interno']); ?></label>

                    <input type="text" id="search-input" />
                </div>

                <div class="form-group">
                    <label class="floating-label" for="search-select"><?php echo esc_html($buscador['especialidad']['texto_interno']); ?></label>

                    <select id="search-select">
                        <option value="" disabled selected hidden>
                        </option>

                        <?php foreach ($titulos as $item) : ?>
                            <option value="<?php echo esc_attr($item['title']); ?>">
                                <?php echo esc_html($item['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="right">
            <?php
            $imagen = get_field('imagen');
            if ($imagen) { ?>
                <?php echo wp_get_attachment_image($imagen, 'full', false, array('class' => 'imagen-hero')); ?>
            <?php } ?>
        </div>
    </section>

    <!-- Listado de Doctores -->
    <section class="doctores">

        <ul id="listado-doctores" class="listado-grid"> </ul> <!-- Renderizado de los Doctores -->

        <p id="sin-doctores" class="sin-doctores"></p> <!-- Renderizado descripción sin Doctores -->

        <?php
        $icono_izquierda = get_field('icono_izquierda', 'informacion-general');
        $icono_derecha = get_field('icono_derecha', 'informacion-general');
        ?>
        <div id="paginacion" class="paginacion">
            <button id="prev-page" title="Atrás">
                <?php
                if ($icono_izquierda) : ?>
                    <img src="<?php echo esc_url($icono_izquierda); ?>" alt="" aria-hidden="true" class="icono">
                <?php endif; ?>
            </button>
            <div>
                <span id="current-page"></span> /
                <span id="total-page"></span>
            </div>
            <button id="next-page" title="Siguiente">
                <?php
                if ($icono_derecha) : ?>
                    <img src="<?php echo esc_url($icono_derecha); ?>" alt="" aria-hidden="true" class="icono">
                <?php endif; ?>
            </button>
        </div>
    </section>
    <!-- Fin Listado de Doctores -->

</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hero Select
        const inputs = document.querySelectorAll("#search-select, #search-input");

        inputs.forEach(input => {
            const label = input.closest(".form-group").querySelector(".floating-label");

            function toggleLabel() {
                if (input.value.trim() !== "") {
                    label.classList.add("active");
                } else {
                    label.classList.remove("active");
                }
            }

            toggleLabel();

            input.addEventListener("input", toggleLabel);
            input.addEventListener("focus", () => label.classList.add("active"));
            input.addEventListener("blur", toggleLabel);
        });

        // Paginacion
        let paged = 1;
        let maxPages = 1;

        const listado = document.getElementById("listado-doctores");
        const vacio = document.getElementById("sin-doctores");
        const mensaje = "<?php echo esc_js(get_field('descripcion_sin_staff_medico', get_page_by_path('staff-medico')->ID)); ?>";
        const paginacion = document.getElementById("paginacion");
        const currentPage = document.getElementById("current-page");
        const totalPage = document.getElementById("total-page");
        const prevBtn = document.getElementById("prev-page");
        const nextBtn = document.getElementById("next-page");

        function cargarDoctores() {
            const data = new FormData();
            data.append('action', 'cargar_doctores');
            data.append('paged', paged);

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(data => {
                    const html = data.html;
                    maxPages = data.maxPages;

                    if (!html || html.trim() === '') {
                        listado.style.display = "none";
                        vacio.innerHTML = mensaje;
                        vacio.style.display = "flex";
                    } else {
                        listado.innerHTML = html;
                        listado.style.display = "grid";
                        paginacion.style.display = "flex";
                        vacio.style.display = "none";
                    }

                    currentPage.textContent = paged;
                    totalPage.textContent = maxPages;
                    prevBtn.disabled = paged <= 1;
                    nextBtn.disabled = paged >= maxPages;
                });
        }

        prevBtn.addEventListener("click", () => {
            if (paged > 1) {
                paged--;
                cargarDoctores();
            }
        });

        nextBtn.addEventListener("click", () => {
            if (paged < maxPages) {
                paged++;
                cargarDoctores();
            }
        });

        cargarDoctores();
    });
</script>

<?php get_footer(); ?>