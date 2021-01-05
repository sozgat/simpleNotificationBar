
<div class="wrap">
    <div class="th_wrapper">
        <div id="th_main">
            <div class="th_content">

                <form method="post" action="options.php">
                    <?php
                    settings_fields($this->option_group);
                    //Prints out all settings sections added to a particular settings page
                    do_settings_sections($this->menu_slug);
                    submit_button();
                    ?>
                </form>

            </div><!-- .th_content -->
        </div><!-- #th_main -->
    </div><!-- .th_wrapper -->
</div><!-- .wrap -->
