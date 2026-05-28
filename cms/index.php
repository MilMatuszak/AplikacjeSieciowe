<?php
if (is_front_page()) {
    get_template_part('front-page');
} elseif (is_home()) {
    get_template_part('archive');
} else {
    get_template_part('page');
}
?>